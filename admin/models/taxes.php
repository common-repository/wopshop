<?php
class TaxesWshopAdminModel extends WshopAdminModel {
    public $string;
    protected $tablename = 'tax';
 
    public function __construct() {
        global $wpdb;
        parent::__construct();
    }

    function getAllTaxes($order = null, $orderDir = null){
        global $wpdb;

        $ordering = 'tax_name';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT * FROM `".$wpdb->prefix."wshop_taxes` ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }

    function getExtTaxes($tax_id = 0, $order = null, $orderDir = null) {
        global $wpdb;
        $where = "";
        if ($tax_id) $where = " where ET.tax_id='".$tax_id."'";
        $ordering = 'ET.id';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT ET.*, T.tax_name FROM `".$wpdb->prefix."wshop_taxes_ext` as ET left join `".$wpdb->prefix."wshop_taxes` as T on T.tax_id=ET.tax_id ".$where." ORDER BY ".$ordering;
        extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    
    function getValue($id){
        global $wpdb;
        $query = "select tax_value from `".$wpdb->prefix."wshop_taxes` where tax_id=".(int)$id;
        extract(js_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_var($query);
    }    
}