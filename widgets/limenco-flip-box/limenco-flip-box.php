<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Limenco_Flip_Box_Widget extends SiteOrigin_Widget {

	function __construct() {
		parent::__construct(
			'limenco-flip-box',
			__( 'Limenco Flip Box', 'limenco-widgets-bundle' ),
			array(
				'description' => __( 'A responsive flip box widget with repeater and advanced controls.', 'limenco-widgets-bundle' ),
				'help'        => 'https://limenco.com/',
				'icon'        => 'dashicons-image-flip-horizontal',
			),
			array(), // Control options
			false, // Base folder path (default false: auto-detect)
			plugin_dir_path( __FILE__ ) . 'tpl'
		);
	}

	function initialize() {
		// Register CSS & JS; only enqueue when rendering widget.
		wp_register_style( 'limenco-flip-box', plugin_dir_url( __FILE__ ) . 'styles/limenco-flip-box.css', array(), '1.0' );
		wp_register_script( 'limenco-flip-box', plugin_dir_url( __FILE__ ) . 'js/limenco-flip-box.js', array('jquery'), '1.0', true );
	}

	function get_widget_form() {
		return array(
			// Layout section
			'layout_section' => array(
				'type'    => 'section',
				'label'   => __( 'Layout', 'limenco-widgets-bundle' ),
				'hide'    => false,
				'fields'  => array(
					'columns_desktop' => array(
						'type'    => 'slider', 'label'   => __( 'Columns (Desktop)', 'limenco-widgets-bundle' ),
						'max'     => 6, 'min' => 1, 'default' => 3, 'integer' => true,
					),
					'columns_tablet' => array(
						'type'    => 'slider', 'label'   => __( 'Columns (Tablet)', 'limenco-widgets-bundle' ),
						'max'     => 4, 'min' => 1, 'default' => 2, 'integer' => true,
					),
					'columns_mobile' => array(
						'type'    => 'slider', 'label'   => __( 'Columns (Mobile)', 'limenco-widgets-bundle' ),
						'max'     => 2, 'min' => 1, 'default' => 1, 'integer' => true,
					),
					'row_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Row Alignment', 'limenco-widgets-bundle' ),
						'options' => array(
							'left'   => __( 'Left', 'limenco-widgets-bundle' ),
							'center' => __( 'Center', 'limenco-widgets-bundle' ),
							'right'  => __( 'Right', 'limenco-widgets-bundle' ),
						),
						'default' => 'left',
					),
					'gap' => array(
						'type'    => 'number',
						'label'   => __( 'Grid Gap (px)', 'limenco-widgets-bundle' ),
						'default' => 24,
					),
					'box_height' => array(
						'type'    => 'number',
						'label'   => __( 'Box Height (px, required)', 'limenco-widgets-bundle' ),
						'default' => 360,
					),
					'box_max_width' => array(
						'type'    => 'number',
						'label'   => __( 'Box Max Width (px, optional)', 'limenco-widgets-bundle' ),
						'default' => '',
					),
					'trigger' => array(
						'type'    => 'select',
						'label'   => __( 'Flip Trigger', 'limenco-widgets-bundle' ),
						'options' => array('hover' => 'Hover', 'click' => 'Click'),
						'default' => 'hover',
					),
					'equalize_height' => array(
						'type'    => 'checkbox',
						'label'   => __( 'Equalize Height', 'limenco-widgets-bundle' ),
						'default' => true,
					),
				),
			),
			// Items section (Repeater)
			'items' => array(
				'label' => __( 'Flip Boxes', 'limenco-widgets-bundle' ),
				'type' => 'repeater',
				'item_label' => array(
					'selector'     => '[id*="front_html"]',
					'update_event' => 'change',
					'value_method' => 'val',
				),
				'fields' => array(
					'flip_direction' => array(
						'type'    => 'select',
						'label'   => __( 'Flip Direction', 'limenco-widgets-bundle' ),
						'options' => array('up' => 'Up', 'down' => 'Down', 'left' => 'Left', 'right' => 'Right'),
						'default' => 'up',
					),
					'front_html' => array(
						'type'    => 'tinymce',
						'label'   => __( 'Front HTML', 'limenco-widgets-bundle' ),
						'default' => '<p class="qodef-front-title">Kevin Johnstone</p> <p style="text-align:center">Managing Director</p>',
					),
					'back_html' => array(
						'type'    => 'tinymce',
						'label'   => __( 'Back HTML', 'limenco-widgets-bundle' ),
						'default' => '<h2 style="text-align:center">Kevin Johnstone</h2> <h2 style="text-align:center">Managing Director</h2> <p style="text-align:center">I am a hands-on Director with an unwavering commitment to revolutionizing the flavour industry and making a lasting impact on the world... (truncated)</p> <p style="text-align:center">FAVOURITE FLAVOUR: PINEAPPLE</p>',
					),
					'custom_classes' => array(
						'type'    => 'text',
						'label'   => __( 'Custom Classes', 'limenco-widgets-bundle' ),
						'default' => 'team-card',
					),
					'custom_css' => array(
						'type'    => 'textarea',
						'label'   => __( 'Custom CSS (per-item)', 'limenco-widgets-bundle' ),
					),
					'aria_label' => array(
						'type'    => 'text',
						'label'   => __( 'Aria Label', 'limenco-widgets-bundle' ),
						'default' => '',
					),
				),
			),
			// Advanced section
			'advanced' => array(
				'type'    => 'section',
				'label'   => __( 'Advanced', 'limenco-widgets-bundle' ),
				'hide'    => true,
				'fields'  => array(
					'instance_custom_css' => array(
						'type'    => 'textarea',
						'label'   => __( 'Instance Custom CSS', 'limenco-widgets-bundle' ),
					),
					'flip_duration' => array(
						'type'    => 'number',
						'label'   => __( 'Flip Duration (ms)', 'limenco-widgets-bundle' ),
						'default' => 500,
					),
					'flip_perspective' => array(
						'type'    => 'number',
						'label'   => __( 'Perspective (px)', 'limenco-widgets-bundle' ),
						'default' => 1000,
					),
				),
			),
		);
	}

	// Implement get_template_variables(), widget(), etc. in later steps.
}
add_action('widgets_init', function(){
	if ( class_exists( 'SiteOrigin_Widget' ) ) {
		siteorigin_widget_register( 'limenco-flip-box', __FILE__, 'Limenco_Flip_Box_Widget' );
	}
});
