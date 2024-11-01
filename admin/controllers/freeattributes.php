<?php

class FreeAttributesWshopAdminController extends WshopAdminController {
    protected $model = 'freeattribut';

    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=freeattributes";
    }

    function display() {
        $context = "admin.freeattributes.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_dir', 'filter_order_Dir', 'asc');

        $freeattributes = $this->getModel("freeattribut");
        $rows = $freeattributes->getAll($filter_order, $filter_order_Dir);

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $freeattributes->getBulkActions($actions);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';

        $view = $this->getView("freeattributes");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('bulk', $bulk);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayFreeAttributes', array(&$view));
        $view->display();
    }

    function edit() {
        $config = WopshopFactory::getConfig();
        $id = WopshopRequest::getInt("id");

        $attribut = WopshopFactory::getTable('freeattribut');
        $attribut->load($id);

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        $view = $this->getView("freeattributes");
        $view->setLayout("edit");
        $view->assign('attribut', $attribut);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditFreeAtribut', array(&$view, &$attribut));
        $view->display();
    }

    function save() {
        if (!empty($_POST) && check_admin_referer('freeattributes_edit', 'name_of_nonce_field')) {
            //global $wpdb;
            $id = WopshopRequest::getInt('id');

            $attribut = WopshopFactory::getTable('freeattribut');
            $post = WopshopRequest::get("post");
            if (!isset($post['required']) || !$post['required'])
                $post['required'] = 0;
            do_action_ref_array('onBeforeSaveFreeAtribut', array(&$post));
            if (!$id) {
                $attribut->ordering = null;
                $attribut->ordering = $attribut->getNextOrder();
            }
            if (!$attribut->bind($post)) {
                wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
                $this->setRedirect("admin.php?page=wopshop-options&tab=freeattributes");
                return 0;
            }
            if (!$attribut->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
                $this->setRedirect("admin.php?page=wopshop-options&tab=freeattributes");
                return 0;
            }
            do_action_ref_array('onAfterSaveFreeAtribut', array(&$attribut));
            $this->setRedirect("admin.php?page=wopshop-options&tab=freeattributes");
        }
    }

    function delete() {
        $cid = WopshopRequest::getVar("rows");
        if (empty($cid)) {
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=freeattributes');
            return 0;
        }
        global $wpdb;
        $text = '';
        do_action_ref_array('onBeforeRemoveFreeAtribut', array(&$cid));
        foreach ($cid as $key => $value) {
            $value = intval($value);
            $wpdb->delete($wpdb->prefix . "wshop_free_attr", array('id' => esc_sql($value)));
            $wpdb->delete($wpdb->prefix . "wshop_products_free_attr", array('attr_id' => esc_sql($value)));
        }
        do_action_ref_array('onAfterRemoveFreeAtribut', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=freeattributes");
    }

}
