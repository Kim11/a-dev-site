<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_Widget')) return;

class Lucide_Icon_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'lucide_icon_widget',
            __('Lucide Icon Widget', 'lucide-icon-widget'),
            array('description' => __('Display a Lucide icon from a JSON file', 'lucide-icon-widget'))
        );
    }

    // Load icon names from JSON file
    private function get_icon_names() {
        $json_path = plugin_dir_path(__FILE__) . 'lucide-icons-clean.json';
        if (!file_exists($json_path)) return array();
        $json = file_get_contents($json_path);
        $icons = json_decode($json, true);
        if (!is_array($icons)) return array();
        return array_keys($icons);
    }

    public function form($instance) {
        $icon = isset($instance['icon']) ? $instance['icon'] : '';
        $icon_color = isset($instance['icon_color']) ? $instance['icon_color'] : '#222';
        $bg_color = isset($instance['bg_color']) ? $instance['bg_color'] : 'transparent';
        $border_radius = isset($instance['border_radius']) ? $instance['border_radius'] : '0';
        $border_width = isset($instance['border_width']) ? $instance['border_width'] : '0';
        $border_color = isset($instance['border_color']) ? $instance['border_color'] : 'transparent';
        $size = isset($instance['size']) ? intval($instance['size']) : 24;
        $alignment = isset($instance['alignment']) ? $instance['alignment'] : 'center';
        $bg_size = isset($instance['bg_size']) ? intval($instance['bg_size']) : 48;
        $icon_class = isset($instance['icon_class']) ? $instance['icon_class'] : '';
        $icon_url = isset($instance['icon_url']) ? $instance['icon_url'] : '';
        // Load icon data (names and SVGs)
        $json_path = plugin_dir_path(__FILE__) . 'lucide-icons-clean.json';
        $icons = array();
        if (file_exists($json_path)) {
            $json = file_get_contents($json_path);
            $icons = json_decode($json, true);
        }
        ?>
        <p>
            <label><?php _e('Select Icon:', 'lucide-icon-widget'); ?></label>
            <input type="hidden" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" value="<?php echo esc_attr($icon); ?>" />
            <input type="text" placeholder="Filter icons..." class="lucide-icon-widget-filter" id="<?php echo $this->get_field_id('icon_filter'); ?>" style="width:100%;margin-bottom:8px;" />
            <button type="button" class="button lucide-icon-widget-expand-btn" id="<?php echo $this->get_field_id('icon_expand_btn'); ?>" style="margin-bottom:8px;">Expand</button>
            <div class="lucide-icon-widget-picker" id="<?php echo $this->get_field_id('icon_picker'); ?>" style="display: flex; flex-wrap: wrap; gap: 10px; max-height: 220px; overflow-y: auto; border: 1px solid #ccc; padding: 8px; background: #fff; transition:max-height 0.2s;">
                <?php 
                $icon_names = array_keys($icons);
                $initial_icons = array_slice($icon_names, 0, 60);
                foreach ($initial_icons as $name): ?>
                    <div class="lucide-icon-widget-picker-item<?php if ($icon === $name) echo ' selected'; ?>" data-icon-name="<?php echo esc_attr($name); ?>" style="cursor:pointer; text-align:center; width:60px; margin-bottom:8px; border: 2px solid <?php echo ($icon === $name) ? '#0073aa' : 'transparent'; ?>; border-radius: 6px; padding: 4px; background: <?php echo ($icon === $name) ? '#e5f5ff' : 'none'; ?>;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block;margin:0 auto;">
                            <?php echo $icons[$name]; ?>
                        </svg>
                        <div style="font-size:11px;word-break:break-all;line-height:1.2;margin-top:2px;"><?php echo esc_html($name); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="margin: 8px 0 0 0; font-size: 12px; color: #666;">
                <?php echo sprintf( esc_html__('Total icons available: %d', 'lucide-icon-widget'), count($icons) ); ?>
            </div>
            <script type="application/json" id="<?php echo $this->get_field_id('icon_json'); ?>">
                <?php echo json_encode($icons); ?>
            </script>
            <style>
                .lucide-icon-widget-picker-item.selected {
                    border-color: #0073aa !important;
                    background: #e5f5ff !important;
                }
                .lucide-icon-widget-picker-item:hover {
                    border-color: #0073aa;
                }
            </style>
            <script>(function($){
                $(document).ready(function(){
                    var $picker = $('#<?php echo $this->get_field_id('icon_picker'); ?>');
                    var $input = $('#<?php echo $this->get_field_id('icon'); ?>');
                    var $filter = $('#<?php echo $this->get_field_id('icon_filter'); ?>');
                    var $expandBtn = $('#<?php echo $this->get_field_id('icon_expand_btn'); ?>');
                    var iconJson = JSON.parse($('#<?php echo $this->get_field_id('icon_json'); ?>').text());
                    var iconNames = Object.keys(iconJson);
                    var batchSize = 60;
                    var loadedCount = $picker.find('.lucide-icon-widget-picker-item').length;
                    var loading = false;

                    function renderIcons(start, end) {
                        var html = '';
                        for (var i = start; i < end && i < iconNames.length; i++) {
                            var name = iconNames[i];
                            var svg = iconJson[name];
                            var selected = ($input.val() === name) ? ' selected' : '';
                            html += '<div class="lucide-icon-widget-picker-item' + selected + '" data-icon-name="' + name + '" style="cursor:pointer; text-align:center; width:60px; margin-bottom:8px; border: 2px solid ' + (selected ? '#0073aa' : 'transparent') + '; border-radius: 6px; padding: 4px; background: ' + (selected ? '#e5f5ff' : 'none') + ';">';
                            html += '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block;margin:0 auto;">' + svg + '</svg>';
                            html += '<div style="font-size:11px;word-break:break-all;line-height:1.2;margin-top:2px;">' + name + '</div>';
                            html += '</div>';
                        }
                        $picker.append(html);
                    }

                    $picker.on('scroll', function() {
                        if (loading) return;
                        if ($picker[0].scrollTop + $picker[0].clientHeight >= $picker[0].scrollHeight - 10) {
                            loading = true;
                            var nextCount = loadedCount + batchSize;
                            renderIcons(loadedCount, nextCount);
                            loadedCount = nextCount;
                            loading = false;
                        }
                    });

                    $picker.on('click', '.lucide-icon-widget-picker-item', function(){
                        $picker.find('.lucide-icon-widget-picker-item').removeClass('selected').css({'border-color':'transparent','background':'none'});
                        $(this).addClass('selected').css({'border-color':'#0073aa','background':'#e5f5ff'});
                        $input.val($(this).data('icon-name'));
                    });
                    $filter.on('keyup change', function(){
                        var val = $(this).val().toLowerCase();
                        $picker.empty();
                        var filtered = iconNames.filter(function(name){
                            return name.toLowerCase().indexOf(val) !== -1;
                        });
                        if (filtered.length === 0) {
                            $picker.append('<div style="padding:12px;color:#999;">No icons found.</div>');
                        } else {
                            for (var i = 0; i < filtered.length; i++) {
                                var name = filtered[i];
                                var svg = iconJson[name];
                                var selected = ($input.val() === name) ? ' selected' : '';
                                var html = '<div class="lucide-icon-widget-picker-item' + selected + '" data-icon-name="' + name + '" style="cursor:pointer; text-align:center; width:60px; margin-bottom:8px; border: 2px solid ' + (selected ? '#0073aa' : 'transparent') + '; border-radius: 6px; padding: 4px; background: ' + (selected ? '#e5f5ff' : 'none') + ';">';
                                html += '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block;margin:0 auto;">' + svg + '</svg>';
                                html += '<div style="font-size:11px;word-break:break-all;line-height:1.2;margin-top:2px;">' + name + '</div>';
                                html += '</div>';
                                $picker.append(html);
                            }
                        }
                        // Disable lazy loading while filtering
                        loadedCount = filtered.length;
                    });
                    $expandBtn.on('click', function(){
                        $picker.toggleClass('lucide-icon-widget-expanded');
                        if($picker.hasClass('lucide-icon-widget-expanded')) {
                            $expandBtn.text('Collapse');
                        } else {
                            $expandBtn.text('Expand');
                        }
                    });
                });
            })(jQuery);</script>
            <style>
            .lucide-icon-widget-picker.lucide-icon-widget-expanded {
                max-height: 800px !important;
            }
            </style>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_color'); ?>"><?php _e('Icon Color:', 'lucide-icon-widget'); ?></label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="hidden" id="<?php echo $this->get_field_id('icon_color'); ?>" name="<?php echo $this->get_field_name('icon_color'); ?>" value="<?php echo esc_attr($icon_color); ?>" />
                <input type="text" id="<?php echo $this->get_field_id('icon_color_picker'); ?>" class="lucide-wp-color-picker" value="<?php echo esc_attr($icon_color); ?>" style="width:32px;min-width:32px;max-width:32px;padding:0;margin:0;vertical-align:middle;" />
            </div>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php _e('Background Color:', 'lucide-icon-widget'); ?></label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="hidden" id="<?php echo $this->get_field_id('bg_color'); ?>" name="<?php echo $this->get_field_name('bg_color'); ?>" value="<?php echo esc_attr($bg_color); ?>" />
                <input type="text" id="<?php echo $this->get_field_id('bg_color_picker'); ?>" class="lucide-wp-color-picker" value="<?php echo esc_attr($bg_color); ?>" style="width:32px;min-width:32px;max-width:32px;padding:0;margin:0;vertical-align:middle;" />
            </div>
        </p>
        <style>
        /* Make the hex field in the color picker popup wider */
        .wp-picker-container input.wp-color-picker {
            min-width: 120px !important;
            width: 120px !important;
            max-width: 160px !important;
        }
        .wp-picker-container .iris-picker .iris-strip + .iris-slider .iris-input,
        .wp-picker-container .iris-picker .iris-palette-container + .iris-strip + .iris-slider .iris-input {
            min-width: 120px !important;
            width: 120px !important;
            max-width: 160px !important;
        }
        </style>
        <script>(function($){
            $(document).ready(function(){
                // Icon color advanced picker
                var $iconColorPicker = $('#<?php echo $this->get_field_id('icon_color_picker'); ?>');
                var $iconColorHidden = $('#<?php echo $this->get_field_id('icon_color'); ?>');
                if ($.fn.wpColorPicker) {
                    $iconColorPicker.wpColorPicker({
                        defaultColor: $iconColorPicker.val(),
                        palettes: true,
                        hide: true,
                        alpha: true,
                        change: function(event, ui) {
                            var color = ui.color.toString();
                            $iconColorHidden.val(color);
                        },
                        clear: function() {
                            $iconColorHidden.val('');
                        }
                    });
                }
                // BG color advanced picker
                var $bgColorPicker = $('#<?php echo $this->get_field_id('bg_color_picker'); ?>');
                var $bgColorHidden = $('#<?php echo $this->get_field_id('bg_color'); ?>');
                if ($.fn.wpColorPicker) {
                    $bgColorPicker.wpColorPicker({
                        defaultColor: $bgColorPicker.val(),
                        palettes: true,
                        hide: true,
                        alpha: true,
                        change: function(event, ui) {
                            var color = ui.color.toString();
                            $bgColorHidden.val(color);
                        },
                        clear: function() {
                            $bgColorHidden.val('');
                        }
                    });
                }
                // Border color advanced picker
                var $borderColorPicker = $('#<?php echo $this->get_field_id('border_color_picker'); ?>');
                var $borderColorHidden = $('#<?php echo $this->get_field_id('border_color'); ?>');
                if ($.fn.wpColorPicker) {
                    $borderColorPicker.wpColorPicker({
                        defaultColor: $borderColorPicker.val(),
                        palettes: true,
                        hide: true,
                        alpha: true,
                        change: function(event, ui) {
                            var color = ui.color.toString();
                            $borderColorHidden.val(color);
                        },
                        clear: function() {
                            $borderColorHidden.val('');
                        }
                    });
                }
            });
        })(jQuery);</script>
        <p>
            <label for="<?php echo $this->get_field_id('border_radius'); ?>"><?php _e('Border Radius (px or %):', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('border_radius'); ?>" name="<?php echo $this->get_field_name('border_radius'); ?>" type="text" value="<?php echo esc_attr($border_radius); ?>" placeholder="0, 8px, 50%" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('border_width'); ?>"><?php _e('Border Width (px):', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('border_width'); ?>" name="<?php echo $this->get_field_name('border_width'); ?>" type="number" min="0" value="<?php echo esc_attr($border_width); ?>" placeholder="0" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('border_color'); ?>"><?php _e('Border Color:', 'lucide-icon-widget'); ?></label>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="hidden" id="<?php echo $this->get_field_id('border_color'); ?>" name="<?php echo $this->get_field_name('border_color'); ?>" value="<?php echo esc_attr($border_color); ?>" />
                <input type="text" id="<?php echo $this->get_field_id('border_color_picker'); ?>" class="lucide-wp-color-picker" value="<?php echo esc_attr($border_color); ?>" style="width:32px;min-width:32px;max-width:32px;padding:0;margin:0;vertical-align:middle;" />
            </div>
        </p>
                <p>
            <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Icon Size (px):', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="number" min="1" value="<?php echo esc_attr($size); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e('Alignment:', 'lucide-icon-widget'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>">
                <option value="left" <?php selected($alignment, 'left'); ?>><?php _e('Left', 'lucide-icon-widget'); ?></option>
                <option value="center" <?php selected($alignment, 'center'); ?>><?php _e('Center', 'lucide-icon-widget'); ?></option>
                <option value="right" <?php selected($alignment, 'right'); ?>><?php _e('Right', 'lucide-icon-widget'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('bg_size'); ?>"><?php _e('Background Size (px):', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('bg_size'); ?>" name="<?php echo $this->get_field_name('bg_size'); ?>" type="number" min="1" value="<?php echo esc_attr($bg_size); ?>" placeholder="48" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_class'); ?>"><?php _e('Icon CSS Class:', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('icon_class'); ?>" name="<?php echo $this->get_field_name('icon_class'); ?>" type="text" value="<?php echo esc_attr($icon_class); ?>" placeholder="e.g. my-custom-class" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('icon_url'); ?>"><?php _e('Icon Link URL:', 'lucide-icon-widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('icon_url'); ?>" name="<?php echo $this->get_field_name('icon_url'); ?>" type="text" value="<?php echo esc_attr($icon_url); ?>" placeholder="https://example.com" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['icon'] = sanitize_text_field($new_instance['icon']);
        $instance['icon_color'] = sanitize_text_field($new_instance['icon_color']);
        $instance['bg_color'] = sanitize_text_field($new_instance['bg_color']);
        $instance['border_radius'] = sanitize_text_field($new_instance['border_radius']);
        $instance['border_width'] = is_numeric($new_instance['border_width']) ? $new_instance['border_width'] : 0;
        $instance['border_color'] = sanitize_text_field($new_instance['border_color']);
        $instance['size'] = is_numeric($new_instance['size']) ? intval($new_instance['size']) : 24;
        $instance['alignment'] = in_array($new_instance['alignment'], array('left','center','right')) ? $new_instance['alignment'] : 'center';
        $instance['bg_size'] = is_numeric($new_instance['bg_size']) ? intval($new_instance['bg_size']) : 48;
        $instance['icon_class'] = sanitize_text_field($new_instance['icon_class']);
        $instance['icon_url'] = esc_url_raw($new_instance['icon_url']);
        return $instance;
    }

    public function widget($args, $instance) {
        $icon = isset($instance['icon']) ? $instance['icon'] : '';
        $icon_color = isset($instance['icon_color']) ? $instance['icon_color'] : '#222';
        $bg_color = isset($instance['bg_color']) ? $instance['bg_color'] : 'transparent';
        $border_radius = isset($instance['border_radius']) ? $instance['border_radius'] : '0';
        $border_width = isset($instance['border_width']) ? $instance['border_width'] : '0';
        $border_color = isset($instance['border_color']) ? $instance['border_color'] : 'transparent';
        $padding = isset($instance['padding']) ? $instance['padding'] : '0';
        $size = isset($instance['size']) ? intval($instance['size']) : 24;
        $alignment = isset($instance['alignment']) ? $instance['alignment'] : 'center';
        $svg_content = '';
        $icon_class = isset($instance['icon_class']) ? $instance['icon_class'] : '';
        $icon_url = isset($instance['icon_url']) ? $instance['icon_url'] : '';
        if ($icon) {
            $json_path = plugin_dir_path(__FILE__) . 'lucide-icons-clean.json';
            if (file_exists($json_path)) {
                $json = file_get_contents($json_path);
                $icons = json_decode($json, true);
                if (isset($icons[$icon])) {
                    $svg_content = $icons[$icon];
                }
            }
        }
        $bg_size = !empty($instance['bg_size']) ? intval($instance['bg_size']) : 48;
        if ($svg_content) {
            // Flex alignment
            $justify = 'center';
            if ($alignment === 'left') $justify = 'flex-start';
            if ($alignment === 'right') $justify = 'flex-end';
            $wrapper_style = 'display:flex;justify-content:' . esc_attr($justify) . ';align-items:center;';
            $wrapper_style .= 'width:100%;box-sizing:border-box;';
            $inner_style = 'display:flex;align-items:center;justify-content:center;';
            $inner_style .= 'width:' . esc_attr($bg_size) . 'px;height:' . esc_attr($bg_size) . 'px;';
            $inner_style .= 'background:' . esc_attr($bg_color) . ';';
            $inner_style .= 'border-radius:' . esc_attr($border_radius) . ';';
            $inner_style .= 'border:' . intval($border_width) . 'px solid ' . esc_attr($border_color) . ';';
            echo $args['before_widget'];
            echo '<div class="lucide-icon-widget-wrap' . ($icon_class ? ' ' . esc_attr($icon_class) : '') . '" style="' . esc_attr($wrapper_style) . '">';
            echo '<div class="lucide-icon-widget-bg" style="' . esc_attr($inner_style) . '">';
            $svg_tag = '<svg class="lucide-icon-widget-icon" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" style="width:' . esc_attr($size) . 'px !important;height:' . esc_attr($size) . 'px !important;display:block;max-width:100%;max-height:100%;margin:auto;" data-icon="' . esc_attr($icon) . '" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($icon_color) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $svg_content . '</svg>';
            if ($icon_url) {
                echo '<a href="' . esc_url($icon_url) . '" target="_blank" rel="noopener noreferrer">' . $svg_tag . '</a>';
            } else {
                echo $svg_tag;
            }
            echo '</div>';
            echo '</div>';
            echo $args['after_widget'];
        }
    }
}
