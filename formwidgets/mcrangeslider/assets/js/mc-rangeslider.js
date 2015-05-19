/*
 * RangeSlider plugin
 * 
 * Data attributes:
 * - data-control="mc-rangeslider" - enables the plugin on an element
 * - data-option="value" - an option with a value
 *
 * JavaScript API:
 * $('a#someElement').rangeSlider({ option: 'value' })
 *
 * Dependences: 
 * - Some other plugin (filename.js)
 */

+function ($) { "use strict";

    // RANGESLIDER CLASS DEFINITION
    // ============================

    var RangeSlider = function(element, options) {
        var self       = this
        this.options   = options
        this.$el       = $(element)

        var $lower = $('input.mc-rangeslider-lower', this.$el);
        var $upper = $('input.mc-rangeslider-upper', this.$el);
        var $slider = $('div.mc-rangeslider-slider', this.$el);

        function minsToText(value) {
            var hours = '' + Math.floor(value / 60);          
            var minutes = '' + value % 60;
            return (hours.length < 2 ? '0' + hours : hours) + 
                ':' + (minutes.length < 2 ? minutes + '0' : minutes);
        }

        var format = {
            to: function(value) {
                return minsToText(Math.round(value));
            },
            from: function(value) {
                if (value.match(/:/g)) {
                    return f(value);
                }
                return value;
            }
        }

        $slider.noUiSlider({
            start: [f(options.from), f(options.to)],
            connect: true,
            margin: options.margin,
            step: options.step,
            range: {
                'min': f(options.min),
                'max': f(options.max)
            },
            format: format
        });

        $slider.Link('lower').to($lower);
        $slider.Link('upper').to($upper);

    }  

    RangeSlider.DEFAULTS = {
        from: 540,
        to: 1080,
        margin: 60,
        step: 30,
        min: 0,
        max: 1440
    }

    function f(value) {
        if (typeof value === 'number') {
            return value;
        }
        var splitedValue = value.split(':');
        return parseInt(splitedValue[0]) * 60 + parseInt(splitedValue[1]);
    }


    // RANGESLIDER PLUGIN DEFINITION
    // ============================

    var old = $.fn.rangeSlider

    $.fn.rangeSlider = function(option) {
        var args = Array.prototype.slice.call(arguments, 1), result
        this.each(function () {
            var $this   = $(this)
            var data    = $this.data('macrobit.rangeslider')
            var options = $.extend({}, RangeSlider.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('macrobit.rangeslider', (data = new RangeSlider(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : this
    }

    $.fn.rangeSlider.Constructor = RangeSlider

    // RANGESLIDER NO CONFLICT
    // =================

    $.fn.rangeSlider.noConflict = function() {
        $.fn.rangeSlider = old
        return this
    }

    // RANGESLIDER DATA-API
    // ===============
    $(document).render(function() {
        $('[data-control="mc-rangeslider"]').rangeSlider()
    })

}(jQuery);