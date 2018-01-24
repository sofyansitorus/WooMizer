/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function (wp, $) {
	'use strict';

	if (!wp || !wp.customize) {
		return;
	}

	/**
	 * wp.customize.woomizerLivePreview
	 *
	 */
	var woomizerLivePreview = {

		api: null,

		init: function (api) {
			this.api = api;
			this._bindSettings();
		},

		_bindSettings: function () {
			var self = this;
			self._injectText('global_flash_sale_text', $('.product span.onsale'));
			self._injectText('product_loop_add_to_cart_btn_text_simple', $('.product-type-simple .product_type_simple'));
			self._injectText('product_loop_add_to_cart_btn_text_variable', $('.product-type-variable .product_type_variable'));
			self._injectText('product_loop_add_to_cart_btn_text_grouped', $('.product-type-grouped .product_type_grouped'));
			self._injectText('product_single_add_to_cart_btn_text', $('.single-product .single_add_to_cart_button'));
			self._toggleElement('woomizer_cart_display_cross_sells', $('.cart-collaterals .cross-sells'));
			self._customCallback('product_single_tabs', function (setting, settingId) {
				self.api.selectiveRefresh.bind('partial-content-rendered', function (placement) {
					$('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
				});
			});
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
			self.api(settingId, function (setting) {
				_toggleElement(self.api.value(settingId)(), $selector);
				setting.bind(function (newVal) {
					_toggleElement(newVal, $selector);
				});
			});

			function _toggleElement(value, $selector) {
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
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}
			return settingId;
		},

	};

	$(document).ready(function () {
		woomizerLivePreview.init(wp.customize);
	});

})(window.wp, jQuery);
