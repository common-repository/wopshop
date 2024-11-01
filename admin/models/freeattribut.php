<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
class FreeAttributWshopAdminModel extends WshopAdminModel {
    public $string;

    public function __construct() {
        parent::__construct();
    }
    function getNameAttrib($id) {
        global $wpdb;
		$config = WopshopFactory::getConfig();
        $lang = $config->cur_lang; //get_bloginfo('language');
        $query = "SELECT `name_".$lang."` as name FROM `".$wpdb->prefix."wshop_free_attr` WHERE id = '".sql_esc($id)."'";
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_var($query);
    }

    function getAll($order = null, $orderDir = null) {
        $config = WopshopFactory::getConfig();
        $lang = $config->cur_lang; //get_bloginfo('language');
        global $wpdb;
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT id, `name_".$lang."` as name, ordering, required FROM `".$wpdb->prefix."wshop_free_attr` ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query, OBJECT);
    }
}
