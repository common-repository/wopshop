<?php
class CouponsWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=coupons";
    }    
   
    function display() {
        $context = "admin.coupons.";
        $limit = wopshopGetStateFromRequest( $context.'per_page', 'per_page', 20);
        $paged = wopshopGetStateFromRequest($context.'paged', 'paged', 1);

        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', 'C.coupon_code');
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc');

        $model = $this->getModel('coupons');
        $total = $model->getCountCoupons();

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $model->getBulkActions($actions);

        if(($paged-1) > ($total/$limit) ) $paged = 1;
        $limitstart = ($paged-1)*$limit;
        $pagination = $model->getPagination($total, $limit);

        $config = WopshopFactory::getConfig();

        $rows = $model->getAllCoupons($limitstart, $limit, $filter_order, $filter_order_Dir);
        
        foreach ($rows as $row) {
            $row->tmp_extra_column_cells = "";
        }

        $currency = WopshopFactory::getTable('currency');
        $currency->load($config->mainCurrency);

        $view = $this->getView('coupons');
        $view->setLayout('list');
        $view->assign('rows', $rows);
        $view->assign('currency', $currency->currency_code);
        $view->assign('pagination', $pagination);
        $view->assign('filter_order', $filter_order);
        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('bulk', $bulk);
        $view->tmp_html_filter = "";
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->tmp_extra_column_headers = "";
		do_action_ref_array('onBeforeDisplayCoupons', array(&$view));
	$view->display();
    }

    function edit(){
        $coupon_id = WopshopRequest::getInt('row');
        $coupon = WopshopFactory::getTable('coupon');
        $coupon->load($coupon_id);
        $edit = ($coupon_id)?($edit = 1):($edit = 0);
        $arr_type_coupon = array();
        $arr_type_coupon[0] = new StdClass();
        $arr_type_coupon[0]->coupon_type = 0;
        $arr_type_coupon[0]->coupon_value = WOPSHOP_COUPON_PERCENT;

        $arr_type_coupon[1] = new StdClass();
        $arr_type_coupon[1]->coupon_type = 1;
        $arr_type_coupon[1]->coupon_value = WOPSHOP_COUPON_ABS_VALUE;
        
        if (!$coupon_id){
          $coupon->coupon_type = 0;
          $coupon->finished_after_used = 1;
          $coupon->for_user_id = 0;
        }
        $currency_code = wopshopGetMainCurrencyCode();

        $lists['coupon_type'] = WopshopHtml::_('select.radiolist', $arr_type_coupon, 'coupon_type', 'onchange="changeCouponType()"', 'coupon_type', 'coupon_value', $coupon->coupon_type);

        $_tax = $this->getModel("taxes");
        $all_taxes = $_tax->getAllTaxes();
        $list_tax = array();
        foreach ($all_taxes as $tax) {
            $list_tax[] = WopshopHtml::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
        }
        $lists['tax'] = WopshopHtml::_('select.genericlist', $list_tax, 'tax_id', 'class = "inputbox" size = "1" ', 'tax_id', 'tax_name', $coupon->tax_id);

        $view=$this->getView("coupons");
        $view->setLayout("edit");
        $view->assign('coupon', $coupon);
        $view->assign('lists', $lists);
        $view->assign('edit', $edit);
        $view->assign('currency_code', $currency_code);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
		do_action_ref_array('onBeforeEditCoupons', array(&$view));
        $view->display();
        
    }
    function save(){
        check_admin_referer('coupon_edit','name_of_nonce_field');
        $coupon_id = WopshopRequest::getInt("coupon_id");
        $coupon = WopshopFactory::getTable('coupon');

        $post = WopshopRequest::get("post");        
        $post['coupon_code'] = WopshopRequest::getCmd("coupon_code");
        $post['coupon_publish'] = WopshopRequest::getInt("coupon_publish");
        $post['finished_after_used'] = WopshopRequest::getInt("finished_after_used");
        $post['coupon_value'] = wopshopSaveAsPrice($post['coupon_value']);
        do_action_ref_array( 'onBeforeSaveCoupon', array(&$post) );
        if (!$post['coupon_code']){
            wopshopAddMessage(WOPSHOP_ERROR_COUPON_CODE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=coupons&task=edit&row=".$coupon->coupon_id);
            return 0;
        }

        if ($post['coupon_value']<0 || ($post['coupon_value']>100 && $post['coupon_type']==0)){
            wopshopAddMessage(WOPSHOP_ERROR_COUPON_VALUE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=coupons&task=edit&row=".$coupon_id);    
            return 0;
        }

        if(!$coupon->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=coupons");
            return 0;
        }

        if ($coupon->getExistCode()){
            wopshopAddMessage(WOPSHOP_ERROR_COUPON_EXIST, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=coupons");
            return 0;
        }

        if (!$coupon->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=coupons");
            return 0;
        }
        do_action_ref_array( 'onAfterSaveCoupon', array(&$coupon) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=coupons", WOPSHOP_MESSAGE_SAVEOK);
    }

}