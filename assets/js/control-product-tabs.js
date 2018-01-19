/**
 * This file adds handle custom settings control: Woomizer_Control_Product_Tabs.
 */
(function ($) {

	"use strict";

	$(document).ready(function ($) {
        // Handle the accordion.
		$('.woomizer_product_tabs').find('.accordion-toggle').click(function () {
			//Expand or collapse this panel.
            $(this).toggleClass('open').next().slideToggle('fast');

            // Remove open class from other accordion title.
            $(".accordion-toggle").not($(this)).removeClass('open');

			//Hide the other panels.
			$(".accordion-content").not($(this).next()).slideUp('fast');
        });

        // Trigger click first accordion panels title.
        $('.woomizer_product_tabs').find('.accordion-toggle').first().trigger('click');

        // Hanlde the form value change.
		$(document).on('change keypress', '.woomizer_product_tabs input', function () {
			var $wrap = $(this).closest('.woomizer_product_tabs_wrap');
			var $parent = $(this).closest('.woomizer_product_tabs');
			var $inputs = $parent.find('input');
			var values = {};
			var input_value, input_name;
			for (var i = 0; i < $inputs.length; i++) {
				var $input = $inputs[i];
				input_name = $($input).data('name');
				switch ($($input).attr('type')) {
					case 'checkbox':
						input_value = $($input).is(':checked') ? 'yes' : 'no';
						break;

					default:
						input_value = $($input).val();
						break;
				}
				values[input_name] = input_value;
            }
            // Insert the form value into the hidden input link and trigger change event.
			$wrap.find('.woomizer_product_tab_value').val(JSON.stringify(values)).trigger('change');
		});
	});

})(jQuery);