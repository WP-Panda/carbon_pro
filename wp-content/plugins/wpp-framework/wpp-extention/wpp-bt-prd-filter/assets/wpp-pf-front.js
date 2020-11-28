function parse_str(str, array) {

    var strArr = String(str).replace(/^&/, '').replace(/&$/, '').split('&')
    var sal = strArr.length
    var i
    var j
    var ct
    var p
    var lastObj
    var obj
    var chr
    var tmp
    var key
    var value
    var postLeftBracketPos
    var keys
    var keysLen

    var _fixStr = function (str) {
        return decodeURIComponent(str.replace(/\+/g, '%20'))
    }

    var $global = (typeof window !== 'undefined' ? window : global)
    $global.$locutus = $global.$locutus || {}
    var $locutus = $global.$locutus
    $locutus.php = $locutus.php || {}

    if (!array) {
        array = $global
    }

    for (i = 0; i < sal; i++) {
        tmp = strArr[i].split('=')
        key = _fixStr(tmp[0])
        value = (tmp.length < 2) ? '' : _fixStr(tmp[1])

        while (key.charAt(0) === ' ') {
            key = key.slice(1)
        }

        if (key.indexOf('\x00') > -1) {
            key = key.slice(0, key.indexOf('\x00'))
        }

        if (key && key.charAt(0) !== '[') {
            keys = []
            postLeftBracketPos = 0

            for (j = 0; j < key.length; j++) {
                if (key.charAt(j) === '[' && !postLeftBracketPos) {
                    postLeftBracketPos = j + 1
                } else if (key.charAt(j) === ']') {
                    if (postLeftBracketPos) {
                        if (!keys.length) {
                            keys.push(key.slice(0, postLeftBracketPos - 1))
                        }

                        keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos))
                        postLeftBracketPos = 0

                        if (key.charAt(j + 1) !== '[') {
                            break
                        }
                    }
                }
            }

            if (!keys.length) {
                keys = [key]
            }

            for (j = 0; j < keys[0].length; j++) {
                chr = keys[0].charAt(j)

                if (chr === ' ' || chr === '.' || chr === '[') {
                    keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1)
                }

                if (chr === '[') {
                    break
                }
            }

            obj = array

            for (j = 0, keysLen = keys.length; j < keysLen; j++) {
                key = keys[j].replace(/^['"]/, '').replace(/['"]$/, '')
                lastObj = obj

                if ((key === '' || key === ' ') && j !== 0) {
                    // Insert new dimension
                    ct = -1

                    for (p in obj) {
                        if (obj.hasOwnProperty(p)) {
                            if (+p > ct && p.match(/^\d+$/g)) {
                                ct = +p
                            }
                        }
                    }

                    key = ct + 1
                }

                // if primitive value, replace with object
                if (Object(obj[key]) !== obj[key]) {
                    obj[key] = {}
                }

                obj = obj[key]
            }

            lastObj[key] = value
        }
    }
}

jQuery(function ($) {

    function submit_filter(){
        var $out = {},
            $fin = '',
            $ind = 1,
            $val = $('#collapseFilters').serialize(),
            $href = location.protocol + '//' + location.host + location.pathname;

        parse_str($val, $out);
        console.log($out);

        $.each($out, function (i, v) {
            var $ind2 = 1;
            $and = $ind == 1 ? '' : '&';

            if (i !== 'min' && i !== 'max') {
                $fin = $fin + $and + i + '=';
                $.each(v, function (i1, v1) {
                    $comma = $ind2 == 1 ? '' : ',';
                    $fin = $fin + $comma + v1;
                    $ind2++;
                })
            } else {
                $fin = $fin + $and + i + '=' + v;
            }

            $ind++;
        })
        window.location.href = $href + '?' + $fin;
    }
    $(document).on('change', '.filter-options-list [type="checkbox"]', function () {
        submit_filter();
    });
    $(document).on('click', '#wpp-bt-fs', function () {
        submit_filter();
    });

    $(document).on('click', '.unchecked-cross', function (e) {
        e.preventDefault();
        var $target = $(this).attr('data-remove');
        $(this).parents('li').remove();
        $('#' + $target).prop("checked", false);
        $('#' + $target).trigger("change");
    });
});