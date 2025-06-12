<?php get_header(); ?>

<div class="container mt-4">
    <!-- Hero Section -->
    <div class="jumbotron bg-primary text-white p-5 rounded mb-4">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-4"><?php bloginfo('name'); ?></h1>
                <p class="lead"><?php bloginfo('description'); ?></p>
                <a class="btn btn-light btn-lg" href="<?php echo get_post_type_archive_link('real_estate'); ?>" role="button">
                    <?php _e('Переглянути нерухомість', 'real-estate'); ?>
                </a>
            </div>
        </div>
    </div>




    <?php echo do_shortcode('[real_estate_filter]'); ?>



    
    <!-- Featured Real Estate Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4"><?php _e('Рекомендована нерухомість', 'real-estate'); ?></h2>
            
            <?php
            $real_estate_query = new WP_Query(array(
                'post_type' => 'real_estate',
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));
            
            if ($real_estate_query->have_posts()) : ?>
                <div class="row">
                    <?php while ($real_estate_query->have_posts()) : $real_estate_query->the_post(); ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-img-top">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h5>
                                    
                                    <?php
                                    $building_name = get_field('building_name');
                                    $floors_count = get_field('floors_count');
                                    $building_type = get_field('building_type');
                                    $districts = get_the_terms(get_the_ID(), 'district');
                                    ?>
                                    
                                    <div class="card-text">
                                        <?php if ($building_name) : ?>
                                            <p><strong><?php _e('Назва:', 'real-estate'); ?></strong> <?php echo esc_html($building_name); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($districts && !is_wp_error($districts)) : ?>
                                            <p><strong><?php _e('Район:', 'real-estate'); ?></strong> <?php echo esc_html($districts[0]->name); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($floors_count) : ?>
                                            <p><strong><?php _e('Поверхів:', 'real-estate'); ?></strong> <?php echo esc_html($floors_count); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                        <?php _e('Детальніше', 'real-estate'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?php echo get_post_type_archive_link('real_estate'); ?>" class="btn btn-outline-primary">
                        <?php _e('Переглянути всю нерухомість', 'real-estate'); ?>
                    </a>
                </div>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </div>

    <br>
    <br>

    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4"><?php _e('Пости Wordpress', 'real-estate'); ?></h2>
            
            <?php if (have_posts()) : ?>
                <div class="row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-md-6 mb-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-img-top">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h5>
                                    
                                    <div class="card-text mb-3">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <small class="text-muted">
                                            <?php echo get_the_date(); ?> | 
                                            <?php _e('Автор:', 'real-estate'); ?> <?php the_author(); ?>
                                        </small>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => __('&laquo; Попередня', 'real-estate'),
                            'next_text' => __('Наступна &raquo;', 'real-estate'),
                            'class' => 'pagination justify-content-center',
                        ));
                        ?>
                    </div>
                </div>
                
            <?php else : ?>
                <div class="alert alert-info">
                    <p><?php _e('Поки що немає постів для відображення.', 'real-estate'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?><?php