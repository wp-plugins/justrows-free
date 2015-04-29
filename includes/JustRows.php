<?php
/*
	class JustRows {

		const SETTINGS_CONFIGLIST_KEY = 'justrows_free_configlist';
		const SETTINGS_CONFIG_PREFIX = 'justrows_free_config_';

		public $Configuration = null;


		public function __construct() {

		public function loadConfig( $slug = false );

		public static function doStyle();

		public function doMarkup();

		public function doScript();

		public function getElements( $page = 1 );

		public function ajaxGetElements();

		public function ajaxGetInfo();


		public static function activate();
	
		public static function uninstall();

		public static function getConfigurationSlugs();

		public static function addConfigToList( $slug );

		public static function removeConfigFromList( $slug );

		public static function isExistingConfig( $slug );

		public static function getLayouts();

	}
*/

class JustRows {

	// Setting keys
	const SETTINGS_CONFIGLIST_KEY = 'justrows_free_configlist';
	const SETTINGS_CONFIG_PREFIX = 'justrows_free_config_';

	// JustRowsConfiguration instance
	public $Configuration = null;


	public function __construct() {

	}

	// Load configuration (use defaults if no slug given)
	public function loadConfig($slug = false) {
		if (!$slug) $this->Configuration = new JustRowsConfiguration();
		else $this->Configuration = new JustRowsConfiguration( $slug );
	}

	// Return css
	public static function doStyle() {
		// Global CSS
		wp_register_style('justrows_style', JUSTROWS_CSS_URL.'jquery.justrows.min.css');
		wp_enqueue_style('justrows_style');
		wp_register_style('justrows_wordpress_style', JUSTROWS_CSS_URL.'justrows-wordpress.min.css');
		wp_enqueue_style('justrows_wordpress_style');

		// CSS for each layout
		$layouts = JustRows::getLayouts();
		foreach($layouts as $layout) {
			if (file_exists( JUSTROWS_LAYOUTS . $layout['slug'] . DIRECTORY_SEPARATOR . 'style.css' )) {
				wp_register_style( 'justrows_layout_' . $layout['slug'] . '_style', JUSTROWS_LAYOUTS_URL.$layout['slug'].'/style.css' );
				wp_enqueue_style( 'justrows_layout_' . $layout['slug'] . '_style' );
			}
		
		}
	}

	// Return markup for JustRows gallery
	public function doMarkup() {

		// Global JS
		if ( !wp_script_is('jquery') ) {
			wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
			wp_enqueue_script('jquery');
		}
		wp_register_script('justrows_imagesloaded_script', JUSTROWS_JS_URL.'imagesloaded.pkgd.min.js');
		wp_enqueue_script('justrows_imagesloaded_script');
		wp_register_script('justrows_spin_script', JUSTROWS_JS_URL.'spin.min.js');
		wp_enqueue_script('justrows_spin_script');
		wp_register_script('justrows_script', JUSTROWS_JS_URL.'jquery.justrows.min.js');
		wp_enqueue_script('justrows_script');
		wp_register_script('justrows_custom_script', JUSTROWS_JS_URL.'script.min.js');
		wp_enqueue_script('justrows_custom_script');

		// Layout JS
		$layout = $this->Configuration->getOption('layout');
		if (file_exists( JUSTROWS_LAYOUTS . $layout . DIRECTORY_SEPARATOR . 'script.js' )) {
			wp_register_script( 'justrows_layout_' . $layout . '_script', JUSTROWS_LAYOUTS_URL.$layout.'/script.js');
			wp_enqueue_script( 'justrows_layout_' . $layout . '_script');
		}

		// Return HTML markup
		$out = '<div id="justrows-'.$this->Configuration->getOption('slug').'" class="justrows-theme-'.$this->Configuration->getOption('layout').'">
			<div class="jr-container"></div>';

		switch( $this->Configuration->getOption('appendMethod') ){
			case 'infinite-scrolling':
				$out .= '<div class="jr-spinner" id="spinner-'.$this->Configuration->getOption('slug').'"></div>';
				break;
			case 'button':
				$out .= '<div class="jr-append-btn" id="jr-append-btn-'.$this->Configuration->getOption('slug').'">Append</div>';
				break;
			default: // button
				;
		}

		$out .= '<noscript>'.$this->Configuration->getOption('noJs').'</noscript>
			</div>';
		
		return $out;
	}

