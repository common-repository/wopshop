<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ClientsWshopAdminController extends WshopAdminController {

    function __construct() {
        parent::__construct();
    }

    function display() {
        $context = "list.admin.clients";
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'order', 'asc');
        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'orderby', 'u_name');
        $text_search = wopshopGetStateFromRequest($context . 'text_search', 's', '');
        $per_page = wopshopGetStateFromRequest($context . 'per_page', 'per_page', 20);
        $paged = wopshopGetStateFromRequest($context . 'paged', 'paged', 1);

        $start = ($paged - 1) * $per_page;

        $model = $this->getModel('products');
        $search = $model->search($text_search);

        $users = $this->getModel("users");

        $total = $users->getCountAllUsers($text_search);

        $actions = array(
            'delete' => WOPSHOP_DELETE
                //,
                //'publish' => WOPSHOP_ACTION_PUBLISH,
                //'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $model->getBulkActions($actions);

        $rows = $users->getAllUsers($start, $per_page, $text_search, $filter_order, $filter_order_Dir);

        //$pagination = $model->getPagination($count_products, $per_page);

        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';

        $view = $this->getView("users");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('pageNav', $pageNav);
        $view->assign('text_search', $text_search);
        $view->assign('orderby', $filter_order);
        $view->assign('order', $filter_order_Dir);
        $view->assign('search', $search);
        $view->assign('bulk', $bulk);
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeDisplayUsers', array(&$view));
        $view->display();
    }

    function edit() {
        //global $wpdb;
        $config = WopshopFactory::getConfig();

        $me = WopshopFactory::getUser();
        $user_id = WopshopRequest::getInt("user_id");
        $user = WopshopFactory::getTable('usershop');
        $user->load($user_id);

        //$user_site = new User($user_id);

        $_countries = $this->getModel("countries");
        $countries = $_countries->getAllCountries(0);
        $lists['country'] = WopshopHtml::_('select.genericlist', $countries, 'country', 'class = "inputbox" size = "1"', 'country_id', 'name', $user->country);
        $lists['d_country'] = WopshopHtml::_('select.genericlist', $countries, 'd_country', 'class = "inputbox endes" size = "1"', 'country_id', 'name', $user->d_country);
        $user->birthday = wopshopGetDisplayDate($user->birthday, $config->field_birthday_format);
        $user->d_birthday = wopshopGetDisplayDate($user->d_birthday, $config->field_birthday_format);
        $option_title = array();

        foreach ($config->user_field_title as $key => $value) {
            $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name');
        }
        $lists['select_titles'] = WopshopHtml::_('select.genericlist', $option_title, 'title', 'class = "inputbox"', 'title_id', 'title_name', $user->title);
        $lists['select_d_titles'] = WopshopHtml::_('select.genericlist', $option_title, 'd_title', 'class = "inputbox endes"', 'title_id', 'title_name', $user->d_title);

        $client_types = array();
        foreach ($config->user_field_client_type as $key => $value) {
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name');
        }
        $lists['select_client_types'] = WopshopHtml::_('select.genericlist', $client_types, 'client_type', 'class = "inputbox" ', 'id', 'name', $user->client_type);

        $_usergroups = $this->getModel("userGroups");
        $usergroups = $_usergroups->getAllUsergroups();
        $lists['usergroups'] = WopshopHtml::_('select.genericlist', $usergroups, 'usergroup_id', 'class = "inputbox" size = "1"', 'usergroup_id', 'usergroup_name', $user->usergroup_id);
        //$lists['block'] = WopshopHtml::_('select.booleanlist',  'block', 'class="inputbox" size="1"', $user_site->get('block') );
        //filterHTMLSafe($user, ENT_QUOTES);

        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['editaccount'];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('editaccount');

        $view = $this->getView("users", 'html');
        $view->setLayout("edit");
        $view->assign('config', $config);
        $view->assign('user', $user);
        $view->assign('me', $me);
        $view->assign('user_site', $user_site);
        $view->assign('lists', $lists);
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        do_action_ref_array('onBeforeEditUsers', array(&$view));
        $view->display();
    }

    function save() {
        check_admin_referer('client_edit');
            $config = WopshopFactory::getConfig();
            global $wpdb;
            $user_id = WopshopRequest::getInt('user_id');
            $user_identification = $user_id;
             $post = WopshopRequest::get("post");

            do_action_ref_array('onBeforeSaveUser', array(&$post));
            if ($user_id > 0) {
                if ($post['password'] != '' || $post['password2'] != '') {
                    if ($post['password'] != $post['password2']) {
                        $this->setRedirect("admin.php?page=wopshop-clients&task=edit&user_id=" . $user_id, 'Error verify pass', 'error');
                        return 0;
                    }
                }
            } else {
                if ($post['password'] == '') {
                    $this->setRedirect("admin.php?page=wopshop-clients&task=edit&user_id=" . $user_id, 'Error empty pass');
                    return 0;
                }
                if ($post['password'] != $post['password2']) {
                    $this->setRedirect("admin.php?page=wopshop-clients&task=edit&user_id=" . $user_id, 'Error verify pass');
                    return 0;
                }
                if (!sanitize_email($post['email'])) {
                    $this->setRedirect("admin.php?page=wopshop-clients&task=edit&user_id=" . $user_id, 'Error email');
                    return 0;
                }
            }

            $userdata = array(
                'ID' => $user_id
                , 'user_login' => $post['u_name']
                , 'user_email' => $post['email']
            );
            if ($post['password'] != '') {
                $userdata['user_pass'] = wp_hash_password($post['password']);
            }
            if ($user_id == 0) {
                $userdata['user_registered'] = date('Y-m-d H:i:s');
            }
            $ress = wp_insert_user($userdata);
            if ($user_id > 0) {
                //$ress = wp_update_user($userdata);
                $wpdb->update($wpdb->prefix.'users', array('user_login' => $post['u_name'] ), array('ID' => $user_id), array('%s'));
            }
            if (is_wp_error($ress)) {
            
                $this->setRedirect("admin.php?page=wopshop-clients&task=edit&user_id=" . $user_id, $ress->get_error_message());
                return;
            } else {
                $usr_id = $ress;
            }


            $post['user_id'] = $usr_id;
            $insert_post = $post;
            $table_name = $wpdb->prefix . "wshop_users";
            if ($user_identification > 0) {
                //$wpdb->show_errors();
                $usershop = WopshopFactory::getTable('usershop');
                $usershop->load($usr_id);
                $usershop->bind($insert_post);
                $usershop->store();
                //$wpdb->hide_errors();
                //$wpdb->print_error();
            } else {
                unset($insert_post['password']);
                unset($insert_post['password2']);
                unset($insert_post['submit']);
                unset($insert_post['name_of_nonce_field']);
                unset($insert_post['_wp_http_referer']);
                $wpdb->insert($table_name, $insert_post);
                $usershop = $insert_post;
            }
            do_action_ref_array('onAfterSaveUser', array(&$usershop_update));
            $this->setRedirect("admin.php?page=wopshop-clients");
    }

    function delete() {
        $cid = WopshopRequest::getVar('rows');

        if (empty($cid)) {
            wopshopAddMessage(WOPSHOP_CLIENT_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-clients');
            return 0;
        }

        do_action_ref_array('onBeforeRemoveUser', array(&$cid));
        if (isset($cid)) {
            foreach ($cid as $id) {
                wp_delete_user($id);

                $user_shop = WopshopFactory::getTable('usershop');
                $user_shop->delete((int) $id);
            }
        }
        do_action_ref_array('onAfterRemoveUser', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-clients");
    }

    function get_userinfo() {
        global $wpdb;
        $id = WopshopRequest::getInt('user_id');
        if (!$id) {
            print esc_html('{}');
            die();
        }
        $query = "SELECT * FROM `" . $wpdb->prefix . "wshop_users` WHERE `user_id` = " . esc_sql($id);
        $user = $wpdb->get_row($query, ARRAY_A);
        echo json_encode((array) $user);// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        die();
    }

}
