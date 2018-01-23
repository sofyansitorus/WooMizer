/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function ($) {
    'use strict';

	/**
	 * wp.customize.woomizerFront
	 *
	 */
    var woomizerFront = {

        init: function () {
            this._addToggles();
        },

        _toggleElement: function (value, $selector) {
            $selector.addClass('woomizer-toggle');
            if (value == 'no' || value == 'none' || value == 'hidden') {
                $selector.addClass('woomizer-hidden');
                $selector.removeClass('woomizer-visible');
            } else {
                $selector.addClass('woomizer-visible');
                $selector.removeClass('woomizer-hidden');
            }
        },

        _addToggles: function () {
            var self = this;
            $.each(woomizer_params.toggle_elements, function (key, element) {
                self._toggleElement(element.value, $(element.selector));
            });
        },
    };

    woomizerFront.init(wp.customize);

})(jQuery);