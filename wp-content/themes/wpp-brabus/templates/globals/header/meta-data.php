<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="header__navigation header__navigation--meta">
    <nav class="navigation navigation--meta">

        <ul class="navigation__toggler-list">
			<?php if ( ! is_cart() ) : ?>
                <li class="navigation__toggler-list-item wpp-head-cart">
					<?php wpp_br_cart_link(); ?>
                </li>
			<?php endif; ?>
        </ul>

    </nav>
</div>