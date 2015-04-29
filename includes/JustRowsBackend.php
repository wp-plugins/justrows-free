<?php

include_once JUSTROWS_INCLUDE_PATH . 'JustRows.php';
include_once JUSTROWS_INCLUDE_PATH . 'JustRowsConfiguration.php';

/*
	class JustRowsBackend {

		public function __construct();

		public function doAdminMenu();

		public function doAdminScripts();

		public function doConfigurationsIndexPage();

		public function doConfigurationAddPage();

		public function doConfigurationEditPage();

		private function createTaxonomiesArray( $postTypes );

	}
*/

class JustRowsBackend {

	public function __construct() {

		// Add custom menu entry to backend menu
		add_action('admin_menu', array($this, 'doAdminMenu') );

		// Add custom style and scripts to backend
		add_action('admin_enqueue_scripts', array($this, 'doAdminScripts') );

	}

	// Create backend menu entry
	public function doAdminMenu(){

		/* Quick reference:
			add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $output_function, $icon_url, $position );
			add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		*/
	
		// "Justrows" menu entry
		add_menu_page( 'JustRows Free', 'JustRows Free', 'activate_plugins', 'justrows-index', array($this, 'doConfigurationsIndexPage'),
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyMHB4IiBoZWlnaHQ9IjE1cHgiIHZpZXdCb3g9IjAgMCAyMCAxNSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjAgMTUiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxnPjxnPjxwYXRoIGZpbGw9IiM5OTk5OTkiIGQ9Ik04LjUxMywwLjg0OHY4Ljk0N2MwLDEuMzM5LTAuMDE0LDIuMi0wLjA0MiwyLjU4NGMtMC4wMjcsMC4zODItMC4xNTMsMC43MjktMC4zNzcsMS4wMzdzLTAuNTI3LDAuNTIzLTAuOTEsMC42NDFjLTAuMzgzLDAuMTItMC45NiwwLjE3OS0xLjczMSwwLjE3OUgxdi0yLjMzOWMyLjkxOCwwLjAxNSwzLjA3LDAuMDI0LDMuMTY0LDAuMDI0YzAuMjQ4LDAsMC40NDQtMC4wNjEsMC41ODctMC4xODJjMC4xNDQtMC4xMjIsMC4yMjctMC4yNzEsMC4yNDktMC40NDdjMC4wMjItMC4xNzYsMC4wMzMtMC41MTcsMC4wMzMtMS4wMjVWMC44NDhIOC41MTN6Ii8+PC9nPjwvZz48Zz48Zz48cGF0aCBmaWxsPSIjOTk5OTk5IiBkPSJNMTAuNDczLDAuODQ4aDMuMjE5YzEuNjQyLDAsMi43NTUsMC4wNjMsMy4zMzUsMC4xOWMwLjU4MiwwLjEyNywxLjA1NiwwLjQ1MSwxLjQyNCwwLjk3MkMxOC44MTYsMi41MywxOSwzLjM2MSwxOSw0LjUwMmMwLDEuMDQyLTAuMTI5LDEuNzQzLTAuMzg5LDIuMTAxYy0wLjI1OCwwLjM1OC0wLjc2OCwwLjU3NC0xLjUyOSwwLjY0NmMwLjY4OCwwLjE3MSwxLjE1MywwLjQsMS4zODksMC42ODZjMC4yMzgsMC4yODcsMC4zODUsMC41NDksMC40NDEsMC43OTFDMTguOTcxLDguOTYyLDE5LDkuNjIzLDE5LDEwLjcwNHYzLjUzMmgtMy4yMzJWOS43ODZjMC0wLjcxNi0wLjA1Ny0xLjE2LTAuMTctMS4zMzFjLTAuMTEzLTAuMTcxLTAuNDA5LTAuMjU3LTIuMzItMC4yNTd2Ni4wMzdoLTIuODA0VjAuODQ4eiBNMTMuMjc3LDMuMTM5djIuOTc2YzEuODIyLDAsMi4wOTUtMC4wNTQsMi4yNTUtMC4xNjFjMC4xNTUtMC4xMDcsMC4yMzUtMC40NTYsMC4yMzUtMS4wNDZWNC4xNzJjMC0wLjQyNS0wLjA3Ni0wLjcwMy0wLjIyOC0wLjgzNUMxNS4zODksMy4yMDUsMTUuMTExLDMuMTM5LDEzLjI3NywzLjEzOXoiLz48L2c+PC9nPjwvZz48L3N2Zz4=');
	
		// "Justrows -> Add new configuration" menu entry
		add_submenu_page( 'justrows-index', __('Add new configuration', 'justrows'), __('New configuration', 'justrows'), 'activate_plugins', 'justrows-add', array($this, 'doConfigurationAddPage') );
	
		// "Justrows -> Edit configuration" menu entry
		if (isset($_GET['page']) && $_GET['page'] == 'justrows-edit' && isset($_GET['slug'])) {
			add_submenu_page('justrows-index', __('Edit configuration', 'justrows'), __('Edit configuration', 'justrows'), 'activate_plugins', 'justrows-edit', array($this, 'doConfigurationEditPage'));
		}
	}

	// Load backend style and scripts
	public function doAdminScripts() {

		// CSS
		wp_register_style('justrows_admin_style', JUSTROWS_CSS_URL.'admin-style.min.css', false, '1.0.0' );
		wp_enqueue_style('justrows_admin_style');
	
		// JS
		wp_register_script( 'justrows-admin-script', JUSTROWS_JS_URL.'admin-script.min.js', array('jquery'));
		wp_enqueue_script( 'justrows-admin-script' );
	}

	// List configurations
	public function doConfigurationsIndexPage() {

		if ( isset($_GET['action']) && ($_GET['action'] == 'trash') ) {
			 foreach($_GET['configs'] as $slug) {
				 JustRowsConfiguration::uninstall($slug) ;
			 }
		}

		// Access page settings
		global $submenu;
		$pageData = $submenu['justrows-index'][0];

		$configSlugs = JustRows::getConfigurationSlugs();
		$configs = array();

		foreach($configSlugs as $key => $slug) {

			$configuration = new JustRowsConfiguration( $slug );

			$configs[] = array(
				'slug' => $configuration->getOption('slug'),
				'name' => $configuration->getOption('name'),
				'postType' => $configuration->getOption('postType'),
				'orderBy' => $configuration->getOption('orderBy'),
				'orderByFieldName' => $configuration->getOption('orderByFieldName'),
				'order' => $configuration->getOption('order'),
				'postsLimit' => $configuration->getOption('postsLimit'),
				'appendMethod' => $configuration->getOption('appendMethod'),
				'layout' => $configuration->getOption('layout')
			);

		}

		require_once(JUSTROWS_ADMIN_PATH.'index.php');
	}

	// "Add Configuration" page
	public function doConfigurationAddPage() {

		// Access page settings 
		global $submenu;
		$pageData = array();
		foreach($submenu['justrows-index'] as $i => $menu_item) {
			if($submenu['justrows-index'][$i][2] == 'justrows-add') {
				$pageData = $submenu['justrows-index'][$i];
				break;
			}
		}

		$postTypes = get_post_types();
		$postTypesTaxonomies = $this->createTaxonomiesArray($postTypes);
		$layouts = JustRows::getLayouts();
		$configuration = array();

		// Save Settings
		if (isset($_POST['update_settings'])) {

			$configName = sanitize_text_field($_POST['config']['name']);
			$configSlug = sanitize_title($configName);

			$options = $_POST['config'];
			$options['name'] = $configName;
			$options['slug'] = $configSlug;

			$configuration = new JustRowsConfiguration();
			$configuration->setOptions( $options );

			// Save configuration (if valid)
			if ( $configuration->validates(true) ) {
				$configuration->save(true);
				echo '<div class="updated ">
					<p>'.__('Configuration saved!', 'justrows').'</p>
				</div>';
				require_once(JUSTROWS_ADMIN_PATH.'redirect.php');
			}
			else {
				echo '<div class="error">
					<p>'.__('Couldn\'t save...', 'justrows').'</p>
				</div>';
				require_once(JUSTROWS_ADMIN_PATH.'add.php');
			}

		}
		else {
			require_once(JUSTROWS_ADMIN_PATH.'add.php');
		}
	}

