<?php
if( !defined( 'ABSPATH' ) ) {
    
    exit; // Exit if accessed directly
    
}

class ProductFieldGroupsWshopAdminModel extends WshopAdminModel {
    
    function getList(  ) {
        
        global $wpdb;
        
        $lang_name = 'name_' . $this->lang;
        $query = "SELECT id, `" . $lang_name . "` as name, ordering FROM `" . $wpdb->prefix . "wshop_products_extra_field_groups` order by ordering";
        
        extract( wopshop_add_trigger( get_defined_vars(  ), "before" ) );
        
        return $wpdb->get_results( $query );
        
    }
    
    function reorder(  ) {
        
        $id   = WopshopRequest::getVar( 'id' );        
        $move = ( WopshopRequest::getVar( 'task' ) == 'orderup') ? -1 : +1;
        $obj  = WopshopFactory::getTable( 'productfieldgroups' );
        $obj->load( $id );
        $obj->move( $move );
        
        return 1;
        
    }
    
}