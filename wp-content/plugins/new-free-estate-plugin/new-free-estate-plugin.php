<?php
/**
 * Plugin Name: Real Estate Objects Test
 * Plugin URI: https://art3msite.com
 * Description: Plugin for managing real estate objects with districts taxonomy (without acf pro need)
 * Version: 1.0.1
 * Author: art3m
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RealEstatePlugin {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('acf/init', array($this, 'register_acf_fields'));
    }
    
    /**
     * Register Real Estate Object post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => 'Об\'єкти нерухомості',
            'singular_name'         => 'Об\'єкт нерухомості',
            'menu_name'             => 'Нерухомість',
            'name_admin_bar'        => 'Об\'єкт нерухомості',
            'archives'              => 'Архіви нерухомості',
            'attributes'            => 'Атрибути об\'єкта',
            'parent_item_colon'     => 'Батьківський об\'єкт:',
            'all_items'             => 'Всі об\'єкти',
            'add_new_item'          => 'Додати новий об\'єкт',
            'add_new'               => 'Додати новий',
            'new_item'              => 'Новий об\'єкт',
            'edit_item'             => 'Редагувати об\'єкт',
            'update_item'           => 'Оновити об\'єкт',
            'view_item'             => 'Переглянути об\'єкт',
            'view_items'            => 'Переглянути об\'єкти',
            'search_items'          => 'Шукати об\'єкти',
            'not_found'             => 'Не знайдено',
            'not_found_in_trash'    => 'Не знайдено в кошику',
            'featured_image'        => 'Головне зображення',
            'set_featured_image'    => 'Встановити головне зображення',
            'remove_featured_image' => 'Видалити головне зображення',
            'use_featured_image'    => 'Використати як головне зображення',
            'insert_into_item'      => 'Вставити в об\'єкт',
            'uploaded_to_this_item' => 'Завантажено до цього об\'єкта',
            'items_list'            => 'Список об\'єктів',
            'items_list_navigation' => 'Навігація по об\'єктах',
            'filter_items_list'     => 'Фільтрувати об\'єкти',
        );
        
        $args = array(
            'label'                 => 'Об\'єкт нерухомості',
            'description'           => 'Об\'єкти нерухомості',
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'taxonomies'            => array('district'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-building',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        
        register_post_type('real_estate', $args);
    }
    
    /**
     * Register District taxonomy
     */
    public function register_taxonomy() {
        $labels = array(
            'name'                       => 'Райони',
            'singular_name'              => 'Район',
            'menu_name'                  => 'Райони',
            'all_items'                  => 'Всі райони',
            'parent_item'                => 'Батьківський район',
            'parent_item_colon'          => 'Батьківський район:',
            'new_item_name'              => 'Назва нового району',
            'add_new_item'               => 'Додати новий район',
            'edit_item'                  => 'Редагувати район',
            'update_item'                => 'Оновити район',
            'view_item'                  => 'Переглянути район',
            'separate_items_with_commas' => 'Розділити райони комами',
            'add_or_remove_items'        => 'Додати або видалити райони',
            'choose_from_most_used'      => 'Вибрати з найбільш використовуваних',
            'popular_items'              => 'Популярні райони',
            'search_items'               => 'Шукати райони',
            'not_found'                  => 'Не знайдено',
            'no_terms'                   => 'Немає районів',
            'items_list'                 => 'Список районів',
            'items_list_navigation'      => 'Навігація по районах',
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        
        register_taxonomy('district', array('real_estate'), $args);
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style('real-estate-style', plugin_dir_url(__FILE__) . 'assets/style.css');
        wp_enqueue_script('real-estate-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), '1.0.0', true);
    }
    
    /**
     * Register ACF Fields
     */
    public function register_acf_fields() {
        if (function_exists('acf_add_local_field_group')) {
            
            // Main Real Estate Fields
            acf_add_local_field_group(array(
                'key' => 'group_real_estate_main',
                'title' => 'Основна інформація про об\'єкт',
                'fields' => array(
                    array(
                        'key' => 'field_building_name',
                        'label' => 'Назва будинку',
                        'name' => 'building_name',
                        'type' => 'text',
                        'instructions' => 'Введіть назву будинку',
                        'required' => 1,
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_coordinates',
                        'label' => 'Координати місцезнаходження',
                        'name' => 'coordinates',
                        'type' => 'text',
                        'instructions' => 'Введіть координати (наприклад: 50.4501, 30.5234)',
                        'placeholder' => '50.4501, 30.5234',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_floors_count',
                        'label' => 'Кількість поверхів',
                        'name' => 'floors_count',
                        'type' => 'select',
                        'instructions' => 'Виберіть кількість поверхів',
                        'choices' => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            '13' => '13',
                            '14' => '14',
                            '15' => '15',
                            '16' => '16',
                            '17' => '17',
                            '18' => '18',
                            '19' => '19',
                            '20' => '20',
                        ),
                        'default_value' => '1',
                        'wrapper' => array(
                            'width' => '33',
                        ),
                    ),
                    array(
                        'key' => 'field_building_type',
                        'label' => 'Тип будівлі',
                        'name' => 'building_type',
                        'type' => 'radio',
                        'instructions' => 'Виберіть тип будівлі',
                        'choices' => array(
                            'panel' => 'Панель',
                            'brick' => 'Цегла',
                            'foam_block' => 'Піноблок',
                        ),
                        'default_value' => 'brick',
                        'layout' => 'horizontal',
                        'wrapper' => array(
                            'width' => '33',
                        ),
                    ),
                    array(
                        'key' => 'field_eco_rating',
                        'label' => 'Екологічність',
                        'name' => 'eco_rating',
                        'type' => 'select',
                        'instructions' => 'Оцініть екологічність від 1 до 5',
                        'choices' => array(
                            '1' => '1 - Низька',
                            '2' => '2 - Нижче середнього',
                            '3' => '3 - Середня',
                            '4' => '4 - Вище середнього',
                            '5' => '5 - Висока',
                        ),
                        'default_value' => '3',
                        'wrapper' => array(
                            'width' => '34',
                        ),
                    ),
                    array(
                        'key' => 'field_building_image',
                        'label' => 'Зображення будинку',
                        'name' => 'building_image',
                        'type' => 'image',
                        'instructions' => 'Завантажте зображення будинку',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'real_estate',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
            
            // Fixed Apartments Field Groups (1-10)
            for ($i = 1; $i <= 10; $i++) {
                acf_add_local_field_group(array(
                    'key' => 'group_real_estate_apart_' . $i,
                    'title' => 'Приміщення ' . $i,
                    'fields' => array(
                        array(
                            'key' => 'field_apart_' . $i . '_enabled',
                            'label' => 'Включити Приміщення ' . $i,
                            'name' => 'apart_' . $i . '_enabled',
                            'type' => 'true_false',
                            'instructions' => 'Активувати це Приміщення для заповнення',
                            'message' => 'Так, включити це Приміщення',
                            'default_value' => 0,
                            'ui' => 1,
                        ),
                        array(
                            'key' => 'field_apart_' . $i . '_area',
                            'label' => 'Площа (м²)',
                            'name' => 'apart_' . $i . '_area',
                            'type' => 'number',
                            'instructions' => 'Введіть площу Приміщенняи в квадратних метрах',
                            'min' => 1,
                            'step' => 0.1,
                            'wrapper' => array(
                                'width' => '20',
                            ),
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_apart_' . $i . '_enabled',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_apart_' . $i . '_rooms_count',
                            'label' => 'Кількість кімнат',
                            'name' => 'apart_' . $i . '_rooms_count',
                            'type' => 'radio',
                            'instructions' => 'Виберіть кількість кімнат',
                            'choices' => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                '7' => '7',
                                '8' => '8',
                                '9' => '9',
                                '10' => '10',
                            ),
                            'default_value' => '1',
                            'layout' => 'horizontal',
                            'wrapper' => array(
                                'width' => '30',
                            ),
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_apart_' . $i . '_enabled',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_apart_' . $i . '_balcony',
                            'label' => 'Балкон',
                            'name' => 'apart_' . $i . '_balcony',
                            'type' => 'radio',
                            'instructions' => 'Чи є балкон?',
                            'choices' => array(
                                'yes' => 'Так',
                                'no' => 'Ні',
                            ),
                            'default_value' => 'no',
                            'layout' => 'horizontal',
                            'wrapper' => array(
                                'width' => '25',
                            ),
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_apart_' . $i . '_enabled',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_apart_' . $i . '_bathroom',
                            'label' => 'Санвузол',
                            'name' => 'apart_' . $i . '_bathroom',
                            'type' => 'radio',
                            'instructions' => 'Чи є санвузол?',
                            'choices' => array(
                                'yes' => 'Так',
                                'no' => 'Ні',
                            ),
                            'default_value' => 'yes',
                            'layout' => 'horizontal',
                            'wrapper' => array(
                                'width' => '25',
                            ),
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_apart_' . $i . '_enabled',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                        array(
                            'key' => 'field_apart_' . $i . '_image',
                            'label' => 'Зображення Приміщення',
                            'name' => 'apart_' . $i . '_image',
                            'type' => 'image',
                            'instructions' => 'Завантажте зображення Приміщенн',
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_apart_' . $i . '_enabled',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'real_estate',
                            ),
                        ),
                    ),
                    'menu_order' => $i,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                ));
            }
        }
    }
    
    /**
     * Helper function to get all active apartments for a post
     */
    public static function get_active_apartments($post_id) {
        $apartments = array();
        
        for ($i = 1; $i <= 10; $i++) {
            $enabled = get_field('apart_' . $i . '_enabled', $post_id);
            
            if ($enabled) {
                $apartments[] = array(
                    'number' => $i,
                    'area' => get_field('apart_' . $i . '_area', $post_id),
                    'rooms_count' => get_field('apart_' . $i . '_rooms_count', $post_id),
                    'balcony' => get_field('apart_' . $i . '_balcony', $post_id),
                    'bathroom' => get_field('apart_' . $i . '_bathroom', $post_id),
                    'image' => get_field('apart_' . $i . '_image', $post_id),
                );
            }
        }
        
        return $apartments;
    }
}

// Initialize the plugin
new RealEstatePlugin();

// Activation hook
register_activation_hook(__FILE__, 'real_estate_plugin_activate');
function real_estate_plugin_activate() {
    // Flush rewrite rules on activation
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'real_estate_plugin_deactivate');
function real_estate_plugin_deactivate() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
}
?>