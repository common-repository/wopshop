<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class WopshopCheckoutController extends WshopController{
    
    public function __construct(){
        parent::__construct();
        
        do_action_ref_array('onConstructWshopControllerCheckout', array(&$this));
    }
    
    public function display(){
        $this->step2();
    }
    
    public function step2(){
        $config = WopshopFactory::getConfig();
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(2);
        $format = str_replace(array("%d","%m","%Y"), array('dd','mm','yy'), $config->field_birthday_format);
        do_action_ref_array('onLoadCheckoutStep2', array());
        
        $session = WopshopFactory::getSession();
        $user = wp_get_current_user();
        $country = WopshopFactory::getTable('country');
        
        $checkLogin = WopshopRequest::getInt('check_login');
        if ($checkLogin){
            $session->set("show_pay_without_reg", 1);
            wopshopCheckUserLogin();
        }

//        appendPathWay(WOPSHOP_CHECKOUT_ADDRESS);
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("checkout-address");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_CHECKOUT_ADDRESS;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);
        
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        $cart->getSum();

        $adv_user = WopshopFactory::getUser();
        
        $adv_user->birthday = wopshopGetDisplayDate($adv_user->birthday, $config->field_birthday_format);
        $adv_user->d_birthday = wopshopGetDisplayDate($adv_user->d_birthday, $config->field_birthday_format);
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = getEnableDeliveryFiledRegistration('address');

        $checkout_navigator = $checkout->showCheckoutNavigation(2);
        if ($config->show_cart_all_step_checkout){
            $small_cart = $this->_showSmallCart(2);
        }else{
            $small_cart = '';
        }

        $view_name = "checkout";
        $view = $this->getView($view_name);
        $view->setLayout("adress");
        $view->assign('select', $config->user_field_title);
        
        if (!$adv_user->country) {
            $adv_user->country = $config->default_country;
        }
        if (!$adv_user->d_country) {
            $adv_user->d_country = $config->default_country;
        }

        $option_country[] = WopshopHtml::_('select.option',  '0', WOPSHOP_REG_SELECT, 'country_id', 'name' );
        $option_countryes = array_merge($option_country, WopshopFactory::getAllCountries());
        $select_countries = WopshopHtml::_('select.genericlist', $option_countryes, 'country', 'class = "inputbox" size = "1"','country_id', 'name', $adv_user->country );
        $select_d_countries = WopshopHtml::_('select.genericlist', $option_countryes, 'd_country', 'class = "inputbox" size = "1"','country_id', 'name', $adv_user->d_country);

        foreach($config->user_field_title as $key => $value) {
            $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name');
        }
        $select_titles = WopshopHtml::_('select.genericlist', $option_title, 'title', 'class = "inputbox"','title_id', 'title_name', $adv_user->title);            
        $select_d_titles = WopshopHtml::_('select.genericlist', $option_title, 'd_title', 'class = "inputbox"','title_id', 'title_name', $adv_user->d_title);
        
        $client_types = array();
        foreach ($config->user_field_client_type as $key => $value) {
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name' );
        }
        $select_client_types = WopshopHtml::_('select.genericlist', $client_types,'client_type','class = "inputbox" onchange="showHideFieldFirm(this.value)"','id','name', $adv_user->client_type);

		if ($config_fields['birthday']['display'] || $config_fields['d_birthday']['display']){
			WopshopFactory::loadDatepicker();
        }
        $view->assign('config', $config);
        $view->assign('format', $format);
        $view->assign('select_countries', $select_countries);
        $view->assign('select_d_countries', $select_d_countries);
        $view->assign('select_titles', $select_titles);
        $view->assign('select_d_titles', $select_d_titles);
        $view->assign('select_client_types', $select_client_types);
        $view->assign('live_path', WopshopUri::base());
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('user', $adv_user);
        $view->assign('delivery_adress', $adv_user->delivery_adress);
        $view->assign('checkout_navigator', $checkout_navigator);
        $view->assign('small_cart', $small_cart);
        $view->_tmp_ext_html_address_start = "";
        $view->_tmpl_address_html_2 = "";
        $view->_tmpl_address_html_3 = "";
        $view->_tmpl_address_html_4 = "";
        $view->_tmpl_address_html_5 = "";
        $view->_tmpl_address_html_6 = "";
        $view->_tmpl_address_html_7 = "";
        $view->_tmp_ext_html_address_end = "";
        $view->_tmpl_address_html_8 = "";
        $view->_tmpl_address_html_9 = "";
        $view->assign('action', esc_url(wopshopSEFLink('controller=checkout&task=step2save', 0, 0, $config->use_ssl)));
        do_action_ref_array('onBeforeDisplayCheckoutStep2View', array(&$view));
        $view->display();
    }
    
    public function step2save(){
        $config = WopshopFactory::getConfig();
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(2);

        do_action_ref_array('onLoadCheckoutStep2save', array());

        $cart = WopshopFactory::getModel('cart');
        $cart->load();

        $post = WopshopRequest::get('post');
        if (!count($post)){
            wopshopAddMessage(WOPSHOP_ERROR_DATA, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step2', 0, 1, $config->use_ssl)));
            return 0;
        }
        
        if ($post['birthday']) {
            $post['birthday'] = wopshopGetJsDateDB($post['birthday'], $config->field_birthday_format);
        }
        
        if ($post['d_birthday']) {
            $post['d_birthday'] = wopshopGetJsDateDB($post['d_birthday'], $config->field_birthday_format);
        }
        unset($post['user_id']);
        unset($post['usergroup_id']);
        $post['lang'] = $config->cur_lang;
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
        
        $adv_user->bind($post);
        if (!$adv_user->check("address")){
            wopshopAddMessage($adv_user->getError(), 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step2', 0, 1, $config->use_ssl)));
            return 0;
        }
        
        do_action_ref_array('onBeforeSaveCheckoutStep2', array(&$adv_user, &$user, &$cart));
                        
        if (!$adv_user->store()){
            wopshopAddMessage(WOPSHOP_REGWARN_ERROR_DATABASE, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step2', 0, 1, $config->use_ssl)));
            return 0;
        }
//              TO-DO
//        if($user->id && !$config->not_update_user_joomla){
//            $user = clone(WopshopFactory::getUser());
//			if ($adv_user->email){
//				$user->email = $adv_user->email;
//			}
//			if ($adv_user->f_name || $adv_user->l_name){
//				$user->name = $adv_user->f_name." ".$adv_user->l_name;
//			}
//			if ($adv_user->f_name || $adv_user->l_name || $adv_user->email){
//				$user->save();
//			}
//        }
        
        wopshopSetNextUpdatePrices();
        
		$cart->setShippingId(0);
		$cart->setShippingPrId(0);
		$cart->setShippingPrice(0);
		$cart->setPaymentId(0);
		$cart->setPaymentParams("");
		$cart->setPaymentPrice(0);
			
        do_action_ref_array('onAfterSaveCheckoutStep2', array(&$adv_user, &$user, &$cart));
        
        if ($config->without_shipping && $config->without_payment) {
            $checkout->setMaxStep(5);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1, $config->use_ssl)));
            return 0; 
        }
        
        if ($config->without_payment){
            $checkout->setMaxStep(4);
            $this->setRedirect(esc_url(wopshopSEFLink('&controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }

		if ($config->step_4_3){
            if ($config->without_shipping){
                $checkout->setMaxStep(3);
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
                return 0;
            }
            $checkout->setMaxStep(4);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
        }else{
			$checkout->setMaxStep(3);
			$this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
		}
    }
    
    public function step3(){
        $config = WopshopFactory::getConfig();
        $checkout = WopshopFactory::getModel('checkout');
    	$checkout->checkStep(3);

        do_action_ref_array('onLoadCheckoutStep3', array() );

        $session = WopshopFactory::getSession();
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
        
//        appendPathWay(WOPSHOP_CHECKOUT_PAYMENT);
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("checkout-payment");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_CHECKOUT_PAYMENT;
        }
        
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);         
        
        //$checkout_navigator = $this->_showCheckoutNavigation(3);
        $checkout_navigator = $checkout->showCheckoutNavigation(3);
        if ($config->show_cart_all_step_checkout){
            $small_cart = $this->_showSmallCart(3);
        }else{
            $small_cart = '';
        }
        
        if ($config->without_payment){
            $checkout->setMaxStep(4);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }

        $paymentmethod = WopshopFactory::getTable('paymentmethod');
		$shipping_id = $cart->getShippingId();
        $all_payment_methods = $paymentmethod->getAllPaymentMethods(1, $shipping_id);
        $i = 0;
        $paym = array();
        foreach($all_payment_methods as $pm){
            $paym[$i] = new stdClass();
            if ($pm->scriptname!=''){
                $scriptname = $pm->scriptname;    
            }else{
                $scriptname = $pm->payment_class;   
            }
            $paymentmethod->load($pm->payment_id); 
            $paymentsysdata = $paymentmethod->getPaymentSystemData($scriptname);
            if ($paymentsysdata->paymentSystem){
                $paym[$i]->existentcheckform = 1;
				$paym[$i]->payment_system = $paymentsysdata->paymentSystem;
            }else{
                $paym[$i]->existentcheckform = 0;
            }
            
            $paym[$i]->name = $pm->name;
            $paym[$i]->payment_id = $pm->payment_id;
            $paym[$i]->payment_class = $pm->payment_class;
            $paym[$i]->scriptname = $pm->scriptname;
            $paym[$i]->payment_description = $pm->description;
            $paym[$i]->price_type = $pm->price_type;
            $paym[$i]->image = $pm->image;
            $paym[$i]->price_add_text = '';
            if ($pm->price_type==2){
                $paym[$i]->calculeprice = $pm->price;
                if ($paym[$i]->calculeprice!=0){
                    if ($paym[$i]->calculeprice>0){
                        $paym[$i]->price_add_text = '+'.$paym[$i]->calculeprice.'%';
                    }else{
                        $paym[$i]->price_add_text = $paym[$i]->calculeprice.'%';
                    }
                }
            }else{
                $paym[$i]->calculeprice = wopshopGetPriceCalcParamsTax($pm->price * $config->currency_value, $pm->tax_id, $cart->products);
                if ($paym[$i]->calculeprice!=0){
                    if ($paym[$i]->calculeprice>0){
                        $paym[$i]->price_add_text = '+'.wopshopFormatprice($paym[$i]->calculeprice);
                    }else{
                        $paym[$i]->price_add_text = wopshopFormatprice($paym[$i]->calculeprice);
                    }
                }
            }
            
            $s_payment_method_id = $cart->getPaymentId();
            if ($s_payment_method_id == $pm->payment_id){
                $params = $cart->getPaymentParams();
            }else{
                $params = array();
            }

            $parseString = new WopshopParseString($pm->payment_params);
            $pmconfig = $parseString->parseStringToParams();

            if ($paym[$i]->existentcheckform){
                $paym[$i]->form = $paymentmethod->loadPaymentForm($paym[$i]->payment_system, $params, $pmconfig);
            }else{
                $paym[$i]->form = "";
            }
            
            $i++;
        }
        
        $s_payment_method_id = $cart->getPaymentId();
        $active_payment = intval($s_payment_method_id);

        if (!$active_payment){
            $list_payment_id = array();
            foreach($paym as $v){
                $list_payment_id[] = $v->payment_id;
            }
            if (in_array($adv_user->payment_id, $list_payment_id)) $active_payment = $adv_user->payment_id;
        }
        
        if (!$active_payment){
            if (isset($paym[0])){
                $active_payment = $paym[0]->payment_id;
            }
        }
        
        if ($config->hide_payment_step){
            $first_payment = $paym[0]->payment_class;
            if (!$first_payment){
                wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error' );
                return 0;
            }
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3save&payment_method='.$first_payment,0,1,$config->use_ssl)));
            return 0;
        }
        $view_name = "checkout";
        $view = $this->getView($view_name);
        $view->setLayout("payments");        
        $view->assign('payment_methods', $paym);
        $view->assign('active_payment', $active_payment);
        $view->assign('checkout_navigator', $checkout_navigator);
        $view->assign('small_cart', $small_cart);
        $view->_tmp_ext_html_payment_start = "";
        $view->_tmp_ext_html_payment_end = "";
        $view->assign('action', esc_url(wopshopSEFLink('controller=checkout&task=step3save', 0, 0, $config->use_ssl)));
        do_action_ref_array('onBeforeDisplayCheckoutStep3View', array(&$view));
        $view->display();    
    }
    
    function step3save(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(3);
        
        $session = WopshopFactory::getSession();
        $config = WopshopFactory::getConfig();
        $post = WopshopRequest::get('post');

        do_action_ref_array('onBeforeSaveCheckoutStep3save', array(&$post) );
        
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
        
        $payment_method = WopshopRequest::getVar('payment_method'); //class payment method
        $params = WopshopRequest::getVar('params');
        if (isset($params[$payment_method])){
            $params_pm = $params[$payment_method];
        }else{
            $params_pm = '';
        }
        
        $paym_method = WopshopFactory::getTable('paymentmethod');
        $paym_method->class = $payment_method;
        $payment_method_id = $paym_method->getId();
        $paym_method->load($payment_method_id);
        $pmconfigs = $paym_method->getConfigs();
        $paymentsysdata = $paym_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemError || $paym_method->payment_publish==0){
            $cart->setPaymentParams('');
            //JError::raiseWarning(500, WOPSHOP_ERROR_PAYMENT);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
            return 0;
        }
        if ($payment_system){
            if (!$payment_system->checkPaymentInfo($params_pm, $pmconfigs)){
                $cart->setPaymentParams('');
                //JError::raiseWarning("", $payment_system->getErrorMessage());
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1)));
                return 0;
            }            
        }
        
        $paym_method->setCart($cart);
        $cart->setPaymentId($payment_method_id);
        $price = $paym_method->getPrice();
        $cart->setPaymentDatas($price, $paym_method);
        
        if (isset($params[$payment_method])) {
            $cart->setPaymentParams($params_pm);
        } else {
            $cart->setPaymentParams('');
        }
        
        $adv_user->saveTypePayment($payment_method_id);
        
        do_action_ref_array( 'onAfterSaveCheckoutStep3save', array(&$adv_user, &$paym_method, &$cart) );
        
        if ($config->without_shipping) {
            $checkout->setMaxStep(5);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            return 0; 
        }
        
		if ($config->step_4_3){
            $checkout->setMaxStep(5);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
        }else{
			$checkout->setMaxStep(4);
			$this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
		}
    }
    
    public function step4(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(4);
        
        $session = WopshopFactory::getSession();
        $config = WopshopFactory::getConfig();

        do_action_ref_array('onLoadCheckoutStep4', array() );

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("checkout-shipping");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_CHECKOUT_SHIPPING;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);          
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
		$checkout_navigator = $checkout->showCheckoutNavigation(4);
        if ($config->show_cart_all_step_checkout){
            $small_cart = $this->_showSmallCart(4);
        }else{
            $small_cart = '';
        }
        
        if ($config->without_shipping){
        	$checkout->setMaxStep(5);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            return 0; 
        }
        
        $shippingmethod = WopshopFactory::getTable('shippingmethod');
        $shippingmethodprice = WopshopFactory::getTable('shippingmethodprice');
        
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $config->default_country;
        
        if (!$id_country){
            wopshopAddMessage(WOPSHOP_REGWARN_COUNTRY, 'error');
        }
        
        if ($config->show_delivery_time_checkout){
            $deliverytimes = WopshopFactory::getAllDeliveryTime();
            $deliverytimes[0] = '';
        }
        if ($config->show_delivery_date){
            $deliverytimedays = WopshopFactory::getAllDeliveryTimeDays();
        }
        $sh_pr_method_id = $cart->getShippingPrId();
        $active_shipping = intval($sh_pr_method_id);
        $payment_id = $cart->getPaymentId();
        $shippings = $shippingmethod->getAllShippingMethodsCountry($id_country, $payment_id);
        foreach($shippings as $key=>$value){
            $shippingmethodprice->load($value->sh_pr_method_id);
            if ($config->show_list_price_shipping_weight){
                $shippings[$key]->shipping_price = $shippingmethodprice->getPricesWeight($value->sh_pr_method_id, $id_country, $cart);
            }
            $prices = $shippingmethodprice->calculateSum($cart);
            $shippings[$key]->calculeprice = $prices['shipping']+$prices['package'];
            $shippings[$key]->delivery = '';
            $shippings[$key]->delivery_date_f = '';
            if ($config->show_delivery_time_checkout){
                $shippings[$key]->delivery = $deliverytimes[$value->delivery_times_id];
            }
            if ($config->show_delivery_date){
                $day = isset($deliverytimedays[$value->delivery_times_id]) ? $deliverytimedays[$value->delivery_times_id] : 0;
                if ($day){
                    $shippings[$key]->delivery_date = wopshopGetCalculateDeliveryDay($day);
                    $shippings[$key]->delivery_date_f = wopshop_formatdate($shippings[$key]->delivery_date);
                }
            }
            
            if ($value->sh_pr_method_id==$active_shipping){
                $params = $cart->getShippingParams();
            }else{
                $params = array();
            }
            
            $shippings[$key]->form = $shippingmethod->loadShippingForm($value->shipping_id, $value, $params);
        }

        if (!$active_shipping){
            foreach($shippings as $v){
                if ($v->shipping_id == $adv_user->shipping_id){
                    $active_shipping = $v->sh_pr_method_id;
                    break;
                }
            }
        }
        if (!$active_shipping){
            if (isset($shippings[0])){
                $active_shipping = $shippings[0]->sh_pr_method_id;
            }
        }
        
        if ($config->hide_shipping_step){
            $first_shipping = $shippings[0]->sh_pr_method_id;
            if (!$first_shipping){
                wopshopAddMessage(WOPSHOP_ERROR_SHIPPING, 'error');
                return 0;
            }
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4save&sh_pr_method_id='.$first_shipping,0,1,$config->use_ssl)));
            return 0;
        }
        $view_name = "checkout";
        $view = $this->getView($view_name);
        $view->setLayout("shippings");        
        $view->assign('shipping_methods', $shippings);
        $view->assign('active_shipping', $active_shipping);
        $view->assign('config', $config);        
        $view->assign('checkout_navigator', $checkout_navigator);
        $view->assign('small_cart', $small_cart);
        $view->_tmp_ext_html_shipping_start = "";
        $view->_tmp_ext_html_shipping_end = "";
        $view->assign('action', esc_url(wopshopSEFLink('controller=checkout&task=step4save',0,0,$config->use_ssl)));
        do_action_ref_array('onBeforeDisplayCheckoutStep4View', array(&$view));
        $view->display();
    }
    
    function step4save(){
        $checkout = WopshopFactory::getModel('checkout');
    	$checkout->checkStep(4);
        $session = WopshopFactory::getSession();
        $config = WopshopFactory::getConfig();

        do_action_ref_array( 'onBeforeSaveCheckoutStep4save', array());

        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
        
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $config->default_country;
        
        $sh_pr_method_id = WopshopRequest::getInt('sh_pr_method_id');
                
        $shipping_method_price = WopshopFactory::getTable('shippingmethodprice');
        $shipping_method_price->load($sh_pr_method_id);
        
        $sh_method = WopshopFactory::getTable('shippingmethod');
        $sh_method->load($shipping_method_price->shipping_method_id);
        
        if (!$shipping_method_price->sh_pr_method_id){
            wopshopAddMessage(WOPSHOP_ERROR_SHIPPING, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }
        
        if (!$shipping_method_price->isCorrectMethodForCountry($id_country)){
            wopshopAddMessage(WOPSHOP_ERROR_SHIPPING, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }
        
        if (!$sh_method->shipping_id){
            wopshopAddMessage(WOPSHOP_ERROR_SHIPPING, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }
        
        $allparams = WopshopRequest::getVar('params');
        $params = $allparams[$sh_method->shipping_id];
        
        if (isset($params)){
            $cart->setShippingParams($params);
        }else{
            $cart->setShippingParams('');
        }
        
        $shippingForm = $sh_method->getShippingForm();
        
        if ($shippingForm && !$shippingForm->check($params, $sh_method)){
            wopshopAddMessage($shippingForm->getErrorMessage(), 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step4',0,1,$config->use_ssl)));
            return 0;
        }
        
        $prices = $shipping_method_price->calculateSum($cart);
        $cart->setShippingId($sh_method->shipping_id);
        $cart->setShippingPrId($sh_pr_method_id);
        $cart->setShippingsDatas($prices, $shipping_method_price);
        
        if ($config->show_delivery_date){
            $delivery_date = '';
            $deliverytimedays = WopshopFactory::getAllDeliveryTimeDays();
            $day = $deliverytimedays[$shipping_method_price->delivery_times_id];
            if ($day){
                $delivery_date = wopshopGetCalculateDeliveryDay($day);
            }else{
                if ($config->delivery_order_depends_delivery_product){
                    $day = $cart->getDeliveryDaysProducts();
                    if ($day){
                        $delivery_date = wopshopGetCalculateDeliveryDay($day);                    
                    }
                }
            }
            $cart->setDeliveryDate($delivery_date);
        }

        //update payment price
        $payment_method_id = $cart->getPaymentId();
        if ($payment_method_id){
            $paym_method = WopshopFactory::getTable('paymentmethod');
            $paym_method->load($payment_method_id);
            $cart->setDisplayItem(1, 1);
            $paym_method->setCart($cart);
            $price = $paym_method->getPrice();
            $cart->setPaymentDatas($price, $paym_method);
        }

        $adv_user->saveTypeShipping($sh_method->shipping_id);
        
        do_action_ref_array('onAfterSaveCheckoutStep4', array(&$adv_user, &$sh_method, &$shipping_method_price, &$cart));   
		if ($config->step_4_3 && !$config->without_payment){            
            $checkout->setMaxStep(3);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
        }else{		
			$checkout->setMaxStep(5);
			$this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
		}
    }
    
    function step5(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(5);
        do_action_ref_array('onLoadCheckoutStep5', array() );

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("checkout-preview");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_CHECKOUT_PREVIEW;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);  

        $cart = WopshopFactory::getModel('cart');
        $cart->load();

        $session = WopshopFactory::getSession();
        $config = WopshopFactory::getConfig(); 
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();

        $sh_method = WopshopFactory::getTable('shippingmethod');
        $shipping_method_id = $cart->getShippingId();
        $sh_method->load($shipping_method_id);
        
        $sh_mt_pr = WopshopFactory::getTable('shippingmethodprice');
        $sh_mt_pr->load($cart->getShippingPrId());
        if ($config->show_delivery_time_checkout){
            $deliverytimes = WopshopFactory::getAllDeliveryTime();
            $deliverytimes[0] = '';
            $delivery_time = $deliverytimes[$sh_mt_pr->delivery_times_id];
            if (!$delivery_time && $config->delivery_order_depends_delivery_product){
                $delivery_time = $cart->getDelivery();
            }
        }else{
            $delivery_time = '';
        }
        if ($config->show_delivery_date){
            $delivery_date = $cart->getDeliveryDate();
            if ($delivery_date){
                $delivery_date = wopshop_formatdate($cart->getDeliveryDate());
            }
        }else{
            $delivery_date = '';
        }
        
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $payment_method_id = $cart->getPaymentId();
		$pm_method->load($payment_method_id); 

        $field_country_name = "name_".$config->cur_lang;
        
        $invoice_info = array();
        $country = WopshopFactory::getTable('country');
        $country->load($adv_user->country);
        $invoice_info['f_name'] = $adv_user->f_name;
        $invoice_info['l_name'] = $adv_user->l_name;
        $invoice_info['firma_name'] = $adv_user->firma_name;
        $invoice_info['street'] = $adv_user->street;
        $invoice_info['street_nr'] = $adv_user->street_nr;
        $invoice_info['zip'] = $adv_user->zip;
        $invoice_info['state'] = $adv_user->state;
        $invoice_info['city'] = $adv_user->city;
        $invoice_info['country'] = $country->$field_country_name;
        $invoice_info['home'] = $adv_user->home;
        $invoice_info['apartment'] = $adv_user->apartment;
        
		if ($adv_user->delivery_adress){
            $country = WopshopFactory::getTable('country');
            $country->load($adv_user->d_country);
			$delivery_info['f_name'] = $adv_user->d_f_name;
            $delivery_info['l_name'] = $adv_user->d_l_name;
			$delivery_info['firma_name'] = $adv_user->d_firma_name;
			$delivery_info['street'] = $adv_user->d_street;
            $delivery_info['street_nr'] = $adv_user->d_street_nr;
			$delivery_info['zip'] = $adv_user->d_zip;
			$delivery_info['state'] = $adv_user->d_state;
            $delivery_info['city'] = $adv_user->d_city;
			$delivery_info['country'] = $country->$field_country_name;
            $delivery_info['home'] = $adv_user->d_home;
            $delivery_info['apartment'] = $adv_user->d_apartment;
		} else {
            $delivery_info = $invoice_info;
		}
        
        $no_return = 0;
        if ($config->return_policy_for_product){
            $cart_products = array();
            foreach($cart->products as $products){
                $cart_products[] = $products['product_id'];
            }
            $cart_products = array_unique($cart_products);
            $_product_option = WopshopFactory::getTable('productOption');
            $list_no_return = $_product_option->getProductOptionList($cart_products, 'no_return');
            $no_return = intval(in_array('1', $list_no_return));
        }
        if ($config->no_return_all){
            $no_return = 1;
        }
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('address');
        
        $checkout_navigator = $checkout->showCheckoutNavigation(5);
        $small_cart = $this->_showSmallCart(5);

        $view_name = "checkout";
        $view = $this->getView($view_name);
        $view->setLayout("previewfinish");        
        
        do_action_ref_array('onBeforeDisplayCheckoutStep5', array(&$sh_method, &$pm_method, &$delivery_info, &$cart, &$view));
        $name = "name_".$config->cur_lang;
        $sh_method->name = $sh_method->$name;
        $view->assign('no_return', $no_return);
		$view->assign('sh_method', $sh_method );
		$view->assign('payment_name', $pm_method->$name);
        $view->assign('delivery_info', $delivery_info);
		$view->assign('invoice_info', $invoice_info);
        $view->assign('action', esc_url(wopshopSEFLink('controller=checkout&task=step5save',0,0, $config->use_ssl)));       
        $view->assign('config', $config);
        $view->assign('delivery_time', $delivery_time);
        $view->assign('delivery_date', $delivery_date);
        $view->assign('checkout_navigator', $checkout_navigator);
        $view->assign('small_cart', $small_cart);
		$view->assign('count_filed_delivery', $count_filed_delivery);
        $view->_tmp_ext_html_previewfinish_start = "";
        $view->_tmp_ext_html_previewfinish_agb = "";
        $view->_tmp_ext_html_previewfinish_before_button = "";
        $view->_tmp_ext_html_previewfinish_end = "";
        do_action_ref_array('onBeforeDisplayCheckoutStep5View', array(&$view));
    	$view->display();
    }

    public function step5save(){
		$session = WopshopFactory::getSession();
        $config = WopshopFactory::getConfig();
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(5);
		$checkagb = WopshopRequest::getVar('agb');
		do_action_ref_array('onLoadStep5save', array(&$checkagb));
        
        $user = wp_get_current_user();
        $adv_user = WopshopFactory::getUser();
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        $cart->setDisplayItem(1, 1);
        $cart->setDisplayFreeAttributes();
		
		if ($config->check_php_agb && $checkagb!='on'){
            wopshopAddMessage(WOPSHOP_ERROR_AGB, 'error');
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            return 0;
        }

        if (!$cart->checkListProductsQtyInStore()){
            $this->setRedirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
            return 0;
        }
		if (!$session->get('checkcoupon')){
            if (!$cart->checkCoupon()){
                $cart->setRabatt(0,0,0);
                wopshopAddMessage(WOPSHOP_RABATT_NON_CORRECT, 'error');
                $this->setRedirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
                return 0;
            }
            $session->set('checkcoupon', 1);
        }

        $orderNumber = $config->getNextOrderNumber();
        $config->updateNextOrderNumber();

        $payment_method_id = $cart->getPaymentId();
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($payment_method_id);
		$payment_method = $pm_method->payment_class;

        if ($config->without_payment){
            $pm_method->payment_type = 1;
            $paymentSystemVerySimple = 1; 
        }else{
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;
            if ($paymentsysdata->paymentSystemVerySimple){
                $paymentSystemVerySimple = 1;
            }
            if ($paymentsysdata->paymentSystemError){
                $cart->setPaymentParams("");
                wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
                return 0;
            }
        }

        $order = WopshopFactory::getTable('order');
        $arr_property = $order->getListFieldCopyUserToOrder();
        foreach($adv_user as $key => $value){
            if (in_array($key, $arr_property)){
                $order->$key = $value;
            }
        }

        $sh_mt_pr = WopshopFactory::getTable('shippingmethodprice');
        $sh_mt_pr->load($cart->getShippingPrId());

        $order->order_date = $order->order_m_date = wopshopGetJsDate();
        $order->order_tax = $cart->getTax(1, 1, 1);
        $order->setTaxExt($cart->getTaxExt(1, 1, 1));
        $order->order_subtotal = $cart->getPriceProducts();
        $order->order_shipping = $cart->getShippingPrice();
        $order->order_payment = $cart->getPaymentPrice();
        $order->order_discount = $cart->getDiscountShow();
        $order->shipping_tax = $cart->getShippingPriceTaxPercent();
        $order->setShippingTaxExt($cart->getShippingTaxList());
        $order->payment_tax = $cart->getPaymentTaxPercent();
        $order->setPaymentTaxExt($cart->getPaymentTaxList());
        $order->order_package = $cart->getPackagePrice();
        $order->setPackageTaxExt($cart->getPackageTaxList());
        $order->order_total = $cart->getSum(1, 1, 1);
        $order->currency_exchange = $config->currency_value;
        $order->vendor_type = $cart->getVendorType();
        $order->vendor_id = $cart->getVendorId();
        $order->order_status = $config->default_status_order;
        $order->shipping_method_id = $cart->getShippingId();
        $order->payment_method_id = $cart->getPaymentId();
        $order->delivery_times_id = $sh_mt_pr->delivery_times_id;
        if ($config->delivery_order_depends_delivery_product){
            $order->delivery_time = $cart->getDelivery();
        }
        if ($config->show_delivery_date){
            $order->delivery_date = $cart->getDeliveryDate();
        }
        $order->coupon_id = $cart->getCouponId();

        $pm_params = $cart->getPaymentParams();

        if (is_array($pm_params) && !$paymentSystemVerySimple){
            $payment_system->setParams($pm_params);
            $payment_params_names = $payment_system->getDisplayNameParams();
            $order->payment_params = wopshopGetTextNameArrayValue($payment_params_names, $pm_params);
            $order->setPaymentParamsData($pm_params);
        }
        
        $sh_params = $cart->getShippingParams();
        if (is_array($sh_params)){
            $sh_method = WopshopFactory::getTable('shippingmethod');
            $sh_method->load($cart->getShippingId());
            $shippingForm = $sh_method->getShippingForm();
            if ($shippingForm){
                $shipping_params_names = $shippingForm->getDisplayNameParams();            
                $order->shipping_params = wopshopGetTextNameArrayValue($shipping_params_names, $sh_params);
            }
            $order->setShippingParamsData($sh_params);
        }
        
        $name = "name_".$config->cur_lang;
        $order->ip_address = $_SERVER['REMOTE_ADDR'];
        $order->order_add_info = WopshopRequest::getVar('order_add_info','');
        $order->currency_code = $config->currency_code;
        $order->currency_code_iso = $config->currency_code_iso;
        $order->order_number = $order->formatOrderNumber($orderNumber);
        $order->order_hash = md5(time().$order->order_total.$order->user_id);
        $order->file_hash = md5(time().$order->order_total.$order->user_id."hashfile");
        $order->display_price = $config->display_price_front_current;
        $order->lang = $config->cur_lang;
        
        if ($order->client_type){
            $order->client_type_name = $config->user_field_client_type[$order->client_type];
        }else{
            $order->client_type_name = "";
        }
		
		if ($order->order_total==0){
            $pm_method->payment_type = 1;
            $config->without_payment = 1;
            $order->order_status = $config->payment_status_paid;
        }
        
        if ($pm_method->payment_type == 1){
            $order->order_created = 1; 
        }else {
            $order->order_created = 0;
        }
        
        if (!$adv_user->delivery_adress) {
			$order->copyDeliveryData();
		}
        
        do_action_ref_array('onBeforeCreateOrder', array(&$order));

        $order->store();

        do_action_ref_array('onAfterCreateOrder', array(&$order));

        if ($cart->getCouponId()){
            $coupon = WopshopFactory::getTable('coupon');
            $coupon->load($cart->getCouponId());
            if ($coupon->finished_after_used){
                $free_discount = $cart->getFreeDiscount();
                if ($free_discount > 0){
                    $coupon->coupon_value = $free_discount / $config->currency_value;
                }else{
                    $coupon->used = $adv_user->user_id;
                }
                $coupon->store();
            }
        }

        $order->saveOrderItem($cart->products);

		do_action_ref_array('onAfterCreateOrderFull', array(&$order));
		
        $session->set("wshop_end_order_id", $order->order_id);

        $order_history = WopshopFactory::getTable('orderhistory');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $order->order_status;
        $order_history->status_date_added = $order->order_date;
        $order_history->customer_notify = 1;
        $order_history->store();
        
        if ($pm_method->payment_type == 1){
            if ($config->order_stock_removed_only_paid_status){
                $product_stock_removed = (in_array($order->order_status, $config->payment_status_enable_download_sale_file));
            }else{
                $product_stock_removed = 1;
            }
            if ($product_stock_removed){
                $order->changeProductQTYinStock("-");
            }
            if ($config->send_order_email){
                $checkout->sendOrderEmail($order->order_id);
            }
        }
        
        do_action_ref_array('onEndCheckoutStep5', array(&$order) );

        $session->set("wshop_send_end_form", 0);
        
        if ($config->without_payment){
            $checkout->setMaxStep(10);
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish',0,1,$config->use_ssl)));
            return 0;
        }
        
        $pmconfigs = $pm_method->getConfigs();
        
        $task = "step6";
        if (isset($pmconfigs['windowtype']) && $pmconfigs['windowtype']==2){
            $task = "step6iframe";
            $session->set("jsps_iframe_width", $pmconfigs['iframe_width']);
            $session->set("jsps_iframe_height", $pmconfigs['iframe_height']);
        }
        $checkout->setMaxStep(6);
        $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task='.$task,0,1,$config->use_ssl)));
    }

    function step6iframe(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(6);
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $width = $session->get("jsps_iframe_width");
        $height = $session->get("jsps_iframe_height");
        if (!$width) $width = 600;
        if (!$height) $height = 600;
        do_action_ref_array('onBeforeStep6Iframe', array(&$width, &$height));
        ?><iframe width="<?php print $width?>" height="<?php print $height?>" frameborder="0" src="<?php print esc_url(wopshopSEFLink('controller=checkout&task=step6&wmiframe=1',0,1,$config->use_ssl))?>"></iframe><?php
    }

    function step6(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(6);
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        header("Cache-Control: no-cache, must-revalidate");
        $order_id = $session->get('wshop_end_order_id');
        $wmiframe = WopshopRequest::getInt("wmiframe");

        if (!$order_id){
            wopshopAddMessage(WOPSHOP_SESSION_FINISH, 'error');
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            }else{
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            }
        }
        
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);

        // user click back in payment system 
        $wshop_send_end_form = $session->get('wshop_send_end_form');
        if ($wshop_send_end_form == 1){
            $this->_cancelPayOrder($order_id);
            return 0;
        }

        $pm_method = WopshopFactory::getTable('paymentmethod');
        $payment_method_id = $order->payment_method_id;
        $pm_method->load($payment_method_id);
        $payment_method = $pm_method->payment_class; 
        
        $paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        $paymentSystemVerySimple = 0;
        if ($paymentsysdata->paymentSystemVerySimple){
            $paymentSystemVerySimple = 1;
        }
        if ($paymentsysdata->paymentSystemError){
            $cart->setPaymentParams("");
            wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
            }else{
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step3',0,1,$config->use_ssl)));
            }
            return 0;
        }
		
        if ($pm_method->payment_type == 1 || $paymentSystemVerySimple) { 
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish',0,1,$config->use_ssl)));
            }else{
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish',0,1,$config->use_ssl)));
            }
            return 0;
        }

        do_action_ref_array('onBeforeShowEndFormStep6', array(&$order, &$cart, $pm_method));
        $session->set('wshop_send_end_form', 1);
        $pmconfigs = $pm_method->getConfigs();
        $payment_system->showEndForm($pmconfigs, $order);

    }

    public function step7(){
        $checkout = WopshopFactory::getModel('checkout');
        $wmiframe = WopshopRequest::getInt("wmiframe");
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        do_action_ref_array('onLoadStep7', array());
        $pm_method = WopshopFactory::getTable('paymentmethod');
        
        $str = "url: ".$_SERVER['REQUEST_URI']."\n";
        foreach($_POST as $k=>$v) $str .= $k."=".$v."\n";
        wopshopSaveToLog("paymentdata.log", $str);
        
        $act = WopshopRequest::getVar("act");
        $payment_method = WopshopRequest::getVar("js_paymentclass");
        
        $pm_method->loadFromClass($payment_method);
        
        $paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemVerySimple){
            if (WopshopRequest::getInt('no_lang')) {
                WopshopFactory::loadLanguageFile();
            }
            wopshopSaveToLog("payment.log", "#001 - Error payment method file. PM ".$payment_method);
            wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
            return 0;
        }
        
        if ($paymentsysdata->paymentSystemError){
            if (WopshopRequest::getInt('no_lang')) {
                WopshopFactory::loadLanguageFile();
            }
            wopshopSaveToLog("payment.log", "#002 - Error payment. CLASS ".$payment_method);
            wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
            return 0;
        }
        
        $pmconfigs = $pm_method->getConfigs();
        $urlParamsPS = $payment_system->getUrlParams($pmconfigs);
        
        $order_id = $urlParamsPS['order_id'];
        $hash = $urlParamsPS['hash'];
        $checkHash = $urlParamsPS['checkHash'];
        $checkReturnParams = $urlParamsPS['checkReturnParams'];
        
        $session->set('wshop_send_end_form', 0);
        
        if ($act == "cancel"){
            $this->_cancelPayOrder($order_id);
            return 0;
        }

        if ($act == "return" && !$checkReturnParams){
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish', 0, 1, $config->use_ssl)));
            } else {
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish', 0, 1, $config->use_ssl)));
            }
            return 1;
        }
        
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        
        if (WopshopRequest::getInt('no_lang')){
            WopshopFactory::loadLanguageFile($order->getLang());
        }

        if ($checkHash && $order->order_hash != $hash){
            wopshopSaveToLog("payment.log", "#003 - Error order hash. Order id ".$order_id);
            wopshopAddMessage(WOPSHOP_ERROR_ORDER_HASH, 'error');
            return 0;
        }
        
        if (!$order->payment_method_id){
            wopshopSaveToLog("payment.log", "#004 - Error payment method id. Order id ".$order_id);
            wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
            return 0;
        }

        if ($order->payment_method_id != $pm_method->payment_id){
            wopshopSaveToLog("payment.log", "#005 - Error payment method set url. Order id ".$order_id);
            wopshopAddMessage(WOPSHOP_ERROR_PAYMENT, 'error');
            return 0;
        }

        $res = $payment_system->checkTransaction($pmconfigs, $order, $act);
        $rescode = $res[0];
        $restext = $res[1];
        $transaction = $res[2];
        $transactiondata = $res[3];
        
        $status = $payment_system->getStatusFromResCode($rescode, $pmconfigs);
        
        $order->transaction = $transaction;
        $order->store();
        $order->saveTransactionData($rescode, $status, $transactiondata);
        
        if ($restext != ''){
            wopshopSaveToLog("payment.log", $restext);
        }        

        if ($status && !$order->order_created){
            $order->order_created = 1;
            $order->order_status = $status;
            do_action_ref_array('onStep7OrderCreated', array(&$order, &$res, &$checkout, &$pmconfigs));
            $order->store();
            if ($config->send_order_email){
                $checkout->sendOrderEmail($order->order_id);
            }
            if ($config->order_stock_removed_only_paid_status){
                $product_stock_removed = in_array($status, $config->payment_status_enable_download_sale_file);
            }else{
                $product_stock_removed = 1;
            }
            if ($product_stock_removed){
                $order->changeProductQTYinStock("-");
            }
            $checkout->changeStatusOrder($order_id, $status, 0);
        }

        if ($status && $order->order_status != $status){
           $checkout->changeStatusOrder($order_id, $status, 1);
        }
        
        do_action_ref_array('onStep7BeforeNotify', array(&$order, &$checkout, &$pmconfigs));
        
        if ($act == "notify"){
            $payment_system->nofityFinish($pmconfigs, $order, $rescode);
            die();
        }
        
        $payment_system->finish($pmconfigs, $order, $rescode, $act);

        if (in_array($rescode, array(0,3,4))){
            wopshopAddMessage($restext, 'error');
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5', 0, 1, $config->use_ssl)));
            } else {
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5', 0, 1, $config->use_ssl)));
            }
            return 0;
        } else {
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish', 0, 1, $config->use_ssl)));
            } else {
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=finish', 0, 1, $config->use_ssl)));
            }
            return 1;
        }
    }

    public function finish(){
        $checkout = WopshopFactory::getModel('checkout');
        $checkout->checkStep(10);
        $session = WopshopFactory::getSession();
        $order_id = $session->get('wshop_end_order_id');

        $this->addMetaTag('title', WOPSHOP_CHECKOUT_FINISH);  
        $statictext = WopshopFactory::getTable("statictext");
        $rowstatictext = $statictext->loadData("order_finish_descr");
        $text = $rowstatictext->text;

        do_action_ref_array('onBeforeDisplayCheckoutFinish', array(&$text, &$order_id));

        if (trim(strip_tags($text))==""){
            $text = '';
        }
        $view_name = "checkout";
        $view = $this->getView($view_name);
        $view->setLayout("finish");  
        $view->assign('text', $text);
        $view->display();

        if ($order_id){
            $order = WopshopFactory::getTable('order');
            $order->load($order_id);
            $pm_method = WopshopFactory::getTable('paymentmethod');
            $payment_method_id = $order->payment_method_id;
            $pm_method->load($payment_method_id);
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;
            if ($payment_system){
                $pmconfigs = $pm_method->getConfigs();
                $payment_system->complete($pmconfigs, $order, $pm_method);
            }
            do_action_ref_array('onAfterDisplayCheckoutFinish', array(&$text, &$order, &$pm_method));
        }

        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        $cart->getSum();
        $cart->clear();
        $checkout->deleteSession();
    }

    function _showSmallCart($step = 0){
        $config =  WopshopFactory::getConfig();
        
        $cart = WopshopFactory::getModel('cart');
        $cart->load();
        $cart->wopshopAddLinkToProducts(0);
        $cart->setDisplayFreeAttributes();
        
        foreach($cart->products as $k=>$v) {
			$cart->products[$k]['_tmp_tr_before'] = "";
			$cart->products[$k]['_tmp_tr_after'] = "";
			$cart->products[$k]['_ext_product_name'] = "";
			$cart->products[$k]['_ext_attribute_html'] = "";
			$cart->products[$k]['_ext_price_html'] = "";
			$cart->products[$k]['_qty_unit'] = "";
			$cart->products[$k]['_ext_price_total_html'] = "";
		}
        
        if ($step == 5){
            $cart->setDisplayItem(1, 1);
        }elseif ($step == 4 && !$config->step_4_3) {
            $cart->setDisplayItem(0, 1);
        }elseif ($step == 3 && $config->step_4_3){
            $cart->setDisplayItem(1, 0);
		}else{
            $cart->setDisplayItem(0, 0);
        }
        $cart->updateDiscountData();

        $weight_product = $cart->getWeightProducts();
        if ($weight_product==0 && $config->hide_weight_in_cart_weight0){
            $config->show_weight_order = 0;
        }
        do_action_ref_array( 'onBeforeDisplaySmallCart', array(&$cart) );
                
        $view_name = "cart";
        $view = $this->getView($view_name);
        $view->setLayout("checkout");
        $view->assign('step', $step);
        $view->assign('config', $config);
        $view->assign('products', $cart->products);
        $view->assign('summ', $cart->getPriceProducts());
        $view->assign('image_product_path', $config->image_product_live_path);
        $view->assign('no_image', $config->noimage);
        $view->assign('discount', $cart->getDiscountShow());
        $view->assign('free_discount', $cart->getFreeDiscount());
        $deliverytimes = WopshopFactory::getAllDeliveryTime();
        $view->assign('deliverytimes', $deliverytimes);
        
        $payment_method_id = $cart->getPaymentId();
        if ($payment_method_id){
            $pm_method = WopshopFactory::getTable('paymentmethod');            
            $pm_method->load($payment_method_id);
            $name = 'name_'.$config->cur_lang;
            $payment_name = $pm_method->$name;
        }else{
            $payment_name = '';
        }
        $view->assign('payment_name', $payment_name);
		$view->summ_payment = 0;
        if ($step == 5){
            if (!$config->without_shipping){
                $view->assign('summ_delivery', $cart->getShippingPrice());
                if ($cart->getPackagePrice()>0 || $config->display_null_package_price){
                    $view->assign('summ_package', $cart->getPackagePrice());
                }
				$view->assign('summ_payment', $cart->getPaymentPrice());
                $fullsumm = $cart->getSum(1,1,1);
                $tax_list = $cart->getTaxExt(1,1,1);
            }else{
				$view->assign('summ_payment', $cart->getPaymentPrice());
                $fullsumm = $cart->getSum(0,1,1);
                $tax_list = $cart->getTaxExt(0,1,1);
            }
        }elseif($step == 4 && !$config->step_4_3){
            $view->assign('summ_payment', $cart->getPaymentPrice());
            $fullsumm = $cart->getSum(0,1,1);
            $tax_list = $cart->getTaxExt(0,1,1);
        }elseif($step == 3 && $config->step_4_3){
			$view->assign('summ_delivery', $cart->getShippingPrice());
            if ($cart->getPackagePrice()>0 || $config->display_null_package_price){
                $view->assign('summ_package', $cart->getPackagePrice());
            }
			$fullsumm = $cart->getSum(1,1,0);
            $tax_list = $cart->getTaxExt(1,1,0);
		}
		else{
            $fullsumm = $cart->getSum(0, 1, 0);
            $tax_list = $cart->getTaxExt(0, 1, 0);
        }
        
        $show_percent_tax = 0;
        if (count($tax_list)>1 || $config->show_tax_in_product) $show_percent_tax = 1;
        if ($config->hide_tax) $show_percent_tax = 0;
        $hide_subtotal = 0;
        if ($step == 5){
            if (($config->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $config->without_shipping && $cart->getPaymentPrice()==0) $hide_subtotal = 1;
        }elseif ($step == 4 && !$config->step_4_3) {
            if (($config->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $cart->getPaymentPrice()==0) $hide_subtotal = 1;
        }elseif ($step == 3 && $config->step_4_3){
            if (($config->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $config->without_shipping) $hide_subtotal = 1;
        }else{
            if (($config->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ) $hide_subtotal = 1;
        }
        
        $text_total = WOPSHOP_PRICE_TOTAL;
        if ($step == 5){
            $text_total = WOPSHOP_ENDTOTAL;
            if (($config->show_tax_in_product || $config->show_tax_product_in_cart) && (count($tax_list)>0)){
                $text_total = WOPSHOP_ENDTOTAL_INKL_TAX;
            }
        }

        $view->assign('tax_list', $tax_list);
        $view->assign('fullsumm', $fullsumm);
        $view->assign('show_percent_tax', $show_percent_tax);
        $view->assign('hide_subtotal', $hide_subtotal);
        $view->assign('text_total', $text_total);
        $view->assign('weight', $weight_product);
        $view->_tmp_ext_subtotal = "";
        $view->_tmp_html_after_subtotal = "";
        $view->_tmp_ext_discount_text = "";
        $view->_tmp_ext_discount = "";
        $view->_tmp_ext_shipping = "";
        $view->_tmp_ext_shipping_package = "";
        $view->_tmp_ext_payment = "";        
        $view->_tmp_ext_tax = array();
        $view->_tmp_ext_total = "";
        $view->_tmp_html_after_total = "";
        $view->_tmp_html_after_checkout_cart = "";
        $view->checkoutcartdescr = "";
        foreach ($tax_list as $k => $v) {
            $view->_tmp_ext_tax[$k] = "";
        }
        do_action_ref_array('onBeforeDisplayCheckoutCartView', array(&$view));
    
        return $view->loadTemplate();
    }

    function _cancelPayOrder($order_id=""){
        $config = WopshopFactory::getConfig();
        $checkout = WopshopFactory::getModel('checkout');
        $wmiframe = WopshopRequest::getInt("wmiframe");
        $session = WopshopFactory::getSession();
        if (!$order_id) $order_id = $session->get('wshop_end_order_id');
        if (!$order_id){
            wopshopAddMessage(WOPSHOP_SESSION_FINISH, 'error');
            if (!$wmiframe){
                $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            }else{
                $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1,$config->use_ssl)));
            }
            return 0;
        }

        $checkout->cancelPayOrder($order_id);
        wopshopAddMessage(WOPSHOP_PAYMENT_CANCELED, 'error');
        if (!$wmiframe){ 
            $this->setRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1, $config->use_ssl)));
        }else{
            $this->iframeRedirect(esc_url(wopshopSEFLink('controller=checkout&task=step5',0,1, $config->use_ssl)));
        }
        return 0;
    }
    
    function iframeRedirect($url){
        echo "<script>parent.location.href='".esc_url($url)."';</script>\n";
        exit();
    }
}