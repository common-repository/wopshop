<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class wopshop_pm_debit extends WopshopPaymentRoot{
    
    function showPaymentForm($params, $pmconfigs){
        if (!isset($params['acc_holder'])) $params['acc_holder'] = '';
        if (!isset($params['bank_iban'])) $params['bank_iban'] = '';
        if (!isset($params['bank_bic'])) $params['bank_bic'] = '';
        if (!isset($params['bank'])) $params['bank'] = '';
    	include(dirname(__FILE__)."/paymentform.php");
    }

    function getDisplayNameParams(){
        $names = array('acc_holder' => WOPSHOP_ACCOUNT_HOLDER, 'bank_iban' => WOPSHOP_IBAN, 'bank_bic' => WOPSHOP_BIC_BIC, 'bank' => WOPSHOP_BANK );
        return $names;
    }
}
?>