	// "Edit Configuration" page
	public function doConfigurationEditPage() {

		// Access page settings 
		global $submenu;
		$pageData = array();
		foreach($submenu['justrows-index'] as $i => $menu_item) {
			if($submenu['justrows-index'][$i][2] == 'justrows-edit') {
				$pageData = $submenu['justrows-index'][$i];
				break;
			}
		}

		$configSlug = $_GET['slug'];
		$postTypes = get_post_types();
		$postTypesTaxonomies = $this->createTaxonomiesArray($postTypes);
		$layouts = JustRows::getLayouts();
		$configuration = array();
		$editURL = '';

		// Save Settings
		if (isset($_POST['update_settings'])) {

			// Check if slug exists
			if ( JustRows::isExistingConfig($configSlug) ) {

				$newOptions = $_POST['config'];


				if (!isset($newOptions['destinationNewTab']))
					$newOptions['destinationNewTab'] = 0;

















				$configuration = new JustRowsConfiguration( $configSlug );

				$changeSlug = false;

				// Check if the configuration name has changed
				if ( $newOptions['name'] != $configuration->getOption('name') ) {

					$newOptions['name'] = sanitize_text_field( $newOptions['name'] );
					$newOptions['slug'] = sanitize_title( $newOptions['name'] );

					// Check if slug must be changed ($configSlug is the old slug)
					if ($configSlug != $newOptions['slug'] ) {
						$changeSlug = true;
					}
				}
				else {
					$newOptions['slug'] = $configSlug;
				}

				$configuration->setOptions($newOptions);

				// Validation
				$saved = false;
				if ( $configuration->validates($changeSlug) ) {
					$saved = $configuration->save();
				}

				// If the configuration with the new name has been saved, delete the old one ($configSlug is the old slug)
				if ($changeSlug && $saved) {
					JustRowsConfiguration::uninstall( $configSlug, true );
				}

				if ($saved)
					$editURL = admin_url('/admin.php?page=justrows-edit&saved=true&slug='.$newOptions['slug']);
				else
					$editURL = admin_url('/admin.php?page=justrows-edit&saved=false&slug='.$configSlug);
				wp_redirect($editURL);

			}
			else {
				//TODO: during edit, original configuration disappeared
				wp_redirect('/admin.php?page=justrows-index');
			}

		}
		else {
			$configuration = new JustRowsConfiguration( $configSlug );
			$editURL = admin_url('/admin.php?page=justrows-edit&noheader=true&slug='.$configSlug);
		}

		if ( isset($_GET['saved']) ) {
			if ($_GET['saved'] == 'true') {
				echo '<div class="updated ">
					<p>'.__('Configuration saved!', 'justrows').'</p>
				</div>';
			}
			if ($_GET['saved'] == 'false') {
				echo '<div class="error">
					<p>'.__('Couldn\'t save...', 'justrows').'</p>
				</div>';
			}
		}

		require_once(JUSTROWS_ADMIN_PATH.'edit.php');
	}

	// Get array of taxonomies for given post types
	private function createTaxonomiesArray($postTypes){
		$taxonomiesArray = array();

		foreach ($postTypes as $postType)
			$taxonomiesArray[$postType] = get_object_taxonomies($postType);

		return $taxonomiesArray;
	}

}