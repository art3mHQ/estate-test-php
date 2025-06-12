</main><!-- #main -->

    <footer id="colophon" class="site-footer bg-dark text-white mt-5">
        <div class="container py-4">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="row">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Всі права захищені.', 'real-estate'); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'list-inline',
                        'container' => false,
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'walker' => new class extends Walker_Nav_Menu {
                            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                $output .= '<li class="list-inline-item">';
                                $attributes = ! empty($item->url) ? ' href="' . esc_attr($item->url) .'"' : '';
                                $attributes .= ' class="text-white"';
                                $output .= '<a' . $attributes .'>';
                                $output .= apply_filters('the_title', $item->title, $item->ID);
                                $output .= '</a></li>';
                            }
                        }
                    ));
                    ?>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html><?php