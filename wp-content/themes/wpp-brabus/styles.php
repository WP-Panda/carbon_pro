<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<style>
    .wpp-error {
        border-bottom: 1px solid red;
    }

    i.wpp-error-notis {
        color: red;
        font-weight: 600;
    }

    form#wpp-mc-subscribe img {
        height: 29px;
    }

    #wpp-mc-subscribe {
        margin-bottom: 50px;
    }

    .wpp-mc-fr.form--row {
        margin: 0;
    }

    .wpp-mc-fr .dirty {
        background-color: transparent;
    }

    select#mc-target {
        height: 100%;
    }

    .grid-teaser {
        padding-bottom: 0 !important;
    }

    li.wpp-del-post-li, li.wpp-ed-post-li {
        opacity: .5;
    }

    li.wpp-del-post-li:hover, li.wpp-ed-post-li:hover {
        opacity: 1;
    }

    .images-links {
        position: absolute;
        bottom: 100%;
        z-index: 3;
        margin-bottom: 94px;
    }

    .images-links ul {
        list-style: none;
    }

    .images-links li {
        float: left;
        border: none !important;
    }

    .images-links a {
        padding: 5px 10px;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
        color: #fff;
    }

    .images-links a:hover {
        background-color: rgba(0, 0, 0, 1);
    }

    h3.wpp-wl-title a {
        padding: 10px;
        color: #010101;
    }

    h3.wpp-wl-title a:hover {
        color: #ff000;
    }

    h3.wpp-wl-title a:first-of-type {
        padding-left: 0;
    }

    h3.wpp-wl-title a:last-of-type {
        padding-right: 0;
    }

    .wl-loading span.wpp-action-icon.wpp-wish-icon {
        background-image: url(/wp-content/themes/wpp-brabus/assets/img/icons/loader.svg) !important;
        background-position: 50%;
        background-size: 100%;
    }

    a {
        outline: none !important;
    }

    span.woocommerce-Price-amount.amount {
        font-size: 18px;
        line-height: 30px;
        color: #666;
    }

    /* .wpp-wish-wrap.out-wpp-wish {
		 position: absolute;
		 z-index: 99;
		 height: 40px;
		 width: 40px;
		 right: 4px;
		 top: 4px;
	 }*/

    .wpp-wish-wrap.out-wpp-wish img {
        width: 100%;
    }

    .row.pag-row {
        text-align: center;
        display: block;
        margin-bottom: 20px;
    }

    .wpp_scu., .cart-item-number {
        color: #fff !important;
    }

    .page-numbers {
        display: inline-block;
        min-width: 18px;
        border: 1px solid #010101;
        padding: 5px 10px 3px;
        text-align: center;
        color: #010101;
    }

    .page-numbers.current, .page-numbers:hover {
        background-color: #e4e4e4;
    }

    a.wpp-cat-tag-edit {
        margin-left: 15px;
    }

    a.wpp-cat-tag-edit img {
        height: 30px;
    }

    img.wpp-menu-logo-img {
        width: 25px;
        margin-right: 10px;
    }

    .flyout-body {
        padding-top: 0;
    }

    button#wpp-send-product-search {
        height: 40px;
        width: 40px;
        border: none;
        background-color: transparent;
        padding: 15px;
        transition: all .3s;
        outline: none;
    }

    input#product_search {
        width: calc(100% - 45px);
    }

    button#wpp-send-product-search:hover {
        transform: scale(1.2);
    }

    form.wpp-s-form {
        padding: 15px;
    }

    span.cart_total {
        font-size: 15px;
    }

    h5.product-list-header__caption {
        margin: 35px 0 25px;
        border-bottom: 1px solid #515151;
    }

    .product-attribute__value .icon {
        opacity: 0;
        position: absolute;
        right: 0;
        top: 0;
        transition: opacity 200ms ease-in-out;
    }

    .value select {
        display: none;
    }

    .single_variation_wrap {
        position: relative;
    }

    .woocommerce-variation-price {
        position: absolute;
        right: 0;
        top: -25px;
        font-size: 21px;
    }

    .variations_form .quantity {
        display: none !important;
    }

    .woocommerce-variation-sku {
        position: absolute;
        right: 0;
        top: -70px;
        font-size: 21px;
        color: #aaa;
    }

    section.text-box-container.pricer {
        padding-top: 0;
        padding-bottom: 0;
    }

    .wpp-noter {
        opacity: .5;
    }

    .product-attribute__image img {
        opacity: 1 !important;
    }

    .product-attribute__value.active img {
        opacity: 1 !important;
        border: 3px solid #00b9eb !important;
    }

    .single-product .quantity {
        display: none;
    }

    img.wpp-mu-im {
        display: none;
    }

    .swiper-slide img.wpp-mu-im {
        display: block;
    }

    img.wpp-mu-im.img-fluid.wpp-im-1 {
        display: block;
    }

    span.imgg-wp-bull.img-bull-w-5 {

        width: 20%;

    }

    span.imgg-wp-bull {
        height: 100%;
        position: absolute;
        top: 0;
    }

    .grid-teaser-image {
        position: relative;
    }

    .img-bull-w-5.img-wpp-1 {
        left: 0;
    }

    .img-bull-w-5.img-wpp-2 {
        left: 20%;
    }

    .img-bull-w-5.img-wpp-3 {
        left: 40%;
    }

    .img-bull-w-5.img-wpp-4 {
        left: 60%;
    }

    .img-bull-w-5.img-wpp-5 {
        left: 80%;
    }

    .img-bull-w-4.img-wpp-1 {
        left: 0;
    }

    .img-bull-w-4.img-wpp-2 {
        left: 25%;
    }

    .img-bull-w-4.img-wpp-3 {
        left: 50%;
    }

    .img-bull-w-4.img-wpp-4 {
        left: 75%;
    }

    .img-bull-w-3.img-wpp-1 {
        left: 0;
    }

    .img-bull-w-3.img-wpp-2 {
        left: 33.333%;
    }

    .img-bull-w-3.img-wpp-3 {
        left: 66.666%;
    }

    .img-bull-w-2.img-wpp-1 {
        left: 0;
    }

    .img-bull-w-2.img-wpp-2 {
        left: 50%;
    }

    .img-bull-w-2.img-wpp-1 {
        display: none;
    }

    /*span.imgg-wp-bull:before {content:'';height: 5px;background-color: #fff;position: absolute;width: 96%;z-index: 55555;display: block;margin: 0 2%;bottom: 10px;}

	span.imgg-wp-bull:hover:before {background-color:#00b9eb}
*/
    span.imgg-wp-bull.img-bull-w-1 {
        width: 100%;
    }

    span.imgg-wp-bull.img-bull-w-2 {
        width: 50%;
    }

    span.imgg-wp-bull.img-bull-w-3 {
        width: 33.33%;
    }

    span.imgg-wp-bull.img-bull-w-4 {
        width: 25%;
    }

    img.svg {
        height: 80px;
    }

    .wpp-error input, .wpp-error textarea, .wpp-error .form--checkbox label:before {
        border: 1px solid #fd0000 !important;
    }

    .wpp-error input, .wpp-error textarea, .wpp-error .form--checkbox label:before {
        border: 1px solid #fd0000 !important;
    }

    ul.sub-menu {
        margin-left: 20px;
    }

    nav.navbar.navbar-light.responsive-gutter.fixed-top {
        display: flex;
        align-items: center;
    }

    .wpp-list-img {
        position: relative;
    }

    .row.wpp-list-cat {
        margin-bottom: 30px;
        position: relative;
    }

    img.wpp-temp-icon {
        height: 25px;
    }

    a.navbar-bran img {
        height: 40px;
    }

    span.nav-item.wpp-swith-wrap {
        padding: 16px 0;
        text-align: right;
        max-width: 100px;
    }

    a.swith-item {
        margin-left: 10px;
    }

    img.wpp-temp-icon {
        height: 25px;
    }

    span.nav-item.wpp-swith-wrap {
        padding: 16px 0;
        text-align: right;
        max-width: 100px;
    }

    a.swith-item {
        margin-left: 10px;
    }

    .row.wpp-list-cat p {
        font-size: 18px;
    }

    .row.wpp-list-cat a {
        color: #010101;
    }

    .row.wpp-list-cat h4 {
        margin-bottom: 15px;
    }

    a.wpp-sort-prod img {
        width: 50px;
    }

    a.wpp-sort-prod {
        position: fixed;
        z-index: 999999;
        top: 50%;
        margin-top: -25px;
    }

    /* .grid-teaser .grid-teaser-image {
		 margin-bottom: 0 !important;
	 }*/

    .wpp-grid_imgs figcaption {
        position: relative;
        font-size: 13.5px;
        line-height: 18px;
        padding: 0 0 13px;
    }

    .wpp-action-groups {
        z-index: 2;
        position: absolute;
        width: 100%;
        padding: 0 10px 10px;
        box-sizing: border-box;
        display: block;
        bottom: 100%;
        opacity: 1;
        transition: opacity ease-in-out .1s;
        -webkit-transition: opacity ease-in-out .1s;
        -moz-transition: opacity ease-in-out .1s;
        -o-transition: opacity ease-in-out .1s;
    }

    .wpp-right-nav-list {
        float: right;
        position: relative;
        text-align: left;
    }

    .wpp-btn-more {
        opacity: 0;
        z-index: 0;
        display: block;
        width: 29px;
        height: 29px;
        background: #fff;
        border-radius: 100%;
        position: relative;
        text-align: center;
        box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.1);
        -webkit-transition: 0.1s ease-in-out;
        transition: 0.1s ease-in-out;
        cursor: pointer;
        font-size: 27px;
        letter-spacing: -1.5px;
        visibility: hidden;
    }

    .opened .wpp-btn-more, .opened-list .wpp-btn-more {
        opacity: 1;
        visibility: visible;
    }

    .wpp-btn-more:hover, .opened .wpp-btn-more, .opened-list .wpp-btn-more {
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
        background: #f0f0f0;
    }

    .wpp-acton-list {
        position: absolute;
        bottom: 36px;
        background: #fff;
        border-radius: 5px;
        animation: none;
        -webkit-animation: none;
        -moz-animation: none;
        width: 200px;
        margin-left: -100px;
        opacity: .3;
        visibility: hidden;
        padding: 7px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 0 1px rgba(0, 0, 0, 0.2);
        left: auto;
        right: 0;
    }

    .opened-list .wpp-acton-list {
        opacity: 1;
        visibility: visible;
        animation: up_pops 0.3s ease-out;
        -webkit-animation: up_pops ease-out 0.3s;
        -moz-animation: up_pops ease-out 0.3s;
    }

    .opened-list.opened-shared .wpp-acton-list {
        opacity: 0;
        visibility: hidden;
    }

    .grid-teaser .wpp-acton-list ul {
        margin: 0;
    }

    .grid-teaser .wpp-acton-list ul li {
        list-style: none;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 10px;
        margin-bottom: 0;
        border: none;
    }

    .wpp-acton-list img {
        width: 16px !important;
        margin-right: 20px;
        height: 16px !important;
    }

    .wpp-acton-list {
    }

    /* .wpp-acton-list li {
		 padding: 3px 6px;
	 }*/

    .wpp-acton-list li:hover {
        background-color: #eee;
    }

    span.copy-text {
        display: block;
        margin-left: 36px;
        font-size: 11px;
        color: #8a8f9c;
        font-weight: 300;
    }

    li.wpp-copy-post-li {
        cursor: pointer;
    }

    .wpp-close-list, .wpp-del-post-li, .wpp-ed-post-li, .wpp-wish-wrap, .wpp-share-post-li {
        cursor: pointer;
    }

    .more-share-popup.bot {
        opacity: 0;
        visibility: hidden;
        position: absolute;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 0 1px rgba(0, 0, 0, 0.2);
        width: 293px;
        margin-left: -148px;
        padding: 0px 3px;
        left: auto;
        right: 0;
        top: 30px;
        bottom: auto;
    }

    .opened-shared .more-share-popup.bot {
        opacity: 1;
        visibility: visible;
        animation: up_pops 0.3s ease-out;
        -webkit-animation: up_pops ease-out 0.3s;
        -moz-animation: up_pops ease-out 0.3s;
    }

    .tit {
        position: relative;
        display: block;
        border-bottom: 1px solid #f1f1f2;
        line-height: 44px;
        color: #414853;
        font-weight: 700;
        padding: 2px 13px 0;
        font-size: 14px;
        border: 0;
        text-transform: none;
    }

    .frm {
        display: block;
        position: relative;
    }

    .more-share-sns {
        position: relative;
    }

    .via {
        display: block;
        border-top: 1px solid #efefef;
        padding: 12px 13px;
    }

    .via a {
        display: inline-block;
        position: relative;
        width: 22px;
        height: 22px;
        vertical-align: middle;
        margin: 0 5px 0 0;
    }

    .via a em {
        display: none;
        position: absolute;
        width: auto;
        left: 50%;
        bottom: 30px;
        white-space: nowrap;
        color: #fff;
        background: #21262c;
        border-radius: 5px;
        line-height: 24px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        font-size: 11px;
        font-style: normal;
        font-weight: 700;
        padding: 2px 10px;
    }

    .via [class^="ic"] {
        background: url(/wp-content/themes/wpp-brabus/assets/img/icons/share.png) no-repeat;
        background-size: 300px 200px;
    }

    .via a [class^="ic"] {
        display: inline-block;
        width: 22px;
        height: 24px;
    }

    .via .ic-fb {
        background-position: -130px 0;
    }

    .via .ic-tw {
        background-position: -158px 0;
    }

    .via .ic-tb {
        background-position: -214px 0;
    }

    .via a:hover .ic-fb {
        background-position: -130px -30px;
    }

    .via a:hover .ic-tw {
        background-position: -158px -30px;
    }

    .via a:hover .ic-tb {
        background-position: -214px -30px;
    }

    .via a em:after /* #show-share .link a.copy-link-wrap em:after*/
    {
        content: '';
        border: 4px solid transparent;
        border-top-color: #21262c;
        position: absolute;
        left: 50%;
        top: 100%;
        margin-left: -4px;
    }

    .wpp-copy-post-li {
        border-top: 1px solid #ccc !important;
    }

    .via a:hover em {
        display: block;
    }

    .wpp-action-icon {
        width: 16px;
        height: 16px;
        float: left;
        margin-right: 20PX;
    }

    .wpp-share-icon {
        background-position: -20px -20px;
    }

    .wpp-copy-icon {
        background-position: -40px -40px;
    }

    .wpp-wish-icon {
        background-position: -20px -40px;
    }

    .in-wpp-wish .wpp-wish-icon {
        background-position: -60px -40px;
    }

    ul.admin-actions {
        position: absolute;
        bottom: 100%;
        margin: 0;
        display: block;
        margin-bottom: 50px;
    }

    .grid-teaser ul.admin-actions li {
        border: none;
        cursor: pointer;
    }

    ul.admin-actions li {
        float: left;
        margin: 0;
        cursor: pointer;
    }

    ul.admin-actions img {
        width: 30px;
        margin-right: 8px;
    }

    span.wpp-wl-indicator:before {
        background: url(/wp-content/themes/wpp-brabus/assets/img/icons/heart.svg) no-repeat center;
        content: '';
        width: 28px;
        height: 28px;
        position: absolute;
        margin-top: 15px;
        z-index: -1;
    }

    .wpp-wl-icon {
        width: 30px;
        height: 30px;
        display: inline-block;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
    }

    .wpp-wall-page.slick-slide {
        padding: 10px;
    }

    /**
	Wpp Fancy gall
	 */
    .wpp-fancy-gallery-thumb {
        width: 100%;
    }

    .wpp-fancy-box-gallery [class^="col-"] {
        margin: 0 !important;
        padding: 0;
    }

    @-webkit-keyframes up_pops {
        from {
            opacity: 0;
            -webkit-transform: translateY(5px);
            transform: translateY(5px)
        }
        to {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0)
        }
    }

    @keyframes up_pops {
        from {
            opacity: 0;
            -webkit-transform: translateY(5px);
            transform: translateY(5px)
        }
        to {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0)
        }
    }

    @-webkit-keyframes down_pops {
        from {
            opacity: 0;
            -webkit-transform: translateY(-5px);
            transform: translateY(-5px)
        }
        to {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0)
        }
    }

    @keyframes down_pops {
        from {
            opacity: 0;
            -webkit-transform: translateY(-5px);
            transform: translateY(-5px)
        }
        to {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0)
        }
    }

    li.vpp-cart-action {
        list-style: none;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 0;
        /* border: none; */
        line-height: 1.1em;
        min-height: 25px;
        /*border: 1px solid #eee;*/
        text-align: center;
        padding: 10px 15px;
        float: left;
    }

    li.vpp-cart-action a {
        color: #000;
        display: block;
        margin: 0 auto;
    }

    span.wpp-ca-li-icon {
        width: 16px;
        height: 16px;
        background-position: -40px -40px;
        display: block;
        margin-right: 5px;
        float: left;
        margin-top: -2px;
    }

    li.vpp-cart-action b {
        display: block;
        float: right;
    }

    a.dell_ass span, a.wpp-clear-cart span, .wpp-save-as-pdf-li span {
        background-repeat: no-repeat;
        background-position: 50%;
    }

    .vpp-cart-action:hover {
        background-color: #eee;
    }

    ul.wpp-al-row {
        margin: 0 auto;
    }

    .wpp-cart-saved-url {
        text-align: center;
    }

    .admin-bar header.header {
        margin-top: 23px;
    }

    span.wpp-cart-title {
        width: 100%;
        display: block;
        margin-bottom: 10px;
    }

    img.wpp-cart-att-img.wp-post-image {
        position: relative;
        width: 70px;
    }

    .wpp-add-cart-categ img, .wpp-add-cart-bundle img {
        width: 30px !important;
    }

    span.serv-icon {
        width: 30px;
        display: inline-block;
    }

    img.wpp-br-box-image {
        margin-left: -3px;
        margin-right: 8px;
        width: 30px;
    }

    span.wpp_br_time_text {
        color: #414853;
    }

    .cart_item_time .wpp_br_time_text {
        margin-left: 32px;
        font-size: 16px;
    }

    .wpp-br-time-p {
        margin-bottom: 5px;
    }

    .product-list-footer .wpp-br-time-p {
        font-size: 16px;
    }

    .product-list-footer .wpp-br-box-image {
        width: 25px;
        margin-right: 5px;
    }

    .product-list-footer span.wpp_br_time_text {
        color: #aaa;
    }

    .product-list-footer p.cart-item-number.wpp_scu {
        margin-bottom: 0;
    }

    ul.wpp_admin_bundles {
        padding-left: 0;
        border-top: 1px solid #eee;
        margin-top: 10px;
    }

    ul.wpp_admin_bundles li {
        list-style: none;
    }

    ul.wpp_admin_bundles label {
        font-weight: 100;
        font-size: 15px;
        margin-left: 10px;
    }

    .wpp_hidden {
        opacity: 0;
        visibility: hidden;
        display: none;
        height: 1px;
        width: 1px;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    a.wpp-add-cart-bundle.form--button-gray.single_add_to_cart_button {
        width: 40%;
        text-align: center;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        border: 0;
        cursor: pointer;
        display: inline-block;
        line-height: 30px;
        padding: 12px 0 8px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        transition: background-color .1s linear;
    }

    img.wpp-bundle-img {
        width: auto;
    }

    .wpp-b-img-4:nth-child(2n-1) {
        margin-left: 4%;
        margin-right: 4%;
    }

    img.wpp-b-img-4 {
        width: 44%;
        margin-top: 12px;
    }

    img.wpp-b-img-2 {
        width: 44%;
        margin-top: 12px
    }

    .wpp-b-img-2:nth-child(2n-1) {
        margin-left: 4%;
        margin-right: 4%;
    }

    img.wpp-bundle-img.wpp-b-img-3:nth-child(2n-1), img.wpp-bundle-img.wpp-b-img-3:nth-child(2n) {
        margin-top: 12px;
        width: 44%;
    }

    img.wpp-bundle-img.wpp-b-img-3:nth-child(2n-1) {
        margin-left: 4%;
        margin-right: 4%
    }

    img.wpp-bundle-img.wpp-b-img-3:nth-child(3n) {

        width: 92%;

    }

    img.wpp-b-img-9, img.wpp-b-img-8, img.wpp-b-img-7, img.wpp-b-img-7 {
        width: 29%;
    }

    img.wpp-bundle-img.wpp-b-img-9:nth-child(3n-2), img.wpp-bundle-img.wpp-b-img-8:nth-child(3n-2) {
        margin-left: 3.5%;
        margin-right: 3%;
    }

    img.wpp-bundle-img.wpp-b-img-9:nth-child(3n-1), img.wpp-bundle-img.wpp-b-img-8:nth-child(3n-1) {
        margin-right: 3%;
    }

    img.wpp-bundle-img.wpp-b-img-9, img.wpp-bundle-img.wpp-b-img-8, img.wpp-bundle-img.wpp-b-img-7, img.wpp-bundle-img.wpp-b-img-5 {
        margin-top: 12px;
    }

    img.wpp-bundle-img.wpp-b-img-4.wpp_b_more {
        box-shadow: 1px -1px 0 1px #f5f5f5, 3px -2px 0 2px #414246, 6px -5px 0 1px #f5f5f5, 8px -6px 0 2px #414246;
    }

    img.wpp-bundle-img.wpp-b-img-8:nth-child(6n) {
        margin-left: 3%;
    }

    img.wpp-b-img-8.wpp-b-1_2, img.wpp-b-img-7.wpp-b-1_2, img.wpp-b-img-5.wpp-b-1_2 {
        width: 45%;
    }

    img.wpp-b-img-5 {
        width: 29%;
    }

    img.wpp-bundle-img.wpp-b-img-6 {
        margin-top: 12px;
        width: 45%;
    }

    img.wpp-bundle-img.wpp-b-img-6:nth-child(2n-1) {
        margin-left: 3.5%;
        margin-right: 3%;
    }

    img.wpp-b-img-7.wpp-b-1_2:nth-of-type(1), img.wpp-b-img-7:nth-of-type(3), img.wpp-b-img-7.wpp-b-1_2:nth-of-type(6), img.wpp-b-img-5:nth-of-type(1), img.wpp-b-img-5.wpp-b-1_2:nth-of-type(4) {
        margin-right: 3%;
        margin-left: 3%;
    }

    img.wpp-b-img-7:nth-of-type(4), img.wpp-b-img-5:nth-of-type(4), img.wpp-b-img-5:nth-of-type(2) {
        margin-right: 3%;
    }

    img.wpp-bundle-img.wpp-b-img-9.wpp_b_more {
        box-shadow: 1px -1px 0 1px #f5f5f5, 3px -2px 0 2px #414246, 6px -5px 0 1px #f5f5f5, 8px -6px 0 2px #414246;
    }

    span.wpp-bundle-sale-ribon {
        position: absolute;
        background-color: rgb(54, 121, 222);
        z-index: 1;
        color: #fff;
        padding: 3px 10px 0;
        right: 0;
        box-shadow: -2px 2px 3px 0px #000;
    }

    small.wpp-cart-bundle-title {
        color: #aaa;
        font-weight: 100;
    }

    .wpp-bundle-sort-send {
        padding: 10px 8px;
        text-transform: uppercase;
        clear: both;
        display: block;
        text-align: center;
    }

    img.wpp_single_loader {
        height: 50px;
    }

    span.wpp-b-total {
        font-weight: 100;
        padding-right: 20px;
    }

    li.wpp_br_item:first-of-type > a.breadcrumb-item:before {
        background-image: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/home.svg');
        content: '';
        width: 17px;
        height: 17px;
        display: inline-block;
        padding-top: 10px;
        background-repeat: no-repeat;
        background-position: 50% 100%;
        background-size: 14px;
        margin-top: -5px;
    }

    .cart-item-price.dell-price {
        margin-top: -22px;
    }

    .cart-item-price.dell-price * {
        color: #aaa !important;
    }

    .promo-row {
        padding: 0;
        margin: 0;
    }

    .promo-50 {
        float: left;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 767px) {
        .product-attribute [class^="col-"] {
            width: 50%;
        }
    }

    .carousel .flickity-prev-next-button.background--light::before, header.header.background--light + main .slick-track h2.font-size-small,
    body.scroll-down header.header.background--dark span.woocommerce-Price-amount.amount,
    body.scroll-down header.header.background--dark .navigation__toggler-button.collapsed {
        color: #000;
    }

    header.header.background--dark span.woocommerce-Price-amount.amount,
    header.header.background--dark .navigation__toggler-button.collapsed,
    header.header.background--complex span.woocommerce-Price-amount.amount,
    header.header.background--complex .navigation__toggler-button.collapsed {
        color: #fff;
    }

    h4.product-list__caption {
        margin-bottom: 10px;
    }

    li.menu-item a {
        font-family: 'DINNextLT-Light', sans-serif;
        text-transform: uppercase;
    }

    ul.menu-maker-items-wrap, ul.menu-model-items-wrap {
        padding-left: 20px;
        display: none;
    }

    a.wpp-disabled, a.wpp-disabled:hover {
        color: #ccc;
    }

    a.menu-maker-trigger, a.menu-model-trigger {
        display: inline-block;
        width: 100%;
        position: relative;
        font-weight: 600;
    }

    a.menu-maker-trigger:after, a.menu-model-trigger:after {
        content: "";
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/plus-gray.svg') no-repeat center;
        width: 20px;
        height: 30px;
        position: absolute;
        right: 0px;
        margin-top: -7px;
    }

    a.menu-maker-trigger.wpp-mi-active:after, a.menu-model-trigger.wpp-mi-active:after {
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/minus-gray.svg') no-repeat center;
    }

    a.menu-model-trigger.wpp-disabled:after {
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/plus-light-gray.svg') no-repeat center;
    }

    .menu-model-items {
        margin-bottom: 48px;
    }

    /*.picture-teaser-container-m, .picture-teaser-container-m .picture-teaser-m, .picture-teaser-container-m .picture-teaser-content {
        min-height: auto;
    }*/
    .col-12.col-sm-12.col-md-6.teaser-newsletter picture {
        opacity: 1;
    }

    ul.wpp-footer-nav {
        list-style: none;
    }

    .text-box-container.text-box-tiny-container {
        padding-top: 0;
    }

    /**
        Слайдер стена
     */
    .more-slider-after {
        cursor: pointer;
    }

    .more-slider-after::before {
        content: "";
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
    }

    .more-slider-after:hover:before {
        background-color: rgba(38, 155, 247, 0.8);
    }

    .more-slider-after::after {
        content: "";
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/plus-gray.svg') no-repeat center;
        width: 50px;
        height: 50px;
        position: absolute;
        z-index: 9;
        background-color: #269bf7;
        left: 50%;
        border-radius: 50%;
        background-size: 25px;
        margin-left: -25px;
        top: 50%;
        cursor: pointer;
    }

    .more-slider-after.the-hide::after {
        content: "";
        background-image: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/minus-gray.svg');
    }

    .video-play a::after {
        content: "";
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/play.svg') no-repeat;
        width: 50px;
        height: 50px;
        position: absolute;
        z-index: 9;
        background-color: #269bf7;
        left: 50%;
        border-radius: 50%;
        background-size: 25px;
        margin-left: -25px;
        top: 50%;
        cursor: pointer;
        margin-top: -25px;
        background-position: 15px 50%;
    }

    .video-play:hover a:after {
        background-color: rgba(255, 255, 255, 1);
    }

    .more-slider-after:hover:after {
        background-color: rgba(255, 255, 255, 1);
    }

    span.learn-more-wrap {
        position: absolute;
        width: 100%;
        height: 100%;
        display: block;
        text-align: center;
        padding-top: 25%;
    }

    span.slider-more-text {
        color: #269bf7;
        font-weight: 100;
    }

    .more-slider-after:hover span.slider-more-text {
        color: #fff;
    }

    .wpp-hide {
        display: none;
    }

    .fancybox-container {
        z-index: 9999992 !important;
    }

    /** блок новинки */
    h2.main-title__headline {
        margin-bottom: 0;
        margin-top: 50px;
    }

    section.container-fluid.responsive-gutter.section-padding-large.homepage-grid-teaser.hone-news-productcts {
        padding-top: 40px;
        float: left;
    }

    .col-xl-2.col-lg-3.col-md-4.col-sm-6.wpp-news-block-front {
        padding: 0 20px;
    }

    .wpp-news-block-front h4.grid-headline-icon {
        font-size: 15px !important;
        line-height: 18px !important;
    }

    .wpp-news-block-front figure {
        margin-bottom: 20px !important;
    }

    .wpp-news-block-front span img {
        width: 20px !important;
        display: inline;
    }

    .wpp-news-block-front span {
        font-size: 15px !important;
    }

    .wpp-news-block-front p {
        margin-bottom: 0 !important;
    }

    .homepage-grid-teaser [class*='col'].wpp-news-block-front:last-child .grid-teaser {
        display: block;
    }

    .wpp-news-row {
        width: 100%;
        float: left;
        opacity: 0;
        transition: opacity 0.4s;
    }

    .wpp-news-row.is-selected {
        opacity: 1;
    }

    .flickity-prev-next-button {
        width: 100px;
        height: 100px;
        outline: none;
    }

    .wpp-show-all-news {
        width: auto;
        margin: 0 auto;
        padding: 10px 25px;
    }

    .filter-news-cats {
        /* width: 20%;*/
    }

    .filter-news-model {
        /* width: 20%;*/
    }

    form#wpp-form-filter, .wpp-form-filter {
        width: 100%;
        display: block;
        margin: 0 auto;
    }

    form#wpp-form-filter select, .wpp-form-filter select {
        margin: 0 10px;
    }

    @media (max-width: 575px) {
        form#wpp-form-filter select, .wpp-form-filter select {
            margin: 0;
        }
    }

    /**
    Добавить картинки
     */
    a.wpp-cat-img-edit {
        margin-left: 20px;
    }

    a.wpp-cat-img-edit img {
        width: 31px;
    }

    div#media-frame-title {
        display: none;
    }

    h2.media-frame-actions-heading.screen-reader-text {
        display: none;
    }

    button span.screen-reader-text {
        display: none;
    }

    .wpp-tag-img.col-12.col-sm-6.col-md-4.col-lg-3.col-xl-2 img {
        width: 100%;
    }

    .wpp-tag-img.col-12.col-sm-6.col-md-4.col-lg-3.col-xl-2 {
        margin-bottom: 15px;
    }

    .wpp-tag-img img {
        border: 1px solid #c7c7c7;
    }

    .wpp-ad-tools-remove img {
        border: none;
    }

    .wpp-ad-tools-remove {
        position: absolute;
        top: 5px;
        color: #fff;
        right: 5px;
        background-color: #ff0303;
        margin-right: 10px;
        width: 50px;
        text-align: center;
        font-size: 25px;
        line-height: 1.8;
        cursor: pointer;
        border-radius: 25px;
        border: 2px solid #000;
        z-index: 1;
    }

    .wpp-ad-tools-remove:hover {
        background-color: #fff;
    }

    .wpp-tag-img {
        cursor: move;
    }

    a.format-slider img {
        width: 40px;
        margin-left: 50px;
        margin-right: 10px;
    }

    .format-slider {
    }

    .format-greed img {
        width: 35px;
    }

    .pulse-wrap {
        background: rgba(0, 0, 0, 0.7);
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 99999999999;
    }

    .pulse {
        height: 100px;
        width: 200px;
        overflow: hidden;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }

    .copy-clipboard-row {
        display: none
    }

    .pulse:after {
        content: '';
        display: block;
        background: url('//carbon.pro/wp-content/themes/wpp-brabus/assets/img/icons/pulse.png') 0 0 no-repeat;
        /* background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 200px 100px" enable-background="new 0 0 200px 100px" xml:space="preserve"><polyline fill="none" stroke-width="3px" stroke="white" points="2.4,58.7 70.8,58.7 76.1,46.2 81.1,58.7 89.9,58.7 93.8,66.5 102.8,22.7 110.6,78.7 115.3,58.7 126.4,58.7 134.4,54.7 142.4,58.7 197.8,58.7 "/></svg>') 0 0 no-repeat;
		*/
        width: 100%;
        height: 100%;
        position: absolute;
        -webkit-animation: 1s pulse linear infinite;
        -moz-animation: 1s pulse linear infinite;
        -o-animation: 1s pulse linear infinite;
        animation: 1s pulse linear infinite;
        clip: rect(0, 0, 100px, 0);
        background-size: 100%;
    }

    .pulse:before {
        content: '';
        position: absolute;
        z-index: -1;
        left: 2px;
        right: 2px;
        bottom: 0;
        top: 16px;
        margin: auto;
        height: 3px;

    }

    img.global-shipping {
        width: 150px;
        margin: -40px auto 10px;
        display: block;
    }

    span.global-shipping-label {
        display: block;
        text-align: center;
    }

    ul.wpp-al-row {
        margin: 0 auto;
        padding: 0;
    }

    span.wpp-copy-btn {
        cursor: pointer;
    }

    span.wpp-copy-btn:hover img {
        filter: invert(0.5);
    }

    span.wpp-copy-btn img {
        transition: .3s all;
    }

    @-webkit-keyframes pulse {
        0% {
            clip: rect(0, 0, 100px, 0);
            opacity: 0.4;
        }
        4% {
            clip: rect(0, 66.66667px, 100px, 0);
            opacity: 0.6;
        }
        15% {
            clip: rect(0, 133.33333px, 100px, 0);
            opacity: 0.8;
        }
        20% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 1;
        }

        80% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 0;
        }

        90% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 0;
        }

        100% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 0;
        }
    }

    @keyframes pulse {
        0% {
            clip: rect(0, 0, 100px, 0);
        }
        4% {
            clip: rect(0, 66.66667px, 100px, 0);
        }
        15% {
            clip: rect(0, 133.33333px, 100px, 0);
        }
        20% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 1;
        }

        80% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 0;
        }

        90% {
            opacity: 0;
        }

        100% {
            clip: rect(0, 300px, 100px, 0);
            opacity: 0;
        }

    }

    form.wpp_sl_bundle.wpp_wrapper.row.wpp-sortable-ajax.ui-sortable {
        margin: 0 !important;
    }

    section.row.wpp-fancy-box-gallery {
        margin: 0;
    }

    /**
    Car for sale admin
     */
    img.sale-car-image {
        width: 40px;
    }

    .wpp-admin-car4sale, .wpp-admin-project {
        position: absolute;
        right: 0px;
        z-index: 1;
        top: 15px;
        background-color: #18c139;
        padding: 5px;
        cursor: pointer;
    }

    .wpp-admin-project {
        right: 60px;
    }

    .wpp-admin-car4sale:hover, .wpp-admin-project:hover {
        background-color: #98ffac;
    }

    .wpp-car4sale-modal, .wpp-project-modal {
        position: absolute;
        z-index: 4;
        width: 100%;
        height: 100%;
        background-color: #fff;
        border: 1px solid #eee;
        padding: 20px;
        display: none;
    }

    .wpp-car4sale-close, .wpp-project-close {
        position: absolute;
        right: 10px;
        top: 10px;
        opacity: .6;
        cursor: pointer;
    }

    .wpp-car4sale-close:hover, .wpp-project-close:hover {
        opacity: 1;
    }

    /**
    ИНФО БЛОКИ
     */
    span.wpp-info-block {
        text-align: center;
        width: 100%;
        display: inline-block;
        padding: 10px;
        margin: 10px 0;
    }

    .wpp-alert {
        border: 1px solid #ec4040;
        color: #ec4040;
    }

    select.wpp-select-2 {
        width: 100%;
    }

    span.wpp-fr-chamge-image {
        position: absolute;
        z-index: 2;
        background-color: #fff;
        top: 15px;
        width: 50px;
        right: 60px;
    }

    span.wpp-fr-chamge-image:hover {
        background-color: #eee;
    }

    span.select2.select2-container {
        width: 100% !important;
    }

    section.container-fluid.responsive-gutter.section-padding-large.accordion:nth-of-type(2) {
        margin-top: -111px;
        padding-top: 0;
    }

    .wpp-tun-title {
        padding-top: 60px !important;
    }

    section.wpp-title.wpp-miidle-title {
        margin-bottom: 60px;
        margin-top: 130px;
    }

    h2.main-title__headline.wpp-head_line {
        font-family: "DINNextLT-Light", sans-serif;
        text-transform: none;
        font-size: 50px;
    }

    .wpp-grid_imgs .more-slider-after::before {
        content: "";
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.8) !important;
        cursor: pointer;
        z-index: 2;
    }

    .wpp-grid_imgs span.learn-more-wrap {
        position: absolute;
        width: 100%;
        height: 100%;
        display: block;
        text-align: center;
        padding-top: 20%;
        z-index: 3;
    }

    .fullscreen-slider img {
        width: 100%;
        height: auto;
        height: calc(100vh - (110px - 2px));
        max-height: calc(100vh - (110px - 2px));
        object-fit: cover;
        background-color: #000;

    }

    .fullscreen-slider .row.picture-teaser-content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
        /* height: calc(100vh - (60px - 1px));*/
        width: 100%;
        opacity: 0;
        -webkit-transition: opacity .5s ease-in-out;
        -moz-transition: opacity .5s ease-in-out;
        -ms-transition: opacity .5s ease-in-out;
        -o-transition: opacity .5s ease-in-out;
        transition: opacity .5s ease-in-out;
    }

    .picture-teaser-container-xl img {
        width: 100%;
    }

    .hone-news-productcts .flickity-button {
        background: transparent;
    }

    .hone-news-productcts .flickity-prev-next-button {
        width: 60px;
        height: 60px;
        outline: none;
    }

    .wpp-static-grid-item {
        position: relative;
    }

    .wpp-static-grid-item:hover img {
        padding: 20px 20px 0;
        -moz-box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
        -webkit-box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
        box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
    }

    .wpp-static-grid-item.overlayed:hover img {
        padding: 0;
        box-shadow: none;
    }

    .wpp-static-grid-item:hover .grid-teaser-details.structured-content {
        display: block;
        position: absolute;
        background: #fff;
        z-index: 10;
        left: 0;
        right: 0;
        padding: 0 20px 20px;
        -moz-box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
        -webkit-box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
        box-shadow: 0px 20px 20px 1px rgba(0, 0, 0, 0.9);
    }

    .wpp-static-grid-item.overlayed:hover .grid-teaser-details.structured-content {
        position: absolute;
        height: 100%;
        top: 0;
        background-color: rgba(0, 0, 0, 0.72) !important;
        box-shadow: none;
    }

    .wpp-static-grid-item h4 {
        color: #000;
        font-size: 18px;
        line-height: 30px;
        font-weight: 900;
        margin: 30px 0 0;
        padding: 0;
    }

    .wpp-static-grid-item h4 {
        color: #000;
        font-size: 18px;
        line-height: 30px;
        font-weight: 900;
        margin: 30px 0 0;
        padding: 0;
    }

    .wpp-static-grid-item.overlayed h4 {
        bottom: 45%;
        position: absolute;
        color: #fff;
        width: 100%;
        text-align: center;
        margin-bottom: 18px;
        right: 0;
        left: 0;
    }

    .wpp-static-grid-item td, .wpp-static-grid-item p {
        color: #666;
        font-size: 18px;
    }

    .wpp-static-grid-item.overlayed p {
        color: #fff;
        position: absolute;
        bottom: 50%;
        text-align: center;
        display: block;
        width: 100%;
        right: 0;
        left: 0;
        margin-bottom: -30px;
    }

    .wpp-static-grid-item table {
        margin-top: 20px;
    }

    .wpp-form-row {
        float: left;
    }

    .object_id {
        position: fixed;
        top: 160px;
        background-color: #000;
        color: #fff;
        padding: 10px;
        font-size: 20px;
    }

    section.wpp-clear {
        clear: both;
        height: 60px;
    }

    .wpp_pr_searh.form--text.form--border {
        padding: 9px 19px 5px;
    }

    .wpp-static-grid-item.overlayed {
        display: none;
        padding: 0;
    }

    .cd-fail-message {
        width: 100%;
        text-align: center;
        text-transform: uppercase;
        font-size: 25px;
        margin: 0 auto 50px;
    }

    .admin-tools-panel {
        display: block;
        position: absolute;
        top: 150px;
        z-index: 1;
        background-color: #fff;
        right: 50px;
    }

    .single-project a.wpp-cat-slider-format img {
        margin: 10px;
    }

    a.add_all_products_to_cart {
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
        border: 0;
        cursor: pointer;
        display: inline-block;
        line-height: 30px;
        padding: 12px 25px 8px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        transition: background-color .1s linear;
        font-size: 20px
    }

    .home .wpp-static-grid-item.overlayed {
        display: block;
    }

    /*  .slider-nav .slick-current:before {
		  border-top: 2px solid #ff000;
		  box-sizing: border-box;
		  content: "";
		  position: absolute;
		  width: 100%;
	  }*/

    .slider-nav .slick-current {
        position: relative;
    }

    section.slider-nav {
        margin-top: -16px;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .slider-nav .slick-slide {
        cursor: pointer;
        /*width: 120px !important;*/
    }

    .wpp-dump-block {
        position: absolute;
        background-color: red;
        top: 341px;
        z-index: 999999;
        text-align: center;
        color: #fff;
        font-size: 25px;
        min-width: 100px;
        padding: 10px 0;
        line-height: 37px;
        font-weight: 600;
    }

    .modal-share-box ul li {
        display: block;
        font-size: 16px;
        font-weight: 100;
        padding: 10px 20px 6px;
        line-height: 16px;
        cursor: pointer;
    }

    span.share-target-icon img {
        width: 21px;
        margin-right: 10px;
        margin-top: -5px;
    }

    .modal-share-box {
        text-align: left;
        position: absolute;
        background: #fff;
        padding: 10px 0;
        box-shadow: 0px 0px 4px 2px #a5a5a5;
        z-index: 10000;
        min-width: 180px;
        display: none;
    }

    .share-overlay {
        background-color: rgba(255, 255, 255, 0.7);
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        z-index: 9999;
    }

    .modal-share-box ul {
        padding-left: 0;
    }

    .modal-share-box li:first-of-type {
    }

    .modal-share-box li:last-of-type {
    }

    .modal-share-box ul li:hover {
        background-color: #eee;
    }

    /*
		.progress-round__wrap {
			width: 80px;
			height: 80px;
			position: absolute;
			bottom: 150px;
			pointer-events: none;
			text-align: center;
			left: 50px;
		}

		.circle-bg {
			fill: rgba(255, 255, 255, 0);
			stroke: rgba(0, 0, 0, 0.05);
			stroke-width: 5;
			stroke-linecap: butt;
		}
		.circle-go {
			fill: rgba(255, 255, 255, 0);
			stroke: rgba(183, 8, 38, 0.75);
			stroke-width: 4;
			stroke-linecap: round;
		} */
    /*.circle-tx {
        fill: rgba(255, 255, 255, 0.6);
        stroke: rgba(0, 0, 0, 0.3);
        stroke-width: 1;
        font: 300 20px 'Arial';
        display: inline-block;
        width: 100%;
        text-anchor: middle;
    }
    */
    .cur-text {
        color: #fff;
        position: absolute;
        left: 30px;
        background: rgba(0, 0, 0, .2);
        padding: 7px 10px 5px;
        border-radius: 5px;
        cursor: default;
        font-size: 18px;
    }

    .slick-track {
        min-width: 100%;
    }

    .slider-nav .slick-track {
        min-width: 100%;
        height: 80px;
        overflow: hidden;
    }

    .wpp_hero figure.dark.picture-teaser--opaque:not(:first-child) {
        display: none;
    }

    .slider-nav figure {
        float: left;
    }

    .slider-nav {
        height: 80px;
        overflow: hidden;
    }

    .archive.tax-product_cat .wpp-static-grid-item.overlayed {
        display: block;
    }

    .fancybox-content {
        width: 100% !important;
        height: 100% !important;
        transform: none !important;
    }

    img.fancybox-image {
        object-fit: contain;
    }

    /*.myFancyBox .fancybox-thumbs {
        top: auto;
        width: auto;
        bottom: 0;
        left: 0;
        right: 0;
        height: 95px;
        padding: 10px 10px 5px 10px;
        box-sizing: border-box;
        background: rgba(0, 0, 0, 0.3);
    }*/

    /* .myFancyBox .fancybox-show-thumbs .fancybox-inner {
		 right: 0;
		 bottom: 95px;
	 }*/

    img.lg-object.lg-image {
        object-fit: cover;
        height: 100% !important;
        width: auto !important;
    }

    .lg-toolbar.lg-group {
        display: block;
        opacity: 1;
        width: 50px;
        right: 0;
        left: auto;
        color: #fff;
        background-color: #fff;
        top: 10px !important;
        transition: none;
    }

    .lg-toolbar.lg-group .lg-icon:after {
        color: #333;
    }

    .home button.navigation__toggler-button.collapsed {
        background: rgba(0, 0, 0, .2);
        border-radius: 5px;
    }

    .scroll-down button.navigation__toggler-button.collapsed {
        background: transparent
    }

    div#lg-counter {
        display: none;
    }

    .lg-thumb-outer.lg-grab {
        background-color: transparent;
    }

    .lg-outer .lg-thumb .lg-thumb-item {
        border: none !important;
        margin-right: 2px !important;
        border-radius: 0;
    }

    .lg-outer .lg-thumb .lg-thumb-item.active {
        border-top: 2px solid #ff000 !important;
    }

    .home button.navigation__toggler-button.collapsed i {
        color: #fff;
    }

    .home.scroll-down button.navigation__toggler-button.collapsed i {
        color: #333;
    }

    option:disabled {
        display: none;
    }

    span.progress {
        width: 0%;
        height: 4px;
        background-color: #ff000;
        display: none;
        position: absolute;
    }

    .slick-current span.progress {
        display: block;
    }

    .fullscreen-slider.slider-main.wpp_hero {
        overflow: hidden;
    }
</style>