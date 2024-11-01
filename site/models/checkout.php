<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//include_once(WOPSHOP_PLUGIN_URL."/payments/payment.php");
//include_once(WOPSHOP_PLUGIN_URL."/shippingform/shippingform.php");

class WopshopCheckoutModel extends WshopModel{

	public function __construct() {
		parent::__construct();
	}

	public function sendOrderEmail($order_id, $manuallysend = 0){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $order = WopshopFactory::getTable('order');
        $config->user_field_title[0] = '';
        $config->user_field_client_type[0] = '';
        $file_generete_pdf_order = $config->file_generete_pdf_order;

        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $config->getEnableDeliveryFiledRegistration('address');

        $order->load($order_id);

        $status = WopshopFactory::getTable('orderstatus');
        $status->load($order->order_status);
        $name = "name_".$config->cur_lang;
        $order->status = $status->$name;
        $order->order_date = strftime($config->store_date_format, strtotime($order->order_date));
        $order->products = $order->getAllItems();
        $order->weight = $order->getWeightItems();
        if ($config->show_delivery_time_checkout){
            $deliverytimes = WopshopFactory::getAllDeliveryTime();
            if (isset($deliverytimes[$order->delivery_times_id])){
                $order->order_delivery_time = $deliverytimes[$order->delivery_times_id];
            }else{
                $order->order_delivery_time = '';
            }
            if ($order->order_delivery_time==""){
                $order->order_delivery_time = $order->delivery_time;
            }
        }
        $order->order_tax_list = $order->getTaxExt();
        $show_percent_tax = 0;        
        if (count($order->order_tax_list)>1 || $config->show_tax_in_product) $show_percent_tax = 1;        
        if ($config->hide_tax) $show_percent_tax = 0;
        $hide_subtotal = 0;
        if (($config->hide_tax || count($order->order_tax_list)==0) && $order->order_discount==0 && $config->without_shipping && $order->order_payment==0) $hide_subtotal = 1;
        
        if ($order->weight==0 && $config->hide_weight_in_cart_weight0){
            $config->show_weight_order = 0;
        }
        
        $country = WopshopFactory::getTable('country');
        $country->load($order->country);
        //$field_country_name = "name_".$config->cur_lang;
        $order->country = $country->$name;        
        
        $d_country = WopshopFactory::getTable('country');
        $d_country->load($order->d_country);
        //$field_country_name = $lang->get("name");
        $order->d_country = $d_country->$name;
        if ($config->show_delivery_date && !wopshop_datenull($order->delivery_date)){
            $order->delivery_date_f = wopshop_formatdate($order->delivery_date);
        }else{
            $order->delivery_date_f = '';
        }
        
        $order->title = $config->user_field_title[$order->title];
        $order->d_title = $config->user_field_title[$order->d_title];
		$order->birthday = wopshopGetDisplayDate($order->birthday, $config->field_birthday_format);
        $order->d_birthday = wopshopGetDisplayDate($order->d_birthday, $config->field_birthday_format);
		$order->client_type_name = $config->user_field_client_type[$order->client_type];
		
        $shippingMethod = WopshopFactory::getTable('shippingmethod');
        $shippingMethod->load($order->shipping_method_id);
        
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($order->payment_method_id);
		$paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;

        $description = "description_".$config->cur_lang;
        $order->shipping_information = $shippingMethod->$name;
        $shippingForm = $shippingMethod->getShippingForm();
        if ($shippingForm){
            $shippingForm->prepareParamsDispayMail($order, $shippingMethod);
        }
        $order->payment_name = $pm_method->$name;
        $order->payment_information = $order->payment_params;
		if ($payment_system){
            $payment_system->prepareParamsDispayMail($order, $pm_method);
        }
        if ($pm_method->show_descr_in_email) $order->payment_description = $pm_method->$description;  else $order->payment_description = "";

        $statictext = WopshopFactory::getTable("statictext");
        $rowstatictext = $statictext->loadData("order_email_descr");        
        $order_email_descr = $rowstatictext->text;
        $order_email_descr = str_replace("{name}",$order->f_name, $order_email_descr);
        $order_email_descr = str_replace("{family}",$order->l_name, $order_email_descr);
        $order_email_descr = str_replace("{email}",$order->email, $order_email_descr);
        $order_email_descr = str_replace("{title}",$order->title, $order_email_descr);
		
        $rowstatictext = $statictext->loadData("order_email_descr_end");
        $order_email_descr_end = $rowstatictext->text;
        $order_email_descr_end = str_replace("{name}",$order->f_name, $order_email_descr_end);
        $order_email_descr_end = str_replace("{family}",$order->l_name, $order_email_descr_end);
        $order_email_descr_end = str_replace("{email}",$order->email, $order_email_descr_end);
		$order_email_descr_end = str_replace("{title}",$order->title, $order_email_descr_end);
        if ($config->show_return_policy_text_in_email_order){
            $list = $order->getReturnPolicy();
            $listtext = array();
            foreach($list as $v){
                $listtext[] = $v->text;
            }
            $rptext = implode('<div class="return_policy_space"></div>', $listtext);
            $order_email_descr_end = $rptext.$order_email_descr_end;
        }

        $text_total = WOPSHOP_ENDTOTAL;
        if (($config->show_tax_in_product || $config->show_tax_product_in_cart) && (count($order->order_tax_list)>0)){
            $text_total = WOPSHOP_ENDTOTAL_INKL_TAX;
        }
        
        $uri = WopshopUri::getInstance();
        $liveurlhost = $uri->toString(array("scheme",'host', 'port'));
        
        if ($config->admin_show_vendors){
            $listVendors = $order->getVendors();
        }else{
            $listVendors = array();
        }

        $vendors_send_message = $config->vendor_order_message_type==1;
        $vendor_send_order = $config->vendor_order_message_type==2;
        $vendor_send_order_admin = (($config->vendor_order_message_type==2 && $order->vendor_type == 0 && $order->vendor_id) || $config->vendor_order_message_type==3);
        if ($vendor_send_order_admin) $vendor_send_order = 0;
        $admin_send_order = 1;
        if ($config->admin_not_send_email_order_vendor_order && $vendor_send_order_admin && count($listVendors)) $admin_send_order = 0;

        do_action_ref_array('onBeforeSendEmailsOrder', array(&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order));
        
        //client message

        include_once(WOPSHOP_PLUGIN_DIR."/site/views/checkout/view.php");                   
        $view_name = "checkout";
        $view = WopshopFactory::getView('checkout');
        $view->setLayout("orderemail");
        $view->assign('client', 1);
        $view->assign('show_customer_info', 1);
        $view->assign('show_weight_order', 1);
        $view->assign('show_total_info', 1);
        $view->assign('show_payment_shipping_info', 1);
        $view->assign('config_fields', $config_fields);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('order_email_descr', $order_email_descr);
        $view->assign('order_email_descr_end', $order_email_descr_end);
        $view->assign('config', $config);
        $view->assign('order', $order);
        $view->assign('products', $order->products);
        $view->assign('show_percent_tax', $show_percent_tax);
        $view->assign('hide_subtotal', $hide_subtotal);
        $view->assign('noimage', $config->noimage);
        $view->assign('text_total',$text_total);
        $view->assign('liveurlhost',$liveurlhost);
        do_action_ref_array('onBeforeCreateTemplateOrderMail', array(&$view));
        $message_client = $view->loadTemplate();

        //admin message
        $view_name = "checkout";
        $view = WopshopFactory::getView('checkout');
        $view->setLayout("orderemail");
        $view->assign('client', 0);
        $view->assign('show_customer_info', 1);
        $view->assign('show_weight_order', 1);
        $view->assign('show_total_info', 1);
        $view->assign('show_payment_shipping_info', 1);
        $view->assign('config_fields', $config_fields);
        $view->assign('order_email_descr', $order_email_descr);
        $view->assign('order_email_descr_end', $order_email_descr_end);
        $view->assign('count_filed_delivery', $count_filed_delivery);
        $view->assign('config', $config);
        $view->assign('order',$order);
        $view->assign('products', $order->products);
        $view->assign('show_percent_tax', $show_percent_tax);
        $view->assign('hide_subtotal', $hide_subtotal);
        $view->assign('noimage', $config->noimage);
        $view->assign('text_total',$text_total);
        $view->assign('liveurlhost',$liveurlhost);
        do_action_ref_array('onBeforeCreateTemplateOrderMail', array(&$view));
        $message_admin = $view->loadTemplate();
        
        //vendors messages or order
        if ($vendors_send_message || $vendor_send_order){
            foreach($listVendors as $k=>$datavendor){
                if ($vendors_send_message){
                    $show_customer_info = 0;
                    $show_weight_order = 0;
                    $show_total_info = 0;
                    $show_payment_shipping_info = 0;
                }
                if ($vendor_send_order){
                    $show_customer_info = 1;
                    $show_weight_order = 0;
                    $show_total_info = 0;
                    $show_payment_shipping_info = 1;
                }
                $vendor_order_items = $order->getVendorItems($datavendor->id);
                $view_name = "checkout";
                $view = WopshopFactory::getView('checkout');
                $view->setLayout("orderemail");
                $view->assign('client', 0);
                $view->assign('show_customer_info', $show_customer_info);
                $view->assign('show_weight_order', $show_weight_order);
                $view->assign('show_total_info', $show_total_info);
                $view->assign('show_payment_shipping_info', $show_payment_shipping_info);
                $view->assign('config_fields', $config_fields);
                $view->assign('count_filed_delivery', $count_filed_delivery);
                $view->assign('order_email_descr', $order_email_descr);
                $view->assign('order_email_descr_end', $order_email_descr_end);
                $view->assign('config', $config);
                $view->assign('order', $order);
                $view->assign('products', $vendor_order_items);
                $view->assign('show_percent_tax', $show_percent_tax);
                $view->assign('hide_subtotal', $hide_subtotal);
                $view->assign('noimage',$config->noimage);
                $view->assign('text_total',$text_total);
                $view->assign('liveurlhost',$liveurlhost);
                $view->assign('show_customer_info',$vendor_send_order);
                do_action_ref_array('onBeforeCreateTemplateOrderPartMail', array(&$view));
                $message_vendor = $view->loadTemplate();
                $listVendors[$k]->message = $message_vendor;
            }
        }
		$pdfsend = 1;
        if ($config->send_invoice_manually && !$manuallysend) $pdfsend = 0;
        
        if ($pdfsend && ($config->order_send_pdf_client || $config->order_send_pdf_admin)){
            include_once($file_generete_pdf_order);
            $order->setInvoiceDate();
            $order->pdf_file = wopshop_generatePdf($order);
            $order->insertPDF();
        }

        
        //send mail client
		if ($order->email){
			$headers = array();
            $attachments = array();
			$subject = sprintf(WOPSHOP_NEW_ORDER, $order->order_number, $order->f_name." ".$order->l_name);
			$message = $message_client;
			$to = $order->email;
			$headers[] = 'From: ' . wp_specialchars_decode(get_bloginfo(), ENT_QUOTES) . ' <' . get_option('admin_email') . ">\r\n";
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			if ($pdfsend && $config->order_send_pdf_client){
				$attachments[] = $config->pdf_orders_path."/".$order->pdf_file;
			}
			do_action_ref_array('onBeforeSendOrderEmailClient', array(&$order, &$manuallysend, &$pdfsend, &$attachments));
			wp_mail( $to, $subject, $message, $headers, $attachments );
        }
		
        //send mail admin
        if ($admin_send_order){
			$headers = array();
            $attachment = array();
			$subject = sprintf(WOPSHOP_NEW_ORDER, $order->order_number, $order->f_name." ".$order->l_name);
			$message = $message_admin;
			$to = explode(',',$config->contact_email);
			$headers[] = 'From: ' . wp_specialchars_decode(get_bloginfo(), ENT_QUOTES) . ' <' . get_option('admin_email') . ">\r\n";
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			if ($pdfsend && $config->order_send_pdf_admin){
				$attachment[] = $config->pdf_orders_path."/".$order->pdf_file;
			}
			do_action_ref_array('onBeforeSendOrderEmailAdmin', array(&$order, &$manuallysend, &$pdfsend, &$attachment));
			wp_mail( $to, $subject, $message, $headers, $attachment );
                    
        }

        //send mail vendors
        if ($vendors_send_message || $vendor_send_order){
			$headers = array();
			$headers[] = 'From: ' . wp_specialchars_decode(get_bloginfo(), ENT_QUOTES) . ' <' . get_option('admin_email') . ">\r\n";
			$headers[] = 'Content-Type: text/html; charset=UTF-8';			
            foreach($listVendors as $k=>$vendor){
                do_action_ref_array('onBeforeSendOrderEmailVendor', array(&$order, &$manuallysend, &$pdfsend, &$vendor, &$vendors_send_message, &$vendor_send_order));
                wp_mail( $vendor->email, sprintf(WOPSHOP_NEW_ORDER_V, $order->order_number, ""), $vendor->message, $headers);
            }
        }

        //vendor send order
        if ($vendor_send_order_admin){
			$headers = array();
            $attachment = array();
			$headers[] = 'From: ' . wp_specialchars_decode(get_bloginfo(), ENT_QUOTES) . ' <' . get_option('admin_email') . ">\r\n";
			$headers[] = 'Content-Type: text/html; charset=UTF-8';				
            foreach($listVendors as $k=>$vendor){
                if ($pdfsend && $config->order_send_pdf_admin){
                    $attachment[] = $config->pdf_orders_path."/".$order->pdf_file;
                }
                do_action_ref_array('onBeforeSendOrderEmailVendorOrder', array(&$order, &$manuallysend, &$pdfsend, &$vendor, &$vendors_send_message, &$vendor_send_order, &$attachment));
				wp_mail( $vendor->email, sprintf(WOPSHOP_NEW_ORDER, $order->order_number, $order->f_name." ".$order->l_name), $message_admin, $headers, $attachment );
            }
        }

        do_action_ref_array('onAfterSendEmailsOrder', array(&$order));
    }
    
