<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index,follow"/>
    <link rel="preload" href="/wp-content/themes/wpp-brabus/assets/css/fonts.css?ver=1&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/wp-content/themes/wpp-brabus/assets/css/fonts.css?ver=1&display=swap"></noscript>
	<?php wp_head(); ?>
	<?php require_once 'styles.php'; ?>
    <!--<script src="//code.jivosite.com/widget/5a6zI9Z9pd" async></script>-->

<?php if(is_cart()) : ?>

<?php endif; ?>

</head>

<body <?php body_class(); ?>>
<header class="header">
	<?php wpp_br_get_header(); ?>
</header>
<main role="main">

