<?php
class DeliveryTimesWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
   
    function display() {
        $context = "admin.deliverytimes.";
        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', "name");
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc");

        $actions = array(
            'delete' => WOPSHOP_DELETE,
        );
        $_deliveryTimes = $this->getModel('deliverytimes');
        $bulk = $_deliveryTimes->getBulkActions($actions);
        $rows = $_deliveryTimes->getDeliveryTimes($filter_order, $filter_order_Dir);

        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';

        $view = $this->getView('deliverytimes');
        $view->setLayout('list');
        $view->assign('rows', $rows);
        $view->assign('filter_order',$filter_order);
        $view->assign('filter_order_Dir',$filter_order_Dir);
        $view->assign('bulk',$bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
		do_action_ref_array('onBeforeDisplayDeliveryTimes', array(&$view));
        $view->display();
        
    }
    function edit(){
        $id = WopshopRequest::getInt("row");
        $deliveryTimes = WopshopFactory::getTable('deliverytimes');
        $deliveryTimes->load($id);
        $edit = ($id)?(1):(0);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;

        $view=$this->getView("deliverytimes");
        $view->setLayout("edit");
        $view->assign('deliveryTimes', $deliveryTimes);        
        $view->assign('edit', $edit);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeEditDeliverytimes', array(&$view));
        $view->display();

    }
    function save(){
        if ( !empty($_POST) && check_admin_referer('deliverytimes_edit','name_of_nonce_field') )
        {
            //$mainframe = WopshopFactory::getApplication();
            $id = WopshopRequest::getInt("id");
            $deliveryTimes = WopshopFactory::getTable('deliverytimes');
            $post = WopshopRequest::get("post");
            do_action_ref_array( 'onBeforeSaveDeliveryTime', array(&$post) );
            if (!$deliveryTimes->bind($post)) {
                wopshopAddMessage(WOPSHOP_ERROR_BIND);
                $this->setRedirect("admin.php?page=wopshop-options&tab=deliverytimes");
                return 0;
            }
	
            if (!$deliveryTimes->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
                $this->setRedirect("admin.php?page=wopshop-options&tab=deliverytimes");
                return 0;
            }
            do_action_ref_array( 'onAfterSaveDeliveryTime', array(&$deliveryTimes) );
        }
        else wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
        $this->setRedirect('admin.php?page=wopshop-options&tab=deliverytimes');
    }
    

    function delete(){
        global $wpdb;
        $text = array();
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_DELIVERY_TIME_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=deliverytimes');
            return 0;
        }
        do_action_ref_array( 'onBeforeRemoveDeliveryTime', array(&$cid) );
        foreach ($cid as $key => $value) {
            if ($wpdb->delete($wpdb->prefix."wshop_delivery_times", array('id'=>esc_sql($value))))
                $text[] = WOPSHOP_DELIVERY_TIME_DELETED."<br>";
                else
                $text[] = WOPSHOP_DELIVERY_TIME_DELETED_ERROR_DELETED."<br>";
            }
        do_action_ref_array( 'onAfterRemoveDeliveryTime', array(&$cid) );
        $this->setRedirect('admin.php?page=wopshop-options&tab=deliverytimes', implode("</p><p>", $text));
    }
    
}