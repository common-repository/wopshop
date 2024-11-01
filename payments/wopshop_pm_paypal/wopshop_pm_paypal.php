<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class wopshop_pm_paypal extends WopshopPaymentRoot{
    private $curlopt_sslversion = 6;
    
    public function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }

	//function call in admin
	public function showAdminFormParams($params){
        $array_params = array('testmode', 'email_received', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status', 'rm1', 'checkdatareturn', 'address_override', 'notifyurlsef');
        foreach ($array_params as $key){
            if (!isset($params[$key])) {
                $params[$key] = '';
            }
        }
        if (!isset($params['address_override'])) {
            $params['address_override'] = 0;
        }
	  
        $orders = WopshopFactory::getAdminModel('orders'); //admin model
        include(dirname(__FILE__)."/adminparamsform.php");
	}

	public function checkTransaction($pmconfigs, $order, $act){
        $config = WopshopFactory::getConfig();
        $paypall_response = !empty($_POST) ? $_POST : false;
        $paypal_adr = "https://www.paypal.com/cgi-bin/webscr";
        if ($pmconfigs['testmode']){
            $paypal_adr = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }
        $post = WopshopRequest::get('post');
        
		$order->order_total = $this->fixOrderTotal($order);
        $opending = 0;
        if ($order->order_total != $post['mc_gross'] || $order->currency_code_iso != $post['mc_currency']){
            $opending = 1;
        }
        
        $payment_status = trim($post['payment_status']);
        $transaction = $post['txn_id'];
        $transactiondata = array('txn_id'=>$post['txn_id'],'payer_email'=>$post['payer_email'], 'mc_gross'=>$post['mc_gross'], 'mc_currency'=>$post['mc_currency'], 'payment_status'=>$post['payment_status']);
        
        if (strtolower($pmconfigs['email_received']) != strtolower($post['business']) && strtolower($pmconfigs['email_received']) != strtolower($post['receiver_email'])){
            return array(0, 'Error email received. Order ID '.$order->order_id, $transaction, $transactiondata);
        }

        //$req = 'cmd=_notify-validate';
        $req = array('cmd' => '_notify-validate');
        $req += stripslashes_deep($paypall_response);
        
        $params = array(
            'body' => $req,
            'sslverify' => false,
            'timeout' => 60,
            'httpversion' => '1.1',
            'compress' => false,
            'decompress' => false,
            'user-agent' => 'Wopshop PayPal'
        );
        
        $res = wp_remote_post($paypal_adr, $params);
        
        if (strstr($res['body'], 'VERIFIED')) {
            if ($payment_status == 'Completed'){
                if ($opending){
                    wopshopSaveToLog("payment.log", "Status pending. Order ID ".$order->order_id.". Error mc_gross or mc_currency.");
                    return array(2, "Status pending. Order ID ".$order->order_id, $transaction, $transactiondata);
                }else{
                    return array(1, '', $transaction, $transactiondata);
                }
            } elseif ($payment_status == 'Pending') {
                wopshopSaveToLog("payment.log", "Status pending. Order ID ".$order->order_id.". Reason: ".sanitize_text_field($_POST['pending_reason']));
                return array(2, trim(sanitize_text_field($_POST['pending_reason'])), $transaction, $transactiondata);
            } else {
                return array(3, "Status $payment_status. Order ID ".$order->order_id, $transaction, $transactiondata);
            }
        }else{
            //if (strcmp ($res, "INVALID") == 0)
            wopshopSaveToLog("payment.log", "Invalid response. Order ID ".$order->order_id.". Reason: ".$res['body']);
            return array(0, 'Invalid response. Order ID '.$order->order_id, $transaction, $transactiondata);
        }

	}

	public function showEndForm($pmconfigs, $order){
        $pm_method = $this->getPmMethod();
        $item_name = sprintf(WOPSHOP_PAYMENT_NUMBER, $order->order_number);
        
        if ($pmconfigs['testmode']){
            $host = "www.sandbox.paypal.com";
        } else {
            $host = "www.paypal.com";
        }
        $email = $pmconfigs['email_received'];
        $address_override = (int)$pmconfigs['address_override'];
        
        $notify_url = esc_url(wopshopSEFLink("controller=checkout&task=step7&act=notify&js_paymentclass=".$pm_method->payment_class."&no_lang=1"));
        $return = esc_url(wopshopSEFLink("controller=checkout&task=step7&act=return&js_paymentclass=".$pm_method->payment_class));
        $cancel_return = esc_url(wopshopSEFLink("controller=checkout&task=step7&act=cancel&js_paymentclass=".$pm_method->payment_class));
		
        $_country = WopshopFactory::getTable('country');
        $_country->load($order->d_country);
        $country = $_country->country_code_2;
        $order->order_total = $this->fixOrderTotal($order);
        if (isset($pmconfigs['rm1']) && $pmconfigs['rm1']==1){
            $rm = 1;
        }else{
            $rm = 2;
        }
		$paypal_adr = 'https://'.$host.'/cgi-bin/webscr';
        ?>
        <html>
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />            
            </head>
            <body>
            <form id="paymentform" action="<?php echo esc_url($paypal_adr)?>" name = "paymentform" method = "post">
                <input type='hidden' name='cmd' value='_xclick'>
                <input type='hidden' name='business' value='<?php echo esc_attr($email)?>'>        
                <input type='hidden' name='notify_url' value='<?php echo esc_attr($notify_url)?>'>
                <input type='hidden' name='return' value='<?php echo esc_attr($return)?>'>
                <input type='hidden' name='cancel_return' value='<?php echo esc_attr($cancel_return)?>'>
                <input type='hidden' name='rm' value='<?php echo esc_attr($rm)?>'>
                <input type='hidden' name='handling' value='0.00'>
                <input type='hidden' name='tax' value='0.00'>
                <input type='hidden' name='charset' value='utf-8'>
                <input type='hidden' name='no_shipping' value='1'>
                <input type='hidden' name='no_note' value='1'>
                <input type='hidden' name='item_name' value='<?php echo esc_attr($item_name);?>'>
                <input type='hidden' name='custom' value='<?php echo esc_attr($order->order_id)?>'>
                <input type='hidden' name='invoice' value='<?php echo esc_attr($order->order_id)?>'>
                <input type='hidden' name='amount' value='<?php echo esc_attr($order->order_total)?>'>
                <input type='hidden' name='currency_code' value='<?php echo esc_attr($order->currency_code_iso)?>'>
                <input type='hidden' name='address_override' value='<?php echo esc_attr($address_override)?>'>
                <input type='hidden' name='country' value='<?php echo esc_attr($country)?>'>
                <input type='hidden' name='first_name' value='<?php echo esc_attr($order->d_f_name)?>'>
                <input type='hidden' name='last_name' value='<?php echo esc_attr($order->d_l_name)?>'>  
                <input type='hidden' name='address1' value='<?php echo esc_attr($order->d_street)?>'>  
                <input type='hidden' name='city' value='<?php echo esc_attr($order->d_city)?>'>  
                <input type='hidden' name='state' value='<?php echo esc_attr($order->d_state)?>'>
                <input type='hidden' name='zip' value='<?php echo esc_attr($order->d_zip)?>'>
                <input type='hidden' name='night_phone_b' value='<?php echo esc_attr($order->d_phone)?>'>
                <input type='hidden' name='email' value='<?php echo esc_attr($order->email)?>'>
                <input type='hidden' name='bn' value='Wopshop_Cart_ECM'>
                </form>        
                <?php echo esc_html(WOPSHOP_REDIRECT_TO_PAYMENT_PAGE)?>
                <br>
                <script type="text/javascript">document.getElementById('paymentform').submit();</script>
            </body>
        </html>
        <?php
        die();
	}
    
    public function getUrlParams($pmconfigs){
        $params = array(); 
        $params['order_id'] =  WopshopRequest::getInt("custom");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        //$params['checkReturnParams'] = 0;
        $params['checkReturnParams'] = $pmconfigs['checkdatareturn'];
    
        return $params;
    }
    
	private function fixOrderTotal($order){
        $total = $order->order_total;
        if ($order->currency_code_iso == 'HUF'){
            $total = round($total);
        } else {
            $total = number_format($total, 2, '.', '');
        }
    
        return $total;
    }
}