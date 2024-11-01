<?php

class ProductFieldGroupsWshopAdminController extends WshopAdminController {

    function __construct() {

        parent::__construct();
    }

    function display($cachable = false, $urlparams = false) {

        $config = WopshopFactory::getConfig();
        $model = $this->getModel("productfieldgroups");
        $rows = $model->getList();
        $actions = array('delete' => WOPSHOP_DELETE);
        $bulk = $model->getBulkActions($actions);
        $view = $this->getView("productfieldgroups");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('bulk', $bulk);
        do_action_ref_array('onBeforeDisplayProductsFieldGroups', array(&$view));
        $view->display();
    }

    function edit() {
        $id = WopshopRequest::getInt("id");
        $productfieldgroup = WopshopFactory::getTable('ProductFieldGroups');
        $productfieldgroup->load($id);
        $_lang = $this->getModel('languages');
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;
        $view = $this->getView('productfieldgroups');

        $view->setLayout('edit');
        $view->assign('row', $productfieldgroup);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = '';
        $view->tmp_html_end = '';
        $view->etemplatevar = '';

        do_action_ref_array('onBeforeEditProductFieldGroups', array(&$view));

        $view->display();
    }

    function save() {
        
        $id = WopshopRequest::getInt('id');
        $post = WopshopRequest::get('post');
        $row = WopshopFactory::getTable('ProductFieldGroups');
        do_action_ref_array('onBeforeSaveProductFieldGroup', array(&$post));
        $row->bind($post);

        if (!$post['id']) {

            $row->ordering = null;
            $row->ordering = $row->getNextOrder();
        }
        
        if (!$row->store()) {

            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            return 0;
        }

        do_action_ref_array('onAfterSaveProductFieldGroup', array(&$row));
        $this->setRedirect('admin.php?page=wopshop-options&tab=productfieldgroups');
    }

    function delete() {

        $id_list = WopshopRequest::getVar('rows');
        if(empty($id_list)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=productfieldgroups');
            return 0;
        }
        $obj = WopshopFactory::getTable('ProductFieldGroups');

        foreach ($id_list as $id) {

            $obj->delete($id);
        }

        do_action_ref_array('onAfterRemoveProductFieldGroup', array(&$id_list));
        $this->setRedirect('admin.php?page=wopshop-options&tab=productfieldgroups');

        return 1;
    }

    function orderup() {

        $fieldgroups = WopshopFactory::getAdminModel('productfieldgroups');
        $fieldgroups->reorder();
        $this->setRedirect('admin.php?page=wopshop-options&tab=productfieldgroups');
    }

    function orderdown() {

        $fieldgroups = WopshopFactory::getAdminModel('productfieldgroups');
        $fieldgroups->reorder();
        $this->setRedirect('admin.php?page=wopshop-options&tab=productfieldgroups');
    }

    function saveorder() {

        $cid = WopshopRequest::getVar('rows');
        $order = WopshopRequest::getVar('order', array(), 'post', 'array');

        foreach ($cid as $k => $id) {

            $table = WopshopFactory::getTable('ProductFieldGroups');
            $table->load($id);

            if ($table->ordering != $order[$k]) {

                $table->ordering = $order[$k];
                $table->store();
            }
        }

        $this->setRedirect("admin.php?page=wopshop-options&tab=productfieldgroups");
    }

}
