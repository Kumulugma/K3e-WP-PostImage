jQuery(document).ready(function ($) {

    jQuery('input#post_media_manager').click(function (e) {

        e.preventDefault();
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: 'add',
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function () {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function (attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            if (ids.length === 0)
                return true;//if closed withput selecting an image
            jQuery('input#post-images').val(ids);
            Refresh_Image(ids);
        });

        image_frame.on('open', function () {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection = image_frame.state().get('selection');
            var ids = jQuery('input#post-images').val().split(',');
            ids.forEach(function (id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });

        });

        image_frame.open();
    });

    jQuery('input#post_media_remover').click(function (e) {

        e.preventDefault();
        let default_img = jQuery('#images-box').data('default');
        jQuery('#images-box').html('<img src="' + default_img + '" style="width: 80px;" class="preview-images"/>');
        jQuery('input#post-images').val('');


    });

});

// Ajax request to refresh the image preview
function Refresh_Image(the_id) {
    var data = {
        action: 'postimage_get_files',
        id: the_id
    };

//    jQuery.each(the_id.split(","), function (index, value) {
//        alert(index + ": " + value);
//    });

    jQuery.get(ajaxurl, data, function (response) {

        if (response.success === true) {
            jQuery('#images-box').html("");
            jQuery.each(response.data.images, function (index, value) {
                jQuery('#images-box').append(value);
            });
            console.log(response.data.images);
            console.log(jQuery('#post-images'));
//            jQuery('#myprefix-preview-image').replaceWith(response.data.image);
        }
    });
}