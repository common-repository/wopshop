<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function wopshopQuickiconButton( $description,$link, $image, $text ){
?>

<div class="wop_icon_item">
            
    <div class="carbonads">
        <div class="carbonads-image">
            <a href="<?php echo esc_url($link)?>" class="carbon-img" rel="noopener">
                <img class="img-responsive" src="<?php print esc_url(WOPSHOP_PLUGIN_URL.'assets/images/'.$image)?>" alt="" border="0"  style="max-width: 120px;">
            </a>
        </div>
        <div class="carbonads-text">
            <a href="<?php echo esc_url($link)?>" class="carbon-text" target="_blank" rel="noopener"><?php echo esc_html($text)?></a>
            <div class="carbon-poweredby" target="_blank" rel="noopener"><?php echo esc_html($description) ?></div>
        </div>
    </div>
</div>

<?php
}

function wopshop_add_trigger($vars = array(), $name = ''){
    list(,$caller) = debug_backtrace();
    $caller['class'] = isset($caller['class']) ? $caller['class'] : "";
	$trigger_name = 'on'.ucfirst($caller['class']).ucfirst($caller['function']).ucfirst($name);
    do_action_ref_array($trigger_name, array(&$caller['object'], &$vars));
    return $vars;
}

function wopshopSEFLink($url, $useDefaultPage = 0, $redirect = 0, $ssl = null, $langPrefix = null){
    $xhtml = $redirect ? false : true;
    $router = new WshopRouter();
    $url = $router->build($url, $ssl, $langPrefix);

    if ($xhtml) {
        return htmlspecialchars($url);
    }

    return $url;
}

function wopshopXhtmlUrl($url, $filter=1){
    if ($filter){
        $url = wopshopFilterUrl($url);
    }
    $url = str_replace("&","&amp;",$url);    

    return $url;
}

function wopshopFilterUrl($url){
    $url = strip_tags($url);
return $url;
}

function wopshopGetQuerySortDirection($fieldnum, $ordernum){
    $dir = "ASC";
    if ($ordernum) {
        $dir = "DESC";
        if ($fieldnum==5 || $fieldnum==6) $dir = "ASC";
    } else {
        $dir = "ASC";
        if ($fieldnum==5 || $fieldnum==6) $dir = "DESC";
    }
return $dir;
}

function wopshopGetImgSortDirection($fieldnum, $ordernum){
    if ($ordernum) {
        $image = 'arrow_down.gif';
    } else {
        $image = 'arrow_up.gif';
    }
return $image;
}

function wopshopGetBuildFilterListProduct($contextfilter, $no_filter = array()){
    $config = WopshopFactory::getConfig();
    $mainframe =WopshopFactory::getApplication();
    
    $category_id = WopshopRequest::getInt('category_id');
    $manufacturer_id = WopshopRequest::getInt('manufacturer_id');
    $label_id = WopshopRequest::getInt('label_id');
    $vendor_id = WopshopRequest::getInt('vendor_id');
    $freeatribute_id = WopshopRequest::getInt('freeatribute_id');
    $price_from = wopshopSaveAsPrice(WopshopRequest::getVar('price_from'));
    $price_to = wopshopSaveAsPrice(WopshopRequest::getVar('price_to'));
    
    $categorys = $mainframe->getUserStateFromRequest( $contextfilter.'categorys', 'categorys', array());
    $categorys = wopshopFilterAllowValue($categorys, "int+");
    $tmpcd = wopshopGetListFromStr(WopshopRequest::getVar('category_id'));    
    if (is_array($tmpcd) && !$categorys) $categorys = $tmpcd;
    
    $manufacturers = $mainframe->getUserStateFromRequest( $contextfilter.'manufacturers', 'manufacturers', array());
    $manufacturers = wopshopFilterAllowValue($manufacturers, "int+");
    $tmp = wopshopGetListFromStr(WopshopRequest::getVar('manufacturer_id'));    
    if (is_array($tmp) && !$manufacturers) $manufacturers = $tmp;
    
    $labels = $mainframe->getUserStateFromRequest( $contextfilter.'labels', 'labels', array());
    $labels = wopshopFilterAllowValue($labels, "int+");
    $tmplb = wopshopGetListFromStr(WopshopRequest::getVar('label_id'));    
    if (is_array($tmplb) && !$labels) $labels = $tmplb;
    
    $vendors = $mainframe->getUserStateFromRequest( $contextfilter.'vendors', 'vendors', array());
    $vendors = wopshopFilterAllowValue($vendors, "int+");
    $tmp = wopshopGetListFromStr(WopshopRequest::getVar('vendor_id'));    
    if (is_array($tmp) && !$vendors) $vendors = $tmp;
    
    if ($config->admin_show_product_extra_field){
        $extra_fields = $mainframe->getUserStateFromRequest( $contextfilter.'extra_fields', 'extra_fields', array());
        $extra_fields = wopshopFilterAllowValue($extra_fields, "array_int_k_v+");
    }
    $fprice_from = $mainframe->getUserStateFromRequest( $contextfilter.'fprice_from', 'fprice_from');
    $fprice_from = wopshopSaveAsPrice($fprice_from);
    if (!$fprice_from) $fprice_from = $price_from;
    $fprice_to = $mainframe->getUserStateFromRequest( $contextfilter.'fprice_to', 'fprice_to');
    $fprice_to = wopshopSaveAsPrice($fprice_to);
    if (!$fprice_to) $fprice_to = $price_to;

    $filters = array();
    $filters['categorys'] = $categorys;
    $filters['manufacturers'] = $manufacturers;
    $filters['price_from'] = $fprice_from;
    $filters['price_to'] = $fprice_to;
    $filters['labels'] = $labels;
    $filters['vendors'] = $vendors;
    if ($config->admin_show_product_extra_field){
        $filters['extra_fields'] = $extra_fields;
    }
    if ($category_id && !$filters['categorys']){
        $filters['categorys'][] = $category_id;
    }
    if ($manufacturer_id && !$filters['manufacturers']){
        $filters['manufacturers'][] = $manufacturer_id;
    }
    if ($label_id && !$filters['labels']){
        $filters['labels'][] = $label_id;
    }
    if ($freeatribute_id && !$filters['freeatributes']){
        $filters['freeatributes'] = $freeatribute_id;
    }
    if ($vendor_id && !$filters['vendors']){
        $filters['vendors'][] = $vendor_id;
    }
    if (is_array($filters['vendors'])){
        $main_vendor = WopshopFactory::getMainVendor();
        foreach($filters['vendors'] as $vid){
            if ($vid == $main_vendor->id){
                $filters['vendors'][] = 0;
            }
        }
    }
    foreach($no_filter as $filterkey){
        unset($filters[$filterkey]);
    }
return $filters;
}

function wopshopGetListFromStr($stelist){
    if (preg_match('/\,/', $stelist)){
        return wopshopFilterAllowValue(explode(',',$stelist), 'int+');
    }else{
        return null;
    }
}


// Remove update notifications

//function remove_core_updates(){
//    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
//}
//add_filter('pre_site_transient_update_core','remove_core_updates');
//add_filter('pre_site_transient_update_plugins','remove_core_updates');
//add_filter('pre_site_transient_update_themes','remove_core_updates');

// End of Update notifications


function wopshopFilterAllowValue($data, $type){
    
    if ($type=="int+"){
        if (is_array($data)){
            foreach($data as $k=>$v){
                $v = intval($v);
                if ($v>0){
                    $data[$k] = $v;
                }else{
                    unset($data[$k]);
                }
            }
        }
    }
    
    if ($type=="array_int_k_v+"){
        if (is_array($data)){
            foreach($data as $k=>$v){
                $k = intval($k);
                if (is_array($v)){
                    foreach($v as $k2=>$v2){
                        $k2 = intval($k2);
                        $v2 = intval($v2);
                        if ($v2>0){
                            $data[$k][$k2] = $v2;
                        }else{
                            unset($data[$k][$k2]);
                        }
                    }
                }
            }
        }
    }
    
    return $data;
}

