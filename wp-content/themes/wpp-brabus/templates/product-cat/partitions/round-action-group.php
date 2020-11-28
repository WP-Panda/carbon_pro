<?php
/**
 * @package brabus.coms
 * @author  WP_Panda
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
$copy = sprintf( '<img class="wpp-trash-icon" src="%s" alt=""><span class="wpp-c-t">%s</span>', get_template_directory_uri() . '/assets/img/icons/link.svg', __( 'Item link', 'wpp-br' ) );


$copy  = sprintf( '<span class="wpp-action-icon wpp-copy-icon" style="background-image: url(%s)"></span><span class="wpp-c-t">%s</span>', get_template_directory_uri() . '/assets/img/icons/lists-icons.svg', __( 'Item link', 'wpp-br' ) );
$share = sprintf( '<span class="wpp-action-icon wpp-share-icon" style="background-image: url(%s)"></span><span class="wpp-c-t">%s</span>', get_template_directory_uri() . '/assets/img/icons/lists-icons.svg', __( 'Share Item', 'wpp-br' ) );


?>

<div class="wpp-action-groups" data-group="<?php echo $post['id']; ?>">
    <div class="wpp-right-nav-list">
        <span class="wpp-btn-more">...</span>
        <div class="wpp-acton-list" data-show="<?php echo $post['id']; ?>">
            <ul>
                <li class="wpp-share-post-li"><?php echo $share; ?>
                    <span class="copy-text">
                        <?php _e( 'Share this with friends', 'wpp-br' ); ?>
                    </span>
                </li>
                <li>
                    <?php do_action( 'wpp_wish_action_icon', $post['id'], $args['term_id'] ); ?>
                </li>
                <li class="wpp-copy-post-li"  data-clipboard-text="<?php echo get_the_permalink( $post['id'] ); ?>">
                    <?php echo $copy; ?>
                    <span class="copy-text">
                        <?php _e( 'Copy to Clipboard', 'wpp-br' ); ?>
                    </span>
                </li>
            </ul>
        </div>
        <div class="more-share-popup more-share-popup-2 has-arrow bot">
            <h3 class="tit">Share</h3>
            <a class="btn-back" style="display: none;">Back</a>
            <div class="frm">
                <div class="more-share-sns" class="more-share-sns">
                    <span class="via after">
                        <a href="https://www.facebook.com/sharer.php?u=<?php echo get_the_permalink( $post['id'] ); ?>"
                           class="fb" target="_blank">
                            <span class="ic-fb"></span>
                            <em>Share with Facebook</em>
                        </a>
                        <a href="https://twitter.com/share?text=<?php echo $post['title']; ?>&amp;url=<?php echo get_the_permalink( $post['id'] ); ?>&amp;via=carbon"
                           class="tw" target="_blank">
                            <span class="ic-tw"></span>
                            <em>Share with Twitter</em>
                        </a>
                        <a href="https://www.tumblr.com/share/link?url=<?php echo get_the_permalink( $post['id'] ); ?>&amp;name=<?php echo $post['title']; ?>&amp;description=<?php echo get_the_excerpt( $post['id'] ); ?>"
                           class="tb" target="_blank">
                            <span class="ic-tb"></span>
                            <em>Share with Tumblr</em>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>