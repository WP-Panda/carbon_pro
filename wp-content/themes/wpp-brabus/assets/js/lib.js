!function (global, factory) {
    "object" == typeof exports && "undefined" != typeof module ? factory(exports, require("jquery")) : "function" == typeof define && define.amd ? define(["exports", "jquery"], factory) : factory((global = global || self).bootstrap = {}, global.jQuery)
}(this, function (exports, $) {
    "use strict";

    function _defineProperties(target, props) {
        for (var i = 0; i < props.length; i++) {
            var descriptor = props[i];
            descriptor.enumerable = descriptor.enumerable || !1,
                descriptor.configurable = !0,
            "value" in descriptor && (descriptor.writable = !0),
                Object.defineProperty(target, descriptor.key, descriptor)
        }
    }

    function _createClass(Constructor, protoProps, staticProps) {
        return protoProps && _defineProperties(Constructor.prototype, protoProps),
        staticProps && _defineProperties(Constructor, staticProps),
            Constructor
    }

    function _defineProperty(obj, key, value) {
        return key in obj ? Object.defineProperty(obj, key, {
            value: value,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : obj[key] = value,
            obj
    }

    function _objectSpread(target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = null != arguments[i] ? arguments[i] : {}
                , ownKeys = Object.keys(source);
            "function" == typeof Object.getOwnPropertySymbols && (ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {
                return Object.getOwnPropertyDescriptor(source, sym).enumerable
            }))),
                ownKeys.forEach(function (key) {
                    _defineProperty(target, key, source[key])
                })
        }
        return target
    }

    $ = $ && $.hasOwnProperty("default") ? $.default : $;
    var TRANSITION_END = "transitionend";

    function transitionEndEmulator(duration) {
        var _this = this
            , called = !1;
        return $(this).one(Util.TRANSITION_END, function () {
            called = !0
        }),
            setTimeout(function () {
                called || Util.triggerTransitionEnd(_this)
            }, duration),
            this
    }

    var Util = {
        TRANSITION_END: "bsTransitionEnd",
        getUID: function (prefix) {
            for (; prefix += ~~(1e6 * Math.random()),
                       document.getElementById(prefix);)
                ;
            return prefix
        },
        getSelectorFromElement: function (element) {
            var selector = element.getAttribute("data-target");
            if (!selector || "#" === selector) {
                var hrefAttr = element.getAttribute("href");
                selector = hrefAttr && "#" !== hrefAttr ? hrefAttr.trim() : ""
            }
            try {
                return document.querySelector(selector) ? selector : null
            } catch (err) {
                return null
            }
        },
        getTransitionDurationFromElement: function (element) {
            if (!element)
                return 0;
            var transitionDuration = $(element).css("transition-duration")
                , transitionDelay = $(element).css("transition-delay")
                , floatTransitionDuration = parseFloat(transitionDuration)
                , floatTransitionDelay = parseFloat(transitionDelay);
            return floatTransitionDuration || floatTransitionDelay ? (transitionDuration = transitionDuration.split(",")[0],
                transitionDelay = transitionDelay.split(",")[0],
            1e3 * (parseFloat(transitionDuration) + parseFloat(transitionDelay))) : 0
        },
        reflow: function (element) {
            return element.offsetHeight
        },
        triggerTransitionEnd: function (element) {
            $(element).trigger(TRANSITION_END)
        },
        supportsTransitionEnd: function () {
            return Boolean(TRANSITION_END)
        },
        isElement: function (obj) {
            return (obj[0] || obj).nodeType
        },
        typeCheckConfig: function (componentName, config, configTypes) {
            for (var property in configTypes)
                if (Object.prototype.hasOwnProperty.call(configTypes, property)) {
                    var expectedTypes = configTypes[property]
                        , value = config[property]
                        , valueType = value && Util.isElement(value) ? "element" : (obj = value,
                        {}.toString.call(obj).match(/\s([a-z]+)/i)[1].toLowerCase());
                    if (!new RegExp(expectedTypes).test(valueType))
                        throw new Error(componentName.toUpperCase() + ': Option "' + property + '" provided type "' + valueType + '" but expected type "' + expectedTypes + '".')
                }
            var obj
        },
        findShadowRoot: function (element) {
            if (!document.documentElement.attachShadow)
                return null;
            if ("function" != typeof element.getRootNode)
                return element instanceof ShadowRoot ? element : element.parentNode ? Util.findShadowRoot(element.parentNode) : null;
            var root = element.getRootNode();
            return root instanceof ShadowRoot ? root : null
        }
    };
    $.fn.emulateTransitionEnd = transitionEndEmulator,
        $.event.special[Util.TRANSITION_END] = {
            bindType: TRANSITION_END,
            delegateType: TRANSITION_END,
            handle: function (event) {
                if ($(event.target).is(this))
                    return event.handleObj.handler.apply(this, arguments)
            }
        };
    var JQUERY_NO_CONFLICT = $.fn.alert
        , Event = {
        CLOSE: "close.bs.alert",
        CLOSED: "closed.bs.alert",
        CLICK_DATA_API: "click.bs.alert.data-api"
    }
        , ClassName_ALERT = "alert"
        , ClassName_FADE = "fade"
        , ClassName_SHOW = "show"
        , Alert = function () {
        function Alert(element) {
            this._element = element
        }

        var _proto = Alert.prototype;
        return _proto.close = function (element) {
            var rootElement = this._element;
            element && (rootElement = this._getRootElement(element)),
            this._triggerCloseEvent(rootElement).isDefaultPrevented() || this._removeElement(rootElement)
        }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.alert"),
                    this._element = null
            }
            ,
            _proto._getRootElement = function (element) {
                var selector = Util.getSelectorFromElement(element)
                    , parent = !1;
                return selector && (parent = document.querySelector(selector)),
                    parent = parent || $(element).closest("." + ClassName_ALERT)[0]
            }
            ,
            _proto._triggerCloseEvent = function (element) {
                var closeEvent = $.Event(Event.CLOSE);
                return $(element).trigger(closeEvent),
                    closeEvent
            }
            ,
            _proto._removeElement = function (element) {
                var _this = this;
                if ($(element).removeClass(ClassName_SHOW),
                    $(element).hasClass(ClassName_FADE)) {
                    var transitionDuration = Util.getTransitionDurationFromElement(element);
                    $(element).one(Util.TRANSITION_END, function (event) {
                        return _this._destroyElement(element, event)
                    }).emulateTransitionEnd(transitionDuration)
                } else
                    this._destroyElement(element)
            }
            ,
            _proto._destroyElement = function (element) {
                $(element).detach().trigger(Event.CLOSED).remove()
            }
            ,
            Alert._jQueryInterface = function (config) {
                return this.each(function () {
                    var $element = $(this)
                        , data = $element.data("bs.alert");
                    data || (data = new Alert(this),
                        $element.data("bs.alert", data)),
                    "close" === config && data[config](this)
                })
            }
            ,
            Alert._handleDismiss = function (alertInstance) {
                return function (event) {
                    event && event.preventDefault(),
                        alertInstance.close(this)
                }
            }
            ,
            _createClass(Alert, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }]),
            Alert
    }();
    $(document).on(Event.CLICK_DATA_API, '[data-dismiss="alert"]', Alert._handleDismiss(new Alert)),
        $.fn.alert = Alert._jQueryInterface,
        $.fn.alert.Constructor = Alert,
        $.fn.alert.noConflict = function () {
            return $.fn.alert = JQUERY_NO_CONFLICT,
                Alert._jQueryInterface
        }
    ;
    var JQUERY_NO_CONFLICT$1 = $.fn.button
        , ClassName$1_ACTIVE = "active"
        , ClassName$1_BUTTON = "btn"
        , ClassName$1_FOCUS = "focus"
        , Selector$1_DATA_TOGGLE_CARROT = '[data-toggle^="button"]'
        , Selector$1_DATA_TOGGLE = '[data-toggle="buttons"]'
        , Selector$1_INPUT = 'input:not([type="hidden"])'
        , Selector$1_ACTIVE = ".active"
        , Selector$1_BUTTON = ".btn"
        , Event$1 = {
        CLICK_DATA_API: "click.bs.button.data-api",
        FOCUS_BLUR_DATA_API: "focus.bs.button.data-api blur.bs.button.data-api"
    }
        , Button = function () {
        function Button(element) {
            this._element = element
        }

        var _proto = Button.prototype;
        return _proto.toggle = function () {
            var triggerChangeEvent = !0
                , addAriaPressed = !0
                , rootElement = $(this._element).closest(Selector$1_DATA_TOGGLE)[0];
            if (rootElement) {
                var input = this._element.querySelector(Selector$1_INPUT);
                if (input) {
                    if ("radio" === input.type)
                        if (input.checked && this._element.classList.contains(ClassName$1_ACTIVE))
                            triggerChangeEvent = !1;
                        else {
                            var activeElement = rootElement.querySelector(Selector$1_ACTIVE);
                            activeElement && $(activeElement).removeClass(ClassName$1_ACTIVE)
                        }
                    if (triggerChangeEvent) {
                        if (input.hasAttribute("disabled") || rootElement.hasAttribute("disabled") || input.classList.contains("disabled") || rootElement.classList.contains("disabled"))
                            return;
                        input.checked = !this._element.classList.contains(ClassName$1_ACTIVE),
                            $(input).trigger("change")
                    }
                    input.focus(),
                        addAriaPressed = !1
                }
            }
            addAriaPressed && this._element.setAttribute("aria-pressed", !this._element.classList.contains(ClassName$1_ACTIVE)),
            triggerChangeEvent && $(this._element).toggleClass(ClassName$1_ACTIVE)
        }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.button"),
                    this._element = null
            }
            ,
            Button._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.button");
                    data || (data = new Button(this),
                        $(this).data("bs.button", data)),
                    "toggle" === config && data[config]()
                })
            }
            ,
            _createClass(Button, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }]),
            Button
    }();
    $(document).on(Event$1.CLICK_DATA_API, Selector$1_DATA_TOGGLE_CARROT, function (event) {
        event.preventDefault();
        var button = event.target;
        $(button).hasClass(ClassName$1_BUTTON) || (button = $(button).closest(Selector$1_BUTTON)),
            Button._jQueryInterface.call($(button), "toggle")
    }).on(Event$1.FOCUS_BLUR_DATA_API, Selector$1_DATA_TOGGLE_CARROT, function (event) {
        var button = $(event.target).closest(Selector$1_BUTTON)[0];
        $(button).toggleClass(ClassName$1_FOCUS, /^focus(in)?$/.test(event.type))
    }),
        $.fn.button = Button._jQueryInterface,
        $.fn.button.Constructor = Button,
        $.fn.button.noConflict = function () {
            return $.fn.button = JQUERY_NO_CONFLICT$1,
                Button._jQueryInterface
        }
    ;
    var NAME$2 = "carousel"
        , EVENT_KEY$2 = ".bs.carousel"
        , JQUERY_NO_CONFLICT$2 = $.fn[NAME$2]
        , Default = {
        interval: 5e3,
        keyboard: !0,
        slide: !1,
        pause: "hover",
        wrap: !0,
        touch: !0
    }
        , DefaultType = {
        interval: "(number|boolean)",
        keyboard: "boolean",
        slide: "(boolean|string)",
        pause: "(string|boolean)",
        wrap: "boolean",
        touch: "boolean"
    }
        , Direction_NEXT = "next"
        , Direction_PREV = "prev"
        , Direction_LEFT = "left"
        , Direction_RIGHT = "right"
        , Event$2 = {
        SLIDE: "slide.bs.carousel",
        SLID: "slid.bs.carousel",
        KEYDOWN: "keydown.bs.carousel",
        MOUSEENTER: "mouseenter.bs.carousel",
        MOUSELEAVE: "mouseleave.bs.carousel",
        TOUCHSTART: "touchstart.bs.carousel",
        TOUCHMOVE: "touchmove.bs.carousel",
        TOUCHEND: "touchend.bs.carousel",
        POINTERDOWN: "pointerdown.bs.carousel",
        POINTERUP: "pointerup.bs.carousel",
        DRAG_START: "dragstart.bs.carousel",
        LOAD_DATA_API: "load.bs.carousel.data-api",
        CLICK_DATA_API: "click.bs.carousel.data-api"
    }
        , ClassName$2_CAROUSEL = "carousel"
        , ClassName$2_ACTIVE = "active"
        , ClassName$2_SLIDE = "slide"
        , ClassName$2_RIGHT = "carousel-item-right"
        , ClassName$2_LEFT = "carousel-item-left"
        , ClassName$2_NEXT = "carousel-item-next"
        , ClassName$2_PREV = "carousel-item-prev"
        , ClassName$2_POINTER_EVENT = "pointer-event"
        , Selector$2_ACTIVE = ".active"
        , Selector$2_ACTIVE_ITEM = ".active.carousel-item"
        , Selector$2_ITEM = ".carousel-item"
        , Selector$2_ITEM_IMG = ".carousel-item img"
        , Selector$2_NEXT_PREV = ".carousel-item-next, .carousel-item-prev"
        , Selector$2_INDICATORS = ".carousel-indicators"
        , Selector$2_DATA_SLIDE = "[data-slide], [data-slide-to]"
        , Selector$2_DATA_RIDE = '[data-ride="carousel"]'
        , PointerType = {
        TOUCH: "touch",
        PEN: "pen"
    }
        , Carousel = function () {
        function Carousel(element, config) {
            this._items = null,
                this._interval = null,
                this._activeElement = null,
                this._isPaused = !1,
                this._isSliding = !1,
                this.touchTimeout = null,
                this.touchStartX = 0,
                this.touchDeltaX = 0,
                this._config = this._getConfig(config),
                this._element = element,
                this._indicatorsElement = this._element.querySelector(Selector$2_INDICATORS),
                this._touchSupported = "ontouchstart" in document.documentElement || 0 < navigator.maxTouchPoints,
                this._pointerEvent = Boolean(window.PointerEvent || window.MSPointerEvent),
                this._addEventListeners()
        }

        var _proto = Carousel.prototype;
        return _proto.next = function () {
            this._isSliding || this._slide(Direction_NEXT)
        }
            ,
            _proto.nextWhenVisible = function () {
                !document.hidden && $(this._element).is(":visible") && "hidden" !== $(this._element).css("visibility") && this.next()
            }
            ,
            _proto.prev = function () {
                this._isSliding || this._slide(Direction_PREV)
            }
            ,
            _proto.pause = function (event) {
                event || (this._isPaused = !0),
                this._element.querySelector(Selector$2_NEXT_PREV) && (Util.triggerTransitionEnd(this._element),
                    this.cycle(!0)),
                    clearInterval(this._interval),
                    this._interval = null
            }
            ,
            _proto.cycle = function (event) {
                event || (this._isPaused = !1),
                this._interval && (clearInterval(this._interval),
                    this._interval = null),
                this._config.interval && !this._isPaused && (this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval))
            }
            ,
            _proto.to = function (index) {
                var _this = this;
                this._activeElement = this._element.querySelector(Selector$2_ACTIVE_ITEM);
                var activeIndex = this._getItemIndex(this._activeElement);
                if (!(index > this._items.length - 1 || index < 0))
                    if (this._isSliding)
                        $(this._element).one(Event$2.SLID, function () {
                            return _this.to(index)
                        });
                    else {
                        if (activeIndex === index)
                            return this.pause(),
                                void this.cycle();
                        var direction = activeIndex < index ? Direction_NEXT : Direction_PREV;
                        this._slide(direction, this._items[index])
                    }
            }
            ,
            _proto.dispose = function () {
                $(this._element).off(EVENT_KEY$2),
                    $.removeData(this._element, "bs.carousel"),
                    this._items = null,
                    this._config = null,
                    this._element = null,
                    this._interval = null,
                    this._isPaused = null,
                    this._isSliding = null,
                    this._activeElement = null,
                    this._indicatorsElement = null
            }
            ,
            _proto._getConfig = function (config) {
                return config = _objectSpread({}, Default, config),
                    Util.typeCheckConfig(NAME$2, config, DefaultType),
                    config
            }
            ,
            _proto._handleSwipe = function () {
                var absDeltax = Math.abs(this.touchDeltaX);
                if (!(absDeltax <= 40)) {
                    var direction = absDeltax / this.touchDeltaX;
                    0 < direction && this.prev(),
                    direction < 0 && this.next()
                }
            }
            ,
            _proto._addEventListeners = function () {
                var _this2 = this;
                this._config.keyboard && $(this._element).on(Event$2.KEYDOWN, function (event) {
                    return _this2._keydown(event)
                }),
                "hover" === this._config.pause && $(this._element).on(Event$2.MOUSEENTER, function (event) {
                    return _this2.pause(event)
                }).on(Event$2.MOUSELEAVE, function (event) {
                    return _this2.cycle(event)
                }),
                this._config.touch && this._addTouchEventListeners()
            }
            ,
            _proto._addTouchEventListeners = function () {
                var _this3 = this;
                if (this._touchSupported) {
                    var start = function (event) {
                        _this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()] ? _this3.touchStartX = event.originalEvent.clientX : _this3._pointerEvent || (_this3.touchStartX = event.originalEvent.touches[0].clientX)
                    }
                        , end = function (event) {
                        _this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()] && (_this3.touchDeltaX = event.originalEvent.clientX - _this3.touchStartX),
                            _this3._handleSwipe(),
                        "hover" === _this3._config.pause && (_this3.pause(),
                        _this3.touchTimeout && clearTimeout(_this3.touchTimeout),
                            _this3.touchTimeout = setTimeout(function (event) {
                                return _this3.cycle(event)
                            }, 500 + _this3._config.interval))
                    };
                    $(this._element.querySelectorAll(Selector$2_ITEM_IMG)).on(Event$2.DRAG_START, function (e) {
                        return e.preventDefault()
                    }),
                        this._pointerEvent ? ($(this._element).on(Event$2.POINTERDOWN, function (event) {
                            return start(event)
                        }),
                            $(this._element).on(Event$2.POINTERUP, function (event) {
                                return end(event)
                            }),
                            this._element.classList.add(ClassName$2_POINTER_EVENT)) : ($(this._element).on(Event$2.TOUCHSTART, function (event) {
                            return start(event)
                        }),
                            $(this._element).on(Event$2.TOUCHMOVE, function (event) {
                                return function (event) {
                                    event.originalEvent.touches && 1 < event.originalEvent.touches.length ? _this3.touchDeltaX = 0 : _this3.touchDeltaX = event.originalEvent.touches[0].clientX - _this3.touchStartX
                                }(event)
                            }),
                            $(this._element).on(Event$2.TOUCHEND, function (event) {
                                return end(event)
                            }))
                }
            }
            ,
            _proto._keydown = function (event) {
                if (!/input|textarea/i.test(event.target.tagName))
                    switch (event.which) {
                        case 37:
                            event.preventDefault(),
                                this.prev();
                            break;
                        case 39:
                            event.preventDefault(),
                                this.next()
                    }
            }
            ,
            _proto._getItemIndex = function (element) {
                return this._items = element && element.parentNode ? [].slice.call(element.parentNode.querySelectorAll(Selector$2_ITEM)) : [],
                    this._items.indexOf(element)
            }
            ,
            _proto._getItemByDirection = function (direction, activeElement) {
                var isNextDirection = direction === Direction_NEXT
                    , isPrevDirection = direction === Direction_PREV
                    , activeIndex = this._getItemIndex(activeElement)
                    , lastItemIndex = this._items.length - 1;
                if ((isPrevDirection && 0 === activeIndex || isNextDirection && activeIndex === lastItemIndex) && !this._config.wrap)
                    return activeElement;
                var itemIndex = (activeIndex + (direction === Direction_PREV ? -1 : 1)) % this._items.length;
                return -1 == itemIndex ? this._items[this._items.length - 1] : this._items[itemIndex]
            }
            ,
            _proto._triggerSlideEvent = function (relatedTarget, eventDirectionName) {
                var targetIndex = this._getItemIndex(relatedTarget)
                    , fromIndex = this._getItemIndex(this._element.querySelector(Selector$2_ACTIVE_ITEM))
                    , slideEvent = $.Event(Event$2.SLIDE, {
                    relatedTarget: relatedTarget,
                    direction: eventDirectionName,
                    from: fromIndex,
                    to: targetIndex
                });
                return $(this._element).trigger(slideEvent),
                    slideEvent
            }
            ,
            _proto._setActiveIndicatorElement = function (element) {
                if (this._indicatorsElement) {
                    var indicators = [].slice.call(this._indicatorsElement.querySelectorAll(Selector$2_ACTIVE));
                    $(indicators).removeClass(ClassName$2_ACTIVE);
                    var nextIndicator = this._indicatorsElement.children[this._getItemIndex(element)];
                    nextIndicator && $(nextIndicator).addClass(ClassName$2_ACTIVE)
                }
            }
            ,
            _proto._slide = function (direction, element) {
                var directionalClassName, orderClassName, eventDirectionName, _this4 = this,
                    activeElement = this._element.querySelector(Selector$2_ACTIVE_ITEM),
                    activeElementIndex = this._getItemIndex(activeElement),
                    nextElement = element || activeElement && this._getItemByDirection(direction, activeElement),
                    nextElementIndex = this._getItemIndex(nextElement), isCycling = Boolean(this._interval);
                if (eventDirectionName = direction === Direction_NEXT ? (directionalClassName = ClassName$2_LEFT,
                    orderClassName = ClassName$2_NEXT,
                    Direction_LEFT) : (directionalClassName = ClassName$2_RIGHT,
                    orderClassName = ClassName$2_PREV,
                    Direction_RIGHT),
                nextElement && $(nextElement).hasClass(ClassName$2_ACTIVE))
                    this._isSliding = !1;
                else if (!this._triggerSlideEvent(nextElement, eventDirectionName).isDefaultPrevented() && activeElement && nextElement) {
                    this._isSliding = !0,
                    isCycling && this.pause(),
                        this._setActiveIndicatorElement(nextElement);
                    var slidEvent = $.Event(Event$2.SLID, {
                        relatedTarget: nextElement,
                        direction: eventDirectionName,
                        from: activeElementIndex,
                        to: nextElementIndex
                    });
                    if ($(this._element).hasClass(ClassName$2_SLIDE)) {
                        $(nextElement).addClass(orderClassName),
                            Util.reflow(nextElement),
                            $(activeElement).addClass(directionalClassName),
                            $(nextElement).addClass(directionalClassName);
                        var nextElementInterval = parseInt(nextElement.getAttribute("data-interval"), 10);
                        nextElementInterval ? (this._config.defaultInterval = this._config.defaultInterval || this._config.interval,
                            this._config.interval = nextElementInterval) : this._config.interval = this._config.defaultInterval || this._config.interval;
                        var transitionDuration = Util.getTransitionDurationFromElement(activeElement);
                        $(activeElement).one(Util.TRANSITION_END, function () {
                            $(nextElement).removeClass(directionalClassName + " " + orderClassName).addClass(ClassName$2_ACTIVE),
                                $(activeElement).removeClass(ClassName$2_ACTIVE + " " + orderClassName + " " + directionalClassName),
                                _this4._isSliding = !1,
                                setTimeout(function () {
                                    return $(_this4._element).trigger(slidEvent)
                                }, 0)
                        }).emulateTransitionEnd(transitionDuration)
                    } else
                        $(activeElement).removeClass(ClassName$2_ACTIVE),
                            $(nextElement).addClass(ClassName$2_ACTIVE),
                            this._isSliding = !1,
                            $(this._element).trigger(slidEvent);
                    isCycling && this.cycle()
                }
            }
            ,
            Carousel._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.carousel")
                        , _config = _objectSpread({}, Default, $(this).data());
                    "object" == typeof config && (_config = _objectSpread({}, _config, config));
                    var action = "string" == typeof config ? config : _config.slide;
                    if (data || (data = new Carousel(this, _config),
                        $(this).data("bs.carousel", data)),
                    "number" == typeof config)
                        data.to(config);
                    else if ("string" == typeof action) {
                        if (void 0 === data[action])
                            throw new TypeError('No method named "' + action + '"');
                        data[action]()
                    } else
                        _config.interval && _config.ride && (data.pause(),
                            data.cycle())
                })
            }
            ,
            Carousel._dataApiClickHandler = function (event) {
                var selector = Util.getSelectorFromElement(this);
                if (selector) {
                    var target = $(selector)[0];
                    if (target && $(target).hasClass(ClassName$2_CAROUSEL)) {
                        var config = _objectSpread({}, $(target).data(), $(this).data())
                            , slideIndex = this.getAttribute("data-slide-to");
                        slideIndex && (config.interval = !1),
                            Carousel._jQueryInterface.call($(target), config),
                        slideIndex && $(target).data("bs.carousel").to(slideIndex),
                            event.preventDefault()
                    }
                }
            }
            ,
            _createClass(Carousel, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default
                }
            }]),
            Carousel
    }();
    $(document).on(Event$2.CLICK_DATA_API, Selector$2_DATA_SLIDE, Carousel._dataApiClickHandler),
        $(window).on(Event$2.LOAD_DATA_API, function () {
            for (var carousels = [].slice.call(document.querySelectorAll(Selector$2_DATA_RIDE)), i = 0, len = carousels.length; i < len; i++) {
                var $carousel = $(carousels[i]);
                Carousel._jQueryInterface.call($carousel, $carousel.data())
            }
        }),
        $.fn[NAME$2] = Carousel._jQueryInterface,
        $.fn[NAME$2].Constructor = Carousel,
        $.fn[NAME$2].noConflict = function () {
            return $.fn[NAME$2] = JQUERY_NO_CONFLICT$2,
                Carousel._jQueryInterface
        }
    ;
    var NAME$3 = "collapse"
        , JQUERY_NO_CONFLICT$3 = $.fn[NAME$3]
        , Default$1 = {
        toggle: !0,
        parent: ""
    }
        , DefaultType$1 = {
        toggle: "boolean",
        parent: "(string|element)"
    }
        , Event$3 = {
        SHOW: "show.bs.collapse",
        SHOWN: "shown.bs.collapse",
        HIDE: "hide.bs.collapse",
        HIDDEN: "hidden.bs.collapse",
        CLICK_DATA_API: "click.bs.collapse.data-api"
    }
        , ClassName$3_SHOW = "show"
        , ClassName$3_COLLAPSE = "collapse"
        , ClassName$3_COLLAPSING = "collapsing"
        , ClassName$3_COLLAPSED = "collapsed"
        , Dimension_WIDTH = "width"
        , Dimension_HEIGHT = "height"
        , Selector$3_ACTIVES = ".show, .collapsing"
        , Selector$3_DATA_TOGGLE = '[data-toggle="collapse"]'
        , Collapse = function () {
        function Collapse(element, config) {
            this._isTransitioning = !1,
                this._element = element,
                this._config = this._getConfig(config),
                this._triggerArray = [].slice.call(document.querySelectorAll('[data-toggle="collapse"][href="#' + element.id + '"],[data-toggle="collapse"][data-target="#' + element.id + '"]'));
            for (var toggleList = [].slice.call(document.querySelectorAll(Selector$3_DATA_TOGGLE)), i = 0, len = toggleList.length; i < len; i++) {
                var elem = toggleList[i]
                    , selector = Util.getSelectorFromElement(elem)
                    , filterElement = [].slice.call(document.querySelectorAll(selector)).filter(function (foundElem) {
                    return foundElem === element
                });
                null !== selector && 0 < filterElement.length && (this._selector = selector,
                    this._triggerArray.push(elem))
            }
            this._parent = this._config.parent ? this._getParent() : null,
            this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray),
            this._config.toggle && this.toggle()
        }

        var _proto = Collapse.prototype;
        return _proto.toggle = function () {
            $(this._element).hasClass(ClassName$3_SHOW) ? this.hide() : this.show()
        }
            ,
            _proto.show = function () {
                var actives, activesData, _this = this;
                if (!this._isTransitioning && !$(this._element).hasClass(ClassName$3_SHOW) && (this._parent && 0 === (actives = [].slice.call(this._parent.querySelectorAll(Selector$3_ACTIVES)).filter(function (elem) {
                    return "string" == typeof _this._config.parent ? elem.getAttribute("data-parent") === _this._config.parent : elem.classList.contains(ClassName$3_COLLAPSE)
                })).length && (actives = null),
                    !(actives && (activesData = $(actives).not(this._selector).data("bs.collapse")) && activesData._isTransitioning))) {
                    var startEvent = $.Event(Event$3.SHOW);
                    if ($(this._element).trigger(startEvent),
                        !startEvent.isDefaultPrevented()) {
                        actives && (Collapse._jQueryInterface.call($(actives).not(this._selector), "hide"),
                        activesData || $(actives).data("bs.collapse", null));
                        var dimension = this._getDimension();
                        $(this._element).removeClass(ClassName$3_COLLAPSE).addClass(ClassName$3_COLLAPSING),
                            this._element.style[dimension] = 0,
                        this._triggerArray.length && $(this._triggerArray).removeClass(ClassName$3_COLLAPSED).attr("aria-expanded", !0),
                            this.setTransitioning(!0);
                        var scrollSize = "scroll" + (dimension[0].toUpperCase() + dimension.slice(1))
                            , transitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, function () {
                            $(_this._element).removeClass(ClassName$3_COLLAPSING).addClass(ClassName$3_COLLAPSE).addClass(ClassName$3_SHOW),
                                _this._element.style[dimension] = "",
                                _this.setTransitioning(!1),
                                $(_this._element).trigger(Event$3.SHOWN)
                        }).emulateTransitionEnd(transitionDuration),
                            this._element.style[dimension] = this._element[scrollSize] + "px"
                    }
                }
            }
            ,
            _proto.hide = function () {
                var _this2 = this;
                if (!this._isTransitioning && $(this._element).hasClass(ClassName$3_SHOW)) {
                    var startEvent = $.Event(Event$3.HIDE);
                    if ($(this._element).trigger(startEvent),
                        !startEvent.isDefaultPrevented()) {
                        var dimension = this._getDimension();
                        this._element.style[dimension] = this._element.getBoundingClientRect()[dimension] + "px",
                            Util.reflow(this._element),
                            $(this._element).addClass(ClassName$3_COLLAPSING).removeClass(ClassName$3_COLLAPSE).removeClass(ClassName$3_SHOW);
                        var triggerArrayLength = this._triggerArray.length;
                        if (0 < triggerArrayLength)
                            for (var i = 0; i < triggerArrayLength; i++) {
                                var trigger = this._triggerArray[i]
                                    , selector = Util.getSelectorFromElement(trigger);
                                if (null !== selector)
                                    $([].slice.call(document.querySelectorAll(selector))).hasClass(ClassName$3_SHOW) || $(trigger).addClass(ClassName$3_COLLAPSED).attr("aria-expanded", !1)
                            }
                        this.setTransitioning(!0);
                        this._element.style[dimension] = "";
                        var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                        $(this._element).one(Util.TRANSITION_END, function () {
                            _this2.setTransitioning(!1),
                                $(_this2._element).removeClass(ClassName$3_COLLAPSING).addClass(ClassName$3_COLLAPSE).trigger(Event$3.HIDDEN)
                        }).emulateTransitionEnd(transitionDuration)
                    }
                }
            }
            ,
            _proto.setTransitioning = function (isTransitioning) {
                this._isTransitioning = isTransitioning
            }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.collapse"),
                    this._config = null,
                    this._parent = null,
                    this._element = null,
                    this._triggerArray = null,
                    this._isTransitioning = null
            }
            ,
            _proto._getConfig = function (config) {
                return (config = _objectSpread({}, Default$1, config)).toggle = Boolean(config.toggle),
                    Util.typeCheckConfig(NAME$3, config, DefaultType$1),
                    config
            }
            ,
            _proto._getDimension = function () {
                return $(this._element).hasClass(Dimension_WIDTH) ? Dimension_WIDTH : Dimension_HEIGHT
            }
            ,
            _proto._getParent = function () {
                var parent, _this3 = this;
                Util.isElement(this._config.parent) ? (parent = this._config.parent,
                void 0 !== this._config.parent.jquery && (parent = this._config.parent[0])) : parent = document.querySelector(this._config.parent);
                var selector = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]'
                    , children = [].slice.call(parent.querySelectorAll(selector));
                return $(children).each(function (i, element) {
                    _this3._addAriaAndCollapsedClass(Collapse._getTargetFromElement(element), [element])
                }),
                    parent
            }
            ,
            _proto._addAriaAndCollapsedClass = function (element, triggerArray) {
                var isOpen = $(element).hasClass(ClassName$3_SHOW);
                triggerArray.length && $(triggerArray).toggleClass(ClassName$3_COLLAPSED, !isOpen).attr("aria-expanded", isOpen)
            }
            ,
            Collapse._getTargetFromElement = function (element) {
                var selector = Util.getSelectorFromElement(element);
                return selector ? document.querySelector(selector) : null
            }
            ,
            Collapse._jQueryInterface = function (config) {
                return this.each(function () {
                    var $this = $(this)
                        , data = $this.data("bs.collapse")
                        ,
                        _config = _objectSpread({}, Default$1, $this.data(), "object" == typeof config && config ? config : {});
                    if (!data && _config.toggle && /show|hide/.test(config) && (_config.toggle = !1),
                    data || (data = new Collapse(this, _config),
                        $this.data("bs.collapse", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            _createClass(Collapse, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$1
                }
            }]),
            Collapse
    }();
    $(document).on(Event$3.CLICK_DATA_API, Selector$3_DATA_TOGGLE, function (event) {
        "A" === event.currentTarget.tagName && event.preventDefault();
        var $trigger = $(this)
            , selector = Util.getSelectorFromElement(this)
            , selectors = [].slice.call(document.querySelectorAll(selector));
        $(selectors).each(function () {
            var $target = $(this)
                , config = $target.data("bs.collapse") ? "toggle" : $trigger.data();
            Collapse._jQueryInterface.call($target, config)
        })
    }),
        $.fn[NAME$3] = Collapse._jQueryInterface,
        $.fn[NAME$3].Constructor = Collapse,
        $.fn[NAME$3].noConflict = function () {
            return $.fn[NAME$3] = JQUERY_NO_CONFLICT$3,
                Collapse._jQueryInterface
        }
    ;
    for (var isBrowser = "undefined" != typeof window && "undefined" != typeof document, longerTimeoutBrowsers = ["Edge", "Trident", "Firefox"], timeoutDuration = 0, i = 0; i < longerTimeoutBrowsers.length; i += 1)
        if (isBrowser && 0 <= navigator.userAgent.indexOf(longerTimeoutBrowsers[i])) {
            timeoutDuration = 1;
            break
        }
    var debounce = isBrowser && window.Promise ? function (fn) {
            var called = !1;
            return function () {
                called || (called = !0,
                    window.Promise.resolve().then(function () {
                        called = !1,
                            fn()
                    }))
            }
        }
        : function (fn) {
            var scheduled = !1;
            return function () {
                scheduled || (scheduled = !0,
                    setTimeout(function () {
                        scheduled = !1,
                            fn()
                    }, timeoutDuration))
            }
        }
    ;

    function isFunction(functionToCheck) {
        return functionToCheck && "[object Function]" === {}.toString.call(functionToCheck)
    }

    function getStyleComputedProperty(element, property) {
        if (1 !== element.nodeType)
            return [];
        var css = element.ownerDocument.defaultView.getComputedStyle(element, null);
        return property ? css[property] : css
    }

    function getParentNode(element) {
        return "HTML" === element.nodeName ? element : element.parentNode || element.host
    }

    function getScrollParent(element) {
        if (!element)
            return document.body;
        switch (element.nodeName) {
            case "HTML":
            case "BODY":
                return element.ownerDocument.body;
            case "#document":
                return element.body
        }
        var _getStyleComputedProp = getStyleComputedProperty(element)
            , overflow = _getStyleComputedProp.overflow
            , overflowX = _getStyleComputedProp.overflowX
            , overflowY = _getStyleComputedProp.overflowY;
        return /(auto|scroll|overlay)/.test(overflow + overflowY + overflowX) ? element : getScrollParent(getParentNode(element))
    }

    var isIE11 = isBrowser && !(!window.MSInputMethodContext || !document.documentMode)
        , isIE10 = isBrowser && /MSIE 10/.test(navigator.userAgent);

    function isIE(version) {
        return 11 === version ? isIE11 : 10 !== version && isIE11 || isIE10
    }

    function getOffsetParent(element) {
        if (!element)
            return document.documentElement;
        for (var noOffsetParent = isIE(10) ? document.body : null, offsetParent = element.offsetParent || null; offsetParent === noOffsetParent && element.nextElementSibling;)
            offsetParent = (element = element.nextElementSibling).offsetParent;
        var nodeName = offsetParent && offsetParent.nodeName;
        return nodeName && "BODY" !== nodeName && "HTML" !== nodeName ? -1 !== ["TH", "TD", "TABLE"].indexOf(offsetParent.nodeName) && "static" === getStyleComputedProperty(offsetParent, "position") ? getOffsetParent(offsetParent) : offsetParent : element ? element.ownerDocument.documentElement : document.documentElement
    }

    function getRoot(node) {
        return null !== node.parentNode ? getRoot(node.parentNode) : node
    }

    function findCommonOffsetParent(element1, element2) {
        if (!(element1 && element1.nodeType && element2 && element2.nodeType))
            return document.documentElement;
        var order = element1.compareDocumentPosition(element2) & Node.DOCUMENT_POSITION_FOLLOWING
            , start = order ? element1 : element2
            , end = order ? element2 : element1
            , range = document.createRange();
        range.setStart(start, 0),
            range.setEnd(end, 0);
        var element, nodeName, commonAncestorContainer = range.commonAncestorContainer;
        if (element1 !== commonAncestorContainer && element2 !== commonAncestorContainer || start.contains(end))
            return "BODY" === (nodeName = (element = commonAncestorContainer).nodeName) || "HTML" !== nodeName && getOffsetParent(element.firstElementChild) !== element ? getOffsetParent(commonAncestorContainer) : commonAncestorContainer;
        var element1root = getRoot(element1);
        return element1root.host ? findCommonOffsetParent(element1root.host, element2) : findCommonOffsetParent(element1, getRoot(element2).host)
    }

    function getScroll(element, argument_1) {
        var upperSide = "top" === (1 < arguments.length && void 0 !== argument_1 ? argument_1 : "top") ? "scrollTop" : "scrollLeft"
            , nodeName = element.nodeName;
        if ("BODY" !== nodeName && "HTML" !== nodeName)
            return element[upperSide];
        var html = element.ownerDocument.documentElement;
        return (element.ownerDocument.scrollingElement || html)[upperSide]
    }

    function getBordersSize(styles, axis) {
        var sideA = "x" === axis ? "Left" : "Top"
            , sideB = "Left" == sideA ? "Right" : "Bottom";
        return parseFloat(styles["border" + sideA + "Width"], 10) + parseFloat(styles["border" + sideB + "Width"], 10)
    }

    function getSize(axis, body, html, computedStyle) {
        return Math.max(body["offset" + axis], body["scroll" + axis], html["client" + axis], html["offset" + axis], html["scroll" + axis], isIE(10) ? parseInt(html["offset" + axis]) + parseInt(computedStyle["margin" + ("Height" === axis ? "Top" : "Left")]) + parseInt(computedStyle["margin" + ("Height" === axis ? "Bottom" : "Right")]) : 0)
    }

    function getWindowSizes(document) {
        var body = document.body
            , html = document.documentElement
            , computedStyle = isIE(10) && getComputedStyle(html);
        return {
            height: getSize("Height", body, html, computedStyle),
            width: getSize("Width", body, html, computedStyle)
        }
    }

    var createClass = function (Constructor, protoProps, staticProps) {
        return protoProps && defineProperties(Constructor.prototype, protoProps),
        staticProps && defineProperties(Constructor, staticProps),
            Constructor
    };

    function defineProperties(target, props) {
        for (var i = 0; i < props.length; i++) {
            var descriptor = props[i];
            descriptor.enumerable = descriptor.enumerable || !1,
                descriptor.configurable = !0,
            "value" in descriptor && (descriptor.writable = !0),
                Object.defineProperty(target, descriptor.key, descriptor)
        }
    }

    function defineProperty(obj, key, value) {
        return key in obj ? Object.defineProperty(obj, key, {
            value: value,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : obj[key] = value,
            obj
    }

    var _extends = Object.assign || function (target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source)
                    Object.prototype.hasOwnProperty.call(source, key) && (target[key] = source[key])
            }
            return target
        }
    ;

    function getClientRect(offsets) {
        return _extends({}, offsets, {
            right: offsets.left + offsets.width,
            bottom: offsets.top + offsets.height
        })
    }

    function getBoundingClientRect(element) {
        var rect = {};
        try {
            if (isIE(10)) {
                rect = element.getBoundingClientRect();
                var scrollTop = getScroll(element, "top")
                    , scrollLeft = getScroll(element, "left");
                rect.top += scrollTop,
                    rect.left += scrollLeft,
                    rect.bottom += scrollTop,
                    rect.right += scrollLeft
            } else
                rect = element.getBoundingClientRect()
        } catch (e) {
        }
        var result = {
            left: rect.left,
            top: rect.top,
            width: rect.right - rect.left,
            height: rect.bottom - rect.top
        }
            , sizes = "HTML" === element.nodeName ? getWindowSizes(element.ownerDocument) : {}
            , width = sizes.width || element.clientWidth || result.right - result.left
            , height = sizes.height || element.clientHeight || result.bottom - result.top
            , horizScrollbar = element.offsetWidth - width
            , vertScrollbar = element.offsetHeight - height;
        if (horizScrollbar || vertScrollbar) {
            var styles = getStyleComputedProperty(element);
            horizScrollbar -= getBordersSize(styles, "x"),
                vertScrollbar -= getBordersSize(styles, "y"),
                result.width -= horizScrollbar,
                result.height -= vertScrollbar
        }
        return getClientRect(result)
    }

    function getOffsetRectRelativeToArbitraryNode(children, parent, argument_2) {
        var fixedPosition = 2 < arguments.length && void 0 !== argument_2 && argument_2
            , isIE10 = isIE(10)
            , isHTML = "HTML" === parent.nodeName
            , childrenRect = getBoundingClientRect(children)
            , parentRect = getBoundingClientRect(parent)
            , scrollParent = getScrollParent(children)
            , styles = getStyleComputedProperty(parent)
            , borderTopWidth = parseFloat(styles.borderTopWidth, 10)
            , borderLeftWidth = parseFloat(styles.borderLeftWidth, 10);
        fixedPosition && isHTML && (parentRect.top = Math.max(parentRect.top, 0),
            parentRect.left = Math.max(parentRect.left, 0));
        var offsets = getClientRect({
            top: childrenRect.top - parentRect.top - borderTopWidth,
            left: childrenRect.left - parentRect.left - borderLeftWidth,
            width: childrenRect.width,
            height: childrenRect.height
        });
        if (offsets.marginTop = 0,
            offsets.marginLeft = 0,
        !isIE10 && isHTML) {
            var marginTop = parseFloat(styles.marginTop, 10)
                , marginLeft = parseFloat(styles.marginLeft, 10);
            offsets.top -= borderTopWidth - marginTop,
                offsets.bottom -= borderTopWidth - marginTop,
                offsets.left -= borderLeftWidth - marginLeft,
                offsets.right -= borderLeftWidth - marginLeft,
                offsets.marginTop = marginTop,
                offsets.marginLeft = marginLeft
        }
        return (isIE10 && !fixedPosition ? parent.contains(scrollParent) : parent === scrollParent && "BODY" !== scrollParent.nodeName) && (offsets = function (rect, element, argument_2) {
            var subtract = 2 < arguments.length && void 0 !== argument_2 && argument_2
                , scrollTop = getScroll(element, "top")
                , scrollLeft = getScroll(element, "left")
                , modifier = subtract ? -1 : 1;
            return rect.top += scrollTop * modifier,
                rect.bottom += scrollTop * modifier,
                rect.left += scrollLeft * modifier,
                rect.right += scrollLeft * modifier,
                rect
        }(offsets, parent)),
            offsets
    }

    function getFixedPositionOffsetParent(element) {
        if (!element || !element.parentElement || isIE())
            return document.documentElement;
        for (var el = element.parentElement; el && "none" === getStyleComputedProperty(el, "transform");)
            el = el.parentElement;
        return el || document.documentElement
    }

    function getBoundaries(popper, reference, padding, boundariesElement, argument_4) {
        var fixedPosition = 4 < arguments.length && void 0 !== argument_4 && argument_4
            , boundaries = {
                top: 0,
                left: 0
            }
            ,
            offsetParent = fixedPosition ? getFixedPositionOffsetParent(popper) : findCommonOffsetParent(popper, reference);
        if ("viewport" === boundariesElement)
            boundaries = function (element, argument_1) {
                var excludeScroll = 1 < arguments.length && void 0 !== argument_1 && argument_1
                    , html = element.ownerDocument.documentElement
                    , relativeOffset = getOffsetRectRelativeToArbitraryNode(element, html)
                    , width = Math.max(html.clientWidth, window.innerWidth || 0)
                    , height = Math.max(html.clientHeight, window.innerHeight || 0)
                    , scrollTop = excludeScroll ? 0 : getScroll(html)
                    , scrollLeft = excludeScroll ? 0 : getScroll(html, "left");
                return getClientRect({
                    top: scrollTop - relativeOffset.top + relativeOffset.marginTop,
                    left: scrollLeft - relativeOffset.left + relativeOffset.marginLeft,
                    width: width,
                    height: height
                })
            }(offsetParent, fixedPosition);
        else {
            var boundariesNode = void 0;
            "scrollParent" === boundariesElement ? "BODY" === (boundariesNode = getScrollParent(getParentNode(reference))).nodeName && (boundariesNode = popper.ownerDocument.documentElement) : boundariesNode = "window" === boundariesElement ? popper.ownerDocument.documentElement : boundariesElement;
            var offsets = getOffsetRectRelativeToArbitraryNode(boundariesNode, offsetParent, fixedPosition);
            if ("HTML" !== boundariesNode.nodeName || function isFixed(element) {
                var nodeName = element.nodeName;
                if ("BODY" === nodeName || "HTML" === nodeName)
                    return !1;
                if ("fixed" === getStyleComputedProperty(element, "position"))
                    return !0;
                var parentNode = getParentNode(element);
                return !!parentNode && isFixed(parentNode)
            }(offsetParent))
                boundaries = offsets;
            else {
                var _getWindowSizes = getWindowSizes(popper.ownerDocument)
                    , height = _getWindowSizes.height
                    , width = _getWindowSizes.width;
                boundaries.top += offsets.top - offsets.marginTop,
                    boundaries.bottom = height + offsets.top,
                    boundaries.left += offsets.left - offsets.marginLeft,
                    boundaries.right = width + offsets.left
            }
        }
        var isPaddingNumber = "number" == typeof (padding = padding || 0);
        return boundaries.left += isPaddingNumber ? padding : padding.left || 0,
            boundaries.top += isPaddingNumber ? padding : padding.top || 0,
            boundaries.right -= isPaddingNumber ? padding : padding.right || 0,
            boundaries.bottom -= isPaddingNumber ? padding : padding.bottom || 0,
            boundaries
    }

    function computeAutoPlacement(placement, refRect, popper, reference, boundariesElement, argument_5) {
        var padding = 5 < arguments.length && void 0 !== argument_5 ? argument_5 : 0;
        if (-1 === placement.indexOf("auto"))
            return placement;
        var boundaries = getBoundaries(popper, reference, padding, boundariesElement)
            , rects = {
            top: {
                width: boundaries.width,
                height: refRect.top - boundaries.top
            },
            right: {
                width: boundaries.right - refRect.right,
                height: boundaries.height
            },
            bottom: {
                width: boundaries.width,
                height: boundaries.bottom - refRect.bottom
            },
            left: {
                width: refRect.left - boundaries.left,
                height: boundaries.height
            }
        }
            , sortedAreas = Object.keys(rects).map(function (key) {
            return _extends({
                key: key
            }, rects[key], {
                area: (_ref = rects[key]).width * _ref.height
            });
            var _ref
        }).sort(function (a, b) {
            return b.area - a.area
        })
            , filteredAreas = sortedAreas.filter(function (_ref2) {
            var width = _ref2.width
                , height = _ref2.height;
            return width >= popper.clientWidth && height >= popper.clientHeight
        })
            , computedPlacement = 0 < filteredAreas.length ? filteredAreas[0].key : sortedAreas[0].key
            , variation = placement.split("-")[1];
        return computedPlacement + (variation ? "-" + variation : "")
    }

    function getReferenceOffsets(state, popper, reference, argument_3) {
        var fixedPosition = 3 < arguments.length && void 0 !== argument_3 ? argument_3 : null;
        return getOffsetRectRelativeToArbitraryNode(reference, fixedPosition ? getFixedPositionOffsetParent(popper) : findCommonOffsetParent(popper, reference), fixedPosition)
    }

    function getOuterSizes(element) {
        var styles = element.ownerDocument.defaultView.getComputedStyle(element)
            , x = parseFloat(styles.marginTop || 0) + parseFloat(styles.marginBottom || 0)
            , y = parseFloat(styles.marginLeft || 0) + parseFloat(styles.marginRight || 0);
        return {
            width: element.offsetWidth + y,
            height: element.offsetHeight + x
        }
    }

    function getOppositePlacement(placement) {
        var hash = {
            left: "right",
            right: "left",
            bottom: "top",
            top: "bottom"
        };
        return placement.replace(/left|right|bottom|top/g, function (matched) {
            return hash[matched]
        })
    }

    function getPopperOffsets(popper, referenceOffsets, placement) {
        placement = placement.split("-")[0];
        var popperRect = getOuterSizes(popper)
            , popperOffsets = {
            width: popperRect.width,
            height: popperRect.height
        }
            , isHoriz = -1 !== ["right", "left"].indexOf(placement)
            , mainSide = isHoriz ? "top" : "left"
            , secondarySide = isHoriz ? "left" : "top"
            , measurement = isHoriz ? "height" : "width"
            , secondaryMeasurement = isHoriz ? "width" : "height";
        return popperOffsets[mainSide] = referenceOffsets[mainSide] + referenceOffsets[measurement] / 2 - popperRect[measurement] / 2,
            popperOffsets[secondarySide] = placement === secondarySide ? referenceOffsets[secondarySide] - popperRect[secondaryMeasurement] : referenceOffsets[getOppositePlacement(secondarySide)],
            popperOffsets
    }

    function find(arr, check) {
        return Array.prototype.find ? arr.find(check) : arr.filter(check)[0]
    }

    function runModifiers(modifiers, data, ends) {
        return (void 0 === ends ? modifiers : modifiers.slice(0, function (arr, prop, value) {
            if (Array.prototype.findIndex)
                return arr.findIndex(function (cur) {
                    return cur[prop] === value
                });
            var match = find(arr, function (obj) {
                return obj[prop] === value
            });
            return arr.indexOf(match)
        }(modifiers, "name", ends))).forEach(function (modifier) {
            modifier.function && console.warn("`modifier.function` is deprecated, use `modifier.fn`!");
            var fn = modifier.function || modifier.fn;
            modifier.enabled && isFunction(fn) && (data.offsets.popper = getClientRect(data.offsets.popper),
                data.offsets.reference = getClientRect(data.offsets.reference),
                data = fn(data, modifier))
        }),
            data
    }

    function isModifierEnabled(modifiers, modifierName) {
        return modifiers.some(function (_ref) {
            var name = _ref.name;
            return _ref.enabled && name === modifierName
        })
    }

    function getSupportedPropertyName(property) {
        for (var prefixes = [!1, "ms", "Webkit", "Moz", "O"], upperProp = property.charAt(0).toUpperCase() + property.slice(1), i = 0; i < prefixes.length; i++) {
            var prefix = prefixes[i]
                , toCheck = prefix ? "" + prefix + upperProp : property;
            if (void 0 !== document.body.style[toCheck])
                return toCheck
        }
        return null
    }

    function getWindow(element) {
        var ownerDocument = element.ownerDocument;
        return ownerDocument ? ownerDocument.defaultView : window
    }

    function setupEventListeners(reference, options, state, updateBound) {
        state.updateBound = updateBound,
            getWindow(reference).addEventListener("resize", state.updateBound, {
                passive: !0
            });
        var scrollElement = getScrollParent(reference);
        return function attachToScrollParents(scrollParent, event, callback, scrollParents) {
            var isBody = "BODY" === scrollParent.nodeName
                , target = isBody ? scrollParent.ownerDocument.defaultView : scrollParent;
            target.addEventListener(event, callback, {
                passive: !0
            }),
            isBody || attachToScrollParents(getScrollParent(target.parentNode), event, callback, scrollParents),
                scrollParents.push(target)
        }(scrollElement, "scroll", state.updateBound, state.scrollParents),
            state.scrollElement = scrollElement,
            state.eventsEnabled = !0,
            state
    }

    function disableEventListeners() {
        var reference, state;
        this.state.eventsEnabled && (cancelAnimationFrame(this.scheduleUpdate),
            this.state = (reference = this.reference,
                state = this.state,
                getWindow(reference).removeEventListener("resize", state.updateBound),
                state.scrollParents.forEach(function (target) {
                    target.removeEventListener("scroll", state.updateBound)
                }),
                state.updateBound = null,
                state.scrollParents = [],
                state.scrollElement = null,
                state.eventsEnabled = !1,
                state))
    }

    function isNumeric(n) {
        return "" !== n && !isNaN(parseFloat(n)) && isFinite(n)
    }

    function setStyles(element, styles) {
        Object.keys(styles).forEach(function (prop) {
            var unit = "";
            -1 !== ["width", "height", "top", "right", "bottom", "left"].indexOf(prop) && isNumeric(styles[prop]) && (unit = "px"),
                element.style[prop] = styles[prop] + unit
        })
    }

    var isFirefox = isBrowser && /Firefox/i.test(navigator.userAgent);

    function isModifierRequired(modifiers, requestingName, requestedName) {
        var requesting = find(modifiers, function (_ref) {
            return _ref.name === requestingName
        })
            , isRequired = !!requesting && modifiers.some(function (modifier) {
            return modifier.name === requestedName && modifier.enabled && modifier.order < requesting.order
        });
        if (!isRequired) {
            var _requesting = "`" + requestingName + "`"
                , requested = "`" + requestedName + "`";
            console.warn(requested + " modifier is required by " + _requesting + " modifier in order to work, be sure to include it before " + _requesting + "!")
        }
        return isRequired
    }

    var placements = ["auto-start", "auto", "auto-end", "top-start", "top", "top-end", "right-start", "right", "right-end", "bottom-end", "bottom", "bottom-start", "left-end", "left", "left-start"]
        , validPlacements = placements.slice(3);

    function clockwise(placement, argument_1) {
        var counter = 1 < arguments.length && void 0 !== argument_1 && argument_1
            , index = validPlacements.indexOf(placement)
            , arr = validPlacements.slice(index + 1).concat(validPlacements.slice(0, index));
        return counter ? arr.reverse() : arr
    }

    var BEHAVIORS_FLIP = "flip"
        , BEHAVIORS_CLOCKWISE = "clockwise"
        , BEHAVIORS_COUNTERCLOCKWISE = "counterclockwise";

    function parseOffset(offset, popperOffsets, referenceOffsets, basePlacement) {
        var offsets = [0, 0]
            , useHeight = -1 !== ["right", "left"].indexOf(basePlacement)
            , fragments = offset.split(/(\+|\-)/).map(function (frag) {
            return frag.trim()
        })
            , divider = fragments.indexOf(find(fragments, function (frag) {
            return -1 !== frag.search(/,|\s/)
        }));
        fragments[divider] && -1 === fragments[divider].indexOf(",") && console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");
        var splitRegex = /\s*,\s*|\s+/
            ,
            ops = -1 !== divider ? [fragments.slice(0, divider).concat([fragments[divider].split(splitRegex)[0]]), [fragments[divider].split(splitRegex)[1]].concat(fragments.slice(divider + 1))] : [fragments];
        return (ops = ops.map(function (op, index) {
            var measurement = (1 === index ? !useHeight : useHeight) ? "height" : "width"
                , mergeWithPrevious = !1;
            return op.reduce(function (a, b) {
                return "" === a[a.length - 1] && -1 !== ["+", "-"].indexOf(b) ? (a[a.length - 1] = b,
                    mergeWithPrevious = !0,
                    a) : mergeWithPrevious ? (a[a.length - 1] += b,
                    mergeWithPrevious = !1,
                    a) : a.concat(b)
            }, []).map(function (str) {
                return function (str, measurement, popperOffsets, referenceOffsets) {
                    var split = str.match(/((?:\-|\+)?\d*\.?\d*)(.*)/)
                        , value = +split[1]
                        , unit = split[2];
                    if (!value)
                        return str;
                    if (0 !== unit.indexOf("%"))
                        return "vh" !== unit && "vw" !== unit ? value : ("vh" === unit ? Math.max(document.documentElement.clientHeight, window.innerHeight || 0) : Math.max(document.documentElement.clientWidth, window.innerWidth || 0)) / 100 * value;
                    var element = void 0;
                    switch (unit) {
                        case "%p":
                            element = popperOffsets;
                            break;
                        case "%":
                        case "%r":
                        default:
                            element = referenceOffsets
                    }
                    return getClientRect(element)[measurement] / 100 * value
                }(str, measurement, popperOffsets, referenceOffsets)
            })
        })).forEach(function (op, index) {
            op.forEach(function (frag, index2) {
                isNumeric(frag) && (offsets[index] += frag * ("-" === op[index2 - 1] ? -1 : 1))
            })
        }),
            offsets
    }

    var Defaults = {
        placement: "bottom",
        positionFixed: !1,
        eventsEnabled: !0,
        removeOnDestroy: !1,
        onCreate: function () {
        },
        onUpdate: function () {
        },
        modifiers: {
            shift: {
                order: 100,
                enabled: !0,
                fn: function (data) {
                    var placement = data.placement
                        , basePlacement = placement.split("-")[0]
                        , shiftvariation = placement.split("-")[1];
                    if (shiftvariation) {
                        var _data$offsets = data.offsets
                            , reference = _data$offsets.reference
                            , popper = _data$offsets.popper
                            , isVertical = -1 !== ["bottom", "top"].indexOf(basePlacement)
                            , side = isVertical ? "left" : "top"
                            , measurement = isVertical ? "width" : "height"
                            , shiftOffsets = {
                            start: defineProperty({}, side, reference[side]),
                            end: defineProperty({}, side, reference[side] + reference[measurement] - popper[measurement])
                        };
                        data.offsets.popper = _extends({}, popper, shiftOffsets[shiftvariation])
                    }
                    return data
                }
            },
            offset: {
                order: 200,
                enabled: !0,
                fn: function (data, _ref) {
                    var offset = _ref.offset
                        , placement = data.placement
                        , _data$offsets = data.offsets
                        , popper = _data$offsets.popper
                        , reference = _data$offsets.reference
                        , basePlacement = placement.split("-")[0]
                        , offsets = void 0;
                    return offsets = isNumeric(+offset) ? [+offset, 0] : parseOffset(offset, popper, reference, basePlacement),
                        "left" === basePlacement ? (popper.top += offsets[0],
                            popper.left -= offsets[1]) : "right" === basePlacement ? (popper.top += offsets[0],
                            popper.left += offsets[1]) : "top" === basePlacement ? (popper.left += offsets[0],
                            popper.top -= offsets[1]) : "bottom" === basePlacement && (popper.left += offsets[0],
                            popper.top += offsets[1]),
                        data.popper = popper,
                        data
                },
                offset: 0
            },
            preventOverflow: {
                order: 300,
                enabled: !0,
                fn: function (data, options) {
                    var boundariesElement = options.boundariesElement || getOffsetParent(data.instance.popper);
                    data.instance.reference === boundariesElement && (boundariesElement = getOffsetParent(boundariesElement));
                    var transformProp = getSupportedPropertyName("transform")
                        , popperStyles = data.instance.popper.style
                        , top = popperStyles.top
                        , left = popperStyles.left
                        , transform = popperStyles[transformProp];
                    popperStyles.top = "",
                        popperStyles.left = "",
                        popperStyles[transformProp] = "";
                    var boundaries = getBoundaries(data.instance.popper, data.instance.reference, options.padding, boundariesElement, data.positionFixed);
                    popperStyles.top = top,
                        popperStyles.left = left,
                        popperStyles[transformProp] = transform,
                        options.boundaries = boundaries;
                    var order = options.priority
                        , popper = data.offsets.popper
                        , check = {
                        primary: function (placement) {
                            var value = popper[placement];
                            return popper[placement] < boundaries[placement] && !options.escapeWithReference && (value = Math.max(popper[placement], boundaries[placement])),
                                defineProperty({}, placement, value)
                        },
                        secondary: function (placement) {
                            var mainSide = "right" === placement ? "left" : "top"
                                , value = popper[mainSide];
                            return popper[placement] > boundaries[placement] && !options.escapeWithReference && (value = Math.min(popper[mainSide], boundaries[placement] - ("right" === placement ? popper.width : popper.height))),
                                defineProperty({}, mainSide, value)
                        }
                    };
                    return order.forEach(function (placement) {
                        var side = -1 !== ["left", "top"].indexOf(placement) ? "primary" : "secondary";
                        popper = _extends({}, popper, check[side](placement))
                    }),
                        data.offsets.popper = popper,
                        data
                },
                priority: ["left", "right", "top", "bottom"],
                padding: 5,
                boundariesElement: "scrollParent"
            },
            keepTogether: {
                order: 400,
                enabled: !0,
                fn: function (data) {
                    var _data$offsets = data.offsets
                        , popper = _data$offsets.popper
                        , reference = _data$offsets.reference
                        , placement = data.placement.split("-")[0]
                        , floor = Math.floor
                        , isVertical = -1 !== ["top", "bottom"].indexOf(placement)
                        , side = isVertical ? "right" : "bottom"
                        , opSide = isVertical ? "left" : "top"
                        , measurement = isVertical ? "width" : "height";
                    return popper[side] < floor(reference[opSide]) && (data.offsets.popper[opSide] = floor(reference[opSide]) - popper[measurement]),
                    popper[opSide] > floor(reference[side]) && (data.offsets.popper[opSide] = floor(reference[side])),
                        data
                }
            },
            arrow: {
                order: 500,
                enabled: !0,
                fn: function (data, options) {
                    var _data$offsets$arrow;
                    if (!isModifierRequired(data.instance.modifiers, "arrow", "keepTogether"))
                        return data;
                    var arrowElement = options.element;
                    if ("string" == typeof arrowElement) {
                        if (!(arrowElement = data.instance.popper.querySelector(arrowElement)))
                            return data
                    } else if (!data.instance.popper.contains(arrowElement))
                        return console.warn("WARNING: `arrow.element` must be child of its popper element!"),
                            data;
                    var placement = data.placement.split("-")[0]
                        , _data$offsets = data.offsets
                        , popper = _data$offsets.popper
                        , reference = _data$offsets.reference
                        , isVertical = -1 !== ["left", "right"].indexOf(placement)
                        , len = isVertical ? "height" : "width"
                        , sideCapitalized = isVertical ? "Top" : "Left"
                        , side = sideCapitalized.toLowerCase()
                        , altSide = isVertical ? "left" : "top"
                        , opSide = isVertical ? "bottom" : "right"
                        , arrowElementSize = getOuterSizes(arrowElement)[len];
                    reference[opSide] - arrowElementSize < popper[side] && (data.offsets.popper[side] -= popper[side] - (reference[opSide] - arrowElementSize)),
                    reference[side] + arrowElementSize > popper[opSide] && (data.offsets.popper[side] += reference[side] + arrowElementSize - popper[opSide]),
                        data.offsets.popper = getClientRect(data.offsets.popper);
                    var center = reference[side] + reference[len] / 2 - arrowElementSize / 2
                        , css = getStyleComputedProperty(data.instance.popper)
                        , popperMarginSide = parseFloat(css["margin" + sideCapitalized], 10)
                        , popperBorderSide = parseFloat(css["border" + sideCapitalized + "Width"], 10)
                        , sideValue = center - data.offsets.popper[side] - popperMarginSide - popperBorderSide;
                    return sideValue = Math.max(Math.min(popper[len] - arrowElementSize, sideValue), 0),
                        data.arrowElement = arrowElement,
                        data.offsets.arrow = (defineProperty(_data$offsets$arrow = {}, side, Math.round(sideValue)),
                            defineProperty(_data$offsets$arrow, altSide, ""),
                            _data$offsets$arrow),
                        data
                },
                element: "[x-arrow]"
            },
            flip: {
                order: 600,
                enabled: !0,
                fn: function (data, options) {
                    if (isModifierEnabled(data.instance.modifiers, "inner"))
                        return data;
                    if (data.flipped && data.placement === data.originalPlacement)
                        return data;
                    var boundaries = getBoundaries(data.instance.popper, data.instance.reference, options.padding, options.boundariesElement, data.positionFixed)
                        , placement = data.placement.split("-")[0]
                        , placementOpposite = getOppositePlacement(placement)
                        , variation = data.placement.split("-")[1] || ""
                        , flipOrder = [];
                    switch (options.behavior) {
                        case BEHAVIORS_FLIP:
                            flipOrder = [placement, placementOpposite];
                            break;
                        case BEHAVIORS_CLOCKWISE:
                            flipOrder = clockwise(placement);
                            break;
                        case BEHAVIORS_COUNTERCLOCKWISE:
                            flipOrder = clockwise(placement, !0);
                            break;
                        default:
                            flipOrder = options.behavior
                    }
                    return flipOrder.forEach(function (step, index) {
                        if (placement !== step || flipOrder.length === index + 1)
                            return data;
                        placement = data.placement.split("-")[0],
                            placementOpposite = getOppositePlacement(placement);
                        var popperOffsets = data.offsets.popper
                            , refOffsets = data.offsets.reference
                            , floor = Math.floor
                            ,
                            overlapsRef = "left" === placement && floor(popperOffsets.right) > floor(refOffsets.left) || "right" === placement && floor(popperOffsets.left) < floor(refOffsets.right) || "top" === placement && floor(popperOffsets.bottom) > floor(refOffsets.top) || "bottom" === placement && floor(popperOffsets.top) < floor(refOffsets.bottom)
                            , overflowsLeft = floor(popperOffsets.left) < floor(boundaries.left)
                            , overflowsRight = floor(popperOffsets.right) > floor(boundaries.right)
                            , overflowsTop = floor(popperOffsets.top) < floor(boundaries.top)
                            , overflowsBottom = floor(popperOffsets.bottom) > floor(boundaries.bottom)
                            ,
                            overflowsBoundaries = "left" === placement && overflowsLeft || "right" === placement && overflowsRight || "top" === placement && overflowsTop || "bottom" === placement && overflowsBottom
                            , isVertical = -1 !== ["top", "bottom"].indexOf(placement)
                            ,
                            flippedVariation = !!options.flipVariations && (isVertical && "start" === variation && overflowsLeft || isVertical && "end" === variation && overflowsRight || !isVertical && "start" === variation && overflowsTop || !isVertical && "end" === variation && overflowsBottom);
                        (overlapsRef || overflowsBoundaries || flippedVariation) && (data.flipped = !0,
                        (overlapsRef || overflowsBoundaries) && (placement = flipOrder[index + 1]),
                        flippedVariation && (variation = function (variation) {
                            return "end" === variation ? "start" : "start" === variation ? "end" : variation
                        }(variation)),
                            data.placement = placement + (variation ? "-" + variation : ""),
                            data.offsets.popper = _extends({}, data.offsets.popper, getPopperOffsets(data.instance.popper, data.offsets.reference, data.placement)),
                            data = runModifiers(data.instance.modifiers, data, "flip"))
                    }),
                        data
                },
                behavior: "flip",
                padding: 5,
                boundariesElement: "viewport"
            },
            inner: {
                order: 700,
                enabled: !1,
                fn: function (data) {
                    var placement = data.placement
                        , basePlacement = placement.split("-")[0]
                        , _data$offsets = data.offsets
                        , popper = _data$offsets.popper
                        , reference = _data$offsets.reference
                        , isHoriz = -1 !== ["left", "right"].indexOf(basePlacement)
                        , subtractLength = -1 === ["top", "left"].indexOf(basePlacement);
                    return popper[isHoriz ? "left" : "top"] = reference[basePlacement] - (subtractLength ? popper[isHoriz ? "width" : "height"] : 0),
                        data.placement = getOppositePlacement(placement),
                        data.offsets.popper = getClientRect(popper),
                        data
                }
            },
            hide: {
                order: 800,
                enabled: !0,
                fn: function (data) {
                    if (!isModifierRequired(data.instance.modifiers, "hide", "preventOverflow"))
                        return data;
                    var refRect = data.offsets.reference
                        , bound = find(data.instance.modifiers, function (modifier) {
                        return "preventOverflow" === modifier.name
                    }).boundaries;
                    if (refRect.bottom < bound.top || refRect.left > bound.right || refRect.top > bound.bottom || refRect.right < bound.left) {
                        if (!0 === data.hide)
                            return data;
                        data.hide = !0,
                            data.attributes["x-out-of-boundaries"] = ""
                    } else {
                        if (!1 === data.hide)
                            return data;
                        data.hide = !1,
                            data.attributes["x-out-of-boundaries"] = !1
                    }
                    return data
                }
            },
            computeStyle: {
                order: 850,
                enabled: !0,
                fn: function (data, options) {
                    var x = options.x
                        , y = options.y
                        , popper = data.offsets.popper
                        , legacyGpuAccelerationOption = find(data.instance.modifiers, function (modifier) {
                        return "applyStyle" === modifier.name
                    }).gpuAcceleration;
                    void 0 !== legacyGpuAccelerationOption && console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");
                    var gpuAcceleration = void 0 !== legacyGpuAccelerationOption ? legacyGpuAccelerationOption : options.gpuAcceleration
                        , offsetParent = getOffsetParent(data.instance.popper)
                        , offsetParentRect = getBoundingClientRect(offsetParent)
                        , styles = {
                        position: popper.position
                    }
                        , offsets = function (data, shouldRound) {
                        var _data$offsets = data.offsets
                            , popper = _data$offsets.popper
                            , reference = _data$offsets.reference
                            , round = Math.round
                            , floor = Math.floor
                            , noRound = function (v) {
                                return v
                            }
                            , referenceWidth = round(reference.width)
                            , popperWidth = round(popper.width)
                            , isVertical = -1 !== ["left", "right"].indexOf(data.placement)
                            , isVariation = -1 !== data.placement.indexOf("-")
                            ,
                            horizontalToInteger = shouldRound ? isVertical || isVariation || referenceWidth % 2 == popperWidth % 2 ? round : floor : noRound
                            , verticalToInteger = shouldRound ? round : noRound;
                        return {
                            left: horizontalToInteger(referenceWidth % 2 == 1 && popperWidth % 2 == 1 && !isVariation && shouldRound ? popper.left - 1 : popper.left),
                            top: verticalToInteger(popper.top),
                            bottom: verticalToInteger(popper.bottom),
                            right: horizontalToInteger(popper.right)
                        }
                    }(data, window.devicePixelRatio < 2 || !isFirefox)
                        , sideA = "bottom" === x ? "top" : "bottom"
                        , sideB = "right" === y ? "left" : "right"
                        , prefixedProperty = getSupportedPropertyName("transform")
                        , left = void 0
                        , top = void 0;
                    if (top = "bottom" == sideA ? "HTML" === offsetParent.nodeName ? -offsetParent.clientHeight + offsets.bottom : -offsetParentRect.height + offsets.bottom : offsets.top,
                        left = "right" == sideB ? "HTML" === offsetParent.nodeName ? -offsetParent.clientWidth + offsets.right : -offsetParentRect.width + offsets.right : offsets.left,
                    gpuAcceleration && prefixedProperty)
                        styles[prefixedProperty] = "translate3d(" + left + "px, " + top + "px, 0)",
                            styles[sideA] = 0,
                            styles[sideB] = 0,
                            styles.willChange = "transform";
                    else {
                        var invertTop = "bottom" == sideA ? -1 : 1
                            , invertLeft = "right" == sideB ? -1 : 1;
                        styles[sideA] = top * invertTop,
                            styles[sideB] = left * invertLeft,
                            styles.willChange = sideA + ", " + sideB
                    }
                    var attributes = {
                        "x-placement": data.placement
                    };
                    return data.attributes = _extends({}, attributes, data.attributes),
                        data.styles = _extends({}, styles, data.styles),
                        data.arrowStyles = _extends({}, data.offsets.arrow, data.arrowStyles),
                        data
                },
                gpuAcceleration: !0,
                x: "bottom",
                y: "right"
            },
            applyStyle: {
                order: 900,
                enabled: !0,
                fn: function (data) {
                    var element, attributes;
                    return setStyles(data.instance.popper, data.styles),
                        element = data.instance.popper,
                        attributes = data.attributes,
                        Object.keys(attributes).forEach(function (prop) {
                            !1 !== attributes[prop] ? element.setAttribute(prop, attributes[prop]) : element.removeAttribute(prop)
                        }),
                    data.arrowElement && Object.keys(data.arrowStyles).length && setStyles(data.arrowElement, data.arrowStyles),
                        data
                },
                onLoad: function (reference, popper, options, modifierOptions, state) {
                    var referenceOffsets = getReferenceOffsets(state, popper, reference, options.positionFixed)
                        ,
                        placement = computeAutoPlacement(options.placement, referenceOffsets, popper, reference, options.modifiers.flip.boundariesElement, options.modifiers.flip.padding);
                    return popper.setAttribute("x-placement", placement),
                        setStyles(popper, {
                            position: options.positionFixed ? "fixed" : "absolute"
                        }),
                        options
                },
                gpuAcceleration: void 0
            }
        }
    }
        , Popper = function () {
        function Popper(reference, popper) {
            var _this = this
                , options = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : {};
            !function (instance, Constructor) {
                if (!(instance instanceof Constructor))
                    throw new TypeError("Cannot call a class as a function")
            }(this, Popper),
                this.scheduleUpdate = function () {
                    return requestAnimationFrame(_this.update)
                }
                ,
                this.update = debounce(this.update.bind(this)),
                this.options = _extends({}, Popper.Defaults, options),
                this.state = {
                    isDestroyed: !1,
                    isCreated: !1,
                    scrollParents: []
                },
                this.reference = reference && reference.jquery ? reference[0] : reference,
                this.popper = popper && popper.jquery ? popper[0] : popper,
                this.options.modifiers = {},
                Object.keys(_extends({}, Popper.Defaults.modifiers, options.modifiers)).forEach(function (name) {
                    _this.options.modifiers[name] = _extends({}, Popper.Defaults.modifiers[name] || {}, options.modifiers ? options.modifiers[name] : {})
                }),
                this.modifiers = Object.keys(this.options.modifiers).map(function (name) {
                    return _extends({
                        name: name
                    }, _this.options.modifiers[name])
                }).sort(function (a, b) {
                    return a.order - b.order
                }),
                this.modifiers.forEach(function (modifierOptions) {
                    modifierOptions.enabled && isFunction(modifierOptions.onLoad) && modifierOptions.onLoad(_this.reference, _this.popper, _this.options, modifierOptions, _this.state)
                }),
                this.update();
            var eventsEnabled = this.options.eventsEnabled;
            eventsEnabled && this.enableEventListeners(),
                this.state.eventsEnabled = eventsEnabled
        }

        return createClass(Popper, [{
            key: "update",
            value: function () {
                return function () {
                    if (!this.state.isDestroyed) {
                        var data = {
                            instance: this,
                            styles: {},
                            arrowStyles: {},
                            attributes: {},
                            flipped: !1,
                            offsets: {}
                        };
                        data.offsets.reference = getReferenceOffsets(this.state, this.popper, this.reference, this.options.positionFixed),
                            data.placement = computeAutoPlacement(this.options.placement, data.offsets.reference, this.popper, this.reference, this.options.modifiers.flip.boundariesElement, this.options.modifiers.flip.padding),
                            data.originalPlacement = data.placement,
                            data.positionFixed = this.options.positionFixed,
                            data.offsets.popper = getPopperOffsets(this.popper, data.offsets.reference, data.placement),
                            data.offsets.popper.position = this.options.positionFixed ? "fixed" : "absolute",
                            data = runModifiers(this.modifiers, data),
                            this.state.isCreated ? this.options.onUpdate(data) : (this.state.isCreated = !0,
                                this.options.onCreate(data))
                    }
                }
                    .call(this)
            }
        }, {
            key: "destroy",
            value: function () {
                return function () {
                    return this.state.isDestroyed = !0,
                    isModifierEnabled(this.modifiers, "applyStyle") && (this.popper.removeAttribute("x-placement"),
                        this.popper.style.position = "",
                        this.popper.style.top = "",
                        this.popper.style.left = "",
                        this.popper.style.right = "",
                        this.popper.style.bottom = "",
                        this.popper.style.willChange = "",
                        this.popper.style[getSupportedPropertyName("transform")] = ""),
                        this.disableEventListeners(),
                    this.options.removeOnDestroy && this.popper.parentNode.removeChild(this.popper),
                        this
                }
                    .call(this)
            }
        }, {
            key: "enableEventListeners",
            value: function () {
                return function () {
                    this.state.eventsEnabled || (this.state = setupEventListeners(this.reference, this.options, this.state, this.scheduleUpdate))
                }
                    .call(this)
            }
        }, {
            key: "disableEventListeners",
            value: function () {
                return disableEventListeners.call(this)
            }
        }]),
            Popper
    }();
    Popper.Utils = ("undefined" != typeof window ? window : global).PopperUtils,
        Popper.placements = placements,
        Popper.Defaults = Defaults;
    var NAME$4 = "dropdown"
        , JQUERY_NO_CONFLICT$4 = $.fn[NAME$4]
        , REGEXP_KEYDOWN = new RegExp("38|40|27")
        , Event$4 = {
        HIDE: "hide.bs.dropdown",
        HIDDEN: "hidden.bs.dropdown",
        SHOW: "show.bs.dropdown",
        SHOWN: "shown.bs.dropdown",
        CLICK: "click.bs.dropdown",
        CLICK_DATA_API: "click.bs.dropdown.data-api",
        KEYDOWN_DATA_API: "keydown.bs.dropdown.data-api",
        KEYUP_DATA_API: "keyup.bs.dropdown.data-api"
    }
        , ClassName$4_DISABLED = "disabled"
        , ClassName$4_SHOW = "show"
        , ClassName$4_DROPUP = "dropup"
        , ClassName$4_DROPRIGHT = "dropright"
        , ClassName$4_DROPLEFT = "dropleft"
        , ClassName$4_MENURIGHT = "dropdown-menu-right"
        , ClassName$4_POSITION_STATIC = "position-static"
        , Selector$4_DATA_TOGGLE = '[data-toggle="dropdown"]'
        , Selector$4_FORM_CHILD = ".dropdown form"
        , Selector$4_MENU = ".dropdown-menu"
        , Selector$4_NAVBAR_NAV = ".navbar-nav"
        , Selector$4_VISIBLE_ITEMS = ".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)"
        , AttachmentMap_TOP = "top-start"
        , AttachmentMap_TOPEND = "top-end"
        , AttachmentMap_BOTTOM = "bottom-start"
        , AttachmentMap_BOTTOMEND = "bottom-end"
        , AttachmentMap_RIGHT = "right-start"
        , AttachmentMap_LEFT = "left-start"
        , Default$2 = {
        offset: 0,
        flip: !0,
        boundary: "scrollParent",
        reference: "toggle",
        display: "dynamic"
    }
        , DefaultType$2 = {
        offset: "(number|string|function)",
        flip: "boolean",
        boundary: "(string|element)",
        reference: "(string|element)",
        display: "string"
    }
        , Dropdown = function () {
        function Dropdown(element, config) {
            this._element = element,
                this._popper = null,
                this._config = this._getConfig(config),
                this._menu = this._getMenuElement(),
                this._inNavbar = this._detectNavbar(),
                this._addEventListeners()
        }

        var _proto = Dropdown.prototype;
        return _proto.toggle = function () {
            if (!this._element.disabled && !$(this._element).hasClass(ClassName$4_DISABLED)) {
                var parent = Dropdown._getParentFromElement(this._element)
                    , isActive = $(this._menu).hasClass(ClassName$4_SHOW);
                if (Dropdown._clearMenus(),
                    !isActive) {
                    var relatedTarget = {
                        relatedTarget: this._element
                    }
                        , showEvent = $.Event(Event$4.SHOW, relatedTarget);
                    if ($(parent).trigger(showEvent),
                        !showEvent.isDefaultPrevented()) {
                        if (!this._inNavbar) {
                            if (void 0 === Popper)
                                throw new TypeError("Bootstrap's dropdowns require Popper.js (https://popper.js.org/)");
                            var referenceElement = this._element;
                            "parent" === this._config.reference ? referenceElement = parent : Util.isElement(this._config.reference) && (referenceElement = this._config.reference,
                            void 0 !== this._config.reference.jquery && (referenceElement = this._config.reference[0])),
                            "scrollParent" !== this._config.boundary && $(parent).addClass(ClassName$4_POSITION_STATIC),
                                this._popper = new Popper(referenceElement, this._menu, this._getPopperConfig())
                        }
                        "ontouchstart" in document.documentElement && 0 === $(parent).closest(Selector$4_NAVBAR_NAV).length && $(document.body).children().on("mouseover", null, $.noop),
                            this._element.focus(),
                            this._element.setAttribute("aria-expanded", !0),
                            $(this._menu).toggleClass(ClassName$4_SHOW),
                            $(parent).toggleClass(ClassName$4_SHOW).trigger($.Event(Event$4.SHOWN, relatedTarget))
                    }
                }
            }
        }
            ,
            _proto.show = function () {
                if (!(this._element.disabled || $(this._element).hasClass(ClassName$4_DISABLED) || $(this._menu).hasClass(ClassName$4_SHOW))) {
                    var relatedTarget = {
                        relatedTarget: this._element
                    }
                        , showEvent = $.Event(Event$4.SHOW, relatedTarget)
                        , parent = Dropdown._getParentFromElement(this._element);
                    $(parent).trigger(showEvent),
                    showEvent.isDefaultPrevented() || ($(this._menu).toggleClass(ClassName$4_SHOW),
                        $(parent).toggleClass(ClassName$4_SHOW).trigger($.Event(Event$4.SHOWN, relatedTarget)))
                }
            }
            ,
            _proto.hide = function () {
                if (!this._element.disabled && !$(this._element).hasClass(ClassName$4_DISABLED) && $(this._menu).hasClass(ClassName$4_SHOW)) {
                    var relatedTarget = {
                        relatedTarget: this._element
                    }
                        , hideEvent = $.Event(Event$4.HIDE, relatedTarget)
                        , parent = Dropdown._getParentFromElement(this._element);
                    $(parent).trigger(hideEvent),
                    hideEvent.isDefaultPrevented() || ($(this._menu).toggleClass(ClassName$4_SHOW),
                        $(parent).toggleClass(ClassName$4_SHOW).trigger($.Event(Event$4.HIDDEN, relatedTarget)))
                }
            }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.dropdown"),
                    $(this._element).off(".bs.dropdown"),
                    this._element = null,
                (this._menu = null) !== this._popper && (this._popper.destroy(),
                    this._popper = null)
            }
            ,
            _proto.update = function () {
                this._inNavbar = this._detectNavbar(),
                null !== this._popper && this._popper.scheduleUpdate()
            }
            ,
            _proto._addEventListeners = function () {
                var _this = this;
                $(this._element).on(Event$4.CLICK, function (event) {
                    event.preventDefault(),
                        event.stopPropagation(),
                        _this.toggle()
                })
            }
            ,
            _proto._getConfig = function (config) {
                return config = _objectSpread({}, this.constructor.Default, $(this._element).data(), config),
                    Util.typeCheckConfig(NAME$4, config, this.constructor.DefaultType),
                    config
            }
            ,
            _proto._getMenuElement = function () {
                if (!this._menu) {
                    var parent = Dropdown._getParentFromElement(this._element);
                    parent && (this._menu = parent.querySelector(Selector$4_MENU))
                }
                return this._menu
            }
            ,
            _proto._getPlacement = function () {
                var $parentDropdown = $(this._element.parentNode)
                    , placement = AttachmentMap_BOTTOM;
                return $parentDropdown.hasClass(ClassName$4_DROPUP) ? (placement = AttachmentMap_TOP,
                $(this._menu).hasClass(ClassName$4_MENURIGHT) && (placement = AttachmentMap_TOPEND)) : $parentDropdown.hasClass(ClassName$4_DROPRIGHT) ? placement = AttachmentMap_RIGHT : $parentDropdown.hasClass(ClassName$4_DROPLEFT) ? placement = AttachmentMap_LEFT : $(this._menu).hasClass(ClassName$4_MENURIGHT) && (placement = AttachmentMap_BOTTOMEND),
                    placement
            }
            ,
            _proto._detectNavbar = function () {
                return 0 < $(this._element).closest(".navbar").length
            }
            ,
            _proto._getOffset = function () {
                var _this2 = this
                    , offset = {};
                return "function" == typeof this._config.offset ? offset.fn = function (data) {
                        return data.offsets = _objectSpread({}, data.offsets, _this2._config.offset(data.offsets, _this2._element) || {}),
                            data
                    }
                    : offset.offset = this._config.offset,
                    offset
            }
            ,
            _proto._getPopperConfig = function () {
                var popperConfig = {
                    placement: this._getPlacement(),
                    modifiers: {
                        offset: this._getOffset(),
                        flip: {
                            enabled: this._config.flip
                        },
                        preventOverflow: {
                            boundariesElement: this._config.boundary
                        }
                    }
                };
                return "static" === this._config.display && (popperConfig.modifiers.applyStyle = {
                    enabled: !1
                }),
                    popperConfig
            }
            ,
            Dropdown._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.dropdown");
                    if (data || (data = new Dropdown(this, "object" == typeof config ? config : null),
                        $(this).data("bs.dropdown", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            Dropdown._clearMenus = function (event) {
                if (!event || 3 !== event.which && ("keyup" !== event.type || 9 === event.which))
                    for (var toggles = [].slice.call(document.querySelectorAll(Selector$4_DATA_TOGGLE)), i = 0, len = toggles.length; i < len; i++) {
                        var parent = Dropdown._getParentFromElement(toggles[i])
                            , context = $(toggles[i]).data("bs.dropdown")
                            , relatedTarget = {
                            relatedTarget: toggles[i]
                        };
                        if (event && "click" === event.type && (relatedTarget.clickEvent = event),
                            context) {
                            var dropdownMenu = context._menu;
                            if ($(parent).hasClass(ClassName$4_SHOW) && !(event && ("click" === event.type && /input|textarea/i.test(event.target.tagName) || "keyup" === event.type && 9 === event.which) && $.contains(parent, event.target))) {
                                var hideEvent = $.Event(Event$4.HIDE, relatedTarget);
                                $(parent).trigger(hideEvent),
                                hideEvent.isDefaultPrevented() || ("ontouchstart" in document.documentElement && $(document.body).children().off("mouseover", null, $.noop),
                                    toggles[i].setAttribute("aria-expanded", "false"),
                                    $(dropdownMenu).removeClass(ClassName$4_SHOW),
                                    $(parent).removeClass(ClassName$4_SHOW).trigger($.Event(Event$4.HIDDEN, relatedTarget)))
                            }
                        }
                    }
            }
            ,
            Dropdown._getParentFromElement = function (element) {
                var parent, selector = Util.getSelectorFromElement(element);
                return selector && (parent = document.querySelector(selector)),
                parent || element.parentNode
            }
            ,
            Dropdown._dataApiKeydownHandler = function (event) {
                if ((/input|textarea/i.test(event.target.tagName) ? !(32 === event.which || 27 !== event.which && (40 !== event.which && 38 !== event.which || $(event.target).closest(Selector$4_MENU).length)) : REGEXP_KEYDOWN.test(event.which)) && (event.preventDefault(),
                    event.stopPropagation(),
                !this.disabled && !$(this).hasClass(ClassName$4_DISABLED))) {
                    var parent = Dropdown._getParentFromElement(this)
                        , isActive = $(parent).hasClass(ClassName$4_SHOW);
                    if (isActive && (!isActive || 27 !== event.which && 32 !== event.which)) {
                        var items = [].slice.call(parent.querySelectorAll(Selector$4_VISIBLE_ITEMS));
                        if (0 !== items.length) {
                            var index = items.indexOf(event.target);
                            38 === event.which && 0 < index && index--,
                            40 === event.which && index < items.length - 1 && index++,
                            index < 0 && (index = 0),
                                items[index].focus()
                        }
                    } else {
                        if (27 === event.which) {
                            var toggle = parent.querySelector(Selector$4_DATA_TOGGLE);
                            $(toggle).trigger("focus")
                        }
                        $(this).trigger("click")
                    }
                }
            }
            ,
            _createClass(Dropdown, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$2
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return DefaultType$2
                }
            }]),
            Dropdown
    }();
    $(document).on(Event$4.KEYDOWN_DATA_API, Selector$4_DATA_TOGGLE, Dropdown._dataApiKeydownHandler).on(Event$4.KEYDOWN_DATA_API, Selector$4_MENU, Dropdown._dataApiKeydownHandler).on(Event$4.CLICK_DATA_API + " " + Event$4.KEYUP_DATA_API, Dropdown._clearMenus).on(Event$4.CLICK_DATA_API, Selector$4_DATA_TOGGLE, function (event) {
        event.preventDefault(),
            event.stopPropagation(),
            Dropdown._jQueryInterface.call($(this), "toggle")
    }).on(Event$4.CLICK_DATA_API, Selector$4_FORM_CHILD, function (e) {
        e.stopPropagation()
    }),
        $.fn[NAME$4] = Dropdown._jQueryInterface,
        $.fn[NAME$4].Constructor = Dropdown,
        $.fn[NAME$4].noConflict = function () {
            return $.fn[NAME$4] = JQUERY_NO_CONFLICT$4,
                Dropdown._jQueryInterface
        }
    ;
    var JQUERY_NO_CONFLICT$5 = $.fn.modal
        , Default$3 = {
        backdrop: !0,
        keyboard: !0,
        focus: !0,
        show: !0
    }
        , DefaultType$3 = {
        backdrop: "(boolean|string)",
        keyboard: "boolean",
        focus: "boolean",
        show: "boolean"
    }
        , Event$5 = {
        HIDE: "hide.bs.modal",
        HIDDEN: "hidden.bs.modal",
        SHOW: "show.bs.modal",
        SHOWN: "shown.bs.modal",
        FOCUSIN: "focusin.bs.modal",
        RESIZE: "resize.bs.modal",
        CLICK_DISMISS: "click.dismiss.bs.modal",
        KEYDOWN_DISMISS: "keydown.dismiss.bs.modal",
        MOUSEUP_DISMISS: "mouseup.dismiss.bs.modal",
        MOUSEDOWN_DISMISS: "mousedown.dismiss.bs.modal",
        CLICK_DATA_API: "click.bs.modal.data-api"
    }
        , ClassName$5_SCROLLABLE = "modal-dialog-scrollable"
        , ClassName$5_SCROLLBAR_MEASURER = "modal-scrollbar-measure"
        , ClassName$5_BACKDROP = "modal-backdrop"
        , ClassName$5_OPEN = "modal-open"
        , ClassName$5_FADE = "fade"
        , ClassName$5_SHOW = "show"
        , Selector$5_DIALOG = ".modal-dialog"
        , Selector$5_MODAL_BODY = ".modal-body"
        , Selector$5_DATA_TOGGLE = '[data-toggle="modal"]'
        , Selector$5_DATA_DISMISS = '[data-dismiss="modal"]'
        , Selector$5_FIXED_CONTENT = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top"
        , Selector$5_STICKY_CONTENT = ".sticky-top"
        , Modal = function () {
        function Modal(element, config) {
            this._config = this._getConfig(config),
                this._element = element,
                this._dialog = element.querySelector(Selector$5_DIALOG),
                this._backdrop = null,
                this._isShown = !1,
                this._isBodyOverflowing = !1,
                this._ignoreBackdropClick = !1,
                this._isTransitioning = !1,
                this._scrollbarWidth = 0
        }

        var _proto = Modal.prototype;
        return _proto.toggle = function (relatedTarget) {
            return this._isShown ? this.hide() : this.show(relatedTarget)
        }
            ,
            _proto.show = function (relatedTarget) {
                var _this = this;
                if (!this._isShown && !this._isTransitioning) {
                    $(this._element).hasClass(ClassName$5_FADE) && (this._isTransitioning = !0);
                    var showEvent = $.Event(Event$5.SHOW, {
                        relatedTarget: relatedTarget
                    });
                    $(this._element).trigger(showEvent),
                    this._isShown || showEvent.isDefaultPrevented() || (this._isShown = !0,
                        this._checkScrollbar(),
                        this._setScrollbar(),
                        this._adjustDialog(),
                        this._setEscapeEvent(),
                        this._setResizeEvent(),
                        $(this._element).on(Event$5.CLICK_DISMISS, Selector$5_DATA_DISMISS, function (event) {
                            return _this.hide(event)
                        }),
                        $(this._dialog).on(Event$5.MOUSEDOWN_DISMISS, function () {
                            $(_this._element).one(Event$5.MOUSEUP_DISMISS, function (event) {
                                $(event.target).is(_this._element) && (_this._ignoreBackdropClick = !0)
                            })
                        }),
                        this._showBackdrop(function () {
                            return _this._showElement(relatedTarget)
                        }))
                }
            }
            ,
            _proto.hide = function (event) {
                var _this2 = this;
                if (event && event.preventDefault(),
                this._isShown && !this._isTransitioning) {
                    var hideEvent = $.Event(Event$5.HIDE);
                    if ($(this._element).trigger(hideEvent),
                    this._isShown && !hideEvent.isDefaultPrevented()) {
                        this._isShown = !1;
                        var transition = $(this._element).hasClass(ClassName$5_FADE);
                        if (transition && (this._isTransitioning = !0),
                            this._setEscapeEvent(),
                            this._setResizeEvent(),
                            $(document).off(Event$5.FOCUSIN),
                            $(this._element).removeClass(ClassName$5_SHOW),
                            $(this._element).off(Event$5.CLICK_DISMISS),
                            $(this._dialog).off(Event$5.MOUSEDOWN_DISMISS),
                            transition) {
                            var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                            $(this._element).one(Util.TRANSITION_END, function (event) {
                                return _this2._hideModal(event)
                            }).emulateTransitionEnd(transitionDuration)
                        } else
                            this._hideModal()
                    }
                }
            }
            ,
            _proto.dispose = function () {
                [window, this._element, this._dialog].forEach(function (htmlElement) {
                    return $(htmlElement).off(".bs.modal")
                }),
                    $(document).off(Event$5.FOCUSIN),
                    $.removeData(this._element, "bs.modal"),
                    this._config = null,
                    this._element = null,
                    this._dialog = null,
                    this._backdrop = null,
                    this._isShown = null,
                    this._isBodyOverflowing = null,
                    this._ignoreBackdropClick = null,
                    this._isTransitioning = null,
                    this._scrollbarWidth = null
            }
            ,
            _proto.handleUpdate = function () {
                this._adjustDialog()
            }
            ,
            _proto._getConfig = function (config) {
                return config = _objectSpread({}, Default$3, config),
                    Util.typeCheckConfig("modal", config, DefaultType$3),
                    config
            }
            ,
            _proto._showElement = function (relatedTarget) {
                var _this3 = this
                    , transition = $(this._element).hasClass(ClassName$5_FADE);
                this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element),
                    this._element.style.display = "block",
                    this._element.removeAttribute("aria-hidden"),
                    this._element.setAttribute("aria-modal", !0),
                    $(this._dialog).hasClass(ClassName$5_SCROLLABLE) ? this._dialog.querySelector(Selector$5_MODAL_BODY).scrollTop = 0 : this._element.scrollTop = 0,
                transition && Util.reflow(this._element),
                    $(this._element).addClass(ClassName$5_SHOW),
                this._config.focus && this._enforceFocus();
                var shownEvent = $.Event(Event$5.SHOWN, {
                    relatedTarget: relatedTarget
                })
                    , transitionComplete = function () {
                    _this3._config.focus && _this3._element.focus(),
                        _this3._isTransitioning = !1,
                        $(_this3._element).trigger(shownEvent)
                };
                if (transition) {
                    var transitionDuration = Util.getTransitionDurationFromElement(this._dialog);
                    $(this._dialog).one(Util.TRANSITION_END, transitionComplete).emulateTransitionEnd(transitionDuration)
                } else
                    transitionComplete()
            }
            ,
            _proto._enforceFocus = function () {
                var _this4 = this;
                $(document).off(Event$5.FOCUSIN).on(Event$5.FOCUSIN, function (event) {
                    document !== event.target && _this4._element !== event.target && 0 === $(_this4._element).has(event.target).length && _this4._element.focus()
                })
            }
            ,
            _proto._setEscapeEvent = function () {
                var _this5 = this;
                this._isShown && this._config.keyboard ? $(this._element).on(Event$5.KEYDOWN_DISMISS, function (event) {
                    27 === event.which && (event.preventDefault(),
                        _this5.hide())
                }) : this._isShown || $(this._element).off(Event$5.KEYDOWN_DISMISS)
            }
            ,
            _proto._setResizeEvent = function () {
                var _this6 = this;
                this._isShown ? $(window).on(Event$5.RESIZE, function (event) {
                    return _this6.handleUpdate(event)
                }) : $(window).off(Event$5.RESIZE)
            }
            ,
            _proto._hideModal = function () {
                var _this7 = this;
                this._element.style.display = "none",
                    this._element.setAttribute("aria-hidden", !0),
                    this._element.removeAttribute("aria-modal"),
                    this._isTransitioning = !1,
                    this._showBackdrop(function () {
                        $(document.body).removeClass(ClassName$5_OPEN),
                            _this7._resetAdjustments(),
                            _this7._resetScrollbar(),
                            $(_this7._element).trigger(Event$5.HIDDEN)
                    })
            }
            ,
            _proto._removeBackdrop = function () {
                this._backdrop && ($(this._backdrop).remove(),
                    this._backdrop = null)
            }
            ,
            _proto._showBackdrop = function (callback) {
                var _this8 = this
                    , animate = $(this._element).hasClass(ClassName$5_FADE) ? ClassName$5_FADE : "";
                if (this._isShown && this._config.backdrop) {
                    if (this._backdrop = document.createElement("div"),
                        this._backdrop.className = ClassName$5_BACKDROP,
                    animate && this._backdrop.classList.add(animate),
                        $(this._backdrop).appendTo(document.body),
                        $(this._element).on(Event$5.CLICK_DISMISS, function (event) {
                            _this8._ignoreBackdropClick ? _this8._ignoreBackdropClick = !1 : event.target === event.currentTarget && ("static" === _this8._config.backdrop ? _this8._element.focus() : _this8.hide())
                        }),
                    animate && Util.reflow(this._backdrop),
                        $(this._backdrop).addClass(ClassName$5_SHOW),
                        !callback)
                        return;
                    if (!animate)
                        return void callback();
                    var backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);
                    $(this._backdrop).one(Util.TRANSITION_END, callback).emulateTransitionEnd(backdropTransitionDuration)
                } else if (!this._isShown && this._backdrop) {
                    $(this._backdrop).removeClass(ClassName$5_SHOW);
                    var callbackRemove = function () {
                        _this8._removeBackdrop(),
                        callback && callback()
                    };
                    if ($(this._element).hasClass(ClassName$5_FADE)) {
                        var _backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);
                        $(this._backdrop).one(Util.TRANSITION_END, callbackRemove).emulateTransitionEnd(_backdropTransitionDuration)
                    } else
                        callbackRemove()
                } else
                    callback && callback()
            }
            ,
            _proto._adjustDialog = function () {
                var isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;
                !this._isBodyOverflowing && isModalOverflowing && (this._element.style.paddingLeft = this._scrollbarWidth + "px"),
                this._isBodyOverflowing && !isModalOverflowing && (this._element.style.paddingRight = this._scrollbarWidth + "px")
            }
            ,
            _proto._resetAdjustments = function () {
                this._element.style.paddingLeft = "",
                    this._element.style.paddingRight = ""
            }
            ,
            _proto._checkScrollbar = function () {
                var rect = document.body.getBoundingClientRect();
                this._isBodyOverflowing = rect.left + rect.right < window.innerWidth,
                    this._scrollbarWidth = this._getScrollbarWidth()
            }
            ,
            _proto._setScrollbar = function () {
                var _this9 = this;
                if (this._isBodyOverflowing) {
                    var fixedContent = [].slice.call(document.querySelectorAll(Selector$5_FIXED_CONTENT))
                        , stickyContent = [].slice.call(document.querySelectorAll(Selector$5_STICKY_CONTENT));
                    $(fixedContent).each(function (index, element) {
                        var actualPadding = element.style.paddingRight
                            , calculatedPadding = $(element).css("padding-right");
                        $(element).data("padding-right", actualPadding).css("padding-right", parseFloat(calculatedPadding) + _this9._scrollbarWidth + "px")
                    }),
                        $(stickyContent).each(function (index, element) {
                            var actualMargin = element.style.marginRight
                                , calculatedMargin = $(element).css("margin-right");
                            $(element).data("margin-right", actualMargin).css("margin-right", parseFloat(calculatedMargin) - _this9._scrollbarWidth + "px")
                        });
                    var actualPadding = document.body.style.paddingRight
                        , calculatedPadding = $(document.body).css("padding-right");
                    $(document.body).data("padding-right", actualPadding).css("padding-right", parseFloat(calculatedPadding) + this._scrollbarWidth + "px")
                }
                $(document.body).addClass(ClassName$5_OPEN)
            }
            ,
            _proto._resetScrollbar = function () {
                var fixedContent = [].slice.call(document.querySelectorAll(Selector$5_FIXED_CONTENT));
                $(fixedContent).each(function (index, element) {
                    var padding = $(element).data("padding-right");
                    $(element).removeData("padding-right"),
                        element.style.paddingRight = padding || ""
                });
                var elements = [].slice.call(document.querySelectorAll("" + Selector$5_STICKY_CONTENT));
                $(elements).each(function (index, element) {
                    var margin = $(element).data("margin-right");
                    void 0 !== margin && $(element).css("margin-right", margin).removeData("margin-right")
                });
                var padding = $(document.body).data("padding-right");
                $(document.body).removeData("padding-right"),
                    document.body.style.paddingRight = padding || ""
            }
            ,
            _proto._getScrollbarWidth = function () {
                var scrollDiv = document.createElement("div");
                scrollDiv.className = ClassName$5_SCROLLBAR_MEASURER,
                    document.body.appendChild(scrollDiv);
                var scrollbarWidth = scrollDiv.getBoundingClientRect().width - scrollDiv.clientWidth;
                return document.body.removeChild(scrollDiv),
                    scrollbarWidth
            }
            ,
            Modal._jQueryInterface = function (config, relatedTarget) {
                return this.each(function () {
                    var data = $(this).data("bs.modal")
                        ,
                        _config = _objectSpread({}, Default$3, $(this).data(), "object" == typeof config && config ? config : {});
                    if (data || (data = new Modal(this, _config),
                        $(this).data("bs.modal", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config](relatedTarget)
                    } else
                        _config.show && data.show(relatedTarget)
                })
            }
            ,
            _createClass(Modal, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$3
                }
            }]),
            Modal
    }();
    $(document).on(Event$5.CLICK_DATA_API, Selector$5_DATA_TOGGLE, function (event) {
        var target, _this10 = this, selector = Util.getSelectorFromElement(this);
        selector && (target = document.querySelector(selector));
        var config = $(target).data("bs.modal") ? "toggle" : _objectSpread({}, $(target).data(), $(this).data());
        "A" !== this.tagName && "AREA" !== this.tagName || event.preventDefault();
        var $target = $(target).one(Event$5.SHOW, function (showEvent) {
            showEvent.isDefaultPrevented() || $target.one(Event$5.HIDDEN, function () {
                $(_this10).is(":visible") && _this10.focus()
            })
        });
        Modal._jQueryInterface.call($(target), config, this)
    }),
        $.fn.modal = Modal._jQueryInterface,
        $.fn.modal.Constructor = Modal,
        $.fn.modal.noConflict = function () {
            return $.fn.modal = JQUERY_NO_CONFLICT$5,
                Modal._jQueryInterface
        }
    ;
    var uriAttrs = ["background", "cite", "href", "itemtype", "longdesc", "poster", "src", "xlink:href"]
        , DefaultWhitelist = {
            "*": ["class", "dir", "id", "lang", "role", /^aria-[\w-]*$/i],
            a: ["target", "href", "title", "rel"],
            area: [],
            b: [],
            br: [],
            col: [],
            code: [],
            div: [],
            em: [],
            hr: [],
            h1: [],
            h2: [],
            h3: [],
            h4: [],
            h5: [],
            h6: [],
            i: [],
            img: ["src", "alt", "title", "width", "height"],
            li: [],
            ol: [],
            p: [],
            pre: [],
            s: [],
            small: [],
            span: [],
            sub: [],
            sup: [],
            strong: [],
            u: [],
            ul: []
        }
        , SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi
        ,
        DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i;

    function sanitizeHtml(unsafeHtml, whiteList, sanitizeFn) {
        if (0 === unsafeHtml.length)
            return unsafeHtml;
        if (sanitizeFn && "function" == typeof sanitizeFn)
            return sanitizeFn(unsafeHtml);
        for (var createdDocument = (new window.DOMParser).parseFromString(unsafeHtml, "text/html"), whitelistKeys = Object.keys(whiteList), elements = [].slice.call(createdDocument.body.querySelectorAll("*")), _loop = function (i) {
            var el = elements[i]
                , elName = el.nodeName.toLowerCase();
            if (-1 === whitelistKeys.indexOf(el.nodeName.toLowerCase()))
                return el.parentNode.removeChild(el),
                    "continue";
            var attributeList = [].slice.call(el.attributes)
                , whitelistedAttributes = [].concat(whiteList["*"] || [], whiteList[elName] || []);
            attributeList.forEach(function (attr) {
                !function (attr, allowedAttributeList) {
                    var attrName = attr.nodeName.toLowerCase();
                    if (-1 !== allowedAttributeList.indexOf(attrName))
                        return -1 === uriAttrs.indexOf(attrName) || Boolean(attr.nodeValue.match(SAFE_URL_PATTERN) || attr.nodeValue.match(DATA_URL_PATTERN));
                    for (var regExp = allowedAttributeList.filter(function (attrRegex) {
                        return attrRegex instanceof RegExp
                    }), i = 0, l = regExp.length; i < l; i++)
                        if (attrName.match(regExp[i]))
                            return 1
                }(attr, whitelistedAttributes) && el.removeAttribute(attr.nodeName)
            })
        }, i = 0, len = elements.length; i < len; i++)
            _loop(i);
        return createdDocument.body.innerHTML
    }

    var NAME$6 = "tooltip"
        , JQUERY_NO_CONFLICT$6 = $.fn.tooltip
        , BSCLS_PREFIX_REGEX = new RegExp("(^|\\s)bs-tooltip\\S+", "g")
        , DISALLOWED_ATTRIBUTES = ["sanitize", "whiteList", "sanitizeFn"]
        , DefaultType$4 = {
        animation: "boolean",
        template: "string",
        title: "(string|element|function)",
        trigger: "string",
        delay: "(number|object)",
        html: "boolean",
        selector: "(string|boolean)",
        placement: "(string|function)",
        offset: "(number|string|function)",
        container: "(string|element|boolean)",
        fallbackPlacement: "(string|array)",
        boundary: "(string|element)",
        sanitize: "boolean",
        sanitizeFn: "(null|function)",
        whiteList: "object"
    }
        , AttachmentMap$1 = {
        AUTO: "auto",
        TOP: "top",
        RIGHT: "right",
        BOTTOM: "bottom",
        LEFT: "left"
    }
        , Default$4 = {
        animation: !0,
        template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        selector: !1,
        placement: "top",
        offset: 0,
        container: !1,
        fallbackPlacement: "flip",
        boundary: "scrollParent",
        sanitize: !0,
        sanitizeFn: null,
        whiteList: DefaultWhitelist
    }
        , HoverState_SHOW = "show"
        , HoverState_OUT = "out"
        , Event$6 = {
        HIDE: "hide.bs.tooltip",
        HIDDEN: "hidden.bs.tooltip",
        SHOW: "show.bs.tooltip",
        SHOWN: "shown.bs.tooltip",
        INSERTED: "inserted.bs.tooltip",
        CLICK: "click.bs.tooltip",
        FOCUSIN: "focusin.bs.tooltip",
        FOCUSOUT: "focusout.bs.tooltip",
        MOUSEENTER: "mouseenter.bs.tooltip",
        MOUSELEAVE: "mouseleave.bs.tooltip"
    }
        , ClassName$6_FADE = "fade"
        , ClassName$6_SHOW = "show"
        , Selector$6_TOOLTIP_INNER = ".tooltip-inner"
        , Selector$6_ARROW = ".arrow"
        , Trigger_HOVER = "hover"
        , Trigger_FOCUS = "focus"
        , Trigger_CLICK = "click"
        , Trigger_MANUAL = "manual"
        , Tooltip = function () {
        function Tooltip(element, config) {
            if (void 0 === Popper)
                throw new TypeError("Bootstrap's tooltips require Popper.js (https://popper.js.org/)");
            this._isEnabled = !0,
                this._timeout = 0,
                this._hoverState = "",
                this._activeTrigger = {},
                this._popper = null,
                this.element = element,
                this.config = this._getConfig(config),
                this.tip = null,
                this._setListeners()
        }

        var _proto = Tooltip.prototype;
        return _proto.enable = function () {
            this._isEnabled = !0
        }
            ,
            _proto.disable = function () {
                this._isEnabled = !1
            }
            ,
            _proto.toggleEnabled = function () {
                this._isEnabled = !this._isEnabled
            }
            ,
            _proto.toggle = function (event) {
                if (this._isEnabled)
                    if (event) {
                        var dataKey = this.constructor.DATA_KEY
                            , context = $(event.currentTarget).data(dataKey);
                        context || (context = new this.constructor(event.currentTarget, this._getDelegateConfig()),
                            $(event.currentTarget).data(dataKey, context)),
                            context._activeTrigger.click = !context._activeTrigger.click,
                            context._isWithActiveTrigger() ? context._enter(null, context) : context._leave(null, context)
                    } else {
                        if ($(this.getTipElement()).hasClass(ClassName$6_SHOW))
                            return void this._leave(null, this);
                        this._enter(null, this)
                    }
            }
            ,
            _proto.dispose = function () {
                clearTimeout(this._timeout),
                    $.removeData(this.element, this.constructor.DATA_KEY),
                    $(this.element).off(this.constructor.EVENT_KEY),
                    $(this.element).closest(".modal").off("hide.bs.modal"),
                this.tip && $(this.tip).remove(),
                    this._isEnabled = null,
                    this._timeout = null,
                    this._hoverState = null,
                (this._activeTrigger = null) !== this._popper && this._popper.destroy(),
                    this._popper = null,
                    this.element = null,
                    this.config = null,
                    this.tip = null
            }
            ,
            _proto.show = function () {
                var _this = this;
                if ("none" === $(this.element).css("display"))
                    throw new Error("Please use show on visible elements");
                var showEvent = $.Event(this.constructor.Event.SHOW);
                if (this.isWithContent() && this._isEnabled) {
                    $(this.element).trigger(showEvent);
                    var shadowRoot = Util.findShadowRoot(this.element)
                        ,
                        isInTheDom = $.contains(null !== shadowRoot ? shadowRoot : this.element.ownerDocument.documentElement, this.element);
                    if (showEvent.isDefaultPrevented() || !isInTheDom)
                        return;
                    var tip = this.getTipElement()
                        , tipId = Util.getUID(this.constructor.NAME);
                    tip.setAttribute("id", tipId),
                        this.element.setAttribute("aria-describedby", tipId),
                        this.setContent(),
                    this.config.animation && $(tip).addClass(ClassName$6_FADE);
                    var placement = "function" == typeof this.config.placement ? this.config.placement.call(this, tip, this.element) : this.config.placement
                        , attachment = this._getAttachment(placement);
                    this.addAttachmentClass(attachment);
                    var container = this._getContainer();
                    $(tip).data(this.constructor.DATA_KEY, this),
                    $.contains(this.element.ownerDocument.documentElement, this.tip) || $(tip).appendTo(container),
                        $(this.element).trigger(this.constructor.Event.INSERTED),
                        this._popper = new Popper(this.element, tip, {
                            placement: attachment,
                            modifiers: {
                                offset: this._getOffset(),
                                flip: {
                                    behavior: this.config.fallbackPlacement
                                },
                                arrow: {
                                    element: Selector$6_ARROW
                                },
                                preventOverflow: {
                                    boundariesElement: this.config.boundary
                                }
                            },
                            onCreate: function (data) {
                                data.originalPlacement !== data.placement && _this._handlePopperPlacementChange(data)
                            },
                            onUpdate: function (data) {
                                return _this._handlePopperPlacementChange(data)
                            }
                        }),
                        $(tip).addClass(ClassName$6_SHOW),
                    "ontouchstart" in document.documentElement && $(document.body).children().on("mouseover", null, $.noop);
                    var complete = function () {
                        _this.config.animation && _this._fixTransition();
                        var prevHoverState = _this._hoverState;
                        _this._hoverState = null,
                            $(_this.element).trigger(_this.constructor.Event.SHOWN),
                        prevHoverState === HoverState_OUT && _this._leave(null, _this)
                    };
                    if ($(this.tip).hasClass(ClassName$6_FADE)) {
                        var transitionDuration = Util.getTransitionDurationFromElement(this.tip);
                        $(this.tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration)
                    } else
                        complete()
                }
            }
            ,
            _proto.hide = function (callback) {
                var _this2 = this
                    , tip = this.getTipElement()
                    , hideEvent = $.Event(this.constructor.Event.HIDE)
                    , complete = function () {
                    _this2._hoverState !== HoverState_SHOW && tip.parentNode && tip.parentNode.removeChild(tip),
                        _this2._cleanTipClass(),
                        _this2.element.removeAttribute("aria-describedby"),
                        $(_this2.element).trigger(_this2.constructor.Event.HIDDEN),
                    null !== _this2._popper && _this2._popper.destroy(),
                    callback && callback()
                };
                if ($(this.element).trigger(hideEvent),
                    !hideEvent.isDefaultPrevented()) {
                    if ($(tip).removeClass(ClassName$6_SHOW),
                    "ontouchstart" in document.documentElement && $(document.body).children().off("mouseover", null, $.noop),
                        this._activeTrigger[Trigger_CLICK] = !1,
                        this._activeTrigger[Trigger_FOCUS] = !1,
                        this._activeTrigger[Trigger_HOVER] = !1,
                        $(this.tip).hasClass(ClassName$6_FADE)) {
                        var transitionDuration = Util.getTransitionDurationFromElement(tip);
                        $(tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration)
                    } else
                        complete();
                    this._hoverState = ""
                }
            }
            ,
            _proto.update = function () {
                null !== this._popper && this._popper.scheduleUpdate()
            }
            ,
            _proto.isWithContent = function () {
                return Boolean(this.getTitle())
            }
            ,
            _proto.addAttachmentClass = function (attachment) {
                $(this.getTipElement()).addClass("bs-tooltip-" + attachment)
            }
            ,
            _proto.getTipElement = function () {
                return this.tip = this.tip || $(this.config.template)[0],
                    this.tip
            }
            ,
            _proto.setContent = function () {
                var tip = this.getTipElement();
                this.setElementContent($(tip.querySelectorAll(Selector$6_TOOLTIP_INNER)), this.getTitle()),
                    $(tip).removeClass(ClassName$6_FADE + " " + ClassName$6_SHOW)
            }
            ,
            _proto.setElementContent = function ($element, content) {
                "object" != typeof content || !content.nodeType && !content.jquery ? this.config.html ? (this.config.sanitize && (content = sanitizeHtml(content, this.config.whiteList, this.config.sanitizeFn)),
                    $element.html(content)) : $element.text(content) : this.config.html ? $(content).parent().is($element) || $element.empty().append(content) : $element.text($(content).text())
            }
            ,
            _proto.getTitle = function () {
                var title = this.element.getAttribute("data-original-title");
                return title = title || ("function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title)
            }
            ,
            _proto._getOffset = function () {
                var _this3 = this
                    , offset = {};
                return "function" == typeof this.config.offset ? offset.fn = function (data) {
                        return data.offsets = _objectSpread({}, data.offsets, _this3.config.offset(data.offsets, _this3.element) || {}),
                            data
                    }
                    : offset.offset = this.config.offset,
                    offset
            }
            ,
            _proto._getContainer = function () {
                return !1 === this.config.container ? document.body : Util.isElement(this.config.container) ? $(this.config.container) : $(document).find(this.config.container)
            }
            ,
            _proto._getAttachment = function (placement) {
                return AttachmentMap$1[placement.toUpperCase()]
            }
            ,
            _proto._setListeners = function () {
                var _this4 = this;
                this.config.trigger.split(" ").forEach(function (trigger) {
                    if ("click" === trigger)
                        $(_this4.element).on(_this4.constructor.Event.CLICK, _this4.config.selector, function (event) {
                            return _this4.toggle(event)
                        });
                    else if (trigger !== Trigger_MANUAL) {
                        var eventIn = trigger === Trigger_HOVER ? _this4.constructor.Event.MOUSEENTER : _this4.constructor.Event.FOCUSIN
                            ,
                            eventOut = trigger === Trigger_HOVER ? _this4.constructor.Event.MOUSELEAVE : _this4.constructor.Event.FOCUSOUT;
                        $(_this4.element).on(eventIn, _this4.config.selector, function (event) {
                            return _this4._enter(event)
                        }).on(eventOut, _this4.config.selector, function (event) {
                            return _this4._leave(event)
                        })
                    }
                }),
                    $(this.element).closest(".modal").on("hide.bs.modal", function () {
                        _this4.element && _this4.hide()
                    }),
                    this.config.selector ? this.config = _objectSpread({}, this.config, {
                        trigger: "manual",
                        selector: ""
                    }) : this._fixTitle()
            }
            ,
            _proto._fixTitle = function () {
                var titleType = typeof this.element.getAttribute("data-original-title");
                !this.element.getAttribute("title") && "string" == titleType || (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""),
                    this.element.setAttribute("title", ""))
            }
            ,
            _proto._enter = function (event, context) {
                var dataKey = this.constructor.DATA_KEY;
                (context = context || $(event.currentTarget).data(dataKey)) || (context = new this.constructor(event.currentTarget, this._getDelegateConfig()),
                    $(event.currentTarget).data(dataKey, context)),
                event && (context._activeTrigger["focusin" === event.type ? Trigger_FOCUS : Trigger_HOVER] = !0),
                    $(context.getTipElement()).hasClass(ClassName$6_SHOW) || context._hoverState === HoverState_SHOW ? context._hoverState = HoverState_SHOW : (clearTimeout(context._timeout),
                        context._hoverState = HoverState_SHOW,
                        context.config.delay && context.config.delay.show ? context._timeout = setTimeout(function () {
                            context._hoverState === HoverState_SHOW && context.show()
                        }, context.config.delay.show) : context.show())
            }
            ,
            _proto._leave = function (event, context) {
                var dataKey = this.constructor.DATA_KEY;
                (context = context || $(event.currentTarget).data(dataKey)) || (context = new this.constructor(event.currentTarget, this._getDelegateConfig()),
                    $(event.currentTarget).data(dataKey, context)),
                event && (context._activeTrigger["focusout" === event.type ? Trigger_FOCUS : Trigger_HOVER] = !1),
                context._isWithActiveTrigger() || (clearTimeout(context._timeout),
                    context._hoverState = HoverState_OUT,
                    context.config.delay && context.config.delay.hide ? context._timeout = setTimeout(function () {
                        context._hoverState === HoverState_OUT && context.hide()
                    }, context.config.delay.hide) : context.hide())
            }
            ,
            _proto._isWithActiveTrigger = function () {
                for (var trigger in this._activeTrigger)
                    if (this._activeTrigger[trigger])
                        return !0;
                return !1
            }
            ,
            _proto._getConfig = function (config) {
                var dataAttributes = $(this.element).data();
                return Object.keys(dataAttributes).forEach(function (dataAttr) {
                    -1 !== DISALLOWED_ATTRIBUTES.indexOf(dataAttr) && delete dataAttributes[dataAttr]
                }),
                "number" == typeof (config = _objectSpread({}, this.constructor.Default, dataAttributes, "object" == typeof config && config ? config : {})).delay && (config.delay = {
                    show: config.delay,
                    hide: config.delay
                }),
                "number" == typeof config.title && (config.title = config.title.toString()),
                "number" == typeof config.content && (config.content = config.content.toString()),
                    Util.typeCheckConfig(NAME$6, config, this.constructor.DefaultType),
                config.sanitize && (config.template = sanitizeHtml(config.template, config.whiteList, config.sanitizeFn)),
                    config
            }
            ,
            _proto._getDelegateConfig = function () {
                var config = {};
                if (this.config)
                    for (var key in this.config)
                        this.constructor.Default[key] !== this.config[key] && (config[key] = this.config[key]);
                return config
            }
            ,
            _proto._cleanTipClass = function () {
                var $tip = $(this.getTipElement())
                    , tabClass = $tip.attr("class").match(BSCLS_PREFIX_REGEX);
                null !== tabClass && tabClass.length && $tip.removeClass(tabClass.join(""))
            }
            ,
            _proto._handlePopperPlacementChange = function (popperData) {
                var popperInstance = popperData.instance;
                this.tip = popperInstance.popper,
                    this._cleanTipClass(),
                    this.addAttachmentClass(this._getAttachment(popperData.placement))
            }
            ,
            _proto._fixTransition = function () {
                var tip = this.getTipElement()
                    , initConfigAnimation = this.config.animation;
                null === tip.getAttribute("x-placement") && ($(tip).removeClass(ClassName$6_FADE),
                    this.config.animation = !1,
                    this.hide(),
                    this.show(),
                    this.config.animation = initConfigAnimation)
            }
            ,
            Tooltip._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.tooltip")
                        , _config = "object" == typeof config && config;
                    if ((data || !/dispose|hide/.test(config)) && (data || (data = new Tooltip(this, _config),
                        $(this).data("bs.tooltip", data)),
                    "string" == typeof config)) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            _createClass(Tooltip, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$4
                }
            }, {
                key: "NAME",
                get: function () {
                    return NAME$6
                }
            }, {
                key: "DATA_KEY",
                get: function () {
                    return "bs.tooltip"
                }
            }, {
                key: "Event",
                get: function () {
                    return Event$6
                }
            }, {
                key: "EVENT_KEY",
                get: function () {
                    return ".bs.tooltip"
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return DefaultType$4
                }
            }]),
            Tooltip
    }();
    $.fn.tooltip = Tooltip._jQueryInterface,
        $.fn.tooltip.Constructor = Tooltip,
        $.fn.tooltip.noConflict = function () {
            return $.fn.tooltip = JQUERY_NO_CONFLICT$6,
                Tooltip._jQueryInterface
        }
    ;
    var NAME$7 = "popover"
        , JQUERY_NO_CONFLICT$7 = $.fn.popover
        , BSCLS_PREFIX_REGEX$1 = new RegExp("(^|\\s)bs-popover\\S+", "g")
        , Default$5 = _objectSpread({}, Tooltip.Default, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    })
        , DefaultType$5 = _objectSpread({}, Tooltip.DefaultType, {
        content: "(string|element|function)"
    })
        , ClassName$7_FADE = "fade"
        , ClassName$7_SHOW = "show"
        , Selector$7_TITLE = ".popover-header"
        , Selector$7_CONTENT = ".popover-body"
        , Event$7 = {
        HIDE: "hide.bs.popover",
        HIDDEN: "hidden.bs.popover",
        SHOW: "show.bs.popover",
        SHOWN: "shown.bs.popover",
        INSERTED: "inserted.bs.popover",
        CLICK: "click.bs.popover",
        FOCUSIN: "focusin.bs.popover",
        FOCUSOUT: "focusout.bs.popover",
        MOUSEENTER: "mouseenter.bs.popover",
        MOUSELEAVE: "mouseleave.bs.popover"
    }
        , Popover = function (_Tooltip) {
        var subClass, superClass;

        function Popover() {
            return _Tooltip.apply(this, arguments) || this
        }

        superClass = _Tooltip,
            (subClass = Popover).prototype = Object.create(superClass.prototype),
            (subClass.prototype.constructor = subClass).__proto__ = superClass;
        var _proto = Popover.prototype;
        return _proto.isWithContent = function () {
            return this.getTitle() || this._getContent()
        }
            ,
            _proto.addAttachmentClass = function (attachment) {
                $(this.getTipElement()).addClass("bs-popover-" + attachment)
            }
            ,
            _proto.getTipElement = function () {
                return this.tip = this.tip || $(this.config.template)[0],
                    this.tip
            }
            ,
            _proto.setContent = function () {
                var $tip = $(this.getTipElement());
                this.setElementContent($tip.find(Selector$7_TITLE), this.getTitle());
                var content = this._getContent();
                "function" == typeof content && (content = content.call(this.element)),
                    this.setElementContent($tip.find(Selector$7_CONTENT), content),
                    $tip.removeClass(ClassName$7_FADE + " " + ClassName$7_SHOW)
            }
            ,
            _proto._getContent = function () {
                return this.element.getAttribute("data-content") || this.config.content
            }
            ,
            _proto._cleanTipClass = function () {
                var $tip = $(this.getTipElement())
                    , tabClass = $tip.attr("class").match(BSCLS_PREFIX_REGEX$1);
                null !== tabClass && 0 < tabClass.length && $tip.removeClass(tabClass.join(""))
            }
            ,
            Popover._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.popover")
                        , _config = "object" == typeof config ? config : null;
                    if ((data || !/dispose|hide/.test(config)) && (data || (data = new Popover(this, _config),
                        $(this).data("bs.popover", data)),
                    "string" == typeof config)) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            _createClass(Popover, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$5
                }
            }, {
                key: "NAME",
                get: function () {
                    return NAME$7
                }
            }, {
                key: "DATA_KEY",
                get: function () {
                    return "bs.popover"
                }
            }, {
                key: "Event",
                get: function () {
                    return Event$7
                }
            }, {
                key: "EVENT_KEY",
                get: function () {
                    return ".bs.popover"
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return DefaultType$5
                }
            }]),
            Popover
    }(Tooltip);
    $.fn.popover = Popover._jQueryInterface,
        $.fn.popover.Constructor = Popover,
        $.fn.popover.noConflict = function () {
            return $.fn.popover = JQUERY_NO_CONFLICT$7,
                Popover._jQueryInterface
        }
    ;
    var NAME$8 = "scrollspy"
        , JQUERY_NO_CONFLICT$8 = $.fn[NAME$8]
        , Default$6 = {
        offset: 10,
        method: "auto",
        target: ""
    }
        , DefaultType$6 = {
        offset: "number",
        method: "string",
        target: "(string|element)"
    }
        , Event$8 = {
        ACTIVATE: "activate.bs.scrollspy",
        SCROLL: "scroll.bs.scrollspy",
        LOAD_DATA_API: "load.bs.scrollspy.data-api"
    }
        , ClassName$8_DROPDOWN_ITEM = "dropdown-item"
        , ClassName$8_ACTIVE = "active"
        , Selector$8_DATA_SPY = '[data-spy="scroll"]'
        , Selector$8_NAV_LIST_GROUP = ".nav, .list-group"
        , Selector$8_NAV_LINKS = ".nav-link"
        , Selector$8_NAV_ITEMS = ".nav-item"
        , Selector$8_LIST_ITEMS = ".list-group-item"
        , Selector$8_DROPDOWN = ".dropdown"
        , Selector$8_DROPDOWN_ITEMS = ".dropdown-item"
        , Selector$8_DROPDOWN_TOGGLE = ".dropdown-toggle"
        , OffsetMethod_OFFSET = "offset"
        , OffsetMethod_POSITION = "position"
        , ScrollSpy = function () {
        function ScrollSpy(element, config) {
            var _this = this;
            this._element = element,
                this._scrollElement = "BODY" === element.tagName ? window : element,
                this._config = this._getConfig(config),
                this._selector = this._config.target + " " + Selector$8_NAV_LINKS + "," + this._config.target + " " + Selector$8_LIST_ITEMS + "," + this._config.target + " " + Selector$8_DROPDOWN_ITEMS,
                this._offsets = [],
                this._targets = [],
                this._activeTarget = null,
                this._scrollHeight = 0,
                $(this._scrollElement).on(Event$8.SCROLL, function (event) {
                    return _this._process(event)
                }),
                this.refresh(),
                this._process()
        }

        var _proto = ScrollSpy.prototype;
        return _proto.refresh = function () {
            var _this2 = this
                ,
                autoMethod = this._scrollElement === this._scrollElement.window ? OffsetMethod_OFFSET : OffsetMethod_POSITION
                , offsetMethod = "auto" === this._config.method ? autoMethod : this._config.method
                , offsetBase = offsetMethod === OffsetMethod_POSITION ? this._getScrollTop() : 0;
            this._offsets = [],
                this._targets = [],
                this._scrollHeight = this._getScrollHeight(),
                [].slice.call(document.querySelectorAll(this._selector)).map(function (element) {
                    var target, targetSelector = Util.getSelectorFromElement(element);
                    if (targetSelector && (target = document.querySelector(targetSelector)),
                        target) {
                        var targetBCR = target.getBoundingClientRect();
                        if (targetBCR.width || targetBCR.height)
                            return [$(target)[offsetMethod]().top + offsetBase, targetSelector]
                    }
                    return null
                }).filter(function (item) {
                    return item
                }).sort(function (a, b) {
                    return a[0] - b[0]
                }).forEach(function (item) {
                    _this2._offsets.push(item[0]),
                        _this2._targets.push(item[1])
                })
        }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.scrollspy"),
                    $(this._scrollElement).off(".bs.scrollspy"),
                    this._element = null,
                    this._scrollElement = null,
                    this._config = null,
                    this._selector = null,
                    this._offsets = null,
                    this._targets = null,
                    this._activeTarget = null,
                    this._scrollHeight = null
            }
            ,
            _proto._getConfig = function (config) {
                if ("string" != typeof (config = _objectSpread({}, Default$6, "object" == typeof config && config ? config : {})).target) {
                    var id = $(config.target).attr("id");
                    id || (id = Util.getUID(NAME$8),
                        $(config.target).attr("id", id)),
                        config.target = "#" + id
                }
                return Util.typeCheckConfig(NAME$8, config, DefaultType$6),
                    config
            }
            ,
            _proto._getScrollTop = function () {
                return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop
            }
            ,
            _proto._getScrollHeight = function () {
                return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
            }
            ,
            _proto._getOffsetHeight = function () {
                return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height
            }
            ,
            _proto._process = function () {
                var scrollTop = this._getScrollTop() + this._config.offset
                    , scrollHeight = this._getScrollHeight()
                    , maxScroll = this._config.offset + scrollHeight - this._getOffsetHeight();
                if (this._scrollHeight !== scrollHeight && this.refresh(),
                maxScroll <= scrollTop) {
                    var target = this._targets[this._targets.length - 1];
                    this._activeTarget !== target && this._activate(target)
                } else {
                    if (this._activeTarget && scrollTop < this._offsets[0] && 0 < this._offsets[0])
                        return this._activeTarget = null,
                            void this._clear();
                    for (var i = this._offsets.length; i--;) {
                        this._activeTarget !== this._targets[i] && scrollTop >= this._offsets[i] && (void 0 === this._offsets[i + 1] || scrollTop < this._offsets[i + 1]) && this._activate(this._targets[i])
                    }
                }
            }
            ,
            _proto._activate = function (target) {
                this._activeTarget = target,
                    this._clear();
                var queries = this._selector.split(",").map(function (selector) {
                    return selector + '[data-target="' + target + '"],' + selector + '[href="' + target + '"]'
                })
                    , $link = $([].slice.call(document.querySelectorAll(queries.join(","))));
                $link.hasClass(ClassName$8_DROPDOWN_ITEM) ? ($link.closest(Selector$8_DROPDOWN).find(Selector$8_DROPDOWN_TOGGLE).addClass(ClassName$8_ACTIVE),
                    $link.addClass(ClassName$8_ACTIVE)) : ($link.addClass(ClassName$8_ACTIVE),
                    $link.parents(Selector$8_NAV_LIST_GROUP).prev(Selector$8_NAV_LINKS + ", " + Selector$8_LIST_ITEMS).addClass(ClassName$8_ACTIVE),
                    $link.parents(Selector$8_NAV_LIST_GROUP).prev(Selector$8_NAV_ITEMS).children(Selector$8_NAV_LINKS).addClass(ClassName$8_ACTIVE)),
                    $(this._scrollElement).trigger(Event$8.ACTIVATE, {
                        relatedTarget: target
                    })
            }
            ,
            _proto._clear = function () {
                [].slice.call(document.querySelectorAll(this._selector)).filter(function (node) {
                    return node.classList.contains(ClassName$8_ACTIVE)
                }).forEach(function (node) {
                    return node.classList.remove(ClassName$8_ACTIVE)
                })
            }
            ,
            ScrollSpy._jQueryInterface = function (config) {
                return this.each(function () {
                    var data = $(this).data("bs.scrollspy");
                    if (data || (data = new ScrollSpy(this, "object" == typeof config && config),
                        $(this).data("bs.scrollspy", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            _createClass(ScrollSpy, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$6
                }
            }]),
            ScrollSpy
    }();
    $(window).on(Event$8.LOAD_DATA_API, function () {
        for (var scrollSpys = [].slice.call(document.querySelectorAll(Selector$8_DATA_SPY)), i = scrollSpys.length; i--;) {
            var $spy = $(scrollSpys[i]);
            ScrollSpy._jQueryInterface.call($spy, $spy.data())
        }
    }),
        $.fn[NAME$8] = ScrollSpy._jQueryInterface,
        $.fn[NAME$8].Constructor = ScrollSpy,
        $.fn[NAME$8].noConflict = function () {
            return $.fn[NAME$8] = JQUERY_NO_CONFLICT$8,
                ScrollSpy._jQueryInterface
        }
    ;
    var JQUERY_NO_CONFLICT$9 = $.fn.tab
        , Event$9 = {
        HIDE: "hide.bs.tab",
        HIDDEN: "hidden.bs.tab",
        SHOW: "show.bs.tab",
        SHOWN: "shown.bs.tab",
        CLICK_DATA_API: "click.bs.tab.data-api"
    }
        , ClassName$9_DROPDOWN_MENU = "dropdown-menu"
        , ClassName$9_ACTIVE = "active"
        , ClassName$9_DISABLED = "disabled"
        , ClassName$9_FADE = "fade"
        , ClassName$9_SHOW = "show"
        , Selector$9_DROPDOWN = ".dropdown"
        , Selector$9_NAV_LIST_GROUP = ".nav, .list-group"
        , Selector$9_ACTIVE = ".active"
        , Selector$9_ACTIVE_UL = "> li > .active"
        , Selector$9_DATA_TOGGLE = '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]'
        , Selector$9_DROPDOWN_TOGGLE = ".dropdown-toggle"
        , Selector$9_DROPDOWN_ACTIVE_CHILD = "> .dropdown-menu .active"
        , Tab = function () {
        function Tab(element) {
            this._element = element
        }

        var _proto = Tab.prototype;
        return _proto.show = function () {
            var _this = this;
            if (!(this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && $(this._element).hasClass(ClassName$9_ACTIVE) || $(this._element).hasClass(ClassName$9_DISABLED))) {
                var target, previous, listElement = $(this._element).closest(Selector$9_NAV_LIST_GROUP)[0],
                    selector = Util.getSelectorFromElement(this._element);
                if (listElement) {
                    var itemSelector = "UL" === listElement.nodeName || "OL" === listElement.nodeName ? Selector$9_ACTIVE_UL : Selector$9_ACTIVE;
                    previous = (previous = $.makeArray($(listElement).find(itemSelector)))[previous.length - 1]
                }
                var hideEvent = $.Event(Event$9.HIDE, {
                    relatedTarget: this._element
                })
                    , showEvent = $.Event(Event$9.SHOW, {
                    relatedTarget: previous
                });
                if (previous && $(previous).trigger(hideEvent),
                    $(this._element).trigger(showEvent),
                !showEvent.isDefaultPrevented() && !hideEvent.isDefaultPrevented()) {
                    selector && (target = document.querySelector(selector)),
                        this._activate(this._element, listElement);
                    var complete = function () {
                        var hiddenEvent = $.Event(Event$9.HIDDEN, {
                            relatedTarget: _this._element
                        })
                            , shownEvent = $.Event(Event$9.SHOWN, {
                            relatedTarget: previous
                        });
                        $(previous).trigger(hiddenEvent),
                            $(_this._element).trigger(shownEvent)
                    };
                    target ? this._activate(target, target.parentNode, complete) : complete()
                }
            }
        }
            ,
            _proto.dispose = function () {
                $.removeData(this._element, "bs.tab"),
                    this._element = null
            }
            ,
            _proto._activate = function (element, container, callback) {
                var _this2 = this
                    ,
                    active = (!container || "UL" !== container.nodeName && "OL" !== container.nodeName ? $(container).children(Selector$9_ACTIVE) : $(container).find(Selector$9_ACTIVE_UL))[0]
                    , isTransitioning = callback && active && $(active).hasClass(ClassName$9_FADE)
                    , complete = function () {
                        return _this2._transitionComplete(element, active, callback)
                    };
                if (active && isTransitioning) {
                    var transitionDuration = Util.getTransitionDurationFromElement(active);
                    $(active).removeClass(ClassName$9_SHOW).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration)
                } else
                    complete()
            }
            ,
            _proto._transitionComplete = function (element, active, callback) {
                if (active) {
                    $(active).removeClass(ClassName$9_ACTIVE);
                    var dropdownChild = $(active.parentNode).find(Selector$9_DROPDOWN_ACTIVE_CHILD)[0];
                    dropdownChild && $(dropdownChild).removeClass(ClassName$9_ACTIVE),
                    "tab" === active.getAttribute("role") && active.setAttribute("aria-selected", !1)
                }
                if ($(element).addClass(ClassName$9_ACTIVE),
                "tab" === element.getAttribute("role") && element.setAttribute("aria-selected", !0),
                    Util.reflow(element),
                element.classList.contains(ClassName$9_FADE) && element.classList.add(ClassName$9_SHOW),
                element.parentNode && $(element.parentNode).hasClass(ClassName$9_DROPDOWN_MENU)) {
                    var dropdownElement = $(element).closest(Selector$9_DROPDOWN)[0];
                    if (dropdownElement) {
                        var dropdownToggleList = [].slice.call(dropdownElement.querySelectorAll(Selector$9_DROPDOWN_TOGGLE));
                        $(dropdownToggleList).addClass(ClassName$9_ACTIVE)
                    }
                    element.setAttribute("aria-expanded", !0)
                }
                callback && callback()
            }
            ,
            Tab._jQueryInterface = function (config) {
                return this.each(function () {
                    var $this = $(this)
                        , data = $this.data("bs.tab");
                    if (data || (data = new Tab(this),
                        $this.data("bs.tab", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config]()
                    }
                })
            }
            ,
            _createClass(Tab, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }]),
            Tab
    }();
    $(document).on(Event$9.CLICK_DATA_API, Selector$9_DATA_TOGGLE, function (event) {
        event.preventDefault(),
            Tab._jQueryInterface.call($(this), "show")
    }),
        $.fn.tab = Tab._jQueryInterface,
        $.fn.tab.Constructor = Tab,
        $.fn.tab.noConflict = function () {
            return $.fn.tab = JQUERY_NO_CONFLICT$9,
                Tab._jQueryInterface
        }
    ;
    var JQUERY_NO_CONFLICT$a = $.fn.toast
        , Event$a = {
        CLICK_DISMISS: "click.dismiss.bs.toast",
        HIDE: "hide.bs.toast",
        HIDDEN: "hidden.bs.toast",
        SHOW: "show.bs.toast",
        SHOWN: "shown.bs.toast"
    }
        , ClassName$a_FADE = "fade"
        , ClassName$a_HIDE = "hide"
        , ClassName$a_SHOW = "show"
        , ClassName$a_SHOWING = "showing"
        , DefaultType$7 = {
        animation: "boolean",
        autohide: "boolean",
        delay: "number"
    }
        , Default$7 = {
        animation: !0,
        autohide: !0,
        delay: 500
    }
        , Selector$a_DATA_DISMISS = '[data-dismiss="toast"]'
        , Toast = function () {
        function Toast(element, config) {
            this._element = element,
                this._config = this._getConfig(config),
                this._timeout = null,
                this._setListeners()
        }

        var _proto = Toast.prototype;
        return _proto.show = function () {
            var _this = this;
            $(this._element).trigger(Event$a.SHOW),
            this._config.animation && this._element.classList.add(ClassName$a_FADE);
            var complete = function () {
                _this._element.classList.remove(ClassName$a_SHOWING),
                    _this._element.classList.add(ClassName$a_SHOW),
                    $(_this._element).trigger(Event$a.SHOWN),
                _this._config.autohide && _this.hide()
            };
            if (this._element.classList.remove(ClassName$a_HIDE),
                this._element.classList.add(ClassName$a_SHOWING),
                this._config.animation) {
                var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration)
            } else
                complete()
        }
            ,
            _proto.hide = function (withoutTimeout) {
                var _this2 = this;
                this._element.classList.contains(ClassName$a_SHOW) && ($(this._element).trigger(Event$a.HIDE),
                    withoutTimeout ? this._close() : this._timeout = setTimeout(function () {
                        _this2._close()
                    }, this._config.delay))
            }
            ,
            _proto.dispose = function () {
                clearTimeout(this._timeout),
                    this._timeout = null,
                this._element.classList.contains(ClassName$a_SHOW) && this._element.classList.remove(ClassName$a_SHOW),
                    $(this._element).off(Event$a.CLICK_DISMISS),
                    $.removeData(this._element, "bs.toast"),
                    this._element = null,
                    this._config = null
            }
            ,
            _proto._getConfig = function (config) {
                return config = _objectSpread({}, Default$7, $(this._element).data(), "object" == typeof config && config ? config : {}),
                    Util.typeCheckConfig("toast", config, this.constructor.DefaultType),
                    config
            }
            ,
            _proto._setListeners = function () {
                var _this3 = this;
                $(this._element).on(Event$a.CLICK_DISMISS, Selector$a_DATA_DISMISS, function () {
                    return _this3.hide(!0)
                })
            }
            ,
            _proto._close = function () {
                var _this4 = this
                    , complete = function () {
                    _this4._element.classList.add(ClassName$a_HIDE),
                        $(_this4._element).trigger(Event$a.HIDDEN)
                };
                if (this._element.classList.remove(ClassName$a_SHOW),
                    this._config.animation) {
                    var transitionDuration = Util.getTransitionDurationFromElement(this._element);
                    $(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration)
                } else
                    complete()
            }
            ,
            Toast._jQueryInterface = function (config) {
                return this.each(function () {
                    var $element = $(this)
                        , data = $element.data("bs.toast");
                    if (data || (data = new Toast(this, "object" == typeof config && config),
                        $element.data("bs.toast", data)),
                    "string" == typeof config) {
                        if (void 0 === data[config])
                            throw new TypeError('No method named "' + config + '"');
                        data[config](this)
                    }
                })
            }
            ,
            _createClass(Toast, null, [{
                key: "VERSION",
                get: function () {
                    return "4.3.1"
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return DefaultType$7
                }
            }, {
                key: "Default",
                get: function () {
                    return Default$7
                }
            }]),
            Toast
    }();
    $.fn.toast = Toast._jQueryInterface,
        $.fn.toast.Constructor = Toast,
        $.fn.toast.noConflict = function () {
            return $.fn.toast = JQUERY_NO_CONFLICT$a,
                Toast._jQueryInterface
        }
        ,
        function () {
            if (void 0 === $)
                throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");
            var version = $.fn.jquery.split(" ")[0].split(".");
            if (version[0] < 2 && version[1] < 9 || 1 === version[0] && 9 === version[1] && version[2] < 1 || 4 <= version[0])
                throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")
        }(),
        exports.Util = Util,
        exports.Alert = Alert,
        exports.Button = Button,
        exports.Carousel = Carousel,
        exports.Collapse = Collapse,
        exports.Dropdown = Dropdown,
        exports.Modal = Modal,
        exports.Popover = Popover,
        exports.Scrollspy = ScrollSpy,
        exports.Tab = Tab,
        exports.Toast = Toast,
        exports.Tooltip = Tooltip,
        Object.defineProperty(exports, "__esModule", {
            value: !0
        })
}),



    jQuery(document).ready(function () {
        var touchStartCoords, touchingCarousel = !1;
        0 !== jQuery(".carousel").length && (document.addEventListener("touchstart", function (e) {
            e.target.closest(".carousel-cell") ? (touchingCarousel = !0,
                touchStartCoords = {
                    x: e.touches[0].pageX,
                    y: e.touches[0].pageY
                }) : touchingCarousel = !1
        }),
            document.addEventListener("touchmove", function (e) {
                if (touchingCarousel && e.cancelable) {
                    var moveVector = {
                        x: e.touches[0].pageX - touchStartCoords.x,
                        y: e.touches[0].pageY - touchStartCoords.y
                    };
                    10 < Math.abs(moveVector.x) && e.preventDefault()
                }
            }, {
                passive: !1
            }))
    }),
