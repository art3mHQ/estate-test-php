<?php
/**
 * Plugin Name: Real Estate Objects Test
 * Plugin URI: https://github.com/art3mHQ/estate-test-php
 * Description: Custom plugin for managing real estate objects with districts taxonomy
 * Version: 1.0.0
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

        add_action('init', array($this, 'init_filter_widget'));
    }


    public function init_filter_widget() {
        new RealEstateFilterWidget();
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
            
            // Rooms Repeater Field

            acf_add_local_field_group(array(
                'key' => 'group_real_estate_rooms',
                'title' => 'Приміщення',
                'fields' => array(
                    array(
                        'key' => 'field_rooms',
                        'label' => 'Приміщення',
                        'name' => 'rooms',
                        'type' => 'repeater',
                        'instructions' => 'Додайте інформацію про приміщення в будинку',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_room_area',
                                'label' => 'Площа (м²)',
                                'name' => 'room_area',
                                'type' => 'number',
                                'instructions' => 'Введіть площу приміщення в квадратних метрах',
                                'required' => 1,
                                'min' => 1,
                                'step' => 0.1,
                                'wrapper' => array(
                                    'width' => '20',
                                ),
                            ),
                            array(
                                'key' => 'field_rooms_count',
                                'label' => 'Кількість кімнат',
                                'name' => 'rooms_count',
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
                            ),
                            array(
                                'key' => 'field_balcony',
                                'label' => 'Балкон',
                                'name' => 'balcony',
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
                            ),
                            array(
                                'key' => 'field_bathroom',
                                'label' => 'Санвузол',
                                'name' => 'bathroom',
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
                            ),
                            array(
                                'key' => 'field_room_image',
                                'label' => 'Зображення приміщення',
                                'name' => 'room_image',
                                'type' => 'image',
                                'instructions' => 'Завантажте зображення приміщення',
                                'return_format' => 'array',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                            ),
                        ),
                        'min' => 1,
                        'layout' => 'block',
                        'button_label' => 'Додати приміщення',
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
                'menu_order' => 1,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));


        }
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


/**
 * Real Estate Filter Widget
 * Add this code to your existing plugin file
 */

