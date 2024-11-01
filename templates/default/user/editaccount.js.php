<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$register_field_require = new stdClass();
foreach($config_fields as $key=>$val){
	if ($val['require']){
		$register_field_require->$key = 1;
	}
}
wp_add_inline_script('wopshop-functions.js', '
	    var register_field_require = '.wp_json_encode($register_field_require).';');