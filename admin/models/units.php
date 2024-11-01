<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}

class UnitsWshopAdminModel extends WshopAdminModel {
 
    public function __construct() {
        global $wpdb;
        parent::__construct();
    }
  
    function getUnits(){
        global $wpdb;
        $query = "SELECT id, `name_".$this->lang."` as name FROM `".$wpdb->prefix."wshop_unit` ORDER BY name";
        return $wpdb->get_results($query, OBJECT);
    }
}