class RealEstateFilterWidget {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_filter_scripts'));
        add_action('wp_ajax_real_estate_filter', array($this, 'handle_filter_ajax'));
        add_action('wp_ajax_nopriv_real_estate_filter', array($this, 'handle_filter_ajax'));
    }
    
    /**
     * Initialize shortcode
     */
    public function init() {
        add_shortcode('real_estate_filter', array($this, 'render_filter_widget'));
    }
    
    /**
     * Enqueue scripts and styles for filter
     */
    public function enqueue_filter_scripts() {
        wp_enqueue_style('real-estate-filter-style', plugin_dir_url(__FILE__) . 'assets/filter-style.css');
        wp_enqueue_script('real-estate-filter-script', plugin_dir_url(__FILE__) . 'assets/filter-script.js', array('jquery'), '1.0.0', true);
        
        // Localize script for AJAX
        wp_localize_script('real-estate-filter-script', 'real_estate_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('real_estate_filter_nonce')
        ));
    }
    
    /**
     * Render filter widget shortcode
     */
    public function render_filter_widget($atts) {
        $atts = shortcode_atts(array(
            'show_results' => 'true',
            'results_per_page' => '5',
            'layout' => 'grid'
        ), $atts);
        
        ob_start();
        ?>
        <div class="real-estate-filter-container">
            <!-- Filter Form -->
            <div class="real-estate-filter-form">
                <h3><?php _e('Фільтр об\'єктів нерухомості', 'real-estate'); ?></h3>
                
                <form id="real-estate-filter-form" method="get">
                    <div class="filter-row">
                        <!-- District Filter -->
                        <div class="filter-field">
                            <label for="filter_district"><?php _e('Район:', 'real-estate'); ?></label>
                            <select name="district" id="filter_district">
                                <option value=""><?php _e('Всі райони', 'real-estate'); ?></option>
                                <?php
                                $districts = get_terms(array(
                                    'taxonomy' => 'district',
                                    'hide_empty' => false,
                                ));
                                foreach ($districts as $district) {
                                    echo '<option value="' . $district->slug . '">' . $district->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Building Name Filter -->
                        <div class="filter-field">
                            <label for="filter_building_name"><?php _e('Назва будинку:', 'real-estate'); ?></label>
                            <input type="text" name="building_name" id="filter_building_name" placeholder="<?php _e('Введіть назву будинку', 'real-estate'); ?>">
                        </div>
                    </div>
                    
                    <div class="filter-row">
                        <!-- Floors Count Filter -->
                        <div class="filter-field">
                            <label for="filter_floors_count"><?php _e('Кількість поверхів:', 'real-estate'); ?></label>
                            <select name="floors_count" id="filter_floors_count">
                                <option value=""><?php _e('Будь-яка', 'real-estate'); ?></option>
                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <!-- Building Type Filter -->
                        <div class="filter-field">
                            <label for="filter_building_type"><?php _e('Тип будівлі:', 'real-estate'); ?></label>
                            <select name="building_type" id="filter_building_type">
                                <option value=""><?php _e('Будь-який', 'real-estate'); ?></option>
                                <option value="panel"><?php _e('Панель', 'real-estate'); ?></option>
                                <option value="brick"><?php _e('Цегла', 'real-estate'); ?></option>
                                <option value="foam_block"><?php _e('Піноблок', 'real-estate'); ?></option>
                            </select>
                        </div>
                        
                        <!-- Eco Rating Filter -->
                        <div class="filter-field">
                            <label for="filter_eco_rating"><?php _e('Екологічність:', 'real-estate'); ?></label>
                            <select name="eco_rating" id="filter_eco_rating">
                                <option value=""><?php _e('Будь-яка', 'real-estate'); ?></option>
                                <option value="5"><?php _e('5 - Висока', 'real-estate'); ?></option>
                                <option value="4"><?php _e('4 - Вище середнього', 'real-estate'); ?></option>
                                <option value="3"><?php _e('3 - Середня', 'real-estate'); ?></option>
                                <option value="2"><?php _e('2 - Нижче середнього', 'real-estate'); ?></option>
                                <option value="1"><?php _e('1 - Низька', 'real-estate'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Room Filters -->
                    <div class="filter-section">
                        <h4><?php _e('Фільтри приміщень:', 'real-estate'); ?></h4>
                        
                        <div class="filter-row">
                            <!-- Room Area Filter -->
                            <div class="filter-field filter-range">
                                <label><?php _e('Площа приміщення (м²):', 'real-estate'); ?></label>
                                <div class="range-inputs">
                                    <input type="number" name="room_area_min" id="filter_room_area_min" placeholder="<?php _e('від', 'real-estate'); ?>" min="1" step="0.1">
                                    <span><?php _e('до', 'real-estate'); ?></span>
                                    <input type="number" name="room_area_max" id="filter_room_area_max" placeholder="<?php _e('до', 'real-estate'); ?>" min="1" step="0.1">
                                </div>
                            </div>
                            
                            <!-- Rooms Count Filter -->
                            <div class="filter-field">
                                <label for="filter_rooms_count"><?php _e('Кількість кімнат:', 'real-estate'); ?></label>
                                <select name="rooms_count" id="filter_rooms_count">
                                    <option value=""><?php _e('Будь-яка', 'real-estate'); ?></option>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="filter-row">
                            <!-- Balcony Filter -->
                            <div class="filter-field">
                                <label for="filter_balcony"><?php _e('Балкон:', 'real-estate'); ?></label>
                                <select name="balcony" id="filter_balcony">
                                    <option value=""><?php _e('Не важливо', 'real-estate'); ?></option>
                                    <option value="yes"><?php _e('Так', 'real-estate'); ?></option>
                                    <option value="no"><?php _e('Ні', 'real-estate'); ?></option>
                                </select>
                            </div>
                            
                            <!-- Bathroom Filter -->
                            <div class="filter-field">
                                <label for="filter_bathroom"><?php _e('Санвузол:', 'real-estate'); ?></label>
                                <select name="bathroom" id="filter_bathroom">
                                    <option value=""><?php _e('Не важливо', 'real-estate'); ?></option>
                                    <option value="yes"><?php _e('Так', 'real-estate'); ?></option>
                                    <option value="no"><?php _e('Ні', 'real-estate'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="filter-buttons">
                        <button type="submit" id="apply-filter" class="btn btn-primary">
                            <?php _e('Застосувати фільтр', 'real-estate'); ?>
                        </button>
                        <button type="button" id="reset-filter" class="btn btn-secondary">
                            <?php _e('Скинути', 'real-estate'); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if ($atts['show_results'] === 'true'): ?>
            <!-- Results Container -->
            <div class="real-estate-results-container">
                <div class="results-header">
                    <h3><?php _e('Результати пошуку:', 'real-estate'); ?></h3>
                    <div class="results-count">
                        <span id="results-counter"><?php _e('Завантаження...', 'real-estate'); ?></span>
                    </div>
                </div>
                
                <div id="real-estate-results" class="real-estate-results <?php echo $atts['layout']; ?>">
                    <?php echo $this->get_initial_results($atts['results_per_page']); ?>
                </div>
                
                <div class="filter-loading" id="filter-loading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p><?php _e('Завантаження результатів...', 'real-estate'); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Get initial results
     */
    private function get_initial_results($per_page = 5) {
        $args = array(
            'post_type' => 'real_estate',
            'posts_per_page' => $per_page,
            'post_status' => 'publish',
        );
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo $this->render_property_card(get_the_ID());
            }
            wp_reset_postdata();
        } else {
            echo '<div class="no-results"><p>' . __('Об\'єкти не знайдені.', 'real-estate') . '</p></div>';
        }
        
        return ob_get_clean();
    }
    
    /**
     * Render property card
     */
    private function render_property_card($post_id) {
        $building_name = get_field('building_name', $post_id);
        $coordinates = get_field('coordinates', $post_id);
        $floors_count = get_field('floors_count', $post_id);
        $building_type = get_field('building_type', $post_id);
        $eco_rating = get_field('eco_rating', $post_id);
        $building_image = get_field('building_image', $post_id);
        $rooms = get_field('rooms', $post_id);
        $districts = get_the_terms($post_id, 'district');
        
        ob_start();
        ?>
        <div class="property-card">
            <?php if ($building_image): ?>
                <div class="property-image">
                    <img src="<?php echo $building_image['sizes']['medium']; ?>" alt="<?php echo $building_name; ?>">
                </div>
            <?php endif; ?>
            
            <div class="property-content">
                <h4 class="property-title">
                    <a href="<?php echo get_permalink($post_id); ?>"><?php echo $building_name ?: get_the_title($post_id); ?></a>
                </h4>
                
                <?php if ($districts): ?>
                    <div class="property-district">
                        <strong><?php _e('Район:', 'real-estate'); ?></strong>
                        <?php echo $districts[0]->name; ?>
                    </div>
                <?php endif; ?>
                
                <div class="property-details">
                    <?php if ($floors_count): ?>
                        <div class="detail-item">
                            <span class="label"><?php _e('Поверхів:', 'real-estate'); ?></span>
                            <span class="value"><?php echo $floors_count; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($building_type): ?>
                        <div class="detail-item">
                            <span class="label"><?php _e('Тип:', 'real-estate'); ?></span>
                            <span class="value"><?php echo $this->get_building_type_label($building_type); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($eco_rating): ?>
                        <div class="detail-item">
                            <span class="label"><?php _e('Екологічність:', 'real-estate'); ?></span>
                            <span class="value"><?php echo $eco_rating; ?>/5</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($rooms): ?>
                    <div class="property-rooms">
                        <strong><?php _e('Приміщення:', 'real-estate'); ?></strong>
                        <div class="rooms-summary">
                            <?php
                            $total_rooms = count($rooms);
                            $total_area = 0;
                            foreach ($rooms as $room) {
                                $total_area += floatval($room['room_area']);
                            }
                            ?>
                            <span><?php printf(__('%d приміщень, загальна площа: %.1f м²', 'real-estate'), $total_rooms, $total_area); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="property-actions">
                    <a href="<?php echo get_permalink($post_id); ?>" class="btn btn-primary">
                        <?php _e('Детальніше', 'real-estate'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get building type label
     */
    private function get_building_type_label($type) {
        $labels = array(
            'panel' => 'Панель',
            'brick' => 'Цегла',
            'foam_block' => 'Піноблок',
        );
        
        return isset($labels[$type]) ? $labels[$type] : $type;
    }
    
    /**
     * Handle AJAX filter request
     */
    public function handle_filter_ajax() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'real_estate_filter_nonce')) {
            wp_die(__('Помилка безпеки', 'real-estate'));
        }
        
        $filters = $_POST['filters'];
        
        // Build query arguments
        $args = array(
            'post_type' => 'real_estate',
            'posts_per_page' => isset($_POST['per_page']) ? intval($_POST['per_page']) : 6,
            'post_status' => 'publish',
            'meta_query' => array('relation' => 'AND'),
        );
        
        // Add taxonomy filter
        if (!empty($filters['district'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'district',
                    'field'    => 'slug',
                    'terms'    => $filters['district'],
                ),
            );
        }
        
        // Add meta queries for building fields
        if (!empty($filters['building_name'])) {
            $args['meta_query'][] = array(
                'key'     => 'building_name',
                'value'   => $filters['building_name'],
                'compare' => 'LIKE',
            );
        }
        
        if (!empty($filters['floors_count'])) {
            $args['meta_query'][] = array(
                'key'     => 'floors_count',
                'value'   => $filters['floors_count'],
                'compare' => '=',
            );
        }
        
        if (!empty($filters['building_type'])) {
            $args['meta_query'][] = array(
                'key'     => 'building_type',
                'value'   => $filters['building_type'],
                'compare' => '=',
            );
        }
        
        if (!empty($filters['eco_rating'])) {
            $args['meta_query'][] = array(
                'key'     => 'eco_rating',
                'value'   => $filters['eco_rating'],
                'compare' => '=',
            );
        }
        
        // Execute query
        $query = new WP_Query($args);
        
        // Filter by room parameters if needed
        $filtered_posts = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $rooms = get_field('rooms', $post_id);
                $include_post = true;
                
                if ($rooms && (
                    !empty($filters['room_area_min']) || 
                    !empty($filters['room_area_max']) || 
                    !empty($filters['rooms_count']) || 
                    !empty($filters['balcony']) || 
                    !empty($filters['bathroom'])
                )) {
                    $include_post = false;
                    
                    foreach ($rooms as $room) {
                        $room_matches = true;
                        
                        // Check room area
                        if (!empty($filters['room_area_min']) && floatval($room['room_area']) < floatval($filters['room_area_min'])) {
                            $room_matches = false;
                        }
                        
                        if (!empty($filters['room_area_max']) && floatval($room['room_area']) > floatval($filters['room_area_max'])) {
                            $room_matches = false;
                        }
                        
                        // Check rooms count
                        if (!empty($filters['rooms_count']) && $room['rooms_count'] !== $filters['rooms_count']) {
                            $room_matches = false;
                        }
                        
                        // Check balcony
                        if (!empty($filters['balcony']) && $room['balcony'] !== $filters['balcony']) {
                            $room_matches = false;
                        }
                        
                        // Check bathroom
                        if (!empty($filters['bathroom']) && $room['bathroom'] !== $filters['bathroom']) {
                            $room_matches = false;
                        }
                        
                        if ($room_matches) {
                            $include_post = true;
                            break;
                        }
                    }
                }
                
                if ($include_post) {
                    $filtered_posts[] = $post_id;
                }
            }
            wp_reset_postdata();
        }
        
        // Generate results HTML
        $results_html = '';
        $results_count = count($filtered_posts);
        
        if ($results_count > 0) {
            foreach ($filtered_posts as $post_id) {
                $results_html .= $this->render_property_card($post_id);
            }
        } else {
            $results_html = '<div class="no-results"><p>' . __('За вашими критеріями об\'єкти не знайдені.', 'real-estate') . '</p></div>';
        }
        
        wp_send_json_success(array(
            'html' => $results_html,
            'count' => $results_count,
        ));
    }
}

