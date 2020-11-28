jQuery(function ($) {
    //Это сохранение Карзины оформленно как плагин
    $.fn.WppSaveCart=function(){var a={action:"wpp_save_cart_details",security:WppAjaxCart.security,actual_url:WppAjaxCart.actual_url};$.post(WppAjaxCart.ajax_url,a,function(a){return a.success&&($(".saved-link").html(a.data.url),$(".wpp-copy-btn").attr("data-clipboard-text",a.data.url),$(".copy-clipboard-row").show()),!1})};
    //Это кастомный инпут количества
   !function(n){n.fn.WppChangeCountInput=function(){n(".cart-item-amount").find(".icon").on("click",function(t){var a=n(t.currentTarget),i=a.hasClass("icon-plus")?1:-1,c=n(a.parents(".cart-item-amount").find("input").get(0)),e=parseInt(c.val());e<1&&i<0&&(i=0),c.val(e+i).trigger("change")})}}(jQuery);

    $.when(
        $.getScript("/wp-content/themes/wpp-brabus/assets/dist/scripts/complite.js"),
        $.getScript("/wp-content/themes/wpp-brabus/assets/dist/scripts/send-contacts.js"),
        $.getScript("/wp-content/themes/wpp-brabus/assets/dist/scripts/remove-error-notes.js"),
        $.getScript("/wp-content/themes/wpp-brabus/assets/dist/scripts/send-contacts.js"),

        $.Deferred(function (deferred) {
            $(deferred.resolve);
        })
    ).done(function () {
    });
});