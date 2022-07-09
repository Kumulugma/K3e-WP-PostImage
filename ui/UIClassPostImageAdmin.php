<?php

class UIClassPostImageAdmin extends UIClassPostImage {

    public static function run() {

        add_action('admin_menu', 'postimage_menu');
        
        function postimage_menu() {

            add_submenu_page(
                    'options-general.php',
                    __('PostImage', 'k3e'),
                    __('PostImage', 'k3e'),
                    'manage_options',
                    'postimage',
                    'postimage_content'
            );

            /* Dostępne pozycje
              2 – Dashboard
              4 – Separator
              5 – Posts
              10 – Media
              15 – Links
              20 – Pages
              25 – Comments
              59 – Separator
              60 – Appearance
              65 – Plugins
              70 – Users
              75 – Tools
              80 – Settings
              99 – Separator
             */
        }

        UIClassPostImageAdmin::SaveSettings();
        UIClassPostImageAdmin::Files();

        function postimage_content() {
            include plugin_dir_path(__FILE__) . 'admin/templates/postimage.php';
        }

    }

    public static function SaveSettings() {
        if (isset($_POST['PostImage']['salt'])) {
            $form = [];
            foreach ($_POST['PostImage'] as $key => $PostImage) {
                if ($key != 'salt') {
                    $form[] = sanitize_text_field($PostImage);
                }
            }

            update_option(self::OPTION_POSTIMAGE, serialize($form));
            wp_redirect('options-general.php?page=' . $_GET['page']);
        }

        if (isset($_POST['PostImage']['regenerate'])) {
            UIClassPostImageAdmin::simpleRegeneratePostImage();
            wp_redirect('options-general.php?page=' . $_GET['page']);
        }
    }

    public static function simpleRegeneratePostImage() {
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'closed',
            'posts_per_page' => -1,
            'post_mime_type' => 'image/jpeg'
        );
        $toRegenerate = new WP_Query($args);
        if ($toRegenerate->have_posts()) {
            while ($toRegenerate->have_posts()) {
                $toRegenerate->the_post();

                $basedir = wp_upload_dir()['basedir'];
                $file_meta = get_post_meta(get_the_id(), '_wp_attachment_metadata', true);
                $file = get_post_meta(get_the_id(), '_wp_attached_file', true);

                $image = wp_get_image_editor($basedir . '/' . $file);
                $noExtension = substr($file, 0, -(strlen($file) - strpos($file, '.')));

                if (!is_wp_error($image)) {
                    $image->resize(80, 80, true);
                    $image->save($basedir . '/' . $noExtension . '-80x80.jpg');
                    $file_meta['sizes']['PostImage'] = [
                        'file' => basename($basedir . '/' . $noExtension . '-80x80.jpg'),
                        'width' => 80,
                        'height' => 80,
                        'mime-type' => 'image/jpeg',
                        'filesize' => filesize($basedir . '/' . $noExtension . '-80x80.jpg')
                    ];
                    update_post_meta(get_the_id(), '_wp_attachment_metadata', $file_meta);
                }
            }
        }
        wp_reset_postdata();
    }

    public static function Files() {

        add_action("add_meta_boxes", "files_meta_box");

        function files_meta_box() {
            $option = unserialize(get_option(UIClassPostImageAdmin::OPTION_POSTIMAGE));
            $postImages = is_array($option) ? $option : [];

            foreach ($postImages as $type) {
                add_meta_box("growlist-photos-meta-box", __('Dodatkowe pliki', 'k3e'), "files_box_markup", $type, "normal", "high", null);
            }
        }

        function files_box_markup($object) {
            wp_enqueue_media();
            wp_enqueue_script('K3e-Media', plugin_dir_url(__FILE__) . '../assets/k3e-media.js', array('jquery'), '0.1');

            include plugin_dir_path(__FILE__) . 'admin/templates/photos/form.php';
        }

        function k3e_files_save_meta_box($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if ($parent_id = wp_is_post_revision($post_id)) {
                $post_id = $parent_id;
            }

            $fields = [
                'post_files',
            ];
            foreach ($fields as $field) {
                if (array_key_exists($field, $_POST)) {
                    update_post_meta($post_id, $field, serialize(sanitize_text_field($_POST[$field])));
                }
            }
        }

        add_action('save_post', 'k3e_files_save_meta_box');

        add_action('wp_ajax_postimage_get_files', 'postimage_get_files');

        function postimage_get_files() {
            if (isset($_GET['id'])) {

                $ids = explode(",", $_GET['id']);
                $images = [];

                foreach ($ids as $id) {
                    $images[] = wp_get_attachment_image($id, 'PostImage', false, array('id' => 'preview-images', 'style' => 'margin-right: 5px;'));
                }
                $data = array(
                    'images' => $images
                );
                wp_send_json_success($data);
            } else {
                wp_send_json_error();
            }
        }

    }

}
