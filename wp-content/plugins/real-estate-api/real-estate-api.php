<?php
/**
 * Plugin Name:       Real Estate API
 * Plugin URI:        https://art3msite.com
 * Description:       Provides a REST API for the Real Estate Objects plugin. Requires 'Real Estate Objects Test' and 'Advanced Custom Fields' plugins.
 * Version:           1.0.0
 * Author:            art3m
 * License:           GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Перевірка наявності залежностей
add_action('admin_init', 'real_estate_api_check_dependencies');
function real_estate_api_check_dependencies() {
    if (!is_plugin_active('advanced-custom-fields/acf.php') && !is_plugin_active('advanced-custom-fields-pro/acf.php')) {
        add_action('admin_notices', 'real_estate_api_acf_notice');
        deactivate_plugins(plugin_basename(__FILE__));
    }
    if (!post_type_exists('real_estate')) {
        add_action('admin_notices', 'real_estate_api_main_plugin_notice');
        deactivate_plugins(plugin_basename(__FILE__));
    }
}

function real_estate_api_acf_notice() {
    echo '<div class="error"><p>Плагін <strong>Real Estate API</strong> було деактивовано. Для його роботи потрібен плагін Advanced Custom Fields (ACF).</p></div>';
}

function real_estate_api_main_plugin_notice() {
    echo '<div class="error"><p>Плагін <strong>Real Estate API</strong> було деактивовано. Для його роботи потрібен основний плагін, що реєструє тип запису "real_estate".</p></div>';
}


class RealEstateApiPlugin {

    public function __construct() {
        // Реєстрація маршрутів REST API
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    /**
     * Register API routes for real estate objects.
     */
    public function register_api_routes() {
        $namespace = 'real-estate-api/v1';
        $resource_name = 'real_estate';

        // Маршрут для отримання колекції та створення нового об'єкта
        register_rest_route($namespace, '/' . $resource_name, array(
            array(
                'methods'             => WP_REST_Server::READABLE, // GET
                'callback'            => array($this, 'get_items'),
                'permission_callback' => '__return_true',
            ),
            array(
                'methods'             => WP_REST_Server::CREATABLE, // POST
                'callback'            => array($this, 'create_item'),
                'permission_callback' => array($this, 'manage_item_permissions_check'),
            ),
        ));

        // Маршрут для роботи з існуючим об'єктом
        register_rest_route($namespace, '/' . $resource_name . '/(?P<id>[\d]+)', array(
            'args' => array( 'id' => array('description' => 'Унікальний ідентифікатор об\'єкта.', 'type' => 'integer')),
            array(
                'methods'             => WP_REST_Server::READABLE, // GET
                'callback'            => array($this, 'get_item'),
                'permission_callback' => '__return_true',
            ),
            array(
                'methods'             => WP_REST_Server::EDITABLE, // PUT/PATCH
                'callback'            => array($this, 'update_item'),
                'permission_callback' => array($this, 'manage_item_permissions_check'),
            ),
            array(
                'methods'             => WP_REST_Server::DELETABLE, // DELETE
                'callback'            => array($this, 'delete_item'),
                'permission_callback' => array($this, 'manage_item_permissions_check'),
            ),
        ));
    }

    public function manage_item_permissions_check($request) {
        if (!current_user_can('edit_posts')) {
            return new WP_Error('rest_forbidden', 'Вибачте, у вас немає прав для виконання цієї операції.', array('status' => 401));
        }
        return true;
    }
    
    public function get_items($request) {
        $args = ['post_type' => 'real_estate', 'posts_per_page' => -1, 'post_status' => 'publish'];
        $params = $request->get_params();

        // Фільтрація
        if (!empty($params['district'])) {
            $args['tax_query'][] = ['taxonomy' => 'district', 'field' => 'slug', 'terms' => sanitize_text_field($params['district'])];
        }
        if (!empty($params['building_type'])) {
            $args['meta_query'][] = ['key' => 'building_type', 'value' => sanitize_text_field($params['building_type']), 'compare' => '='];
        }

        $query = new WP_Query($args);
        $data = [];

        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $item_data = $this->prepare_item_for_response($post, $request);
                $data[] = $item_data->get_data();
            }
        }
        return new WP_REST_Response($data, 200);
    }
    
    public function get_item($request) {
        $post = get_post((int) $request['id']);
        if (empty($post) || $post->post_type !== 'real_estate') {
            return new WP_Error('rest_post_not_found', 'Об\'єкт не знайдено.', array('status' => 404));
        }
        return $this->prepare_item_for_response($post, $request);
    }

    public function create_item($request) {
        $params = $request->get_params();
        if (empty($params['title'])) {
            return new WP_Error('rest_missing_title', 'Заголовок (title) є обов\'язковим.', array('status' => 400));
        }
        
        $post_data = [
            'post_type' => 'real_estate', // Double-check this typo!
            'post_title' => sanitize_text_field($params['title']),
            'post_content' => isset($params['content']) ? wp_kses_post($params['content']) : '',
            'post_status' => 'publish'
        ];
        
        $post_id = wp_insert_post($post_data, true);
        if (is_wp_error($post_id)) return $post_id;
        
        $this->update_custom_data($post_id, $params);
        
        // Get the post directly instead of through get_item()
        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error('rest_creation_failed', 'Об\'єкт створено, але не вдалося отримати дані.', array('status' => 500));
        }
        
        $response = $this->prepare_item_for_response($post, $request);
        $response->set_status(201);
        return $response;
    }

    public function update_item($request) {
        $id = (int) $request['id'];
        $post = get_post($id);
        
        // Validate post exists and is correct type
        if (empty($post) || $post->post_type !== 'real_estate') {
            return new WP_Error('rest_post_not_found', 'Об\'єкт не знайдено.', ['status' => 404]);
        }

        $params = $request->get_params();
        $post_data = ['ID' => $id];

        // Update core post fields
        if (isset($params['title'])) {
            $post_data['post_title'] = sanitize_text_field($params['title']);
        }
        if (isset($params['content'])) {
            $post_data['post_content'] = wp_kses_post($params['content']);
        }

        // Update the post
        $updated = wp_update_post($post_data, true);
        if (is_wp_error($updated)) {
            return $updated;
        }

        // Update custom fields
        $this->update_custom_data($id, $params);

        // Return updated post data (without new HTTP request)
        $post = get_post($id);
        return $this->prepare_item_for_response($post, $request);
    }

    public function delete_item($request) {
        $id = (int) $request['id'];
        $post = get_post($id);

        // Validate post exists and is correct type
        if (empty($post) || $post->post_type !== 'real_estate') {
            return new WP_Error('rest_post_not_found', 'Об\'єкт не знайдено.', ['status' => 404]);
        }

        // Force delete (skip trash)
        $result = wp_delete_post($id, true);

        if (!$result) {
            return new WP_Error(
                'rest_cannot_delete', 
                'Не вдалося видалити об\'єкт.', 
                ['status' => 500]
            );
        }

        return new WP_REST_Response([
            'message' => 'Об\'єкт успішно видалено.',
            'id' => $id
        ], 200);
    }
    
    private function update_custom_data($post_id, $params) {
        // Validate post exists first
        if (!get_post($post_id)) return false;

        $acf_fields = [
            'building_name' => 'text',
            'coordinates' => 'array',
            'floors_count' => 'number',
            'building_type' => 'text',
            'eco_rating' => 'text',
            'building_image' => 'image',
            'rooms' => 'array'
        ];

        foreach ($acf_fields as $field => $type) {
            if (isset($params[$field])) {
                $value = $this->sanitize_field($params[$field], $type);
                update_field($field, $value, $post_id);
            }
        }

        if (isset($params['district_ids'])) {
            $term_ids = array_filter(array_map('intval', (array) $params['district_ids']));
            if (!empty($term_ids)) {
                wp_set_post_terms($post_id, $term_ids, 'district', false);
            }
        }
        
        return true;
    }

    private function sanitize_field($value, $type) {
        switch ($type) {
            case 'number':
                return (int) $value;
            case 'array':
                return (array) $value;
            case 'image':
                return attachment_url_to_postid($value) ?: $value;
            default:
                return sanitize_text_field($value);
        }
    }


    public function prepare_item_for_response($post, $request) {
        $post_data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'slug' => $post->post_name,
            'link' => get_permalink($post->ID),
            'acf' => get_fields($post->ID),
            'districts' => get_the_terms($post->ID, 'district'),
        ];
        return new WP_REST_Response($post_data, 200);
    }

    // public function prepare_item_for_response($post, $request) {
    //     // First verify we have a valid post
    //     if (!($post instanceof WP_Post)) {
    //         return new WP_Error('invalid_post', 'Invalid post object', ['status' => 404]);
    //     }

    //     // Check if ACF function exists
    //     if (!function_exists('get_fields')) {
    //         return new WP_Error('acf_missing', 'ACF plugin is not active', ['status' => 500]);
    //     }

    //     // Get ACF fields with debug information
    //     $acf_fields = get_fields($post->ID);
        
    //     // Debug output
    //     error_log('ACF Fields for post ' . $post->ID . ': ' . print_r($acf_fields, true));
        
    //     // Check if post has ACF fields assigned
    //     $field_groups = acf_get_field_groups(['post_id' => $post->ID]);
    //     error_log('Field groups for post ' . $post->ID . ': ' . print_r($field_groups, true));

    //     $post_data = [
    //         'id' => $post->ID,
    //         'title' => $post->post_title,
    //         'content' => $post->post_content,
    //         'slug' => $post->post_name,
    //         'link' => get_permalink($post->ID),
    //         'acf' => $acf_fields,
    //         'acf_debug' => [ // Additional debug info
    //             'field_groups' => $field_groups,
    //             'post_type' => $post->post_type,
    //             'has_fields' => !empty($acf_fields)
    //         ],
    //         'districts' => get_the_terms($post->ID, 'district'),
    //     ];

    //     return new WP_REST_Response($post_data, 200);
    // }

}

// Initialize the API plugin
new RealEstateApiPlugin();