Element.prototype.matches || (Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector),
Element.prototype.closest || (Element.prototype.closest = function (s) {
        var el = this;
        do {
            if (el.matches(s))
                return el;
            el = el.parentElement || el.parentNode
        } while (null !== el && 1 === el.nodeType);
        return null
    }
),
    function (a) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], a) : "undefined" != typeof exports ? module.exports = a(require("jquery")) : a(jQuery)
    }(function (a) {
        "use strict";
        var b = window.Slick || {};
        (b = function () {
            var b = 0;
            return function (c, d) {
                var f, e = this;
                e.defaults = {
                    accessibility: !0,
                    adaptiveHeight: !1,
                    appendArrows: a(c),
                    appendDots: a(c),
                    arrows: !0,
                    asNavFor: null,
                    prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button">Previous</button>',
                    nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button">Next</button>',
                    autoplay: !1,
                    autoplaySpeed: 3e3,
                    centerMode: !1,
                    centerPadding: "50px",
                    cssEase: "ease",
                    customPaging: function (b, c) {
                        return a('<button type="button" data-role="none" role="button" tabindex="0" />').text(c + 1)
                    },
                    dots: !1,
                    dotsClass: "slick-dots",
                    draggable: !0,
                    easing: "linear",
                    edgeFriction: .35,
                    fade: !1,
                    focusOnSelect: !1,
                    infinite: !0,
                    initialSlide: 0,
                    lazyLoad: "ondemand",
                    mobileFirst: !1,
                    pauseOnHover: !0,
                    pauseOnFocus: !0,
                    pauseOnDotsHover: !1,
                    respondTo: "window",
                    responsive: null,
                    rows: 1,
                    rtl: !1,
                    slide: "",
                    slidesPerRow: 1,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    speed: 500,
                    swipe: !0,
                    swipeToSlide: !1,
                    touchMove: !0,
                    touchThreshold: 5,
                    useCSS: !0,
                    useTransform: !0,
                    variableWidth: !1,
                    vertical: !1,
                    verticalSwiping: !1,
                    waitForAnimate: !0,
                    zIndex: 1e3
                },
                    e.initials = {
                        animating: !1,
                        dragging: !1,
                        autoPlayTimer: null,
                        currentDirection: 0,
                        currentLeft: null,
                        currentSlide: 0,
                        direction: 1,
                        $dots: null,
                        listWidth: null,
                        listHeight: null,
                        loadIndex: 0,
                        $nextArrow: null,
                        $prevArrow: null,
                        slideCount: null,
                        slideWidth: null,
                        $slideTrack: null,
                        $slides: null,
                        sliding: !1,
                        slideOffset: 0,
                        swipeLeft: null,
                        $list: null,
                        touchObject: {},
                        transformsEnabled: !1,
                        unslicked: !1
                    },
                    a.extend(e, e.initials),
                    e.activeBreakpoint = null,
                    e.animType = null,
                    e.animProp = null,
                    e.breakpoints = [],
                    e.breakpointSettings = [],
                    e.cssTransitions = !1,
                    e.focussed = !1,
                    e.interrupted = !1,
                    e.hidden = "hidden",
                    e.paused = !0,
                    e.positionProp = null,
                    e.respondTo = null,
                    e.rowCount = 1,
                    e.shouldClick = !0,
                    e.$slider = a(c),
                    e.$slidesCache = null,
                    e.transformType = null,
                    e.transitionType = null,
                    e.visibilityChange = "visibilitychange",
                    e.windowWidth = 0,
                    e.windowTimer = null,
                    f = a(c).data("slick") || {},
                    e.options = a.extend({}, e.defaults, d, f),
                    e.currentSlide = e.options.initialSlide,
                    e.originalSettings = e.options,
                    void 0 !== document.mozHidden ? (e.hidden = "mozHidden",
                        e.visibilityChange = "mozvisibilitychange") : void 0 !== document.webkitHidden && (e.hidden = "webkitHidden",
                        e.visibilityChange = "webkitvisibilitychange"),
                    e.autoPlay = a.proxy(e.autoPlay, e),
                    e.autoPlayClear = a.proxy(e.autoPlayClear, e),
                    e.autoPlayIterator = a.proxy(e.autoPlayIterator, e),
                    e.changeSlide = a.proxy(e.changeSlide, e),
                    e.clickHandler = a.proxy(e.clickHandler, e),
                    e.selectHandler = a.proxy(e.selectHandler, e),
                    e.setPosition = a.proxy(e.setPosition, e),
                    e.swipeHandler = a.proxy(e.swipeHandler, e),
                    e.dragHandler = a.proxy(e.dragHandler, e),
                    e.keyHandler = a.proxy(e.keyHandler, e),
                    e.instanceUid = b++,
                    e.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/,
                    e.registerBreakpoints(),
                    e.init(!0)
            }
        }()).prototype.activateADA = function () {
            this.$slideTrack.find(".slick-active").attr({
                "aria-hidden": "false"
            }).find("a, input, button, select").attr({
                tabindex: "0"
            })
        }
            ,
            b.prototype.addSlide = b.prototype.slickAdd = function (b, c, d) {
                var e = this;
                if ("boolean" == typeof c)
                    d = c,
                        c = null;
                else if (c < 0 || c >= e.slideCount)
                    return !1;
                e.unload(),
                    "number" == typeof c ? 0 === c && 0 === e.$slides.length ? a(b).appendTo(e.$slideTrack) : d ? a(b).insertBefore(e.$slides.eq(c)) : a(b).insertAfter(e.$slides.eq(c)) : !0 === d ? a(b).prependTo(e.$slideTrack) : a(b).appendTo(e.$slideTrack),
                    e.$slides = e.$slideTrack.children(this.options.slide),
                    e.$slideTrack.children(this.options.slide).detach(),
                    e.$slideTrack.append(e.$slides),
                    e.$slides.each(function (b, c) {
                        a(c).attr("data-slick-index", b)
                    }),
                    e.$slidesCache = e.$slides,
                    e.reinit()
            }
            ,
            b.prototype.animateHeight = function () {
                var a = this;
                if (1 === a.options.slidesToShow && !0 === a.options.adaptiveHeight && !1 === a.options.vertical) {
                    var b = a.$slides.eq(a.currentSlide).outerHeight(!0);
                    a.$list.animate({
                        height: b
                    }, a.options.speed)
                }
            }
            ,
            b.prototype.animateSlide = function (b, c) {
                var d = {}
                    , e = this;
                e.animateHeight(),
                !0 === e.options.rtl && !1 === e.options.vertical && (b = -b),
                    !1 === e.transformsEnabled ? !1 === e.options.vertical ? e.$slideTrack.animate({
                        left: b
                    }, e.options.speed, e.options.easing, c) : e.$slideTrack.animate({
                        top: b
                    }, e.options.speed, e.options.easing, c) : !1 === e.cssTransitions ? (!0 === e.options.rtl && (e.currentLeft = -e.currentLeft),
                        a({
                            animStart: e.currentLeft
                        }).animate({
                            animStart: b
                        }, {
                            duration: e.options.speed,
                            easing: e.options.easing,
                            step: function (a) {
                                a = Math.ceil(a),
                                    !1 === e.options.vertical ? d[e.animType] = "translate(" + a + "px, 0px)" : d[e.animType] = "translate(0px," + a + "px)",
                                    e.$slideTrack.css(d)
                            },
                            complete: function () {
                                c && c.call()
                            }
                        })) : (e.applyTransition(),
                        b = Math.ceil(b),
                        !1 === e.options.vertical ? d[e.animType] = "translate3d(" + b + "px, 0px, 0px)" : d[e.animType] = "translate3d(0px," + b + "px, 0px)",
                        e.$slideTrack.css(d),
                    c && setTimeout(function () {
                        e.disableTransition(),
                            c.call()
                    }, e.options.speed))
            }
            ,
            b.prototype.getNavTarget = function () {
                var c = this.options.asNavFor;
                return c && null !== c && (c = a(c).not(this.$slider)),
                    c
            }
            ,
            b.prototype.asNavFor = function (b) {
                var d = this.getNavTarget();
                null !== d && "object" == typeof d && d.each(function () {
                    var c = a(this).slick("getSlick");
                    c.unslicked || c.slideHandler(b, !0)
                })
            }
            ,
            b.prototype.applyTransition = function (a) {
                var b = this
                    , c = {};
                !1 === b.options.fade ? c[b.transitionType] = b.transformType + " " + b.options.speed + "ms " + b.options.cssEase : c[b.transitionType] = "opacity " + b.options.speed + "ms " + b.options.cssEase,
                    !1 === b.options.fade ? b.$slideTrack.css(c) : b.$slides.eq(a).css(c)
            }
            ,
            b.prototype.autoPlay = function () {
                var a = this;
                a.autoPlayClear(),
                a.slideCount > a.options.slidesToShow && (a.autoPlayTimer = setInterval(a.autoPlayIterator, a.options.autoplaySpeed))
            }
            ,
            b.prototype.autoPlayClear = function () {
                this.autoPlayTimer && clearInterval(this.autoPlayTimer)
            }
            ,
            b.prototype.autoPlayIterator = function () {
                var a = this
                    , b = a.currentSlide + a.options.slidesToScroll;
                a.paused || a.interrupted || a.focussed || (!1 === a.options.infinite && (1 === a.direction && a.currentSlide + 1 === a.slideCount - 1 ? a.direction = 0 : 0 === a.direction && (b = a.currentSlide - a.options.slidesToScroll,
                a.currentSlide - 1 == 0 && (a.direction = 1))),
                    a.slideHandler(b))
            }
            ,
            b.prototype.buildArrows = function () {
                var b = this;
                !0 === b.options.arrows && (b.$prevArrow = a(b.options.prevArrow).addClass("slick-arrow"),
                    b.$nextArrow = a(b.options.nextArrow).addClass("slick-arrow"),
                    b.slideCount > b.options.slidesToShow ? (b.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),
                        b.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),
                    b.htmlExpr.test(b.options.prevArrow) && b.$prevArrow.prependTo(b.options.appendArrows),
                    b.htmlExpr.test(b.options.nextArrow) && b.$nextArrow.appendTo(b.options.appendArrows),
                    !0 !== b.options.infinite && b.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true")) : b.$prevArrow.add(b.$nextArrow).addClass("slick-hidden").attr({
                        "aria-disabled": "true",
                        tabindex: "-1"
                    }))
            }
            ,
            b.prototype.buildDots = function () {
                var c, d, b = this;
                if (!0 === b.options.dots && b.slideCount > b.options.slidesToShow) {
                    for (b.$slider.addClass("slick-dotted"),
                             d = a("<ul />").addClass(b.options.dotsClass),
                             c = 0; c <= b.getDotCount(); c += 1)
                        d.append(a("<li />").append(b.options.customPaging.call(this, b, c)));
                    b.$dots = d.appendTo(b.options.appendDots),
                        b.$dots.find("li").first().addClass("slick-active").attr("aria-hidden", "false")
                }
            }
            ,
            b.prototype.buildOut = function () {
                var b = this;
                b.$slides = b.$slider.children(b.options.slide + ":not(.slick-cloned)").addClass("slick-slide"),
                    b.slideCount = b.$slides.length,
                    b.$slides.each(function (b, c) {
                        a(c).attr("data-slick-index", b).data("originalStyling", a(c).attr("style") || "")
                    }),
                    b.$slider.addClass("slick-slider"),
                    b.$slideTrack = 0 === b.slideCount ? a('<div class="slick-track"/>').appendTo(b.$slider) : b.$slides.wrapAll('<div class="slick-track"/>').parent(),
                    b.$list = b.$slideTrack.wrap('<div aria-live="polite" class="slick-list"/>').parent(),
                    b.$slideTrack.css("opacity", 0),
                !0 !== b.options.centerMode && !0 !== b.options.swipeToSlide || (b.options.slidesToScroll = 1),
                    a("img[data-lazy]", b.$slider).not("[src]").addClass("slick-loading"),
                    b.setupInfinite(),
                    b.buildArrows(),
                    b.buildDots(),
                    b.updateDots(),
                    b.setSlideClasses("number" == typeof b.currentSlide ? b.currentSlide : 0),
                !0 === b.options.draggable && b.$list.addClass("draggable")
            }
            ,
            b.prototype.buildRows = function () {
                var b, c, d, e, f, g, h, a = this;
                if (e = document.createDocumentFragment(),
                    g = a.$slider.children(),
                1 < a.options.rows) {
                    for (h = a.options.slidesPerRow * a.options.rows,
                             f = Math.ceil(g.length / h),
                             b = 0; b < f; b++) {
                        var i = document.createElement("div");
                        for (c = 0; c < a.options.rows; c++) {
                            var j = document.createElement("div");
                            for (d = 0; d < a.options.slidesPerRow; d++) {
                                var k = b * h + (c * a.options.slidesPerRow + d);
                                g.get(k) && j.appendChild(g.get(k))
                            }
                            i.appendChild(j)
                        }
                        e.appendChild(i)
                    }
                    a.$slider.empty().append(e),
                        a.$slider.children().children().children().css({
                            width: 100 / a.options.slidesPerRow + "%",
                            display: "inline-block"
                        })
                }
            }
            ,
            b.prototype.checkResponsive = function (b, c) {
                var e, f, g, d = this, h = !1, i = d.$slider.width(), j = window.innerWidth || a(window).width();
                if ("window" === d.respondTo ? g = j : "slider" === d.respondTo ? g = i : "min" === d.respondTo && (g = Math.min(j, i)),
                d.options.responsive && d.options.responsive.length && null !== d.options.responsive) {
                    for (e in f = null,
                        d.breakpoints)
                        d.breakpoints.hasOwnProperty(e) && (!1 === d.originalSettings.mobileFirst ? g < d.breakpoints[e] && (f = d.breakpoints[e]) : g > d.breakpoints[e] && (f = d.breakpoints[e]));
                    null !== f ? null !== d.activeBreakpoint && f === d.activeBreakpoint && !c || (d.activeBreakpoint = f,
                        "unslick" === d.breakpointSettings[f] ? d.unslick(f) : (d.options = a.extend({}, d.originalSettings, d.breakpointSettings[f]),
                        !0 === b && (d.currentSlide = d.options.initialSlide),
                            d.refresh(b)),
                        h = f) : null !== d.activeBreakpoint && (d.activeBreakpoint = null,
                        d.options = d.originalSettings,
                    !0 === b && (d.currentSlide = d.options.initialSlide),
                        d.refresh(b),
                        h = f),
                    b || !1 === h || d.$slider.trigger("breakpoint", [d, h])
                }
            }
            ,
            b.prototype.changeSlide = function (b, c) {
                var f, g, d = this, e = a(b.currentTarget);
                switch (e.is("a") && b.preventDefault(),
                e.is("li") || (e = e.closest("li")),
                    f = d.slideCount % d.options.slidesToScroll != 0 ? 0 : (d.slideCount - d.currentSlide) % d.options.slidesToScroll,
                    b.data.message) {
                    case "previous":
                        g = 0 == f ? d.options.slidesToScroll : d.options.slidesToShow - f,
                        d.slideCount > d.options.slidesToShow && d.slideHandler(d.currentSlide - g, !1, c);
                        break;
                    case "next":
                        g = 0 == f ? d.options.slidesToScroll : f,
                        d.slideCount > d.options.slidesToShow && d.slideHandler(d.currentSlide + g, !1, c);
                        break;
                    case "index":
                        var i = 0 === b.data.index ? 0 : b.data.index || e.index() * d.options.slidesToScroll;
                        d.slideHandler(d.checkNavigable(i), !1, c),
                            e.children().trigger("focus");
                        break;
                    default:
                        return
                }
            }
            ,
            b.prototype.checkNavigable = function (a) {
                var c, d;
                if (d = 0,
                a > (c = this.getNavigableIndexes())[c.length - 1])
                    a = c[c.length - 1];
                else
                    for (var e in c) {
                        if (a < c[e]) {
                            a = d;
                            break
                        }
                        d = c[e]
                    }
                return a
            }
            ,
            b.prototype.cleanUpEvents = function () {
                var b = this;
                b.options.dots && null !== b.$dots && a("li", b.$dots).off("click.slick", b.changeSlide).off("mouseenter.slick", a.proxy(b.interrupt, b, !0)).off("mouseleave.slick", a.proxy(b.interrupt, b, !1)),
                    b.$slider.off("focus.slick blur.slick"),
                !0 === b.options.arrows && b.slideCount > b.options.slidesToShow && (b.$prevArrow && b.$prevArrow.off("click.slick", b.changeSlide),
                b.$nextArrow && b.$nextArrow.off("click.slick", b.changeSlide)),
                    b.$list.off("touchstart.slick mousedown.slick", b.swipeHandler),
                    b.$list.off("touchmove.slick mousemove.slick", b.swipeHandler),
                    b.$list.off("touchend.slick mouseup.slick", b.swipeHandler),
                    b.$list.off("touchcancel.slick mouseleave.slick", b.swipeHandler),
                    b.$list.off("click.slick", b.clickHandler),
                    a(document).off(b.visibilityChange, b.visibility),
                    b.cleanUpSlideEvents(),
                !0 === b.options.accessibility && b.$list.off("keydown.slick", b.keyHandler),
                !0 === b.options.focusOnSelect && a(b.$slideTrack).children().off("click.slick", b.selectHandler),
                    a(window).off("orientationchange.slick.slick-" + b.instanceUid, b.orientationChange),
                    a(window).off("resize.slick.slick-" + b.instanceUid, b.resize),
                    a("[draggable!=true]", b.$slideTrack).off("dragstart", b.preventDefault),
                    a(window).off("load.slick.slick-" + b.instanceUid, b.setPosition),
                    a(document).off("ready.slick.slick-" + b.instanceUid, b.setPosition)
            }
            ,
            b.prototype.cleanUpSlideEvents = function () {
                var b = this;
                b.$list.off("mouseenter.slick", a.proxy(b.interrupt, b, !0)),
                    b.$list.off("mouseleave.slick", a.proxy(b.interrupt, b, !1))
            }
            ,
            b.prototype.cleanUpRows = function () {
                var b, a = this;
                1 < a.options.rows && ((b = a.$slides.children().children()).removeAttr("style"),
                    a.$slider.empty().append(b))
            }
            ,
            b.prototype.clickHandler = function (a) {
                !1 === this.shouldClick && (a.stopImmediatePropagation(),
                    a.stopPropagation(),
                    a.preventDefault())
            }
            ,
            b.prototype.destroy = function (b) {
                var c = this;
                c.autoPlayClear(),
                    c.touchObject = {},
                    c.cleanUpEvents(),
                    a(".slick-cloned", c.$slider).detach(),
                c.$dots && c.$dots.remove(),
                c.$prevArrow && c.$prevArrow.length && (c.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""),
                c.htmlExpr.test(c.options.prevArrow) && c.$prevArrow.remove()),
                c.$nextArrow && c.$nextArrow.length && (c.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""),
                c.htmlExpr.test(c.options.nextArrow) && c.$nextArrow.remove()),
                c.$slides && (c.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function () {
                    a(this).attr("style", a(this).data("originalStyling"))
                }),
                    c.$slideTrack.children(this.options.slide).detach(),
                    c.$slideTrack.detach(),
                    c.$list.detach(),
                    c.$slider.append(c.$slides)),
                    c.cleanUpRows(),
                    c.$slider.removeClass("slick-slider"),
                    c.$slider.removeClass("slick-initialized"),
                    c.$slider.removeClass("slick-dotted"),
                    c.unslicked = !0,
                b || c.$slider.trigger("destroy", [c])
            }
            ,
            b.prototype.disableTransition = function (a) {
                var b = this
                    , c = {};
                c[b.transitionType] = "",
                    !1 === b.options.fade ? b.$slideTrack.css(c) : b.$slides.eq(a).css(c)
            }
            ,
            b.prototype.fadeSlide = function (a, b) {
                var c = this;
                !1 === c.cssTransitions ? (c.$slides.eq(a).css({
                    zIndex: c.options.zIndex
                }),
                    c.$slides.eq(a).animate({
                        opacity: 1
                    }, c.options.speed, c.options.easing, b)) : (c.applyTransition(a),
                    c.$slides.eq(a).css({
                        opacity: 1,
                        zIndex: c.options.zIndex
                    }),
                b && setTimeout(function () {
                    c.disableTransition(a),
                        b.call()
                }, c.options.speed))
            }
            ,
            b.prototype.fadeSlideOut = function (a) {
                var b = this;
                !1 === b.cssTransitions ? b.$slides.eq(a).animate({
                    opacity: 0,
                    zIndex: b.options.zIndex - 2
                }, b.options.speed, b.options.easing) : (b.applyTransition(a),
                    b.$slides.eq(a).css({
                        opacity: 0,
                        zIndex: b.options.zIndex - 2
                    }))
            }
            ,
            b.prototype.filterSlides = b.prototype.slickFilter = function (a) {
                var b = this;
                null !== a && (b.$slidesCache = b.$slides,
                    b.unload(),
                    b.$slideTrack.children(this.options.slide).detach(),
                    b.$slidesCache.filter(a).appendTo(b.$slideTrack),
                    b.reinit())
            }
            ,
            b.prototype.focusHandler = function () {
                var b = this;
                b.$slider.off("focus.slick blur.slick").on("focus.slick blur.slick", "*:not(.slick-arrow)", function (c) {
                    c.stopImmediatePropagation();
                    var d = a(this);
                    setTimeout(function () {
                        b.options.pauseOnFocus && (b.focussed = d.is(":focus"),
                            b.autoPlay())
                    }, 0)
                })
            }
            ,
            b.prototype.getCurrent = b.prototype.slickCurrentSlide = function () {
                return this.currentSlide
            }
            ,
            b.prototype.getDotCount = function () {
                var a = this
                    , b = 0
                    , c = 0
                    , d = 0;
                if (!0 === a.options.infinite)
                    for (; b < a.slideCount;)
                        ++d,
                            b = c + a.options.slidesToScroll,
                            c += a.options.slidesToScroll <= a.options.slidesToShow ? a.options.slidesToScroll : a.options.slidesToShow;
                else if (!0 === a.options.centerMode)
                    d = a.slideCount;
                else if (a.options.asNavFor)
                    for (; b < a.slideCount;)
                        ++d,
                            b = c + a.options.slidesToScroll,
                            c += a.options.slidesToScroll <= a.options.slidesToShow ? a.options.slidesToScroll : a.options.slidesToShow;
                else
                    d = 1 + Math.ceil((a.slideCount - a.options.slidesToShow) / a.options.slidesToScroll);
                return d - 1
            }
            ,
            b.prototype.getLeft = function (a) {
                var c, d, f, b = this, e = 0;
                return b.slideOffset = 0,
                    d = b.$slides.first().outerHeight(!0),
                    !0 === b.options.infinite ? (b.slideCount > b.options.slidesToShow && (b.slideOffset = b.slideWidth * b.options.slidesToShow * -1,
                        e = d * b.options.slidesToShow * -1),
                    b.slideCount % b.options.slidesToScroll != 0 && a + b.options.slidesToScroll > b.slideCount && b.slideCount > b.options.slidesToShow && (e = a > b.slideCount ? (b.slideOffset = (b.options.slidesToShow - (a - b.slideCount)) * b.slideWidth * -1,
                    (b.options.slidesToShow - (a - b.slideCount)) * d * -1) : (b.slideOffset = b.slideCount % b.options.slidesToScroll * b.slideWidth * -1,
                    b.slideCount % b.options.slidesToScroll * d * -1))) : a + b.options.slidesToShow > b.slideCount && (b.slideOffset = (a + b.options.slidesToShow - b.slideCount) * b.slideWidth,
                        e = (a + b.options.slidesToShow - b.slideCount) * d),
                b.slideCount <= b.options.slidesToShow && (e = b.slideOffset = 0),
                    !0 === b.options.centerMode && !0 === b.options.infinite ? b.slideOffset += b.slideWidth * Math.floor(b.options.slidesToShow / 2) - b.slideWidth : !0 === b.options.centerMode && (b.slideOffset = 0,
                        b.slideOffset += b.slideWidth * Math.floor(b.options.slidesToShow / 2)),
                    c = !1 === b.options.vertical ? a * b.slideWidth * -1 + b.slideOffset : a * d * -1 + e,
                !0 === b.options.variableWidth && (f = b.slideCount <= b.options.slidesToShow || !1 === b.options.infinite ? b.$slideTrack.children(".slick-slide").eq(a) : b.$slideTrack.children(".slick-slide").eq(a + b.options.slidesToShow),
                    c = !0 === b.options.rtl ? f[0] ? -1 * (b.$slideTrack.width() - f[0].offsetLeft - f.width()) : 0 : f[0] ? -1 * f[0].offsetLeft : 0,
                !0 === b.options.centerMode && (f = b.slideCount <= b.options.slidesToShow || !1 === b.options.infinite ? b.$slideTrack.children(".slick-slide").eq(a) : b.$slideTrack.children(".slick-slide").eq(a + b.options.slidesToShow + 1),
                    c = !0 === b.options.rtl ? f[0] ? -1 * (b.$slideTrack.width() - f[0].offsetLeft - f.width()) : 0 : f[0] ? -1 * f[0].offsetLeft : 0,
                    c += (b.$list.width() - f.outerWidth()) / 2)),
                    c
            }
            ,
            b.prototype.getOption = b.prototype.slickGetOption = function (a) {
                return this.options[a]
            }
            ,
            b.prototype.getNavigableIndexes = function () {
                var e, a = this, b = 0, c = 0, d = [];
                for (e = !1 === a.options.infinite ? a.slideCount : (b = -1 * a.options.slidesToScroll,
                    c = -1 * a.options.slidesToScroll,
                2 * a.slideCount); b < e;)
                    d.push(b),
                        b = c + a.options.slidesToScroll,
                        c += a.options.slidesToScroll <= a.options.slidesToShow ? a.options.slidesToScroll : a.options.slidesToShow;
                return d
            }
            ,
            b.prototype.getSlick = function () {
                return this
            }
            ,
            b.prototype.getSlideCount = function () {
                var d, e, b = this;
                return e = !0 === b.options.centerMode ? b.slideWidth * Math.floor(b.options.slidesToShow / 2) : 0,
                    !0 === b.options.swipeToSlide ? (b.$slideTrack.find(".slick-slide").each(function (c, f) {
                        return f.offsetLeft - e + a(f).outerWidth() / 2 > -1 * b.swipeLeft ? (d = f,
                            !1) : void 0
                    }),
                    Math.abs(a(d).attr("data-slick-index") - b.currentSlide) || 1) : b.options.slidesToScroll
            }
            ,
            b.prototype.goTo = b.prototype.slickGoTo = function (a, b) {
                this.changeSlide({
                    data: {
                        message: "index",
                        index: parseInt(a)
                    }
                }, b)
            }
            ,
            b.prototype.init = function (b) {
                var c = this;
                a(c.$slider).hasClass("slick-initialized") || (a(c.$slider).addClass("slick-initialized"),
                    c.buildRows(),
                    c.buildOut(),
                    c.setProps(),
                    c.startLoad(),
                    c.loadSlider(),
                    c.initializeEvents(),
                    c.updateArrows(),
                    c.updateDots(),
                    c.checkResponsive(!0),
                    c.focusHandler()),
                b && c.$slider.trigger("init", [c]),
                !0 === c.options.accessibility && c.initADA(),
                c.options.autoplay && (c.paused = !1,
                    c.autoPlay())
            }
            ,
            b.prototype.initADA = function () {
                var b = this;
                b.$slides.add(b.$slideTrack.find(".slick-cloned")).attr({
                    "aria-hidden": "true",
                    tabindex: "-1"
                }).find("a, input, button, select").attr({
                    tabindex: "-1"
                }),
                    b.$slideTrack.attr("role", "listbox"),
                    b.$slides.not(b.$slideTrack.find(".slick-cloned")).each(function (c) {
                        a(this).attr({
                            role: "option",
                            "aria-describedby": "slick-slide" + b.instanceUid + c
                        })
                    }),
                null !== b.$dots && b.$dots.attr("role", "tablist").find("li").each(function (c) {
                    a(this).attr({
                        role: "presentation",
                        "aria-selected": "false",
                        "aria-controls": "navigation" + b.instanceUid + c,
                        id: "slick-slide" + b.instanceUid + c
                    })
                }).first().attr("aria-selected", "true").end().find("button").attr("role", "button").end().closest("div").attr("role", "toolbar"),
                    b.activateADA()
            }
            ,
            b.prototype.initArrowEvents = function () {
                var a = this;
                !0 === a.options.arrows && a.slideCount > a.options.slidesToShow && (a.$prevArrow.off("click.slick").on("click.slick", {
                    message: "previous"
                }, a.changeSlide),
                    a.$nextArrow.off("click.slick").on("click.slick", {
                        message: "next"
                    }, a.changeSlide))
            }
            ,
            b.prototype.initDotEvents = function () {
                var b = this;
                !0 === b.options.dots && b.slideCount > b.options.slidesToShow && a("li", b.$dots).on("click.slick", {
                    message: "index"
                }, b.changeSlide),
                !0 === b.options.dots && !0 === b.options.pauseOnDotsHover && a("li", b.$dots).on("mouseenter.slick", a.proxy(b.interrupt, b, !0)).on("mouseleave.slick", a.proxy(b.interrupt, b, !1))
            }
            ,
            b.prototype.initSlideEvents = function () {
                var b = this;
                b.options.pauseOnHover && (b.$list.on("mouseenter.slick", a.proxy(b.interrupt, b, !0)),
                    b.$list.on("mouseleave.slick", a.proxy(b.interrupt, b, !1)))
            }
            ,
            b.prototype.initializeEvents = function () {
                var b = this;
                b.initArrowEvents(),
                    b.initDotEvents(),
                    b.initSlideEvents(),
                    b.$list.on("touchstart.slick mousedown.slick", {
                        action: "start"
                    }, b.swipeHandler),
                    b.$list.on("touchmove.slick mousemove.slick", {
                        action: "move"
                    }, b.swipeHandler),
                    b.$list.on("touchend.slick mouseup.slick", {
                        action: "end"
                    }, b.swipeHandler),
                    b.$list.on("touchcancel.slick mouseleave.slick", {
                        action: "end"
                    }, b.swipeHandler),
                    b.$list.on("click.slick", b.clickHandler),
                    a(document).on(b.visibilityChange, a.proxy(b.visibility, b)),
                !0 === b.options.accessibility && b.$list.on("keydown.slick", b.keyHandler),
                !0 === b.options.focusOnSelect && a(b.$slideTrack).children().on("click.slick", b.selectHandler),
                    a(window).on("orientationchange.slick.slick-" + b.instanceUid, a.proxy(b.orientationChange, b)),
                    a(window).on("resize.slick.slick-" + b.instanceUid, a.proxy(b.resize, b)),
                    a("[draggable!=true]", b.$slideTrack).on("dragstart", b.preventDefault),
                    a(window).on("load.slick.slick-" + b.instanceUid, b.setPosition),
                    a(document).on("ready.slick.slick-" + b.instanceUid, b.setPosition)
            }
            ,
            b.prototype.initUI = function () {
                var a = this;
                !0 === a.options.arrows && a.slideCount > a.options.slidesToShow && (a.$prevArrow.show(),
                    a.$nextArrow.show()),
                !0 === a.options.dots && a.slideCount > a.options.slidesToShow && a.$dots.show()
            }
            ,
            b.prototype.keyHandler = function (a) {
                var b = this;
                a.target.tagName.match("TEXTAREA|INPUT|SELECT") || (37 === a.keyCode && !0 === b.options.accessibility ? b.changeSlide({
                    data: {
                        message: !0 === b.options.rtl ? "next" : "previous"
                    }
                }) : 39 === a.keyCode && !0 === b.options.accessibility && b.changeSlide({
                    data: {
                        message: !0 === b.options.rtl ? "previous" : "next"
                    }
                }))
            }
            ,
            b.prototype.lazyLoad = function () {
                function g(c) {
                    a("img[data-lazy]", c).each(function () {
                        var c = a(this)
                            , d = a(this).attr("data-lazy")
                            , e = document.createElement("img");
                        e.onload = function () {
                            c.animate({
                                opacity: 0
                            }, 100, function () {
                                c.attr("src", d).animate({
                                    opacity: 1
                                }, 200, function () {
                                    c.removeAttr("data-lazy").removeClass("slick-loading")
                                }),
                                    b.$slider.trigger("lazyLoaded", [b, c, d])
                            })
                        }
                            ,
                            e.onerror = function () {
                                c.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),
                                    b.$slider.trigger("lazyLoadError", [b, c, d])
                            }
                            ,
                            e.src = d
                    })
                }

                var e, f, b = this;
                !0 === b.options.centerMode ? f = !0 === b.options.infinite ? (e = b.currentSlide + (b.options.slidesToShow / 2 + 1)) + b.options.slidesToShow + 2 : (e = Math.max(0, b.currentSlide - (b.options.slidesToShow / 2 + 1)),
                b.options.slidesToShow / 2 + 1 + 2 + b.currentSlide) : (e = b.options.infinite ? b.options.slidesToShow + b.currentSlide : b.currentSlide,
                    f = Math.ceil(e + b.options.slidesToShow),
                !0 === b.options.fade && (0 < e && e--,
                f <= b.slideCount && f++)),
                    g(b.$slider.find(".slick-slide").slice(e, f)),
                    b.slideCount <= b.options.slidesToShow ? g(b.$slider.find(".slick-slide")) : b.currentSlide >= b.slideCount - b.options.slidesToShow ? g(b.$slider.find(".slick-cloned").slice(0, b.options.slidesToShow)) : 0 === b.currentSlide && g(b.$slider.find(".slick-cloned").slice(-1 * b.options.slidesToShow))
            }
            ,
            b.prototype.loadSlider = function () {
                var a = this;
                a.setPosition(),
                    a.$slideTrack.css({
                        opacity: 1
                    }),
                    a.$slider.removeClass("slick-loading"),
                    a.initUI(),
                "progressive" === a.options.lazyLoad && a.progressiveLazyLoad()
            }
            ,
            b.prototype.next = b.prototype.slickNext = function () {
                this.changeSlide({
                    data: {
                        message: "next"
                    }
                })
            }
            ,
            b.prototype.orientationChange = function () {
                this.checkResponsive(),
                    this.setPosition()
            }
            ,
            b.prototype.pause = b.prototype.slickPause = function () {
                this.autoPlayClear(),
                    this.paused = !0
            }
            ,
            b.prototype.play = b.prototype.slickPlay = function () {
                var a = this;
                a.autoPlay(),
                    a.options.autoplay = !0,
                    a.paused = !1,
                    a.focussed = !1,
                    a.interrupted = !1
            }
            ,
            b.prototype.postSlide = function (a) {
                var b = this;
                b.unslicked || (b.$slider.trigger("afterChange", [b, a]),
                    b.animating = !1,
                    b.setPosition(),
                    b.swipeLeft = null,
                b.options.autoplay && b.autoPlay(),
                !0 === b.options.accessibility && b.initADA())
            }
            ,
            b.prototype.prev = b.prototype.slickPrev = function () {
                this.changeSlide({
                    data: {
                        message: "previous"
                    }
                })
            }
            ,
            b.prototype.preventDefault = function (a) {
                a.preventDefault()
            }
            ,
            b.prototype.progressiveLazyLoad = function (b) {
                b = b || 1;
                var e, f, g, c = this, d = a("img[data-lazy]", c.$slider);
                d.length ? (e = d.first(),
                    f = e.attr("data-lazy"),
                    (g = document.createElement("img")).onload = function () {
                        e.attr("src", f).removeAttr("data-lazy").removeClass("slick-loading"),
                        !0 === c.options.adaptiveHeight && c.setPosition(),
                            c.$slider.trigger("lazyLoaded", [c, e, f]),
                            c.progressiveLazyLoad()
                    }
                    ,
                    g.onerror = function () {
                        b < 3 ? setTimeout(function () {
                            c.progressiveLazyLoad(b + 1)
                        }, 500) : (e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),
                            c.$slider.trigger("lazyLoadError", [c, e, f]),
                            c.progressiveLazyLoad())
                    }
                    ,
                    g.src = f) : c.$slider.trigger("allImagesLoaded", [c])
            }
            ,
            b.prototype.refresh = function (b) {
                var d, e, c = this;
                e = c.slideCount - c.options.slidesToShow,
                !c.options.infinite && c.currentSlide > e && (c.currentSlide = e),
                c.slideCount <= c.options.slidesToShow && (c.currentSlide = 0),
                    d = c.currentSlide,
                    c.destroy(!0),
                    a.extend(c, c.initials, {
                        currentSlide: d
                    }),
                    c.init(),
                b || c.changeSlide({
                    data: {
                        message: "index",
                        index: d
                    }
                }, !1)
            }
            ,
            b.prototype.registerBreakpoints = function () {
                var c, d, e, b = this, f = b.options.responsive || null;
                if ("array" === a.type(f) && f.length) {
                    for (c in b.respondTo = b.options.respondTo || "window",
                        f)
                        if (e = b.breakpoints.length - 1,
                            d = f[c].breakpoint,
                            f.hasOwnProperty(c)) {
                            for (; 0 <= e;)
                                b.breakpoints[e] && b.breakpoints[e] === d && b.breakpoints.splice(e, 1),
                                    e--;
                            b.breakpoints.push(d),
                                b.breakpointSettings[d] = f[c].settings
                        }
                    b.breakpoints.sort(function (a, c) {
                        return b.options.mobileFirst ? a - c : c - a
                    })
                }
            }
            ,
            b.prototype.reinit = function () {
                var b = this;
                b.$slides = b.$slideTrack.children(b.options.slide).addClass("slick-slide"),
                    b.slideCount = b.$slides.length,
                b.currentSlide >= b.slideCount && 0 !== b.currentSlide && (b.currentSlide = b.currentSlide - b.options.slidesToScroll),
                b.slideCount <= b.options.slidesToShow && (b.currentSlide = 0),
                    b.registerBreakpoints(),
                    b.setProps(),
                    b.setupInfinite(),
                    b.buildArrows(),
                    b.updateArrows(),
                    b.initArrowEvents(),
                    b.buildDots(),
                    b.updateDots(),
                    b.initDotEvents(),
                    b.cleanUpSlideEvents(),
                    b.initSlideEvents(),
                    b.checkResponsive(!1, !0),
                !0 === b.options.focusOnSelect && a(b.$slideTrack).children().on("click.slick", b.selectHandler),
                    b.setSlideClasses("number" == typeof b.currentSlide ? b.currentSlide : 0),
                    b.setPosition(),
                    b.focusHandler(),
                    b.paused = !b.options.autoplay,
                    b.autoPlay(),
                    b.$slider.trigger("reInit", [b])
            }
            ,
            b.prototype.resize = function () {
                var b = this;
                a(window).width() !== b.windowWidth && (clearTimeout(b.windowDelay),
                    b.windowDelay = window.setTimeout(function () {
                        b.windowWidth = a(window).width(),
                            b.checkResponsive(),
                        b.unslicked || b.setPosition()
                    }, 50))
            }
            ,
            b.prototype.removeSlide = b.prototype.slickRemove = function (a, b, c) {
                var d = this;
                return a = "boolean" == typeof a ? !0 === (b = a) ? 0 : d.slideCount - 1 : !0 === b ? --a : a,
                !(d.slideCount < 1 || a < 0 || a > d.slideCount - 1) && (d.unload(),
                    !0 === c ? d.$slideTrack.children().remove() : d.$slideTrack.children(this.options.slide).eq(a).remove(),
                    d.$slides = d.$slideTrack.children(this.options.slide),
                    d.$slideTrack.children(this.options.slide).detach(),
                    d.$slideTrack.append(d.$slides),
                    d.$slidesCache = d.$slides,
                    void d.reinit())
            }
            ,
            b.prototype.setCSS = function (a) {
                var d, e, b = this, c = {};
                !0 === b.options.rtl && (a = -a),
                    d = "left" == b.positionProp ? Math.ceil(a) + "px" : "0px",
                    e = "top" == b.positionProp ? Math.ceil(a) + "px" : "0px",
                    c[b.positionProp] = a,
                !1 === b.transformsEnabled || (!(c = {}) === b.cssTransitions ? c[b.animType] = "translate(" + d + ", " + e + ")" : c[b.animType] = "translate3d(" + d + ", " + e + ", 0px)"),
                    b.$slideTrack.css(c)
            }
            ,
            b.prototype.setDimensions = function () {
                var a = this;
                !1 === a.options.vertical ? !0 === a.options.centerMode && a.$list.css({
                    padding: "0px " + a.options.centerPadding
                }) : (a.$list.height(a.$slides.first().outerHeight(!0) * a.options.slidesToShow),
                !0 === a.options.centerMode && a.$list.css({
                    padding: a.options.centerPadding + " 0px"
                })),
                    a.listWidth = a.$list.width(),
                    a.listHeight = a.$list.height(),
                    !1 === a.options.vertical && !1 === a.options.variableWidth ? (a.slideWidth = Math.ceil(a.listWidth / a.options.slidesToShow),
                        a.$slideTrack.width(Math.ceil(a.slideWidth * a.$slideTrack.children(".slick-slide").length))) : !0 === a.options.variableWidth ? a.$slideTrack.width(5e3 * a.slideCount) : (a.slideWidth = Math.ceil(a.listWidth),
                        a.$slideTrack.height(Math.ceil(a.$slides.first().outerHeight(!0) * a.$slideTrack.children(".slick-slide").length)));
                var b = a.$slides.first().outerWidth(!0) - a.$slides.first().width();
                !1 === a.options.variableWidth && a.$slideTrack.children(".slick-slide").width(a.slideWidth - b)
            }
            ,
            b.prototype.setFade = function () {
                var c, b = this;
                b.$slides.each(function (d, e) {
                    c = b.slideWidth * d * -1,
                        !0 === b.options.rtl ? a(e).css({
                            position: "relative",
                            right: c,
                            top: 0,
                            zIndex: b.options.zIndex - 2,
                            opacity: 0
                        }) : a(e).css({
                            position: "relative",
                            left: c,
                            top: 0,
                            zIndex: b.options.zIndex - 2,
                            opacity: 0
                        })
                }),
                    b.$slides.eq(b.currentSlide).css({
                        zIndex: b.options.zIndex - 1,
                        opacity: 1
                    })
            }
            ,
            b.prototype.setHeight = function () {
                var a = this;
                if (1 === a.options.slidesToShow && !0 === a.options.adaptiveHeight && !1 === a.options.vertical) {
                    var b = a.$slides.eq(a.currentSlide).outerHeight(!0);
                    a.$list.css("height", b)
                }
            }
            ,
            b.prototype.setOption = b.prototype.slickSetOption = function () {
                var c, d, e, f, h, b = this, g = !1;
                if ("object" === a.type(arguments[0]) ? (e = arguments[0],
                    g = arguments[1],
                    h = "multiple") : "string" === a.type(arguments[0]) && (f = arguments[1],
                    g = arguments[2],
                    "responsive" === (e = arguments[0]) && "array" === a.type(arguments[1]) ? h = "responsive" : void 0 !== arguments[1] && (h = "single")),
                "single" === h)
                    b.options[e] = f;
                else if ("multiple" === h)
                    a.each(e, function (a, c) {
                        b.options[a] = c
                    });
                else if ("responsive" === h)
                    for (d in f)
                        if ("array" !== a.type(b.options.responsive))
                            b.options.responsive = [f[d]];
                        else {
                            for (c = b.options.responsive.length - 1; 0 <= c;)
                                b.options.responsive[c].breakpoint === f[d].breakpoint && b.options.responsive.splice(c, 1),
                                    c--;
                            b.options.responsive.push(f[d])
                        }
                g && (b.unload(),
                    b.reinit())
            }
            ,
            b.prototype.setPosition = function () {
                var a = this;
                a.setDimensions(),
                    a.setHeight(),
                    !1 === a.options.fade ? a.setCSS(a.getLeft(a.currentSlide)) : a.setFade(),
                    a.$slider.trigger("setPosition", [a])
            }
            ,
            b.prototype.setProps = function () {
                var a = this
                    , b = document.body.style;
                a.positionProp = !0 === a.options.vertical ? "top" : "left",
                    "top" === a.positionProp ? a.$slider.addClass("slick-vertical") : a.$slider.removeClass("slick-vertical"),
                void 0 === b.WebkitTransition && void 0 === b.MozTransition && void 0 === b.msTransition || !0 !== a.options.useCSS || (a.cssTransitions = !0),
                a.options.fade && ("number" == typeof a.options.zIndex ? a.options.zIndex < 3 && (a.options.zIndex = 3) : a.options.zIndex = a.defaults.zIndex),
                void 0 !== b.OTransform && (a.animType = "OTransform",
                    a.transformType = "-o-transform",
                    a.transitionType = "OTransition",
                void 0 === b.perspectiveProperty && void 0 === b.webkitPerspective && (a.animType = !1)),
                void 0 !== b.MozTransform && (a.animType = "MozTransform",
                    a.transformType = "-moz-transform",
                    a.transitionType = "MozTransition",
                void 0 === b.perspectiveProperty && void 0 === b.MozPerspective && (a.animType = !1)),
                void 0 !== b.webkitTransform && (a.animType = "webkitTransform",
                    a.transformType = "-webkit-transform",
                    a.transitionType = "webkitTransition",
                void 0 === b.perspectiveProperty && void 0 === b.webkitPerspective && (a.animType = !1)),
                void 0 !== b.msTransform && (a.animType = "msTransform",
                    a.transformType = "-ms-transform",
                    a.transitionType = "msTransition",
                void 0 === b.msTransform && (a.animType = !1)),
                void 0 !== b.transform && !1 !== a.animType && (a.animType = "transform",
                    a.transformType = "transform",
                    a.transitionType = "transition"),
                    a.transformsEnabled = a.options.useTransform && null !== a.animType && !1 !== a.animType
            }
            ,
            b.prototype.setSlideClasses = function (a) {
                var c, d, e, f, b = this;
                d = b.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden", "true"),
                    b.$slides.eq(a).addClass("slick-current"),
                    !0 === b.options.centerMode ? (c = Math.floor(b.options.slidesToShow / 2),
                    !0 === b.options.infinite && (c <= a && a <= b.slideCount - 1 - c ? b.$slides.slice(a - c, a + c + 1).addClass("slick-active").attr("aria-hidden", "false") : (e = b.options.slidesToShow + a,
                        d.slice(e - c + 1, e + c + 2).addClass("slick-active").attr("aria-hidden", "false")),
                        0 === a ? d.eq(d.length - 1 - b.options.slidesToShow).addClass("slick-center") : a === b.slideCount - 1 && d.eq(b.options.slidesToShow).addClass("slick-center")),
                        b.$slides.eq(a).addClass("slick-center")) : 0 <= a && a <= b.slideCount - b.options.slidesToShow ? b.$slides.slice(a, a + b.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false") : d.length <= b.options.slidesToShow ? d.addClass("slick-active").attr("aria-hidden", "false") : (f = b.slideCount % b.options.slidesToShow,
                        e = !0 === b.options.infinite ? b.options.slidesToShow + a : a,
                        b.options.slidesToShow == b.options.slidesToScroll && b.slideCount - a < b.options.slidesToShow ? d.slice(e - (b.options.slidesToShow - f), e + f).addClass("slick-active").attr("aria-hidden", "false") : d.slice(e, e + b.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false")),
                "ondemand" === b.options.lazyLoad && b.lazyLoad()
            }
            ,
            b.prototype.setupInfinite = function () {
                var c, d, e, b = this;
                if (!0 === b.options.fade && (b.options.centerMode = !1),
                !0 === b.options.infinite && !1 === b.options.fade && (d = null,
                b.slideCount > b.options.slidesToShow)) {
                    for (e = !0 === b.options.centerMode ? b.options.slidesToShow + 1 : b.options.slidesToShow,
                             c = b.slideCount; c > b.slideCount - e; --c)
                        d = c - 1,
                            a(b.$slides[d]).clone(!0).attr("id", "").attr("data-slick-index", d - b.slideCount).prependTo(b.$slideTrack).addClass("slick-cloned");
                    for (c = 0; c < e; c += 1)
                        d = c,
                            a(b.$slides[d]).clone(!0).attr("id", "").attr("data-slick-index", d + b.slideCount).appendTo(b.$slideTrack).addClass("slick-cloned");
                    b.$slideTrack.find(".slick-cloned").find("[id]").each(function () {
                        a(this).attr("id", "")
                    })
                }
            }
            ,
            b.prototype.interrupt = function (a) {
                a || this.autoPlay(),
                    this.interrupted = a
            }
            ,
            b.prototype.selectHandler = function (b) {
                var c = this
                    , d = a(b.target).is(".slick-slide") ? a(b.target) : a(b.target).parents(".slick-slide")
                    , e = parseInt(d.attr("data-slick-index"));
                return e = e || 0,
                    c.slideCount <= c.options.slidesToShow ? (c.setSlideClasses(e),
                        void c.asNavFor(e)) : void c.slideHandler(e)
            }
            ,
            b.prototype.slideHandler = function (a, b, c) {
                var d, e, f, g, j, h = null, i = this;
                return b = b || !1,
                    !0 === i.animating && !0 === i.options.waitForAnimate || !0 === i.options.fade && i.currentSlide === a || i.slideCount <= i.options.slidesToShow ? void 0 : (!1 === b && i.asNavFor(a),
                        d = a,
                        h = i.getLeft(d),
                        g = i.getLeft(i.currentSlide),
                        i.currentLeft = null === i.swipeLeft ? g : i.swipeLeft,
                        !1 === i.options.infinite && !1 === i.options.centerMode && (a < 0 || a > i.getDotCount() * i.options.slidesToScroll) || !1 === i.options.infinite && !0 === i.options.centerMode && (a < 0 || a > i.slideCount - i.options.slidesToScroll) ? void (!1 === i.options.fade && (d = i.currentSlide,
                            !0 !== c ? i.animateSlide(g, function () {
                                i.postSlide(d)
                            }) : i.postSlide(d))) : (i.options.autoplay && clearInterval(i.autoPlayTimer),
                            e = d < 0 ? i.slideCount % i.options.slidesToScroll != 0 ? i.slideCount - i.slideCount % i.options.slidesToScroll : i.slideCount + d : d >= i.slideCount ? i.slideCount % i.options.slidesToScroll != 0 ? 0 : d - i.slideCount : d,
                            i.animating = !0,
                            i.$slider.trigger("beforeChange", [i, i.currentSlide, e]),
                            f = i.currentSlide,
                            i.currentSlide = e,
                            i.setSlideClasses(i.currentSlide),
                        i.options.asNavFor && ((j = (j = i.getNavTarget()).slick("getSlick")).slideCount <= j.options.slidesToShow && j.setSlideClasses(i.currentSlide)),
                            i.updateDots(),
                            i.updateArrows(),
                            !0 === i.options.fade ? (!0 !== c ? (i.fadeSlideOut(f),
                                i.fadeSlide(e, function () {
                                    i.postSlide(e)
                                })) : i.postSlide(e),
                                void i.animateHeight()) : void (!0 !== c ? i.animateSlide(h, function () {
                                i.postSlide(e)
                            }) : i.postSlide(e))))
            }
            ,
            b.prototype.startLoad = function () {
                var a = this;
                !0 === a.options.arrows && a.slideCount > a.options.slidesToShow && (a.$prevArrow.hide(),
                    a.$nextArrow.hide()),
                !0 === a.options.dots && a.slideCount > a.options.slidesToShow && a.$dots.hide(),
                    a.$slider.addClass("slick-loading")
            }
            ,
            b.prototype.swipeDirection = function () {
                var a, b, c, d, e = this;
                return a = e.touchObject.startX - e.touchObject.curX,
                    b = e.touchObject.startY - e.touchObject.curY,
                    c = Math.atan2(b, a),
                (d = Math.round(180 * c / Math.PI)) < 0 && (d = 360 - Math.abs(d)),
                    d <= 45 && 0 <= d || d <= 360 && 315 <= d ? !1 === e.options.rtl ? "left" : "right" : 135 <= d && d <= 225 ? !1 === e.options.rtl ? "right" : "left" : !0 === e.options.verticalSwiping ? 35 <= d && d <= 135 ? "down" : "up" : "vertical"
            }
            ,
            b.prototype.swipeEnd = function (a) {
                var c, d, b = this;
                if (b.dragging = !1,
                    b.interrupted = !1,
                    b.shouldClick = !(10 < b.touchObject.swipeLength),
                void 0 === b.touchObject.curX)
                    return !1;
                if (!0 === b.touchObject.edgeHit && b.$slider.trigger("edge", [b, b.swipeDirection()]),
                b.touchObject.swipeLength >= b.touchObject.minSwipe) {
                    switch (d = b.swipeDirection()) {
                        case "left":
                        case "down":
                            c = b.options.swipeToSlide ? b.checkNavigable(b.currentSlide + b.getSlideCount()) : b.currentSlide + b.getSlideCount(),
                                b.currentDirection = 0;
                            break;
                        case "right":
                        case "up":
                            c = b.options.swipeToSlide ? b.checkNavigable(b.currentSlide - b.getSlideCount()) : b.currentSlide - b.getSlideCount(),
                                b.currentDirection = 1
                    }
                    "vertical" != d && (b.slideHandler(c),
                        b.touchObject = {},
                        b.$slider.trigger("swipe", [b, d]))
                } else
                    b.touchObject.startX !== b.touchObject.curX && (b.slideHandler(b.currentSlide),
                        b.touchObject = {})
            }
            ,
            b.prototype.swipeHandler = function (a) {
                var b = this;
                if (!(!1 === b.options.swipe || "ontouchend" in document && !1 === b.options.swipe || !1 === b.options.draggable && -1 !== a.type.indexOf("mouse")))
                    switch (b.touchObject.fingerCount = a.originalEvent && void 0 !== a.originalEvent.touches ? a.originalEvent.touches.length : 1,
                        b.touchObject.minSwipe = b.listWidth / b.options.touchThreshold,
                    !0 === b.options.verticalSwiping && (b.touchObject.minSwipe = b.listHeight / b.options.touchThreshold),
                        a.data.action) {
                        case "start":
                            b.swipeStart(a);
                            break;
                        case "move":
                            b.swipeMove(a);
                            break;
                        case "end":
                            b.swipeEnd(a)
                    }
            }
            ,
            b.prototype.swipeMove = function (a) {
                var d, e, f, g, h, b = this;
                return h = void 0 !== a.originalEvent ? a.originalEvent.touches : null,
                !(!b.dragging || h && 1 !== h.length) && (d = b.getLeft(b.currentSlide),
                    b.touchObject.curX = void 0 !== h ? h[0].pageX : a.clientX,
                    b.touchObject.curY = void 0 !== h ? h[0].pageY : a.clientY,
                    b.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(b.touchObject.curX - b.touchObject.startX, 2))),
                !0 === b.options.verticalSwiping && (b.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(b.touchObject.curY - b.touchObject.startY, 2)))),
                    "vertical" !== (e = b.swipeDirection()) ? (void 0 !== a.originalEvent && 4 < b.touchObject.swipeLength && a.preventDefault(),
                        g = (!1 === b.options.rtl ? 1 : -1) * (b.touchObject.curX > b.touchObject.startX ? 1 : -1),
                    !0 === b.options.verticalSwiping && (g = b.touchObject.curY > b.touchObject.startY ? 1 : -1),
                        f = b.touchObject.swipeLength,
                    (b.touchObject.edgeHit = !1) === b.options.infinite && (0 === b.currentSlide && "right" === e || b.currentSlide >= b.getDotCount() && "left" === e) && (f = b.touchObject.swipeLength * b.options.edgeFriction,
                        b.touchObject.edgeHit = !0),
                        !1 === b.options.vertical ? b.swipeLeft = d + f * g : b.swipeLeft = d + f * (b.$list.height() / b.listWidth) * g,
                    !0 === b.options.verticalSwiping && (b.swipeLeft = d + f * g),
                    !0 !== b.options.fade && !1 !== b.options.touchMove && (!0 === b.animating ? (b.swipeLeft = null,
                        !1) : void b.setCSS(b.swipeLeft))) : void 0)
            }
            ,
            b.prototype.swipeStart = function (a) {
                var c, b = this;
                return b.interrupted = !0,
                    1 !== b.touchObject.fingerCount || b.slideCount <= b.options.slidesToShow ? !(b.touchObject = {}) : (void 0 !== a.originalEvent && void 0 !== a.originalEvent.touches && (c = a.originalEvent.touches[0]),
                        b.touchObject.startX = b.touchObject.curX = void 0 !== c ? c.pageX : a.clientX,
                        b.touchObject.startY = b.touchObject.curY = void 0 !== c ? c.pageY : a.clientY,
                        void (b.dragging = !0))
            }
            ,
            b.prototype.unfilterSlides = b.prototype.slickUnfilter = function () {
                var a = this;
                null !== a.$slidesCache && (a.unload(),
                    a.$slideTrack.children(this.options.slide).detach(),
                    a.$slidesCache.appendTo(a.$slideTrack),
                    a.reinit())
            }
            ,
            b.prototype.unload = function () {
                var b = this;
                a(".slick-cloned", b.$slider).remove(),
                b.$dots && b.$dots.remove(),
                b.$prevArrow && b.htmlExpr.test(b.options.prevArrow) && b.$prevArrow.remove(),
                b.$nextArrow && b.htmlExpr.test(b.options.nextArrow) && b.$nextArrow.remove(),
                    b.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden", "true").css("width", "")
            }
            ,
            b.prototype.unslick = function (a) {
                var b = this;
                b.$slider.trigger("unslick", [b, a]),
                    b.destroy()
            }
            ,
            b.prototype.updateArrows = function () {
                var a = this;
                Math.floor(a.options.slidesToShow / 2),
                !0 === a.options.arrows && a.slideCount > a.options.slidesToShow && !a.options.infinite && (a.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false"),
                    a.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false"),
                    0 === a.currentSlide ? (a.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true"),
                        a.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : (a.currentSlide >= a.slideCount - a.options.slidesToShow && !1 === a.options.centerMode || a.currentSlide >= a.slideCount - 1 && !0 === a.options.centerMode) && (a.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"),
                        a.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")))
            }
            ,
            b.prototype.updateDots = function () {
                var a = this;
                null !== a.$dots && (a.$dots.find("li").removeClass("slick-active").attr("aria-hidden", "true"),
                    a.$dots.find("li").eq(Math.floor(a.currentSlide / a.options.slidesToScroll)).addClass("slick-active").attr("aria-hidden", "false"))
            }
            ,
            b.prototype.visibility = function () {
                var a = this;
                a.options.autoplay && (document[a.hidden] ? a.interrupted = !0 : a.interrupted = !1)
            }
            ,
            a.fn.slick = function () {
                var f, g, a = this, c = arguments[0], d = Array.prototype.slice.call(arguments, 1), e = a.length;
                for (f = 0; f < e; f++)
                    if ("object" == typeof c || void 0 === c ? a[f].slick = new b(a[f], c) : g = a[f].slick[c].apply(a[f].slick, d),
                    void 0 !== g)
                        return g;
                return a
            }
    });

var AttributeSelection = function ($) {
    $(document).ready(function () {
        $(".product-attribute").find("input[type=radio]").on("change", function () {
            $(this).parents(".product-attribute").find(".product-attribute__value").removeClass("active"),
                $(this).parents(".product-attribute__value").addClass("active")
        })
    })
}(jQuery)
    , CategoryNavigation = function ($) {
    return $(document).ready(function () {
        $(".nav-category .nav-item").click(function () {
            return $("html, body").animate({
                scrollTop: $(this.getAttribute("href")).offset().top - 40
            }, 1700),
                !1
        })
    }),
        CategoryNavigation
}(jQuery)
    , HeaderTransparency = function ($) {
    var headerTransparency, vh = Math.max(document.documentElement.clientHeight, window.innerHeight || 0),
        last_known_scroll_position = 0, ticking = !1, HeaderTransparency = function () {
            function HeaderTransparency(element) {
                this._element = element,
                    this.rgb = element.css("background-color").match(/\d+/g).shift()
            }

            var _proto = HeaderTransparency.prototype;
            return _proto.setBackgroundColor = function (last_known_scroll_position) {
                var scrollTop = last_known_scroll_position || $(window).scrollTop()
                    , opacity = this.getOpacity(scrollTop);
                scrollTop <= vh && this._element.css({
                    "background-color": "rgba(" + this.rgb + "," + this.rgb + "," + this.rgb + "," + opacity + ")",
                    color: "rgba(0,0,0," + opacity / 10 + ")"
                })
            }
                ,
                _proto.getOpacity = function (scrollTop) {
                    return void 0 === scrollTop && (scrollTop = 0),
                        this.hasPictureTeaserOnFirstPosition() ? Math.max(Math.min(4 * (scrollTop / vh - .5), 1), 0) : 1
                }
                ,
                _proto.hasPictureTeaserOnFirstPosition = function () {
                    return 0 < $(".picture-teaser-container-fullscreen:first-child, .picture-teaser-container-xl:first-child").length
                }
                ,
                HeaderTransparency
        }();
    $(window).ready(function (event) {
        var headerElement = $(".header__brand");
        headerTransparency = new HeaderTransparency(headerElement),
        headerElement && (headerTransparency.setBackgroundColor(),
            window.addEventListener("scroll", function (e) {
                last_known_scroll_position = window.scrollY,
                ticking || (window.requestAnimationFrame(function () {
                    headerTransparency.setBackgroundColor(last_known_scroll_position),
                        ticking = !1
                }),
                    ticking = !0)
            }))
    })
}(jQuery);
!function ($) {
    $(window).ready(function () {
        $(".cart-item-amount").find(".icon").on("click", function (event) {
            var trigger = $(event.currentTarget)
                , direction = trigger.hasClass("icon-plus") ? 1 : -1
                , input = $(trigger.parents(".cart-item-amount").find("input").get(0))
                , currentValue = parseInt(input.val());
            currentValue < 1 && direction < 0 && (direction = 0),
                input.trigger("itemAmountChange", [currentValue, direction]),
                input.val(currentValue + direction).trigger("change")
        })
    })
}(jQuery);
var MainTitle = function ($) {
    $(document).ready(function () {
        var mainTitle = $(".main-title").get(0)
            , breadcrumb = $(".breadcrumb").get(0)
            , teaser = $("[class^=picture-teaser-container]:first-child").get(0);
        mainTitle && breadcrumb ? breadcrumb.after(mainTitle) : mainTitle && teaser && teaser.after(mainTitle)
    })
}(jQuery)
    , StickyNavigation = function ($) {
    return $(document).on("scrolldirectionchange", function (evt, direction) {
        "down" === direction ? $("body").addClass("scroll-down") : $("body").removeClass("scroll-down")
    }),
        function () {
        }
}(jQuery)
    , Tac = function ($) {
    $(document).ready(function () {
        $("[data-prevent-submit]").on("change", function (event) {
            $(this).parents("form").find('button[type="submit"]').attr("disabled", !$(this).prop("checked"))
        })
    })
}(jQuery);

jQuery(function ($) {
    $('[data-toggle="tooltip"]').tooltip()
}),
    function ($) {
        function switchOrientation(orientation) {
            $("[data-portrait]").each(function (index, item) {
                var video = $(item).parent("video")[0];
                video.pause(),
                    item.src = $(item).data(orientation),
                    video.load()
            })
        }

        $(window).ready(function () {
            window.matchMedia("(orientation: portrait)").matches ? switchOrientation("portrait") : switchOrientation("landscape")
        })
    }(jQuery),
    function ($) {
        $(window).ready(function () {
            $("[data-fullScreen]").on("click", function (event) {
                var video = $(this).parents(".full-video").find("video").get(0);
                (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) && video.requestFullscreen(),
                    navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) ? video.webkitEnterFullscreen() : video.requestFullscreen()
            })
        })
    }(jQuery),
    function ($) {
        function setMinHeight() {
            var minHeight = $("[data-min-height]");
            minHeight && minHeight.css("min-height", "auto").each(function (index, item) {
                var height = $(item).height();
                $(item).css("min-height", height)
            })
        }

        window.onload = setMinHeight,
            $(window).on("resize", setMinHeight)
    }(jQuery),
    function () {
        "use strict";
        if ("object" == typeof window)
            if ("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype)
                "isIntersecting" in window.IntersectionObserverEntry.prototype || Object.defineProperty(window.IntersectionObserverEntry.prototype, "isIntersecting", {
                    get: function () {
                        return 0 < this.intersectionRatio
                    }
                });
            else {
                var document = window.document
                    , registry = [];
                IntersectionObserver.prototype.THROTTLE_TIMEOUT = 100,
                    IntersectionObserver.prototype.POLL_INTERVAL = null,
                    IntersectionObserver.prototype.USE_MUTATION_OBSERVER = !0,
                    IntersectionObserver.prototype.observe = function (target) {
                        if (!this._observationTargets.some(function (item) {
                            return item.element == target
                        })) {
                            if (!target || 1 != target.nodeType)
                                throw new Error("target must be an Element");
                            this._registerInstance(),
                                this._observationTargets.push({
                                    element: target,
                                    entry: null
                                }),
                                this._monitorIntersections(),
                                this._checkForIntersections()
                        }
                    }
                    ,
                    IntersectionObserver.prototype.unobserve = function (target) {
                        this._observationTargets = this._observationTargets.filter(function (item) {
                            return item.element != target
                        }),
                        this._observationTargets.length || (this._unmonitorIntersections(),
                            this._unregisterInstance())
                    }
                    ,
                    IntersectionObserver.prototype.disconnect = function () {
                        this._observationTargets = [],
                            this._unmonitorIntersections(),
                            this._unregisterInstance()
                    }
                    ,
                    IntersectionObserver.prototype.takeRecords = function () {
                        var records = this._queuedEntries.slice();
                        return this._queuedEntries = [],
                            records
                    }
                    ,
                    IntersectionObserver.prototype._initThresholds = function (opt_threshold) {
                        var threshold = opt_threshold || [0];
                        return Array.isArray(threshold) || (threshold = [threshold]),
                            threshold.sort().filter(function (t, i, a) {
                                if ("number" != typeof t || isNaN(t) || t < 0 || 1 < t)
                                    throw new Error("threshold must be a number between 0 and 1 inclusively");
                                return t !== a[i - 1]
                            })
                    }
                    ,
                    IntersectionObserver.prototype._parseRootMargin = function (opt_rootMargin) {
                        var margins = (opt_rootMargin || "0px").split(/\s+/).map(function (margin) {
                            var parts = /^(-?\d*\.?\d+)(px|%)$/.exec(margin);
                            if (!parts)
                                throw new Error("rootMargin must be specified in pixels or percent");
                            return {
                                value: parseFloat(parts[1]),
                                unit: parts[2]
                            }
                        });
                        return margins[1] = margins[1] || margins[0],
                            margins[2] = margins[2] || margins[0],
                            margins[3] = margins[3] || margins[1],
                            margins
                    }
                    ,
                    IntersectionObserver.prototype._monitorIntersections = function () {
                        this._monitoringIntersections || (this._monitoringIntersections = !0,
                            this.POLL_INTERVAL ? this._monitoringInterval = setInterval(this._checkForIntersections, this.POLL_INTERVAL) : (addEvent(window, "resize", this._checkForIntersections, !0),
                                addEvent(document, "scroll", this._checkForIntersections, !0),
                            this.USE_MUTATION_OBSERVER && "MutationObserver" in window && (this._domObserver = new MutationObserver(this._checkForIntersections),
                                this._domObserver.observe(document, {
                                    attributes: !0,
                                    childList: !0,
                                    characterData: !0,
                                    subtree: !0
                                }))))
                    }
                    ,
                    IntersectionObserver.prototype._unmonitorIntersections = function () {
                        this._monitoringIntersections && (this._monitoringIntersections = !1,
                            clearInterval(this._monitoringInterval),
                            this._monitoringInterval = null,
                            removeEvent(window, "resize", this._checkForIntersections, !0),
                            removeEvent(document, "scroll", this._checkForIntersections, !0),
                        this._domObserver && (this._domObserver.disconnect(),
                            this._domObserver = null))
                    }
                    ,
                    IntersectionObserver.prototype._checkForIntersections = function () {
                        var rootIsInDom = this._rootIsInDom()
                            , rootRect = rootIsInDom ? this._getRootRect() : getEmptyRect();
                        this._observationTargets.forEach(function (item) {
                            var target = item.element
                                , targetRect = getBoundingClientRect(target)
                                , rootContainsTarget = this._rootContainsTarget(target)
                                , oldEntry = item.entry
                                ,
                                intersectionRect = rootIsInDom && rootContainsTarget && this._computeTargetAndRootIntersection(target, rootRect)
                                , newEntry = item.entry = new IntersectionObserverEntry({
                                    time: window.performance && performance.now && performance.now(),
                                    target: target,
                                    boundingClientRect: targetRect,
                                    rootBounds: rootRect,
                                    intersectionRect: intersectionRect
                                });
                            oldEntry ? rootIsInDom && rootContainsTarget ? this._hasCrossedThreshold(oldEntry, newEntry) && this._queuedEntries.push(newEntry) : oldEntry && oldEntry.isIntersecting && this._queuedEntries.push(newEntry) : this._queuedEntries.push(newEntry)
                        }, this),
                        this._queuedEntries.length && this._callback(this.takeRecords(), this)
                    }
                    ,
                    IntersectionObserver.prototype._computeTargetAndRootIntersection = function (target, rootRect) {
                        if ("none" != window.getComputedStyle(target).display) {
                            for (var rect1, rect2, top, bottom, left, right, width, height, intersectionRect = getBoundingClientRect(target), parent = getParentNode(target), atRoot = !1; !atRoot;) {
                                var parentRect = null
                                    , parentComputedStyle = 1 == parent.nodeType ? window.getComputedStyle(parent) : {};
                                if ("none" == parentComputedStyle.display)
                                    return;
                                if (parent == this.root || parent == document ? (atRoot = !0,
                                    parentRect = rootRect) : parent != document.body && parent != document.documentElement && "visible" != parentComputedStyle.overflow && (parentRect = getBoundingClientRect(parent)),
                                parentRect && (rect1 = parentRect,
                                    rect2 = intersectionRect,
                                    0,
                                    top = Math.max(rect1.top, rect2.top),
                                    bottom = Math.min(rect1.bottom, rect2.bottom),
                                    left = Math.max(rect1.left, rect2.left),
                                    right = Math.min(rect1.right, rect2.right),
                                    height = bottom - top,
                                    !(intersectionRect = 0 <= (width = right - left) && 0 <= height && {
                                        top: top,
                                        bottom: bottom,
                                        left: left,
                                        right: right,
                                        width: width,
                                        height: height
                                    })))
                                    break;
                                parent = getParentNode(parent)
                            }
                            return intersectionRect
                        }
                    }
                    ,
                    IntersectionObserver.prototype._getRootRect = function () {
                        var rootRect;
                        if (this.root)
                            rootRect = getBoundingClientRect(this.root);
                        else {
                            var html = document.documentElement
                                , body = document.body;
                            rootRect = {
                                top: 0,
                                left: 0,
                                right: html.clientWidth || body.clientWidth,
                                width: html.clientWidth || body.clientWidth,
                                bottom: html.clientHeight || body.clientHeight,
                                height: html.clientHeight || body.clientHeight
                            }
                        }
                        return this._expandRectByRootMargin(rootRect)
                    }
                    ,
                    IntersectionObserver.prototype._expandRectByRootMargin = function (rect) {
                        var margins = this._rootMarginValues.map(function (margin, i) {
                            return "px" == margin.unit ? margin.value : margin.value * (i % 2 ? rect.width : rect.height) / 100
                        })
                            , newRect = {
                            top: rect.top - margins[0],
                            right: rect.right + margins[1],
                            bottom: rect.bottom + margins[2],
                            left: rect.left - margins[3]
                        };
                        return newRect.width = newRect.right - newRect.left,
                            newRect.height = newRect.bottom - newRect.top,
                            newRect
                    }
                    ,
                    IntersectionObserver.prototype._hasCrossedThreshold = function (oldEntry, newEntry) {
                        var oldRatio = oldEntry && oldEntry.isIntersecting ? oldEntry.intersectionRatio || 0 : -1
                            , newRatio = newEntry.isIntersecting ? newEntry.intersectionRatio || 0 : -1;
                        if (oldRatio !== newRatio)
                            for (var i = 0; i < this.thresholds.length; i++) {
                                var threshold = this.thresholds[i];
                                if (threshold == oldRatio || threshold == newRatio || threshold < oldRatio != threshold < newRatio)
                                    return !0
                            }
                    }
                    ,
                    IntersectionObserver.prototype._rootIsInDom = function () {
                        return !this.root || containsDeep(document, this.root)
                    }
                    ,
                    IntersectionObserver.prototype._rootContainsTarget = function (target) {
                        return containsDeep(this.root || document, target)
                    }
                    ,
                    IntersectionObserver.prototype._registerInstance = function () {
                        registry.indexOf(this) < 0 && registry.push(this)
                    }
                    ,
                    IntersectionObserver.prototype._unregisterInstance = function () {
                        var index = registry.indexOf(this);
                        -1 != index && registry.splice(index, 1)
                    }
                    ,
                    window.IntersectionObserver = IntersectionObserver,
                    window.IntersectionObserverEntry = IntersectionObserverEntry
            }

        function IntersectionObserverEntry(entry) {
            this.time = entry.time,
                this.target = entry.target,
                this.rootBounds = entry.rootBounds,
                this.boundingClientRect = entry.boundingClientRect,
                this.intersectionRect = entry.intersectionRect || getEmptyRect(),
                this.isIntersecting = !!entry.intersectionRect;
            var targetRect = this.boundingClientRect
                , targetArea = targetRect.width * targetRect.height
                , intersectionRect = this.intersectionRect
                , intersectionArea = intersectionRect.width * intersectionRect.height;
            this.intersectionRatio = targetArea ? Number((intersectionArea / targetArea).toFixed(4)) : this.isIntersecting ? 1 : 0
        }

        function IntersectionObserver(callback, opt_options) {
            var fn, timeout, timer, options = opt_options || {};
            if ("function" != typeof callback)
                throw new Error("callback must be a function");
            if (options.root && 1 != options.root.nodeType)
                throw new Error("root must be an Element");
            this._checkForIntersections = (fn = this._checkForIntersections.bind(this),
                    timeout = this.THROTTLE_TIMEOUT,
                    timer = null,
                    function () {
                        timer = timer || setTimeout(function () {
                            fn(),
                                timer = null
                        }, timeout)
                    }
            ),
                this._callback = callback,
                this._observationTargets = [],
                this._queuedEntries = [],
                this._rootMarginValues = this._parseRootMargin(options.rootMargin),
                this.thresholds = this._initThresholds(options.threshold),
                this.root = options.root || null,
                this.rootMargin = this._rootMarginValues.map(function (margin) {
                    return margin.value + margin.unit
                }).join(" ")
        }

        function addEvent(node, event, fn, opt_useCapture) {
            "function" == typeof node.addEventListener ? node.addEventListener(event, fn, opt_useCapture || !1) : "function" == typeof node.attachEvent && node.attachEvent("on" + event, fn)
        }

        function removeEvent(node, event, fn, opt_useCapture) {
            "function" == typeof node.removeEventListener ? node.removeEventListener(event, fn, opt_useCapture || !1) : "function" == typeof node.detatchEvent && node.detatchEvent("on" + event, fn)
        }

        function getBoundingClientRect(el) {
            var rect;
            try {
                rect = el.getBoundingClientRect()
            } catch (err) {
            }
            return rect ? (rect.width && rect.height || (rect = {
                top: rect.top,
                right: rect.right,
                bottom: rect.bottom,
                left: rect.left,
                width: rect.right - rect.left,
                height: rect.bottom - rect.top
            }),
                rect) : getEmptyRect()
        }

        function getEmptyRect() {
            return {
                top: 0,
                bottom: 0,
                left: 0,
                right: 0,
                width: 0,
                height: 0
            }
        }

        function containsDeep(parent, child) {
            for (var node = child; node;) {
                if (node == parent)
                    return !0;
                node = getParentNode(node)
            }
            return !1
        }

        function getParentNode(node) {
            var parent = node.parentNode;
            return parent && 11 == parent.nodeType && parent.host ? parent.host : parent && parent.assignedSlot ? parent.assignedSlot.parentNode : parent
        }
    }(),
    function ($) {
        $.fn.isInViewport = function (options) {
            var elementTop = $(this).offset().top
                , elementBottom = elementTop + $(this).outerHeight()
                , viewportTop = $(window).scrollTop()
                , viewportBottom = viewportTop + $(window).height()
                , settings = $.extend({
                threshold: 0
            }, options);
            return elementBottom - options.threshold > viewportTop && elementTop + settings.threshold < viewportBottom
        }
    }(jQuery),
    document.addEventListener("DOMContentLoaded", function () {
        var imageObserver = new IntersectionObserver(function (entries, imgObserver) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var lazyImage = entry.target;
                        lazyImage.srcset = lazyImage.dataset.srcset,
                            lazyImage.src = lazyImage.dataset.src,
                            lazyImage.classList.remove("lzy-img"),
                            imgObserver.unobserve(lazyImage)
                    }
                })
            }
        );
        [].slice.call(document.querySelectorAll("img.lzy-img")).forEach(function (v) {
            imageObserver.observe(v)
        })
    }),
    document.addEventListener("DOMContentLoaded", function () {
        var lazyVideos = [].slice.call(document.querySelectorAll("video.lazy"));
        if ("IntersectionObserver" in window) {
            var lazyVideoObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (video) {
                        video.isIntersecting && window.setTimeout(function () {
                            var playPromise = video.target.play();
                            void 0 !== playPromise && playPromise.then(function () {
                            }).catch(function (error) {
                            })
                        }, 3500)
                    })
                }
            );
            lazyVideos.forEach(function (lazyVideo) {
                lazyVideoObserver.observe(lazyVideo)
            })
        }
    }),
    function ($) {
        $(window).ready(function () {
            $("[data-muted]").on("click", function (event) {
                var video = $(this).parents(".full-video").find("video").get(0)
                    , muted = $(video).prop("muted");
                $(this).toggleClass("muted"),
                    $(video).prop("muted", function () {
                        return !muted
                    }),
                    $(this).attr("data-muted", !muted)
            }),
                $("[data-muted]").each(function () {
                    var video = $(this).parents(".full-video").find("video").get(0)
                        , muted = $(video).prop("muted");
                    $(this).attr("data-muted", !muted),
                    muted && $(this).addClass("muted")
                })
        });
    }(jQuery);
