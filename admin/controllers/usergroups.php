<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class UserGroupsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }

    function display() {
        $context = "admin.usergroups.";
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', "usergroup_id");
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "asc");

        $usergroups = $this->getModel("usergroups");
        $rows = $usergroups->getAllUsergroups($filter_order, $filter_order_Dir);

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $usergroups->getBulkActions($actions);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';
        $view = $this->getView("usergroups");
        $view->setLayout("list");
        $view->assign("rows", $rows);
        $view->assign("bulk", $bulk);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeDisplayUserGroups', array(&$view));
        $view->display();
    }

    function edit() {
        $usergroup_id = WopshopRequest::getInt("row");
        $usergroup = WopshopFactory::getTable('usergroup');
        $usergroup->load($usergroup_id);
        $_lang = WopshopFactory::getAdminModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;        
        $edit = ($usergroup_id) ? 1 : 0;
        $view = $this->getView("usergroups");
        $view->setLayout("edit");
        $view->assign("usergroup", $usergroup);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);          
        $view->assign('edit', $edit);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeEditUserGroups', array(&$view));
        $view->display();
    }

    function save() {
        if (!empty($_POST) && check_admin_referer('usergroups_edit', 'name_of_nonce_field')) {
            $usergroup_id = WopshopRequest::getInt("usergroup_id");
            $usergroup = WopshopFactory::getTable('usergroup');
            $usergroups = $this->getModel("usergroups");
            $post = WopshopRequest::get("post");
            $_lang = $this->getModel("languages");
            $ml = WopshopFactory::getLang();
            $languages = $_lang->getAllLanguages(1);
            foreach ($languages as $lang) {
                $post['name_' . $lang->language] = trim($post['name_' . $lang->language]);
                $post['description_' . $lang->language] = WopshopRequest::getVar('description' . $lang->id, '', 'post', "string", 2);
            }
            $post['usergroup_name'] = $post[$ml->get("name")];
            $post['usergroup_description'] = $post[$ml->get("description")];            
            do_action_ref_array('onBeforeSaveUserGroup', array(&$post));
            if (!$usergroup->bind($post)) {
                wopshopAddMessage(WOPSHOP_ERROR_BIND);
                $this->setRedirect("admin.php?page=wopshop-options&tab=usergroups");
            }
            if ($usergroup->usergroup_is_default) {
                $default_usergroup_id = $usergroups->resetDefaultUsergroup();
            }
            if (!$usergroup->store()) {
                wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE);
                $usergroups->setDefaultUsergroup($default_usergroup_id);
                $this->setRedirect("admin.php?page=wopshop-options&tab=usergroups");
            }
            do_action_ref_array('onAfterSaveUserGroup', array(&$usergroup));
            $this->setRedirect("admin.php?page=wopshop-options&tab=usergroups");
        } else
            $this->setRedirect('admin.php?page=wopshop-options&tab=usergroups', WOPSHOP_ERROR_BIND);
    }

    function delete() {
        $cid = WopshopRequest::getVar("rows");
        global $wpdb;
        if(empty($cid)){
            wopshopAddMessage(WOPSHOP_EMPTY_POST_CHECBOX_SELECT_SOMTHING, 'error');
            $this->setRedirect('admin.php?page=wopshop-options&tab=usergroups');
            return 0;
        }
        do_action_ref_array('onBeforeRemoveUserGroup', array(&$cid));
        $text = "";
        foreach ($cid as $key => $value) {
            $query = "SELECT `usergroup_name` FROM `" . $wpdb->prefix . "wshop_usergroups` WHERE `usergroup_id` = '" . esc_sql($value) . "'";
            $usergroup_name = $wpdb->get_var($query);
            if ($wpdb->delete($wpdb->prefix . 'wshop_usergroups', array('usergroup_id' => esc_sql($value)))) {
                $text .= sprintf(WOPSHOP_USERGROUP_DELETED, $usergroup_name) . "<br>";
            }
        }
        do_action_ref_array('onAfterRemoveUserGroup', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=usergroups", $text);
    }
}