    function changeStatusOrder($order_id, $status, $sendmessage = 1){       
        $config = WopshopFactory::getConfig();
        $restext = '';

        do_action_ref_array('onBeforeChangeOrderStatus', array(&$order_id, &$status, &$sendmessage, &$restext));
            
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        $order->order_status = $status;
        $order->order_m_date = current_time('Y-m-d H:i:s');
        $order->store();
        
        $vendorinfo = $order->getVendorInfo();

        $order_status = WopshopFactory::getTable('orderstatus');
        $order_status->load($status);
        
        if ($config->order_stock_removed_only_paid_status){
            $product_stock_removed = (in_array($status, $config->payment_status_enable_download_sale_file));
        }else{
            $product_stock_removed = (!in_array($status, $config->payment_status_return_product_in_stock));
        }
        
        if ($order->order_created && !$product_stock_removed && $order->product_stock_removed==1){
            $order->changeProductQTYinStock("+");            
        }
        
        if ($order->order_created && $product_stock_removed && $order->product_stock_removed==0){
            $order->changeProductQTYinStock("-");            
        }
        
        $order_history = WopshopFactory::getTable('orderhistory');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $status;
        $order_history->status_date_added = current_time('Y-m-d H:i:s');
        $order_history->customer_notify = 1;
        $order_history->comments = $restext;
        $order_history->store();
        
        $name = "name_".$config->cur_lang;
        
        $uri = WopshopUri::getInstance();
        $liveurlhost = $uri->toString( array("scheme",'host', 'port'));
        $order_details_url = $liveurlhost.esc_url(wopshopSEFLink('controller=user&task=order&order_id='.$order_id,1));
        if ($order->user_id==-1){
            $order_details_url = '';
        }
        
        $message = $this->getMessageChangeStatusOrder($order, $order_status->$name, $vendorinfo, $order_details_url);

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
                
        if ($sendmessage){
            //message client
            $subject = sprintf(WOPSHOP_ORDER_STATUS_CHANGE_SUBJECT, $order->order_number);
            $to = $order->email;
            $headers[] = 'From: '.  get_bloginfo().' <'.get_option('admin_email') . ">\r\n";
            wp_mail( $to, $subject, $message, $headers );
            
            //message admin
            if ($admin_send_order){
                $to = $config->contact_email;
                wp_mail( $to, $subject, $message, $headers );
            }
            
            //message vendors
            if ($vendors_send_message || $vendor_send_order){
				$headers = array();
				$headers[] = 'From: ' . wp_specialchars_decode(get_bloginfo(), ENT_QUOTES) . ' <' . get_option('admin_email') . ">\r\n";
				$headers[] = 'Content-Type: text/html; charset=UTF-8';				
                foreach($listVendors as $k=>$datavendor){
					do_action_ref_array('onBeforeSendMailChangeOrderStatusVendor', array(&$order_id, &$status, &$sendmessage, &$order));
					wp_mail( $datavendor->email, WOPSHOP_ORDER_STATUS_CHANGE_TITLE, $message, $headers);					
                }
            }
        }
        do_action_ref_array('onAfterChangeOrderStatus', array(&$order_id, &$status, &$sendmessage));
    return 1;
    }
    
