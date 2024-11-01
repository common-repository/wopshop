<?php
class AttributesGroupsWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=attributesgroups";
    }    
   
    function display($cachable = false, $urlparams = false){        
        $config = WopshopFactory::getConfig();
        
        $model = $this->getModel("attributesgroups");
        $rows = $model->getList();
        
        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $model->getBulkActions($actions);        
        
        $view = $this->getView("attributesgroups");
        $view->setLayout("list");
        $view->assign('bulk', $bulk);
        $view->assign('rows', $rows);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeDisplayAttributesGroups', array(&$view));
        $view->display();
    }
    
    function edit(){
        $id = WopshopRequest::getInt('id');
        $row = WopshopFactory::getTable('attributesgroup');
        $row->load($id);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        $view = $this->getView("attributesgroups");
        $view->setLayout("edit");
        $view->assign('row', $row);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditAttributesGroups', array(&$view));
        $view->display();
    }

    function save(){
        check_admin_referer('attributesgroups_edit');
        $post = WopshopRequest::get("post");
        $row = WopshopFactory::getTable('attributesGroup');
        do_action_ref_array('onBeforeSaveAttributesGroups', array(&$post));
        $row->bind($post);
        if (!$post['id']){
            $row->ordering = null;
            $row->ordering = $row->getNextOrder();
        }
        $row->store();
        $this->setRedirect("admin.php?page=wopshop-options&tab=attributesgroups");
    }
}