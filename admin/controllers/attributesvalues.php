<?php

class AttributesValuesWshopAdminController extends WshopAdminController {
    protected $model = 'attributvalue';
    protected $modelSaveItemFileName = 'image';

    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        $attr_id = WopshopRequest::getInt("attr_id");
        return "admin.php?page=wopshop-options&tab=".$this->getNameController()."&attr_id=".$attr_id;
    }
    
    function display($cachable = false, $urlparams = false) {
        $config = WopshopFactory::getConfig();

        $attr_id = WopshopRequest::getVar("attr_id");
        $context = "admin.attributesvalues.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', 'value_ordering');
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_dir', 'filter_order_Dir', 'asc');

        $attributValues = $this->getModel("AttributValue");
        $rows = $attributValues->getAllValues($attr_id, $filter_order, $filter_order_Dir);
        $attr_name = $attributValues->getNameValue($attr_id);

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $attributValues->getBulkActions($actions);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';

        $view = $this->getView("attributesvalues");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('attr_id', $attr_id);
        $view->assign('config', $config);
        $view->assign('bulk', $bulk);
        $view->assign('attr_name', $attr_name);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayAttributesValues', array(&$view));
        $view->display();
    }

    function edit() {
        global $wpdb;

        $config = WopshopFactory::getConfig();

        $value_id = WopshopRequest::getInt("value_id");
        $attr_id = WopshopRequest::getInt("attr_id");

        $attributValue = WopshopFactory::getTable('attributvalue');
        $attributValue->load($value_id);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        //FilterOutput::objectHTMLSafe($attributValue, ENT_QUOTES);

        $view = $this->getView("attributesvalues");
        $view->setLayout("edit");
        $view->assign('attributValue', $attributValue);
        $view->assign('attr_id', $attr_id);
        $view->assign('config', $config);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditAtributesValues', array(&$view));
        $view->display();
    }

    function save() {
        check_admin_referer('attributesvalues_edit');
        $config = WopshopFactory::getConfig();
        require_once ($config->path . 'lib/uploadfile.class.php');
        global $wpdb;
        $value_id = WopshopRequest::getInt("value_id");
        $attr_id = WopshopRequest::getInt("attr_id");
        $post = WopshopRequest::get("post");
        $attributValue = WopshopFactory::getTable('attributvalue');
        do_action_ref_array('onBeforeSaveAttributValue', array(&$post));
        $upload = new WopshopUploadFile($_FILES['image']);
        $upload->setAllowFile(array('jpeg', 'jpg', 'gif', 'png'));
        $upload->setDir($config->image_attributes_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        if ($upload->upload()) {
            if ($post['old_image']) {
                @unlink($config->image_attributes_path . "/" . $post['old_image']);
            }
            $post['image'] = $upload->getName();
            @chmod($config->image_attributes_path . "/" . $post['image'], 0777);
        } else {
            if ($upload->getError() != 4) {
                wopshopAddMessage(WOPSHOP_ERROR_UPLOADING_IMAGE, 'error');
                wopshopSaveToLog("error.log", "SaveAttributeValue - Error upload image. code: ".$upload->getError());
            }
        }

        if (!$value_id) {
            $query = "SELECT MAX(value_ordering) AS value_ordering FROM `" . $wpdb->prefix . "wshop_attr_values` where attr_id='" . esc_sql($attr_id) . "'";
            $row = $wpdb->get_results($query, OBJECT);
            $post['value_ordering'] = $row[0]->value_ordering + 1;
        }

        if (!$attributValue->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=attributesvalues&attr_id=" . $attr_id);
            return 0;
        }

        if (!$attributValue->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=attributesvalues&attr_id=" . $attr_id);
            return 0;
        }
        do_action_ref_array('onAfterSaveAttributValue', array(&$attributValue));
        $this->setRedirect("admin.php?page=wopshop-options&tab=attributesvalues&attr_id=" . $attr_id);
    }
    
    function delete_foto(){
        $id = WopshopRequest::getInt("id");
        $this->getAdminModel()->deleteFoto($id);
        die();               
    }  
    
    protected function getOrderWhere(){
        $attr_id = WopshopRequest::getInt("attr_id");
        return 'attr_id='.(int)$attr_id;
    }
	
	protected function getSaveOrderWhere(){
        $field_id = WopshopRequest::getInt("attr_id");
        return 'attr_id='.(int)$field_id;
    }    

}
