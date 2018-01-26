/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function (wp, $) {
	'use strict';

	if (!wp || !wp.customize) {
		return;
	}

	/**
	 * woomizerCustomizePreview
	 *
	 */
	var woomizerCustomizePreview = {

		settingPrefix: 'woomizer_setting',

		api: null,

		init: function (api) {
			this.api = api;
			this._bindSettings();
		},

		_bindSettings: function () {
			var self = this;

			// Product loop settings section.
			self._injectText('flash_sale_loop', $(".archive .product .onsale, .single .product .product .onsale"));
			self._injectText('add_to_cart_button_simple', $('.product-type-simple .product_type_simple'));
			self._injectText('add_to_cart_button_variable', $('.product-type-variable .product_type_variable'));
			self._injectText('add_to_cart_button_grouped', $('.product-type-grouped .product_type_grouped'));

			// Product single settings section.
			self._injectText('flash_sale_single', $(".single .product .onsale").not(".single .product .product .onsale"));
			self._injectText('add_to_cart_button', $('.single-product .single_add_to_cart_button'));
			self._customCallback('product_tabs', function (setting, settingId) {
				self.api.selectiveRefresh.bind('partial-content-rendered', function (placement) {
					$('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
				});
			});

			// Cart settings section.
			self._toggleElement('display_cross_sells', $('.cart-collaterals .cross-sells'));
		},

		_injectText: function (settingId, $selector) {
			var self = this;
			settingId = self._autoPrefix(settingId);
			self.api(settingId, function (setting) {
				$selector.text(self.api.value(settingId)());
				setting.bind(function (newVal) {
					$selector.text(newVal);
				});
			});
		},

		_injectAttr: function (settingId, $selector, attrName) {
			var self = this;
			settingId = self._autoPrefix(settingId);
			self.api(settingId, function (setting) {
				$selector.attr(attrName, self.api.value(settingId)());
				setting.bind(function (newVal) {
					$selector.attr(attrName, newVal);
				});
			});
		},

		_toggleElement: function (settingId, $selector) {
			var self = this;
			settingId = self._autoPrefix(settingId);

			var _toggleElement = function (value, $selector) {
				var isHidden = ["no", "none", "hidden"];
				$selector.addClass('woomizer-toggle-preview');
				if (isHidden.indexOf(value) !== -1) {
					$selector.addClass('woomizer-hidden');
					$selector.removeClass('woomizer-visible');
				} else {
					$selector.addClass('woomizer-visible');
					$selector.removeClass('woomizer-hidden');
				}
			}

			self.api(settingId, function (setting) {
				_toggleElement(self.api.value(settingId)(), $selector);
				setting.bind(function (newVal) {
					_toggleElement(newVal, $selector);
				});
			});
		},

		_customCallback: function (settingId, callback) {
			var self = this;
			settingId = self._autoPrefix(settingId);
			if (typeof callback === "function") {
				self.api(settingId, function (setting) {
					callback(setting, settingId);
				});
			}
		},

		_autoPrefix: function (settingId) {
			if (settingId.indexOf(this.settingPrefix) !== 0) {
				settingId = this.settingPrefix + '_' + settingId;
			}
			return settingId;
		},

	};

	$(document).ready(function () {
		woomizerCustomizePreview.init(wp.customize);
	});

})(window.wp, jQuery);
