<?php
class ShippingextpriceWshopAdminModel extends WshopAdminModel {
    public $string;
 
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'wshop_config_seo';
        parent::__construct();
    }
	
    function getList($active = 0){
        global $wpdb;
        $adv_query = "";
        if ($active==1){
            $adv_query = "where `published`='1'";
        }
        $query = "select * from `".$wpdb->prefix."wshop_shipping_ext_calc` ".$adv_query." order by `ordering`";
        extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
	
	function republish() {
		global $wpdb;
		$cid = WopshopRequest::getVar("id");
        $flag = (WopshopRequest::getVar('task') == 'publish') ? 1 : 0;        
        do_action_ref_array( 'onBeforePublishShippingExtPrice', array(&$cid,&$flag) );		
		$wpdb->update( $wpdb->prefix.'wshop_shipping_ext_calc',
			array( 'published' => $flag ),
			array( 'id' => $cid )
		);
        do_action_ref_array('onAfterPublishShippingExtPrice', array(&$cid,&$flag) );        
		return 1;
	}		
	
	function delete(){
        $id = WopshopRequest::getInt("id");
        do_action_ref_array( 'onBeforeRemoveShippingExtPrice', array(&$id) );
        $obj = WopshopFactory::getTable('shippingExt');        
        $obj->delete($id);
        do_action_ref_array( 'onAfterRemoveShippingExtPrice', array(&$id) ); 
		return 1;	
	}
	
    function reorder(){
        $id = WopshopRequest::getVar('id');        
        $move = (WopshopRequest::getVar('task') == 'orderup') ? -1 : +1;
        $obj = WopshopFactory::getTable('shippingExt');
        $obj->load($id);
        $obj->move($move);
        return 1;
    }		
}