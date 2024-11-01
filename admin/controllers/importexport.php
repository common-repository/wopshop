<?php
if (!defined('ABSPATH' )) {
	exit; // Exit if accessed directly
}

include_once(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/iecontroller.php");

class ImportExportWshopAdminController extends WshopAdminController{
    
    public function __construct(){
        parent::__construct();        
    }

    public function display(){      
    	$importexport = WopshopFactory::getAdminModel("importexport");    	
        $rows = $importexport->getList();		
        $view=$this->getView("importexportlist", 'html');
        $view->assign('rows', $rows);
        do_action_ref_array('onBeforeDisplayImportExport', array(&$view));
        $view->display();
    }

    public function remove() {        
        $cid = WopshopRequest::getInt("cid");        
        $_importexport = WopshopFactory::getTable('ImportExport'); 
        $_importexport->load($cid);        
        $_importexport->delete();        
        $this->setRedirect('admin.php?page=wopshop-options&tab=importexport', WOPSHOP_ITEM_DELETED);
    }
    
    public function setautomaticexecution(){
        $cid = WopshopRequest::getInt("cid");        
        $_importexport = WopshopFactory::getTable('Importexport'); 
        $_importexport->load($cid);
        if ($_importexport->steptime > 0){
            $_importexport->steptime = 0;
        }else{
            $_importexport->steptime = 1;
        }
        $_importexport->store();
        $this->setRedirect('admin.php?page=wopshop-options&tab=importexport');
    }
    
    public function view(){
        $ie_id = WopshopRequest::getInt("ie_id");
        $_importexport = WopshopFactory::getTable('Importexport'); 
        $_importexport->load($ie_id);
        
        if (isset($_importexport->id) && $_importexport->id){
            $alias = $_importexport->get('alias');

            if (!file_exists(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/".$alias."/".$alias.".php")){
                sprintf(WOPSHOP_ERROR_FILE_NOT_EXIST, "/importexport/".$alias."/".$alias.".php");
                return 0;
            }

            include_once(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/".$alias."/".$alias.".php");

            $classname = 'Ie'.$alias;
            $controller = new $classname($ie_id);
            $controller->set('ie_id', $ie_id);
            $controller->set('alias', $alias);
            $controller->execute(WopshopRequest::getVar('task'));
        } else {
            $this->setRedirect('admin.php?page=wopshop-options&tab=importexport');
        }
    }
    
    public function filedelete(){
        $this->view();
    }
            
    public function save(){
        $this->view();        
    }		      
}