function wopshopListProductUpdateData($products, $setUrl = 0) {
    $config = WopshopFactory::getConfig();
    $userShop = WopshopFactory::getUserShop();
    $taxes = WopshopFactory::getAllTaxes();
    if ($config->product_list_show_manufacturer){
        $manufacturers = WopshopFactory::getAllManufacturer();
    }
    if ($config->show_delivery_time){
        $deliverytimes = WopshopFactory::getAllDeliveryTime();
    }
    if ($config->product_list_show_vendor){
        $vendors = WopshopFactory::getAllVendor();
    }    

    $image_path = $config->image_product_live_path;
    $noimage = $config->noimage;

    foreach ($products as $key => $value) {
        $products[$key]->_tmp_var_start = "";
        $products[$key]->_tmp_var_image_block = "";
        $products[$key]->_tmp_var_bottom_foto = "";
        $products[$key]->_tmp_var_old_price_ext = "";
        $products[$key]->_tmp_var_bottom_price = "";
        $products[$key]->_tmp_var_bottom_old_price = "";
        $products[$key]->_tmp_var_price_ext  = "";
        $products[$key]->_tmp_var_top_buttons = "";
        $products[$key]->_tmp_var_buttons = "";
        $products[$key]->_tmp_var_bottom_buttons = "";
        $products[$key]->_tmp_var_end = "";
        $use_userdiscount = 1;
        if ($config->user_discount_not_apply_prod_old_price && $products[$key]->product_old_price > 0){
            $use_userdiscount = 0;
        }
        do_action_ref_array('onwopshopListProductUpdateDataProduct', array(&$products, &$key, &$value, &$use_userdiscount));
        $products[$key]->_original_product_price = $products[$key]->product_price;
        $products[$key]->product_price_wp = $products[$key]->product_price;
        $products[$key]->product_price_default = 0;
        if ($config->product_list_show_min_price) {
            if ($products[$key]->min_price > 0){
                $products[$key]->product_price = $products[$key]->min_price;
            }
        }
        $products[$key]->show_price_from = 0;
        if ($config->product_list_show_min_price && $value->different_prices) {
            $products[$key]->show_price_from = 1;
        }

        $products[$key]->product_price = wopshopGetPriceFromCurrency($products[$key]->product_price, $products[$key]->currency_id);
        $products[$key]->product_old_price = wopshopGetPriceFromCurrency($products[$key]->product_old_price, $products[$key]->currency_id);
        $products[$key]->product_price_wp = wopshopGetPriceFromCurrency($products[$key]->product_price_wp, $products[$key]->currency_id);
        $products[$key]->product_price = wopshopGetPriceCalcParamsTax($products[$key]->product_price, $products[$key]->tax_id);
        $products[$key]->product_old_price = wopshopGetPriceCalcParamsTax($products[$key]->product_old_price, $products[$key]->tax_id);
        $products[$key]->product_price_wp = wopshopGetPriceCalcParamsTax($products[$key]->product_price_wp, $products[$key]->tax_id);

        if ($userShop->percent_discount && $use_userdiscount) {
            $products[$key]->product_price_default = $products[$key]->_original_product_price;
            $products[$key]->product_price_default = wopshopGetPriceFromCurrency($products[$key]->product_price_default, $products[$key]->currency_id);
            $products[$key]->product_price_default = wopshopGetPriceCalcParamsTax($products[$key]->product_price_default, $products[$key]->tax_id);

            $products[$key]->product_price = wopshopGetPriceDiscount($products[$key]->product_price, $userShop->percent_discount);
            $products[$key]->product_old_price = wopshopGetPriceDiscount($products[$key]->product_old_price, $userShop->percent_discount);
            $products[$key]->product_price_wp = wopshopGetPriceDiscount($products[$key]->product_price_wp, $userShop->percent_discount);
        }    
        
        
        if ($config->list_products_calc_basic_price_from_product_price) {
            $products[$key]->basic_price_info = wopshopGetProductBasicPriceInfo($value, $products[$key]->product_price_wp);
        } else {
            $products[$key]->basic_price_info = wopshopGetProductBasicPriceInfo($value, $products[$key]->product_price);
        }

        if ($value->tax_id) {
            $products[$key]->tax = $taxes[$value->tax_id];
        }

        if ($config->product_list_show_manufacturer && $value->product_manufacturer_id && isset($manufacturers[$value->product_manufacturer_id])) {
            $products[$key]->manufacturer = $manufacturers[$value->product_manufacturer_id];
        } else {
            $products[$key]->manufacturer = new stdClass();
            $products[$key]->manufacturer->name = '';
        }
        if ($config->admin_show_product_extra_field){
            $products[$key]->extra_field = wopshopGetProductExtraFieldForProduct($value);
        } else {
            $products[$key]->extra_field = '';
        }
        if ($config->product_list_show_vendor){
            $vendordata = $vendors[$value->vendor_id];
            $vendordata->products = esc_url(wopshopSEFLink("controller=vendor&task=products&vendor_id=".$vendordata->id,1));
            $products[$key]->vendor = $vendordata;
        }else{
            $products[$key]->vendor = '';
        }
        if ($config->hide_delivery_time_out_of_stock && $products[$key]->product_quantity <= 0) {
            $products[$key]->delivery_times_id = 0;
            $value->delivery_times_id = 0;
        }
        if ($config->show_delivery_time && $value->delivery_times_id) {
            $products[$key]->delivery_time = $deliverytimes[$value->delivery_times_id];
        } else {
            $products[$key]->delivery_time = '';
        }
        $products[$key]->_display_price = wopshopGetDisplayPriceForProduct($products[$key]->product_price);
        if (!$products[$key]->_display_price) {
            $products[$key]->product_old_price = 0;
            $products[$key]->product_price_default = 0;
            $products[$key]->basic_price_info['price_show'] = 0;
            $products[$key]->tax = 0;
            $config->show_plus_shipping_in_product = 0;
        }
        if ($config->product_list_show_qty_stock) {
            $products[$key]->qty_in_stock = wopshopGgetDataProductQtyInStock($products[$key]);
        }
        $image = wopshopGetPatchProductImage($products[$key]->image);
        $products[$key]->product_name_image = $products[$key]->image;
        $products[$key]->product_thumb_image = $image;
        if (!$image)
            $image = $noimage;
        $products[$key]->image = $image_path . "/" . $image;
        $products[$key]->template_block_product = "product.php";
        if (!$config->admin_show_product_labels)
            $products[$key]->label_id = null;
        if ($products[$key]->label_id) {
            $image = wopshopGetNameImageLabel($products[$key]->label_id);
            if ($image) {
                $products[$key]->_label_image = $config->image_labels_live_path . "/" . $image;
            }
            $products[$key]->_label_name = wopshopGetNameImageLabel($products[$key]->label_id, 2);
        }
        if ($config->display_short_descr_multiline) {
            $products[$key]->short_description = nl2br($products[$key]->short_description);
        }
    }

    if ($setUrl) {
        wopshopAddLinkToProducts($products);
    }
    
    do_action_ref_array('onwopshopListProductUpdateData', array(&$products));
    
    return $products;
}

function wopshopGetProductExtraFieldForProduct($product){
    $fields = WopshopFactory::wopshopGetAllProductExtraField();
    $fieldvalues = WopshopFactory::wopshopGetAllProductExtraFieldValue();
    $displayfields = WopshopFactory::getDisplayListProductExtraFieldForCategory($product->category_id);
    $rows = array();
    foreach($displayfields as $field_id){
        $field_name = "extra_field_".$field_id;
        if ($fields[$field_id]->type==0){
            if ($product->$field_name!=0){
                $listid = explode(',', $product->$field_name);
                $tmp = array();
                foreach($listid as $extrafiledvalueid){
                    $tmp[] = $fieldvalues[$extrafiledvalueid];
                }
                $extra_field_value = implode(", ", $tmp);
                $rows[$field_id] = array("name"=>$fields[$field_id]->name, "description"=>$fields[$field_id]->description, "value"=>$extra_field_value);
            }
        }else{
            if ($product->$field_name!=""){
                $rows[$field_id] = array("name"=>$fields[$field_id]->name, "description"=>$fields[$field_id]->description, "value"=>$product->$field_name);
            }
        }
    }
return $rows;
}

function wopshopReplaceNbsp($string) {
return (str_replace(" ","_",$string));
}

function wopshopReplaceToNbsp($string) {
return (str_replace("_"," ",$string));
}

function wopshopGetTextNameArrayValue($names, $values){
    $return = '';
    foreach ($names as $key=>$value){
        $return .= $names[$key].": ".$values[$key]."\n";
    }
    return $return;
}

function wopshopAddLinkToProducts(&$products){
    $config = WopshopFactory::getConfig();
    
    foreach($products as $key=>$value){
        $products[$key]->product_link = esc_url(wopshopSEFLink('controller=product&task=view&product_id='.$products[$key]->product_id));
        $products[$key]->buy_link = '';
        if ($config->show_buy_in_category && $products[$key]->_display_price){
            if (!($config->hide_buy_not_avaible_stock && ($products[$key]->product_quantity <= 0))){
                $products[$key]->buy_link = esc_url(wopshopSEFLink('controller=cart&task=add&product_id='.$products[$key]->product_id));
            }
        }
    }
}

