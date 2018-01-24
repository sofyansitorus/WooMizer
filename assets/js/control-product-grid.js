/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function ($) {
	'use strict';

	var woomizerControlProductGrid = {
		_defaults: {
			rows: 5,
			cols: 4,
			wrapper: '.woomizer-product-grid-wrap',
			container: '.woomizer-product-grid-picker',
			input: '.woomizer-product-grid-input'
		},

		init: function (options) {
			this.settings = $.extend({}, this._defaults, options);
			this._bindInputs();
			this._bindInputsOutFocus();
			this._bindSquares();
		},

		_bindInputs: function () {
			var self = this;
			$(self.settings.input).focus(function () {
				var curentCols = 0;
				var curentRows = 0;
				var dimensions = $(this).val().split("x");
				if (dimensions.length === 2) {
					curentCols = parseInt(dimensions[0]);
					curentRows = parseInt(dimensions[1]);
                }
                $(self.settings.container).remove();
				if (!$(this).next(self.settings.container).length) {
					self._renderPicker($(this), curentCols, curentRows);
				}
			});
		},

		_bindInputsOutFocus: function () {
			var self = this;
			$(document).click(function (event) {
				if (!$(event.target).closest(self.settings.wrapper).length) {
					$(self.settings.input).next(self.settings.container).remove()
				}
			});
		},

		_renderPicker: function ($input, curentCols, curentRows) {
			var self = this;
			var grid = '<div class="' + self.settings.container.replace('.', '') + '">';
			for (var i = 0; i < self.settings.rows; i++) {
				grid += '<div class="row">';
				for (var c = 0; c < self.settings.cols; c++) {
					if (i < curentRows && c < curentCols) {
						grid += '<div class="product-grid-square highlight"><div class="inner"></div></div>';
					} else {
						grid += '<div class="product-grid-square"><div class="inner"></div></div>';
					}
				}
				grid += '</div>';
			}
			grid += '</div>';

			var $grid = $(grid).height(self.settings.rows * 24).insertAfter($input);
			$grid.find('.row').css({
				height: 'calc(100%/' + self.settings.rows + ')'
			});
			$grid.find('.product-grid-square').css({
				width: 'calc(100%/' + self.settings.cols + ')'
			});
		},

		_bindSquares: function () {
			var self = this;
			$(document).on('mouseover', '.product-grid-square', function () {
				var $this = $(this);
				var col = $this.index() + 1;
				var row = $this.parent().index() + 1;
				$('.product-grid-square').removeClass('highlight');
				$('.row:nth-child(-n+' + row + ') .product-grid-square:nth-child(-n+' + col + ')').addClass('highlight');
				$this.bind('click', function () {
					$this.closest('.woomizer-product-grid-wrap').find(self.settings.input).val(col + 'x' + row).trigger('change');
					$this.closest(self.settings.container).hide().remove();
				});
			});
		}

	};

	$(document).ready(function () {
		woomizerControlProductGrid.init();
	});

})(jQuery);
