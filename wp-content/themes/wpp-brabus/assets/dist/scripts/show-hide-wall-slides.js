jQuery(function ($) {
    /**
     * Показать скрыть слайды для стен
     */

    $(document).on('click', '.more-slider-after', function () {
        let $target = $(this),
            $parent = $target.parents('.wpp-fancy-box-gallery');
        if ($parent.find('.wpp-hide').length) {
            $target.addClass('the-hide').clone().insertAfter($parent.find("div.wpp-grid-slide:last"));
            $target.remove()
            $parent.find('[data-hide]').show().removeClass('wpp-hide');
            $parent.find('.slider-more-text').text(WppAjaxCart.wall_hide_text);
        } else {
            $parent.find('[data-hide]').hide().addClass('wpp-hide');
            $parent.find('.slider-more-text').text(WppAjaxCart.wall_show_text);
            $target.removeClass('the-hide');
        }
    });

})