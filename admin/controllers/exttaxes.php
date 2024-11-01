<?php
class ExtTaxesWshopAdminController extends WshopAdminController{
    function __construct() {
        parent::__construct();
    }

    function display(){
        $сonfig = WopshopFactory::getConfig();
        $back_tax_id = WopshopRequest::getInt("back_tax_id");
        $mainframe = WopshopFactory::getApplication();
        $context = "wopshop.list.admin.exttaxes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ET.id", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $taxes = $this->getModel("taxes");
        $rows = $taxes->getExtTaxes($back_tax_id, $filter_order, $filter_order_Dir);

        $countries = $this->getModel("countries");
        $list = $countries->getAllCountries(0);
        $countries_name = array();
        foreach($list as $v){
            $countries_name[$v->country_id] = $v->name;
        }

        foreach($rows as $k=>$v){
            if($v->zones != '')
            $list = json_decode($v->zones, 1);

            if(is_array($list))
            foreach($list as $k2=>$v2){
                $list[$k2] = $countries_name[$v2];
            }
            if (count($list) > 10){
                $tmp = array_slice($list, 0, 10);
                $rows[$k]->countries = implode(", ", $tmp)."...";
            }else{
                if(is_array($list))
                $rows[$k]->countries = implode(", ", $list);
                else 
                $rows[$k]->countries = '';
            }
        }
        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $countries->getBulkActions($actions);
        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        $view = $this->getView("taxesext");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('back_tax_id', $back_tax_id);
        $view->assign('config', $сonfig);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforedisplayExtTax', array(&$view)); 
        $view->display();
    }

    function edit(){
        $сonfig = WopshopFactory::getConfig();
        $back_tax_id = WopshopRequest::getInt("back_tax_id");
        $id = WopshopRequest::getInt("row");

        $tax = WopshopFactory::getTable('taxext');
        $tax->load($id);

        if (!$tax->tax_id && $back_tax_id){
            $tax->tax_id = $back_tax_id;
        }

        $list_c = $tax->getZones();
        $zone_countries = array();
        foreach($list_c as $v){
            $obj = new stdClass();
            $obj->country_id = $v;
            $zone_countries[] = $obj;
        }

        $taxes = $this->getModel("taxes");
        $all_taxes = $taxes->getAllTaxes();
        $list_tax = array();
        foreach ($all_taxes as $_tax) {
            $list_tax[] = WopshopHtml::_('select.option', $_tax->tax_id,$_tax->tax_name, 'tax_id', 'tax_name');
        }
        $lists['taxes'] = WopshopHtml::_('select.genericlist', $list_tax, 'tax_id', '', 'tax_id', 'tax_name', $tax->tax_id);

        $countries = $this->getModel("countries");
        $lists['countries'] = WopshopHtml::_('select.genericlist', $countries->getAllCountries(0), 'countries_id[]', 'size = "10", multiple = "multiple"', 'country_id', 'name', $zone_countries);        

        $view = $this->getView("taxesext", 'html');
        $view->setLayout("edit");
        //JFilterOutput::objectHTMLSafe($tax, ENT_QUOTES);
        $view->assign('tax', $tax);
        $view->assign('back_tax_id', $back_tax_id);
        $view->assign('lists', $lists);
        $view->assign('config', $сonfig);
        $view->etemplatevar = "";
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeEditExtTax', array(&$view));
        $view->display();
    }

    function save(){
        $back_tax_id = WopshopRequest::getInt("back_tax_id");
        $id = WopshopRequest::getInt("id");
        $tax = WopshopFactory::getTable('taxExt');
        $post = WopshopRequest::get("post"); 
        $post['tax'] = wopshopSaveAsPrice($post['tax']);
        $post['firma_tax'] = wopshopSaveAsPrice($post['firma_tax']);
        do_action_ref_array( 'onBeforeSaveExtTax', array(&$post) );
        
        if (!$tax->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND);
            $this->setRedirect("admin.php?page=wopshop-options&tab=exttaxes&back_tax_id=".$back_tax_id);
            return 0;
        }
        $tax->setZones($post['countries_id']);

        if (!$tax->store()){
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
            $this->setRedirect("admin.php?page=wopshop-options&tab=exttaxes&back_tax_id=".$back_tax_id);
            return 0; 
        }

        wopshopUpdateCountExtTaxRule();

        do_action_ref_array( 'onAfterSaveExtTax', array(&$tax) );

            $this->setRedirect("admin.php?page=wopshop-options&tab=exttaxes&back_tax_id=".$back_tax_id);
    }

    function delete(){
        $back_tax_id = WopshopRequest::getInt("back_tax_id");
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_TAXES_TEXAS_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=taxes');
            return 0;
        }
        
        global $wpdb;
        $text = array();

        do_action_ref_array( 'onBeforeRemoveExtTax', array(&$cid) );

        foreach ($cid as $key => $value) {
            $wpdb->delete($wpdb->prefix."wshop_taxes_ext", array( 'id' => esc_sql($value)) );
            $text[] = WOPSHOP_ITEM_DELETED;
        }
        
        wopshopUpdateCountExtTaxRule();
        
        do_action_ref_array( 'onAfterRemoveExtTax', array(&$cid) );
        
        $this->setRedirect("admin.php?page=wopshop-options&tab=exttaxes&back_tax_id=".$back_tax_id, implode("</li><li>",$text));
    }
    
    function back(){
        $this->setRedirect("admin.php?page=wopshop-options&tab=exttaxes");
    }
    
}