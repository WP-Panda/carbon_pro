/**/?><!--
<script>
function (t, e) {
    t.exports = function () {
        lastScrollPosition = 0;
        var t = $("#scroll_to_top"),
            e = 0,
            n = !1;

        t.on("click", function (e) {
            e.preventDefault(),
                t.hasClass("back-down") ? (
                    t.attr("title", window.i18nMsg("SCROLL_TOP")).removeClass("back-down"),
                        $.scrollTo(lastScrollPosition, 700, {axis: "y"}),
                        lastScrollPosition = 0
                ) : (
                    lastScrollPosition = window.pageYOffset,
                        t.attr("title", window.i18nMsg("SCROLL_DOWN")).addClass("back-down"),
                        $.scrollTo(0, 700, {axis: "y"})
                )
        }),
            $(window).on("scroll", function () {
                window.pageYOffset > 100 ?
                    n || (t.removeClass("hidden"), n = !0)
                    :
                    n && (t.addClass("hidden"), n = !1),
                e < window.pageYOffset && t.hasClass("back-down") && t.removeClass("back-down"), e = window.pageYOffset
            })
    }
}
</script>-->