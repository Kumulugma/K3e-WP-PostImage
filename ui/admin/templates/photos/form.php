<?php
$post_images = (get_post_meta(get_the_ID(), "post_files", true));

if ($post_images) {
    $post_images = unserialize($post_images);
    $post_images_input = $post_images;
    $post_images = explode(",", $post_images);
} else {
    $post_images = [];
    $post_images_input = "";    
}
?>
<div id="images-box" data-default='<?= plugin_dir_url(__FILE__) . '../../../../images/default.png' ?>' style="padding-left: 5px;">
<?php if (count($post_images) > 0 &&  $post_images[0] != "") { ?>
        <?php foreach ($post_images as $image) { ?>
            <img src="<?= wp_get_attachment_image_url($image, 'PostImage') ?>" style="width: 80px; margin-right: 5px;" class="preview-images">
        <?php } ?>
    <?php } else { ?>
        <img src="<?= plugin_dir_url(__FILE__) . '../../../../images/default.png' ?>" style="width: 80px;" class="preview-images"/>
    <?php } ?>
</div>
<input type="hidden" name="post_files" value="<?php echo esc_attr($post_images_input); ?>" id="post-images" class="regular-text" />
<div style="display: block">
    <input type='button' class="button-primary" value="<?php esc_attr_e('Wybierz obrazki', 'k3e'); ?>" id="post_media_manager" style="margin-left: 5px;"/>
    <input type='button' class="button-secondary" value="<?php esc_attr_e('Usuń obrazki', 'k3e'); ?>" id="post_media_remover"/>
</div>