var MainNavigationTeam = function ($) {
    var MainNavigationTeam = function () {
        function MainNavigationTeam(element) {
            this._element = element,
                this._selector = ".flyout-navigation-team-toggle:not(#" + element.attr("id") + ")"
        }

        return MainNavigationTeam.prototype.toggle = function () {
            $(this._selector).removeClass("active"),
                this._element.toggleClass("active")
        }
            ,
            MainNavigationTeam
    }();
    return $(document).on("click", ".navbar-toggler.team", function () {
        var elem = $("#" + $(this).attr("aria-controls").toString());
        new MainNavigationTeam(elem);
        $(".flyout-container").find("li.flyout-item").removeClass("active open")
    }),
        $(document).on("click", ".flyout-item a", function () {
            var subElem = $(this);
            new MainNavigationTeam(subElem);
            $("a").attr("href") && ($(this).closest("ul").find("li.flyout-item").removeClass("active open"),
                $(this).closest("li").toggleClass("active open"))
        }),
        $(document).on("click", ".flyout-title-item a", function () {
            var subElem = $(this);
            new MainNavigationTeam(subElem);
            $(this).closest(".flyout-item").toggleClass("active open")
        }),
        $(document).on("scrolldirectionchange", function (evt, direction) {
            var navBarElement = $("nav.navbar");
            "down" === direction ? $("#navbarSupportedContent").hasClass("active") || navBarElement.addClass("hidden") : navBarElement.removeClass("hidden")
        }),
        MainNavigationTeam
}(jQuery)
    , MainNavigation = function ($) {
    var MainNavigation = function () {
        function MainNavigation(element) {
            this._element = element,
                this._selector = ".flyout-navigation-toggle:not(#" + element.attr("id") + ")"
        }

        return MainNavigation.prototype.toggle = function () {
            $(this._selector).removeClass("active"),
                this._element.toggleClass("active")
        }
            ,
            MainNavigation
    }();
    return $(document).on("click", '[data-toggle="collapse"]', function () {
        var elem = $("#" + $(this).attr("aria-controls").toString());
        new MainNavigation(elem).toggle(),
            $(this).toggleClass("collapsed")
    }),
        $(document).on("scrolldirectionchange", function (evt, direction) {
            var navBarElement = $("nav.navbar");
            "down" === direction ? $("#navbarSupportedContent").hasClass("active") || navBarElement.addClass("hidden") : navBarElement.removeClass("hidden")
        }),
        MainNavigation
}(jQuery);
!function ($) {
    $(window).ready(function () {
        var newsletterRegistrationForm = $("[data-newsletter-registration]")[0];
        newsletterRegistrationForm && ($(newsletterRegistrationForm).on("submit", function () {
            return 1 !== $(this).data("newsletter-registration-form-collapsed")
        }),
            $("[data-newsletter-registration] .newsletter-inputs").on("shown.bs.collapse", function () {
                $(newsletterRegistrationForm).data("newsletter-registration-form-collapsed", 0)
            }))
    })
}(jQuery),
    function ($) {
        $(window).ready(function () {
            var FormTextInput = $(".form--row .form--text");
            FormTextInput.on("focus", function () {
                $(this).hasClass("dirty") || ($(this).addClass("dirty"),
                    $(this).parent(".form--row").addClass("in"))
            }).on("blur", function () {
                $(this).hasClass("dirty") && "" === $(this).val().toString() && ($(this).removeClass("dirty"),
                    $(this).parent(".form--row").removeClass("in"))
            }),
                FormTextInput.each(function () {
                    0 < $(this).val().length && $(this).parent(".form--row").addClass("in")
                })
        })
    }(jQuery),
    function ($) {
        $(window).ready(function () {
            $(".carousel__play-button").on("click", function (event) {
                var video = $(this).find("video").get(0);
                $(this).removeClass("carousel__play-button"),
                    video.controls = !0,
                    video.play()
            })
        });
    }(jQuery);