function wopshopGetPriceDiscount($price, $discount){
    return $price - ($price*$discount/100);
}

function wopshopGetMessageJson(){
   global $cart_error;
   $rows = array();
	if ( $cart_error->get_error_code() ) {
		foreach( $cart_error->get_error_messages() as $error ){
			$rows[] = array(
                "code"=>$cart_error->get_error_code(),
                "message"=>$error
                    );
		}
	}
	$session = WopshopFactory::getSession();
	$session->set('application.queue', null);
	return json_encode($rows);
}

function wopshopGetOkMessageJson($cart){
	global $cart_error;
    if ( $cart_error->get_error_code() ) {
        return wopshopGetMessageJson(); 
    }else{
        return json_encode($cart);
    }
}

function wopshopGetCalculateDeliveryDay($day, $date=null){
    if (!$date){
        $date = wopshopGetJsDate();
    }
    $time = intval(strtotime($date) + $day*86400);
return date('Y-m-d H:i:s', $time);
}

function wopshopGetProductBySlug($alias) {
    global $wpdb;
    $config = WopshopFactory::getConfig();
    $lang = $config->cur_lang;
    $query = "SELECT product_id FROM `".$wpdb->prefix.'wshop_products'."` WHERE `alias_".$lang."` = '".esc_sql($alias)."'";
    $id = $wpdb->get_var($query);
    return ($id) ? $id : $alias;
}

function wopshopInsertValueInArray($value, &$array) {
    if ($key = array_search($value, $array)) return $key;
    $array[$value] = $value;
    asort($array);
    return $key-1;
}

function wopshopWillBeUseFilter($filters){
    $res = 0;    
    if (isset($filters['price_from']) && $filters['price_from']>0) $res = 1;
    if (isset($filters['price_to']) && $filters['price_to']>0) $res = 1;
    if (isset($filters['categorys']) && count($filters['categorys'])>0) $res = 1;
    if (isset($filters['manufacturers']) && count($filters['manufacturers'])>0) $res = 1;
    if (isset($filters['vendors']) && count($filters['vendors'])>0) $res = 1;    
    if (isset($filters['labels']) && count($filters['labels'])>0) $res = 1;
    if (isset($filters['extra_fields']) && count($filters['extra_fields'])>0) $res = 1;
return $res;
}

function wopshopGetQueryListProductsExtraFields(){
    $query = "";
    $list = wopshopGetAllProductExtraField();
    $config_list = wopshopGetProductListDisplayExtraFields();
    foreach($list as $v){
        if (in_array($v->id, $config_list)){
            $query .= ", prod.`extra_field_".$v->id."` ";
        }
    }

    return $query;
}

function wopshopGetAllProductExtraField(){
static $list;
    if (!is_array($list)){
        $productfield = WopshopFactory::getTable('productfield');
        $list = $productfield->getList();
    }
return $list;
}

function wopshopGetProductListDisplayExtraFields(){
    $config = WopshopFactory::getConfig();
    if ($config->product_list_display_extra_fields!=""){
        return json_decode($config->product_list_display_extra_fields, 1);
    }else{
        return array();
    }
}

function wopshopGgetDataProductQtyInStock($product){
    $qty = $product->product_quantity;
    if ($product instanceof ProductWshopTable){
        $qty = $product->getQty();
    }

    $qty_in_stock = array(
        "qty" => floatval($qty), 
        "unlimited" => $product->unlimited
    );
    
    if ($qty_in_stock['qty'] < 0) {
        $qty_in_stock['qty'] = 0;
    }

    return $qty_in_stock;
}

function wopshopSprintQtyInStock($qty_in_stock){
    if (!is_array($qty_in_stock)){
        return $qty_in_stock;
    }else{
        if ($qty_in_stock['unlimited']){
            return WOPSHOP_UNLIMITED;
        }else{
            return $qty_in_stock['qty'];
        }
    }
}

/**
* check date Format date yyyy-mm-dd
*/
function wopshopCheckDate($date) {
    if (trim($date)=="") return false;
    $arr = explode("-",$date);
return checkdate($arr[1],$arr[2],$arr[0]);
}

function wopshopGetProductBasicPriceInfo($obj, $price){
    $config = WopshopFactory::getConfig();
    $price_show = $obj->weight_volume_units!=0;

    if (!$config->admin_show_product_basic_price || $price_show==0){
        return array("price_show"=>0);
    }

    $units = WopshopFactory::getAllUnits();
    $unit = $units[$obj->basic_price_unit_id];
    $basic_price = $price / $obj->weight_volume_units * $unit->qty;

    return array("price_show"=>$price_show, "basic_price"=>$basic_price, "name"=>$unit->name, "unit_qty"=>$unit->qty);
}

function wopshopGetDisplayPriceForProduct($price){
    $config = WopshopFactory::getConfig();
    $user = WopshopFactory::getUser();
    $display_price = 1;
    if ($config->displayprice==1){
        $display_price = 0;
    }elseif($config->displayprice==2 && !$user->user_id){
        $display_price = 0;
    }
    if ($display_price && $price==0 && $config->user_as_catalog){
        $display_price = 0;
    }
    
    return $display_price;
}

function wopshopGetDisplayPriceShop(){
    $config = WopshopFactory::getConfig();
    $user = WopshopFactory::getUser();

    $display_price = 1;
    if ($config->displayprice == 1){
        $display_price = 0;
    } else if ($config->displayprice == 2 && !$user->user_id){
        $display_price = 0;
    }

    return $display_price;
}

function wopshopGetPriceTaxRatioForProducts($products, $group = 'tax'){
    $prodtaxes = array();
    foreach($products as $k => $v){
        if (!isset($prodtaxes[$v[$group]])) {
            $prodtaxes[$v[$group]] = 0;
        }
        $prodtaxes[$v[$group]] += $v['price'] * $v['quantity'];
    }

    $sumproducts = array_sum($prodtaxes);        
    foreach($prodtaxes as $k => $v){
		if ($sumproducts > 0){
			$prodtaxes[$k] = $v / $sumproducts;
		} else {
			$prodtaxes[$k] = 0;
		}
    }

    return $prodtaxes;
}

function wopshopGetFixBrutopriceToTax($price, $tax_id){
    $config = WopshopFactory::getConfig();
    if ($config->no_fix_brutoprice_to_tax==1){
        return $price;
    }
    $taxoriginal = WopshopFactory::getAllTaxesOriginal();
    $taxes = WopshopFactory::getAllTaxes();
    $tax = $taxes[$tax_id];
    $tax2 = $taxoriginal[$tax_id];
    if ($tax!=$tax2){
        $price = $price / (1 + $tax2 / 100);
        $price = $price * (1+$tax/100);    
    }
return $price;
}

function wopshopGetPriceTaxValue($price, $tax, $price_netto = 0){
    if ($price_netto==0){
        $tax_value = $price * $tax / (100 + $tax);
    }else{
        $tax_value = $price * $tax / 100;
    }
return $tax_value;
}

function wopshopCheckUserLogin(){
    $config = WopshopFactory::getConfig();
    $user = wp_get_current_user();
    header("Cache-Control: no-cache, must-revalidate");
    
    if (!$user->ID) {
        $application = WopshopFactory::getApplication();
        $return = base64_encode($_SERVER['REQUEST_URI']);
        $session = WopshopFactory::getSession();
        $session->set("return", $return);
        
        $application->redirect(esc_url(wopshopSEFLink('controller=user&task=login', 1, 1, $config->use_ssl)));
        exit();
    }
}

function wopshopUpdateAllprices( $ignore = array() ){
    $cart = WopshopFactory::getModel('cart');
    $cart->load();
    $cart->updateCartProductPrice();
    
    $sh_pr_method_id = $cart->getShippingPrId();
    if ($sh_pr_method_id){
        $shipping_method_price = WopshopFactory::getTable('shippingmethodprice');
        $shipping_method_price->load($sh_pr_method_id);
        $prices = $shipping_method_price->calculateSum($cart);
        $cart->setShippingsDatas($prices, $shipping_method_price);
    }
    $payment_method_id = $cart->getPaymentId();
    if ($payment_method_id){
        $paym_method = WopshopFactory::getTable('paymentmethod');
        $paym_method->load($payment_method_id);
        $paym_method->setCart($cart);
        $price = $paym_method->getPrice();
        $cart->setPaymentDatas($price, $paym_method);
    }
    
    $cart = WopshopFactory::getModel('cart');
    $cart->load('wishlist');
    $cart->updateCartProductPrice();   
}

function wopshopSetNextUpdatePrices(){
    $session =WopshopFactory::getSession();
    $session->set('wshop_update_all_price', 1);
}

