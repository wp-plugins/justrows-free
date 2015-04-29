<?php
/**
 *
 * Plugin Name: JustRows FREE
 * Plugin URI: http://www.canaveralstudio.com
 * Description: Used to create a justified image gallery
 * Version: 0.1
 * Author: Canaveral Studio
 * Author URI: http://www.canaveralstudio.com
 *
 */

// Constants
if (!defined('JUSTROWS_FILE'))
	define('JUSTROWS_FILE', __FILE__);

if (!defined('JUSTROWS_INCLUDE_PATH'))
	define('JUSTROWS_INCLUDE_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);

if (!defined('JUSTROWS_ADMIN_PATH'))
	define('JUSTROWS_ADMIN_PATH', JUSTROWS_INCLUDE_PATH . 'admin' . DIRECTORY_SEPARATOR);

if (!defined('JUSTROWS_CSS_URL'))
	define('JUSTROWS_CSS_URL', plugin_dir_url( __FILE__ ) . 'css/');

if (!defined('JUSTROWS_JS_URL'))
	define('JUSTROWS_JS_URL', plugin_dir_url( __FILE__ ) . 'js/');

if (!defined('JUSTROWS_LAYOUTS_URL'))
	define('JUSTROWS_LAYOUTS_URL', plugin_dir_url( __FILE__ ) . 'layouts/');

if (!defined('JUSTROWS_LAYOUTS'))
	define('JUSTROWS_LAYOUTS', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR);

// Inclusions
include_once JUSTROWS_INCLUDE_PATH . 'JustRows.php';
include_once JUSTROWS_INCLUDE_PATH . 'JustRowsConfiguration.php';
include_once JUSTROWS_INCLUDE_PATH . 'JustRowsWidget.php';
if ( is_admin() ) include_once JUSTROWS_INCLUDE_PATH . 'JustRowsBackend.php';

// Register activation
register_activation_hook( JUSTROWS_FILE, array('JustRows', 'activate') );

// Register uninstall function
register_uninstall_hook( JUSTROWS_FILE, array('JustRows', 'uninstall') );

// JustRows instance
$justrows = new JustRows();

// Backend / Frontend implementations
if ( is_admin() ) {

	// Create backend GUI
	$justrowsBackend = new JustRowsBackend();

}
else {

	add_action('wp_enqueue_scripts', 'JustRows::doStyle');

	// Array of dinamically generated scripts to put in the footer
	$justrowsFooterScripts = array();

	// Register shortcode
	function justrows_shortcode($atts) {
		global $justrows;
		global $justrowsFooterScripts;
	
		// Get shortcode parameters
		extract(shortcode_atts(
			array(
				'slug' => '',
				'getkey' => false // Indicates if configuration SLUG must be read from url parameter or not (false)
			), 
			$atts
		));
	
		// Get slug from GET parameter if given
		if ( !empty($getkey) && !empty($_GET[$getkey]) )
			$slug = $_GET[$getkey];

		// Load JustRows configuration (if no slug given, use default configuration)
		if ( !empty($slug) )
			$justrows->loadConfig($slug);
		else 
			$justrows->loadConfig();
	
		// Add JustRows gallery script at the end of body
		$justrowsFooterScripts[] = $justrows->doScript();
	
		// Return JustRows gallery markup
		return $justrows->doMarkup();
	}
	add_shortcode('justrows', 'justrows_shortcode');

	// Add dinamically generated scripts to the footer
	function justrows_footer_scripts() {
		global $justrowsFooterScripts;
	
		if (!empty($justrowsFooterScripts)) {
			echo '<script type="text/javascript">//<![CDATA[';
			foreach($justrowsFooterScripts as $script) {
				echo $script;
			}
			echo '//]]></script>';
		}
	}
	add_action('wp_footer', 'justrows_footer_scripts', 100);

}

// AJAX frontend functions
add_action( 'wp_ajax_jr_get_elements', array($justrows, 'ajaxGetElements') );
add_action( 'wp_ajax_nopriv_jr_get_elements', array($justrows, 'ajaxGetElements') );
add_action( 'wp_ajax_jr_get_info', array($justrows, 'ajaxGetInfo') );
add_action( 'wp_ajax_nopriv_jr_get_info', array($justrows, 'ajaxGetInfo') );
