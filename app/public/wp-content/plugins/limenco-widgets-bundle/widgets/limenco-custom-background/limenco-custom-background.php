<?php
/*
Widget Name: Limenco Custom Background
Description: Adds a background image with up to two customizable gradients (each with start, middle, and end color stops, each with opacity, angle, and activation) to any container. By Limenco Design.
Author: Limenco Design
Author URI: https://limencodesign.co.za
Widget URI: https://limencodesign.co.za
*/

if (!defined('ABSPATH')) exit;

class Limenco_Custom_Background_Widget extends SiteOrigin_Widget {
    public function __construct() {
        parent::__construct(
            'limenco-custom-background',
            __('Limenco Custom Background (Only 1 p/page)', 'limenco-widgets-bundle'),
            [
                'description' => __('Background image with up to two customizable gradients, each with three color stops, activation, angle, and per-color opacity.', 'limenco-widgets-bundle'),
                'help' => 'https://limencodesign.co.za',
            ],
            [],
            false,
            plugin_dir_path(__FILE__)
        );
        // No compass JS, keep overlay logic only
    }
    public function get_widget_form() {
        $color_field = function($label, $name, $default) {
            return [
                'type' => 'color',
                'label' => $label,
                'default' => $default,
            ];
        };
        $opacity_field = function($label, $name, $default) {
            return [
                'type' => 'number',
                'label' => $label,
                'default' => $default,
                'step' => 0.01,
                'min' => 0,
                'max' => 1,
            ];
        };
        $angle_field = function($label, $name, $default) {
            return [
                'type' => 'number',
                'label' => $label,
                'default' => $default,
                'min' => 0,
                'max' => 360,
                'attributes' => [ 'class' => 'limenco-angle-input', 'data-angle-name' => $name ],
            ];
        };
        $gradient_fields = function($prefix, $defaults) use ($color_field, $opacity_field, $angle_field) {
            return [
                $prefix.'_active' => [ 'type' => 'checkbox', 'label' => __('Activate', 'limenco-widgets-bundle'), 'default' => $defaults['active'] ],
                $prefix.'_angle' => $angle_field(__('Angle (deg)', 'limenco-widgets-bundle'), $prefix.'_angle', $defaults['angle']),
                $prefix.'_start' => $color_field(__('Start Color', 'limenco-widgets-bundle'), $prefix.'_start', $defaults['start']),
                $prefix.'_start_op' => $opacity_field(__('Start Opacity', 'limenco-widgets-bundle'), $prefix.'_start_op', $defaults['start_op']),
                $prefix.'_mid' => $color_field(__('Middle Color', 'limenco-widgets-bundle'), $prefix.'_mid', $defaults['mid']),
                $prefix.'_mid_op' => $opacity_field(__('Middle Opacity', 'limenco-widgets-bundle'), $prefix.'_mid_op', $defaults['mid_op']),
                $prefix.'_end' => $color_field(__('End Color', 'limenco-widgets-bundle'), $prefix.'_end', $defaults['end']),
                $prefix.'_end_op' => $opacity_field(__('End Opacity', 'limenco-widgets-bundle'), $prefix.'_end_op', $defaults['end_op']),
            ];
        };
        return [
            'image' => [
                'type' => 'media',
                'label' => __('Background Image', 'limenco-widgets-bundle'),
                'choose' => __('Choose image', 'limenco-widgets-bundle'),
                'update' => __('Set image', 'limenco-widgets-bundle'),
                'library' => 'image',
            ],
            'g1_section' => [
                'type' => 'section',
                'label' => __('Gradient 1', 'limenco-widgets-bundle'),
                'fields' => $gradient_fields('g1', [
                    'active' => true,
                    'angle' => 135,
                    'start' => '#000000',
                    'start_op' => 1,
                    'mid' => '#ffffff',
                    'mid_op' => 1,
                    'end' => '#000000',
                    'end_op' => 1,
                ]),
            ],
            'g2_section' => [
                'type' => 'section',
                'label' => __('Gradient 2', 'limenco-widgets-bundle'),
                'fields' => $gradient_fields('g2', [
                    'active' => false,
                    'angle' => 135,
                    'start' => '#ffffff',
                    'start_op' => 1,
                    'mid' => '#000000',
                    'mid_op' => 1,
                    'end' => '#ffffff',
                    'end_op' => 1,
                ]),
            ],
        ];
    }
    public function angle_dial_js() {
        ?>
        <script>
        (function($){
            function drawDial($input) {
                var $canvas = $input.nextAll('canvas.limenco-angle-dial').first();
                if(!$canvas.length) return;
                var ctx = $canvas[0].getContext('2d');
                ctx.clearRect(0,0,36,36);
                ctx.beginPath();
                ctx.arc(18,18,16,0,2*Math.PI);
                ctx.strokeStyle = '#bbb';
                ctx.lineWidth = 2;
                ctx.stroke();
                var angle = (parseInt($input.val(),10)-90) * Math.PI/180;
                var x2 = 18 + 14 * Math.cos(angle);
                var y2 = 18 + 14 * Math.sin(angle);
                ctx.beginPath();
                ctx.moveTo(18,18);
                ctx.lineTo(x2,y2);
                ctx.strokeStyle = '#0073aa';
                ctx.lineWidth = 3;
                ctx.stroke();
            }
            function setupDials() {
                $('.limenco-angle-input').each(function(){
                    var $input = $(this);
                    if(!$input.nextAll('canvas.limenco-angle-dial').length) {
                        $input.after('<canvas class="limenco-angle-dial" width="36" height="36" style="vertical-align:middle;margin-left:8px;"></canvas>');
                    }
                    drawDial($input);
                    $input.on('input change', function(){ drawDial($input); });
                });
            }
            $(document).on('widget-added widget-updated panelsopen', setupDials);
            $(document).ready(setupDials);
        })(jQuery);
        </script>
        <?php
    }
    public function get_template_name($instance) { return 'default'; }
    public function get_style_name($instance) { return false; }
    public function get_template_variables($instance, $args) {
        $defaults = [
            'g1_active' => true, 'g1_angle' => 135,
            'g1_start' => '#000000', 'g1_start_op' => 1, 'g1_mid' => '#ffffff', 'g1_mid_op' => 1, 'g1_end' => '#000000', 'g1_end_op' => 1,
            'g2_active' => false, 'g2_angle' => 135,
            'g2_start' => '#ffffff', 'g2_start_op' => 1, 'g2_mid' => '#000000', 'g2_mid_op' => 1, 'g2_end' => '#ffffff', 'g2_end_op' => 1,
        ];
        foreach (['g1_section', 'g2_section'] as $section) {
            if (!empty($instance[$section]) && is_array($instance[$section])) {
                foreach ($instance[$section] as $k => $v) {
                    $instance[$k] = $v;
                }
            }
        }
        foreach ($defaults as $k => $v) {
            if (!isset($instance[$k])) $instance[$k] = $v;
        }
        return $instance;
    }
    public function update($new_instance, $old_instance, $form_type = 'widget') {
        $instance = array();
        $instance['image'] = sanitize_text_field($new_instance['image']);
        foreach (['g1_section', 'g2_section'] as $section) {
            $instance[$section] = [];
            if (!empty($new_instance[$section]) && is_array($new_instance[$section])) {
                foreach ($new_instance[$section] as $k => $v) {
                    $instance[$section][$k] = $v;
                }
            }
        }
        return $instance;
    }
    public static function hex2rgba($color, $opacity = 1) {
        $color = ltrim($color, '#');
        if(strlen($color) == 6){ $hex = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]); }
        elseif(strlen($color) == 3){ $hex = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]); }
        else{ return 'rgba(0,0,0,'.$opacity.')'; }
        $rgb = array_map('hexdec', $hex);
        return 'rgba('.implode(',',$rgb).','.$opacity.')';
    }
    public static function make_gradient($angle, $stops) {
        $parts = [];
        foreach ($stops as $stop) {
            $parts[] = self::hex2rgba($stop['color'], $stop['op']) . ' ' . $stop['pos'] . '%';
        }
        return 'linear-gradient(' . intval($angle) . 'deg, ' . implode(', ', $parts) . ')';
    }
    public static function wrap_gradient_with_opacity($gradient, $opacity) {
        $opacity = max(0, min(1, floatval($opacity)));
        return 'linear-gradient(rgba(255,255,255,' . $opacity . '), rgba(255,255,255,' . $opacity . ')),' . $gradient;
    }
}
siteorigin_widget_register('limenco-custom-background', __FILE__, 'Limenco_Custom_Background_Widget');
