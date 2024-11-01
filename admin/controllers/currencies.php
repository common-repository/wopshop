<?php
class CurrenciesWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=currencies";
    }
    
    function display() {
        $config = WopshopFactory::getConfig();
        $context = "admin.currencies.";
        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', "currency_ordering");
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc");

        $current_currency = WopshopFactory::getTable('currency');
        $current_currency->load($config->mainCurrency);
        if ($current_currency->currency_value!=1){
            wopshopAddMessage(WOPSHOP_ERROR_MAIN_CURRENCY_VALUE);
        }

        $currencies = $this->getModel("currencies");
        $rows = $currencies->getAllCurrencies(0, $filter_order, $filter_order_Dir);

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $currencies->getBulkActions($actions);
        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';

        $view=$this->getView("currencies");
        $view->setLayout("list");        
        $view->assign('rows', $rows);        
        $view->assign('config', $config);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('bulk',$bulk);
        $view->etemplatevar = "";
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeDisplayCourencies', array(&$view));
        $view->display();
    }
    
    function edit(){
        $currency = WopshopFactory::getTable('currency');
        $currencies = $this->getModel("currencies");
        $currency_id = WopshopRequest::getInt('rows');
        $currency->load($currency_id);
        if ($currency->currency_value==0) $currency->currency_value = 1;
        $first[] = WopshopHtml::_('select.option', '0',WOPSHOP_ORDERING_FIRST,'currency_ordering','currency_name');
        $rows = array_merge($first, $currencies->getAllCurrencies() );
        $lists['order_currencies'] = WopshopHtml::_('select.genericlist', $rows, 'currency_ordering', 'class="form-control" size="1"', 'currency_ordering', 'currency_name', $currency->currency_ordering);
        $edit = ($currency_id)?($edit = 1):($edit = 0);
        //FilterOutput::objectHTMLSafe( $currency, ENT_QUOTES);
        $view=$this->getView("currencies");
        $view->setLayout("edit");
        $view->assign('currency', $currency);
        $view->assign('lists', $lists);
        $view->assign('edit', $edit);
        $view->etemplatevar = "";
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeEditCurrencies', array(&$view));        
        $view->display();
    } 

    function save(){
        check_admin_referer('coutry_edit','name_of_nonce_field');
        $currency_id = WopshopRequest::getInt("currency_id");
        $currency = WopshopFactory::getTable('currency');
        $post = WopshopRequest::get("post");
        $post['currency_value'] = wopshopSaveAsPrice($post['currency_value']);
        do_action_ref_array( 'onBeforeSaveCurrencie', array(&$post) );
        if (!$currency->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=currencies");
            return 0;
        }
        if ($currency->currency_value==0) $currency->currency_value = 1;

        $this->_reorderCurrency($currency);
        if (!$currency->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=currencies");
            return 0;
        }
        do_action_ref_array( 'onAfterSaveCurrencie', array(&$currency) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=currencies", WOPSHOP_MESSAGE_SAVEOK);
    }

    function _reorderCurrency(&$currency) {
        global $wpdb;
        $query = "UPDATE `".$wpdb->prefix."wshop_currencies` SET `currency_ordering` = currency_ordering + 1 WHERE `currency_ordering` > '" . $currency->currency_ordering . "'";
        $wpdb->get_results($query);
        $currency->currency_ordering++;
    }

    function setdefault(){
        $config = WopshopFactory::getConfig();
        $cid = WopshopRequest::getInt("currency_id");
        if ($cid){
            $configuration = WopshopFactory::getTable('configuration');
            $configuration->id = '1';
            $configuration->mainCurrency = $cid;
            $configuration->store();
        }
        $this->setRedirect('admin.php?page=wopshop-options&tab=currencies');
    }

}