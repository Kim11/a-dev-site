@echo off
setlocal

:: =========================
:: EDIT THESE VARIABLES
:: =========================
set "SITE_PATH=E:\WP Local\a-dev-site\app\public"
:: Themes
set "PARENT_THEME_SLUG=siteorigin-north"
set "CHILD_THEME_SLUG=limenco-design-child"
set "CHILD_THEME_NAME=Limenco Design Child"
set "CHILD_THEME_VERSION=1.0.0"
set "CHILD_THEME_DESC=A lightweight child theme for SiteOrigin North by Limenco Design, adding refined styling, custom layouts, and WooCommerce-friendly tweaks. Built for speed, clarity, and easy updates with SiteOrigin Page Builder."
:: SiteOrigin Premium
set "SO_PREMIUM_ZIP=E:\WP Local\siteorigin-premium.1.73.1.zip"
set "SO_PREMIUM_KEY=bfb61a77c0211186051dfbd75571d1bb"
:: Privacy page JSON
set "PRIVACY_JSON=E:\WP Local\privacy.json"
:: WordPress settings
set "TIMEZONE=Africa/Johannesburg"
set "PERMALINK=/%%postname%%/"
:: Plugins to install + activate
set "PLUGS_ACT=contact-form-7 flamingo generate-child-theme megamenu siteorigin-panels so-css so-widgets-bundle svg-support insert-headers-and-footers classic-widgets"
:: Plugins to install only (inactive)
set "PLUGS_INACTIVE=duplicator w3-total-cache wordpress-seo wordfence loftloader"
:: Screenshot source
set "SCREENSHOT_SRC=E:\WP Local\screenshot.jpg"

echo.
echo === Checking WP-CLI ===
echo [debug] SITE_PATH=%SITE_PATH%
if not exist "%SITE_PATH%\wp-config.php" (
  echo [error] wp-config.php not found at "%SITE_PATH%"
  goto :end
)
wp --path="%SITE_PATH%" --info
echo [debug] WP-CLI check ERRORLEVEL=%ERRORLEVEL%
echo [ok] WP-CLI looks good. Continuing...
echo.
echo === Install parent theme ===
wp --path="%SITE_PATH%" theme install "%PARENT_THEME_SLUG%" --force >nul
echo.
echo === Create + activate child theme ===
wp --path="%SITE_PATH%" scaffold child-theme "%CHILD_THEME_SLUG%" --parent_theme="%PARENT_THEME_SLUG%" --theme_name="%CHILD_THEME_NAME%" --author="Limenco Design" --author_uri="https://limencodesign.co.za" --activate --force >nul
echo.
echo === Patch child style.css (Description/Author/Version) ===
set "CHILD_STYLE=%SITE_PATH%\wp-content\themes\%CHILD_THEME_SLUG%\style.css"
powershell -NoProfile -Command ^
  "$p='%CHILD_STYLE%';$desc='%CHILD_THEME_DESC%';$author='Limenco Design';$ver='%CHILD_THEME_VERSION%';" ^
  "$t = Get-Content $p -Raw;" ^
  "if ($t -match '(?m)^Description:') {$t = $t -replace '(?m)^Description:.*$', 'Description: ' + $desc} else {$t += \r\n'Description: ' + $desc}" ^
  "if ($t -match '(?m)^Author:') {$t = $t -replace '(?m)^Author:.*$', 'Author: ' + $author} else {$t += \r\n'Author: ' + $author}" ^
  "if ($t -match '(?m)^Version:') {$t = $t -replace '(?m)^Version:.*$', 'Version: ' + $ver} else {$t += \r\n'Version: ' + $ver}" ^
  "Set-Content $p $t -Encoding UTF8"
echo.
echo === Copy screenshot.jpg to child theme as screenshot.png ===
set "CHILD_SCREENSHOT=%SITE_PATH%\wp-content\themes\%CHILD_THEME_SLUG%\screenshot.png"
if exist "%SCREENSHOT_SRC%" (
  powershell -NoProfile -Command "Copy-Item -Path '%SCREENSHOT_SRC%' -Destination '%CHILD_SCREENSHOT%' -Force"
  echo [ok] Screenshot copied to child theme.
) else (
  echo [warn] Screenshot source not found: %SCREENSHOT_SRC%
)
echo.
echo === Plugins: install + activate ===
if not "%PLUGS_ACT%"=="" wp --path="%SITE_PATH%" plugin install %PLUGS_ACT% --activate
echo.
echo === Plugins: install (inactive) ===
if not "%PLUGS_INACTIVE%"=="" wp --path="%SITE_PATH%" plugin install %PLUGS_INACTIVE%
echo.
if exist "%SO_PREMIUM_ZIP%" (
  echo === Install SiteOrigin Premium from ZIP ===
  wp --path="%SITE_PATH%" plugin install "%SO_PREMIUM_ZIP%" --activate
) else (
  echo !!! Premium ZIP not found at: %SO_PREMIUM_ZIP%
)
echo.
if not "%SO_PREMIUM_KEY%"=="" (
  echo === Set SiteOrigin Premium license ===
  wp --path="%SITE_PATH%" option update siteorigin_premium_key "%SO_PREMIUM_KEY%"
  wp --path="%SITE_PATH%" option update siteorigin_premium_key_status "valid"
) else (
  echo !!! Skipping SiteOrigin Premium license (no key set)
)
echo.
echo === Core WordPress settings ===
wp --path="%SITE_PATH%" option update uploads_use_yearmonth_folders 0
wp --path="%SITE_PATH%" option update blog_public 0
wp --path="%SITE_PATH%" option update timezone_string "%TIMEZONE%"
wp --path="%SITE_PATH%" rewrite structure "%PERMALINK%" --hard
wp --path="%SITE_PATH%" rewrite flush --hard
echo.
echo === Delete default content ===
for /f %%i in ('wp --path="%SITE_PATH%" post list --post_type=post --name=hello-world --format=ids') do wp --path="%SITE_PATH%" post delete %%i --force
for /f %%i in ('wp --path="%SITE_PATH%" post list --post_type=page --name=sample-page --format=ids') do wp --path="%SITE_PATH%" post delete %%i --force
echo.
echo === Contact Form 7: ensure default form + Flamingo-ready mail ===
powershell -NoProfile -Command ^
  "$php=@' ^