    function getMessageChangeStatusOrder($order, $newstatus, $vendorinfo, $order_details_url, $comments=''){
        $config = WopshopFactory::getConfig();
        include_once(WOPSHOP_PLUGIN_DIR."site/views/checkout/view.php");
        $view = WopshopFactory::getView('order');
        $view->setLayout("statusorder");
        $view->assign('order', $order);
        $view->assign('order_status', $newstatus);
        $view->assign('vendorinfo', $vendorinfo);
        $view->assign('order_detail', $order_details_url);
        $view->assign('comment', $comments);
        do_action_ref_array('onBeforeCreateMailOrderStatusView', array(&$view));
        return $view->loadTemplate();
    }

	public function cancelPayOrder($order_id){
        $order = WopshopFactory::getTable('order');
        $order->load($order_id);
        $pm_method = WopshopFactory::getTable('paymentmethod');
        $pm_method->load($order->payment_method_id);
        $pmconfigs = $pm_method->getConfigs();
        $status = $pmconfigs['transaction_cancel_status'];
        if (!$status) $status = $pmconfigs['transaction_failed_status'];
        if ($order->order_created) $sendmessage = 1; else $sendmessage = 0;
        $this->changeStatusOrder($order_id, $status, $sendmessage);
        do_action_ref_array('onAfterCancelPayOrderWshopCheckout', array(&$order_id, $status, $sendmessage));
    }

