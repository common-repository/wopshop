<?php

class CountriesWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=countries";
    }

    function display() {
        $context = "admin.countries.";
        $publish = wopshopGetStateFromRequest($context . 'publish', '');
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc');

        $paged = wopshopGetStateFromRequest($context . 'paged', 'paged', 1);
        $limit = wopshopGetStateFromRequest($context . 'per_page', 'per_page', 20);
        $model = $this->getModel('countries');
        $total = $model->getCountAllCountries();

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );

        $bulk = $model->getBulkActions($actions);
        if ($publish == 0) {
            $total = $model->getCountAllCountries();
        } else {
            $total = $model->getCountPublishCountries($publish % 2);
        }

        if (($paged - 1) > ($total / $limit))
            $paged = 1;
        $limitstart = ($paged - 1) * $limit;
        $pagination = $model->getPagination($total, $limit);
        //$search = $model->search($s);
        $rows = $model->getAllCountries($publish, $limitstart, $limit, 0, $filter_order, $filter_order_Dir);

        $f_option = array();
        $f_option[] = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 1, WOPSHOP_PUBLISH, 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 2, WOPSHOP_UNPUBLISH, 'id', 'name');

        $filter = WopshopHtml::_('select.genericlist', $f_option, 'publish', 'onchange="document.adminForm.submit();"', 'id', 'name', $publish);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';
        $view = $this->getView("countries");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('pagination', $pagination);
        $view->assign('filter', $filter);
        $view->assign('bulk', $bulk);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeDisplayCountries', array(&$view));
        $view->display();
    }

    function edit() {
        $country_id = WopshopRequest::getInt("row");
        $countries = $this->getModel("countries");
        $country = WopshopFactory::getTable('country');
        $country->load($country_id);

        $first[] = WopshopHtml::_('select.option', '0', WOPSHOP_ORDERING_FIRST, 'ordering', 'name');
        $rows = array_merge($first, $countries->getAllCountries(0));

        $lists['order_countries'] = WopshopHtml::_('select.genericlist', $rows, 'ordering', 'class="inputbox" size="1"', 'ordering', 'name', $country->ordering);

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        $edit = ($country_id) ? ($edit = 1) : ($edit = 0);

        //FilterOutput::objectHTMLSafe( $country, ENT_QUOTES);

        $view = $this->getView("countries");
        $view->setLayout("edit");
        $view->assign('country', $country);
        $view->assign('lists', $lists);
        $view->assign('edit', $edit);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        do_action_ref_array('onBeforeEditCountries', array(&$view));
        $view->display();
    }

    function save() {
        check_admin_referer('coutry_edit', 'name_of_nonce_field');
        $country_id = WopshopRequest::getInt("country_id");
        $post = WopshopRequest::get('post');
        do_action_ref_array('onBeforeSaveCountry', array(&$post));
        $country = WopshopFactory::getTable('country');

        if (!$country->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=countries");
            return 0;
        }
        if (!$country->country_publish) {
            $country->country_publish = 0;
        }
        $this->_reorderCountry($country);
        if (!$country->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=countries");
            return 0;
        }
        do_action_ref_array('onAfterSaveCountry', array(&$country));
        $this->setRedirect('admin.php?page=wopshop-options&tab=countries', WOPSHOP_MESSAGE_SAVEOK);
    }

    function _reorderCountry(&$country) {
        global $wpdb;
        $query = "UPDATE `" . $wpdb->prefix . "wshop_countries` SET `ordering` = ordering + 1 WHERE `ordering` > '" . $country->ordering . "'";
        $wpdb->query($query);
    }

    function delete() {
        global $wpdb;
        $query = '';
        $text = '';
        $cid = WopshopRequest::getVar("rows");
        do_action_ref_array('onBeforeRemoveCountry', array(&$cid));
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=countries');
            return 0;
        }
        foreach ($cid as $key => $value) {
            if ($wpdb->delete($wpdb->prefix . 'wshop_countries', array('country_id' => $value)))
                $text .= WOPSHOP_COUNTRY_DELETED . "<br>";
            else
                $text .= WOPSHOP_COUNTRY_ERROR_DELETED . "<br>";
        }
        do_action_ref_array('onAfterRemoveCountry', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=countries", $text);
    }

}
