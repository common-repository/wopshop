<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class OrdersWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
   
    function display() {
        $config = WopshopFactory::getConfig();   
        $context = "list.admin.orders";
        $limit = wopshopGetStateFromRequest( $context.'per_page', 'per_page', 20);
        $paged =  wopshopGetStateFromRequest( $context.'paged', 'paged', 20);
        //$paged = WopshopRequest::getInt('paged');
        //$per_page = wopshopGetStateFromRequest($context.'categories_per_page', 'per_page', 20);

        $id_vendor_cuser = wopshopGetIdVendorForCUser();
        $client_id = WopshopRequest::getInt('client_id',0);

        $status_id = wopshopGetStateFromRequest( $context.'status_id', 'status_id', 0 );
        $year = wopshopGetStateFromRequest( $context.'year', 'year', 0 );
        $month = wopshopGetStateFromRequest( $context.'month', 'month', 0 );
        $day = wopshopGetStateFromRequest( $context.'day', 'day', 0 );
        $notfinished = wopshopGetStateFromRequest( $context.'notfinished', 'notfinished', 0 );
        $text_search = wopshopGetStateFromRequest( $context.'text_search', 's', '' );
        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', "order_number");
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "desc");

        $filter = array("status_id"=>$status_id, 'user_id'=>$client_id, "year"=>$year, "month"=>$month, "day"=>$day, "text_search"=>$text_search, 'notfinished'=>$notfinished);
        
        if ($id_vendor_cuser){
            $filter["vendor_id"] = $id_vendor_cuser;
        }
        
        $orders = $this->getModel("orders");
        
        $total = $orders->getCountAllOrders($filter);        
        if(($paged-1) > ($total/$limit) ) $paged = 1;
        $limitstart = ($paged-1)*$limit;
        $pagination = $orders->getPagination($total, $limit);
        $search = $orders->search($text_search);
        
        $_list_order_status = $orders->getAllOrderStatus();
        $list_order_status = array();
        foreach($_list_order_status as $v){
            $list_order_status[$v->status_id] = $v->name;
        }
        $rows = $orders->getAllOrders($limitstart, $limit, $filter, $filter_order, $filter_order_Dir);
        $lists['status_orders'] = $_list_order_status;
        $_list_status0[] = WopshopHtml::_('select.option', 0, WOPSHOP_ALL_ORDERS, 'status_id', 'name');
        $_list_status = $lists['status_orders'];
        $_list_status = array_merge($_list_status0, $_list_status);
        $lists['changestatus'] = WopshopHtml::_('select.genericlist', $_list_status,'status_id','style = "width: 170px;" ','status_id','name', $status_id );
        $nf_option = array();
        $nf_option[] = WopshopHtml::_('select.option', 0, WOPSHOP_HIDE, 'id', 'name');
        $nf_option[] = WopshopHtml::_('select.option', 1, WOPSHOP_SHOW, 'id', 'name');
        $lists['notfinished'] = WopshopHtml::_('select.genericlist', $nf_option, 'notfinished','style = "width: 100px;" ','id','name', $notfinished );
        
        $firstYear = $orders->getMinYear(); 
        $y_option = array();
        $y_option[] = WopshopHtml::_('select.option', 0, " - - - ", 'id', 'name');
        for($y=$firstYear;$y<=date("Y");$y++){
            $y_option[] = WopshopHtml::_('select.option', $y, $y, 'id', 'name');
        }        
        $lists['year'] = WopshopHtml::_('select.genericlist', $y_option, 'year', 'style = "width: 80px;" ', 'id', 'name', $year);
        
        $y_option = array();
        $y_option[] = WopshopHtml::_('select.option', 0, " - - ", 'id', 'name');
        for($y=1;$y<=12;$y++){
            if ($y<10) $y_month = "0".$y; else $y_month = $y;
            $y_option[] = WopshopHtml::_('select.option', $y_month, $y_month, 'id', 'name');
        }        
        $lists['month'] = WopshopHtml::_('select.genericlist', $y_option, 'month', 'style = "width: 80px;" ', 'id', 'name', $month);
        
        $y_option = array();
        $y_option[] = WopshopHtml::_('select.option', 0, " - - ", 'id', 'name');
        for($y=1;$y<=31;$y++){
            if ($y<10) $y_day = "0".$y; else $y_day = $y;
            $y_option[] = WopshopHtml::_('select.option', $y_day, $y_day, 'id', 'name');
        }        
        $lists['day'] = WopshopHtml::_('select.genericlist', $y_option, 'day', 'style = "width: 80px;" ', 'id', 'name', $day);
		
        $payments = $this->getModel("payments");
        $payments_list = $payments->getListNamePaymens(0);
        
        $shippings = $this->getModel("shippings");
        $shippings_list = $shippings->getListNameShippings(0);
        
        $show_vendor = $config->admin_show_vendors;
        if ($id_vendor_cuser) $show_vendor = 0;
        $display_info_only_my_order = 0;
        if ($config->admin_show_vendors && $id_vendor_cuser){
            $display_info_only_my_order = 1;
        }

        $total = 0;
        foreach($rows as $k=>$row){
            if ($row->vendor_id>0){
                $vendor_name = $row->v_fname." ".$row->v_name;
            }else{
                $vendor_name = "-";
            }
            $rows[$k]->vendor_name = $vendor_name;

            $display_info_order = 1;
            if ($display_info_only_my_order && $id_vendor_cuser!=$row->vendor_id) $display_info_order = 0;
            $rows[$k]->display_info_order = $display_info_order;

            $blocked = 0;
            if (wopshopOrderBlocked($row) || !$display_info_order) $blocked = 1;
            $rows[$k]->blocked = $blocked;

            $rows[$k]->payment_name = $payments_list[$row->payment_method_id];
            $rows[$k]->shipping_name = $shippings_list[$row->shipping_method_id];
			
            $total += $row->order_total / $row->currency_exchange;
        }

        $actions = array(
            'delete' => WOPSHOP_DELETE
        );
        $bulk = $shippings->getBulkActions($actions);
        do_action_ref_array('onBeforeDisplayListOrderAdmin', array(&$rows));
        
        foreach ($rows as $row) {
            $row->_tmp_ext_info_order_number = "";
            $row->_tmp_cols_1 = "";
            $row->_tmp_cols_after_user = "";
            $row->_tmp_cols_3 = "";
            $row->_tmp_cols_4 = "";
            $row->_tmp_cols_5 = "";
            $row->_tmp_cols_6 = "";
            $row->_tmp_cols_7 = "";
            $row->_tmp_cols_8 = "";
            $row->_tmp_ext_info_status = "";
            $row->_tmp_ext_info_update = "";
            $row->_tmp_ext_info_order_total = "";
        }        
        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        $view=$this->getView("orders");
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('lists', $lists);
        $view->assign('filter', $filter);
        $view->assign('pagination', $pagination);
        $view->assign('text_search', $search);
        $view->assign('show_vendor', $show_vendor);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('list_order_status', $list_order_status);
        $view->assign('client_id', $client_id);
        $view->assign('config', $config);
        $view->assign('total', $total);
        $view->assign('bulk', $bulk);
        $view->_tmp_order_list_html_end = '';
        $view->tmp_html_start = "";
        $view->tmp_html_filter = "";
        $view->_tmp_cols_1 = "";
        $view->_tmp_cols_after_user = "";
        $view->_tmp_cols_3 = "";
        $view->_tmp_cols_4 = "";
        $view->_tmp_cols_5 = "";
        $view->_tmp_cols_6 = "";
        $view->_tmp_cols_7 = "";
        $view->_tmp_cols_8 = "";
        $view->_tmp_cols_foot_total = "";
        $view->tmp_html_col_before_td_foot = "";
        $view->tmp_html_col_after_td_foot = "";
        $view->tmp_html_end = "";
		$view->deltaColspan0 = 0;
		$view->deltaColspan = 0;
        do_action_ref_array('onBeforeShowOrderListView', array(&$view));
        $view->display();
        
    }
    function show(){
		$config = WopshopFactory::getConfig();
        $order_id = WopshopRequest::getInt("order_id");
        $lang = $config->cur_lang; //get_bloginfo('language');
        global $wpdb;
        $config = WopshopFactory::getConfig();
        $orders = $this->getModel("orders");
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        $orderstatus = WopshopFactory::getTable('orderstatus');
        $orderstatus->load($order->order_status);
        $name = 'name_'.$lang;
        $order->status_name = $orderstatus->$name;

        $id_vendor_cuser = wopshopGetIdVendorForCUser();

        $shipping_method =WopshopFactory::getTable('shippingmethod');
        $shipping_method->load($order->shipping_method_id);

        $name = "name_".$lang;
        $order->shipping_info = $shipping_method->$name;
        
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;

        $order_items = $order->getAllItems();
        if ($config->admin_show_vendors){
            $tmp_order_vendors = $order->getVendors();
            $order_vendors = array();
            foreach($tmp_order_vendors as $v){
                $order_vendors[$v->id] = $v;
            }
        }

        $order->weight = $order->getWeightItems();
        $order_history = $order->getHistory();
        $lists['status'] = WopshopHtml::_('select.genericlist', $orders->getAllOrderStatus(),'order_status','class = "inputbox" size = "1" id = "order_status"','status_id','name', $order->order_status);

        $country = WopshopFactory::getTable('country');
        $country->load($order->country);
        $field_country_name = "name_".$lang;
        $order->country = $country->$field_country_name;
        
        $d_country = WopshopFactory::getTable('country');
        $d_country->load($order->d_country);
        $field_country_name = "name_".$lang;
        $order->d_country = $d_country->$field_country_name;

        $order->title = $config->user_field_title[$order->title];
        $order->d_title = $config->user_field_title[$order->d_title];

        $order->birthday = wopshopGetDisplayDate($order->birthday, $config->field_birthday_format);
        $order->d_birthday = wopshopGetDisplayDate($order->d_birthday, $config->field_birthday_format);

        $config->user_field_client_type[0]="";
        $order->client_type_name = $config->user_field_client_type[$order->client_type];

        $order->order_tax_list = $order->getTaxExt();

        if ($order->coupon_id){
            $coupon = WopshopFactory::getTable('coupon');
            $coupon->load($order->coupon_id);
            $order->coupon_code = $coupon->coupon_code;
        }

        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('address');
        
        $display_info_only_product = 0;
        if ($config->admin_show_vendors && $id_vendor_cuser){
            if ($order->vendor_id!=$id_vendor_cuser) $display_info_only_product = 1; 
        }
        
        $display_block_change_order_status = $order->order_created;
        if ($config->admin_show_vendors && $id_vendor_cuser){
            if ($order->vendor_id!=$id_vendor_cuser) $display_block_change_order_status = 0;
            foreach($order_items as $k=>$v){
                if ($v->vendor_id!=$id_vendor_cuser){
                    unset($order_items[$k]);
                }
            }
        }

        $order->delivery_time_name = '';
        $order->delivery_date_f = '';
        if ($config->show_delivery_time_checkout){
            $deliverytimes = WopshopFactory::getAllDeliveryTime();
            $order->delivery_time_name = isset( $deliverytimes[$order->delivery_times_id] ) ? $deliverytimes[$order->delivery_times_id] : '';
            if ($order->delivery_time_name==""){
                $order->delivery_time_name = $order->delivery_time;
            }
        }
        if ($config->show_delivery_date && !wopshop_datenull($order->delivery_date)){
            $order->delivery_date_f = wopshop_formatdate($order->delivery_date);
        }
		
        $stat_download = $order->getFilesStatDownloads(1);
        do_action_ref_array( 'onBeforeDisplayOrderAdmin', array(&$order, &$order_items, &$order_history) );        
        $print = WopshopRequest::getInt("print");
        
        $view=$this->getView("orders");
        $view->setLayout("show");
        $view->assign('config', $config); 
        $view->assign('order', $order); 
        $view->assign('order_history', $order_history); 
        $view->assign('order_items', $order_items); 
        $view->assign('lists', $lists); 
        $view->assign('print', $print);
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('display_info_only_product', $display_info_only_product);
        $view->assign('current_vendor_id', $id_vendor_cuser);
        $view->assign('display_block_change_order_status', $display_block_change_order_status);
        $view->_tmp_ext_discount = '';
        $view->_tmp_ext_shipping_package = '';
        $view->tmp_html_start = "";
        $view->tmp_html_info = "";
        $view->tmp_html_table_history_field = "";
        $view->tmp_fields = "";
        $view->tmp_d_fields = "";
        $view->_tmp_html_after_customer_info = "";
        $view->_tmp_ext_subtotal = "";
        $view->_tmp_html_after_subtotal = "";
        $view->_tmp_ext_discount_text = "";
        $view->_tmp_ext_discount = "";
        $view->_tmp_ext_shipping_package = "";
        $view->_tmp_html_after_total = "";
        $view->_ext_end_html = "";
        $view->tmp_html_end = "";
        $view->_update_status_html = "";
        $view->assign('stat_download', $stat_download);
        if ($config->admin_show_vendors){ 
            $view->assign('order_vendors', $order_vendors);
        }
        do_action_ref_array('onBeforeShowOrder', array(&$view));
        $view->display();
    }
    
    function edit(){
        $mainframe = WopshopFactory::getApplication();
        $order_id = WopshopRequest::getVar("order_id");
        $client_id = WopshopRequest::getInt('client_id',0);
		$config = WopshopFactory::getConfig();
        $lang = $config->cur_lang; //get_bloginfo('language');
        global $wpdb;
        $config = WopshopFactory::getConfig();
        $orders = $this->getModel("orders");
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        $name = "name_".$lang;

        $id_vendor_cuser = wopshopGetIdVendorForCUser();
        if ($config->admin_show_vendors && $id_vendor_cuser){
            if ($order->vendor_id!=$id_vendor_cuser) {
                $mainframe->redirect('index.php', 'ALERTNOTAUTH');
                return 0;
            }
        }

        $order_items = $order->getAllItems();

        $_languages = $this->getModel("languages");
        $languages = $_languages->getAllLanguages(1);

        $select_language = WopshopHtml::_('select.genericlist', $languages, 'lang', 'class = "inputbox" style="float:none"','language', 'name', $order->lang);

        $country = WopshopFactory::getTable('country');
        $countries = $country->getAllCountries();
        $select_countries = WopshopHtml::_('select.genericlist', $countries, 'country', 'class = "inputbox"','country_id', 'name', $order->country );
        $select_d_countries = WopshopHtml::_('select.genericlist', $countries, 'd_country', 'class = "inputbox"','country_id', 'name', $order->d_country);

        $option_title = array();
        foreach($config->user_field_title as $key=>$value){
            if ($key>0) $option_title[] = WopshopHtml::_('select.option', $key, $value, 'title_id', 'title_name');
        }
        $select_titles = WopshopHtml::_('select.genericlist', $option_title,'title','class = "inputbox"','title_id','title_name', $order->title);
        $select_d_titles = WopshopHtml::_('select.genericlist', $option_title,'d_title','class = "inputbox endes"','title_id','title_name', $order->d_title);

        $order->birthday = wopshopGetDisplayDate($order->birthday, $config->field_birthday_format);
        $order->d_birthday = wopshopGetDisplayDate($order->d_birthday, $config->field_birthday_format);

        $client_types = array(); 
        foreach ($config->user_field_client_type as $key => $value) {
            $client_types[] = WopshopHtml::_('select.option', $key, $value, 'id', 'name' );
        }
        $select_client_types = WopshopHtml::_('select.genericlist', $client_types,'client_type','class = "inputbox" onchange="showHideFieldFirm(this.value)"','id','name', $order->client_type);

        $config->user_field_client_type[0]="";
        if (isset($config->user_field_client_type[$order->client_type])){
            $order->client_type_name = $config->user_field_client_type[$order->client_type];
        }else{
            $order->client_type_name = '';
        }
        
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('address');

        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;

        $order->order_tax_list = $order->getTaxExt();

        $_currency = $this->getModel("currencies");
        $currency_list = $_currency->getAllCurrencies();
        $order_currency = 0;
        foreach($currency_list as $k=>$v){
            if ($v->currency_code_iso==$order->currency_code_iso) $order_currency = $v->currency_id;
        }
        $select_currency = WopshopHtml::_('select.genericlist', $currency_list, 'currency_id','class = "inputbox"','currency_id','currency_code', $order_currency);

        $display_price_list = array();
        $display_price_list[] = WopshopHtml::_('select.option', 0, WOPSHOP_PRODUCT_BRUTTO_PRICE, 'id', 'name');
        $display_price_list[] = WopshopHtml::_('select.option', 1, WOPSHOP_PRODUCT_NETTO_PRICE, 'id', 'name');
        $display_price_select = WopshopHtml::_('select.genericlist', $display_price_list, 'display_price', 'onchange="updateOrderTotalValue();"', 'id', 'name', $order->display_price);

        $shippings = $this->getModel("shippings");
        $shippings_list = $shippings->getAllShippings(0);
        $shippings_select = WopshopHtml::_('select.genericlist', $shippings_list, 'shipping_method_id', '', 'shipping_id', 'name', $order->shipping_method_id);
        
        $payments = $this->getModel("payments");
        $payments_list = $payments->getAllPaymentMethods(0);
        $payments_select = WopshopHtml::_('select.genericlist', $payments_list, 'payment_method_id', '', 'payment_id', 'name', $order->payment_method_id);
        
        $deliverytimes = WopshopFactory::getAllDeliveryTime();
        $first=array(0=>"- - -");
        $delivery_time_select = WopshopHtml::_('select.genericlist', array_replace($first,$deliverytimes), 'order_delivery_times_id', '', 'id', 'name', $order->delivery_times_id);
        
        $users = $this->getModel('users');
        $users_list = $users->getUsers();
        $first = array(0=>'- - -');
        $users_list_select = WopshopHtml::_('select.genericlist', array_merge($first,$users_list), 'user_id', 'onchange="updateBillingShippingForUser(this.value);"', 'user_id', 'name', $order->user_id);

        foreach($order_items as $k=>$v){
            //FilterOutput::objectHTMLSafe($order_items[$k]);
        }

        $view=$this->getView("orders");
        $view->setLayout("edit");
        $view->assign('config', $config); 
        $view->assign('order', $order);  
        $view->assign('order_items', $order_items); 
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('order_id',$order_id);
        $view->assign('select_countries', $select_countries);
        $view->assign('select_d_countries', $select_d_countries);
        $view->assign('select_titles', $select_titles);
        $view->assign('select_d_titles', $select_d_titles);
        $view->assign('select_client_types', $select_client_types);
        $view->assign('select_currency', $select_currency);
        $view->assign('display_price_select', $display_price_select);
        $view->assign('shippings_select', $shippings_select);
        $view->assign('payments_select', $payments_select);
        $view->assign('select_language', $select_language);
        $view->assign('delivery_time_select', $delivery_time_select);
        $view->assign('users_list_select', $users_list_select);
        $view->assign('client_id', $client_id);
        $view->tmp_html_start = "";
        $view->display_info_only_product = "";
        $view->_tmp_html_after_customer_info = "";
        $view->tmp_fields = "";
        $view->tmp_d_fields = "";
        $view->_ext_attribute_html = "";
        $view->_tmp_html_after_subtotal = "";
        $view->_tmp_html_after_total = "";
        $view->tmp_html_end = "";
        do_action_ref_array('onBeforeEditOrders', array(&$view));
        $view->display();
    }

    function save(){
        if ( !empty($_POST) && check_admin_referer('order_edit','name_of_nonce_field') )
        {
        global $wpdb;
        $config = WopshopFactory::getConfig();
        $post = WopshopRequest::get('post');
        $client_id = WopshopRequest::getInt('client_id',0);        
        $file_generete_pdf_order = $config->file_generete_pdf_order;
        
        $order_id = intval($post['order_id']);
        $orders = $this->getModel("orders");
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        if (!$order_id){
            $order->user_id = -1;
            $order->order_date = wopshopGetJsDate();
            $orderNumber = $config->next_order_number;
            $config->updateNextOrderNumber();
            $order->order_number = $order->formatOrderNumber($orderNumber);
            $order->order_hash = md5(time().$order->order_total.$order->user_id);
            $order->file_hash = md5(time().$order->order_total.$order->user_id."hashfile");
            $order->ip_address = $_SERVER['REMOTE_ADDR'];
            $order->order_status = $config->default_status_order;
        }
        $order->order_m_date = wopshopGetJsDate();
        $order_created_prev = $order->order_created;
        if (isset($post['birthday'])) $post['birthday'] = wopshopGetJsDateDB($post['birthday'], $config->field_birthday_format);
        if (isset($post['d_birthday'])) $post['d_birthday'] = wopshopGetJsDateDB($post['d_birthday'], $config->field_birthday_format);
		if (isset($post['invoice_date'])) $post['invoice_date'] = wopshopGetJsDateDB($post['invoice_date'], $config->store_date_format);
        
        if (!$config->hide_tax){
            $post['order_tax'] = 0;
            $order_tax_ext = array();
            if (isset($post['tax_percent'])){
                foreach($post['tax_percent'] as $k=>$v){
                    if ($post['tax_percent'][$k]!="" || $post['tax_value'][$k]!=""){
                        $order_tax_ext[number_format($post['tax_percent'][$k],2)] = $post['tax_value'][$k];
                    }
                }
            }
            $post['order_tax_ext'] = json_encode($order_tax_ext);
            $post['order_tax'] = number_format(array_sum($order_tax_ext),2);
        }
        
        $currency = WopshopFactory::getTable('currency');
        $currency->load($post['currency_id']);
        $post['currency_code'] = $currency->currency_code;
        $post['currency_code_iso'] = $currency->currency_code_iso;
        $post['currency_exchange'] = $currency->currency_value;
        do_action_ref_array('onBeforeSaveOrder', array(&$post, &$file_generete_pdf_order));
        $order->bind($post);
        $order->delivery_times_id = isset($post['order_delivery_times_id']) ? $post['order_delivery_times_id'] : '';
        $order->store();
        $order_id = $order->order_id;
        $order_items = $order->getAllItems();
        $orders->saveOrderItem($order_id, $post, $order_items);

        $order->items = null;
        $vendor_id = $order->getVendorIdForItems();        
        $order->vendor_id = $vendor_id;
        $order->store();
        
        //WopshopFactory::loadLanguageFile($order->getLang());

        if ($config->order_send_pdf_client || $config->order_send_pdf_admin){
            $order->load($order_id);
            $order->items = null;
            $order->products = $order->getAllItems();
            //WopshopFactory::loadLanguageFile($order->getLang());
            $lang = $config->cur_lang; //get_bloginfo('language');

            $order->order_date = strftime($config->store_date_format, strtotime($order->order_date));
            $order->order_tax_list = $order->getTaxExt();
            $country = WopshopFactory::getTable('country');
            $country->load($order->country);
            $field_country_name = "name_".$lang;
            $order->country = $country->$field_country_name;
            
            $d_country = WopshopFactory::getTable('country');
            $d_country->load($order->d_country);
            $field_country_name = "name_".$lang;
            $order->d_country = $d_country->$field_country_name;

            $shippingMethod = WopshopFactory::getTable('shippingmethod');
            $shippingMethod->load($order->shipping_method_id);

            $pm_method = WopshopFactory::getTable('paymentmethod');
            $pm_method->load($order->payment_method_id);

            $name = "name_".$lang;
            $description = "description_".$lang;
            $order->shipping_information = $shippingMethod->$name;
            $order->payment_name = $pm_method->$name;
            $order->payment_information = $order->payment_params;
            if ($pm_method->show_descr_in_email)
                $this->payment_description = $pm_method->$description;
            else 
                $this->payment_description = "";
			
            if ($config->order_send_pdf_client || $config->order_send_pdf_admin){
                include_once($file_generete_pdf_order);
                $order->pdf_file = wopshop_generatePdf($order);
                $order->insertPDF();
            }
        }
        if ($order->order_created==1 && $order_created_prev==0){
            $order->items = null;
            //WopshopFactory::loadLanguageFile($order->getLang());
            $checkout = WopshopFactory::getModel('checkout');
            if ($config->send_order_email){        
                $checkout->sendOrderEmail($order_id, 1);
            }    
        }
        
        //WopshopFactory::loadAdminLanguageFile();
        do_action_ref_array('onAfterSaveOrder', array(&$order, &$file_generete_pdf_order) );
        $this->setRedirect("admin.php?page=wopshop-orders&client_id=".$client_id);
            
        }
        else wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
        $this->setRedirect("admin.php?page=wopshop-orders&client_id=".$client_id);
    }
        
    
    function _getAllCategoriesLevel($parentId, $currentOrdering = 0){
        $model = $this->getModel('categories');

        $rows = $model->getSubCategories($parentId, "ordering");

        $firstTop = new stdClass();
        $firstTop->category_id = 0;
        $firstTop->name = WOPSHOP_ORDERING_FIRST;
        $first[] = $firstTop;
        $rows = array_merge($first,$rows);
        $currentOrdering = (!$currentOrdering) ? ($rows[count($rows) - 1]->ordering) : ($currentOrdering);

        $select = '<select id="ordering" class="inputbox" size="1" name="ordering">';
        foreach($rows as $index=>$data){
            if($data->category_id == $currentOrdering) $fff = 'selected="selected"'; else $fff = '';
            $select.= '<option '.$fff.' value="'.$data->category_id.'">'.$data->name.'</option>';
        }
        $select.='</select>';
        return $select;
    }


    function update_one_status(){
        $this->_updateStatus(WopshopRequest::getVar('order_id'),WopshopRequest::getVar('order_status'),WopshopRequest::getVar('status_id'),(int)WopshopRequest::getVar('notify'),WopshopRequest::getVar('comments'),WopshopRequest::getVar('include'),1);
    }
    
    function update_status(){
        $this->_updateStatus(WopshopRequest::getVar('order_id'),WopshopRequest::getVar('order_status'),WopshopRequest::getVar('status_id'),(int)WopshopRequest::getVar('notify'),WopshopRequest::getVar('comments'),WopshopRequest::getVar('include'),0);
    }    
    
    function _updateStatus($order_id, $order_status, $status_id, $notify, $comments, $include, $view_order) {
        $config = WopshopFactory::getConfig();
        $client_id = WopshopRequest::getInt('client_id',0);

        do_action_ref_array('onBeforeChangeOrderStatusAdmin', array(&$order_id, &$order_status, &$status_id, &$notify, &$comments, &$include, &$view_order));

        $order = WopshopFactory::getTable('order');
        $order->load($order_id);

        //WopshopFactory::loadLanguageFile($order->getLang());
        $prev_order_status = $order->order_status;
        $order->order_status = $order_status;
        $order->order_m_date = wopshopGetJsDate();
        $order->store();

        $vendorinfo = $order->getVendorInfo();
        if (in_array($order_status, $config->payment_status_return_product_in_stock) && !in_array($prev_order_status, $config->payment_status_return_product_in_stock)){
            $order->changeProductQTYinStock("+");            
        }

        if (in_array($prev_order_status, $config->payment_status_return_product_in_stock) && !in_array($order_status, $config->payment_status_return_product_in_stock)){
            $order->changeProductQTYinStock("-");
        }

        $order_history = WopshopFactory::getTable('orderhistory');
        $order_history->order_id = $order_id;
        $order_history->order_status_id = $order_status;
        $order_history->status_date_added = wopshopGetJsDate();
        $order_history->customer_notify = $notify;
        $order_history->comments = $comments;
        $order_history->store();

        if ($config->admin_show_vendors){
            $listVendors = $order->getVendors();
        }else{
            $listVendors = array();
        }

        $vendors_send_message = ($config->vendor_order_message_type==1 || ($order->vendor_type==1 && $config->vendor_order_message_type==2));
        $vendor_send_order = ($config->vendor_order_message_type==2 && $order->vendor_type == 0 && $order->vendor_id);
        if ($config->vendor_order_message_type==3) $vendor_send_order = 1;
        $admin_send_order = 1;
        if ($config->admin_not_send_email_order_vendor_order && $vendor_send_order && count($listVendors)) $admin_send_order = 0;

        $lang = $config->cur_lang; //get_bloginfo('language');
        $new_status = WopshopFactory::getTable('orderstatus');
        $new_status->load($order_status);
        $comments = ($include)?($comments):('');
        $name = "name_".$lang;

        //$shop_item_id = getShopMainPageItemid();
        //$juri = WopshopUri::getInstance();
        //$liveurlhost = $juri->toString( array("scheme",'host', 'port'));
        //$app = Application::getInstance('site');
        //$router = $app->getRouter();
        //$uri = $router->build('controller=user&task=order&order_id='.$order_id."&Itemid=".$shop_item_id);
        //$url = $uri->toString();
        $url = "";
        //$order_details_url = $liveurlhost.str_replace('/administrator', '', $url);
        /*if ($order->user_id==-1){
            $order_details_url = '';
        }*/

        $order_details_url = '';


        $checkout = WopshopFactory::getModel('checkout');
        $message = $checkout->getMessageChangeStatusOrder($order, $new_status->$name, $vendorinfo, $order_details_url, $comments);

        //message client
        if ($notify){
            $subject = sprintf(WOPSHOP_ORDER_STATUS_CHANGE_SUBJECT, $order->order_number); 
            if ( is_email($order->email) ) {
				do_action_ref_array('onBeforeSendClientMailOrderStatus', array(&$message, &$order, &$comments, &$new_status, &$vendorinfo, &$order_details_url, &$ishtml, &$mailfrom, &$fromname, &$subject));				
				$headers[] = 'From: ' . get_bloginfo() . ' <' . get_option('admin_email') . ">\r\n";				
                wp_mail( $order->email, $subject, $message, $headers);
            }
        }
        
        if ($vendors_send_message || $vendor_send_order){
            $subject = sprintf(WOPSHOP_ORDER_STATUS_CHANGE_SUBJECT, $order->order_number);
			$headers[] = 'From: ' . get_bloginfo() . ' <' . get_option('admin_email') . ">\r\n";
            foreach($listVendors as $k=>$datavendor){
                do_action_ref_array('onBeforeSendVendorMailOrderStatus', array(&$message, &$order, &$comments, &$new_status, &$vendorinfo, &$order_details_url, &$ishtml, &$mailfrom, &$fromname, &$subject, &$datavendor));
				wp_mail( $datavendor->email, $subject, $message, $headers);
            }
        }
        
        do_action_ref_array( 'onAfterChangeOrderStatusAdmin', array(&$order_id, &$order_status, &$status_id, &$notify, &$comments, &$include, &$view_order) );
        if ($view_order)
            $this->setRedirect("admin.php?page=wopshop-orders&task=show&order_id=".$order_id, WOPSHOP_ORDER_STATUS_CHANGED);
        else
            $this->setRedirect("admin.php?page=wopshop-orders&client_id=".$client_id, WOPSHOP_ORDER_STATUS_CHANGED);
        
    }

    function delete(){
        $client_id = WopshopRequest::getInt('client_id',0);
        $cid = WopshopRequest::getVar("rows");
        global $wpdb;
        $tmp = array();
        do_action_ref_array( 'onBeforeRemoveOrder', array(&$cid) );
        if (count($cid)){
            foreach ($cid as $key=>$value){
                if ($wpdb->delete( $wpdb->prefix."wshop_orders", array('order_id' => esc_sql($value)))){
                    $wpdb->delete( $wpdb->prefix."wshop_order_item", array('order_id' => esc_sql($value)));
                    $wpdb->delete( $wpdb->prefix."wshop_order_history", array('order_id' => esc_sql($value)));
                    $tmp[] = $value;
                }
            }
            do_action_ref_array( 'onAfterRemoveOrder', array(&$cid) );
        }
        if (count($tmp)){
            $text = sprintf(WOPSHOP_ORDER_DELETED_ID, implode(",",$tmp));
        }else{
            $text = "";
        }
        $this->setRedirect("admin.php?page=wopshop-orders&client_id=".$client_id, $text);
        
    }

    function get_userinfo() {
        global $wpdb;
        echo esc_html($id = sanitize_key($_REQUEST['user_id']));
        if(!$id){
            print '{}'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            die;
        }

        echo esc_html($query = 'SELECT * FROM `'.$wpdb->prefix.'wshop_users` WHERE `user_id`='.$id);
        $user = $wpdb->get_results($query, ARRAY_A);
        echo json_encode((array)$user);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        die();
    }

    function finish(){
        $config = WopshopFactory::getConfig();
        echo esc_html($order_id = WopshopRequest::getInt('order_id'));

        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        $order->order_created = 1;
        
        do_action_ref_array('onBeforeAdminFinishOrder', array(&$order));
        $order->store();
        
        WopshopFactory::loadLanguageFile($order->getLang());
        $checkout = WopshopFactory::getModel('checkout');
        if ($config->send_order_email){
            $checkout->sendOrderEmail($order_id, 1);
        }
        
        //WopshopFactory::loadAdminLanguageFile();
        $this->setRedirect("admin.php?page=wopshop-orders", WOPSHOP_ORDER_FINISHED);
    }
    function printOrder(){
        WopshopRequest::setVar("print", 1);
        $this->show();
    }
}