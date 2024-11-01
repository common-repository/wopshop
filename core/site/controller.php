<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WshopController extends WopshopWobject {

    protected $default_view;
    protected $name;
    protected $redirect;

    function getModel($name){
        if (file_exists(WOPSHOP_PLUGIN_DIR ."/site/models/".strtolower($name).".php")){
            include_once(WOPSHOP_PLUGIN_DIR ."/site/models/".strtolower($name).".php");
            $modelname = $name."WshopModel";
            if (class_exists($modelname)){
                $obj = new $modelname();
                return $obj;
            }         
        }
    }
    
    function getView($name){
        return WopshopFactory::getView($name);     
    } 

    public function redirect() {
        if ($this->redirect) {
            $app = WopshopFactory::getApplication();
            $app->redirect($this->redirect, $this->message, $this->messageType);
        }

        return false;
    }

    public function addMetaTag($id, $content) {
        $app = WopshopFactory::getApplication();
        if(!isset($app->tags[$id])){
            $app->tags[$id] = '';
        }
        if (isset($content) && !$app->tags[$id] && $content){
            $app->tags[$id] = $content;
        }
        return true;
    }

    function setRedirect($url){
        if (headers_sent()) {
            echo "<script>document.location.href='".esc_url_raw( $url )."';</script>\n";
        } else {            
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( 'Location: ' . esc_url_raw( $url ) );
            die();
        }
    }     
}