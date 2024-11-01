<?php
class WshopAdminController {
    
    protected $model;
    protected $controller;
 
    public function __construct() {
        $this->model = $this->getNameModel();
    }
    
    public function getModel($name = null){
        $name = $name ? $name : $this->model;
        
        if (file_exists(WOPSHOP_PLUGIN_ADMIN_DIR ."/models/".strtolower($name).".php")){
            include_once(WOPSHOP_PLUGIN_ADMIN_DIR ."/models/".strtolower($name).".php");
            $modelname = $name."WshopAdminModel";
            if (class_exists($modelname)){
                $obj = new $modelname();
                return $obj;                   
            }         
        }
    }
    
    public function getView($name){
        if (file_exists(WOPSHOP_PLUGIN_ADMIN_DIR ."/views/".strtolower($name)."/view.php")){
            include_once(WOPSHOP_PLUGIN_ADMIN_DIR ."/views/".strtolower($name)."/view.php");
            $viewname = $name."WshopAdminView";
            if (class_exists($viewname)){
                $obj = new $viewname($name);
                return $obj;
            } else {
                wp_die('No View Class found');
            }            
        } else {
           wp_die('No View file found'); 
        }        
    }    
    
    public function setRedirect($url, $msg = null, $type = 'updated'){
        if ($msg !== null) {
            wopshopAddMessage($msg, $type);
        }
        if (headers_sent()) {
            echo "<script>document.location.href=' ".esc_url_raw( $url )." ';</script>\n";
        } else {            
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( 'Location: ' . esc_url_raw( $url ) );
        }
    }
    
    public function publish(){
        $cid = WopshopRequest::getVar('cid', array(), 'default', 'array');
        if(!count($cid)){
            $cid = WopshopRequest::getVar('rows', array(), 'default', 'array');
        }
        $this->getModel()->publish($cid, 1);
		$this->setRedirect($this->getUrlListItems());
    }
    
    public function unpublish(){
        $cid = WopshopRequest::getVar('cid', array(), 'default', 'array');
        if(!count($cid)){
            $cid = WopshopRequest::getVar('rows', array(), 'default', 'array');
        }        
        $this->getModel()->publish($cid, 0);
		$this->setRedirect($this->getUrlListItems());
    }
    
    public function delete(){
		$cid = WopshopRequest::getVar('rows', array(), 'default', 'array');
		$this->getAdminModel()->deleteList($cid);
		$this->setRedirect($this->getUrlListItems());
	}
    
    protected function getUrlListItems(){
        return "admin.php?page=wopshop-".$this->getNameController();
    }
    
    protected function getNameController(){
		if (empty($this->controller)){
			$r = null;
			preg_match('/(.*)WshopAdminController/i', get_class($this), $r);
			$this->controller = strtolower($r[1]);
		}
        
		return $this->controller;
	}
    
    protected function getNameModel(){
		if (empty($this->model)){
			$r = null;
			preg_match('/(.*)WshopAdminController/i', get_class($this), $r);
			$this->model = strtolower($r[1]);
		}
        
		return $this->model;
	}
    
    public function getAdminModel(){
        return WopshopFactory::getAdminModel($this->model);
    }   
    
    public function order(){
        $order = WopshopRequest::getVar("order");
		$cid = WopshopRequest::getInt("id");        
        $move = WopshopRequest::getInt("move");
        if ($order == 'up'){
            $move = -1;
        }
        if ($order == 'down'){
            $move = 1;
        }
        $this->getAdminModel()->order($cid, $move, $this->getOrderWhere());
		$this->setRedirect($this->getUrlListItems(), WOPSHOP_MESSAGE_SAVEOK);
    } 
    
    public function saveorder(){
        $cid = WopshopRequest::getVar('rows', array());
        $order = WopshopRequest::getVar('order', array());
        $this->getAdminModel()->saveorder($cid, $order, $this->getSaveOrderWhere());
        $this->setRedirect($this->getUrlListItems(), WOPSHOP_MESSAGE_SAVEOK);
    } 
    
    protected function getOrderWhere(){
        return '';
    }
    
    protected function getSaveOrderWhere(){
        return '';
    }

    protected function getMessageSaveOk($post){
        return '';
    }
}