/*
 * TagFinder plugin
 * 
 * Data attributes:
 * - data-control="tagfinder" - enables the plugin on an element
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
        this.initialValues = null;
        this.formName = null;
        this.init()
    }  

    function fetchTags(callback) {
        $.ajax({
            type: 'POST',
            beforeSend: function (request) {
                request.setRequestHeader('X-OCTOBER-REQUEST-HANDLER', 'onFetchTags');
            },
            converters: {
                'text json': function(value) {
                    var parsedValue = jQuery.parseJSON(value);
                    var tags = JSON.parse(parsedValue.result);
                    var source = [];
                    for (var key in tags) {
                        source.push({
                            id: tags[key],
                            value: key
                        });
                    }
                    parsedValue.result = source;
                    return parsedValue;
                }
            },
            success: function(msg) {
                callback(msg.result);
            }
        });
    }

    TagFinder.DEFAULTS = {
    }

    TagFinder.prototype.init = function() {
        var self = this;
        var $this = this.$el;
        fetchTags(function(tags) {
            $($this.find('div').magicSuggest({
                data: tags,
                displayField: 'value',
                allowFreeEntries: false,
                maxDropHeight: 145,
                highlight: false,
                value: self.initialValues || []
            })).on('selectionchange', function() {
                $this.find('.tagfinder-widget-hidden').val(this.getValue());
            })
        })
    }

    TagFinder.prototype.setInitialValues = function(value) {
        this.initialValues = JSON.parse(value);
    }

    TagFinder.prototype.setFormName = function(value) {
        this.formName = value;
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
        $('[data-control="tagfinder"]').tagFinder()
    })

}(window.jQuery);