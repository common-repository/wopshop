<?php

class VendorsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }

    function display() {
        $mainframe = WopshopFactory::getApplication();
        $context = "list.admin.vendors";
        $limit = wopshopGetStateFromRequest($context . 'per_page', 'per_page', 20);
        $paged = $mainframe->getUserStateFromRequest($context . 'paged', 'paged', '1');
        $text_search = $mainframe->getUserStateFromRequest($context . 'text_search', 's', '');

        $vendors = $this->getModel("vendors");
        $total = $vendors->getCountAllVendors($text_search);
        $search = $vendors->search($text_search);

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $vendors->getBulkActions($actions);

        if (($paged - 1) > ($total / $limit))
            $paged = 1;
        $limitstart = ($paged - 1) * $limit;
        $pagination = $vendors->getPagination($total, $limit);
        $rows = $vendors->getAllVendors($limitstart, $limit, $text_search);

        $view = $this->getView("vendors");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('limit', $limit);
        $view->assign('limitstart', $limitstart);
        $view->assign('search', $search);
        $view->assign('pagination', $pagination);
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_filter = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayVendors', array(&$view));
        $view->display();
    }

    function delete() {
        global $wpdb;
        $vendor = WopshopFactory::getTable('vendor');
        $cid = WopshopRequest::getVar('rows');
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=vendors');
            return 0;
        }
        do_action_ref_array('onBeforeRemoveVendor', array(&$cid));
        foreach ($cid as $id) {
            $query = "select count(*) from `" . $wpdb->prefix . "wshop_products` where `vendor_id`=" . intval($id);
            $cp = $wpdb->get_var($query);
            if (!$cp) {
                $query = "delete from `" . $wpdb->prefix . "wshop_vendors` where id='" . esc_sql($id) . "' and main=0";
                $wpdb->query($query);
            } else {
                $vendor->load($id);
                wopshopAddMessage(sprintf(WOPSHOP_ITEM_ALREADY_USE, $vendor->f_name . " " . $vendor->l_name), 'error');
            }
        }
        do_action_ref_array('onAfterRemoveVendor', array(&$cid));

        $this->setRedirect("admin.php?page=wopshop-options&tab=vendors");


        $reviews_model = $this->getModel("reviews");
        $cid = WopshopRequest::getVar('cid');

        do_action_ref_array('onBeforeRemoveReview', array(&$cid));

        foreach ($cid as $key => $value) {
            $review = WopshopFactory::getTable('review');
            $review->load($value);
            $reviews_model->deleteReview($value);
            $product = WopshopFactory::getTable('product');
            $product->load($review->product_id);
            $product->loadAverageRating();
            $product->loadReviewsCount();
            $product->store();
            unset($product);
            unset($review);
        }
        do_action_ref_array('onAfterRemoveReview', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
    }

    function edit() {
        $id = WopshopRequest::getInt("id");
        $vendor = WopshopFactory::getTable('vendor');
        $vendor->load($id);
        if (!$id) {
            $vendor->publish = 1;
        }
        $_countries = $this->getModel("countries");
        $countries = $_countries->getAllCountries(0);
        $lists['country'] = WopshopHtml::_('select.genericlist', $countries, 'country', 'class = "inputbox" size = "1"', 'country_id', 'name', $vendor->country);


        $view = $this->getView("vendors", 'html');
        $view->setLayout("edit");
        $view->assign('vendor', $vendor);
        $view->assign('lists', $lists);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditVendors', array(&$view));
        $view->display();
    }

    function save() {
        $vendor = WopshopFactory::getTable('vendor');

        $id = WopshopRequest::getInt("id");
        $vendor->load($id);
        $post = WopshopRequest::get("post");
        if (!isset($post['publish'])) {
            $post['publish'] = 0;
        }
        
        do_action_ref_array('onBeforeSaveVendor', array(&$post));
        $vendor->bind($post);
        WopshopFactory::loadLanguageFile();
        if (!$vendor->check()) {
            wopshopAddMessage($vendor->getError(), 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=vendors&task=edit&id=" . $vendor->id);
            return 0;
        }
        if (!$vendor->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=vendors&task=edit&id=" . $vendor->id);
            return 0;
        }
        do_action_ref_array('onAfterSaveVendor', array(&$vendor));
        $this->setRedirect("admin.php?page=wopshop-options&tab=vendors");
    }

}
