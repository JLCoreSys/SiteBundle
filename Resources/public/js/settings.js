//var uploadifive_upload_image_url = '{{ path( 'media_upload_image' ) }}';
//var uploadifive_timestamp = '{{ timestamp() }}';
//var uploadifive_token = '{{ md5( secret_key ~ timestamp() ) }}';
//var uploadifive_extra_data = {'type':'site'};
//var ajax_add_logo_url = '{{ path( 'admin_ajax_site_add_logo' ) }}';
//var ajax_remove_logo_url = '{{ path( 'admin_ajax_site_remove_logo' ) }}';
//var ajax_add_site_image = '{{ path( 'admin_ajax_site_add_image' ) }}';
//var ajax_remove_site_image = '{{ path( 'admin_ajax_site_add_image' ) }}';
//{% set timestamp = timestamp() %}
//var timestamp = '{{ timestamp }}';
//var token = '{{ md5( secret_key ~ timestamp ) }}';

$(document).ready(function(){
    $('.save-settings-btn').click(function(){
        $('#settings-form').submit();
    });
    var hash = window.location.hash;
    $('.nav li a').click(function(e){
        window.location.hash = $(this).attr('href');
    });
    $(hash).tab('show');
    $('[data-settings-tab]').click(function(e){
        e.preventDefault();
        var hash = $(this).attr('data-settings-tab');
        $("a[href='" + hash + "']").click();
    });

//    logoEqualHeights();
//
    setTimeout(function(){
        initImagesTable();
//        setTimeout(function(){
//            logoEqualHeights();
//            setTimeout(function(){
//                logoEqualHeights();
//            }, 1000);
//        }, 1000);
    },750);
});

var sa_table = null;

function initImagesTable()
{
    $('#site-images-table').AdminTable({
        onHasChecked: function showCheckDependent($table){$('.require-checked:hidden').show('slow');},
        onHasNoneChecked: function hideCheckDependent($table) {$('.require-checked:visible').hide('slow');},
        contextMenuButtons: [
            {
                text: 'Edit',
                icon: 'icon-edit',
                action: editImage
            },{
                text: 'View',
                icon: 'icon-search blue',
                action: viewImage
            },{
                text: 'Remove',
                icon: 'icon-remove red',
                action: removeImage
            }
        ],
        usingDataTables: true
    });

    sa_table = $('#site-images-table').AdminTable('get');
}

var single_config1 = { type: 'site',multiple: false,timestamp: timestamp,token: token,uploadScript: uploadifive_upload_image_url,buttonClass: 'btn btn-primary',buttonText: 'Select Logo',removeCompleted: true,auto: true,formData: {logo_num:1},onUploadComplete: logoUploadComplete};
var single_config2 = $.extend({},single_config1,{formData:{logo_num:2}});
var multiple_config = $.extend({},single_config1,{formData:{},onUploadComplete:uploadImagesComplete});
var edit_modal = null;
var view_modal = null;

$('#settings_type_logo_file_one').AdminUploadifive(single_config1);
$('#settings_type_logo_file_two').AdminUploadifive(single_config2);
$('#site-images-upload').AdminUploadifive(multiple_config);

function logoUploadComplete(file,data)
{
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
                        $tools = $('<div class="image-tools pull-right"><span class="btn btn-xs btn-danger pull-right" title="Remove" onclick="removeLogo(\'' + ( logo_num == 1 ? 'main' : 'alt' ) + '\');"><i class="icon-remove"></i></span><code class="pull-left">{{ site' + ( logo_num == 1 ? '' : 'Alt' ) + 'Logo( \'classes\', {\'attr\':\'val\'} ) }}</code></div>');
                    $target.html( '' );
                    $target.append($img).append($tools);
                    logoEqualHeights();
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

function uploadImagesComplete(file,data) {
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
                        text: "Image Uploaded and Added",
                        timeout: 500
                    });

                    var template = $(res.template);
                    sa_table.addRow( template );
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

function editImage(table,$r,id,name,$context)
{
    edit_modal = $('#edit-image-modal').modal({
        remote: ajax_edit_image_url + '/' + id
    });
    edit_modal.on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
        $(this).empty();
        edit_modal = null;
    })
}

function viewImage(table,$r,id,name,$context)
{
    view_modal = $('#view-image-modal').modal({
        remote: ajax_view_image_url + '/' + id
    });
    view_modal.on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
        $(this).empty();
        view_modal = null;
    })
}

function formSavedCallback(res,status,isNew)
{
    res = $.parseJSON(res);
    var data = [
        null,
        null,
        res.image.title
    ];
    var $row = $('[data-row-id=' + res.image.id + ']');
    sa_table.updateRow($row, data );
    $('.close-modal').click();
}

function removeImage(table,$r,id,name)
{
    $r = $r || $('#site-images-table tbody').find('tr[data-row-id="' + id + '"]');

    noty({
        text: 'Are you sure you want to remove this image?',
        type: 'confirm',
        layout: 'top',
        timeout: 2000,
        modal: true,
        buttons: [
            {
                addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                $noty.close();
                $.ajax({
                    url: ajax_remove_site_image + '/' + id,
                    success: function(res) {
                        res = $.parseJSON(res);
                        if(res.success) {
                            sa_table.removeRow($r);
                        } else {
                            noty({
                                type: 'error',
                                text: res.msg,
                                timeout: 3000
                            });
                        }
                    }
                });
            }
            },
            {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {$noty.close();}}
        ]
    });
}

function removeSelectedImages()
{
    var $selected_rows = sa_table.getSelectedRows();
    var ids = [];
    $.each($selected_rows, function(ix,row){
        var $this = $(row),
            id = $this.attr('data-row-id'),
            name = $this.attr('data-row-name');
        ids.push(id);
    });

    noty({
        text: 'Are you sure you want to remove these <strong class="red">' + $selected_rows.length + '</strong> images?',
        type: 'confirm',
        layout: 'top',
        timeout: 2000,
        modal: true,
        buttons: [
            {
                addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                $noty.close();
                $.ajax({
                    url: ajax_remove_site_images,
                    data: {'ids':ids},
                    type: 'post',
                    success:function(res) {
                        res = $.parseJSON(res);
                        if(res.success) {
                            var wait = 300;
                            $.each($selected_rows, function(ix,row){
                                var $this = $(row),
                                    id = $this.attr('data-row-id'),
                                    name = $this.attr('data-row-name');
                                setTimeout(function(){
                                    sa_table.removeRow($this);
                                },wait);
                                wait += 200;
                            });
                        }
                    }
                });
            }
            },
            {addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {$noty.close();}}
        ]
    });
}

function logoEqualHeights()
{
    $('.equal-height').css('height','auto');
    setTimeout(function(){
        $('.equal-height').equalHeights();
    },500);
}