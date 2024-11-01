<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AddonsWshopAdminController extends WshopAdminController {
    public function __construct() {        
        parent::__construct();
    }
   
    public function display() {
        $addons = $this->getModel("addons");
        $rows = $addons->getList(1);
        
        $actions = array(
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH
        );
        $bulk = $addons->getBulkActions($actions);
        
        $view = $this->getView("addons");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->back = base64_encode( admin_url( "admin.php?page=wopshop-options&tab=addons" ) );
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayAddons', array(&$view));
        $view->display();
    }
    
    public function edit(){
        $id = WopshopRequest::getInt("id");
        $row = WopshopFactory::getTable('addon');
        $row->load($id);

        $config_file_patch = WOPSHOP_PLUGIN_DIR."/site/addons/".$row->alias."/config.tmpl.php";

        $view = $this->getView("addons");
        $view->setLayout("edit");
        $view->assign('row', $row);
        $view->assign('params', $row->getParams());
        $view->assign('config_file_patch', $config_file_patch);
        $view->assign('config_file_exist', file_exists($config_file_patch));
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeEditAddons', array(&$view));
        $view->display();
    }
    
    public function save(){ 
        check_admin_referer('addon_edit');
        $post = WopshopRequest::get('post');
        $this->getModel("addons")->save($post);       
        $this->setRedirect( admin_url( 'admin.php?page=wopshop-options&tab=addons' ) );
    }

    public function delete(){
        $id = WopshopRequest::getInt("id");
        $this->getModel("addons")->delete($id);
        $this->setRedirect( admin_url( 'admin.php?page=wopshop-options&tab=addons' ) );
    }
    
    protected function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=addons";
    }
}