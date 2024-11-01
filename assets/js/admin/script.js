Ultimate_Toast = {
    init: function ($) {
        var toast_container = $('.us-toast-container');
        if (toast_container.length == 0) {
            $('body').append($('<div class="us-toast-container" />'));
        }
    },
    show: function ($, message, error) {

        var toast = $('<div class="us-toast" />');
        if (error) {
            toast.addClass('us-toast-error');
        }

        toast.text(message);

        $('.us-toast-container').append(toast);

        setTimeout(function () {
            toast.remove();
        }, 5000);
    }
}


jQuery(function ($) {

    Ultimate_Toast.init($);

    /* tabs */
    $(document).on('click', '.ultimate-tab-nav li a', function (e) {
        var tabContent = $(this).attr('rel');
        if(!tabContent) {
            return;
        }
        e.preventDefault();
        
        $('.ultimate-tab-content .ultimate-tab').hide();
        $('.ultimate-tab-content').find(tabContent).show();

        $('.ultimate-tab-nav li a').removeClass('active-tab');
        $(this).addClass('active-tab');
    });

    $('.ultimate-tab-content .ultimate-tab').hide();

    $('.ultimate-tab-nav li a:first').click();
    /* tabs */

    /* data depend toggle */

    function us_data_dependent($that) {
        var value = $that.val();
        if ($that.is('input') && !$that.is(':checked')) {
            value = '';
        }

        var dataDependEle = $($that.data('depend'));
        dataDependEle.children('.depend').hide();
        dataDependEle.children('.depend-' + value).show();
    }

    $(document).on('change', '.ultimate-depend', function (e) {
        us_data_dependent($(this));
    });

    $('.ultimate-depend').each(function () {
        us_data_dependent($(this));
    });

    /* data depend toggle */
    
    /*data toggle disable/enable*/
    function us_data_toggle_disable_enable($that) {
        var id = $that.data('toggle');
        if($that.is(':checked')) {
            $('#' + id).prop('disabled', true);
        } else {
            $('#' + id).prop('disabled', false);
        }
        
    }

    $(document).on('change', '.ultimate-toggle-ed', function (e) {
        us_data_toggle_disable_enable($(this));
    });

    $('.ultimate-toggle-ed').each(function () {
        us_data_toggle_disable_enable($(this));
    });
    /*data toggle disable/enable*/

    $(document).on('submit', '.ultimate-admin-form', function (e) {
        e.preventDefault();
        var $form = $(this);
        var formData = $form.serialize();
        
        if($form.hasClass('loading')) {
            return;
        }

        $form.addClass('loading');
        
        $.ajax({
            'url': $form.attr('action'),
            'type': 'post',
            'data': formData,
            'success': function (response) {
                if (response.status) {
                    Ultimate_Toast.show($, response.message, false);
                } else {
                    Ultimate_Toast.show($, response.message, true);
                }
            },
            'error': function () {

            },
            'complete': function() {
                $form.removeClass('loading');
            }
        })
    });
    
    $(document).on('click', '._ultimate-upload-media', function(e) {
        e.preventDefault();
        var rel = $(this).data('rel')
        var uploader = wp.media({
            title: 'Custom Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            var attachment = uploader.state().get('selection').first().toJSON();
            $('input[rel='+rel+']').val(attachment.url);
        })
        .open();
    });

    $('.color-field').wpColorPicker();
    
});