	// Return script block for specific JustRows gallery
	public function doScript() {

		$thumbSizeName = $this->Configuration->getOption('rowsThumbSize');
		$rowImagesHeight = 200;

		if( in_array( $thumbSizeName, array( 'thumbnail', 'medium', 'large' ) ) ){
			$rowImagesHeight = get_option( $thumbSizeName . '_size_h' );
		}
		else{
			global $_wp_additional_image_sizes;
			if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[$thumbSizeName] ) )
				$rowImagesHeight = $_wp_additional_image_sizes[$thumbSizeName]['height'];
		}

		$animation = $this->Configuration->getOption('animation');
		if ( $animation == 'false') $animation = 'false';
		else $animation = '"'.$animation.'"';

		$captionAttr = '"title"';
		if ( $this->Configuration->getOption('captionSource') == 'none' ) $captionAttr = 'false';

		$pluginToUse = 'justrows';

		$script = '
			jQuery(document).ready(function($) {

				justRowsOpts = {
					"animation"				: '.$animation.',
					"animation-duration"	: '.$this->Configuration->getOption('animationDuration').',
					"caption-attr"			: '.$captionAttr.',
					"max-row-height"		: '.$rowImagesHeight.'
				};

				$.extend( justRowsOpts, justRowsThemesConfig["'.$this->Configuration->getOption('layout').'"]["options"] );

				justRowsInstances["'.$this->Configuration->getOption('slug').'"] = new Array();
				justRowsInstances["'.$this->Configuration->getOption('slug').'"]["container"] = $("#justrows-'.$this->Configuration->getOption('slug').' .jr-container");
				if (typeof justRowsThemesConfig["'.$this->Configuration->getOption('layout').'"]["init-callback"] == "function") {
					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["container"].'.$pluginToUse.'(
						justRowsOpts,
						justRowsThemesConfig["'.$this->Configuration->getOption('layout').'"]["init-callback"]
					);
				}
				else
					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["container"].'.$pluginToUse.'(justRowsOpts);

				justRowsInstances["'.$this->Configuration->getOption('slug').'"]["next-page"] = 1;
				justRowsInstances["'.$this->Configuration->getOption('slug').'"]["complete"] = false;

				justRowsInstances["'.$this->Configuration->getOption('slug').'"]["loading"] = true;

				if ("'.$this->Configuration->getOption('appendMethod').'" == "infinite-scrolling") {

					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["append-method"] = "scroll";

					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["spinner-wrap"] = document.getElementById("spinner-'.$this->Configuration->getOption('slug').'");
					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["spinner"] = new Spinner();

					jQuery(window).on("scroll.justrows", function() {
						if (
							( $(window).scrollTop() >= ($(document).height() - '.$this->Configuration->getOption('infiniteScrollingOffset').') - $(window).height() )
							&&
							( ! justRowsInstances["'.$this->Configuration->getOption('slug').'"]["loading"] )
						) {
							justRowsInstances["'.$this->Configuration->getOption('slug').'"]["loading"] = true;
							justRowsInstances["'.$this->Configuration->getOption('slug').'"]["spinner"].spin( justRowsInstances["'.$this->Configuration->getOption('slug').'"]["spinner-wrap"] );
							jrAddElements("'.get_bloginfo('url').'","'.$this->Configuration->getOption('slug').'");
						}
					});
				}

				if ("'.$this->Configuration->getOption('appendMethod').'" == "button") {
					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["append-method"] = "button";

					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["append-button"] = $("#jr-append-btn-'.$this->Configuration->getOption('slug').'");

					justRowsInstances["'.$this->Configuration->getOption('slug').'"]["append-button"].click(function() {
						justRowsInstances["'.$this->Configuration->getOption('slug').'"]["loading"] = true;
						jrAddElements("'.get_bloginfo('url').'","'.$this->Configuration->getOption('slug').'");
					});
				}

				if ("'.$this->Configuration->getOption('appendMethod').'" == "none") {
				}

				jrAddElements("'.get_bloginfo('url').'","'.$this->Configuration->getOption('slug').'");

			});
		';
		return $script;
	}

	// Get elements from database
	public function getElements($page = 1) {

		$args = array ( 
			'post_type' => $this->Configuration->getOption('postType'),
			'post_status' => 'publish',
			'posts_per_page' => $this->Configuration->getOption('postsLimit'),
			'paged' => $page,
			'orderby' => $this->Configuration->getOption('orderBy'),
			'order' => $this->Configuration->getOption('order')
		);

		$orderByFieldName = trim( $this->Configuration->getOption('orderByFieldName') );
		if (
			( ($args['orderby'] == 'meta_value') || ($args['orderby'] == 'meta_value_num') )
			&& (!empty($orderByFieldName))
		) {
			$args['meta_key'] = $orderByFieldName;
		}

		if ($this->Configuration->getOption('filterBy') == 'taxonomy') {
			$taxonomy = trim( $this->Configuration->getOption('taxonomyName') );
			$taxonomySlug = trim( $this->Configuration->getOption('taxonomyValue') );
			if ( !empty($taxonomy) && !empty($taxonomySlug) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'slug',
						'terms' => $taxonomySlug
					)
				);
			}
		}

		$query = new WP_Query( $args );

		$output = '';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				if ( has_post_thumbnail() ) {
					$ajaxURL = admin_url( 'admin-ajax.php?action=jr_get_info&config='.$this->Configuration->getOption('slug').'&postID='.get_the_ID() );

					$thumbnailAttrs = array();
					if ($this->Configuration->getOption('captionSource') != 'none') {
						if ($this->Configuration->getOption('captionSource') == 'image-descr')
							$thumbnailAttrs['title'] = strip_tags(  get_post( get_post_thumbnail_id() )->post_content  );
						if ($this->Configuration->getOption('captionSource') == 'custom-field') {
							$captionField = trim( $this->Configuration->getOption('captionField') );
							if (!empty($captionField)) {
								$fieldValue = get_post_meta(get_the_ID(), $captionField, true); 
								if (!empty($fieldValue)) $thumbnailAttrs['title'] = strip_tags( $fieldValue );
							}
						}
					}

					$href = get_permalink();
					switch( $this->Configuration->getOption('destinationUrl') ) {
						case 'featured-img':
							$href = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
							break;
						case 'custom-field':
							$urlFieldName = trim( $this->Configuration->getOption('destinationUrlField') );
							if ( !empty($urlFieldName) )
								$href = get_post_meta(get_the_ID(), $urlFieldName, true);
								if (empty($href)) $href = '#';
							break;
					}

					$target = '';
					if ( $this->Configuration->getOption('destinationNewTab') )
						$target = ' target="_blank"';

					$output .= '<a href="'.$href.'"'.$target.' data-jr-post-id="'.get_the_ID().'" data-jr-ajax-url="'.$ajaxURL.'">'.get_the_post_thumbnail( get_the_ID(), $this->Configuration->getOption('rowsThumbSize'), $thumbnailAttrs ).'</a>';
				}
			}
		}
		
		wp_reset_query();
		return $output;
	}

	// Convenience AJAX wrapper for getElements method
	public function ajaxGetElements() {
		$this->loadConfig( $_REQUEST["config"] );
		echo $this->getElements($_REQUEST["page"]);
		die();
	}

