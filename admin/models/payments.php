<?php
class PaymentsWshopAdminModel extends WshopAdminModel {
    protected $tablename = 'paymentMethod';
    protected $tableFieldOrdering = 'payment_ordering';
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'wshop_payment_method';
        parent::__construct();
    }
    
    function getAllPaymentMethods($publish = 1, $order = null, $orderDir = null) {
        global $wpdb;
        $query_where = ($publish)?("WHERE payment_publish = '1'"):("");
        $ordering = 'payment_ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT payment_id, `name_".$this->lang."` as name, `description_".$this->lang."` as description , payment_code, payment_class, scriptname, payment_publish, payment_ordering, payment_params, payment_type FROM `".$this->table_name."`
                  $query_where
                  ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    
    function getTypes(){
    	return array('1' => WOPSHOP_TYPE_DEFAULT,'2' => WOPSHOP_PAYPAL_RELATED);
    }

    function getMaxOrdering(){
        global $wpdb; 
        $query = "select max(payment_ordering) from ".$this->table_name;
        return $wpdb->get_var($query);
    }    
    
    public function publish(array $cid, $flag){
        global $wpdb;
        do_action_ref_array('onBeforePublishPayment', array(&$cid, &$flag));
		foreach($cid as $value){
			$wpdb->update($wpdb->prefix . 'wshop_payment_method', array('payment_publish' => esc_sql($flag)), array('payment_id' => esc_sql($value)));
		}        
        do_action_ref_array('onAfterPublishPayment', array(&$cid, &$flag));
    } 

    function getListNamePaymens($publish = 1){
        $_list = $this->getAllPaymentMethods($publish);
        $list = array();
        foreach($_list as $v){
            $list[$v->payment_id] = $v->name;
        }
        return $list;
    }
}