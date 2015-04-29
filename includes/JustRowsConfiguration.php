<?php
/*
	class JustRowsConfiguration {

		private $settings_config_key;

		private $options;

		private $defaults;

		private $data;

		public $validation_errors;


		public function __construct( $slug = null );

		public function adminInit();

		public static function uninstall( $slug, $removeFromList = false );

		public function save();

		public function setOptions( $opts );

		public function getOption( $key );

		private function validates( $checkUniqueSlug = true );

	}
*/

class JustRowsConfiguration {

	// Key for Wordpress settings
	private $settings_config_key;

	// Configuration values
	private $options = array();

	// Default configuration values
	private $defaults = array(
		'slug' => 'default',

		'name' => 'Default',

		'postType' => 'post',
		'orderBy' => 'date',
		'orderByFieldName' => '',
		'order' => 'DESC',

		'filterBy' => 'none',
		'taxonomyName' => '',
		'taxonomyValue' => '',

		'postsLimit' => 6,
		'appendMethod' => 'button',
		'infiniteScrollingOffset' => 20,

		'captionSource' => 'image-descr',
		'captionField' => '',

		'layout' => 'classic',
		'rowsThumbSize' => 'medium',

		'animation' => 'fade',
		'animationDuration' => 1000,

		'usePanels' => false,

		'destinationUrl' => 'post',
		'destinationUrlField' => '',
		'destinationNewTab' => 1,

		'panelsShowThumb' => 1,
		'panelsThumbSize' => 'medium',
		'panelsShowTitle' => 1,
		'panelsShowExcerpt' => 1,
		'panelsShowBody' => 0,
		'panelsShowCustomContent' => 0,
		'panelsCustomContent' => '',

		'keepOpenAppend' => 1,
		'toggle' => 1,
		'expandTime' => 400,
		'scrollOnOpen' => 1,
		'scrollTime' => 600,
		'scrollOffset' => 50,

		'noJs' => ''
	);

	// User input
	private $data = array();
	public $validation_errors = array();

	public function __construct( $slug = null ) {

		// Register plugin options with the settings API
		//add_action('admin_init', array($this, 'adminInit'));

		// If slug given, load settings, else use defaults
		if ($slug != null) {
			$this->settings_config_key = JustRows::SETTINGS_CONFIG_PREFIX . $slug;
			$storedOptions = get_option($this->settings_config_key);
			if ($storedOptions)
				$this->options = $storedOptions;
			else
				$this->options = $this->defaults;
		}
		else {
			$this->setOptions($this->defaults);
		}

	}

	// Register plugin options with the settings API
	public function adminInit() {
		register_setting($this->settings_config_key, $this->settings_config_key);
	}

	// Uninstall: delete settings
	public static function uninstall($slug) {
		if ( JustRows::removeConfigFromList($slug) )
			delete_option( JustRows::SETTINGS_CONFIG_PREFIX.$slug );
	}

	// Save current Configuration in settings (and update configlist)
	public function save($isNewOption = false) {
		$saved = false;
		if ($isNewOption)
			$saved = add_option($this->settings_config_key, $this->options, '', 'no');
		else
			$saved = update_option($this->settings_config_key, $this->options);
		$slug =  $this->getOption('slug');
		if ($saved && !JustRows::isExistingConfig($slug))
			JustRows::addConfigToList($slug);
		return $saved;
	}

	// Sette function for options
	public function setOptions($opts) {
		$this->options = array_merge($this->options, $opts);
		$this->settings_config_key = JustRows::SETTINGS_CONFIG_PREFIX . $this->getOption('slug');
	}

	// Getter function for single option
	public function getOption($key) {
		return $this->options[$key];
	}

	// Input validation
	public function validates($checkUniqueSlug = true) {
		// Reset validation errors array
		$this->validationErrors = array();

		if ($checkUniqueSlug) {
			// Check if slug is empty
			if ($this->options['slug'] == '')
				$this->validationErrors['slug'][] = __('Missing configuration name.', 'justrows');

			// Check if configuration already exists
			if ( JustRows::isExistingConfig($this->options['slug']) )
				$this->validationErrors['slug'][] = __('Configuration already exists.', 'justrows');	
		}

		if (!empty($this->validationErrors))
			return false;

		return true;
	}

}
