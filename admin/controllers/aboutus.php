<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AboutusWshopAdminController extends WshopAdminController {
    public function __construct() {
        parent::__construct();
    }
    
    public function display() {
        $view = $this->getView('panel');
        $view->setLayout('info');
        $view->version        = get_option('wopshop_version');
        $view->tmp_html_start = '';
        $view->tmp_html_end   = '';
		do_action_ref_array( 'onBeforeDisplayAboutus', array( &$view ) );
        $view->display();
    }
}