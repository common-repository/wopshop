<?php
class shippingextpriceWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }

    function display() {
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$rows = $shippings->getList();
		$view = $this->getView("shippingext");
        $view->setLayout("list");
		$view->assign('rows', $rows);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayShippingExtPrices', array(&$view));
		$view->display();				
    }
	
	
    function edit(){
		$id = WopshopRequest::getInt("id");
        $row = WopshopFactory::getTable('shippingExt');
        $row->load($id);

        if (!$row->exec) {
			wopshopAddMessage("Error load ShippingExt", 'error');
        }

        $shippings_conects = $row->getShippingMethod();

        $shippings = WopshopFactory::getAdminModel("shippings");
        $list_shippings = $shippings->getAllShippings(0);

        $view = $this->getView("shippingext");
        $view->setLayout("edit");
        $view->assign('row', $row);
        $view->assign('list_shippings', $list_shippings);
        $view->assign('shippings_conects', $shippings_conects);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditShippingExtPrice', array(&$view));
        $view->display();
    }
    function save(){
		$id = WopshopRequest::getInt("id");		
        $post = WopshopRequest::get("post");
        $row = WopshopFactory::getTable('shippingExt');        
        do_action_ref_array( 'onBeforeSaveShippingExtCalc', array(&$post));        
        $row->bind($post);        
        $row->setShippingMethod($post['shipping']); 
        if(isset($post['params'])){
            $row->setParams($post['params']);
        }
		$row->store();
        
        do_action_ref_array( 'onAfterSaveShippingExtCalc', array(&$row) );        		
        $this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice");	
    }
	
	function publish() {
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$shippings->republish();
		$this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice");
	}

	function unpublish() {
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$shippings->republish();
		$this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice");
	}
	
    function delete(){
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$shippings->delete();
		$this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice",  WOPSHOP_ITEM_DELETED);			
    }
	
	function orderup(){
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$shippings->reorder();
		$this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice");			
	}

	function orderdown(){
		$shippings = WopshopFactory::getAdminModel("shippingextprice");
		$shippings->reorder();
		$this->setRedirect("admin.php?page=wopshop-options&tab=shippingextprice");			
	}
				
}