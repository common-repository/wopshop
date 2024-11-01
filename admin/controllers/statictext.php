<?php
class StatictextWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
   
    function display() {
        $orderby = wopshopGetStateFromRequest('statictext_orderby', 'orderby', 'id');
        $order = wopshopGetStateFromRequest('statictext_order', 'order', 'asc');

        $config = WopshopFactory::getConfig();

        $model = $this->getModel('statictext');
        $actions = array();

        $statictext = $model->getAllStatictext($orderby, $order);

        $view = $this->getView('statictext');
        $view->setLayout('list');
        $view->assign('statictext',$statictext);
        if($order == 'asc') $order = 'desc'; else $order = 'asc';
        $view->assign('orderby',$orderby);
        $view->assign('order',$order);
        $view->assign('config',$config);
		do_action_ref_array('onBeforeDisplayStatisticText', array(&$view));
        $view->display();
    }
    function edit(){
        $config = WopshopFactory::getConfig();
        $id = WopshopRequest::getInt("row");
        
        $statictext = WopshopFactory::getTable("statictext");
        $statictext->load($id);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;

        $nofilter = array();        
        $view = $this->getView('statictext');
        $view->setLayout('edit');
        $view->assign('statictext',$statictext);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
		do_action_ref_array('onBeforeDisplayStatisticTextEdit', array(&$view));
        $view->display();
    }
    function save(){
        if ( !empty($_POST) && check_admin_referer('statictext_edit','name_of_nonce_field') )
        {
            $config = WopshopFactory::getConfig();

            $id = WopshopRequest::getInt("statictext_id");
            $post = WopshopRequest::get("post");
			do_action_ref_array( 'onBeforeSaveConfigStaticPage', array(&$post) );
            $_lang = $this->getModel("languages");
            $languages = $_lang->getAllLanguages(1);

            foreach($languages as $lang){
                $post['text_'.$lang->language] = WopshopRequest::getVar('text'.$lang->id,'','post',"string", 2);
            }
 
            $statictext = WopshopFactory::getTable("statictext");
            $statictext->load($id);
            $statictext->bind($post);        
            $result = $statictext->store($post);
			do_action_ref_array( 'onAfterSaveConfigStaticPage', array(&$statictext) );
            if($result) 
                $this->setRedirect('admin.php?page=wopshop-configuration&tab=statictext', WOPSHOP_CONFIG_SUCCESS);
            else 
                $this->setRedirect('admin.php?page=wopshop-configuration&tab=statictext', WOPSHOP_CONFIG_ERROR);
        }
    }

    function delete(){
        $id = WopshopRequest::getInt("row");
        $statictext = WopshopFactory::getTable("statictext");
        $statictext->load($id);
        $statictext->delete();

        $this->setRedirect('admin.php?page=wopshop-configuration&tab=statictext');
    }
}