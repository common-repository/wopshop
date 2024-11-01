<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
class AttributValueWshopAdminModel extends WshopAdminModel {
    public $string;
    protected $tableFieldOrdering = 'value_ordering';

    public function __construct() {
        parent::__construct();
    }

    function getNameValue($value_id) {
        global $wpdb;
        $query = "SELECT `name_".$this->lang."` as name FROM `".$wpdb->prefix."wshop_attr_values` WHERE value_id = '".esc_sql($value_id)."'";
        return $wpdb->get_var($query);
    }
    function getAllValues($attr_id, $order = null, $orderDir = null) {
        global $wpdb;
        $ordering = 'value_ordering, value_id';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT value_id, image, `name_".$this->lang."` as name, attr_id, value_ordering FROM `".$wpdb->prefix."wshop_attr_values` where attr_id='".$attr_id."' ORDER BY ".$ordering;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query);
    }
    /**
    * get All Atribute value
    * @param $resulttype (0 - ObjectList, 1 - array {id->name}, 2 - array(id->object) )
    * 
    * @param mixed $resulttype
    */
    function getAllAttributeValues($resulttype=0){
        global $wpdb;
        $query = "SELECT value_id, image, `name_".$this->lang."` as name, attr_id, value_ordering FROM `".$wpdb->prefix."wshop_attr_values` ORDER BY value_ordering, value_id";
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        $attribs = $wpdb->get_results($query);

        if ($resulttype==2){
            $rows = array();
            foreach($attribs as $k=>$v){
                $rows[$v->value_id] = $v;    
            }
            return $rows;
        }elseif ($resulttype==1){
            $rows = array();
            foreach($attribs as $k=>$v){
                $rows[$v->value_id] = $v->name;    
            }
            return $rows;
        }else{
            return $attribs;
        }        
    }
    
	public function delete($id){
		$this->deleteImage($id);
		$this->deleteProductAttributeValue($id);
		$this->deleteAttributeValue($id);		
	} 
    
	public function deleteAttributeValue($id){
		global $wpdb;
		$wpdb->delete($wpdb->prefix . "wshop_attr_values", array('value_id' => $id));
	} 
    
	public function deleteProductAttributeValue($id){
		$this->deleteProductAttributeValueDependent($id);
		$this->deleteProductAttributeValueNotDependent($id);
	}
    
	public function deleteProductAttributeValueDependent($id){
		global $wpdb;
		$attributValue = WopshopFactory::getTable('attributValue');
		$attributValue->load($id);
		$attr_id = $attributValue->attr_id;
		if ($attr_id){
			$field = 'attr_'.(int)$attr_id;
			$query = "update `".$wpdb->prefix."wshop_products_attr` set `".$field."`='' where `".$field."`='". esc_sql($id)."'";
			$wpdb->query($query);
		}
	}
    
	public function deleteProductAttributeValueNotDependent($id){
		global $wpdb;
		$wpdb->delete($wpdb->prefix . "wshop_products_attr2", array('attr_value_id' => $id));
	}
    
    public function deleteList(array $cid, $msg = 1){
        $app = WopshopFactory::getApplication();		
        do_action_ref_array('onBeforeRemoveAttributValue', array(&$cid));
		foreach($cid as $value){
            $this->delete(intval($value));
		}
        if ($msg){
            $app->enqueueMessage(WOPSHOP_ATTRIBUT_VALUE_DELETED, 'message');
        }        
        do_action_ref_array('onAfterRemoveAttributValue', array(&$cid));
    }    
    
    function deleteFoto($id){
        $config = WopshopFactory::getConfig();
        $attributValue = WopshopFactory::getTable('attributValue');
        $attributValue->load($id);
        @unlink($config->image_attributes_path."/".$attributValue->image);
        $attributValue->image = "";
        $attributValue->store();
        die();               
    }
    
    public function deleteImage($id){
		$image = $this->getImage($id);
		if ($image){
			@unlink(WopshopFactory::getConfig()->image_attributes_path."/".$image);
		}
	}
    
    public function getImage($id){
		global $wpdb;
		$query = "SELECT image FROM `".$wpdb->prefix."wshop_attr_values` WHERE value_id ='". esc_sql($id)."'";
		return $wpdb->get_var($query);
	}
}