/* -------------------------------------------------------------------------------
		Static functions
------------------------------------------------------------------------------- */

	// Activate: if no config, create default
	public static function activate() {
		$configurationSlugs = JustRows::getConfigurationSlugs();
		if ( empty($configurationSlugs) ) {
			delete_option(JustRows::SETTINGS_CONFIGLIST_KEY);
			add_option(JustRows::SETTINGS_CONFIGLIST_KEY, array(), '', 'no');
			$defaultConfig = new JustRowsConfiguration();
			$defaultConfig->save(true);
		}
	}

	// Uninstall: delete all settings
	public static function uninstall() {
		// Delete settings of all configurations
		foreach(JustRows::getConfigurationSlugs() as $slug)
			JustRowsConfiguration::uninstall($slug);

		// Delete "justrows_configlist" setting
		delete_option( JustRows::SETTINGS_CONFIGLIST_KEY );
	}

	// Get configuration slugs from settings
	public static function getConfigurationSlugs() {
		$configSlugs = get_option(JustRows::SETTINGS_CONFIGLIST_KEY);
		if (!$configSlugs) $configSlugs = array();
		return $configSlugs;
	}

	// Add configuration slug to list
	public static function addConfigToList($slug) {
		$configSlugs = JustRows::getConfigurationSlugs();
		$configSlugs[] = $slug;
		update_option(JustRows::SETTINGS_CONFIGLIST_KEY, $configSlugs);
	}

	// Remove configuration slug from list
	public static function removeConfigFromList($slug) {
		$configSlugs = JustRows::getConfigurationSlugs();
		foreach(JustRows::getConfigurationSlugs() as $key => $configSlug) {
			if ($slug == $configSlug){
				unset( $configSlugs[$key] );
				update_option(JustRows::SETTINGS_CONFIGLIST_KEY, $configSlugs);
				return true;
			}
		}
		return false;
	}

	// Check if given configuration slug is in list
	public static function isExistingConfig($slug) {
		foreach(JustRows::getConfigurationSlugs() as $key => $configSlug) {
			if ($slug == $configSlug){
				return true;
			}
		}
		return false;
	}

	// Search for layouts and return their information in an array
	public static function getLayouts() {

		$layouts = array();

		$dirHandle = opendir(JUSTROWS_LAYOUTS);

		// Loop: each subfolder of 'layouts' should be a skin
		while ( false !== ($entry = readdir($dirHandle)) ) {

			// Ignore '.' and '..' directories
			if ($entry == '.' || $entry == '..') continue;

			// If current entry is a directory, then assume it is a skin
			if ( is_dir( JUSTROWS_LAYOUTS.DIRECTORY_SEPARATOR.$entry ) ) {

				$skinDirectory = JUSTROWS_LAYOUTS.$entry.DIRECTORY_SEPARATOR;
				$styleFile = $skinDirectory.'style.css';
				$scriptFile = $skinDirectory.'script.js';

				// Check for all files
				if ( is_file($styleFile) && is_file($scriptFile) ) {

					// Open style.css in read mode
					$fp = fopen( $styleFile, 'r' );

					// Read first 8Kb of the file
					$file_data = fread( $fp, 8192 );

					// Get name from first comment
					$name = '';
					if (preg_match('%/\*(.*?)\*/%s', $file_data, $matches))
						$name = trim($matches[1]);

					// Close file
					fclose( $fp );

					// Add skin to the layouts array
					if ($name != '')
						$layouts[] = array(
							'name' => $name,
							'slug' => $entry
						);
				}

			} // end of is_dir condition

		} // end of subdirectories loop

		closedir($dirHandle);

		return $layouts;	
	}

}
