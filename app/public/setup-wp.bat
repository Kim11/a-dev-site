@echo off
setlocal

REM =========================
REM EDIT THESE VARIABLES
REM =========================
set "SITE_PATH=E:\WP Local\a-dev-site\app\public"
set "PARENT_THEME=siteorigin-north"
set "CHILD_THEME=limenco-design-child"
set "CHILD_THEME_NAME=Limenco Design Child"
set "CHILD_THEME_VERSION=1.0.0"
set "CHILD_THEME_DESC=A lightweight child theme for SiteOrigin North by Limenco Design, adding refined styling, custom layouts, and WooCommerce-friendly tweaks. Built for speed, clarity, and easy updates with SiteOrigin Page Builder."
set "PLUGS_ACT=contact-form-7 flamingo generate-child-theme megamenu siteorigin-panels so-css so-widgets-bundle svg-support insert-headers-and-footers classic-widgets"
set "PLUGS_INACTIVE=duplicator w3-total-cache wordpress-seo wordfence loftloader"
set "SO_PREMIUM_ZIP=E:\WP Local\siteorigin-premium.1.73.1.zip"
set "SO_PREMIUM_KEY=bfb61a77c0211186051dfbd75571d1bb"
set "TIMEZONE=Africa/Johannesburg"
set "PERMALINK=/%%postname%%/"
set "SCREENSHOT_SRC=E:\WP Local\screenshot.jpg"
set "SITE_URL=https://a-dev-site.local"

cd /d "%SITE_PATH%"

echo === Installing parent theme ===
call wp theme install "%PARENT_THEME%" --force

echo === Creating and activating child theme ===
set "CHILD_THEME_PATH=%SITE_PATH%\wp-content\themes\%CHILD_THEME%"
if exist "%CHILD_THEME_PATH%" (
    echo Deleting existing child theme folder: "%CHILD_THEME_PATH%"
    rmdir /S /Q "%CHILD_THEME_PATH%"
)
call wp scaffold child-theme "%CHILD_THEME%" --parent_theme="%PARENT_THEME%" --theme_name="%CHILD_THEME_NAME%" --author="Limenco Design" --author_uri="https://limencodesign.co.za" --activate --force

echo === Creating minimal style.css for theme recognition ===
set "CHILD_STYLE=%SITE_PATH%\wp-content\themes\%CHILD_THEME%\style.css"
echo /* > "%CHILD_STYLE%"
echo Theme Name: %CHILD_THEME_NAME%>>"%CHILD_STYLE%"
echo Description: %CHILD_THEME_DESC%>>"%CHILD_STYLE%"
echo Author: Limenco Design>>"%CHILD_STYLE%"
echo Author URI: https://limencodesign.co.za>>"%CHILD_STYLE%"
echo Template: %PARENT_THEME%>>"%CHILD_STYLE%"
echo Version: %CHILD_THEME_VERSION%>>"%CHILD_STYLE%"
echo Text Domain: %CHILD_THEME%>>"%CHILD_STYLE%"
echo */>>"%CHILD_STYLE%"
echo /* This file is required for WordPress to recognize the theme. */ >> "%CHILD_STYLE%"

echo === Creating/enhancing child theme functions.php ===
set "CHILD_FUNCTIONS=%SITE_PATH%\wp-content\themes\%CHILD_THEME%\functions.php"
echo <?php > "%CHILD_FUNCTIONS%"
echo // Enqueue parent style only >> "%CHILD_FUNCTIONS%"
echo add_action('wp_enqueue_scripts', function() { >> "%CHILD_FUNCTIONS%"
echo 	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css'); >> "%CHILD_FUNCTIONS%"
echo }); >> "%CHILD_FUNCTIONS%"

echo === Copying screenshot ===
set "CHILD_SCREENSHOT=%SITE_PATH%\wp-content\themes\%CHILD_THEME%\screenshot.png"
if exist "%SCREENSHOT_SRC%" copy /Y "%SCREENSHOT_SRC%" "%CHILD_SCREENSHOT%"

echo === Copying parent header.php to child theme ===
set "PARENT_HEADER=%SITE_PATH%\wp-content\themes\%PARENT_THEME%\header.php"
set "CHILD_HEADER=%SITE_PATH%\wp-content\themes\%CHILD_THEME%\header.php"
if exist "%PARENT_HEADER%" copy /Y "%PARENT_HEADER%" "%CHILD_HEADER%"

echo === Installing and activating plugins ===
call wp plugin install %PLUGS_ACT% --activate

echo === Installing (inactive) plugins ===
call wp plugin install %PLUGS_INACTIVE%

if exist "%SO_PREMIUM_ZIP%" (
    echo === Installing SiteOrigin Premium ===
    call wp plugin install "%SO_PREMIUM_ZIP%" --activate
)

if not "%SO_PREMIUM_KEY%"=="" (
    echo === Setting SiteOrigin Premium license ===
    call wp option update siteorigin_premium_key "%SO_PREMIUM_KEY%"
    call wp option update siteorigin_premium_key_status "valid"
)

echo === Core WordPress settings ===
call wp option update uploads_use_yearmonth_folders 0
call wp option update blog_public 0
call wp option update timezone_string "%TIMEZONE%"
call wp rewrite structure "%PERMALINK%" --hard
call wp rewrite flush --hard
call wp option update siteurl "%SITE_URL%"
call wp option update home "%SITE_URL%"

echo === Activating all SiteOrigin Widgets ===
call wp eval "if (class_exists('SiteOrigin_Widgets_Bundle')) { $b = SiteOrigin_Widgets_Bundle::single(); if (method_exists($b,'activate_widget') && method_exists($b,'get_widgets')) { foreach ($b->get_widgets() as $id => $cls) { $b->activate_widget($id); } } }"

echo === Done! ===
endlocal
