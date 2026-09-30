<?php
/**
 * Uninstall handler.
 *
 * The listing page is deliberately left in place: it is a published page the
 * site owner may have edited or linked to, so removing it would destroy their
 * content rather than the plugin's own.
 *
 * @package EasyBookmark
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'esbmrk_options' );
