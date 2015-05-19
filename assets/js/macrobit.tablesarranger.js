/*
 * Tables arranger control. 
 *
 * Data attributes:
 * - data-control="tablesarranger" - enables the plugin
 *
 */

+function ($) { 

    "use strict";

    var TablesArranger = function(element, options) {

        this.$el = $(element);

        this.options = options || {};

        var self = this;

        this.$el.css({
            'height': this.$el.data('height'),
            'width': this.$el.data('width')
        });

        this.$el.children('.tables-arranger-table').each(function() {
            var data, $field, $this = $(this);
            $field = $('input', $this);
            
            $this.data('macrobit.table-arranger.position', 
                    (data = JSON.parse($field.val())));

            function onDrag(ev, ui) {
                data.position = ui.position;
                $field.val(JSON.stringify(data));
            }

            $this.draggable({
                containment: self.$el,
                appendTo: self.$el,
                drag: onDrag
            });

            $this.css(typeof data.position === 'string' ? JSON.parse(data.position) : data.position);
        });

    }

    TablesArranger.DEFAULTS = {}

    // TABLES ARRANGER PLUGIN DEFINITION
    // ===================================

    var old = $.fn.tablesArranger;

    $.fn.tablesArranger = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('macrobit.tables-arranger');
            var options = $.extend({}, TablesArranger.DEFAULTS, 
                    $this.data(), typeof option == 'object' && option);

            $this.data('macrobit.tables-arranger', (data = new TablesArranger(this, options)));
        });
    }

    $.fn.tablesArranger.Constructor = TablesArranger;

    // TABLES ARRANGER NO CONFLICT
    // ===================================

    $.fn.tablesArranger.noConflict = function() {
        $.fn.tablesArranger = old;
        return this;
    }

    // TABLES ARRANGER DATA-API
    // ===================================

    $(document).on('render', function(){
        $('div[data-control=tablesarranger]').tablesArranger();
    });

}(jQuery);