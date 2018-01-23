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
			this._addSettings();
		},

		_toggleElement: function (value, $selector) {
			$selector.addClass('woomizer-toggle-preview');
			if (value == 'no' || value == 'none' || value == 'hidden') {
				$selector.addClass('woomizer-hidden');
				$selector.removeClass('woomizer-visible');
			} else {
				$selector.addClass('woomizer-visible');
				$selector.removeClass('woomizer-hidden');
			}
		},

		_registerSetting: function (settingId) {
			var self = this;
			if (settingId.indexOf(woomizer_live_preview_params.prefix) !== 0) {
				settingId = woomizer_live_preview_params.prefix + '_' + settingId;
			}

			var longFunction = '_bindSetting_' + settingId;
			if (typeof self[longFunction] === "function") {
				self.api(id, function (setting) {
					self[longFunction](setting, settingId);
				});
				return;
			}

			var shortFunction = '_bindSetting_' + settingId.replace(woomizer_live_preview_params.prefix + '_', '');
			if (typeof self[shortFunction] === "function") {
				self.api(settingId, function (setting) {
					self[shortFunction](setting, settingId);
				});
				return;
			}
		},

		_addSettings: function () {
			this._registerSetting('global_flash_sale_text');
			this._registerSetting('product_loop_add_to_cart_btn_text_simple');
			this._registerSetting('product_loop_add_to_cart_btn_text_variable');
			this._registerSetting('product_loop_add_to_cart_btn_text_grouped');
			this._registerSetting('product_single_add_to_cart_btn_text');
			this._registerSetting('product_single_tabs');
			this._registerSetting('cart_display_cross_sells');
		},

		_bindSetting_global_flash_sale_text: function (setting, settingId) {
			var self = this;
			var $selector = $('.product span.onsale');
			$selector.text(self.api.value(settingId)());
			setting.bind(function (newVal) {
				$selector.text(newVal);
			});
		},

		_bindSetting_product_loop_add_to_cart_btn_text_simple: function (setting, settingId) {
			var self = this;
			var $selector = $('.product-type-simple .product_type_simple');
			$selector.text(self.api.value(settingId)());
			setting.bind(function (newVal) {
				$selector.text(newVal);
			});
		},

		_bindSetting_product_loop_add_to_cart_btn_text_variable: function (setting, settingId) {
			var self = this;
			var $selector = $('.product-type-variable .product_type_variable');
			$selector.text(self.api.value(settingId)());
			setting.bind(function (newVal) {
				$selector.text(newVal);
			});
		},

		_bindSetting_product_loop_add_to_cart_btn_text_grouped: function (setting, settingId) {
			var self = this;
			var $selector = $('.product-type-grouped .product_type_grouped');
			$selector.text(self.api.value(settingId)());
			setting.bind(function (newVal) {
				$selector.text(newVal);
			});
		},

		_bindSetting_product_single_add_to_cart_btn_text: function (setting, settingId) {
			var self = this;
			var $selector = $('.single-product .single_add_to_cart_button');
			$selector.text(self.api.value(settingId)());
			setting.bind(function (newVal) {
				$selector.text(newVal);
			});
		},

		_bindSetting_product_single_add_to_cart_btn_text: function (setting, settingId) {
			var self = this;
			self.api.selectiveRefresh.bind('partial-content-rendered', function (placement) {
				$(document).on('init', '#rating', function () {
					if ($('.comment-form-rating').find('p.stars').length > 1) {
						$('.comment-form-rating').find('p.stars:not(first-child)').remove();
					}
				});
				$('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
			});
		},

		_bindSetting_cart_display_cross_sells: function (setting, settingId) {
			var self = this;
			var $selector = $('.cart-collaterals .cross-sells');
			self._toggleElement(self.api.value(settingId)(), $selector);
			setting.bind(function (newVal) {
				self._toggleElement(newVal, $selector);
			});
		}
	};

	$(document).ready(function () {
		woomizerLivePreview.init(wp.customize);
	});

})(window.wp, jQuery);
