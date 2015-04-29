<?php

// Block direct requests
if ( !defined('ABSPATH') ) die('-1');

// Include plugin.php to allow the use of is_plugin_active() function
include_once ABSPATH . 'wp-admin/includes/plugin.php';

// Register widget
add_action('widgets_init', function(){
	register_widget('JustRowsWidget');
});

// Widget class
class JustRowsWidget extends WP_Widget {

	public function __construct() {
		parent::__construct('justrowswidget', 'JustRows');
	}

	// Widget frontend display
	public function widget( $args, $instance ) {
		$this->instance = $instance;

		if ( !empty($instance['config']) ) {

			$title = apply_filters('widget_title', $instance['title'] );

			echo $args['before_widget'];

			// Display the widget title 
			if ( $title )
				 echo $args['before_title'] . $title .  $args['after_title'];

			echo do_shortcode('[justrows slug="'.$instance['config'].'"]');

			echo $args['after_widget'];
		}
		else
			echo '<p>',__('Error: you must choose a JustRows configuration.', 'justrows'),'</p>';
	}

	// Widget backend form
	public function form( $instance ) {

		$configSlugs = JustRows::getConfigurationSlugs();
		$configSlugs = array_values($configSlugs);
		$defaults = array(
			'title' => __('Gallery', 'justrows'),
			'config' => $configSlugs[0]
		);
		$instance = wp_parse_args( (array)$instance, $defaults );

		if (!empty($configSlugs)) {
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'justrows'); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'config' ); ?>"><?php _e('Configuration:', 'justrows'); ?></label>
				<select id="<?php echo $this->get_field_id( 'config' ); ?>" name="<?php echo $this->get_field_name( 'config' ); ?>" style="width:100%;">
					<?php
						foreach($configSlugs as $configSlug) {
							$config = new JustRowsConfiguration($configSlug);
							if ($configSlug == $instance['config'])
								echo '<option value="'.$configSlug.'" selected="selected">'.$config->getOption('name').'</option>';
							else
								echo '<option value="'.$configSlug.'">'.$config->getOption('name').'</option>';
						} 
					?>
				</select>
			</p>
		<?php 
		}
		else
			echo '<p>'.__('You must create a JustRows configuration first', 'justrows').'</p>';
	}
	
}
