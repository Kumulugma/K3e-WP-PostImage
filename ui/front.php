<?php

function getPostImageFiles($post_id) {
    $post_images = get_post_meta($post_id, "post_files", true);
    if (!empty($post_images)) {
        $post_images = unserialize($post_images);
        $post_images = explode(",", $post_images);
    } else {
        $post_images = [];
    }
    return $post_images;
}
