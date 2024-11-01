<?php

class ProductFieldsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=productfields";
    }

    function display() {
        global $wpdb;
        $config = WopshopFactory::getConfig();

        $context = "admin.productfields.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', "F.ordering");
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "asc");
        $group = wopshopGetStateFromRequest($context . 'group', 'group', 0);
        $text_search = wopshopGetStateFromRequest($context . 'text_search', 's', '');

        $filter = array("group" => $group, "text_search" => $text_search);

        $_categories = $this->getModel("categories");
        $search = $_categories->search($text_search);
        $listCats = $_categories->getAllList(1);

        $_productfields = $this->getModel("productFields");
        $rows = $_productfields->getList(0, $filter_order, $filter_order_Dir, $filter);
        foreach ($rows as $k => $v) {
            if ($v->allcats) {
                $rows[$k]->printcat = WOPSHOP_ALL;
            } else {
                $catsnames = array();
                $_cats = json_decode($v->cats, 1);
                foreach ($_cats as $cat_id) {
                    $catsnames[] = $listCats[$cat_id];
                    $rows[$k]->printcat = implode(", ", $catsnames);
                }
            }
        }

        $_productfieldvalues = $this->getModel("productFieldValues");
        $vals = $_productfieldvalues->getAllList(2);

        foreach ($rows as $k => $v) {
            if (isset($vals[$v->id])) {
                if (is_array($vals[$v->id])) {
                    $rows[$k]->count_option = count($vals[$v->id]);
                } else {
                    $rows[$k]->count_option = 0;
                }
            } else {
                $rows[$k]->count_option = 0;
            }
        }
        $lists = array();
        $_productfieldgroups = $this->getModel("productFieldGroups");
        $groups = $_productfieldgroups->getList();
        $groups0 = array();
        $groups0[] = WopshopHtml::_('select.option', 0, "- - -", 'id', 'name');
        $lists['group'] = WopshopHtml::_('select.genericlist', array_merge($groups0, $groups), 'group', 'onchange="document.ExtraFieldsFilter.submit();"', 'id', 'name', $group);

        $types = array(WOPSHOP_LIST, WOPSHOP_TEXT);
        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $_categories->getBulkActions($actions);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';

        $view = $this->getView("productfields");
        $view->setLayout("list");
        $view->assign('lists', $lists);
        $view->assign('rows', $rows);
        $view->assign('vals', $vals);
        $view->assign('types', $types);
        $view->assign('bulk', $bulk);
        $view->assign('search', $search);
        $view->assign('text_search', $text_search);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeDisplayProductField', array(&$view));
        $view->display();
    }

    function edit() {
        $id = WopshopRequest::getInt("id");
        $productfield = WopshopFactory::getTable('productfield');
        $productfield->load($id);

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        $all = array();
        $all[] = WopshopHtml::_('select.option', 1, WOPSHOP_ALL, 'id', 'value');
        $all[] = WopshopHtml::_('select.option', 0, WOPSHOP_SELECTED, 'id', 'value');
        if (!isset($productfield->allcats))
            $productfield->allcats = 1;
        $lists['allcats'] = WopshopHtml::_('select.radiolist', $all, 'allcats', 'onclick="PFShowHideSelectCats()"', 'id', 'value', $productfield->allcats);

        $categories_selected = $productfield->getCategorys();

        $model_categories = $this->getModel('categories');
        $categories = $model_categories->wopshopBuildTreeCategory(0, 1, 0);

        $lists['categories'] = WopshopHtml::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox" size="10" multiple = "multiple"', 'category_id', 'name', $categories_selected);

        $type = array();
        $type[] = WopshopHtml::_('select.option', 0, WOPSHOP_LIST, 'id', 'value');
        $type[] = WopshopHtml::_('select.option', -1, WOPSHOP_MULTI_LIST, 'id', 'value');
        $type[] = WopshopHtml::_('select.option', 1, WOPSHOP_TEXT, 'id', 'value');
        if (!isset($productfield->type))
            $productfield->type = 0;
        if ($productfield->multilist)
            $productfield->type = -1;
        $lists['type'] = WopshopHtml::_('select.radiolist', $type, 'type', '', 'id', 'value', $productfield->type);

        $_productfieldgroups = $this->getModel("productFieldGroups");
        $groups = $_productfieldgroups->getList();
        $groups0 = array();
        $groups0[] = WopshopHtml::_('select.option', 0, "- - -", 'id', 'name');
        $lists['group'] = WopshopHtml::_('select.genericlist', array_merge($groups0, $groups), 'group', 'class="inputbox"', 'id', 'name', $productfield->group);

        $view = $this->getView("productfields", 'html');
        $view->setLayout("edit");
        $view->assign('row', $productfield);
        $view->assign('lists', $lists);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->etemplatevar = "";
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeEditProductFields', array(&$view));
        $view->display();
    }

    function save() {
        if (!empty($_POST) && check_admin_referer('productfields_edit', 'name_of_nonce_field')) {
            $config = WopshopFactory::getConfig();
            global $wpdb;

            $id = WopshopRequest::getInt("id");
            $productfield = WopshopFactory::getTable('productfield');
            $post = WopshopRequest::get("post");
            if ($post['type'] == -1) {
                $post['type'] = 0;
                $post['multilist'] = 1;
            } else {
                $post['multilist'] = 0;
            }
            do_action_ref_array('onBeforeSaveProductField', array(&$post));
            if (!$productfield->bind($post)) {
                wopshopAddMessage(_WOPSHOP_ERROR_BIND);
                $this->setRedirect("admin.php?page=wopshop-options&tab=productfields");
                return 0;
            }
            if(!isset($post['category_id'])){
                $post['category_id'] = array();
            }
            $categorys = $post['category_id'];
            if (!is_array($categorys))
                $categorys = array();

            $productfield->setCategorys($categorys);

            if (!$id) {
                $productfield->ordering = null;
                $productfield->ordering = $productfield->getNextOrder();
            }

            if (!$productfield->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
                $this->setRedirect("admin.php?page=wopshop-options&tab=productfields");
                return 0;
            }

            if (!$id) {
                $query = "ALTER TABLE `" . $wpdb->prefix . "wshop_products` ADD `extra_field_" . $productfield->id . "` " . $config->new_extra_field_type . " NOT NULL";
                $wpdb->get_results($query);
            }
            do_action_ref_array('onAfterSaveProductField', array(&$productfield));
            $this->setRedirect('admin.php?page=wopshop-options&tab=productfields');
        }
    }

    function delete() {
        $cid = WopshopRequest::getVar("rows");
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=productfields');
            return 0;
        }
        global $wpdb;
        $text = array();
        do_action_ref_array('onBeforeRemoveProductField', array(&$cid));
        foreach ($cid as $key => $value) {
            if ($wpdb->delete($wpdb->prefix . "wshop_products_extra_fields", array('id' => esc_sql($value)))) {
                $text[] = WOPSHOP_ITEM_DELETED;
            }
            $wpdb->delete($wpdb->prefix . "wshop_products_extra_field_values", array('field_id' => esc_sql($value)));
            $query = "ALTER TABLE `" . $wpdb->prefix . "wshop_products` DROP `extra_field_" . $value . "`";
            $wpdb->query($query);
        }
        do_action_ref_array('onAfterRemoveProductField', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=productfields", implode("</li><li>", $text));
    }

}
