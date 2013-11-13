var ajax_add_logo_url = ajax_add_logo_url || null;
var uploadifive_upload_image_url = uploadifive_upload_image_url || null;
var uploadifive_timestamp = uploadifive_timestamp || null;
var uploadifive_token = uploadifive_token || null;
var uploadifive_extra_data =  uploadifive_extra_data || null;
var isotope_timeout;

$(document).ready(function(){
    /* These are for the logos on the site */
    var count = 1;
    $('[rel=uploadifive_single]').each(function(){
        var $this = $(this),
            logo_num = $this.attr( 'data-logo-num'),
            data = $.extend({
                'logo_num':logo_num,
                'timestamp': uploadifive_timestamp,
                'token': uploadifive_token
            },uploadifive_extra_data);

        $this.uploadifive({
            'auto': true,
            'multi': false,
            'uploadScript': uploadifive_upload_image_url,
            'buttonClass': 'btn btn-primary',
            'buttonText': 'Upload New ' + ( count <= 1 ? '' : 'Alt ' ) + 'Logo',
            'formData': data,
            'onInit': onUploadifiveInit,
            'onUploadComplete': onUploadifiveSingleComplete,
            'onUpload': onUploadifiveSingleUpload,
            'removeCompleted': true
        });

        count++;
    });

    var data = $.extend({
            'timestamp': uploadifive_timestamp,
            'token': uploadifive_token
        },uploadifive_extra_data);

    // initialize the multiple file upload
    $('#site-images-upload').uploadifive({
        'auto': true,
        'multi': true,
        'uploadScript': uploadifive_upload_image_url,
        'buttonClass': 'btn btn-primary',
        'buttonText': 'Upload Site Images',
        'formData': data,
        'onInit': onUploadifiveInit,
        'onUploadComplete': onUploadifiveMultipleComplete,
        'onUpload': onUploadifiveMultipleUpload,
        'removeCompleted': true
    });

    moveModals();
});

function moveModals()
{
    var $body = $('body');
    $('[data-modal-moved="0"]').each(function(){
        $(this).detach();
        $body.append($(this));
        $(this).attr('data-modal-moved',1);
    });

    if(isotope_timeout) {
        clearTimeout( isotope_timeout);
    }
    isotope_timeout = setTimeout(function(){
        $('.isotope-container').isotope('reLayout');
    },1000);
}

function reloadSiteSettingsModals()
{
    moveModals();

    $('[data-toggle="new-modal"]').each(function(){
        var $this   = $(this);
        var href    = $this.attr('href');
        var $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
        var option  = $target.data('modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data());

        $this.click(function(e){
            e.preventDefault();
            console.log( 'click' );
            $target.modal('show');
        });
    });
}

var onUploadifiveMultipleComplete = function(file,data) {
    data = $.parseJSON(data);
    var image_id = data.image_id || null;
    if(data.success) {
        // now we need to add this image to the settings
        $.ajax({
            url: ajax_add_site_image + '/' + image_id,
            success: function(res) {
                res = $.parseJSON(res);
                if(res.success) {
                    noty({
                        type: 'success',
                        text: "Logo Uploaded and Added",
                        timeout: 3000
                    });

                    var template = $(res.template);

                    $('.isotope-container').isotope('insert',template);

                    reloadSiteSettingsModals();
                } else {
                    noty({
                        type: 'error',
                        text: data.msg,
                        timeout: 3000
                    });
                }
            }
        });
    } else {
        noty({
            type: 'error',
            text: data.msg,
            timeout: 3000
        });
    }
};

var onUploadifiveMultipleUpload = function(files) {};
var onUploadifiveSingleUpload = function(files) {}
var onUploadifiveInit = function() {$('.uploadifive-button').removeAttr('style').css({cursor:'pointer'}).removeClass('uploadifive-button').find('input').css({'height':'100%','cursor':'pointer'}).parent().parent().parent().css('text-align','center');}
var onUploadifiveSingleComplete = function(file,data) {
    data = $.parseJSON(data);
    var logo_num = data.logo_num || 1,
        type = data.type || 'site',
        image_id = data.image_id;
    if(data.success) {
        // now we need to add this image to the settings
        $.ajax({
            url: ajax_add_logo_url + '/' + logo_num + '/' + image_id,
            success: function(res) {
                res = $.parseJSON(res);
                if(res.success) {
                    noty({
                        type: 'success',
                        text: "Logo Uploaded and Changed",
                        timeout: 3000
                    });
                    var $target = $('#' + (logo_num == 1 ? 'settings-logo' : 'settings-alt-logo')),
                        $img = $('<img class="img-responsive main-logo" src="' + res.image_url + '" data-logo-num="' + logo_num + '" />'),
                        $tools = $('<div class="image-tools pull-right"><span class="btn btn-xs btn-danger" title="Remove" onclick="removeLogo(\'' + ( logo_num == 1 ? 'main' : 'alt' ) + '\');"><i class="icon-remove"></i></span></div>');
                    $target.html( '' );
                    $target.append($img).append($tools);
                    clearSingleQueue();
                    reloadSiteSettingsModals();
                } else {
                    noty({
                        type: 'error',
                        text: data.msg,
                        timeout: 3000
                    });
                }
            }
        });
    } else {
        noty({
            type: 'error',
            text: data.msg,
            timeout: 3000
        });
    }
}

function removeLogo( which )
{
    noty({
        text: 'Are you sure you want to remove this logo?',
        type: 'confirm',
        layout: 'top',
        timeout: 2000,
        modal: true,
        buttons: [
            {
                addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                $noty.close();
                $.ajax({
                    url: ajax_remove_logo_url + '/' + which,
                    success: function(res) {
                        res = $.parseJSON(res);
                        if(res.success) {
                            noty({
                                type: 'success',
                                text: 'Successfully removed site logo',
                                timeout: 3000
                            });
                            var $target = $('#' + (which == 'main' ? 'settings-logo' : 'settings-alt-logo'))
                            $target.html('');
                        } else {
                            noty({
                                type: 'error',
                                text: data.msg,
                                timeout: 3000
                            });
                        }
                    }
                });
                clearSingleQueue();
            }
            },
            {
                addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                $noty.close();
            }
            }
        ]
    });
}

function clearSingleQueue()
{
    $('[rel=uploadifive_single]').each(function(){
        $(this).uploadifive('clearQueue');
    });
}

function clearMultipleQueue()
{

}