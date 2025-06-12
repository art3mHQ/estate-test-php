<?php get_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-4">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail mt-3">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                        
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . __('Сторінки:', 'real-estate'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                    
                    <?php if (get_edit_post_link()) : ?>
                        <footer class="entry-footer mt-4 pt-4 border-top">
                            <?php
                            edit_post_link(
                                sprintf(
                                    __('Редагувати %s', 'real-estate'),
                                    the_title('<span class="screen-reader-text">"', '"</span>', false)
                                ),
                                '<span class="edit-link">',
                                '</span>'
                            );
                            ?>
                        </footer>
                    <?php endif; ?>
                </article>
                
                <!-- Page children (if any) -->
                <?php
                $children = get_pages(array(
                    'child_of' => $post->ID,
                    'sort_column' => 'menu_order',
                    'sort_order' => 'ASC'
                ));
                
                if ($children) :
                ?>
                    <div class="child-pages mt-5">
                        <h3><?php _e('Підсторінки:', 'real-estate'); ?></h3>
                        <div class="row">
                            <?php foreach ($children as $child) : ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <?php if (has_post_thumbnail($child->ID)) : ?>
                                            <a href="<?php echo get_permalink($child->ID); ?>">
                                                <?php echo get_the_post_thumbnail($child->ID, 'medium', array('class' => 'card-img-top')); ?>
                                            </a>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="<?php echo get_permalink($child->ID); ?>" class="text-decoration-none">
                                                    <?php echo $child->post_title; ?>
                                                </a>
                                            </h5>
                                            <?php if ($child->post_excerpt) : ?>
                                                <p class="card-text"><?php echo $child->post_excerpt; ?></p>
                                            <?php endif; ?>
                                            <a href="<?php echo get_permalink($child->ID); ?>" class="btn btn-primary">
                                                <?php _e('Читати далі', 'real-estate'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Comments for pages (if enabled) -->