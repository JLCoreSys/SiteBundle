(function($,window,undefined){

    $.fn.adminUploadifive = function(element,options) {
        var $this = this,
            config = {
                type: 'site',
                multiple: true,
                timestamp: 0,
                token: null,
                formData: {},
                uploadScript: 'uploadifive.php',
                buttonClass: '',
                buttonText: 'Select Files',
                removeCompleted: false,
                auto: false,
                onUploadFile: function(){},
                onUpload: function(){},
                onUploadComplete: function(){},
                onInit: function(){}
            },
            element = element,
            $element = $(element),
            data = {};

        this.uploadifive = null;

        function init() {
            config = $.extend({},config,options);
            data = $element.data();

            var rel = $element.attr('rel');
            if(rel == 'uploadifive_single') {
                config.multiple = false;
            } else {
                config.multiple = true;
            }

            var text = $element.attr('data-btn-text');
            if(text !== undefined) {
                config.buttonText = text;
            }

            var cls = $element.attr('data-btn-cls');
            if(cls !== undefined) {
                config.buttonClass = cls;
            }

            initUploadifive();
        }

        function initUploadifive()
        {
            var formdata = $.extend({},config.formData,{timestamp:config.timestamp,token:config.token,type:config.type});

            var options = {
                multi: config.multiple,
                auto: config.auto,
                buttonClass: config.buttonClass,
                buttonText: config.buttonText,
                uploadScript: config.uploadScript,
                removeCompleted: config.removeCompleted,
                formData: formdata,
                onInit: onInit,
                onUploadComplete: config.onUploadComplete,
                onUpload: config.onUpload,
                onUploadFile: config.onUploadFile
            };

            $this.uploadifive = $element.uploadifive(options);
        }

        function callback(func, p1, p2, p3, p4) {
            if(typeof func == 'function') {
                func(p1,p2,p3,p4);
            }
        }

        function onInit()
        {
            var id = 'uploadifive-' + $element.attr('id');
            if(config.buttonClass != '') {
                $('#' + id).removeClass('uploadifive-button').removeAttr('style');
            }
            callback(config.onInit);
        }


        init();
    };

    $.fn.AdminUploadifive = function(options) {
        if(options == 'get') {
            return $(this).data('admin_uploadifive');
        }
        return this.each(function(){
            if(undefined === $(this).data('admin_uploadifive')) {
                var table = new $.fn.adminUploadifive($(this),options);
                $(this).data('admin_uploadifive',table);
                return table;
            }
        });
    }

})(jQuery,window,undefined);