<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $product;
?>
<meta itemprop="sku" content="<?php echo $product->get_sku(); ?>">
<!--<meta itemprop="@id" content="<?php /*echo get_the_permalink( $post->ID ); */?>">-->
<section class="text-box-container">
    <div class="text-box-row">
        <div class="text-box" itemprop="description"><?php the_content( $post->ID ); ?>
        </div>
    </div>
</section>