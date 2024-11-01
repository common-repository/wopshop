<?php
class ShippingsWshopAdminModel extends WshopAdminModel {
    protected $tablename = 'shippingMethod';
 
    public function __construct() {
        parent::__construct();
    }
    
    public function getAllShippings($publish = 1, $order = null, $orderDir = null) {
        global $wpdb;
        $query_where = ($publish)?("WHERE published = '1'"):("");
        
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT shipping_id, `name_".$this->lang."` as name, `description_".$this->lang."` as description, published, ordering  
                  FROM `".$wpdb->prefix."wshop_shipping_method` 
                  $query_where 
                  ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query, OBJECT);
    }
    
    public function getAllShippingPrices($publish = 1, $shipping_method_id = 0, $order = null, $orderDir = null) {
        global $wpdb;
        $query_where = "";
        $query_where .= ($publish)?(" and shipping.published = '1'"):("");
        $query_where .= ($shipping_method_id)?(" and shipping_price.shipping_method_id= '".$shipping_method_id."'"):("");

        $ordering = "shipping_price.sh_pr_method_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }

        $query = "SELECT shipping_price.*, shipping.`name_".$this->lang."` as name
                  FROM `".$wpdb->prefix."wshop_shipping_method_price` AS shipping_price
                  INNER JOIN `".$wpdb->prefix."wshop_shipping_method` AS shipping ON shipping.shipping_id = shipping_price.shipping_method_id
                  where (1=1) $query_where
                  ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query, OBJECT);
    }
    
    function getMaxOrdering(){
        global $wpdb;
        $query = "select max(ordering) from `".$wpdb->prefix."wshop_shipping_method`";
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_var($query);
    }
    
    function saveCountries($sh_pr_method_id, $countries){
        global $wpdb;
        $wpdb->delete($wpdb->prefix."wshop_shipping_method_price_countries", array('sh_pr_method_id'=>esc_sql($sh_pr_method_id)));
        if (!is_array($countries)) return 0;
        foreach($countries as $key => $value){
            $wpdb->insert(
                $wpdb->prefix."wshop_shipping_method_price_countries",
                array('country_id'=>$value, 'sh_pr_method_id'=>$sh_pr_method_id)
            );
        }
    }
    
    function savePrices($sh_pr_method_id, $array_post) {
        global $wpdb;
		$query = "DELETE FROM `".$wpdb->prefix."wshop_shipping_method_price_weight` WHERE `sh_pr_method_id` = '".esc_sql($sh_pr_method_id)."'";
        $wpdb->query($query);
        if (!isset($array_post['shipping_price']) || !is_array($array_post['shipping_price'])) return 0;

        foreach($array_post['shipping_price'] as $key => $value){
            if(!$array_post['shipping_weight_from'][$key] && !$array_post['shipping_weight_to'][$key]){
                continue;
            }
            $sh_method = WopshopFactory::getTable('shippingMethodPriceWeight');            
            $sh_method->sh_pr_method_id = $sh_pr_method_id;
            $sh_method->shipping_price = wopshopSaveAsPrice($array_post['shipping_price'][$key]);
            $sh_method->shipping_package_price = wopshopSaveAsPrice($array_post['shipping_package_price'][$key]);
            $sh_method->shipping_weight_from = wopshopSaveAsPrice($array_post['shipping_weight_from'][$key]);
            $sh_method->shipping_weight_to = wopshopSaveAsPrice($array_post['shipping_weight_to'][$key]);
            if (!$sh_method->store()) {
				wopshopAddMessage("Error saving to database", 'error');
            }
        }
    }
    
    function getListNameShippings($publish = 1){
        $_list = $this->getAllShippings($publish);
        $list = array();
        foreach($_list as $v){
            $list[$v->shipping_id] = $v->name;
        }
        return $list;
    }
    
    public function publish(array $cid, $flag){
        global $wpdb;
        do_action_ref_array('onBeforePublishShipping', array(&$cid, &$flag));
		foreach($cid as $value){
			$wpdb->update( $wpdb->prefix . 'wshop_shipping_method', array('published' => esc_sql($flag)), array('shipping_id' => $value) );
		}        
        do_action_ref_array('onAfterPublishPayment', array(&$cid, &$flag));
    } 
    
//    public function publish(array $cid, $flag){
//        do_action_ref_array('onBeforePublishShipping', array(&$cid, &$flag));
//		$obj = WopshopFactory::getTable('shippingMethod');
//        $obj->publish($cid, $flag);
//        do_action_ref_array('onAfterPublishPayment', array(&$cid, &$flag));
//    }   
    
    
    
    
    
    
/*    function getShippingMethod($shipping_id){
        global $wpdb;
        $query = "SELECT * FROM `".$wpdb->prefix."wshop_shipping_method` WHERE `shipping_id` = '".esc_sql($shipping_id)."'";
        return $wpdb->get_row($query, OBJECT);
    }
    

    
    function t_getParams(){        
        if ($this->params==""){
            return array();
        }else{
            return json_decode($this->params, 1);
        }
    }
    function t_getPayments(){
        if ($this->payments==""){
            return array();
        }else{
            return explode(",", $this->payments);
        }
    }
    function t_setPayments($payments){
        $payments = (array)$payments;
        foreach($payments as $v){
            if ($v==0){
                $payments = array();
                break;
            }
        }
        extract(wopshop_add_trigger(get_defined_vars()));
        //$this->payments = implode(",", $payments);
        return implode(",", $payments);
    }
    
    function getPrices($id, $orderdir = "asc"){
        global $wpdb;
       
        $query = "SELECT * FROM `".$wpdb->prefix."_wshop_shipping_method_price_weight` AS sh_price
                  WHERE sh_price.sh_pr_method_id = '" .esc_sql($id) . "'
                  ORDER BY sh_price.shipping_weight_from ".$orderdir;
        return $wpdb->get_results($query, OBJECT);
    }
    function getShippingsPrice($id){
        global $wpdb;

        $query = "SELECT * FROM `".$wpdb->prefix.'wshop_shipping_method_price'."` WHERE sh_pr_method_id = '" .esc_sql($id)."'";
        return $wpdb->get_row($query);
    }
    
    function ShippingspricesUpdate($post, $id){
        global $wpdb;
        $wpdb->update( 
            $wpdb->prefix."wshop_shippings_prices",
            $post, 
            array( 
                'id' => $id
            )
        );
        wopshopAddMessage(WOPSHOP_ACTION_SHIPPINGSPRICES_UPDATE);
    }
    
    function ShippingspricesInsert($post){
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix."wshop_shippings_prices",
            $post
        );
        wopshopAddMessage(WOPSHOP_ACTION_SHIPPINGSPRICES_INSERT);
    }
    
    function ShippingspricesDelete($rows){
        global $wpdb;
        
        if(is_array($rows)){
            foreach($rows as $i=>$v){
                $wpdb->delete($wpdb->prefix."wshop_shippings_prices", array( 'id' => $v ));
            }
        }
        wopshopAddMessage(WOPSHOP_ACTION_SHIPPINGSPRICES_DELETED);
    }
    
    function t_getCountries($sh_pr_method_id) {
        global $wpdb;
        $query = "SELECT sh_country.country_id, countries.`name_".$this->lang."` as name
                  FROM `".$wpdb->prefix."wshop_shipping_method_price_countries` AS sh_country
                  INNER JOIN `".$wpdb->prefix."wshop_countries` AS countries ON countries.country_id = sh_country.country_id
                  WHERE sh_country.sh_pr_method_id = '".esc_sql($sh_pr_method_id)."'";
        return $wpdb->get_results($query, OBJECT);
    }
*/
}