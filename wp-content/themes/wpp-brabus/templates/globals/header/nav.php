<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="header__navigation header__navigation--main">
    <nav class="navigation navigation--main">
        <ul class="navigation__toggler-list">
            <li class="navigation__toggler-list-item">
                <button class="navigation__toggler-button collapsed" type="button"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
                        data-toggle="collapse">
                    <i class="icon icon-menu"></i>
                </button>
            </li>
        </ul>

		<?php wpp_get_template_part( 'templates/globals/aside/side', [] ); ?>
    </nav>
</div>
