<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
* @class        WshopAddon
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
abstract class WopshopShippingFormRoot{
    
    var $_errormessage = "";
    
    abstract function showForm($shipping_id, $shippinginfo, $params);
    
    function check($params, $sh_method){
        return 1;
    }
    
    /**
    * Set message error check
    */
    function setErrorMessage($msg){
        $this->_errormessage = $msg;
    }
    
    /**
    * Get message error check
    */
    function getErrorMessage(){
        return $this->_errormessage;
    }
    
    /**
    * list display params name shipping saved to order
    */
    function getDisplayNameParams(){
        return array();
    }
    
    /**
    * exec before mail send
    */
    function prepareParamsDispayMail(&$order, &$sh_method){
    }

}