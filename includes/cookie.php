<?php
/**
 * Bookmark cookie read/write helpers.
 *
 * @package EasyBookmark
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'esbmrk_set_bookmark_cookie' ) ) {
	/**
	 * Writes the bookmark cookie with a consistent lifetime and safe flags.
	 *
	 * Every write uses the same expiry: previously adding an item set a year
	 * while removing one set 30 days, so a single removal silently shortened
	 * the whole bookmark list's lifetime.
	 *
	 * @param string   $value   JSON-encoded post IDs.
	 * @param int|null $expires Optional explicit expiry timestamp.
	 * @return void
	 */
	function esbmrk_set_bookmark_cookie( $value, $expires = null ) {
		setcookie(
			'easy_bookmarked',
			$value,
			array(
				'expires'  => null === $expires ? time() + YEAR_IN_SECONDS : $expires,
				'path'     => COOKIEPATH ? COOKIEPATH : '/',
				'domain'   => COOKIE_DOMAIN ? COOKIE_DOMAIN : '',
				'secure'   => is_ssl(),
				'httponly' => true, // Only ever read server-side; the JS uses AJAX.
				'samesite' => 'Lax',
			)
		);
	}
}

if ( ! function_exists( 'esbmrk_get_bookmark_cookie_ids' ) ) {
	/**
	 * Reads the bookmark cookie and returns clean integer post IDs.
	 *
	 * sanitize_text_field() mangles JSON (it strips tags and encodes
	 * characters), and stripslashes() after wp_unslash() unslashes twice, so
	 * the raw value is decoded and then hardened by casting every entry.
	 *
	 * @return int[]
	 */
	function esbmrk_get_bookmark_cookie_ids() {
		if ( ! isset( $_COOKIE['easy_bookmarked'] ) ) {
			return array();
		}

		$decoded = json_decode( wp_unslash( $_COOKIE['easy_bookmarked'] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- decoded then cast to int below.

		if ( ! is_array( $decoded ) ) {
			return array();
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $decoded ) ) ) );
	}
}
