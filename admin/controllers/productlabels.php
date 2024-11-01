<?php

class ProductLabelsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }

    function display() {
        $config = WopshopFactory::getConfig();

        $context = "admin.productlabels.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', "name");
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "asc");

        $_productLabels = $this->getModel("productLabels");
        $rows = $_productLabels->getList($filter_order, $filter_order_Dir);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $_productLabels->getBulkActions($actions);

        $view = $this->getView("productlabels");
        $view->setLayout("list");
        $view->assign('bulk', $bulk);
        $view->assign('rows', $rows);
        $view->assign('config', $config);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeDisplayProductLabels', array(&$view));
        $view->display();
    }

    function edit() {
        $config = WopshopFactory::getConfig();
        $id = WopshopRequest::getInt("row");
        $productLabel = WopshopFactory::getTable('productlabel');
        $productLabel->load($id);
        $edit = ($id) ? (1) : (0);
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        $view = $this->getView("productlabels", 'html');
        $view->setLayout("edit");
        $view->assign('productLabel', $productLabel);
        $view->assign('config', $config);
        $view->assign('edit', $edit);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditProductLabels', array(&$view));
        $view->display();
    }

    function save() {
        if (!empty($_POST) && check_admin_referer('productlabel_edit', 'name_of_nonce_field')) {
            $config = WopshopFactory::getConfig();
            require_once($config->path . 'lib/uploadfile.class.php');

            $id = WopshopRequest::getInt("id");
            $productLabel = WopshopFactory::getTable('productlabel');
            $post = WopshopRequest::get("post");
            $lang = $config->cur_lang; //get_bloginfo('language');
            $post['name'] = $post["name_" . $lang];
            do_action_ref_array('onBeforeSaveProductLabel', array(&$post));
            $upload = new WopshopUploadFile($_FILES['productlabel_image']);
            $upload->setAllowFile(array('jpeg', 'jpg', 'gif', 'png'));
            $upload->setDir($config->image_labels_path);
            $upload->setFileNameMd5(0);
            $upload->setFilterName(1);
            if ($upload->upload()) {
                if (isset($post['old_image'])) {
                    @unlink($config->image_labels_path . "/" . $post['old_image']);
                }
                $post['image'] = $upload->getName();
                @chmod($config->image_labels_path . "/" . $post['image'], 0777);
            } else {
                if ($upload->getError() != 4) {
                    wopshopAddMessage(WOPSHOP_ERROR_UPLOADING_IMAGE);
                    //wopshopSaveToLog("error.log", "Label - Error upload image. code: ".$upload->getError());
                }
            }
            if (!$productLabel->bind($post)) {
                wopshopAddMessage(WOPSHOP_ERROR_BIND);
                $this->setRedirect("admin.php?page=wopshop-options&tab=productlabels");
                return 0;
            }
            if (!$productLabel->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
                $this->setRedirect("admin.php?page=wopshop-options&tab=productlabels");
                return 0;
            }
            do_action_ref_array('onAfterSaveProductLabel', array(&$productLabel));
            $this->setRedirect("admin.php?page=wopshop-options&tab=productlabels");
        }
        //$this->setRedirect('admin.php?page=wopshop-options&tab=productlabels');
    }

    function delete() {
        $config = WopshopFactory::getConfig();
        $text = array();
        $productLabel = WopshopFactory::getTable('productlabel');
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=productlabels');
            return 0;
        }
        do_action_ref_array('onBeforeRemoveProductLabel', array(&$cid));
        foreach ($cid as $key => $value) {
            $productLabel->load($value);
            @unlink($config->image_labels_path . "/" . $productLabel->image);
            $productLabel->delete();
            $text[] = WOPSHOP_ITEM_DELETED . "<br>";
        }
        do_action_ref_array('onAfterRemoveProductLabel', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=productlabels", implode("</p><p>", $text));


        //$this->setRedirect('admin.php?page=wopshop-options&tab=productlabels');
    }

    function deleteFoto() {
        $config = WopshopFactory::getConfig();
        $id = WopshopRequest::getInt("id");
        $productLabel = WopshopFactory::getTable('productlabel');
        $productLabel->load($id);
        @unlink($config->image_labels_path . "/" . $productLabel->image);
        $productLabel->image = "";
        $productLabel->store();
    }

}
