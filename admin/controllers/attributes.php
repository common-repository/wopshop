<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class AttributesWshopAdminController extends WshopAdminController {
    protected $model = 'attribut';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=attributes";
    }
   
    public function display() {
        $context = "admin.attributes.";
        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', 'A.attr_ordering');
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_dir', 'filter_order_Dir', 'asc');

    	$attributes = $this->getModel("attribut");
    	$attributesvalue = $this->getModel("attributvalue");
        $rows = $attributes->getAllAttributes(0, null, $filter_order, $filter_order_Dir);
        foreach ($rows as $key => $value){
            $rows[$key]->values = splitValuesArrayObject( $attributesvalue->getAllValues($rows[$key]->attr_id), 'name');
            $rows[$key]->count_values = count($attributesvalue->getAllValues($rows[$key]->attr_id));
        }

        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $attributes->getBulkActions($actions);
        
        $view = $this->getView("attributes");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('bulk', $bulk);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeDisplayAttributes', array(&$view));
        $view->display();
    }
    
    public function edit(){
        $attr_id = WopshopRequest::getInt("attr_id");
        $attribut = WopshopFactory::getTable('attribut');
        $attribut->load($attr_id);

        if (!$attribut->independent) $attribut->independent = 0;
    
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
	
        $types[] = WopshopHtml::_('select.option', '1','Select','attr_type_id','attr_type');
        $types[] = WopshopHtml::_('select.option', '2','Radio','attr_type_id','attr_type');
        $type_attribut = WopshopHtml::_('select.genericlist', $types, 'attr_type','class = "inputbox" size = "1"','attr_type_id','attr_type',$attribut->attr_type);

        $dependent[] = WopshopHtml::_('select.option', '0',WOPSHOP_YES,'id','name');
        $dependent[] = WopshopHtml::_('select.option', '1',WOPSHOP_NO,'id','name');
        $dependent_attribut = WopshopHtml::_('select.radiolist', $dependent, 'independent', 'class = "inputbox" size = "1"', 'id', 'name', $attribut->independent, false, false, true);

        $all = array();
        $all[] = WopshopHtml::_('select.option', 1, WOPSHOP_ALL, 'id','value');
        $all[] = WopshopHtml::_('select.option', 0, WOPSHOP_SELECTED, 'id','value');
        if (!isset($attribut->allcats)) $attribut->allcats = 1;
        $lists['allcats'] = WopshopHtml::_('select.radiolist', $all, 'allcats','onclick="PFShowHideSelectCats()"','id','value', $attribut->allcats);
        
        $categories_selected = $attribut->getCategorys();
        $model_categories   = $this->getModel('categories');
        $categories = $model_categories->wopshopBuildTreeCategory(0,1,0);
        $lists['categories'] = WopshopHtml::_('select.genericlist', $categories,'category_id[]','class="inputbox" size="10" multiple = "multiple"','category_id','name', $categories_selected);
        
        $mgroups = $this->getModel("attributesgroups");
        $groups = $mgroups->getList();
        $groups0 = array();
        $groups0[] = WopshopHtml::_('select.option', 0, "- - -", 'id', 'name');        
        $lists['group'] = WopshopHtml::_('select.genericlist', array_merge($groups0, $groups),'group','class="inputbox"','id','name', $attribut->group);
        
        //FilterOutput::objectHTMLSafe($attribut, ENT_QUOTES);
        $view=$this->getView("attributes");
        $view->setLayout("edit");
        $view->assign('attribut', $attribut);
        $view->assign('type_attribut', $type_attribut);
        $view->assign('dependent_attribut', $dependent_attribut);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->assign('lists', $lists);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditAtribut', array(&$view, &$attribut));
        $view->display();
    }
    
    public function save(){
        check_admin_referer('attributes_edit');
        global $wpdb;
        $attr_id = WopshopRequest::getInt('attr_id');
        $attribut = WopshopFactory::getTable('attribut');
        $post = WopshopRequest::get("post");
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
            $post['description_'.$lang->language] = WopshopRequest::getVar('description_'.$lang->language, '', 'post');
        }
        do_action_ref_array('onBeforeSaveAttribut', array(&$post));
        if (!$attr_id){
            $query = "SELECT MAX(attr_ordering) AS attr_ordering FROM `".$wpdb->prefix."wshop_attr`";
            $row = $wpdb->get_row($query, OBJECT);
            $post['attr_ordering'] = $row->attr_ordering + 1;
        }

        if (!$attribut->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=attributes");
            return 0;
        }

        if (isset($post['category_id'])) 
            $categorys = $post['category_id'];
        else
            $categorys = '';

        if (!is_array($categorys)) $categorys = array();

        $attribut->setCategorys($categorys);

        if (!$attribut->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=attributes");
            return 0;
        }

        if (!$attr_id){
            $query="ALTER TABLE `".$wpdb->prefix."wshop_products_attr` ADD `attr_".$attribut->attr_id."` INT( 11 ) NOT NULL";
            $wpdb->query($query);
            $attr_id = $attribut->attr_id;
        }
        do_action_ref_array('onAfterSaveAttribut', array(&$attribut));
        $this->setRedirect("admin.php?page=wopshop-options&tab=attributes");

        $this->setRedirect('admin.php?page=wopshop-options&tab=attributes');
    }

    public function delete(){
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=attributes');
            return 0;
        }
        $config = WopshopFactory::getConfig();
        global $wpdb;
        do_action_ref_array( 'onBeforeRemoveAttribut', array(&$cid) );
        $text = '';
	
        foreach ($cid as $key => $value) {
            $value = intval($value);
            $query = "DELETE FROM `".$wpdb->prefix."wshop_attr` WHERE `attr_id` = '".esc_sql($value)."'";
            $wpdb->query($query);
            
            $query="ALTER TABLE `".$wpdb->prefix."wshop_products_attr` DROP `attr_".$value."`";
            $wpdb->query($query);
            
            $query = "select * from `".$wpdb->prefix."wshop_attr_values` where `attr_id` = '".esc_sql($value)."' ";
            $attr_values = $wpdb->get_results($query);
            foreach ($attr_values as $attr_val){
                @unlink($config->image_attributes_path."/".$attr_val->image);
            }
            $query = "delete from `".$wpdb->prefix."wshop_attr_values` where `attr_id` = '".esc_sql($value)."' ";
            $wpdb->query($query);
            
            $text = WOPSHOP_ATTRIBUT_DELETED;
        }
		do_action_ref_array( 'onAfterRemoveAttribut', array(&$cid) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=attributes", $text);
    }
	