	public function setMaxStep($step){
        $session = WopshopFactory::getSession();
        $wop_shop_max_step = $session->get('wop_shop_max_step');
        if (!isset($wop_shop_max_step)) $session->set('wop_shop_max_step', 2);
        $wop_shop_max_step = $session->get('wop_shop_max_step');
        $session->set('wop_shop_max_step', $step);
        do_action_ref_array('onAfterSetMaxStepWshopCheckout', array(&$step));
    }

	public function checkStep($step){
        $config = WopshopFactory::getConfig();
        $mainframe = WopshopFactory::getApplication();
        $session = WopshopFactory::getSession();
        
        if ($step<10){
            if (!$config->shop_user_guest){
                wopshopCheckUserLogin();
            }
            
            $_cart = WopshopFactory::getModel('cart');
            $cart = $_cart->load();

            if ($_cart->getCountProduct() == 0){
                $mainframe->redirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
                exit();
            }

            if ($config->min_price_order && ($cart->getPriceProducts() < ($config->min_price_order * $config->currency_value) )){
                wopshopAddMessage(sprintf(WOPSHOP_ERROR_MIN_SUM_ORDER, wopshopFormatprice($config->min_price_order * $config->currency_value)), 'error');
                $mainframe->redirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
                exit();
            }
            
            if ($config->max_price_order && ($cart->getPriceProducts() > ($config->max_price_order * $config->currency_value) )){
                wopshopAddMessage(sprintf(WOPSHOP_ERROR_MAX_SUM_ORDER, wopshopFormatprice($config->max_price_order * $config->currency_value)), 'error');
                $mainframe->redirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
                exit();
            }
        }

        if ($step>2){
            $wop_shop_max_step = $session->get("wop_shop_max_step");
            if (!$wop_shop_max_step){
                $session->set('wop_shop_max_step', 2);
                $wop_shop_max_step = 2;
            }
            if ($step > $wop_shop_max_step){
                if ($step==10){
                    $mainframe->redirect(esc_url(wopshopSEFLink('controller=cart&task=view',1,1)));
                }else{
                    wopshopAddMessage(WOPSHOP_ERROR_STEP, 'error');
                    $mainframe->redirect(esc_url(wopshopSEFLink('controller=checkout&task=step2',1,1, $config->use_ssl)));
                }
                exit();
            }
        }
    }