function wopshopSprintAtributeInCart($atribute){
    $html = "";
    if (count($atribute)) $html .= '<div class="list_attribute">';
    foreach($atribute as $attr){
        do_action_ref_array('beforewopshopSprintAtributeInCart', array(&$attr) );
        $html .= '<p class="wshop_cart_attribute"><span class="name">'.esc_html($attr->attr).'</span>: <span class="value">'.esc_html($attr->value).'</span></p>';
    }
    if (count($atribute)) $html .= '</div>';
    return $html;
}

function wopshopSprintFreeAtributeInCart($freeatribute){
    $html = "";
    if (count($freeatribute)) $html .= '<div class="list_free_attribute">';
    foreach($freeatribute as $attr){
        do_action_ref_array('beforewopshopSprintFreeAtributeInCart', array(&$attr) );
        $html .= '<p class="wshop_cart_attribute"><span class="name">'.esc_html($attr->attr).'</span>: <span class="value">'.esc_html($attr->value).'</span></p>';
    }
    if (count($freeatribute)) $html .= '</div>';
    return $html;
}

function wopshopSprintFreeExtraFiledsInCart($extra_fields){
    $html = "";
    if (count($extra_fields)) $html .= '<div class="list_extra_field">';
    foreach($extra_fields as $f){
        do_action_ref_array('beforeSprintExtraFieldsInCart', array(&$f) );
        $html .= '<p class="wshop_cart_extra_field"><span class="name">'.esc_html($f['name']).'</span>: <span class="value">'.esc_html($f['value']).'</span></p>';
    }
    if (count($extra_fields)) $html .= '</div>';
    return $html;
}

function wopshopSearchChildCategories($category_id,$all_categories,&$cat_search) {
    foreach ($all_categories as $all_cat) {
        if($all_cat->category_parent_id == $category_id) {
            wopshopSearchChildCategories($all_cat->category_id, $all_categories, $cat_search);
            $cat_search[] = $all_cat->category_id;
        }
    }
}

function wopshopGetDBFieldNameFromConfig($name){
    $config = WopshopFactory::getConfig();
    $tmp = explode('.', $name);
    if (count($tmp)>1){
        $res = $tmp[0].'.';
        $field = $tmp[1];
    }else{
        $res = '';
        $field = $tmp[0];
    }
    $tmp2 = explode(':', $field);
    if (count($tmp2)>1 && $tmp2[0]=='ml'){
        $res .= '`'.$tmp2[1].'_'.$config->cur_lang.'`';
    }else{
        $res .= '`'.$field.'`';
    }
return $res;
}

function wopshopShowMarkStar($rating){
    $config = WopshopFactory::getConfig();
    $count = floor($config->max_mark / $config->rating_starparts);
    $width = $count * 16;
    $rating = round($rating);
    $width_active = intval($rating * 16 / $config->rating_starparts);
    $html = "<div class='stars_no_active' style='width:".esc_attr($width)."px'>";
    $html .= "<div class='stars_active' style='width:".esc_attr($width_active)."px'>";
    $html .= "</div>";
    $html .= "</div>";
return $html;
}

function wopshopSprintRadioList($list, $name, $params, $key, $val, $actived = null, $separator = ' '){
    $html = "";
    $id = str_replace("[","",$name);
    $id = str_replace("]","",$id);
    foreach($list as $obj){
        $id_text = $id.$obj->$key;
        if ($obj->$key == $actived) $sel = ' checked="checked"'; else $sel = '';
        $html.='<span class="input_type_radio"><input type="radio" name="'.esc_attr($name).'" id="'.esc_attr($id_text).'" value="'.esc_attr($obj->$key).'"'.esc_attr($sel).' '.$params.'> <label for="'.esc_attr($id_text).'">'.wp_kses_post($obj->$val)."</label></span>".wp_kses_post($separator);
    }
return $html;
}

function wopshopSaveToLog($file, $text){
    $config = WopshopFactory::getConfig();
    if (!$config->savelog) {
        return 0;
    }
    if ($file == 'paymentdata.log' && !$config->savelogpaymentdata) {
        return 0;
    }
    $f = fopen($config->log_path.$file, "a+");
    fwrite($f, date('Y-m-d H:i:s')." ".$text."\r\n");
    fclose($f);
    
    return 1;
}

function wopshop_json_value_encode($val, $textfix = 0){
    if ($textfix){
        $val = str_replace(array("\n","\r","\t"), "", $val);
    }
    $val = str_replace('"', '\"', $val);
    return $val;
}

function wopshopGetPriceCalcParamsTax($price, $tax_id, $products = array()) {
    $config = WopshopFactory::getConfig();
    $taxes = WopshopFactory::getAllTaxes();
    if ($tax_id == -1) {
        $prodtaxes = wopshopGetPriceTaxRatioForProducts($products);
    }
    if ($config->display_price_admin == 0 && $tax_id > 0) {
        $price = wopshopGetFixBrutopriceToTax($price, $tax_id);
    }
    if ($config->display_price_admin == 0 && $tax_id == -1) {
        $prices = array();
        $prodtaxesid = wopshopGetPriceTaxRatioForProducts($products, 'tax_id');
        foreach ($prodtaxesid as $k => $v) {
            $prices[$k] = wopshopGetFixBrutopriceToTax($price * $v, $k);
        }
        $price = array_sum($prices);
    }
    if ($tax_id > 0) {
        $tax = $taxes[$tax_id];
    } elseif ($tax_id == -1) {
        $prices = array();
        foreach ($prodtaxes as $k => $v) {
            $prices[] = array('tax' => $k, 'price' => $price * $v);
        }
    } else {
        $taxlist = array_values($taxes);
        $tax = $taxlist[0];
    }
    if ($config->display_price_admin == 1 && $config->display_price_front_current == 0) {
        if ($tax_id == -1) {
            $price = 0;
            foreach ($prices as $v) {
                $price+= $v['price'] * (1 + $v['tax'] / 100);
            }
        } else {
            $price = $price * (1 + $tax / 100);
        }
    }
    if ($config->display_price_admin == 0 && $config->display_price_front_current == 1) {
        if ($tax_id == -1) {
            $price = 0;
            foreach ($prices as $v) {
                $price+= $v['price'] / (1 + $v['tax'] / 100);
            }
        } else {
            $price = $price / (1 + $tax / 100);
        }
    }
    return $price;
}

function wopshopGetPriceFromCurrency($price, $currency_id = 0, $current_currency_value = 0) {
    $config = WopshopFactory::getConfig();
    if ($currency_id) {
        $all_currency = WopshopFactory::getAllCurrency();
        $value = $all_currency[$currency_id]->currency_value;
        if (!$value)
            $value = 1;
        $pricemaincurrency = $price / $value;
    }else {
        $pricemaincurrency = $price;
    }
    if (!$current_currency_value) {
        $current_currency_value = $config->currency_value;
    }
    return $pricemaincurrency * $current_currency_value;
}

function wopshopDisplayPanelSettings() {
    $menu = array();
    $menu['kunden'] = array(WOPSHOP_MENU_CUSTOMER, WOPSHOP_MENU_CUSTOMER_DESCRIPTION, admin_url( 'admin.php?page=wopshop-clients'), 'new_icons/Kunden.png', 1);
    $menu['bestellungen'] = array( WOPSHOP_MENU_ORDERS, WOPSHOP_MENU_ORDER_DESCRIPTION, admin_url('admin.php?page=wopshop-orders'), 'new_icons/bestellungen.png', 1 );
    
    do_action_ref_array('onBeforeAdminMainPanelIcoDisplay', array(&$menu));

    foreach($menu as $item) {
        if ($item[4]) {
            wopshopQuickiconButton($item[1], $item[2], $item[3], $item[0]);            
        }
    }
}

