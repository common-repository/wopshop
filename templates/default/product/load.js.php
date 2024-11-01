<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$script = '';
if ($this->product->product_quantity >0){
    $script .= 'var translate_not_available = "'.esc_js(WOPSHOP_PRODUCT_NOT_AVAILABLE_THIS_OPTION).'";'.PHP_EOL;
}else{
    $script .= 'var translate_not_available = "'.esc_js(WOPSHOP_PRODUCT_NOT_AVAILABLE).'";'.PHP_EOL;
}
$script .= 'var translate_zoom_image = "'.esc_js(WOPSHOP_ZOOM_IMAGE).'";'.PHP_EOL;
$script .= 'var product_basic_price_volume = "'.esc_js($this->product->weight_volume_units).'";'.PHP_EOL;
$script .= 'var product_basic_price_unit_qty = "'.esc_js($this->product->product_basic_price_unit_qty).'";'.PHP_EOL;
$script .= 'var currency_code = "'.esc_js($this->config->currency_code).'";'.PHP_EOL;
$script .= 'var format_currency = "'.esc_js($this->config->format_currency[$this->config->currency_format]).'";'.PHP_EOL;
$script .= 'var decimal_count = "'.esc_js($this->config->decimal_count).'";'.PHP_EOL;
$script .= 'var decimal_symbol = "'.esc_js($this->config->decimal_symbol).'";'.PHP_EOL;
$script .= 'var thousand_separator = "'.esc_js($this->config->thousand_separator).'";'.PHP_EOL;
$script .= 'var attr_value = new Object();'.PHP_EOL;
$script .= 'var attr_list = new Array();'.PHP_EOL;
$script .= 'var attr_img = new Object();'.PHP_EOL;
$script .= 'var liveurl = "'.esc_js(WOPSHOP_PLUGIN_URL).'";'.PHP_EOL;
$script .= 'var liveattrpath = "'.esc_js($this->config->image_attributes_live_path).'";'.PHP_EOL;
$script .= 'var liveproductimgpath = "'.esc_js($this->config->image_product_live_path).'";'.PHP_EOL;
$script .= 'var liveimgpath = "'.esc_js($this->config->live_path.'assets/images').'";'.PHP_EOL;
$script .= 'var urlupdateprice = "'.esc_js($this->urlupdateprice).'";'.PHP_EOL;
if($this->config->load_jquery_lightbox){
    $script .= 'function initWopshoplightBox(){'.PHP_EOL;
    $script .= 'jQuery("a.lightbox").simpleLightbox({'.PHP_EOL;
//    $script .= 'imageLoading: "'.esc_js(WOPSHOP_PLUGIN_URL).'assets/images/loading.gif",'.PHP_EOL;
//    $script .= 'imageBtnClose: "'.esc_js(WOPSHOP_PLUGIN_URL).'assets/images/close.gif",'.PHP_EOL;
//    $script .= 'imageBtnPrev: "'.esc_js(WOPSHOP_PLUGIN_URL).'assets/images/prev.gif",'.PHP_EOL;
//    $script .= 'imageBtnNext: "'.esc_js(WOPSHOP_PLUGIN_URL).'assets/images/next.gif",'.PHP_EOL;
//    $script .= 'imageBlank: "'.esc_js(WOPSHOP_PLUGIN_URL).'assets/images/blank.gif",'.PHP_EOL;
//    $script .= 'txtImage: "'.esc_js(WOPSHOP_IMAGE).'",'.PHP_EOL;
//    $script .= 'txtOf: "'.esc_js(WOPSHOP_OF).'"'.PHP_EOL;
    $script .= '});'.PHP_EOL;
    $script .= '}'.PHP_EOL;
    $script .= 'jQuery(document).ready(function(){initWopshoplightBox();});'.PHP_EOL;
}
if (count($this->attributes)){
	$i=0;
    foreach($this->attributes as $attribut){
	    $script .= 'attr_value["'.esc_js($attribut->attr_id).'"] = "'.esc_js($attribut->firstval).'";'.PHP_EOL;
	    $script .= 'attr_list["'.esc_js($i).'"] = "'.esc_js($attribut->attr_id).'";'.PHP_EOL;
    }
}
foreach($this->all_attr_values as $attrval){
    if ($attrval->image){
	    $script .= 'attr_img["'.esc_js($attrval->value_id).'"] = "'.esc_js($attrval->image).'";'.PHP_EOL;
    }
}
$script .= $this->_tmp_product_ext_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
wp_add_inline_script('wopshop-functions.js', $script);