	public function showCheckoutNavigation($step){
        $config = WopshopFactory::getConfig();
        if (!$config->ext_menu_checkout_step && in_array($step, array('0', '1'))){
            return '';
        }
        if ($config->step_4_3){
            $array_navigation_steps = array('0'=>WOPSHOP_CART, '1'=>WOPSHOP_LOGIN, '2'=>WOPSHOP_STEP_ORDER_2, '4'=>WOPSHOP_STEP_ORDER_4, '3'=>WOPSHOP_STEP_ORDER_3, '5'=>WOPSHOP_STEP_ORDER_5);
        }else{
            $array_navigation_steps = array('0'=>WOPSHOP_CART, '1'=>WOPSHOP_LOGIN, '2' => WOPSHOP_STEP_ORDER_2, '3' => WOPSHOP_STEP_ORDER_3, '4' => WOPSHOP_STEP_ORDER_4, '5' => WOPSHOP_STEP_ORDER_5);
        }
        $output = array();
        $cssclass = array();
        if (!$config->ext_menu_checkout_step){
            unset($array_navigation_steps['0']);
            unset($array_navigation_steps['1']);
        }
        if ($config->shop_user_guest==2){
            unset($array_navigation_steps['1']);    
        }
        if ($config->without_shipping || $config->hide_shipping_step){
            unset($array_navigation_steps['4']);
        }
        if ($config->without_payment || $config->hide_payment_step){
            unset($array_navigation_steps['3']);
        }

        foreach($array_navigation_steps as $key=>$value){
            if ($key == 0){
                $url = esc_url(wopshopSEFLink('controller=cart', 1, 0));
            }elseif($key == 1){
                $url = esc_url(wopshopSEFLink('controller=user&task=login', 1, 0, $config->use_ssl));
            }else{
                $url = esc_url(wopshopSEFLink('controller=checkout&task=step'.$key,0,0,$config->use_ssl));
            }
            if ($key < $step && !($config->step_4_3 && $key==3 && $step==4) || ($config->step_4_3 && $key==4 && $step==3)){
                $output[$key] = '<span class="not_active_step"><a href="'.esc_url($url).'">'.$value.'</a></span>';
                $cssclass[$key] = "prev";
            }else{
                if ($key == $step){
                    $output[$key] = '<span id="active_step"  class="active_step">'.$value.'</span>';
                    $cssclass[$key] = "active";
                }else{
                    $output[$key] = '<span class="not_active_step">'.$value.'</span>';
                    $cssclass[$key] = "next";
                }
            }
        }
        do_action_ref_array('onBeforeDisplayCheckoutNavigator', array(&$output, &$array_navigation_steps, &$step));
        include_once(WOPSHOP_PLUGIN_DIR ."site/views/checkout/view.php");
        $view = WopshopFactory::getView('checkout');
        $view->setLayout("menu");
        $view->assign('steps', $output);
        $view->assign('step', $step);
        $view->assign('cssclass', $cssclass);
        $view->assign('array_navigation_steps', $array_navigation_steps);
        do_action_ref_array('onAfterDisplayCheckoutNavigator', array(&$view));
        return $view->loadTemplate();
    }

	public function deleteSession(){
        $session = WopshopFactory::getSession();        
        $session->set('check_params', null);
        $session->set('cart', null);
        $session->set('jhop_max_step', null);        
        $session->set('wshop_price_shipping_tax_percent', null);
        $session->set('wshop_price_shipping', null);
        $session->set('wshop_price_shipping_tax', null);
        $session->set('pm_params', null);
        $session->set('payment_method_id', null);
        $session->set('wshop_payment_price', null);
        $session->set('shipping_method_id', null);
        $session->set('sh_pr_method_id', null);
        $session->set('wshop_end_order_id', null);
        $session->set('wshop_send_end_form', null);
        $session->set('show_pay_without_reg', 0);
        $session->set('checkcoupon', 0);
        do_action_ref_array('onAfterDeleteDataOrder', array(&$this));
    }
}