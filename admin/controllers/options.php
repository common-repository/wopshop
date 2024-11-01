<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class OptionsWshopAdminController extends WshopAdminController {

    public function __construct() {
        parent::__construct();
    }
    
    public function display() {
        //WopshopFactory::loadNunitoFonts();
        $view = $this->getView('options');
        $menu = wopshopGetItemsOptionPanelMenu();
        unset($menu['units']);
        unset($menu['vendors']);
        $view->items = $menu;
        $view->display();
    }
}