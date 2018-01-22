/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function ($, wp) {

	"use strict";

	if ('undefined' === typeof wp || 'undefined' === typeof wp.customize) {
		return;
	}

	// Customizer live preview: woomizer_general_flash_sale_text
	wp.customize('woomizer_general_flash_sale_text', function (setting) {
		var $selector = $('.product span.onsale');
		$selector.text(wp.customize.value('woomizer_general_flash_sale_text')());
		setting.bind(function (newVal) {
			$selector.text(newVal);
		});
	});

	// Customizer live preview: woomizer_product_loop_add_to_cart_btn_text_simple
	wp.customize('woomizer_product_loop_add_to_cart_btn_text_simple', function (setting) {
		var $selector_simple = $('.product-type-simple .product_type_simple');
		$selector_simple.text(wp.customize.value('woomizer_product_loop_add_to_cart_btn_text_simple')());
		setting.bind(function (newVal) {
			$selector_simple.text(newVal);
		});
	});

	// Customizer live preview: woomizer_product_loop_add_to_cart_btn_text_variable
	wp.customize('woomizer_product_loop_add_to_cart_btn_text_variable', function (setting) {
		var $selector_variable = $('.product-type-variable .product_type_variable');
		$selector_variable.text(wp.customize.value('woomizer_product_loop_add_to_cart_btn_text_variable')());
		setting.bind(function (newVal) {
			$selector_variable.text(newVal);
		});
	});

	// Customizer live preview: woomizer_product_loop_add_to_cart_btn_text_grouped
	wp.customize('woomizer_product_loop_add_to_cart_btn_text_grouped', function (setting) {
		var $selector_grouped = $('.product-type-grouped .product_type_grouped');
		$selector_grouped.text(wp.customize.value('woomizer_product_loop_add_to_cart_btn_text_grouped')());
		setting.bind(function (newVal) {
			$selector_grouped.text(newVal);
		});
	});

	// Customizer live preview: woomizer_product_single_add_to_cart_btn_text
	wp.customize('woomizer_product_single_add_to_cart_btn_text', function (setting) {
		var $selector = $('.single-product .single_add_to_cart_button');
		$selector.text(wp.customize.value('woomizer_product_single_add_to_cart_btn_text')());
		setting.bind(function (newVal) {
			$selector.text(newVal);
		});
	});

	// Customizer live preview: woomizer_product_single_tabs
	wp.customize('woomizer_product_single_tabs', function (setting) {
		wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
			$('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
		});
	});

})(jQuery, wp);
