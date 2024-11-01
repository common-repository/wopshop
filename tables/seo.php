<?php
class SeoWshopTable extends WshopTable{
    
    function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_config_seo', 'id');
    }
    
    function loadData($alias){
        $config = WopshopFactory::getConfig();
        $query = "SELECT id, alias, `title_" . $config->cur_lang . "` as title, `keyword_" . $config->cur_lang . "` as keyword, `description_" . $config->cur_lang . "` as description FROM `".$this->_tbl."` WHERE `alias`='".  esc_sql($alias) . "'";
        $data = $this->_db->get_row($query);
		if (!isset($data)){
            $data = new stdClass();
            $data->title = '';
            $data->keyword = '';
            $data->description = '';
        }
        return $data;
    }
}