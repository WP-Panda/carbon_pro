jQuery(function ($) {
    /**
     * Галерея LG
     */
    $(document).ready(function () {
        var e = $(".wpp-fancy-box-gallery"), l = {
            selector: ".wpp-gallery",
            download: 0,
            share: 0,
            zoom: 0,
            mode: 'lg-fade',
            autoplay: 0,
            videoMaxWidth: "100%",
            youtubePlayerParams: {modestbranding: 1, showinfo: 0, rel: 0, iv_load_policy: 3},
            thumbMargin: 2,
            showThumbByDefault: !1,
            thumbWidth: 142,
            thumbContHeight: 100,
            thumbHeight: 80
        };
        e.lightGallery(l), e.on("onAfterOpen.lg", function () {
            e.data("lightGallery").modules.fullscreen.requestFullscreen(), $(".globalClass_25a").hide(), $(document).bind("fullscreenchange webkitfullscreenchange mozfullscreenchange msfullscreenchange", function (n) {
                if (!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullscreenElement || document.msFullscreenElement)) try {
                    e.data("lightGallery").destroy(!0), e.lightGallery(l)
                } catch (e) {
                }
            })
        }), e.on("onCloseAfter.lg", function () {
            $(".globalClass_25a").show()
        })
    });
})