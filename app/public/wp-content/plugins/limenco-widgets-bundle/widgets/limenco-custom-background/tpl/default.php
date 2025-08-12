<?php
// Limenco Custom Background Widget Template
$image = !empty($instance['image']) ? wp_get_attachment_url($instance['image']) : '';
// Gradient 1
$g1 = isset($instance['g1_section']) ? $instance['g1_section'] : [];
$g2 = isset($instance['g2_section']) ? $instance['g2_section'] : [];
$g1_active = !empty($g1['g1_active']) && $g1['g1_active'] !== '0';
$g1_angle = isset($g1['g1_angle']) ? intval($g1['g1_angle']) : 135;
$g1_start = !empty($g1['g1_start']) ? $g1['g1_start'] : '#000000';
$g1_start_op = isset($g1['g1_start_op']) ? floatval($g1['g1_start_op']) : 1;
$g1_mid = !empty($g1['g1_mid']) ? $g1['g1_mid'] : '#ffffff';
$g1_mid_op = isset($g1['g1_mid_op']) ? floatval($g1['g1_mid_op']) : 1;
$g1_end = !empty($g1['g1_end']) ? $g1['g1_end'] : '#000000';
$g1_end_op = isset($g1['g1_end_op']) ? floatval($g1['g1_end_op']) : 1;
// Gradient 2
$g2_active = !empty($g2['g2_active']) && $g2['g2_active'] !== '0';
$g2_angle = isset($g2['g2_angle']) ? intval($g2['g2_angle']) : 135;
$g2_start = !empty($g2['g2_start']) ? $g2['g2_start'] : '#ffffff';
$g2_start_op = isset($g2['g2_start_op']) ? floatval($g2['g2_start_op']) : 1;
$g2_mid = !empty($g2['g2_mid']) ? $g2['g2_mid'] : '#000000';
$g2_mid_op = isset($g2['g2_mid_op']) ? floatval($g2['g2_mid_op']) : 1;
$g2_end = !empty($g2['g2_end']) ? $g2['g2_end'] : '#ffffff';
$g2_end_op = isset($g2['g2_end_op']) ? floatval($g2['g2_end_op']) : 1;
// Compose gradients
$gradients = [];
if ($g2_active) {
    $g2_css = Limenco_Custom_Background_Widget::make_gradient($g2_angle, [
        ['color'=>$g2_start,'op'=>$g2_start_op,'pos'=>0],
        ['color'=>$g2_mid,'op'=>$g2_mid_op,'pos'=>50],
        ['color'=>$g2_end,'op'=>$g2_end_op,'pos'=>100],
    ]);
    $gradients[] = $g2_css;
}
if ($g1_active) {
    $g1_css = Limenco_Custom_Background_Widget::make_gradient($g1_angle, [
        ['color'=>$g1_start,'op'=>$g1_start_op,'pos'=>0],
        ['color'=>$g1_mid,'op'=>$g1_mid_op,'pos'=>50],
        ['color'=>$g1_end,'op'=>$g1_end_op,'pos'=>100],
    ]);
    $gradients[] = $g1_css;
}
$background = implode(", ", $gradients);
if ($image) {
    $background .= ($background ? ', ' : '') . "url('$image') center center/cover no-repeat";
}
$unique_id = uniqid('limenco_bg_');
?>
<div class="limenco-custom-bg-overlay-canvas" id="<?php echo esc_attr($unique_id); ?>" style="position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;z-index:-1;padding:0;margin:0;background:<?php echo esc_attr($background); ?>;pointer-events:none;"></div>
<script>(function(){
    // Only move the overlay for the first instance in a row (original working logic)
    setTimeout(function() {
        var overlay = document.getElementById('<?php echo esc_js($unique_id); ?>');
        if(!overlay) return;
        var cell = overlay.closest('.panel-grid-cell');
        if(!cell) return;
        var row = cell.closest('.panel-row-style');
        if(!row) return;
        // Only move if this is the first overlay in the row
        var overlays = row.querySelectorAll('.limenco-custom-bg-overlay-canvas');
        if (overlays[0] !== overlay) return;
        row.insertBefore(overlay, row.firstChild);
        overlay.style.position = "absolute";
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.right = 0;
        overlay.style.bottom = 0;
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.zIndex = 0;
        overlay.style.pointerEvents = "none";
        row.style.position = "relative";
    }, 100);
})();</script>
<style>
.limenco-custom-bg-overlay-canvas{pointer-events:none;z-index:-1 !important;}
.so-panel .limenco-custom-bg-overlay-canvas{z-index:-1 !important;}
</style>
