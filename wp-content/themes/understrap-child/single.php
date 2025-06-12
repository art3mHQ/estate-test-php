<?php get_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-4">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <div class="entry-meta text-muted mb-3">
                            <span class="posted-on">
                                <i class="fas fa-calendar"></i>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <span class="byline ms-3">
                                <i class="fas fa-user"></i>
                                <?php _e('Автор:', 'real-estate'); ?> 
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="text-decoration-none">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                            
                            <?php if (has_category()) : ?>
                                <span class="cat-links ms-3">
                                    <i class="fas fa-folder"></i>
                                    <?php _e('Категорії:', 'real-estate'); ?> <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (has_tag()) : ?>
                                <span class="tags-links ms-3">
                                    <i class="fas fa-tags"></i>
                                    <?php _e('Теги:', 'real-estate'); ?> <?php the_tags('', ', '); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                        </div>
                    <?php endif; ?>





            <div class="entry-content">
                <?php the_content(); ?>
                
                <div class="real-estate-meta">
                    <h2>Основна інформація</h2>
                    
                    <?php if ($building_name = get_field('building_name')) : ?>
                        <p><strong>Назва будинку:</strong> <?php echo esc_html($building_name); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($coordinates = get_field('coordinates')) : ?>
                        <p><strong>Координати:</strong> <?php echo esc_html($coordinates); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($floors_count = get_field('floors_count')) : ?>
                        <p><strong>Кількість поверхів:</strong> <?php echo esc_html($floors_count); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($building_type = get_field('building_type')) : ?>
                        <p><strong>Тип будівлі:</strong> 
                        <?php 
                            $type_labels = array(
                                'panel' => 'Панель',
                                'brick' => 'Цегла',
                                'foam_block' => 'Піноблок'
                            );
                            echo esc_html($type_labels[$building_type] ?? $building_type); 
                        ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($eco_rating = get_field('eco_rating')) : ?>
                        <p><strong>Екологічність:</strong> 
                        <?php 
                            $rating_labels = array(
                                '1' => '1 - Низька',
                                '2' => '2 - Нижче середнього',
                                '3' => '3 - Середня',
                                '4' => '4 - Вище середнього',
                                '5' => '5 - Висока'
                            );
                            echo esc_html($rating_labels[$eco_rating] ?? $eco_rating); 
                        ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($building_image = get_field('building_image')) : ?>
                        <div class="building-image">
                            <strong>Зображення будинку:</strong><br>
                            <img src="<?php echo esc_url($building_image['url']); ?>" alt="<?php echo esc_attr($building_image['alt']); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Display taxonomy terms -->
                    <?php 
                    $terms = get_the_terms(get_the_ID(), 'district');
                    if ($terms && !is_wp_error($terms)) : ?>
                        <p><strong>Район:</strong> 
                            <?php 
                            $term_names = array();
                            foreach ($terms as $term) {
                                $term_names[] = $term->name;
                            }
                            echo esc_html(implode(', ', $term_names));
                            ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Display rooms repeater field -->
                <?php
                // Get all ACF fields for the current post.
                // If you're outside the WordPress Loop, you might need to pass get_the_ID() or a specific post ID.
                $all_fields = get_fields();

                // Check if the 'rooms' repeater field exists and has data.
                if (isset($all_fields['rooms']) && is_array($all_fields['rooms']) && !empty($all_fields['rooms'])) : ?>

                    <div class="real-estate-rooms">
                        <h2>Приміщення</h2>

                        <div class="rooms-grid">
                            <?php
                            $room_index = 1; // Initialize a counter for room numbering
                            foreach ($all_fields['rooms'] as $room) : // Loop through each room array
                                ?>
                                <div class="room-item">
                                    <h3>Приміщення <?php echo $room_index; ?></h3>

                                    <p><strong>Площа:</strong> <?php echo esc_html($room['room_area']); ?> м²</p>
                                    <p><strong>Кількість кімнат:</strong> <?php echo esc_html($room['rooms_count']); ?></p>
                                    <p><strong>Балкон:</strong> <?php echo (isset($room['balcony']) && $room['balcony'] == 'yes') ? 'Так' : 'Ні'; ?></p>
                                    <p><strong>Санвузол:</strong> <?php echo (isset($room['bathroom']) && $room['bathroom'] == 'yes') ? 'Так' : 'Ні'; ?></p>

                                    <?php
                                    // Assuming 'room_image' is set to return 'array' in ACF field settings
                                    $room_image = $room['room_image'];
                                    if ($room_image) : ?>
                                        <div class="room-image">
                                            <img src="<?php echo esc_url($room_image['url']); ?>" alt="<?php echo esc_attr($room_image['alt']); ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php
                                $room_index++; // Increment the counter for the next room
                            endforeach; ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>










                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                        
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . __('Сторінки:', 'real-estate'),
                            'after'  => '</div>',
                        ));
                        ?>

                    </div>
                    
                    <footer class="entry-footer mt-4 pt-4 border-top">
                        <?php if (has_tag()) : ?>
                            <div class="tags-wrapper">
                                <strong><?php _e('Теги:', 'real-estate'); ?></strong>
                                <div class="mt-2">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<span class="badge bg-secondary me-1 mb-1">' . esc_html($tag->name) . '</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        

                    </footer>
                </article>
                
                <!-- Post navigation -->
                <nav class="post-navigation row mb-5">
                    <div class="col-md-6">
                        <?php previous_post_link('<div class="nav-previous"><strong>%link</strong></div>', '&laquo; %title'); ?>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php next_post_link('<div class="nav-next"><strong>%link</strong></div>', '%title &raquo;'); ?>
                    </div>
                </nav>
                
                <!-- Author bio -->
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-info card mb-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo get_avatar(get_the_author_meta('user_email'), 80, '', '', array('class' => 'rounded-circle')); ?>
                                </div>
                                <div class="col-md-10">
                                    <h5><?php _e('Про автора:', 'real-estate'); ?> <?php the_author(); ?></h5>
                                    <p><?php the_author_meta('description'); ?></p>
                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="btn btn-sm btn-outline-primary">
                                        <?php _e('Всі пости автора', 'real-estate'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Related posts -->
                <?php
                $related_posts = get_posts(array(
                    'category__in' => wp_get_post_categories($post->ID),
                    'numberposts' => 3,
                    'post__not_in' => array($post->ID)
                ));
                
                if ($related_posts) :
                ?>
                    <div class="related-posts mb-5">
                        <h3><?php _e('Схожі пости', 'real-estate'); ?></h3>
                        <div class="row">
                            <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                            </a>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted"><?php echo get_the_date(); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Comments -->
                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            <?php endwhile; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?><?php