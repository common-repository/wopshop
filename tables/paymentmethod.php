<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class PaymentMethodWshopTable extends WshopTable {

    function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_payment_method', 'payment_id');        
    }
    
    function loadFromClass($class){
        global $wpdb;
        $query = "SELECT payment_id FROM `$this->_tbl` WHERE payment_class='".  esc_sql($class)."'";
        extract(wopshop_add_trigger(get_defined_vars(), "query"));
        $id = $this->_db->get_var($query);
        return $this->load($id);
    }

    function getAllPaymentMethods($publish = 1, $shipping_id = 0){
        $config = WopshopFactory::getConfig();
        $query_where = ($publish)?("WHERE payment_publish = '1'"):("");
        $query = "SELECT payment_id, `name_".$config->cur_lang."` as name, `description_".$config->cur_lang."` as description , payment_code, payment_class, scriptname, payment_publish, payment_ordering, payment_params, payment_type, price, price_type, tax_id, image FROM `$this->_tbl` $query_where ORDER BY payment_ordering";
        extract(wopshop_add_trigger(get_defined_vars(), "query"));
        $rows = $this->_db->get_results($query);
        if ($shipping_id && $config->step_4_3){
            $sh = WopshopFactory::getTable('shippingMethod');            
            $sh->load($shipping_id);
            $payments = $sh->getPayments();
            if (count($payments)>0){
                foreach($rows as $k=>$v){
                    if (!in_array($v->payment_id, $payments)) unset($rows[$k]);
                }
                $rows = array_values($rows);
            }
        }
    return $rows;
    }

    /**
    * get id payment for payment_class
    */
    function getId(){
        $query = "SELECT payment_id FROM `$this->_tbl` WHERE payment_class = '".  esc_sql($this->class)."'";
        extract(wopshop_add_trigger(get_defined_vars(), "query"));
        return $this->_db->get_var($query);
    }
    
    function setCart(&$cart){
        $this->_cart = $cart;
    }
    
    function getCart(){
        return $this->_cart;
    }
    
    function getPrice(){
        $config = WopshopFactory::getConfig();
        if ($this->price_type==2){
            $cart = $this->getCart();
            $price = $cart->getSummForCalculePlusPayment() * $this->price / 100;
            if ($config->display_price_front_current){
                $price = wopshopGetPriceCalcParamsTax($price, $this->tax_id, $cart->products);
            }
        }else{
            $cart = $this->getCart();
            $price = $this->price * $config->currency_value; 
            $price = wopshopGetPriceCalcParamsTax($price, $this->tax_id, $cart->products);
        }
        do_action_ref_array('onAfterGetPricePaymant', array(&$this, &$price));        
        return $price;
    }
    
    function getTax(){        
        $taxes = WopshopFactory::getAllTaxes();        
        return $taxes[$this->tax_id];
    }
    
    function calculateTax($price = 0){
        $config = WopshopFactory::getConfig();
        if (!$price){
            $price = $this->getPrice();
        }
        $pricetax = wopshopGetPriceTaxValue($price, $this->getTax(), $config->display_price_front_current);
        return $pricetax;
    }
    
    function getPriceForTaxes($price){
        if ($this->tax_id==-1){
            $cart = $this->getCart();
            $prodtaxes = wopshopGetPriceTaxRatioForProducts($cart->products);
            $prices = array();
            foreach($prodtaxes as $k=>$v){
                $prices[$k] = $price*$v;
            }
        }else{
            $prices = array();
            $prices[$this->getTax()] = $price;
        }
    return $prices;
    }
    
    function calculateTaxList($price){
        $cart = $this->getCart();
        $config = WopshopFactory::getConfig();
        if ($this->tax_id==-1){
            $prodtaxes = wopshopGetPriceTaxRatioForProducts($cart->products);
            $prices = array();
            foreach($prodtaxes as $k=>$v){
                $prices[] = array('tax'=>$k, 'price'=>$price*$v);
            }
            $taxes = array();
            if ($config->display_price_front_current==0){
                foreach($prices as $v){
                    $taxes[$v['tax']] = $v['price']*$v['tax']/(100+$v['tax']);
                }
            }else{
                foreach($prices as $v){
                    $taxes[$v['tax']] = $v['price']*$v['tax']/100;
                }
            }    
        }else{
            $taxes = array();
            $taxes[$this->getTax()] = $this->calculateTax($price);
        }        
    return $taxes;
    }
    
    /**
    * static
    * get config payment for classname
    */
    function getConfigsForClassName($classname) {
        $query = "SELECT payment_params FROM `$this->_tbl` WHERE payment_class = '".  esc_sql($classname)."'";
        extract(wopshop_add_trigger(get_defined_vars(), "query"));
        $params_str = $this->_db->get_row($query);
        $parseString = new WopshopParseString($params_str);
        $params = $parseString->parseStringToParams();
        return $params;
    }
    
    /**
    * get config    
    */
    function getConfigs(){
        $parseString = new WopshopParseString($this->payment_params);
        $params = $parseString->parseStringToParams();
        return $params;
    }
    
    function check(){
        if ($this->payment_class==""){
            $this->setError("Alias Empty");
            return 0;
        }
        return 1;
    }
	
    function getPaymentSystemData($script=''){
        $config = WopshopFactory::getConfig();
        if ($script==''){
            if ($this->scriptname!=''){
                $script = $this->scriptname;
            }else{
                $script = $this->payment_class;
            }
        }else{
            $script = str_replace(array('.','/'),'', $script);
        }
        $data = new stdClass();

        if (!file_exists($config->path.'payments/'.$script."/".$script.'.php')){
            $data->paymentSystemVerySimple = 1;
            $data->paymentSystemError = 0;
            $data->paymentSystem = null;
        }else{
            include_once($config->path.'payments/'.$script."/".$script.'.php');

            if (!class_exists($script)){
                $data->paymentSystemVerySimple = 0;
                $data->paymentSystemError = 1;
                $data->paymentSystem = null;
            }else{
                $data->paymentSystemVerySimple = 0;
                $data->paymentSystemError = 0;
                $data->paymentSystem = new $script();
                $data->paymentSystem->setPmMethod($this);
            }
        }

    return $data;
    }

    function loadPaymentForm($payment_system, $params, $pmconfig){
        ob_start();
        $payment_system->showPaymentForm($params, $pmconfig);
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
    }
}