<?php
defined('ABSPATH') or die('No script kiddies please!');

/*
 * Add City in bg_BG.po
 */
add_action('after_setup_theme', 'setup_child_domain');
function setup_child_domain ()
{
    load_theme_textdomain('theme_child', get_stylesheet_directory() . '/languages');
}

if (file_exists(get_stylesheet_directory().'/dynamicPropertiesLocation/DynamicLocation.php')){
    include_once "dynamicPropertiesLocation/DynamicLocation.php";

    $dynlocation = \DynamicLocation::getInstance();
    $dynlocation->run();
}
