<?php
/*
Plugin Name: C4D Mega Menu
Plugin URI: http://coffee4dev.com/
Description: Simple mega menu
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-mega-menu
Version: 2.0.0
*/

define('C4DMEGAMENU_PLUGIN_URI', plugins_url('', __FILE__));
define('C4DMEGAMENU_LOCATION', 'primary');
add_action( 'admin_enqueue_scripts', 'c4d_mega_menu_load_scripts' );
add_action( 'wp_enqueue_scripts', 'c4d_mega_menu_load_scripts_site');
add_action( 'widgets_init', 'c4d_mega_menu_widget' );
add_filter( 'walker_nav_menu_start_el', 'c4d_mega_menu_walker_nav_menu_start_el', 10, 4 );
add_filter( 'plugin_row_meta', 'c4d_mega_menu_plugin_row_meta', 10, 2 );

function c4d_mega_menu_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'forum' => '<a href="http://coffee4dev.com/forums/">Forum</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_mega_menu_load_scripts_site() {
    if(!defined('C4DPLUGINMANAGER')) {
	   wp_enqueue_script( 'c4d-mega-menu-site-js', C4DMEGAMENU_PLUGIN_URI . '/assets/default.js' );    
	   wp_enqueue_style( 'c4d-mega-menu-site-style', C4DMEGAMENU_PLUGIN_URI.'/assets/default.css' );
    }
}
function c4d_mega_menu_load_scripts($hook) {
	if ( 'nav-menus.php' == $hook ) {
    	wp_enqueue_script( 'c4d-mega-menu-admin-js', C4DMEGAMENU_PLUGIN_URI . '/assets/admin.js' );    
    	wp_enqueue_style( 'c4d-mega-menu-admin-style', C4DMEGAMENU_PLUGIN_URI.'/assets/admin.css' );
    }
}

function c4d_mega_menu_widget() {
    $location = C4DMEGAMENU_LOCATION;
    $locations = get_nav_menu_locations();
	if ( isset( $locations[ $location ] ) ) {
        $menu = get_term( $locations[ $location ], 'nav_menu' );
        if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
        	foreach ( $items as $item ) {
                if ( in_array( 'c4d-mega-menu', $item->classes ) ) {
                    register_sidebar( array(
                        'id'   => 'c4d-mega-menu-widget-area-' . $item->ID,
                        'name' => $item->title . ' - Mega Menu',
                        'description' => '',
						'class' => '',
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget' => "</div>\n",
						'before_title' => '<h3 class="widgettitle">',
						'after_title' => "</h3>\n",
                    ) );
                }
            }
        }
    }
}

function c4d_mega_menu_walker_nav_menu_start_el($item_output, $item, $depth, $args ) {
	if( $args->theme_location == C4DMEGAMENU_LOCATION ){
        if ( in_array( 'c4d-mega-menu', $item->classes ) ) {
			ob_start();
			if ( is_active_sidebar( 'c4d-mega-menu-widget-area-' . $item->ID ) ) {
                echo '<div class="c4d-mega-menu-block">';
                dynamic_sidebar( 'c4d-mega-menu-widget-area-' . $item->ID );
                echo '</div>';
            }
            $html = ob_get_contents();
            ob_end_clean();
			$item_output = $item_output.$html;
		}
	}
   	return $item_output;
}