function wopshopDisplayPanelSupport() {
    $menu = array();
    $menu['hilfe'] = array(WOPSHOP_MENU_HELP, WOPSHOP_MENU_HELP_DESCRIPTION, admin_url('admin.php?page=wopshop-hilfe'), 'new_icons/hilfe.png', 1);
    $menu['webdesign'] = array(WOPSHOP_MENU_WEBDESIGN, WOPSHOP_MENU_WEBDESIGN_DESCRIPTION, admin_url('admin.php?page=wopshop-shop'), 'new_icons/webdesign.png', 1);
    $menu['programmierung'] = array(WOPSHOP_MENU_PROGRAMMING, WOPSHOP_MENU_PROGRAMMING_DESCRIPTION, admin_url('admin.php?page=wopshop-programmierung'), 'new_icons/programmierung.png', 1);
    $menu['seo'] = array(WOPSHOP_MENU_OPTIMISING, WOPSHOP_MENU_OPTIMISING_DESCRIPTION, admin_url('admin.php?page=wopshop-seo'), 'new_icons/optimierung.png', 1 );    
    $menu['marketing'] = array(WOPSHOP_MENU_ONLINEMARKETING, WOPSHOP_MENU_ONLINEMARKETING_DESCRIPTION, admin_url('admin.php?page=wopshop-marketing'), 'new_icons/sem.png', 1);    
    $menu['content'] = array(WOPSHOP_MENU_CONTENT_CREATION, WOPSHOP_MENU_CONTENT_CREATION_DESCRIPTION, admin_url('admin.php?page=wopshop-content'), 'new_icons/shop-content.png', 1);

    do_action_ref_array('onBeforeAdminMainPanelIcoDisplay', array(&$menu));

    foreach($menu as $item){
        if ($item[4]){
            wopshopQuickiconButton($item[1], $item[2], $item[3], $item[0]);            
        }
    }   
}

function wopshopDisplayMainPanelIco(){
    $menu = array();
    $menu['products'] = array(WOPSHOP_MENU_PRODUCTS, WOPSHOP_MENU_PRODUCTS_DESCRIPTION, admin_url('admin.php?page=wopshop-products'), 'new_icons/Produkte.png', 1);
    $menu['categories'] = array(WOPSHOP_MENU_CATEGORIES, WOPSHOP_MENU_CATEGORYIES_DESCRPTION, admin_url( 'admin.php?page=wopshop-categories'), 'new_icons/Kategorien.png', 1);
    $menu['options'] = array(WOPSHOP_MENU_OTHER, WOPSHOP_MENU_OTHER_DESCRIPTION, admin_url('admin.php?page=wopshop-options'), 'new_icons/optionen.png', 1);
    $menu['config'] = array( WOPSHOP_MENU_CONFIG, WOPSHOP_MENU_CONFIG_DESCRIPTION, admin_url('admin.php?page=wopshop-configuration'), 'new_icons/Einstellungen.png', 1 );
    $menu['update'] = array(WOPSHOP_PANEL_UPDATE, WOPSHOP_PANEL_UPDATE_DESCRIPTION, admin_url('admin.php?page=wopshop-update'), 'new_icons/Installieren.png', 1);
    
    do_action_ref_array('onBeforeAdminMainPanelIcoDisplay', array(&$menu));

    foreach($menu as $item){
        if ($item[4]){
            wopshopQuickiconButton($item[1], $item[2], $item[3], $item[0]);            
        }
    }
}

function wopshopAddMessage($message, $type = 'updated') {
    WopshopFactory::getApplication()->enqueueMessage($message, $type);    
}

function wopshopGetStateFromRequest($key, $request, $default=null) {
    $app = WopshopFactory::getApplication();
    return $app->getUserStateFromRequest($key, $request, $default);
}

function wopshopGetItemsOptionPanelMenu(){
    $config = WopshopFactory::getConfig();
    $menu = array();
    $menu['manufacturers'] = array(WOPSHOP_MENU_MANUFACTURERS, admin_url( 'admin.php?page=wopshop-options&tab=manufacturers'), 'new_icons/Manufactures.png', 1, WOPSHOP_MENU_MANUFACTURERS_DESCRIPTION);
    $menu['coupons'] = array(WOPSHOP_MENU_COUPONS, admin_url( 'admin.php?page=wopshop-options&tab=coupons'), 'new_icons/Coupons.png', $config->use_rabatt_code, WOPSHOP_MENU_COUPONS_DESCRIPTION);
    $menu['currencies'] = array(WOPSHOP_PANEL_CURRENCIES, admin_url('admin.php?page=wopshop-options&tab=currencies'), 'new_icons/Currencies.png', 1, WOPSHOP_PANEL_CURRENCIES_DESCRIPTION);
    $menu['taxes'] = array(WOPSHOP_PANEL_TAXES, admin_url('admin.php?page=wopshop-options&tab=taxes'), 'new_icons/Taxes.png', $config->tax, WOPSHOP_PANEL_TAXES_DESCRIPTION);
    $menu['payments'] = array( WOPSHOP_PANEL_PAYMENTS, admin_url('admin.php?page=wopshop-options&tab=payments'), 'new_icons/Payments.png', $config->without_payment==0, WOPSHOP_PANEL_PAYMENTS_DESCRIPTION);
    $menu['shippings'] = array(WOPSHOP_PANEL_SHIPPINGS, admin_url('admin.php?page=wopshop-options&tab=shippings'), 'new_icons/Shipping_methods.png', $config->without_shipping==0, WOPSHOP_PANEL_SHIPPINGS_DESCRIPTION);
    $menu['shippingsprices'] = array(WOPSHOP_PANEL_SHIPPINGS_PRICES, admin_url('admin.php?page=wopshop-options&tab=shippingsprices'), 'new_icons/Shipping_prices.png', $config->without_shipping==0, WOPSHOP_PANEL_SHIPPINGS_PRICES_DESCRIPTION);
    $menu['deliverytimes'] = array(WOPSHOP_PANEL_DELIVERY_TIME, admin_url('admin.php?page=wopshop-options&tab=deliverytimes'), 'new_icons/Delivery_time.png', $config->admin_show_delivery_time, WOPSHOP_PANEL_DELIVERY_TIME_DESCRIPTION);
    $menu['orderstatus'] = array(WOPSHOP_PANEL_ORDER_STATUS, admin_url('admin.php?page=wopshop-options&tab=orderstatus'), 'new_icons/Order_status.png', 1, WOPSHOP_PANEL_ORDER_STATUS_DESCRIPTION);
    $menu['countries'] = array( WOPSHOP_PANEL_COUNTRIES, admin_url('admin.php?page=wopshop-options&tab=countries'), 'new_icons/Country_list.png', 1, WOPSHOP_PANEL_COUNTRIES_DESCRIPTION);
    $menu['attributes'] = array(WOPSHOP_PANEL_ATTRIBUTES, admin_url('admin.php?page=wopshop-options&tab=attributes'), 'new_icons/Attributes.png', $config->admin_show_attributes, WOPSHOP_PANEL_ATTRIBUTES_DESCRIPTION);
    $menu['freeattributes'] = array(WOPSHOP_PANEL_FREEATTRIBUTES, admin_url('admin.php?page=wopshop-options&tab=freeattributes'), 'new_icons/Free_Attributes.png', $config->admin_show_freeattributes, WOPSHOP_PANEL_FREEATTRIBUTES_DESCRIPTION);
	 $menu['units'] = array(WOPSHOP_PANEL_UNITS_MEASURE, admin_url('admin.php?page=wopshop-options&tab=units'), 'new_icons/units.png', $config->admin_show_units);
    $menu['usergroups'] = array(WOPSHOP_PANEL_USERGROUPS, admin_url('admin.php?page=wopshop-options&tab=usergroups'), 'new_icons/User_Groups.png', 1, WOPSHOP_PANEL_USERGROUPS_DESCRIPTION);
	$menu['vendors'] = array(WOPSHOP_VENDORS, admin_url('admin.php?page=wopshop-options&tab=vendors'), 'new_icons/vendors.png', 1);
    $menu['reviews'] = array(WOPSHOP_PANEL_REVIEWS, admin_url('admin.php?page=wopshop-options&tab=reviews'), 'new_icons/p_comments.png', 1, WOPSHOP_PANEL_REVIEWS_DESCRIPTION);
    $menu['labels'] = array(WOPSHOP_PANEL_PRODUCT_LABELS, admin_url('admin.php?page=wopshop-options&tab=productlabels'), 'new_icons/p_labels.png', $config->admin_show_product_labels, WOPSHOP_PANEL_PRODUCT_LABELS_DESCRIPTION);
    $menu['productfields'] = array(WOPSHOP_PANEL_PRODUCT_EXTRA_FIELDS, admin_url('admin.php?page=wopshop-options&tab=productfields'), 'new_icons/p_haracteristic.png', 1, WOPSHOP_PANEL_PRODUCT_EXTRA_FIELDS_DESCRIPTION);
    $menu['languages'] = array(WOPSHOP_PANEL_LANGUAGES, admin_url('admin.php?page=wopshop-options&tab=languages'), 'new_icons/Languages.png', $config->admin_show_languages, WOPSHOP_PANEL_LANGUAGES_DESCRIPTION);
    $menu['importexport'] = array(WOPSHOP_PANEL_IMPORT_EXPORT, admin_url('admin.php?page=wopshop-options&tab=importexport'), 'new_icons/Import_Export.png', 1, WOPSHOP_PANEL_IMPORT_EXPORT_DESCRIPTION);
    $menu['addons'] = array(WOPSHOP_ADDONS, admin_url('admin.php?page=wopshop-options&tab=addons'), 'new_icons/Addons.png', 1, WOPSHOP_ADDONS_DESCRIPTION);
    
    do_action_ref_array('onBeforeAdminOptionPanelMenuDisplay', array(&$menu));
    
    return $menu; 
}

