<?php

class UIClassPostImage {

    const POST_EXCLUDES = [
        'attachment',
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
        'wp_block',
        'wp_template',
        'wp_template_part',
        'wp_global_styles',
        'wp_navigation',
        'acf-field-group',
        'acf-field',
        'wpcf7_contact_form'
    ];
    const OPTION_POSTIMAGE = '_k3e_postimages';

    public static function init() {
        add_image_size('PostImage', 80, 80, true);
    }

    public static function run() {
        
    }

}
