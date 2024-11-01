<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AddonWshopTable extends WshopTable{
    
    var $id = null;
    var $alias = null;
    var $key = null;
    var $version = null;
    var $params = null;
    
    public function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_addons', 'id');         
    }
    
    public function setParams($params){
        foreach ($params as $key => $value){
            $params[$key] = stripslashes_deep($value);
        }
        $this->params = json_encode($this->params, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    }
        
    public function getParams(){
        if ($this->params != ""){
            return json_decode($this->params);
        } else {
            return new stdClass();
        }
    }
    
    public function loadAlias($alias){
        $query = "SELECT `id` FROM `".$this->_tbl."` WHERE `alias` = '".esc_sql($alias)."'";
        $id = $this->_db->get_var($query);
        
        $this->load($id);
        $this->alias = $alias;
    }
    
    public function getKeyForAlias($alias){
        $query = "SELECT `key` FROM `".$this->_tbl."` WHERE `alias`='".esc_sql($alias)."'";
        return $this->_db->get_var($query);
    }
	   
    public function installShipping($data, $installexist = 0){
        $query = "SELECT `id` FROM `".$this->_db->prefix."wshop_shipping_ext_calc` WHERE `alias`='" . esc_sql($data['alias']) . "'";
        $exid = (int)$this->_db->get_var($query);
        if ($exid && !$installexist){
            return -1;
        }
        $extension = WopshopFactory::getTable('shippingext');
        if ($exid){
            $extension->load($exid);
        }
        if (!$exid){
            $query = "SELECT MAX(ordering) FROM `".$this->_db->prefix."wshop_shipping_ext_calc`";
            $extension->ordering = $this->_db->get_var($query) + 1;
        }
        $extension->bind($data);
        if ($extension->store()){
            return 1;
        } else {
            return 0;
        }
    }
	
	public function installShippingMethod($data, $installexist = 0){
        $query = "SELECT `shipping_id` FROM `".$this->_db->prefix."wshop_shipping_method` WHERE `alias`='" . esc_sql($data['alias']) . "'";
        $exid = (int)$this->_db->get_var($query);
        if ($exid && !$installexist){
            return -1;
        }
        $extension = WopshopFactory::getTable('shippingmethod');
        if ($exid){
            $extension->load($exid);
        }
        if (!$exid){
            $query = "SELECT MAX(ordering) FROM `".$this->_db->prefix."wshop_shipping_method`";
            $extension->ordering = $this->_db->get_var($query) + 1;
        }
        $extension->bind($data);
        if ($extension->store()){
            return 1;
        } else {
            return 0;
        }
    }
    
    public function installPayment($data, $installexist = 0){
        $query = "SELECT `payment_id` FROM `".$this->_db->prefix."wshop_payment_method` WHERE `payment_class`='" . esc_sql($data['payment_class']) . "'";
        $exid = (int)$this->_db->get_var($query);
        if ($exid && !$installexist){
            return -1;
        }
        $extension = WopshopFactory::getTable('paymentMethod');
        if ($exid){
            $extension->load($exid);
        }
        if (!$exid){
            $query = "SELECT MAX(payment_ordering) FROM `".$this->_db->prefix."wshop_payment_method`";
            $extension->payment_ordering = $this->_db->get_var($query) + 1;
        }
        $extension->bind($data);
        if ($extension->store()){
            return 1;
        } else {
            return 0;
        }
    }
    
    public function unInstallPayment($paymentClass){
        $query = "DELETE FROM `".$this->_db->prefix."wshop_payment_method` WHERE `payment_class`='" . esc_sql($paymentClass) . "'";
        return $this->_db->query($query);
    }
    
    public function installImportExport($data, $installexist = 0){
        $query = "SELECT `id` FROM `".$this->_db->prefix."wshop_import_export` WHERE `alias`='" . esc_sql($data['alias']) . "'";
        $exid = (int)$this->_db->get_var($query);
        if ($exid && !$installexist){
            return -1;
        }
        $extension = WopshopFactory::getTable('importexport');
        if ($exid){
            $extension->load($exid);
        }
        if (!isset($data['steptime'])){
            $data['steptime'] = 1;
        }
        $extension->bind($data);
        if ($extension->store()){
            return 1;
        } else {
            return 0;
        }
    }
    
    public function addFieldTable($table, $field, $type){
        $listfields = $this->getTableColumns($table);
        if (!isset($listfields[$field])){
            $query = "ALTER TABLE `".$table."` ADD `".$field."` ".$type;
            $this->_db->query($query);
        }
    }

	function deleteFieldTable($table, $field){
		$query = "ALTER TABLE ".esc_sql($table)." DROP ".esc_sql($field);
		$this->_db->query($query);
	}

	function dropTable($table_name){
		$query = "DROP TABLE IF EXISTS $table_name";
		$this->_db->query($query);
	}

	function deleteFolders($folders){
        WP_Filesystem();
        global $wp_filesystem;
		foreach($folders as $folder){
            if($wp_filesystem->is_dir($folder))
                $wp_filesystem->delete($folder, true);
        }
	}
	
	function deleteFiles($files){
        WP_Filesystem();
        global $wp_filesystem;
		foreach($files as $file){
            if($wp_filesystem->is_file($file))
                $wp_filesystem->delete($file);
        }
	}    
}