function wopshopGetItemsConfigPanelMenu(){    
    $menu = array();
    $menu['adminfunction'] = array( WOPSHOP_SHOP_FUNCTION, admin_url('admin.php?page=wopshop-configuration&task=adminfunction'), 'Funktionen.png', 1, WOPSHOP_SHOP_FUNCTION_DESCRIPTION);
    $menu['general'] = array(WOPSHOP_GENERAL_PARAMETERS, admin_url( 'admin.php?page=wopshop-configuration&task=general'), 'Allgemein.png', 1, WOPSHOP_GENERAL_PARAMETERS_DESCRIPTION);
    $menu['catprod'] = array(WOPSHOP_CAT_PROD, admin_url( 'admin.php?page=wopshop-configuration&task=catprod'), 'Producte.png', 1, WOPSHOP_CAT_PROD_DESCRIPTION);
    $menu['checkout'] = array(WOPSHOP_CHECKOUT, admin_url('admin.php?page=wopshop-configuration&task=checkout'), 'Kasse.png', 1, WOPSHOP_CHECKOUT_DESCRIPTION);
    $menu['fieldregister'] = array(WOPSHOP_REGISTER_FIELDS, admin_url('admin.php?page=wopshop-configuration&task=fieldregister'), 'Registrierungsfelder.png', 1, WOPSHOP_REGISTER_FIELDS_DESCRIPTION);
    $menu['currency'] = array( WOPSHOP_PANEL_CURRENCIES, admin_url('admin.php?page=wopshop-configuration&task=currency'), 'Wahrungen.png', 1, WOPSHOP_PANEL_CURRENCIES_DESCRIPTION);
    $menu['image'] = array(WOPSHOP_IMAGE_VIDEO_PARAMETERS, admin_url('admin.php?page=wopshop-configuration&task=image'), 'Bild.png', 1, WOPSHOP_IMAGE_VIDEO_PARAMETERS_DESCRIPTION);
    $menu['statictext'] = array(WOPSHOP_STATIC_TEXT, admin_url('admin.php?page=wopshop-configuration&tab=statictext'), 'Statische Texte.png', 1, WOPSHOP_STATIC_TEXT_DESCRIPTION);
    $menu['seo'] = array(WOPSHOP_SEO, admin_url('admin.php?page=wopshop-configuration&task=seo'), 'SEO.png', 1, WOPSHOP_SEO_DESCRIPTION);
    $menu['storeinfo'] = array(WOPSHOP_STORE_INFO, admin_url('admin.php?page=wopshop-configuration&task=storeinfo'), 'info.png', 1, WOPSHOP_STORE_INFO_DESCRIPTION);
    $menu['permalinks'] = array(WOPSHOP_PERMALINKS, admin_url('admin.php?page=wopshop-configuration&task=permalinks'), 'Permalinks.png', 1, WOPSHOP_PERMALINKS_DESCRIPTION);
    $menu['otherconfig'] = array(WOPSHOP_OC, admin_url('admin.php?page=wopshop-configuration&task=otherconfig'), 'Config.png', 1, WOPSHOP_OC_DESCRIPTION);
    do_action_ref_array( 'onBeforeAdminConfigPanelMenuDisplay', array(&$menu) );
    
    return $menu;
}

function wopshopDisplaySubmenuOptions($active=""){
    include(WOPSHOP_PLUGIN_ADMIN_DIR."/views/panel/tmpl/options_submenu.php");
}

function wopshopDisplaySubmenuConfigs($active=""){
    include(WOPSHOP_PLUGIN_ADMIN_DIR."/views/configuration/tmpl/submenu.php");
}

function getEnableDeliveryFiledRegistration($type='address'){
    $config = WopshopFactory::getConfig();
    $tmp_fields = $config->getListFieldsRegister();
    $config_fields = (array)$tmp_fields[$type];
    $count = 0;
    foreach($config_fields as $k=>$v){
        if (substr($k, 0, 2)=="d_" && $v['display']==1) $count++;
    }
    return ($count>0);
}

function wopshopGetShopTemplatesSelect($default){
    $config = WopshopFactory::getConfig();
    $temp = array();
    $dir = $config->template_path;
    $dh = opendir($dir);
    while(($file = readdir($dh)) !== false){        
        if (is_dir($dir.$file) && $file!="." && $file!=".." && $file!='addons'){
            $temp[] = $file;
        }
    }
    closedir($dh);
    $list = array();
    foreach($temp as $val){
        $list[] = WopshopHtml::_('select.option', $val, $val, 'id', 'value');
    }
    return WopshopHtml::_('select.genericlist', $list, "template",'class = "inputbox" size = "1"','id','value', $default);
}

function wopshopGetTemplates($type, $default, $first_empty = 0){
    $name = $type."_template";
    $folder = $type;

    $config = WopshopFactory::getConfig();
    $temp = array();
    $dir = $config->template_path.$config->template."/".$folder."/";
    $dh = opendir($dir);
    while (($file = readdir($dh)) !== false) {
        if (preg_match("/".$type."_(.*)\.php/", $file, $matches)){
            $temp[] = $matches[1];
        }
    }
    closedir($dh);
    $list = array();
    if ($first_empty){
        $list[] = WopshopHtml::_('select.option', -1, "- - -", 'id', 'value');
    }
    foreach($temp as $val){
        $list[] = WopshopHtml::_('select.option', $val, $val, 'id', 'value');
    }
    
    return WopshopHtml::_('select.genericlist', $list, $name,'class = "inputbox" size = "1"','id','value', $default);
}

function wopshopBuildTreeCategory($publish = 1, $is_select = 1, $access = 1) {
    $config = WopshopFactory::getConfig();
    global $wpdb;
    $lang = $config->getLang();
    $user = WopshopFactory::getUser();
    $where = array();
    if ($publish){
        $where[] = "category_publish = '1'";
    }
    //if ($access){
    //    $groups = implode(',', $user->getAuthorisedViewLevels());
    //    $where[] =' access IN ('.$groups.')';
    //}
    $add_where = "";
    if (count($where)){
        $add_where = " where ".implode(" and ", $where);
    }
    $query = "SELECT `name_".$lang."` as name, category_id, category_parent_id, category_publish FROM `".$wpdb->prefix."wshop_categories`
                  ".$add_where." ORDER BY category_parent_id, ordering";
    $all_cats = $wpdb->get_results($query);

    $categories = array();
        if(count($all_cats)) {
        foreach ($all_cats as $key => $value) {
            if(!$value->category_parent_id){
                wopshopRecurseTree($value, 0, $all_cats, $categories, $is_select);
            }
        }
    }
    return $categories;
}

function wopshopRecurseTree($cat, $level, $all_cats, &$categories, $is_select) {
    $probil = '';
    if($is_select) {
        for ($i = 0; $i < $level; $i++) {
            $probil .= '-- ';
        }
        $cat->name = ($probil . $cat->name);
        $categories[] = WopshopHtml::_('select.option', $cat->category_id, $cat->name,'category_id','name' );
    } else {
        $cat->level = $level;
        $categories[] = $cat;
    }
    foreach ($all_cats as $categ) {
        if($categ->category_parent_id == $cat->category_id) {
            wopshopRecurseTree($categ, ++$level, $all_cats, $categories, $is_select);
            $level--;
        }
    }
    return $categories;
}

function wopshopFormatprice($price, $currency_code = null, $currency_exchange = 0, $style_currency = 0) {
    $config = WopshopFactory::getConfig();
    if ($currency_exchange){
        $price = $price * $config->currency_value;
    }
    if ($config->wopshopFormatprice_style_currency_span && $style_currency!=-1){
        $style_currency = 1;
    }
    if (!$currency_code) $currency_code = $config->currency_code;
    $price = number_format($price, $config->decimal_count, $config->decimal_symbol, $config->thousand_separator);
    if ($style_currency==1) $currency_code = '<span class="currencycode">'.$currency_code.'</span>';
    
    $return = str_replace("Symb", $currency_code, str_replace("00", $price, $config->format_currency[$config->currency_format]));
	extract(wopshop_add_trigger(get_defined_vars(), "after"));
    return $return;
}
//
//function getListTaxes(){
//    global $wpdb;
//    $name_table = $wpdb->prefix.'wshop_taxes';
//    return $wpdb->get_results( "SELECT * FROM ".$name_table." WHERE `tax_publish` = '1' ORDER BY `ordering` asc");
//}
//    
//function getOrderstatuses(){
//    global $wpdb;
//    $config = WopshopFactory::getConfig();
//    $lang = $config->getLang();
//    return $wpdb->get_results("SELECT *, `name_".$lang."` as name FROM ".$wpdb->prefix.'wshop_order_status');
//}

