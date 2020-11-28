jQuery(function ($) {

    $(document).on('click', '.wpp-close-cookie', function (e) {
        e.preventDefault();
        $('.wpp-cookie-message').remove();
    });

    $(document).on('click', '.wpp-cookie-apply', function (e) {
        e.preventDefault();
        console.log(new Date().getTime());
        Cookies.set('_conf_pol', new Date().getTime(),{ expires: 7 });
        $('.wpp-cookie-message').remove();
    });


});