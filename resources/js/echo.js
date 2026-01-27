const echo = (function () {
    'use strict';

    var store = [],
        offset,
        throttle,
        poll,
        detach,
        unload,
        callback = function () { };

    var inView = function (element, view) {
        if (element.offsetParent === null) {
            return false;
        }
        var box = element.getBoundingClientRect();
        return (
            box.right >= view.l &&
            box.bottom >= view.t &&
            box.left <= view.r &&
            box.top <= view.b
        );
    };

    var debounceOrThrottle = function () {
        if (!detach) {
            clearTimeout(poll);
            poll = setTimeout(function () {
                echo.render();
                poll = null;
            }, throttle);
        }
    };

    var echo = {};

    echo.init = function (opts) {
        opts = opts || {};
        var offsetAll = opts.offset || 0;
        var offsetVertical = opts.offsetVertical || offsetAll;
        var offsetHorizontal = opts.offsetHorizontal || offsetAll;
        var getOffset = function (val, defaultVal) {
            return parseInt(val || defaultVal, 10);
        };
        offset = {
            t: getOffset(opts.offsetTop, offsetVertical),
            b: getOffset(opts.offsetBottom, offsetVertical),
            l: getOffset(opts.offsetLeft, offsetHorizontal),
            r: getOffset(opts.offsetRight, offsetHorizontal),
        };
        throttle = getOffset(opts.throttle, 250);
        detach = opts.debounce !== false;
        unload = !!opts.unload;
        callback = opts.callback || callback;
        echo.render();
        if (document.addEventListener) {
            window.addEventListener('scroll', debounceOrThrottle, false);
            window.addEventListener('load', debounceOrThrottle, false);
        } else {
            window.attachEvent('onscroll', debounceOrThrottle);
            window.attachEvent('onload', debounceOrThrottle);
        }
    };

    echo.render = function (scope) {
        var nodes = (scope || document).querySelectorAll(
            '[data-echo], [data-echo-background]'
        );
        var length = nodes.length;
        var src, elem;
        var view = {
            l: 0 - offset.l,
            t: 0 - offset.t,
            b: (window.innerHeight || document.documentElement.clientHeight) + offset.b,
            r: (window.innerWidth || document.documentElement.clientWidth) + offset.r,
        };
        for (var i = 0; i < length; i++) {
            elem = nodes[i];
            if (inView(elem, view)) {
                if (unload) {
                    elem.setAttribute('data-echo-placeholder', elem.src);
                }

                if (elem.getAttribute('data-echo-background') !== null) {
                    elem.style.backgroundImage =
                        'url(' + elem.getAttribute('data-echo-background') + ')';
                } else if (elem.src !== (src = elem.getAttribute('data-echo'))) {
                    elem.src = src;
                }

                if (!unload) {
                    elem.removeAttribute('data-echo');
                    elem.removeAttribute('data-echo-background');
                }

                callback(elem, 'load');
            } else if (unload && (src = elem.getAttribute('data-echo-placeholder'))) {
                if (elem.getAttribute('data-echo-background') !== null) {
                    elem.style.backgroundImage = 'url(' + src + ')';
                } else {
                    elem.src = src;
                }
                elem.removeAttribute('data-echo-placeholder');
                callback(elem, 'unload');
            }
        }
        if (!length) {
            echo.detach();
        }
    };

    echo.detach = function () {
        if (document.removeEventListener) {
            window.removeEventListener('scroll', debounceOrThrottle);
        } else {
            window.detachEvent('onscroll', debounceOrThrottle);
        }
        clearTimeout(poll);
    };

    return echo;
})();

export default echo;