function wopshopToArray($arr, $key, $name){
    $result = array();
    if(is_array($arr)){
        foreach($arr as $index=>$data){
            $result[$data->$key] = $data->$name;
        }
    }
    return $result;
}

function wopshopSprintUnitWeight(){
    $config = WopshopFactory::getConfig();
    global $wpdb;
    $name_table = $wpdb->prefix.'wshop_unit';
    return $wpdb->get_var( "SELECT `name_".get_bloginfo('language')."` FROM ".$name_table." WHERE `id` = ".$config->main_unit_weight);
}

function wopshopSaveAsPrice($val){
    $val = str_replace(",",".",$val);
    if(!$val){
        return floatval(0);
    }
    preg_match('/-?[0-9]+(\.[0-9]+)?/', $val, $matches);
return floatval($matches[0]);
}

function splitValuesArrayObject($array_object,$property_name) {
    $return = '';
	if (is_array($array_object)){
		foreach($array_object as $key=>$value){
	        $return .= $array_object[$key]->$property_name.', ';
	    }
	    $return = "( ".substr($return,0,strlen($return) - 2)." )";
    }
    return $return;
}

function wopshopGetNameImageLabel($id, $type = 1){
    $config = WopshopFactory::getConfig();
    //global $config;
    global $wpdb;
    static $listLabels;
    if (!$config->admin_show_product_labels) return "";
    if (!is_array($listLabels)){
        $query = "SELECT id, image, `name_".$config->cur_lang."` as name FROM `".$wpdb->prefix."wshop_product_labels` ORDER BY name";
        $list = $wpdb->get_results($query);
        $rows = array();
        foreach($list as $row){
            $rows[$row->id] = $row;
        }
        $listLabels = $rows;
    }
    $obj = $listLabels[$id];
    if ($type==1)
        return $obj->image;
    else
        return $obj->name;
}

function wopshopOrderBlocked($order){
    if (!$order->order_created && time()-strtotime($order->order_date)<3600){
        return 1;
    }else{
        return 0;
    }
}

function wopshopGetMainCurrencyCode(){
    $config = WopshopFactory::getConfig();
    global $wpdb;
    return $wpdb->get_var( "SELECT currency_code FROM ".$wpdb->prefix."wshop_currencies WHERE `currency_id` = ".$config->main_unit_weight);
}

function wopshopGetIdVendorForCUser(){
	static $id;
    $config = WopshopFactory::getConfig();
    if (!$config->admin_show_vendors) return 0;
    if (!isset($id)){
        $user = WopshopFactory::getUser();
//        $adminaccess = $user->authorise('core.admin', 'com_jshopping');
        if ( current_user_can( 'manage_options' ) ) {
            $id = 0;    
        }else{
            $vendors = $this->getModel("vendors");    
            $id = $vendors->getIdVendorForUserId($user->user_id);
        }
    }
    return $id; 
}

function wopshop_datenull($date){
	return (substr($date,0,1)=="0");
}

function wopshopGetDisplayDate($date, $format='%d.%m.%Y'){
    if (wopshop_datenull($date)){
        return '';
    }
    $adate = array(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2));
    $str = str_replace(array("%Y","%m","%d"), $adate, $format);
	return $str;
}

function wopshopDisplayTotalCartTaxName($display_price = null){
    $config = WopshopFactory::getConfig();
    if (!isset($display_price)) {
        $display_price = $config->display_price_front_current;
    }
    if ($display_price==0){
        return WOPSHOP_INC_TAX;
    }else{
        return WOPSHOP_PLUS_TAX;
    }
}

function wopshopLoadCurrencyValue(){
    $config = WopshopFactory::getConfig();
    $session = WopshopFactory::getSession();
    $id_currency_session = $session->get('wshop_id_currency');
    $id_currency = WopshopRequest::getInt('id_currency');
    $main_currency = $config->mainCurrency;
    if ($config->default_frontend_currency) $main_currency = $config->default_frontend_currency;

    if ($session->get('wshop_id_currency_orig') && $session->get('wshop_id_currency_orig') != $main_currency) {
        $id_currency_session = 0;
        $session->set('wshop_update_all_price', 1);
    }

    if (!$id_currency && $id_currency_session){
        $id_currency = $id_currency_session;
    }

    $session->set('wshop_id_currency_orig', $main_currency);

    if ($id_currency){
        $config->cur_currency = $id_currency;
    }else{
        $config->cur_currency = $main_currency;
    }
    $session->set('wshop_id_currency', $config->cur_currency);
    $all_currency = WopshopFactory::getAllCurrency();
    $current_currency = $all_currency[$config->cur_currency];
    if (!$current_currency->currency_value) $current_currency->currency_value = 1;
    $config->currency_value = $current_currency->currency_value;
    $config->currency_code = $current_currency->currency_code;
    $config->currency_code_iso = $current_currency->currency_code_iso;
}

function wopshopGetJsDateDB($str, $format='%d.%m.%Y'){
    $f = str_replace(array("%d","%m","%Y"), array('dd','mm','yyyy'), $format);
    $pos = array(strpos($f, 'y'),strpos($f, 'm'),strpos($f, 'd'));
    $date = substr($str, $pos[0], 4).'-'.substr($str, $pos[1], 2).'-'.substr($str, $pos[2], 2);
    return $date;
}

function wopshopGetJsDate($date = 'now', $format='Y-m-d H:i:s'){
    $date = WopshopFactory::getDate($date);
    return $date->format($format);
}

function wopshopOutputDigit($digit, $count_null) {
    $length = strlen(strval($digit));
    for ($i = 0; $i < $count_null - $length; $i++) {
        $digit = '0'.$digit;
    }
    return $digit;
}

function wopshopGetCorrectedPriceForQueryFilter($price){
    $config = WopshopFactory::getConfig();

    $taxes = WopshopFactory::getAllTaxes();
    $taxlist = array_values($taxes);
    $tax = $taxlist[0];

    if ($config->display_price_admin == 1 && $config->display_price_front_current == 0){
        $price = $price / (1 + $tax / 100);
    }
    if ($config->display_price_admin == 0 && $config->display_price_front_current == 1){
        $price = $price * (1 + $tax / 100);
    }
    
    $price = $price / $config->currency_value;
    return $price;
}

function wopshopGetPatchProductImage($name, $prefix = '', $patchtype = 0){
    $config = WopshopFactory::getConfig();
    if ($name==''){
        return '';
    }
    if ($prefix!=''){
        $name = $prefix."_".$name;
    }
    if ($patchtype==1){
        $name = $config->image_product_live_path."/".$name;
    }
    if ($patchtype==2){
        $name = $config->image_product_path."/".$name;
    }
return $name;
}
function wopshop_formatweight($val, $unitid = 0, $show_unit = 1){
    $config = WopshopFactory::getConfig();
    if (!$unitid){
        $unitid = $config->main_unit_weight;
    }
    $units = WopshopFactory::getAllUnits();
    $unit = $units[$unitid];
    if ($show_unit){
        $sufix = " ".$unit->name;
    }else{
        $sufix = "";
    }
    $val = floatval($val);
    return str_replace(".", $config->decimal_symbol, $val).$sufix;
}
function wopshopFormatEPrice($price){
    $config = WopshopFactory::getConfig();
    return number_format($price, $config->product_price_precision, '.', '');
}
function wopshop_formatdate($date, $showtime = 0){
    $config = WopshopFactory::getConfig();
    $format = $config->store_date_format;
    if ($showtime) $format = $format." %H:%M:%S";
    return strftime($format, strtotime($date));
}
function wopshopFixRealVendorId($id){
    if ($id==0){
        $mainvendor = WopshopFactory::getMainVendor();
        $id = $mainvendor->id;
    }
return $id;
}
function wopshop_formatqty($val){
    return floatval($val);
}
function wopshopSprintAtributeInOrder($atribute, $type="html"){   
    do_action_ref_array('beforewopshopSprintAtributeInOrder', array(&$atribute, $type));
    if ($type=="html"){
        $html = nl2br($atribute);
    }else{
        $html = $atribute;
    }
return $html;
}
function wopshopSprintFreeAtributeInOrder($freeatribute, $type="html"){
    do_action_ref_array('beforewopshopSprintFreeAtributeInOrder', array(&$freeatribute, $type));
    if ($type=="html"){
        $html = nl2br($freeatribute);
    }else{
        $html = $freeatribute;
    }
return $html;
}
function wopshopSprintExtraFiledsInOrder($extra_fields, $type="html"){
    do_action_ref_array('beforeSprintExtraFieldsInOrder', array(&$extra_fields, $type));
    if ($type=="html"){
        $html = nl2br($extra_fields);
    }else{
        $html = $extra_fields;
    }
return $html;
}
function wopshop_formattax($val){
    $config = WopshopFactory::getConfig();
    $val = floatval($val);
    return str_replace(".", $config->decimal_symbol, $val);
}

