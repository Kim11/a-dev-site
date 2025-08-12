<?php
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
    $font = !empty($btn['font_family']) ? siteorigin_widget_get_font($btn['font_family']) : ['family' => 'inherit', 'weight_raw' => 400, 'style' => 'normal'];
    $font_family = $font['family'];
    $font_weight = isset($font['weight_raw']) ? $font['weight_raw'] : 400;
    $font_style = isset($font['style']) ? $font['style'] : 'normal';
    $padding = !empty($btn['padding']) ? $btn['padding'] : '12px 32px';
    $radius = !empty($btn['radius']) ? $btn['radius'] : '4px';
    $border_width = !empty($btn['border_width']) ? $btn['border_width'] : '2px';
    $border_color = !empty($btn['border_color']) ? $btn['border_color'] : '#ff0000';
    $border_opacity = isset($btn['border_opacity']) ? floatval($btn['border_opacity']) : 1;
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
    $style .= 'font-weight:' . esc_attr($font_weight) . ';';
    $style .= 'font-style:' . esc_attr($font_style) . ';';
    $style .= 'padding:' . esc_attr($padding) . ';';
    $border_rgba = Limenco_Button_Grid_Widget::hex2rgba($border_color, $border_opacity);
    $style .= 'border:' . esc_attr($border_width) . ' solid ' . esc_attr($border_rgba) . ';';
    $style .= 'border-radius:' . esc_attr($radius) . ';';
    $style .= 'cursor:pointer;display:inline-block;text-align:center;text-decoration:none;transition:all 0.2s;';
    $unique_id = uniqid('limenco_btn_');
    $hover_rgba = Limenco_Button_Grid_Widget::hex2rgba($hover_bg_color, $hover_bg_opacity);
    $hover_css = 'background:' . esc_attr($hover_rgba) . ' !important;';
    $hover_css .= 'color:' . esc_attr($hover_text_color) . ' !important;';
    $hover_css .= 'border-color:' . esc_attr($hover_border_color) . ' !important;';
    // Icon rendering
    $icon_html = '';
    $icon_placement = 'left';
    $icon_style = 'font-size:1.4em;vertical-align:middle;line-height:1;';
    $img_style = 'width:1.4em;height:1.4em;vertical-align:middle;line-height:1;';
    if (!empty($btn['icon_section']['icon_selected'])) {
        $icon_placement = !empty($btn['icon_section']['icon_placement']) ? $btn['icon_section']['icon_placement'] : 'left';
        $icon_color = !empty($btn['icon_section']['icon_color']) ? $btn['icon_section']['icon_color'] : '';
        if ($icon_placement === 'left') $icon_style .= 'margin-right:0.5em;margin-left:0;';
        if ($icon_placement === 'right') $icon_style .= 'margin-left:0.5em;margin-right:0;';
        $icon_html = '<span class="limenco-btn-icon" style="' . $icon_style . '">' . siteorigin_widget_get_icon($btn['icon_section']['icon_selected'], $icon_color ? [ 'color: ' . esc_attr($icon_color) ] : []) . '</span>';
    } elseif (!empty($btn['icon_section']['icon'])) {
        $icon_placement = !empty($btn['icon_section']['icon_placement']) ? $btn['icon_section']['icon_placement'] : 'left';
        $icon_url = wp_get_attachment_url($btn['icon_section']['icon']);
        if ($icon_url) {
            if ($icon_placement === 'left') $img_style .= 'margin-right:0.5em;margin-left:0;';
            if ($icon_placement === 'right') $img_style .= 'margin-left:0.5em;margin-right:0;';
            $icon_html = '<img src="' . esc_url($icon_url) . '" alt="icon" class="limenco-btn-icon" style="' . $img_style . '" />';
        }
    }
    $content = '';
    if ($icon_html && ($icon_placement === 'left' || $icon_placement === 'top')) $content .= $icon_html;
    $content .= esc_html($text);
    if ($icon_html && ($icon_placement === 'right' || $icon_placement === 'bottom')) $content .= $icon_html;
    ?>
    <?php if ($url): ?>
        <a id="<?php echo esc_attr($unique_id); ?>" href="<?php echo esc_url($url); ?>"<?php if ($new_window) echo ' target="_blank" rel="noopener noreferrer"'; ?> style="<?php echo $style; ?>"><?php echo $content; ?></a>
    <?php else: ?>
        <span id="<?php echo esc_attr($unique_id); ?>" style="<?php echo $style; ?>"><?php echo $content; ?></span>
    <?php endif; ?>
    <style>#<?php echo esc_attr($unique_id); ?>:hover{<?php echo $hover_css; ?>}</style>
<?php endforeach; ?>
</div>
