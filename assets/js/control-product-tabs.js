/**
 * This file adds handle custom settings control: Woomizer_Control_Product_Tabs.
 */
(function ($) {

    "use strict";

    $(document).ready(function ($) {
        $(".woomizer_product_tabs").accordion({
            collapsible: true,
            heightStyle: "content"
        });
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
            $wrap.find('.woomizer_product_tab_value').val(JSON.stringify(values)).trigger('change');
        });
    });

})(jQuery);