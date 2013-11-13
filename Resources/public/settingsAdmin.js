(function($,window,undefined){

    $.fn.adminTable = function(element,options) {
        var $this = this,
            config = {
                usingDataTables: true,
                onMasterCheckboxClick: null,
                onRowCheck: null,
                rowIdIdent: 'data-row-id',
                rowNameIdent: 'data-row-name',
                onHasChecked: null,
                onHasNoneChecked: null,
                contextMenuButtons: []
            },
            element = element,
            $element = $(element),
            $thead = null,
            $tbody = null,
            $rows = [],
            data = null,
            $selected_rows = [],
            $master_cb = null,
            $context = null,
            $context_menu = null,
            $row_clicked = null;

        function init() {
            config = $.extend({},config,options);
            $thead = $element.find('thead');
            $tbody = $element.find('tbody');
            data = $element.data();
            $rows = getRows();

            initRows();
            initContextMenu();
            initCheckboxes();
        }

        function initContextMenu()
        {
            $context = $('<div id="contextMenu" class="context-menu dropdown clearfix" style="position:absolute;display:none"></div>');
            $context_menu = $('<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;"></ul>');

            config.contextMenuButtons = config.contextMenuButtons || {};
            var cnt = 1;
            $.each(config.contextMenuButtons,function(ix,btn){
                var li = $('<li></li>'),
                    lia = $('<a tabindex="' + cnt + '" href="javascript:void(0);"></a>'),
                    icon = btn.icon || null,
                    text = btn.text || 'Item ' + cnt,
                    action = btn.action || null;

                if(icon) {
                    var $icon = '<i class="' + icon + '"></i>';
                    lia.prepend( $icon );
                }
                li.append(lia);
                lia.append(text);
                $context_menu.append( li );
            });

            $context.append($context_menu);
            $('body').append($context);
            $context.hide();

            $(document).click(function () {
                $context.hide();
            });

            $this.reloadContextMenuClicks();
        }

        this.reloadContextMenuClicks = function() {
            $('body').unbind('contextmenu');
            $('body').on('contextmenu', 'table tbody tr', function(e){
                $row_clicked = $(this);
                $context.css({
                    display: 'block',
                    left: e.pageX,
                    top: e.pageY
                });

                return false;
            });

            $context.unbind('click');
            $context.on('click','a',function(){
                var name = $row_clicked.attr(config.rowNameIdent),
                    id = $row_clicked.attr(config.rowIdIdent),
                    text = $(this).text().replace( ' ', '');

                $.each(config.contextMenuButtons,function(ix,btn){
                    if(btn.text.replace( ' ', '' ) == text) {
                        callback(btn.action, $this, $row_clicked, id, name, $context);
                    }
                });

                $context.hide();
            });
        }

        function getRows()
        {
            return $tbody.find('tr');
        }

        function callback( which, param1, param2, param3, param4, param5 ) {
            if(typeof which == 'function' ) {
                which(param1, param2, param3, param4, param5);
            }
        }

        function initCheckboxes()
        {
            $master_cb = $thead.find('[data-master-checkbox]');
            $master_cb.click(onClickMasterCheckbox);
            $this.resetCheckBoxes();
        }

        function onClickMasterCheckbox(e)
        {
            var selected = $this.getSelectedRows();

            if(selected.length != $this.getVisibleRows().length) {
                $this.checkAllRows();
            } else {
                $this.uncheckAllRows();
            }

            callback(config.onMasterCheckboxClick);
            $this.triggerSelected();
        }

        this.getSelectedRows = function() {
            $selected_rows = [];
            $rows.each(function(){
                var $row = $(this),
                    $check = $(this).find('[data-row-check]');

                if($check.is(':checked') && $row.is(':visible')) {
                    $selected_rows.push($row);
                }
            });

            return $selected_rows;
        }

        this.triggerSelected = function() {
            var $selected_rows = $this.getSelectedRows();

            if( $selected_rows.length == 0 ) {
                callback( config.onHasNoneChecked, $this );
            } else {
                callback( config.onHasChecked, $this );
            }
        }

        this.getVisibleRows = function() {
            var rows = [];
            $rows.each(function(){
                if($(this).is(':visible')) {
                    rows.push($(this));
                }
            });
            return rows;
        }

        this.allChecked = function() {
            var selected = $this.getSelectedRows(),
                visible = $this.getVisibleRows();

            return selected.length == visible.length;
        }

        this.checkAllRows = function() {
            $.each($rows, function(ix,row){
                var $row = $(row),
                    $check = $row.find('[data-row-check]');

                if(!$check.is(':checked') && $row.is(':visible')) {
                    $check.prop('checked',true);
                } else if( !$row.is(':visible') ) {
                    $check.prop('checked',false);
                }

               updateUniform('[data-row-check]');
            });
        }

        this.uncheckAllRows = function() {

            $.each($rows, function(ix,row){
                var $row = $(row),
                    $check = $row.find('[data-row-check]');

                $check.prop('checked',false);
            });
            updateUniform('[data-row-check]');
        }

        this.resetCheckBoxes = function()
        {
            $master_cb.attr('checked',false);
            if($master_cb.hasClass('uniform')) {
                $master_cb.parent().removeClass('checked');
            }

            $rows.each(function(){
                var $check = $(this).find('[data-row-check]');
                if($check.data('admin-settings-click') === undefined) {
                    $check.on('click',function(){
                        onRowCheck( $(this), $check );
                    });
                    $check.data('admin-settings-click',true);
                }
                updateUniform($check);
            });

            $this.uncheckAllRows();
            updateUniform('input[type=checkbox]');
            $this.triggerSelected();
        }

        function onRowCheck( $row, $check )
        {
            var allChecked = $this.allChecked();

            if(allChecked) {
                $master_cb.prop('checked',true);
            } else {
                $master_cb.prop('checked',false);
            }

            $element.trigger( 'row_checked', [$row,$check,$this] );
            callback(config.onRowCheck, $row, $check, $this);

            updateUniform($master_cb);
            $this.triggerSelected();
        }

        function updateUniform( selector )
        {
            if($.uniform) {
                $.uniform.update(selector);
            }
        }

        function initRows()
        {
            $rows.each(function(){
                var $row = $(this);
                initRow($row);
            });
        }

        function initRow($row)
        {
            var name = $row.find('[' + config.rowNameIdent + ']').attr(config.rowNameIdent),
                id = $row.find('[' + config.rowIdIdent + ']').attr(config.rowIdIdent);
            $row.attr(config.rowIdIdent,id);
            $row.attr(config.rowNameIdent,name);
        }

        this.version = function() {
            return '0.0.1';
        }

        this.rowsRemoved = function() {
            $this.triggerSelected();
            refreshTable();
        }

        this.rowsAdded = function() {
            $this.triggerSelected();
            refreshTable();
        }

        function refreshTable()
        {
            if(config.usingDataTables) {
                if($.fn.dataTable) {
                    setTimeout(function(
                        if( $this.getVisibleRows().length == 0 ) {
                            var el = $('.dataTables_length').find('select');
                            el.change();
                        }
                    ),750);
                }
            }
        }

        init();
    };

    $.fn.AdminTable = function(options) {
        if(options === 'get') {
            return $(this).data('Admin_Table');
        }
        return this.each(function(){
            if(undefined === $(this).data('Admin_Table')) {
                var table = new $.fn.adminTable($(this),options);
                $(this).data('Admin_Table',table);
                return table;
            }
        });
    }

    $.fn.AdminSettings = function() {
        return this.each(function() {
            if($(this).data('admin_settings') === undefined) {
                var adminSettings = new $.fn.adminSettings($(this),options);
                $(this).data('admin_settings',adminSettings);
            }
        });

    };
})(jQuery,window,undefined);