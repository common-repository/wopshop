<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists("WshopSite")) {
    class WshopSite {

        public function __construct() {
            $config = WopshopFactory::getConfig();
            WopshopFactory::loadJQuery();
            WopshopFactory::loadCssFiles();
            WopshopFactory::loadJsFiles();

            $controllerPath = WopshopRequest::getVar('controller', 'products');
            $task = WopshopRequest::getVar('task', 'display');
            $_controller = 'Wopshop' . ucfirst($controllerPath) . 'Controller';

            $user = wp_get_current_user();
            $session = WopshopFactory::getSession();
            $wshop_update_all_price = $session->get('wshop_update_all_price');
            $wshop_prev_user_id = $session->get('wshop_prev_user_id');
            if ($wshop_update_all_price || ($wshop_prev_user_id != $user->ID)){
                wopshopUpdateAllprices();
                $session->set('wshop_update_all_price', 0);
            }
            $session->set("wshop_prev_user_id", $user->ID);
            do_action_ref_array('onAfterLoadShopParams', array());
            
            if (file_exists(WOPSHOP_PLUGIN_DIR . '/site/controllers/' .  $controllerPath . '.php')){
                require_once WOPSHOP_PLUGIN_DIR . '/site/controllers/' . $controllerPath . '.php';
                
                if (class_exists($_controller) && (int)method_exists($_controller, $task)) {
                    $controller = new $_controller();
                    call_user_func(array($controller, $task));
                } else {
                    global $wp_query;
                    $wp_query->set_404();
                    status_header(404);
                    get_template_part(404); exit();
                }
            } else {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                get_template_part(404); exit();
            }
			
//            if ($controllerPath != 'content' && !wopshopCompareX64(wopshopReplaceWWW(wopshopGetHttpHost()), $config->licensekod)){
//                print $config->copyrightText;
//            }
        }
    }
      
    new WshopSite();
}