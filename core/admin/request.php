<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WshopAdminRequest {
    
    private $_folder = '';
    
    private $_controller = 'panel';
    
    private $_task = 'display';


    public function __construct(){
        $page = str_replace('wopshop-', '', WopshopRequest::getString('page'));
        $tab = WopshopRequest::getString('tab');

        $this->_controller = $tab ? $tab : $page;
        $this->_folder = $page.'/';

        $task = WopshopRequest::getVar('task', 'dislpay');
        $action =  WopshopRequest::getVar('action');
        if($action && $action !='-1'){
            $task = $action;
        }
        $this->_task = $task;
    }

    public function getController(){
        return $this->_controller;
    }
    
    public function getTask(){
        return $this->_task;
    }
    
    public function getFolder(){
        return $this->_folder;
    }
    
}    

