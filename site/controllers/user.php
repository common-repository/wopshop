<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class WopshopUserController extends WshopController{

    public function __construct(){
        parent::__construct();
        
        do_action_ref_array('onConstructWshopControllerUser', array(&$this));
    }
    
    public function display(){
        $this->myaccount();
    }
    
    public function login(){
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        
        $checkout = WopshopFactory::getModel('checkout');
        $checkout_navigator = $checkout->showCheckoutNavigation('1');
        
        $user = wp_get_current_user();
        if ($user->ID){            
            $view = $this->getView("user"); 
            $view->setLayout("logout");
            $view->assign('checkout_navigator', $checkout_navigator);            
            $view->display();
            return 0;
        }
   
        if (WopshopRequest::getVar('return')){
            $return = WopshopRequest::getVar('return');
        } else {
            $return = $session->get('return');
        }

        $show_pay_without_reg = $session->get("show_pay_without_reg");
        
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("login");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_LOGIN;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);        
        $country = WopshopFactory::getTable('country');
        $list_country = $country->getAllCountries();
        $option_country[] = WopshopHtml::_('select.option',  '0', WOPSHOP_REG_SELECT, 'country_id', 'name' );    
        $select_countries = WopshopHtml::_('select.genericlist', array_merge($option_country, $list_country),'country','id = "country" class = "inputbox" size = "1"','country_id','name' );
        foreach ($config->user_field_title as $key => $value) {        
            $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name' );    
        }
        $select_titles = WopshopHtml::_('select.genericlist', $option_title,'title','class = "inputbox"','title_id','title_name' );
        
        $client_types = array();
        foreach ($config->user_field_client_type as $key => $value) {        
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name' );
        }
        $select_client_types = WopshopHtml::_('select.genericlist', $client_types,'client_type','class = "inputbox" onchange="showHideFieldFirm(this.value)"','id','name');
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['register'];
        
        do_action_ref_array('onBeforeDisplayLogin', array() );
        if ($config->show_registerform_in_logintemplate){
            $adv_user = WopshopFactory::getRegistrationDefaultData();
            do_action_ref_array('onBeforeDisplayRegister', array());
        }
        if ($config->show_registerform_in_logintemplate && $config_fields['birthday']['display']){
            WopshopFactory::loadDatepicker();
        }

        $view = $this->getView("user");
        $view->setLayout("login");
        $view->assign('href_register', esc_url(wopshopSEFLink('controller=user&task=register',1,0, $config->use_ssl)));
        $view->assign('href_lost_pass', esc_url(wopshopSEFLink('controller=user&task=reset',0,0, $config->use_ssl)));
        $view->assign('return', $return);
        $view->assign('config', $config);
        $view->assign('show_pay_without_reg', $show_pay_without_reg);
        $view->assign('select_client_types', $select_client_types);
        $view->assign('select_titles', $select_titles);
        $view->assign('select_countries', $select_countries);
        $view->assign('config_fields', $config_fields);
        //$view->assign('live_path', WopshopUri::base());
        $view->assign('urlcheckdata', esc_url(wopshopSEFLink("controller=user&task=check_user_exist_ajax&ajax=1", 1, 1, $config->use_ssl)));
        $view->assign('checkout_navigator', $checkout_navigator);
        if (isset($adv_user)) {
            $view->assign('user', $adv_user);
        }
        $view->tmpl_login_html_1 = "";
        $view->tmpl_login_html_2 = "";
        $view->tmpl_login_html_3 = "";
        $view->tmpl_login_html_4 = "";
        $view->tmpl_login_html_5 = "";
        $view->tmpl_login_html_6 = "";
        $view->_tmpl_register_html_1 = "";
        $view->_tmpl_register_html_2 = "";
        $view->_tmpl_register_html_3 = "";
        $view->_tmpl_register_html_4 = "";
        $view->_tmpl_register_html_5 = "";
        $view->_tmpl_register_html_6 = "";
        do_action_ref_array('onBeforeDisplayLoginView', array(&$view));
        if ($config->show_registerform_in_logintemplate){
            do_action_ref_array('onBeforeDisplayRegisterView', array(&$view));
        }
        $view->display();
    }
    
    public function loginsave(){
        $config = WopshopFactory::getConfig(); 
        $mainframe = WopshopFactory::getApplication();

        do_action_ref_array('onBeforeLoginSave', array());
        
        if ($return = WopshopRequest::getVar('return', '', 'method', 'base64')) {
            $return = base64_decode($return);
            if (!WopshopUri::isInternal($return)) {
                $return = '';
            }
        }
        
        $credentials = array();
        $credentials['username'] = WopshopRequest::getVar('username');
        $credentials['password'] = WopshopRequest::getString('passwd');
        $credentials['remember'] = WopshopRequest::getBool('remember', false);
        
        $secure_cookie = false;
        do_action_ref_array('onBeforeLogin', array(&$credentials, &$secure_cookie, &$return));
        
        $error = $mainframe->login($credentials, $secure_cookie);
        
        wopshopSetNextUpdatePrices();        

        if (is_wp_error($error)){
            do_action_ref_array('onAfterLoginEror', array(&$credentials, &$secure_cookie, &$return));
            wopshopAddMessage($error->get_error_message(), 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=user&task=login&return='.base64_encode($return), 0, 1, $config->use_ssl))) ;             
        } else {
            if (!$return) {
                $return = WopshopUri::base();
            } 
            do_action_ref_array('onAfterLogin', array(&$credentials, &$secure_cookie, &$return));
            $this->setRedirect($return);
        }
    }
    
    public function check_user_exist_ajax(){
        $mes = array();
        $username = WopshopRequest::getVar("username");
        $email = WopshopRequest::getVar("email");
        do_action_ref_array('onBeforeUserCheck_user_exist_ajax', array(&$mes, &$username, &$email));
        if ($username){
            if ( username_exists( $username ) ){
                $mes[] = sprintf(WOPSHOP_USER_EXIST, $username);
            }
        }
        if ($email){
            if ( email_exists( $email ) ) {
                $mes[] = sprintf(WOPSHOP_USER_EXIST_EMAIL, $email);
            }
        }
        do_action_ref_array('onAfterUserCheck_user_exist_ajax', array(&$mes, &$username, &$email));
        if (count($mes)==0){
            print "1";
        }else{
            print esc_html(implode("\n",$mes));
        }
        die();
    }
    
    public function register(){
        $config = WopshopFactory::getConfig();
        $format = str_replace(array("%d","%m","%Y"), array('dd','mm','yy'), $config->field_birthday_format);
        
        $adv_user = WopshopFactory::getRegistrationDefaultData();
//        $session = WopshopFactory::getSession();
//        if (WopshopRequest::getInt('lrd')){
//            $adv_user = (object)$session->get('registrationdata');
//        } else {
//            $adv_user = new stdClass();
//        }

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("register");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_REGISTRATION;
        }    
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);
        
        if (!get_option('users_can_register')) {
            wopshopAddMessage('<strong>Access Forbidden</strong>', 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=login"))) ; 
        }      
        
        if (!$adv_user->country) {
            $adv_user->country = $config->default_country;
        }
        $country = WopshopFactory::getTable('country');
        $list_country = $country->getAllCountries();
        $option_country[] = WopshopHtml::_('select.option',  '0', WOPSHOP_REG_SELECT, 'country_id', 'name' );    
        $select_countries = WopshopHtml::_('select.genericlist', array_merge($option_country, $list_country),'country','id = "country" class = "inputbox" size = "1"','country_id','name', $adv_user->country);
        foreach($config->user_field_title as $key => $value){
            $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name' );
        }
        $select_titles = WopshopHtml::_('select.genericlist', $option_title,'title','class = "inputbox"','title_id','title_name', $adv_user->title);
        
        $client_types = array();
        foreach ($config->user_field_client_type as $key => $value) {        
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name' );
        }
        $select_client_types = WopshopHtml::_('select.genericlist', $client_types,'client_type','class = "inputbox" onchange="showHideFieldFirm(this.value)"','id','name', $adv_user->client_type);
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['register'];

        do_action_ref_array('onBeforeDisplayRegister', array(&$adv_user));
               
        $checkout = WopshopFactory::getModel('checkout');
        $checkout_navigator = $checkout->showCheckoutNavigation('1');

        if ($config_fields['birthday']['display']){
            WopshopFactory::loadDatepicker();
        }

        $view = $this->getView("user");
        $view->setLayout("register"); 
        $view->assign('config', $config);
        $view->assign('format', $format);
        $view->assign('select_client_types', $select_client_types);
        $view->assign('select_titles', $select_titles);
        $view->assign('select_countries', $select_countries);
        $view->assign('config_fields', $config_fields);
        $view->assign('user', $adv_user);
        $view->assign('live_path', WopshopUri::base());        
        $view->assign('urlcheckdata', esc_url(wopshopSEFLink("controller=user&task=check_user_exist_ajax&ajax=1",1,1)));        
        $view->assign('checkout_navigator', $checkout_navigator);
        $view->_tmpl_register_html_1 = "";
        $view->_tmpl_register_html_2 = "";
        $view->_tmpl_register_html_3 = "";
        $view->_tmpl_register_html_4 = "";
        $view->_tmpl_register_html_5 = "";
        $view->_tmpl_register_html_6 = "";
        do_action_ref_array('onBeforeDisplayRegisterView', array(&$view));
        $view->display();
    }
    
    public function registersave(){
        $config = WopshopFactory::getConfig();
        $post = WopshopRequest::get('post');

        if (!get_option('users_can_register')) {
            wopshopAddMessage('<strong>Access Forbidden</strong>', 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=login"))) ; 
        } 
        
        $usergroup = WopshopFactory::getTable('usergroup');
        $default_usergroup = $usergroup->getDefaultUsergroup();
        $post['username'] = $post['u_name'];
        $post['password2'] = $post['password_2'];
        if ($post['f_name']=="") $post['f_name'] = $post['email'];
        $post['name'] = $post['f_name'].' '.$post['l_name'];
        if ($post['birthday']) $post['birthday'] = wopshopGetJsDateDB($post['birthday'], $config->field_birthday_format);

        $post['lang'] = $config->cur_lang;
		
        do_action_ref_array('onBeforeRegister', array(&$post, &$default_usergroup));
        
        $row = WopshopFactory::getTable('usershop');
        $row->bind($post);
        $row->usergroup_id = $default_usergroup;
        $row->password = $post['password'];
        $row->password2 = $post['password2'];
        
        if (!$row->check("register")){
            $session = WopshopFactory::getSession();
            $registrationdata = WopshopRequest::get('post');
            $session->set('registrationdata', $registrationdata);
            wopshopAddMessage($row->getError(), 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=register&lrd=1", 1, 1, $config->use_ssl)));
            return 0;
        }
        if ($post["u_name"]==""){
            $post["u_name"] = $post['email'];
            $row->u_name = $post["u_name"];
        }
        if ($post["password"]==""){
            $post["password"] = substr(md5('up'.time()), 0, 8);
        }
        $userdata = array(
            'user_login'    =>   $post['u_name'],
            'user_email'    =>   $post['email'],
            'user_pass'     =>   $post['password'],
            'first_name'    =>   $post['f_name'],
            'last_name'     =>   $post['l_name'],
            'nickname'      =>   $post['u_name'],
        );
        $user = wp_insert_user($userdata);  

        if ( is_wp_error( $user ) ) {
            wopshopAddMessage($user->get_error_message(), 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=register",1,1,$config->use_ssl)));
        }
        $row->user_id = $user;		
        $row->number = $row->getNewUserNumber();
        unset($row->password);
        unset($row->password2);
        if(!$row->insertObject('user_id')){
            wopshopAddMessage("Error insert in table ", 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=register",1,1,$config->use_ssl)));  
            return 0;
        }
       
        do_action_ref_array('onAfterRegister', array(&$user, &$row, &$post));
        wopshopAddMessage(WOP_SHOP_USERS_REGISTRATION_SAVE_SUCCESS);
        $return = esc_url(wopshopSEFLink("controller=user&task=login",1,1,$config->use_ssl));

        $this->setRedirect($return);
    }
    
    
    public function editaccount(){
        wopshopCheckUserLogin();

        $adv_user = WopshopFactory::getUserShop();
        $config = WopshopFactory::getConfig();
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("editaccount");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_EDIT_DATA;
        }    
        
        if (!$adv_user->country) $adv_user->country = $config->default_country;
        if (!$adv_user->d_country) $adv_user->d_country = $config->default_country;
        $adv_user->birthday = wopshopGetDisplayDate($adv_user->birthday, $config->field_birthday_format);
        $adv_user->d_birthday = wopshopGetDisplayDate($adv_user->d_birthday, $config->field_birthday_format);
        
        $country = WopshopFactory::getTable('country');
        $list_country = $country->getAllCountries();
        $option_country[] = WopshopHtml::_('select.option', 0, WOPSHOP_REG_SELECT, 'country_id', 'name' );
        $option_countryes = array_merge($option_country, $list_country);
        $select_countries = WopshopHtml::_('select.genericlist', $option_countryes,'country','class = "inputbox" size = "1"','country_id', 'name',$adv_user->country );
        $select_d_countries = WopshopHtml::_('select.genericlist', $option_countryes,'d_country','class = "inputbox" size = "1"','country_id', 'name',$adv_user->d_country );

        foreach($config->user_field_title as $key => $value){
            $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name' );
        }    
        $select_titles = WopshopHtml::_('select.genericlist', $option_title,'title','class = "inputbox"','title_id','title_name',$adv_user->title );
        $select_d_titles = WopshopHtml::_('select.genericlist', $option_title,'d_title','class = "inputbox"','title_id','title_name',$adv_user->d_title );
        
        $client_types = array();
        foreach ($config->user_field_client_type as $key => $value) {        
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name' );
        }
        $select_client_types = WopshopHtml::_('select.genericlist', $client_types,'client_type','class = "inputbox" onchange="showHideFieldFirm(this.value)"','id','name', $adv_user->client_type);
                
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['editaccount'];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('editaccount');

        do_action_ref_array('onBeforeDisplayEditUser', array(&$adv_user));
        
        if ($config_fields['birthday']['display'] || $config_fields['d_birthday']['display']){
            WopshopFactory::loadDatepicker();
        }

        $view = $this->getView("user");
        $view->setLayout("editaccount");        
		$view->assign('config',$config);
        $view->assign('select_countries',$select_countries);
        $view->assign('select_d_countries',$select_d_countries);
        $view->assign('select_titles',$select_titles);
        $view->assign('select_d_titles',$select_d_titles);
        $view->assign('select_client_types', $select_client_types);
        $view->assign('live_path', WopshopUri::base());
        $view->assign('user', $adv_user);
        $view->assign('delivery_adress', $adv_user->delivery_adress);
        $view->assign('action', esc_url(wopshopSEFLink('controller=user&task=accountsave',0,0,$config->use_ssl)));
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->_tmpl_editaccount_html_1 = "";
        $view->_tmpl_editaccount_html_2 = "";
        $view->_tmpl_editaccount_html_3 = "";
        $view->_tmpl_editaccount_html_4 = "";
        $view->_tmpl_editaccount_html_4_1 = "";
        $view->tmp_fields = "";
        $view->tmp_d_fields = "";
        $view->_tmpl_editaccount_html_5 = "";
        $view->_tmpl_editaccount_html_6 = "";
        $view->_tmpl_editaccount_html_7 = "";
        $view->_tmpl_editaccount_html_8 = "";
        do_action_ref_array('onBeforeDisplayEditAccountView', array(&$view));
        $view->display();
    }
    
    public function accountsave(){
		global $wpdb;
        wopshopCheckUserLogin();
        $user = WopshopFactory::getUser();
        $app = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        
        $user_shop = WopshopFactory::getTable('usershop');        
        $post = WopshopRequest::get('post');
        if (!isset($post['password'])) $post['password'] = '';
        if (!isset($post['password_2'])) $post['password_2'] = '';
        if (isset($post['birthday'])) $post['birthday'] = wopshopGetJsDateDB($post['birthday'], $config->field_birthday_format);
        if (isset($post['d_birthday'])) $post['d_birthday'] = wopshopGetJsDateDB($post['d_birthday'], $config->field_birthday_format);
        $post['lang'] = $config->getLang();
        
        do_action_ref_array('onBeforeAccountSave', array(&$post));
        
        unset($post['user_id']);
        unset($post['usergroup_id']);
        $user_shop->load($user->user_id);
        $user_shop->bind($post);
        $user_shop->password = $post['password'];
        $user_shop->password2 = $post['password_2'];
		$password = $user_shop->password;

        if (!$user_shop->check("editaccount")) {
			wopshopAddMessage(WOPSHOP_REGWARN_ERROR_DATABASE, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=editaccount", 0, 1, $config->use_ssl)));
            return 0;
        }
			
        unset($user_shop->password);
        unset($user_shop->password2);        

        if (!$user_shop->store()){
            wopshopAddMessage(WOPSHOP_REGWARN_ERROR_DATABASE, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=editaccount", 0, 1, $config->use_ssl)));
            return 0;
        }
		
		if($password !== ''){
			$user_pass = wp_hash_password($password);
			wp_update_user(array('ID' => $user->user_id, 'user_pass' => $user_pass));
			//wp_set_password($password, $user->user_id);
		}		

        
        $data = array();
        $data['email'] = $user->email;
        $data['name'] = $user->name;
        $app->setUserState('wshop_users.edit.profile.data', $data);
        
        wopshopSetNextUpdatePrices();
        do_action_ref_array('onAfterAccountSave', array());
		
		
		
		wopshopAddMessage(WOPSHOP_ACCOUNT_UPDATE);
                
        $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=myaccount",0,1,$config->use_ssl)));
    }
    
    function orders(){
        $config = WopshopFactory::getConfig();
        
        wopshopCheckUserLogin();
        $user = WopshopFactory::getUser();

        $order = WopshopFactory::getTable('order');

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("myorders");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_MY_ORDERS;
        }

        $orders = $order->getOrdersForUser($user->user_id);

        $total = 0;
        foreach($orders as $key=>$value){
            $orders[$key]->order_href = esc_url(wopshopSEFLink('controller=user&task=order&order_id='.$value->order_id));
            $total += $value->order_total / $value->currency_exchange;
        }

        do_action_ref_array( 'onBeforeDisplayListOrder', array(&$orders) );

        $view = $this->getView("order");
        $view->setLayout("listorder");
        $view->assign('orders', $orders);
        $view->assign('image_path', $config->live_path."images");
        $view->assign('total', $total);
        $view->_tmp_html_before_user_order_list = "";
        $view->_tmp_html_after_user_order_list = "";
        do_action_ref_array('onBeforeDisplayOrdersView', array(&$view));
        $view->display();
    }
    
    function order(){
        $config = WopshopFactory::getConfig();
        wopshopCheckUserLogin();
        $user = WopshopFactory::getUser();
        
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("myorder-detail");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_MY_ORDERS;
        }

        
        $order_id = WopshopRequest::getInt('order_id');
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        do_action_ref_array('onAfterLoadOrder', array(&$order, &$user));

        
        if ($user->user_id!=$order->user_id){
            wopshopAddMessage("Error order number. You are not the owner of this order", 'error');
            return 0;
        }
        
        $order->items = $order->getAllItems();
        $order->weight = $order->getWeightItems();
        $order->status_name = $order->getStatus();
        $order->history = $order->getHistory();
        if ($config->client_allow_cancel_order && $order->order_status!=$config->payment_status_for_cancel_client && !in_array($order->order_status, $config->payment_status_disable_cancel_client) ){
            $allow_cancel = 1;
        }else{
            $allow_cancel = 0;
        }
		
        if ($order->weight==0 && $config->hide_weight_in_cart_weight0){
            $config->show_weight_order = 0;
        }
        
	$order->birthday = wopshopGetDisplayDate($order->birthday, $config->field_birthday_format);
        $order->d_birthday = wopshopGetDisplayDate($order->d_birthday, $config->field_birthday_format);
        
        $shipping_method =WopshopFactory::getTable('shippingmethod');
        $shipping_method->load($order->shipping_method_id);
        
        $name = 'name_'.$config->cur_lang;
        $description = 'description_'.$config->cur_lang;
        $order->shipping_info = $shipping_method->$name;
        
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;
        if ($pm_method->show_descr_in_email) $order->payment_description = $pm_method->$description;  else $order->payment_description = "";
        
        $country = WopshopFactory::getTable('country');
        $country->load($order->country);
        $field_country_name = $name;
        $order->country = $country->$field_country_name;
        
        $d_country = WopshopFactory::getTable('country');
        $d_country->load($order->d_country);
        $field_country_name = $name;
        $order->d_country = $d_country->$field_country_name;
        
        $config->user_field_client_type[0]="";
        $order->client_type_name = $config->user_field_client_type[$order->client_type];
        
        $order->delivery_time_name = '';
        $order->delivery_date_f = '';
        if ($config->show_delivery_time_checkout){
            $deliverytimes = WopshopFactory::getAllDeliveryTime();
            $order->delivery_time_name = $order->delivery_times_id ? $deliverytimes[$order->delivery_times_id] : '';
            if ($order->delivery_time_name==""){
                $order->delivery_time_name = $order->delivery_time;
            }
        }
        if ($config->show_delivery_date && !wopshop_datenull($order->delivery_date)){
            $order->delivery_date_f = wopshop_formatdate($order->delivery_date);
        }
        
        $order->order_tax_list = $order->getTaxExt();
        $show_percent_tax = 0;
        if (count($order->order_tax_list)>1 || $config->show_tax_in_product) $show_percent_tax = 1;
        if ($config->hide_tax) $show_percent_tax = 0;
        $hide_subtotal = 0;
        if (($config->hide_tax || count($order->order_tax_list)==0) && $order->order_discount==0 && $order->order_payment==0 && $config->without_shipping) $hide_subtotal = 1;
        
        $text_total = WOPSHOP_ENDTOTAL;
        if (($config->show_tax_in_product || $config->show_tax_product_in_cart) && (count($order->order_tax_list)>0)){
            $text_total = WOPSHOP_ENDTOTAL_INKL_TAX;
        }
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('address');

        if ($config->order_display_new_digital_products){
            $product = WopshopFactory::getTable('product');
            foreach($order->items as $k=>$v){
                $product->product_id = $v->product_id;
                $product->setAttributeActive(json_decode($v->attributes, 1));
                $files = $product->getSaleFiles();
                $order->items[$k]->files = json_encode($files);
            }
        }
        do_action_ref_array('onBeforeDisplayOrder', array(&$order));

        $view = $this->getView("order");
        $view->setLayout("order");
        $view->assign('order', $order);
        $view->assign('config', $config);
        $view->assign('text_total', $text_total);
        $view->assign('show_percent_tax', $show_percent_tax);
        $view->assign('hide_subtotal', $hide_subtotal);
        $view->assign('image_path', $config->live_path . "images");
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('allow_cancel', $allow_cancel);
        $view->_tmp_html_start = "";
        $view->_tmp_html_after_customer_info = "";
        $view->_tmpl_html_order_items_end = "";
        $view->_tmp_ext_subtotal = "";
        $view->_tmp_html_after_subtotal = "";
        $view->_tmp_ext_discount_text = "";
        $view->_tmp_ext_discount = "";
        $view->_tmp_ext_shipping = "";
        $view->_tmp_ext_shipping_package = "";
        $view->_tmp_ext_payment = "";
        $view->_tmp_ext_total = "";
        $view->_tmp_ext_tax = array();
        foreach($order->order_tax_list as $percent=>$value) {
            $view->_tmp_ext_tax[$percent] = "";
        }
        $view->_tmp_html_after_total = "";
        $view->_tmp_html_shipping_block_info_end = "";
        $view->_tmp_html_after_comment = "";
        $view->_tmp_html_after_history = "";
        $view->_tmp_html_end = "";
        $view->tmp_fields = "";
        $view->tmp_d_fields  = "";
        do_action_ref_array('onBeforeDisplayOrderView', array(&$view));
        $view->display();
    }
    
    function cancelorder(){
        $config = WopshopFactory::getConfig();
        wopshopCheckUserLogin();
        $user = WopshopFactory::getUser();
        
        if (!$config->client_allow_cancel_order) return 0;
        
        $order_id = WopshopRequest::getInt('order_id');
        
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        
        
        if ($user->user_id!=$order->user_id){
            wopshopAddMessage("Error order number", 'error');
        }
        $status = $config->payment_status_for_cancel_client;
        
        if ($order->order_status==$status || in_array($order->order_status, $config->payment_status_disable_cancel_client)){
            $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=order&order_id=".$order_id, 0, 1, $config->use_ssl)));
            return 0;
        }
        
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->changeStatusOrder($order_id, $status, 1);

        do_action_ref_array('onAfterUserCancelOrder', array(&$order_id));
        
        $this->setRedirect(esc_url(wopshopSEFLink("controller=user&task=order&order_id=".$order_id, 0, 1, $config->use_ssl), WOPSHOP_ORDER_CANCELED));
    }

    public function myaccount(){
        wopshopCheckUserLogin();

        $adv_user = WopshopFactory::getUserShop();
        $config = WopshopFactory::getConfig();
        
        $country = WopshopFactory::getTable('country');
        $country->load($adv_user->country);
        $field_name = 'name_'.$config->cur_lang;
        $adv_user->country = $country->$field_name;
        
        $group = WopshopFactory::getTable('usergroup');
        $group->load($adv_user->usergroup_id);
        if ($group->$field_name!=''){
            $adv_user->groupname = $group->$field_name;
        }else{
            $adv_user->groupname = $group->usergroup_name;
        }
        $adv_user->discountpercent = floatval($group->usergroup_discount);

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("myaccount");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_MY_ACCOUNT;
        }
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['editaccount'];

        do_action_ref_array( 'onBeforeDisplayMyAccount', array(&$adv_user, &$config_fields));

        $view = $this->getView("user");
        $view->setLayout("myaccount");
        $view->assign('config', $config);
        $view->assign('user', $adv_user);
        $view->assign('config_fields', $config_fields);
        $view->assign('href_user_group_info', esc_url(wopshopSEFLink('controller=user&task=groupsinfo')));
        $view->assign('href_edit_data', esc_url(wopshopSEFLink('controller=user&task=editaccount',0,0,$config->use_ssl)));
        $view->assign('href_show_orders', esc_url(wopshopSEFLink('controller=user&task=orders',0,0,$config->use_ssl)));
        $view->assign('href_logout', esc_url(wopshopSEFLink('controller=user&task=logout')));
        $view->tmpl_my_account_html_start = "";
        $view->tmpl_my_account_html_content = "";
        $view->tmpl_my_account_html_end = "";
        do_action_ref_array('onBeforeDisplayMyAccountView', array(&$view));
        $view->display();
    }
    
    public function groupsinfo(){        
        $group = WopshopFactory::getTable('usergroup');
        $list = $group->getList();

        do_action_ref_array('onBeforeDisplayGroupsInfo', array());

        $view = $this->getView("user");
        $view->setLayout("groupsinfo");
        $view->assign('rows', $list);
        $view->_tmpl_start = '';
		$view->_tmpl_end = '';
        do_action_ref_array('onBeforeDisplayGroupsInfoView', array(&$view));
        $view->display();
    }
    
    public function logout(){
        do_action_ref_array('onBeforeLogout', array());
        
        wp_logout();
        
        $session = WopshopFactory::getSession();
        $session->set('user_shop_guest', null);
        $session->set('cart', null);
        
        if ($return = WopshopRequest::getVar('return', '', 'method', 'base64')) {
            $return = base64_decode($return);
            if (!WopshopUri::isInternal($return)) {
                $return = '';
            }
        }

        wopshopSetNextUpdatePrices();
        do_action_ref_array('onAfterLogout', array(&$return));

        if (!$return) {
            $return = WopshopUri::base();
        }
        
        $this->setRedirect($return);
    }
}