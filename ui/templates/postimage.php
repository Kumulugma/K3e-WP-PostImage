<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('K3e PostImage', 'k3e'); ?>
    </h1>

    <?php $option = unserialize(get_option(PostImage::OPTION_POSTIMAGE)); ?>
    <?php $postImages = is_array($option) ? $option : []; ?>


    <div class="card">
        <form method="post" action="options-general.php?page=postimage">
            <fieldset>
                <h3><?=__('Wybierz typy postów', 'k3e')?></h3>
                <?php foreach (get_post_types() as $type) { ?>
                    <?php if (!in_array($type, PostImage::POST_EXCLUDES)) { ?>
                        <p>
                            <input type="checkbox" id="<?= $type ?>Form" name="PostImage[<?= $type ?>]" value="<?= $type ?>" <?= (in_array($type, $postImages)) ? "checked" : "" ?>>
                            <label for="<?= $type ?>Form"><?php $post_type_obj = get_post_type_object($type) ?> <?= $post_type_obj->labels->singular_name; ?> [<?= $type ?>]</label>
                        </p>
                    <?php } ?>
                <?php } ?>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="PostImage[salt]">
                <button class="button button-primary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
    
        <div class="card">
        <form method="post" action="options-general.php?page=postimage">
            <fieldset>
                <h3><?=__('Zregeneruj miniaturki', 'k3e')?></h3>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="PostImage[regenerate]">
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="PostImage[salt]">
                <button class="button button-secondary" type="submit">Zapisz</button>
            </fieldset>
        </form>
    </div>
</div>