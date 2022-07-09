<?php

/*
  Plugin name: K3e - PostImage
  Plugin URI:
  Description: Możliwość podpięcia kilku obrazków pod wpis danego typu.
  Author: K3e
  Author URI: https://www.k3e.pl/
  Text Domain:
  Domain Path:
  Version: 0.0.1a
 */

add_action('init', 'k3e_postimage_plugin_init');

function k3e_postimage_plugin_init() {
    do_action('k3e_postimage_plugin_init');
    if (is_admin()) {
        if (current_user_can('manage_options')) {
            require_once 'ui/front.php';
            require_once 'ui/admin.php';
            PostImage::run();
        }
    } else {
        require_once 'ui/front.php';
    }
}

function k3e_postimage_plugin_activate() {
    
}

register_activation_hook(__FILE__, 'k3e_postimage_plugin_activate');

function k3e_postimage_plugin_deactivate() {
    
}

register_deactivation_hook(__FILE__, 'k3e_postimage_plugin_deactivate');