// Initialize the filter widget
new RealEstateFilterWidget();



// class RealEstateQueryModifier {

//     public function __construct() {
//         add_action('pre_get_posts', array($this, 'modify_real_estate_query'));
//     }

//     public function modify_real_estate_query($query) {

//         if (
//             is_admin()||
//             // !$query->is_main_query() ||
//             !$query->is_post_type_archive('real_estate')
//         ) {
//             return;
//         }

//         // Modify the query to order by the ACF field 'eco_rating'
//         $query->set('meta_key', 'eco_rating');
//         $query->set('orderby', 'meta_value_num'); // because it's numeric
//         $query->set('order', 'DESC'); // or 'ASC' if you want low -> high
//     }

// }


class RealEstateQueryModifier {

    public function __construct() {
        add_action('pre_get_posts', array($this, 'modify_real_estate_query'));
    }

    public function modify_real_estate_query($query) {
        // Skip admin and non-main queries
        if (
            is_admin() || 
            // !$query->is_main_query() ||
            !$query->is_post_type_archive('real_estate')
            ) {
            return;
        }

        // Modify real_estate archive pages
        if ($query->is_post_type_archive('real_estate')) {
            $this->apply_eco_rating_sort($query);
        }

        // Modify AJAX WP_Query (e.g., handle_filter_ajax), by checking post_type directly
        if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'real_estate') {
            $this->apply_eco_rating_sort($query);
        }
    }

    private function apply_eco_rating_sort($query) {
        // Do not override if ordering is already defined explicitly
        // if (!$query->get('orderby') ) {
            $query->set('meta_key', 'eco_rating');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        // }
    }
}


new RealEstateQueryModifier();

?>