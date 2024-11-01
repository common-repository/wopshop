<?php
class TaxesWshopAdminController extends WshopAdminController {
    function __construct(){
        parent::__construct();
    }

    function display() {
        $filter_order = wopshopGetStateFromRequest('taxes_filter_order', 'filter_order', 'tax_name');
        $filter_order_Dir = wopshopGetStateFromRequest('taxes_filter_order_Dir', 'filter_order_Dir', 'asc');

        $model = $this->getModel("taxes");
        $rows = $model->getAllTaxes($filter_order, $filter_order_Dir);
        
        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $model->getBulkActions($actions);
        
        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        $view=$this->getView("taxes");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('bulk', $bulk);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeDisplayTaxes', array(&$view));
        $view->display();
    }
    function edit(){
        $tax_id = WopshopRequest::getInt("tax_id");
        $tax = WopshopFactory::getTable('tax');
        $tax->load($tax_id);
        $edit = ($tax_id)?($edit = 1):($edit = 0);

        $view=$this->getView("taxes");
        $view->setLayout("edit");
        $view->assign('tax', $tax); 
        $view->assign('edit', $edit);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditTaxes', array(&$view));
        $view->display();
    }
    function save(){
        check_admin_referer('tax_edit','name_of_nonce_field');
        $tax_id = WopshopRequest::getInt("tax_id");
        $tax = WopshopFactory::getTable('tax');
        $post = WopshopRequest::get("post"); 
        $post['tax_value'] = wopshopSaveAsPrice($post['tax_value']);
        do_action_ref_array( 'onBeforeSaveTax', array(&$tax) );
        if (!$tax->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND);
            $this->setRedirect("admin.php?page=wopshop-options&tab=taxes");
            return 0;
        }

        if (!$tax->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
            $this->setRedirect("admin.php?page=wopshop-options&tab=taxes");
            return 0; 
        }
        do_action_ref_array( 'onAfterSaveTax', array(&$tax) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=taxes", WOPSHOP_MESSAGE_SAVEOK);
    }

    function delete(){
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_TAXES_TEXAS_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=taxes');
            return 0;
        }
        global $wpdb;
        $text = '';
		do_action_ref_array( 'onBeforeRemoveTax', array(&$cid) );
        foreach ($cid as $key => $value) {
            $tax = WopshopFactory::getTable('tax');
            $tax->load($value);
            $query2 = "SELECT pr.product_id
                       FROM `".$wpdb->prefix."wshop_products` AS pr
                       WHERE pr.product_tax_id = '".esc_sql($value)."'";
            $res = $wpdb->get_results($query2);
            if(count($res)){
                $text .= sprintf(WOPSHOP_TAX_NO_DELETED, $tax->tax_name)."<br>";
                continue;
            }
            
            if ($wpdb->delete($wpdb->prefix."wshop_taxes", array( 'tax_id' => esc_sql($value)) )){
                $text .= sprintf(WOPSHOP_TAX_DELETED,$tax->tax_name)."<br>";
            }
            $wpdb->delete($wpdb->prefix."wshop_taxes_ext", array( 'tax_id' => esc_sql($value)) );
        }
		do_action_ref_array( 'onAfterRemoveTax', array(&$cid) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=taxes", $text);
    }
}