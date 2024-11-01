<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
class AttributesGroupsWshopAdminModel extends WshopAdminModel {
    public $string;
    protected $tablename = 'attributesgroup';

    public function __construct() {
        parent::__construct();
    }
    function getList(){
        global $wpdb;
        $query = "SELECT id, `name_".$this->lang."` as name, ordering FROM `".$wpdb->prefix."wshop_attr_groups` order by ordering";
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    
    public function deleteList(array $cid, $msg = 1){
        $app = WopshopFactory::getApplication();
        foreach($cid as $id){
            $this->delete($id);
        }
        do_action_ref_array('onAfterRemoveAttributesGroups', array(&$cid));
        if ($msg){
            $app->enqueueMessage(WOPSHOP_ITEM_DELETED, 'updated');
        }
    }
    
    public function delete($id){
        global $wpdb;
        $wpdb->delete($wpdb->prefix."wshop_attr_groups", array( 'id' => $id ) );
    }    
}
