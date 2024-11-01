<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WshopAdminRouter{

    public static function route(){
        $request = new WshopAdminRequest();
        $controllerPath = $request->getController();
        $task = $request->getTask();
        //$folder = $request->getFolder();       
        $_controller = $controllerPath . 'WshopAdminController';
        do_action_ref_array('onAfterLoadShopParamsAdmin', array());
//        if (file_exists(WOPSHOP_PLUGIN_ADMIN_DIR . '/controllers/' . $folder . $controllerPath . '.php')){
//            require_once WOPSHOP_PLUGIN_ADMIN_DIR . '/controllers/' . $folder . $controllerPath . '.php';
//        } else {
        if (file_exists(WOPSHOP_PLUGIN_ADMIN_DIR . '/controllers/' .  $controllerPath . '.php')){
            require_once WOPSHOP_PLUGIN_ADMIN_DIR . '/controllers/' . $controllerPath . '.php';
        } else {
            wp_die('No controller file found');
        }
        do_action_ref_array('onAfterGetControllerAdmin', array());
		if(!$task)
			$task = 'display';
		if (class_exists($_controller)){
			$controller = new $_controller();
			if((int)method_exists($_controller, $task)){
				 call_user_func(array($controller, $task));
			}else{
				call_user_func(array($controller, 'display'));
			}
		}
    }
}