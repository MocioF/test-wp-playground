<?php
/**
 * Test WP Playground
 *
 * @package   test-wp-playground
 * @author    Giuseppe Foti
 * @copyright Giuseppe Foti
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Test WP Playground
 * Text Domain: test-wp-playground
 * Domain Path: /languages
 * Plugin URI: https://github.com/MocioF/test-wp-playground/
 * Description: Test code to report issues to wordpress-playground (https://github.com/WordPress/wordpress-playground)
 * Version: 0.0.1
 * Author: Giuseppe Foti
 * Author URI: https://github.com/MocioF/
 * License: GPLv2 or later
 **/

class Test_Wp_Playground {

	private static function test_wp_remote_get( $test_url ) {
		$args = array(
			'timeout'            => 5,
			'reject_unsafe_urls' => false,
			'blocking'           => true,
			'sslverify'          => false,
		);

		$result = wp_remote_get(
			get_site_url() . $test_url,
			$args
		);
		return $result;
	}

	public static function show_results() {
		$output    = '';
		$test_urls = array(
			'/wp-admin/load-scripts.php?c=0&load%5Bchunk_0%5D=jquery-core,jquery-migrate,utils,wp-polyfill-inert,regenerator-runtime,wp-polyfill,wp-hooks&ver=6.5.2',
			'/wp-admin/js/theme-plugin-editor.js?ver=6.5.2',
			'/wp-includes/js/customize-base.js?ver=6.5.2',
		);

		$div_start = '<div id="output" style="border:1px solid black; display:block;">';
		$div_end   = '</div>';

		foreach ( $test_urls as $url ) {
			$test = self::test_wp_remote_get( $url );
			if ( is_wp_error( $test ) ) {
				$content  = '<p style="color: red;"><b>wp_remote_get fails with url:<br>' . get_site_url() . $url . '</b></p>';
				$content .= '<pre>' . var_export( $test, true ) . '</pre>';
			} else {
				$content = '<p style="color: green;">wp_remote_get works with url:<br>' . get_site_url() . $url . '</p>';
			}

			$output .= $div_start . $content . $div_end;
		}
		return $output;
	}
}

add_filter( 'the_content', array( 'Test_Wp_Playground', 'show_results' ) );
