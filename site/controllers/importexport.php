<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include_once(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/iecontroller.php");
class WopshopImportexportController extends WshopController{
    
    public function __construct(){
        parent::__construct();
    }
    
    function start(){
        $config = WopshopFactory::getConfig();
        $key = WopshopRequest::getVar("key", '');
        if ($key && $key!=$config->securitykey){
            die();
        }
        
        $_GET['noredirect'] = 1; $_POST['noredirect'] = 1; $_REQUEST['noredirect'] = 1;

        global $wpdb;
        $time = time();
        $query = "SELECT * FROM `".$wpdb->prefix."wshop_import_export` WHERE `steptime`>0 and (endstart + steptime < $time)  ORDER BY id";

        $list = $wpdb->get_results($query);

        foreach($list as $ie){
            $alias = $ie->alias;
            if (!file_exists(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/".$alias."/".$alias.".php")){
                print sprintf(WOPSHOP_ERROR_FILE_NOT_EXIST, "/importexport/".$alias."/".$alias.".php");
                return 0;
            }
            include_once(WOPSHOP_PLUGIN_ADMIN_DIR."/importexport/".$alias."/".$alias.".php");
            $classname    = 'Ie'.$alias;
            $controller   = new $classname($ie->id);
            $controller->set('ie_id', $ie->id);
            $controller->set('alias', $alias);
            $controller->save();
            print $alias."\n";
        }
        
        die();
    }
}