(function ($) {
    var lastScrollTop = 0
        , direction = -1;
    $(window).scroll(function (event) {
        var scrollTop = $(this).scrollTop();
        $("nav.navbar").height() < scrollTop && (lastScrollTop < scrollTop ? 1 !== direction && (direction = 1,
            $(document).trigger("scrolldirectionchange", ["down"])) : 0 !== direction && (direction = 0,
            $(document).trigger("scrolldirectionchange", ["up"]))),
            lastScrollTop = scrollTop
    }),
        function () {
            function setVH() {
                if (!document.body.classList.contains("user-is-touching")) {
                    var vh = .01 * window.innerHeight;
                    document.documentElement.style.setProperty("--vh", vh + "px")
                }
            }

            document.addEventListener("DOMContentLoaded", setVH),
                window.addEventListener("resize", setVH),
                window.addEventListener("touchstart", function onFirstTouch() {
                    document.body.classList.add("user-is-touching"),
                        window.removeEventListener("touchstart", onFirstTouch, !1),
                        window.removeEventListener("resize", setVH, !1)
                }, !1)
        }(),
        $(window).ready(function () {
            $(".icon-share").on("click", function (event) {
                $(this).hide(),
                    $(".hide").hide(),
                    $(".social-icons").show()
            })
        }),
        $(window).ready(function () {
            $(".picture-teaser__play-button").on("click", function (event) {
                $(this).removeClass("picture-teaser__play-button");
                var shortVideo = $(this).find(".short-video")
                    , fullVideo = $(this).find(".full-video");
                shortVideo.css("display", "none"),
                    fullVideo.css("display", "block");
                var video = $(fullVideo).find("video").get(0);
                video.play(),
                (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) && video.requestFullscreen(),
                (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)) && video.webkitEnterFullscreen()
            })
        });


    var dataSpecs, CountUp = function (target, startVal, endVal, decimals, duration, options) {
        var self = this;
        if (self.version = function () {
            return "1.9.2"
        }
            ,
            self.options = {
                useEasing: !0,
                useGrouping: !0,
                separator: ",",
                decimal: ".",
                easingFn: function (t, b, c, d) {
                    return c * (1 - Math.pow(2, -10 * t / d)) * 1024 / 1023 + b
                },
                formattingFn: function (num) {
                    var x, x1, x2, x3, i, l;
                    if (num = num.toFixed(self.decimals),
                        x = (num += "").split("."),
                        x1 = x[0],
                        x2 = 1 < x.length ? self.options.decimal + x[1] : "",
                        self.options.useGrouping) {
                        for (x3 = "",
                                 i = 0,
                                 l = x1.length; i < l; ++i)
                            0 !== i && i % 3 == 0 && (x3 = self.options.separator + x3),
                                x3 = x1[l - i - 1] + x3;
                        x1 = x3
                    }
                    self.options.numerals.length && (x1 = x1.replace(/[0-9]/g, function (w) {
                        return self.options.numerals[+w]
                    }),
                        x2 = x2.replace(/[0-9]/g, function (w) {
                            return self.options.numerals[+w]
                        }));
                    return self.options.prefix + x1 + x2 + self.options.suffix
                },
                prefix: "",
                suffix: "",
                numerals: []
            },
        options && "object" == typeof options)
            for (var key in self.options)
                options.hasOwnProperty(key) && null !== options[key] && (self.options[key] = options[key]);
        "" === self.options.separator ? self.options.useGrouping = !1 : self.options.separator = "" + self.options.separator;
        for (var lastTime = 0, vendors = ["webkit", "moz", "ms", "o"], x = 0; x < vendors.length && !window.requestAnimationFrame; ++x)
            window.requestAnimationFrame = window[vendors[x] + "RequestAnimationFrame"],
                window.cancelAnimationFrame = window[vendors[x] + "CancelAnimationFrame"] || window[vendors[x] + "CancelRequestAnimationFrame"];

        function ensureNumber(n) {
            return "number" == typeof n && !isNaN(n)
        }

        window.requestAnimationFrame || (window.requestAnimationFrame = function (callback, element) {
                var currTime = (new Date).getTime()
                    , timeToCall = Math.max(0, 16 - (currTime - lastTime))
                    , id = window.setTimeout(function () {
                    callback(currTime + timeToCall)
                }, timeToCall);
                return lastTime = currTime + timeToCall,
                    id
            }
        ),
        window.cancelAnimationFrame || (window.cancelAnimationFrame = function (id) {
                clearTimeout(id)
            }
        ),
            self.initialize = function () {
                return !!self.initialized || (self.error = "",
                    self.d = "string" == typeof target ? document.getElementById(target) : target,
                    self.d ? (self.startVal = Number(startVal),
                        self.endVal = Number(endVal),
                        ensureNumber(self.startVal) && ensureNumber(self.endVal) ? (self.decimals = Math.max(0, decimals || 0),
                            self.dec = Math.pow(10, self.decimals),
                            self.duration = 1e3 * Number(duration) || 2e3,
                            self.countDown = self.startVal > self.endVal,
                            self.frameVal = self.startVal,
                            self.initialized = !0) : (self.error = "[CountUp] startVal (" + startVal + ") or endVal (" + endVal + ") is not a number",
                            !1)) : !(self.error = "[CountUp] target is null or undefined"))
            }
            ,
            self.printValue = function (value) {
                var result = self.options.formattingFn(value);
                "INPUT" === self.d.tagName ? this.d.value = result : "text" === self.d.tagName || "tspan" === self.d.tagName ? this.d.textContent = result : this.d.innerHTML = result
            }
            ,
            self.count = function (timestamp) {
                self.startTime || (self.startTime = timestamp);
                var progress = (self.timestamp = timestamp) - self.startTime;
                self.remaining = self.duration - progress,
                    self.options.useEasing ? self.countDown ? self.frameVal = self.startVal - self.options.easingFn(progress, 0, self.startVal - self.endVal, self.duration) : self.frameVal = self.options.easingFn(progress, self.startVal, self.endVal - self.startVal, self.duration) : self.countDown ? self.frameVal = self.startVal - (self.startVal - self.endVal) * (progress / self.duration) : self.frameVal = self.startVal + (self.endVal - self.startVal) * (progress / self.duration),
                    self.countDown ? self.frameVal = self.frameVal < self.endVal ? self.endVal : self.frameVal : self.frameVal = self.frameVal > self.endVal ? self.endVal : self.frameVal,
                    self.frameVal = Math.round(self.frameVal * self.dec) / self.dec,
                    self.printValue(self.frameVal),
                    progress < self.duration ? self.rAF = requestAnimationFrame(self.count) : self.callback && self.callback()
            }
            ,
            self.start = function (callback) {
                self.initialize() && (self.callback = callback,
                    self.rAF = requestAnimationFrame(self.count))
            }
            ,
            self.pauseResume = function () {
                self.paused ? (self.paused = !1,
                    delete self.startTime,
                    self.duration = self.remaining,
                    self.startVal = self.frameVal,
                    requestAnimationFrame(self.count)) : (self.paused = !0,
                    cancelAnimationFrame(self.rAF))
            }
            ,
            self.reset = function () {
                self.paused = !1,
                    delete self.startTime,
                    self.initialized = !1,
                self.initialize() && (cancelAnimationFrame(self.rAF),
                    self.printValue(self.startVal))
            }
            ,
            self.update = function (newEndVal) {
                self.initialize() && (ensureNumber(newEndVal = Number(newEndVal)) ? (self.error = "",
                newEndVal !== self.frameVal && (cancelAnimationFrame(self.rAF),
                    self.paused = !1,
                    delete self.startTime,
                    self.startVal = self.frameVal,
                    self.endVal = newEndVal,
                    self.countDown = self.startVal > self.endVal,
                    self.rAF = requestAnimationFrame(self.count))) : self.error = "[CountUp] update() - new endVal is not a number: " + newEndVal)
            }
            ,
        self.initialize() && self.printValue(self.startVal)
    };
    debug = !1;

    function switchUnit() {
        $(this).siblings().removeClass("active"),
            $(this).addClass("active");
        var parent = $(this).parents("[data-specs]")
            , unit = $(this).data("unit").toString()
            , links = parent.find("[data-scale-trigger]").find("a")
            , scaleLabel = parent.find("[data-scale-values]").data("scale-values");
        scaleLabel && (links[0].innerHTML = scaleLabel[unit][0],
            links[1].innerHTML = scaleLabel[unit][1]),
            setNumberAnimated(parent, !0)
    }

    function switchScale() {
        $(this).siblings().removeClass("active"),
            $(this).addClass("active"),
            setNumberAnimated($(this).parents("[data-specs]"), !0)
    }

    function setNumber(parent) {
        var state = parent.find("[data-scale-trigger] .active").data("index")
            , unit = parent.find("[data-unit-trigger] .active").data("unit")
            , velocityValues = parent.find("[data-velocity-values]").data("velocity-values");
        parent.find("[data-velocity-values]").text(formatState(velocityValues[unit][state]))
    }

    function formatState(num) {
        return num
    }

    function setNumberAnimated(parent, fast) {
        var state = parent.find("[data-scale-trigger] .active").data("index")
            , unit = parent.find("[data-unit-trigger] .active").data("unit")
            , velocityValues = parent.find("[data-velocity-values]").data("velocity-values")
            , limit = parseFloat(velocityValues[unit][state])
            , decimals = parent.find("[data-velocity-values]").data("decimals")
            , duration = !0 === fast ? 1 : 2;
        myTargetElement = $(parent).find(".spec-number span")[0],
            options = {
                useEasing: !0,
                useGrouping: !0,
                separator: ".",
                decimal: ",",
                easingFn: function (t, b, c, d) {
                    return c * (1 - Math.pow(2, -10 * t / d)) * 1024 / 1023 + b
                },
                prefix: "",
                suffix: "",
                numerals: []
            };
        var counter = new CountUp(myTargetElement, 0, limit, decimals, duration, options);
        counter.error ? console.error(counter.error) : counter.start()
    }

    var isAnimatedSpecs = !1
        , inViewStatus = []
        , inviewOptions = {
        threshold: 100
    };
    $(window).on("resize scroll", function () {
        0 < navigator.userAgent.indexOf("HeadlessChrome") || ((dataSpecs = dataSpecs || $("[data-specs]")).each(function (index, elem) {
            isAnimatedSpecs = 0 !== parseInt($(elem).find(".spec-number span").text()),
                inViewStatus[index] = $(elem).isInViewport(inviewOptions),
            $(elem).isInViewport(inviewOptions) && !isAnimatedSpecs && setNumberAnimated($(elem))
        }),
        debug && console.log(inViewStatus))
    }),
        $(document).ready(function () {
            (dataSpecs = $("[data-specs]")).each(function (index) {
                var unitTrigger = $(this).find("[data-unit-trigger]")
                    , scaleTrigger = $(this).find("[data-scale-trigger]");
                scaleTrigger && scaleTrigger.on("click", "a[href]", switchScale),
                unitTrigger && unitTrigger.on("click", "a[href]", switchUnit)
            }),
                $(window).trigger("resize")
        });
})(jQuery);