add_action('wp_ajax_wopshop_modal_insert_product_to_order', 'wopshop_modal_insert_product_to_order_callback');
function wopshop_modal_insert_product_to_order_callback(){
    $e_name = WopshopRequest::getInt('e_name');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&tab=productlistselectable&action=display&e_name='.$e_name);
    die();
}
add_action('wp_ajax_wopshop_modal_insert_product_to_order_json', 'wopshop_modal_insert_product_to_order_json_callback');
function wopshop_modal_insert_product_to_order_json_callback(){
    $pid = WopshopRequest::getInt('product_id');
    $currency_id = WopshopRequest::getInt('currency_id');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=loadproductinfo&product_id='.$pid.'&currency_id='.$currency_id);
    die();
}
add_action('wp_ajax_wopshop_userinfo_json', 'wopshop_userinfo_json_callback');
function wopshop_userinfo_json_callback(){
    $uid = WopshopRequest::getInt('user_id');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-clients&task=get_userinfo&user_id='.$uid);
    die();
}
add_action('wp_ajax_wopshop_product_cat_attr', 'wopshop_product_cat_attr_callback');
function wopshop_product_cat_attr_callback(){
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=product_extra_fields');
    die();
}
add_action('wp_ajax_wopshop_product_delete_file', 'wopshop_product_delete_file');
function wopshop_product_delete_file(){
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=delete_file');
    die();
}
add_action('wp_ajax_wopshop_product_delete_foto', 'wopshop_product_delete_foto');
function wopshop_product_delete_foto(){
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=delete_foto');
    die();
}
add_action('wp_ajax_wopshop_search_related', 'wopshop_product_search_related');
function wopshop_product_search_related(){
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=search_related');
    die();
}
add_action('wp_ajax_wopshop_wopshop_printOrder', 'wopshop_printOrder');
function wopshop_printOrder(){
    $order_id = WopshopRequest::getInt('order_id');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-orders&task=printOrder&order_id='.$order_id);
    die();
}
add_action('wp_ajax_wopshop_delete_video', 'wopshop_delete_video');
function wopshop_delete_video(){
    $id = WopshopRequest::getInt('id');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-products&task=delete_video&id='.$id);
    die();
}
add_action('wp_ajax_wopshop_setmenu', 'wopshop_setmenu');
function wopshop_setmenu(){
    $url = WopshopRequest::getVar('url');
    echo esc_url(wopshopSEFLink($url));
    die();
}
add_action('wp_ajax_wopshop_category_parent_sorting', 'wopshop_category_parent_sorting');
function wopshop_category_parent_sorting(){
    $catid = WopshopRequest::getInt('catid');
    unset($_REQUEST['action']);
    WshopAdminRouter::route('admin.php?page=wopshop-categories&task=sorting_cats_html&catid='.$catid);
    die();
}

function wopshopGetPageHeaderOfParams(&$params){
    $header = "";
    //if (@$params->get('show_page_heading') && @$params->get('page_heading')){
    if ($params->show_page_heading && $params->page_heading){
        $header = $params->page_heading;
    }
    return $header;
}

function wopshopSplitSql($sql){
    $start = 0;
    $open = false;
    $char = '';
    $end = strlen($sql);
    $queries = array();

    for ($i = 0; $i < $end; $i++)
    {
        $current = substr($sql, $i, 1);

        if (($current == '"' || $current == '\''))
        {
            $n = 2;

            while (substr($sql, $i - $n + 1, 1) == '\\' && $n < $i)
            {
                $n++;
            }

            if ($n % 2 == 0)
            {
                if ($open)
                {
                    if ($current == $char)
                    {
                            $open = false;
                            $char = '';
                    }
                }
                else
                {
                    $open = true;
                    $char = $current;
                }
            }
        }

        if (($current == ';' && !$open) || $i == $end - 1)
        {
            $queries[] = substr($sql, $start, ($i - $start + 1));
            $start = $i + 1;
        }
    }

    return $queries;
}
function wopshopGetProductById($product_id){
    global $wpdb;
    $config = WopshopFactory::getConfig();
    $lang = $config->cur_lang;
    $query = "SELECT `alias_".$lang."` FROM `".$wpdb->prefix.'wshop_products'."` WHERE product_id = ".(int)esc_sql($product_id);
    $alias = $wpdb->get_var($query);
    return ($alias) ? $alias : $product_id;
}
function wopshopParseParamsToArray($string) {
    $temp = explode("\n",$string);
    foreach ($temp as $key => $value) {
        if(!$value) continue;
        $temp2 = explode("=",$value);
        $array[$temp2[0]] = $temp2[1];
    }
    return $array;
}
function wopshopParseArrayToParams($array) {
    $str = '';
    foreach ($array as $key => $value) {
        $str .= $key."=".$value."\n";
    }
    return $str;
}
function wopshop_get_list_files( $folder = '', $filter = '.', $levels = 1 ) {
	if ( empty($folder) )
		return false;

	if ( ! $levels )
		return false;

	$files = array();
	if ( $dir = @opendir( $folder ) ) {
		while (($file = readdir( $dir ) ) !== false ) {
			if ( in_array($file, array('.', '..') ) )
				continue;
			if ( is_dir( $folder . '/' . $file ) ) {
				$files2 = list_files( $folder . '/' . $file, $levels - 1);
				if ( $files2 )
					$files = array_merge($files, $files2 );
				else
					$files[] = $folder . '/' . $file . '/';
			} else {
                            if (preg_match("/$filter/", $file))
				//$files[] = $folder . '/' . $file;
                                $files[] = $file;
			}
		}
	}
	@closedir( $dir );
	return $files;
}

//php 5.4
//function slugify($string) {
//    $string = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $string);
//    $string = preg_replace('/[-\s]+/', '-', $string);
//    return trim($string, '-');
//}

function wopshopUpdateCountExtTaxRule(){
    global $wpdb;
    $query = "SELECT count(id) FROM `".$wpdb->prefix."wshop_taxes_ext`";
    $count = $wpdb->get_var($query);

    $query = "update ".$wpdb->prefix."wshop_config set use_extend_tax_rule='".$count."' where id='1'";
    $wpdb->query( $query );
}

function wopshopReplaceWWW($str){
    return str_replace("www.","",$str);
}

function wopshopGetHttpHost(){
    return $_SERVER["HTTP_HOST"];
}

function wopshopCompareX64($a, $b){
    return base64_encode($a) == $b;
}

function wopshopProductTaxInfo($tax, $display_price = null){
    if (!isset($display_price)) {
        $config = WopshopFactory::getConfig();
        $display_price = $config->display_price_front_current;
    }
    if ($display_price==0){
        return sprintf(WOPSHOP_INC_PERCENT_TAX, wopshop_formattax($tax));
    }else{
        return sprintf(WOPSHOP_PLUS_PERCENT_TAX, wopshop_formattax($tax));
    }
}

function wopshopSprintBasicPrice($prod){
    if (is_object($prod)) $prod = (array)$prod;
    do_action_ref_array('beforewopshopSprintBasicPrice', array(&$prod));
    $html = '';
    if ($prod['basicprice']>0){
        $html = wopshopFormatprice($prod['basicprice'])." / ".$prod['basicpriceunit'];
    }
return $html;
}

function wopshopGetWPLanguageTag(){
    //$language = get_bloginfo('language');
    $language = get_locale();

    return str_replace('_', '-', $language);
}

/*function wopshopProductTaxInfo($tax, $display_price = null){
    if (!isset($display_price)) {
        $config = WopshopFactory::getConfig();
        $display_price = $config->display_price_front_current;
    }
    if ($display_price==0){
        return sprintf(WOPSHOP_INC_PERCENT_TAX, wopshop_formattax($tax));
    }else{
        return sprintf(WOPSHOP_PLUS_PERCENT_TAX, wopshop_formattax($tax));
    }
}
 * 
 */