/*
 * TagFinder plugin
 * 
 * Data attributes:
 * - data-control="mc-tagfinder" - enables the plugin on an element
 * - data-option="value" - an option with a value
 *
 * JavaScript API:
 * $('a#someElement').tagFinder({ option: 'value' })
 *
 * Dependences: 
 * - Some other plugin (filename.js)
 */

+function ($) { "use strict";

    // TAGFINDER CLASS DEFINITION
    // ============================

    var TagFinder = function(element, options) {
        var self       = this
        this.options   = options
        this.$el       = $(element)
        
        console.log(options.tags);
        $('div', this.$el).magicSuggest({
            data: self.options.tags,
            displayField: 'value',
            placeholder: '',
            allowFreeEntries: false,
            maxDropHeight: 145,
            highlight: false,
            value: self.options.value,
            name: self.options.name
        });

    }

    TagFinder.DEFAULTS = {
        value: []
    }


    // TAGFINDER PLUGIN DEFINITION
    // ============================

    var old = $.fn.tagFinder

    $.fn.tagFinder = function(option) {
        var args = Array.prototype.slice.call(arguments, 1), result
        this.each(function () {
            var $this   = $(this)
            var data    = $this.data('macrobit.tagfinder')
            var options = $.extend({}, TagFinder.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('macrobit.tagfinder', (data = new TagFinder(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : this
    }

    $.fn.tagFinder.Constructor = TagFinder

    // TAGFINDER NO CONFLICT
    // =================

    $.fn.tagFinder.noConflict = function() {
        $.fn.tagFinder = old
        return this
    }

    // TAGFINDER DATA-API
    // ===============
    $(document).render(function() {
        $('[data-control="mc-tagfinder"]').tagFinder()
    })

}(window.jQuery);