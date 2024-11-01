<?php

class ShippingsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=shippings";
    }

    function display() {
        $config = WopshopFactory::getConfig();

        $context = "admin.shippings.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', "ordering");
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "asc");

        $_shippings = $this->getModel("shippings");
        $rows = $_shippings->getAllShippings(0, $filter_order, $filter_order_Dir);

        $not_set_price = 0;
        $rowsprices = $_shippings->getAllShippingPrices(0);

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $_shippings->getBulkActions($actions);

        $shippings_prices = array();
        foreach ($rowsprices as $row) {
            $shippings_prices[$row->shipping_method_id][] = $row;
        }
        foreach ($rows as $k => $v) {
            if (is_array($shippings_prices[$v->shipping_id])) {
                $rows[$k]->count_shipping_price = count($shippings_prices[$v->shipping_id]);
            } else {
                $not_set_price = 1;
                $rows[$k]->count_shipping_price = 0;
            }
        }

        if ($not_set_price) {
            wopshopAddMessage(WOPSHOP_CERTAIN_METHODS_DELIVERY_NOT_SET_PRICE, 'error');
        }
        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';
        $view = $this->getView("shippings", 'html');
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeDisplayShippings', array(&$view));
        $view->display();
    }

    function edit() {
        $config = WopshopFactory::getConfig();
        $shipping_id = WopshopRequest::getInt("shipping_id");
        $shipping = WopshopFactory::getTable('shippingmethod');
        $shipping->load($shipping_id);
        $edit = ($shipping_id) ? ($edit = 1) : ($edit = 0);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;
        $params = $shipping->getParams();

        $_payments = $this->getModel("payments");
        $list_payments = $_payments->getAllPaymentMethods(0);

        $lists['payments'] = WopshopHtml::_('select.genericlist', $list_payments, 'listpayments[]', 'class="inputbox" size="10" multiple = "multiple"', 'payment_id', 'name', $shipping->getPayments());

        $nofilter = array();

        $view = $this->getView("shippings");
        $view->setLayout("edit");
        $view->assign('params', $params);
        $view->assign('shipping', $shipping);
        $view->assign('edit', $edit);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->assign('lists', $lists);
        $view->assign('config', $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeEditShippings', array(&$view));
        $view->display();
    }

    function save() {
        if (!empty($_POST) && check_admin_referer('shippings_edit', 'name_of_nonce_field')) {
            $shipping_id = WopshopRequest::getInt("shipping_id", 0);
            $shipping = WopshopFactory::getTable('shippingmethod');
            $post = WopshopRequest::get('post');
            if (!isset($post['published']))
                $post['published'] = 0;
            if (!isset($post['listpayments'])) {
                $post['listpayments'] = array();
            }
            $shipping->setPayments($post['listpayments']);
            do_action_ref_array('onBeforeSaveShipping', array(&$post));
            $_lang = $this->getModel("languages");
            $languages = $_lang->getAllLanguages(1);
            foreach ($languages as $lang) {
                $post['description_' . $lang->language] = WopshopRequest::getVar('description' . $lang->id, '', 'post', "string", 2);
            }
            if (!$shipping->bind($post)) {
                wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
                $this->setRedirect("admin.php?page=wopshop-options&tab=shippings");
                return 0;
            }
            $_shippings = $this->getModel("shippings");
            if (!$shipping->shipping_id) {
                $shipping->ordering = $_shippings->getMaxOrdering() + 1;
            }

            //$shipping->setParams($post['s_params']);
            if (!$shipping->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
                $this->setRedirect("admin.php?page=wopshop-options&tab=shippings");
                return 0;
            }
            do_action_ref_array('onAfterSaveShipping', array(&$shipping));
            $this->setRedirect("admin.php?page=wopshop-options&tab=shippings");
        }
    }

    function delete() {
        $cid = WopshopRequest::getVar("rows");
        global $wpdb;
        $text = array();
        
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_SHIPPINGS_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=shippings');
            return 0;
        }
        
        do_action_ref_array('onBeforeRemoveShipping', array(&$cid));
        foreach ($cid as $key => $value) {
            if ($wpdb->delete($wpdb->prefix . "wshop_shipping_method", array('shipping_id' => $value))) {
                $text[] = WOPSHOP_SHIPPING_DELETED;
                $query = "SELECT `sh_pr_method_id` FROM `" . $wpdb->prefix . "wshop_shipping_method_price` WHERE `shipping_method_id` = '" . esc_sql($value) . "'";
                $sh_pr_ids = $wpdb->get_results($query, OBJECT);
                if (count($sh_pr_ids)) {
                    foreach ($sh_pr_ids as $key2 => $value2) {
                        $wpdb->delete($wpdb->prefix . "wshop_shipping_method_price_weight", array('sh_pr_method_id' => esc_sql($value2)));
                    }
                }
                $wpdb->delete($wpdb->prefix . "wshop_shipping_method_price", array('shipping_method_id' => esc_sql($value)));
            } else {
                $text[] = WOPSHOP_ERROR_SHIPPING_DELETED;
            }
        }
        do_action_ref_array('onAfterRemoveShipping', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=shippings", implode("</li><li>", $text));
    }

}
