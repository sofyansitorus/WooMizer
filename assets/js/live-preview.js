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
			self._bindInjectText('global_flash_sale_text', $('.product span.onsale'));
			self._bindInjectText('product_loop_add_to_cart_btn_text_simple', $('.product-type-simple .product_type_simple'));
			self._bindInjectText('product_loop_add_to_cart_btn_text_variable', $('.product-type-variable .product_type_variable'));
			self._bindInjectText('product_loop_add_to_cart_btn_text_grouped', $('.product-type-grouped .product_type_grouped'));
			self._bindInjectText('product_single_add_to_cart_btn_text', $('.single-product .single_add_to_cart_button'));
			self._bindToggleElement('cart_display_cross_sells', $('.cart-collaterals .cross-sells'));
			self._bindCustomCallback('product_single_tabs', function (setting, settingId) {
				self.api.selectiveRefresh.bind('partial-content-rendered', function (placement) {
					$('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
				});
			});
		},

		_bindInjectText: function (settingId, $selector) {
			var self = this;
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}
			self.api(settingId, function (setting) {
				self._injectText(self.api.value(settingId)(), $selector);
				setting.bind(function (newVal) {
					self._injectText(newVal, $selector);
				});
			});
		},

		_bindInjectAttr: function (settingId, $selector, attrName) {
			var self = this;
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}
			self.api(settingId, function (setting) {
				self._injectAttr(self.api.value(settingId)(), $selector, attrName);
				setting.bind(function (newVal) {
					self._injectAttr(newVal, $selector, attrName);
				});

			});
		},

		_bindToggleElement: function (settingId, $selector) {
			var self = this;
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}
			self.api(settingId, function (setting) {
				self._toggleElement(self.api.value(settingId)(), $selector);
				setting.bind(function (newVal) {
					self._toggleElement(newVal, $selector);
				});
			});
		},

		_bindCustomCallback: function (settingId, callback) {
			var self = this;
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}
			if (typeof callback === "function") {
				self.api(settingId, function (setting) {
					callback(setting, settingId);
				});
			}
		},

		_injectText: function (value, $selector) {
			$selector.text(value);
		},

		_injectAttr: function (value, $selector, attrName) {
			$selector.attr(attrName, value);
		},

		_toggleElement: function (value, $selector) {
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
	};

	$(document).ready(function () {
		woomizerLivePreview.init(wp.customize);
	});

})(window.wp, jQuery);
