<?php
/*
Widget Name: Limenco Button Grid
Description: A button grid with full repeater/accordion UI.
Author: Limenco Design
Author URI: https://limencodesign.co.za
Widget URI: https://limencodesign.co.za
*/

if (!defined('ABSPATH')) exit;

class Limenco_Button_Grid_Widget extends SiteOrigin_Widget {
    public function __construct() {
        parent::__construct(
            'limenco-button-grid',
            __('Limenco Button Grid', 'limenco-widgets-bundle'),
            [
                'description' => __('A button grid with full repeater/accordion UI.', 'limenco-widgets-bundle'),
                'help' => 'https://limencodesign.co.za',
            ],
            [],
            false,
            plugin_dir_path(__FILE__)
        );
    }
    public function get_widget_form() {
        return [
            'buttons' => [
                'type' => 'repeater',
                'label' => __('Buttons', 'limenco-widgets-bundle'),
                'item_name' => __('Button', 'limenco-widgets-bundle'),
                'item_label' => [
                    'selector' => "[id*='text']",
                    'update_event' => 'change',
                    'value_method' => 'val',
                ],
                'fields' => [
                    'text' => [ 'type' => 'text', 'label' => __('Button Text', 'limenco-widgets-bundle'), 'default' => 'Click Me', ],
                    'url' => [ 'type' => 'link', 'label' => __('URL', 'limenco-widgets-bundle'), ],
                    'new_window' => [ 'type' => 'checkbox', 'label' => __('Open in new window', 'limenco-widgets-bundle'), ],
                    'icon_section' => [
                        'type' => 'section',
                        'label' => __('Icon', 'limenco-widgets-bundle'),
                        'fields' => [
                            'icon_selected' => [ 'type' => 'icon', 'label' => __('Icon', 'limenco-widgets-bundle') ],
                            'icon_color' => [ 'type' => 'color', 'label' => __('Icon Color', 'limenco-widgets-bundle') ],
                            'icon' => [ 'type' => 'media', 'label' => __('Image Icon', 'limenco-widgets-bundle'), 'description' => __('Replaces the icon with your own image icon.', 'limenco-widgets-bundle') ],
                            'icon_placement' => [
                                'type' => 'select',
                                'label' => __('Icon Placement', 'limenco-widgets-bundle'),
                                'default' => 'left',
                                'options' => [
                                    'top'    => __('Top', 'limenco-widgets-bundle'),
                                    'right'  => __('Right', 'limenco-widgets-bundle'),
                                    'bottom' => __('Bottom', 'limenco-widgets-bundle'),
                                    'left'   => __('Left', 'limenco-widgets-bundle'),
                                ],
                            ],
                        ],
                    ],
                    'bg_color' => [ 'type' => 'color', 'label' => __('Background Color', 'limenco-widgets-bundle'), 'default' => '#0073aa', ],
                    'bg_opacity' => [ 'type' => 'number', 'label' => __('Background Opacity (0-1)', 'limenco-widgets-bundle'), 'default' => 1, 'step' => 0.01, 'min' => 0, 'max' => 1, ],
                    'text_color' => [ 'type' => 'color', 'label' => __('Text Color', 'limenco-widgets-bundle'), 'default' => '#ffffff', ],
                    'font_size' => [ 'type' => 'text', 'label' => __('Font Size (e.g. 16px)', 'limenco-widgets-bundle'), 'default' => '16px', ],
                    'font_family' => [
                        'type' => 'font',
                        'label' => __('Font Family', 'limenco-widgets-bundle'),
                        'default' => 'inherit',
                    ],
                    'padding' => [ 'type' => 'text', 'label' => __('Padding (e.g. 12px 32px)', 'limenco-widgets-bundle'), 'default' => '12px 32px', ],
                    'radius' => [ 'type' => 'text', 'label' => __('Border Radius', 'limenco-widgets-bundle'), 'default' => '4px', ],
                    'border_width' => [ 'type' => 'text', 'label' => __('Border Width', 'limenco-widgets-bundle'), 'default' => '2px', ],
                    'border_color' => [ 'type' => 'color', 'label' => __('Border Color', 'limenco-widgets-bundle'), 'default' => '#ff0000', ],
                    'border_opacity' => [ 'type' => 'number', 'label' => __('Border Opacity (0-1)', 'limenco-widgets-bundle'), 'default' => 1, 'step' => 0.01, 'min' => 0, 'max' => 1, ],
                    'hover_bg_color' => [ 'type' => 'color', 'label' => __('Hover Background Color', 'limenco-widgets-bundle'), 'default' => '#005177', ],
                    'hover_bg_opacity' => [ 'type' => 'number', 'label' => __('Hover BG Opacity (0-1)', 'limenco-widgets-bundle'), 'default' => 1, 'step' => 0.01, 'min' => 0, 'max' => 1, ],
                    'hover_text_color' => [ 'type' => 'color', 'label' => __('Hover Text Color', 'limenco-widgets-bundle'), 'default' => '#ffffff', ],
                    'hover_border_color' => [ 'type' => 'color', 'label' => __('Hover Border Color', 'limenco-widgets-bundle'), 'default' => '#ff0000', ],
                ],
            ],
            'gap' => [ 'type' => 'text', 'label' => __('Button Gap (e.g. 12px)', 'limenco-widgets-bundle'), 'default' => '12px', ],
            'align' => [ 'type' => 'select', 'label' => __('Button Row Alignment', 'limenco-widgets-bundle'), 'default' => 'center', 'options' => [ 'left' => __('Left', 'limenco-widgets-bundle'), 'center' => __('Center', 'limenco-widgets-bundle'), 'right' => __('Right', 'limenco-widgets-bundle'), ], ],
        ];
    }
    public function get_template_name($instance) { return 'default'; }
    public function get_style_name($instance) { return 'default'; }
    public function get_template_variables($instance, $args) {
        // Enqueue Google Fonts for all selected fonts
        if (!empty($instance['buttons']) && is_array($instance['buttons'])) {
            foreach ($instance['buttons'] as $btn) {
                if (!empty($btn['font_family']) && is_array($btn['font_family'])) {
                    $this->enqueue_fonts([ 'font' => $btn['font_family'] ]);
                }
            }
        }
        return $instance;
    }
    public function get_less_variables($instance) {
        $less = [];
        if (!empty($instance['buttons']) && is_array($instance['buttons'])) {
            foreach ($instance['buttons'] as $i => $btn) {
                $font = !empty($btn['font_family']) ? siteorigin_widget_get_font($btn['font_family']) : ['family' => 'inherit', 'weight_raw' => 400, 'style' => 'normal'];
                $less['btn' . $i . '_font_family'] = $font['family'];
                $less['btn' . $i . '_font_weight'] = isset($font['weight_raw']) ? $font['weight_raw'] : 400;
                $less['btn' . $i . '_font_style'] = isset($font['style']) ? $font['style'] : 'normal';
            }
        }
        return $less;
    }
    public static function hex2rgba($color, $opacity = 1) {
        $color = ltrim($color, '#');
        if(strlen($color) == 6){ $hex = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]); }
        elseif(strlen($color) == 3){ $hex = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]); }
        else{ return 'rgba(0,0,0,'.$opacity.')'; }
        $rgb = array_map('hexdec', $hex);
        return 'rgba('.implode(',',$rgb).','.$opacity.')';
    }
}
siteorigin_widget_register('limenco-button-grid', __FILE__, 'Limenco_Button_Grid_Widget');
