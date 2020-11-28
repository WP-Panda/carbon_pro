(function ($) {
    $.fn.WppChangeCountInput = function () {

        var ROW_ELEMENT = '.cart-item-amount';
        var INCREASE_IDENTIFIER = 'icon-plus';
        var TRIGGER_IDENTIFIER = '.icon';

        // row identifier
        var ItemAmountField = $(ROW_ELEMENT); // event handler

        ItemAmountField.find(TRIGGER_IDENTIFIER).on('click', function (event) {
            var trigger = $(event.currentTarget);
            var direction = trigger.hasClass(INCREASE_IDENTIFIER) ? 1 : -1;
            var input = $(trigger.parents(ROW_ELEMENT).find('input').get(0));
            var currentValue = parseInt(input.val()); // avoid minus values

            if (currentValue < 1 && direction < 0) {
                direction = 0;
            }

            input.val(currentValue + direction).trigger('change');

        });
    }
}(jQuery))