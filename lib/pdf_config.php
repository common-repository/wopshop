<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('K_TCPDF_EXTERNAL_CONFIG', true);
// Installation path
define("K_PATH_MAIN", dirname(__FILE__)."/tcpdf");
// URL path
define("K_PATH_URL", WopshopUri::base());
// Fonts path
define("K_PATH_FONTS", dirname(__FILE__)."/tcpdf/fonts/");
// Cache directory path
define("K_PATH_CACHE", K_PATH_MAIN."/cache");
// Cache URL path
define("K_PATH_URL_CACHE", K_PATH_URL."/cache");
// Images path
define("K_PATH_IMAGES", K_PATH_MAIN."/images");
// Blank image path
define("K_BLANK_IMAGE", K_PATH_IMAGES."/_blank.png");

// Cell height ratio
define("K_CELL_HEIGHT_RATIO", 1.5);
// Magnification scale for titles
define("K_TITLE_MAGNIFICATION", 1);
// Reduction scale for small font
define("K_SMALL_RATIO", 2/3);
// Magnication scale for head
define("HEAD_MAGNIFICATION", 1);