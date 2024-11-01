<?php
class ManufacturersWshopAdminModel extends WshopAdminModel {
    protected $tablename = 'manufacturer';
    protected $tableFieldPublish = 'manufacturer_publish';
 
    public function __construct() {
        global $wpdb;
        parent::__construct();
    }

    function getAllManufacturers($publish=0, $order=null, $orderDir=null){
        global $wpdb;
        $query_where = ($publish)?(" WHERE manufacturer_publish = '1'"):("");  
		$queryorder = '';		
        if ($order && $orderDir){
            $queryorder = "order by ".$order." ".$orderDir;
        }
        $query = "SELECT manufacturer_id, manufacturer_url, manufacturer_logo, manufacturer_publish, ordering, `name_".$this->lang."` as name FROM `".$wpdb->prefix."wshop_manufacturers` $query_where ".$queryorder;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    
    function getList(){
        $config = WopshopFactory::getConfig();
        if ($config->manufacturer_sorting==2){
            $morder = 'name';
        }else{
            $morder = 'ordering';
        }
    return $this->getAllManufacturers(0, $morder, 'asc');
    }
    
    public function deleteList(array $cid, $msg = 1){
        $app = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        do_action_ref_array( 'onBeforeRemoveManufacturer', array(&$cid) );
        $res = array();
        foreach($cid as $value){
            $manuf = WopshopFactory::getTable('manufacturer');
            $manuf->load($value);
            if($manuf->delete()){
                if($manuf->manufacturer_logo){
                    @unlink($config->image_manufs_path.'/'.$manuf->manufacturer_logo);
                }
                if($msg){
                    $app->enqueueMessage(sprintf(WOPSHOP_MANUFACTURER_DELETED, $value), 'updated');
                }
                $res[$value] = true;
            }else if($msg){
                $app->enqueueMessage('Error', 'error');
            }
        }
        do_action_ref_array( 'onAfterRemoveManufacturer', array(&$cid) );
        return $res;
    }    
}