//	function order() {
//		$order = WopshopRequest::getVar("order");
//		$cid = WopshopRequest::getInt("id");
//		$number = WopshopRequest::getInt("number");
//		global $wpdb;
//		switch ($order) {
//			case 'up':
//				$query = "SELECT a.attr_id, a.attr_ordering
//					   FROM `".$wpdb->prefix."wshop_attr` AS a
//					   WHERE a.attr_ordering < '" . $number . "'
//					   ORDER BY a.attr_ordering DESC
//					   LIMIT 1";
//				break;
//			case 'down':
//				$query = "SELECT a.attr_id, a.attr_ordering
//					   FROM `".$wpdb->prefix."wshop_attr` AS a
//					   WHERE a.attr_ordering > '" . $number . "'
//					   ORDER BY a.attr_ordering ASC
//					   LIMIT 1";
//		}
//		$row = $wpdb->get_row($query);
//		$query1 = "UPDATE `".$wpdb->prefix."wshop_attr` AS a
//					 SET a.attr_ordering = '" . $row->attr_ordering . "'
//					 WHERE a.attr_id = '" . $cid . "'";
//		$query2 = "UPDATE `".$wpdb->prefix."wshop_attr` AS a
//					 SET a.attr_ordering = '" . $number . "'
//					 WHERE a.attr_id = '" . $row->attr_id . "'";
//		$wpdb->query($query1);
//		$wpdb->query($query2);
//		
//		$this->setRedirect("admin.php?page=wopshop-options&tab=attributes");
//	}
//    
//    function saveorder(){
//		$cid = WopshopRequest::getVar("rows");
//        $order = WopshopRequest::getVar('order', array(), 'post', 'array' );                
//        foreach($cid as $k=>$id){
//            $table = WopshopFactory::getTable('attribut');
//            $table->load($id);
//            if ($table->attr_ordering!=$order[$k]){
//                $table->attr_ordering = $order[$k];
//                $table->store();
//            }
//        }                
//        $this->setRedirect("admin.php?page=wopshop-options&tab=attributes");
//    }	
}