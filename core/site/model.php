<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class WshopModel extends WopshopWobject {
    public $lang;
 
    public function __construct() {
        $this->lang = WopshopFactory::getConfig()->getLang();
    }
}