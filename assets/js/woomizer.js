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
			this._bindToggleElements();
		},

		_toggleElement: function (value, $selector) {
			var isHidden = ["no", "none", "hidden"];
			if (isHidden.indexOf(value) !== -1) {
				$selector.addClass('woomizer-toggle-hidden');
			} else {
				$selector.removeClass('woomizer-toggle-hidden');
			}
		},

		_bindToggleElements: function () {
			var self = this;
			$.each(woomizer_params.toggle_elements, function (key, element) {
				self._toggleElement(element.value, $(element.selector));
			});
		},
	};

	woomizerFront.init();

})(jQuery);
