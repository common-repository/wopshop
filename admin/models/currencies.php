<?php
class CurrenciesWshopAdminModel extends WshopAdminModel {
    protected $tablename = 'currency';
    protected $tableFieldOrdering = 'currency_ordering';
    protected $tableFieldPublish = 'currency_publish';
    
    public function __construct() {
        global $wpdb;
        parent::__construct();
    }
    
    function getAllCurrencies($publish = 1, $order = null, $orderDir = null) {
        global $wpdb;
        $query_where = ($publish)?("WHERE currency_publish = '1'"):("");
        $ordering = 'currency_ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT * FROM `".$wpdb->prefix."wshop_currencies` $query_where ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    
    function getCountProduct($id){
        global $wpdb;
        $query = "select count(*) from `".$wpdb->prefix."wshop_products` where currency_id=".intval($id);
        return $wpdb->get_var($query);
    }
    
    function delete($id, $check = 1){
        if ($check){
            if ($this->getCountProduct($id)){
                return 0;
            }
        }
        $row = WopshopFactory::getTable('currency');
        return $row->delete($id);
    }    
    
    function deleteList(array $cid, $msg = 1){
        $app = WopshopFactory::getApplication();
        do_action_ref_array( 'onBeforeRemoveCurrencie', array(&$cid) );
        $res = array();
        foreach($cid as $id){
            if ($this->delete($id)){
                if ($msg){
                    $app->enqueueMessage(WOPSHOP_CURRENCY_DELETED, 'updated');
                }
                $res[$id] = true;
            }else{
                if ($msg){
                    $app->enqueueMessage(WOPSHOP_CURRENCY_ERROR_DELETED, 'error');
                }
                $res[$id] = false;
            }
        }        
        do_action_ref_array( 'onAfterRemoveCurrencie', array(&$cid) );
        return $res;
    }
    
    protected function reorderCurrency($currency_ordering) {
        global $wpdb;       
        $query = "UPDATE $this->table_name SET `currency_ordering` = `currency_ordering` + 1 WHERE `currency_ordering` > '" . $currency_ordering . "'";
        $wpdb->query($query);   
    }    
 
}