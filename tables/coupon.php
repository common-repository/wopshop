<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class CouponWshopTable extends WshopTable {
    
    function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_coupons', 'coupon_id');
    }
    
    function getExistCode(){
        $query = "SELECT COUNT(`coupon_id`) FROM `$this->_tbl`
                  WHERE `coupon_code` = '" . esc_sql($this->coupon_code) . "' AND `coupon_id` <> '" . esc_sql($this->coupon_id) . "'";
		extract(wopshop_add_trigger(get_defined_vars(), "query"));
        return $this->_db->get_var($query);
    }
    
    function getEnableCode($code){
        $config = WopshopFactory::getConfig();
		
        if(!$config->use_rabatt_code) {
			$this->error = WOPSHOP_RABATT_NON_SUPPORT;
            WopshopFactory::getApplication()->enqueueMessage(WOPSHOP_RABATT_NON_SUPPORT, 'error');
            return 0;
        }
        $date = wopshopGetJsDate('now', 'Y-m-d');
        $query = "SELECT * FROM `$this->_tbl` WHERE coupon_code = '".  esc_sql($code)."' AND coupon_publish = '1'";
		extract(wopshop_add_trigger(get_defined_vars(), "query"));
        $row = $this->_db->get_row($query);
        
        if(!isset($row->coupon_id)) {
			$this->error = WOPSHOP_RABATT_NON_CORRECT;
            return 0;
        }
        
        if ($row->coupon_expire_date < $date && $row->coupon_expire_date!="0000-00-00"){
			$this->error = WOPSHOP_RABATT_NON_CORRECT;
            return 0;
        }
        
        if ($row->coupon_start_date > $date){
			$this->error = WOPSHOP_RABATT_NON_CORRECT;
            return 0;
        }
        
        if($row->used) {
			$this->error = WOPSHOP_RABATT_USED;
            return 0;
        }
        
        if ($row->for_user_id){
            $user = WopshopFactory::getUser();
            if (!$user->user_id){
				$this->error = WOPSHOP_FOR_USE_COUPON_PLEASE_LOGIN;
                return 0;
            }
            if ($row->for_user_id!=$user->user_id){
				$this->error = WOPSHOP_RABATT_NON_CORRECT;
                return 0;    
            }
        }
        
        $this->load($row->coupon_id);
        return 1;                
    }

}
?>