<?php
// Minimal output for Limenco Button Grid widget
if ( empty( $instance['buttons'] ) ) return;
$gap = !empty($instance['gap']) ? $instance['gap'] : '12px';
$align = isset($instance['align']) ? $instance['align'] : 'center';
$justify = $align === 'left' ? 'flex-start' : ($align === 'right' ? 'flex-end' : 'center');
?>
<div class="limenco-button-grid" style="display:flex;flex-wrap:wrap;gap:<?php echo esc_attr($gap); ?>;justify-content:<?php echo esc_attr($justify); ?>;align-items:center;">
<?php foreach ( $instance['buttons'] as $btn ) :
    $text = !empty($btn['text']) ? $btn['text'] : 'Click Me';
    $url = isset($btn['url']) ? trim($btn['url']) : '';
    $new_window = !empty($btn['new_window']);
    $bg_color = !empty($btn['bg_color']) ? $btn['bg_color'] : '#0073aa';
    $bg_opacity = isset($btn['bg_opacity']) ? floatval($btn['bg_opacity']) : 1;
    $text_color = !empty($btn['text_color']) ? $btn['text_color'] : '#ffffff';
    $font_size = !empty($btn['font_size']) ? $btn['font_size'] : '16px';
    $font_family = !empty($btn['font_family']) ? $btn['font_family'] : 'inherit';
    $padding = !empty($btn['padding']) ? $btn['padding'] : '12px 32px';
    $radius = !empty($btn['radius']) ? $btn['radius'] : '4px';
    $border_width = !empty($btn['border_width']) ? $btn['border_width'] : '2px';
    $border_color = !empty($btn['border_color']) ? $btn['border_color'] : '#ff0000';
    $hover_bg_color = !empty($btn['hover_bg_color']) ? $btn['hover_bg_color'] : '#005177';
    $hover_bg_opacity = isset($btn['hover_bg_opacity']) ? floatval($btn['hover_bg_opacity']) : 1;
    $hover_text_color = !empty($btn['hover_text_color']) ? $btn['hover_text_color'] : '#ffffff';
    $hover_border_color = !empty($btn['hover_border_color']) ? $btn['hover_border_color'] : '#ff0000';
    $style = '';
    $rgba = Limenco_Button_Grid_Widget::hex2rgba($bg_color, $bg_opacity);
    $style .= 'background:' . esc_attr($rgba) . ';';
    $style .= 'color:' . esc_attr($text_color) . ';';
    $style .= 'font-size:' . esc_attr($font_size) . ';';
    $style .= 'font-family:' . esc_attr($font_family) . ';';
    $style .= 'padding:' . esc_attr($padding) . ';';
    $style .= 'border:' . esc_attr($border_width) . ' solid ' . esc_attr($border_color) . ';';
    $style .= 'border-radius:' . esc_attr($radius) . ';';
    $style .= 'cursor:pointer;display:inline-block;text-align:center;text-decoration:none;transition:all 0.2s;';
    $unique_id = uniqid('limenco_btn_');
    $hover_rgba = Limenco_Button_Grid_Widget::hex2rgba($hover_bg_color, $hover_bg_opacity);
    $hover_css = 'background:' . esc_attr($hover_rgba) . ' !important;';
    $hover_css .= 'color:' . esc_attr($hover_text_color) . ' !important;';
    $hover_css .= 'border-color:' . esc_attr($hover_border_color) . ' !important;';
    ?>
    <?php if ($url): ?>
        <a id="<?php echo esc_attr($unique_id); ?>" href="<?php echo esc_url($url); ?>"<?php if ($new_window) echo ' target="_blank" rel="noopener noreferrer"'; ?> style="<?php echo $style; ?>"><?php echo esc_html($text); ?></a>
    <?php else: ?>
        <span id="<?php echo esc_attr($unique_id); ?>" style="<?php echo $style; ?>"><?php echo esc_html($text); ?></span>
    <?php endif; ?>
    <style>#<?php echo esc_attr($unique_id); ?>:hover{<?php echo $hover_css; ?>}</style>
<?php endforeach; ?>
</div>
<?php
// Helper for rgba
if (!method_exists('Limenco_Button_Grid_Widget', 'hex2rgba')) {
    class Limenco_Button_Grid_Widget_Helper {
        public static function hex2rgba($color, $opacity = 1) {
            $color = ltrim($color, '#');
            if(strlen($color) == 6){
                $hex = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
            }elseif(strlen($color) == 3){
                $hex = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
            }else{
                return 'rgba(0,0,0,'.$opacity.')';
            }
            $rgb = array_map('hexdec', $hex);
            return 'rgba('.implode(',',$rgb).','.$opacity.')';
        }
    }
    function Limenco_Button_Grid_Widget_hex2rgba($color, $opacity = 1) {
        return Limenco_Button_Grid_Widget_Helper::hex2rgba($color, $opacity);
    }
}
?>