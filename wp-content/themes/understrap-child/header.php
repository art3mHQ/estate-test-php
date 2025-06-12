<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <?php if (has_custom_logo()) : ?>
                    <div class="navbar-brand">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'navbar-nav ms-auto',
                        'container' => false,
                        'fallback_cb' => false,
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'walker' => new class extends Walker_Nav_Menu {
                            function start_lvl(&$output, $depth = 0, $args = null) {
                                $output .= '<ul class="dropdown-menu">';
                            }
                            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                $classes = empty($item->classes) ? array() : (array) $item->classes;
                                $classes[] = 'nav-item';
                                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                                $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                                
                                $output .= '<li' . $class_names .'>';
                                $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                                $attributes .= ! empty($item->target) ? ' target="' . esc_attr($item->target) .'"' : '';
                                $attributes .= ! empty($item->xfn) ? ' rel="'    . esc_attr($item->xfn) .'"' : '';
                                $attributes .= ! empty($item->url) ? ' href="'   . esc_attr($item->url) .'"' : '';
                                $attributes .= ' class="nav-link"';
                                
                                $item_output = isset($args->before) ? $args->before : '';
                                $item_output .= '<a' . $attributes .'>';
                                $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
                                $item_output .= '</a>';
                                $item_output .= isset($args->after) ? $args->after : '';
                                
                                $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                            }
                        }
                    ));
                    ?>
                </div>
            </div>
        </nav>
    </header>

    <main id="primary" class="site-main"><?php