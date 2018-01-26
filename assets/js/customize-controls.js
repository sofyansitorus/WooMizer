/**
 * This file handle Navigating to a URL in the Customizer Preview when a Section is Expanded.
 */
(function (wp, $) {
	'use strict';

	if (!wp || !wp.customize) {
		return;
	}

	/**
	 * woomizerCustomizeControls
	 *
	 */
	var woomizerCustomizeControls = {

		sectionPrefix: 'woomizer_section',

		api: null,

		init: function (api) {
			this.api = api;
			this._bindSections();
		},

		_bindSections: function () {
			this._redirectToUrl('product_loop', woomizer_customize_controls_params.url.product_loop);
			this._redirectToUrl('product_single', woomizer_customize_controls_params.url.product_single);
			this._redirectToUrl('cart', woomizer_customize_controls_params.url.cart);
			this._redirectToUrl('checkout', woomizer_customize_controls_params.url.checkout);
		},

		_redirectToUrl: function (sectionId, url) {
			var self = this;
			sectionId = self._autoPrefix(sectionId);
			self.api.section(sectionId, function (section) {
				var previousUrl, clearPreviousUrl, previewUrlValue;
				previewUrlValue = self.api.previewer.previewUrl;

				clearPreviousUrl = function () {
					previousUrl = null;
				};

				section.expanded.bind(function (isExpanded) {
					if (isExpanded) {
						previousUrl = previewUrlValue.get();
						previewUrlValue.set(url);
						previewUrlValue.bind(clearPreviousUrl);
					} else {
						previewUrlValue.unbind(clearPreviousUrl);
						if (previousUrl) {
							previewUrlValue.set(previousUrl);
						}
					}
				});
			});
		},

		_autoPrefix: function (sectionId) {
			if (sectionId.indexOf(this.sectionPrefix) !== 0) {
				sectionId = this.sectionPrefix + '_' + sectionId;
			}
			return sectionId;
		},

	};

	wp.customize.bind('ready', function () {
		woomizerCustomizeControls.init(wp.customize);
	});

})(window.wp, jQuery);