<?php
if ( ! function_exists('wp_insert_post') ) { exit; }
if ( ! post_type_exists('wpcf7_contact_form') ) { echo \"CF7 inactive\\n\"; exit; }
$title = 'Contact form 1';
$post = get_page_by_title($title, OBJECT, 'wpcf7_contact_form');
if ( ! $post ) {
  $post_id = wp_insert_post(array('post_type'=>'wpcf7_contact_form','post_title'=>$title,'post_status'=>'publish'));
} else { $post_id = $post->ID; }
$form = \"<p><label>Your Name (required)<br /> [text* your-name]</label></p>\n<p><label>Your Email (required)<br /> [email* your-email]</label></p>\n<p><label>Subject<br /> [text your-subject]</label></p>\n<p><label>Your Message<br /> [textarea your-message]</label></p>\n<p>[submit \\\"Send\\\"]</p>\";
update_post_meta($post_id, '_form', $form);
$admin = get_option('admin_email');
$host = parse_url(home_url(), PHP_URL_HOST);
$mail = array(
  'recipient' => $admin,
  'sender' => '[your-name] <wordpress@' . $host . '>',
  'subject' => 'New message via contact form',
  'additional_headers' => \"Reply-To: [your-name] <[your-email]>\",
  'body' => \"From: [your-name] <[your-email]>\\nSubject: [your-subject]\\n\\n[your-message]\",
  'use_html' => 1,
  'exclude_blank' => 1,
  'attachments' => ''
);
update_post_meta($post_id, '_mail', $mail);
echo \"CF7 form ID: $post_id\\n\";
?> ^
'@; ^
Set-Content -Path \"$env:TEMP\cf7_setup.php\" -Value $php -Encoding UTF8; ^
wp --path=\"%SITE_PATH%\" eval-file \"$env:TEMP\cf7_setup.php\""
echo.
echo === Privacy page from JSON (if present) ===
if exist "%PRIVACY_JSON%" (
  powershell -NoProfile -Command ^
    "$j = Get-Content '%PRIVACY_JSON%' -Raw | ConvertFrom-Json; $title = if($j.title){$j.title}else{'Privacy Policy'}; $content = $j.content; $tmp = Join-Path $env:TEMP 'privacy.html'; Set-Content $tmp $content -Encoding UTF8; ^
    $id = (wp --path='%SITE_PATH%' post list --post_type=page --name=privacy-policy --format=ids); ^
    if(-not $id -or $id -eq ''){ $id = (wp --path='%SITE_PATH%' post create --post_type=page --post_title=\"$title\" --post_status=publish --porcelain); } ^
    wp --path='%SITE_PATH%' post update $id --post_title=\"$title\" --post_content=\"$(Get-Content $tmp -Raw)\"; Remove-Item $tmp"
) else (
  echo (No privacy JSON at "%PRIVACY_JSON%" — skipping)
)
echo.
echo === Page Builder & Widgets Bundle tweaks (best-effort) ===
powershell -NoProfile -Command ^
  "$php=@' ^
<?php
$opt = get_option('siteorigin_panels_settings', array());
$opt['responsive'] = true;
if ( empty($opt['breakpoints']) || !is_array($opt['breakpoints']) ) $opt['breakpoints'] = array();
$opt['breakpoints']['tablet'] = 1024;
$opt['breakpoints']['mobile'] = 480;
$opt['legacy_layout_engine'] = 'always';
update_option('siteorigin_panels_settings', $opt);
if ( class_exists('SiteOrigin_Widgets_Bundle') ) {
  $b = SiteOrigin_Widgets_Bundle::single();
  if ( method_exists($b,'activate_widget') && method_exists($b,'get_widgets') ) {
    foreach ( $b->get_widgets() as $id => $cls ) { $b->activate_widget($id); }
  } else {
    $active = get_option('so_widgets_active', array());
    if ( is_array($active) ) { foreach ($active as $k => $v) { $active[$k] = true; } update_option('so_widgets_active',$active); }
  }
}
?> ^
'@; ^
Set-Content -Path \"$env:TEMP\so_settings.php\" -Value $php -Encoding UTF8; ^
wp --path=\"%SITE_PATH%\" eval-file \"$env:TEMP\so_settings.php\""
echo.
echo === Done. ===
:end
endlocal
