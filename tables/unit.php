<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class UnitWshopTable extends WshopTable{

    function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_unit', 'id');
    }
	
    function getName() {
        $lang = WopshopFactory::getLang();
        $name = $lang->get('name');
        return $this->$name;
    }
	
    function getAllUnits(){
		global $wpdb;
        $lang = WopshopFactory::getLang();
        $query = "SELECT id, `".$lang->get("name")."` as name, qty FROM `".$wpdb->prefix."wshop_unit` ORDER BY id";
        $list = $wpdb->get_results($query);
        $rows = array();
        foreach($list as $row){
             $rows[$row->id] = $row;
        }
        return $rows;
    }	
}