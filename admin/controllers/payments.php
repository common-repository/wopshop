<?php

class PaymentsWshopAdminController extends WshopAdminController {

    function __construct() {
        $config = WopshopFactory::getConfig();
        parent::__construct();
        if (file_exists($config->path . 'payments/payment.php')) {
            include_once $config->path . 'payments/payment.php';
        }
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=payments";
    }    

    function display() {
        $context = "admin.payments.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', "payment_ordering");
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "asc");

        $model = $this->getModel('payments');
        $rows = $model->getAllPaymentMethods(0, $filter_order, $filter_order_Dir);
        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $model->getBulkActions($actions);
        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';
        $view = $this->getView('payments');
        $view->setLayout('list');
        $view->assign('bulk', $bulk);
        $view->assign('rows', $rows);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeDisplayPayments', array(&$view));
        $view->display();
    }

    function edit() {
        $config = WopshopFactory::getConfig();
        $payment_id = WopshopRequest::getInt("rows");
        $payment = WopshopFactory::getTable('paymentmethod');
        $payment->load($payment_id);
        $parseString = new WopshopParseString($payment->payment_params);
        $params = $parseString->parseStringToParams();
        $edit = ($payment_id) ? ($edit = 1) : ($edit = 0);

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;
        $_payments = $this->getModel("payments");

        if ($edit) {
            $paymentsysdata = $payment->getPaymentSystemData();
            if ($paymentsysdata->paymentSystem) {
                ob_start();
                $paymentsysdata->paymentSystem->showAdminFormParams($params);
                $lists['html'] = ob_get_contents();
                ob_get_clean();
            } else {
                $lists['html'] = '';
            }
        } else {
            $lists['html'] = '';
        }
        $currencyCode = wopshopGetMainCurrencyCode();
        if ($config->tax) {
            $_tax = $this->getModel("taxes");
            $all_taxes = $_tax->getAllTaxes();
            $list_tax = array();
            $list_tax[] = WopshopHtml::_('select.option', -1, WOPSHOP_PRODUCT_TAX_RATE, 'tax_id', 'tax_name');
            foreach ($all_taxes as $tax) {
                $list_tax[] = WopshopHtml::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)', 'tax_id', 'tax_name');
            }
            $lists['tax'] = WopshopHtml::_('select.genericlist', $list_tax, 'tax_id', 'class = "inputbox"', 'tax_id', 'tax_name', $payment->tax_id);
        }

        $list_price_type = array();
        $list_price_type[] = WopshopHtml::_('select.option', "1", $currencyCode, 'id', 'name');
        $list_price_type[] = WopshopHtml::_('select.option', "2", "%", 'id', 'name');
        $lists['price_type'] = WopshopHtml::_('select.genericlist', $list_price_type, 'price_type', 'class = "inputbox"', 'id', 'name', $payment->price_type);

        $payment_type = array('1' => WOPSHOP_TYPE_DEFAULT, '2' => WOPSHOP_PAYPAL_RELATED);
        
        $opt = array();
        foreach ($payment_type as $key => $value) {
            $opt[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name');
        }
        if ($config->shop_mode == 0 && $payment_id) {
            $disabled = 'disabled';
        } else {
            $disabled = '';
        }
        $lists['type_payment'] = WopshopHtml::_('select.genericlist', $opt, 'payment_type', 'class = "inputbox" ' . $disabled, 'id', 'name', $payment->payment_type);

        $nofilter = array();
        //FilterOutput::objectHTMLSafe($payment, ENT_QUOTES, $nofilter);

        $view = $this->getView('payments');
        $view->setLayout('edit');
        $view->assign('payment', $payment);
        $view->assign('edit', $edit);
        $view->assign('params', $params);
        $view->assign('lists', $lists);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->assign('config', $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditPayments', array(&$view));
        $view->display();
    }

    function save() {
        if (check_admin_referer('payment_edit', 'wop_shop') && !empty($_POST)) {
            $payment_id = WopshopRequest::getInt("payment_id");

            $payment = WopshopFactory::getTable('paymentmethod');
            $post = WopshopRequest::get("post");

            if (!isset($post['payment_publish']))
                $post['payment_publish'] = 0;
            if (!isset($post['show_descr_in_email']))
                $post['show_descr_in_email'] = 0;
            $post['price'] = wopshopSaveAsPrice($post['price']);
            $post['payment_class'] = WopshopRequest::getCmd("payment_class");
            if (!$post['payment_id'])
                $post['payment_type'] = 1;
            do_action_ref_array('onBeforeSavePayment', array(&$post));
            $_lang = $this->getModel("languages");
            $languages = $_lang->getAllLanguages(1);

            foreach ($languages as $lang) {
                $post['description_' . $lang->language] = WopshopRequest::getVar('description' . $lang->id, '', 'post', "string", 2);
            }
            $payment->bind($post);

            $_payments = $this->getModel("payments");
            if (!$payment->payment_id) {
                $payment->payment_ordering = $_payments->getMaxOrdering() + 1;
            }
            if (isset($post['pm_params'])) {
                $parseString = new WopshopParseString($post['pm_params']);
                $payment->payment_params = $parseString->splitParamsToString();
            }

            if (!$payment->check()) {
                wopshopAddMessage($payment->getError());
                $this->setRedirect("admin.php?page=wopshop-options&tab=payments&task=edit&rows=" . $payment->payment_id);
                return 0;
            }
            $payment->store();

            do_action_ref_array('onAfterSavePayment', array(&$payment));
            $this->setRedirect("admin.php?page=wopshop-options&tab=payments", WOPSHOP_MESSAGE_SAVEOK);
        }
    }

    function delete() {
        global $wpdb;
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_PAYHMENTSS_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=payments');
            return 0;
        }
        $text = '';
        do_action_ref_array('onBeforeRemovePayment', array(&$cid));
        foreach ($cid as $key => $value) {
            $result = $wpdb->delete($wpdb->prefix . 'wshop_payment_method', array('payment_id' => esc_sql($value)));
            if ($result > 0)
                $text .= WOPSHOP_PAYMENT_DELETED . "<br>";
            else
                $text .= WOPSHOP_ERROR_PAYMENT_DELETED . "<br>";
        }
        do_action_ref_array('onAfterRemovePayment', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=payments", $text);
    }

}
