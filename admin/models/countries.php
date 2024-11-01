<?php
class CountriesWshopAdminModel extends WshopAdminModel {
    protected $tablename = 'country';
    protected $tableFieldPublish = 'country_publish';
 
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'wshop_countries';
        parent::__construct();
    }
    
    function getAllCountries($publish = 1, $limitstart = null, $limit = null, $orderConfig = 1, $order = null, $orderDir = null){
        global $wpdb;
        $config = WopshopFactory::getConfig();

        if ($publish == 0) {
            $where = " ";
        } else {
            if ($publish == 1) {
                $where = (" WHERE country_publish = '1' ");
            } else {
                if ($publish == 2) {
                    $where = (" WHERE country_publish = '0' ");
                }
            }
        }
        $ordering = "ordering";
        if ($orderConfig && $config->sorting_country_in_alphabet) $ordering = "name";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $name = 'name_'.$this->lang;
        $query = "SELECT country_id, country_publish, ordering, country_code, country_code_2, `".$name."` as name FROM `".$wpdb->prefix."wshop_countries` ".$where." ORDER BY ".$ordering;
        if($limit)
        $query.= " LIMIT ".$limitstart.", ".$limit;
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results( $query , OBJECT);
    }
    
    function getCountAllCountries() {
        global $wpdb;
        $count = $wpdb->get_var( "SELECT COUNT(country_id) FROM `".$wpdb->prefix. "wshop_countries`" );
        extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $count;
    }
    
    function getCountPublishCountries($publish = 1) {
        global $wpdb;
        $query = "SELECT COUNT(country_id) FROM `".$wpdb->prefix. "wshop_countries` WHERE country_publish = '".intval($publish)."'";
        extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_var($query);
    }
    
    public function publish(array $cid, $flag){
        do_action_ref_array('onBeforePublishCountry', array(&$cid, &$flag));
        parent::publish($cid, $flag);
        do_action_ref_array('onAfterPublishCountry', array(&$cid, &$flag));
	}

    
    
    
	
//    function getDataCountry($country_id){
//        global $wpdb;
//
//        $where = 'WHERE `country_id` = '.esc_sql($country_id);
//
//        $country = $wpdb->get_row( "SELECT *, `name_".$this->lang."` as name FROM ".$this->table_name." ".$where , OBJECT);
//        extract(wopshop_add_trigger(get_defined_vars(), "before"));
//        return $country;
//    }
//    
//    function CountryUpdate($post, $country_id){
//        global $wpdb;
//		extract(wopshop_add_trigger(get_defined_vars(), "before"));
//        $wpdb->update( 
//            $this->table_name, 
//            $post, 
//            array( 
//                'country_id' => $country_id
//            )
//        );
//        wopshopAddMessage(WOPSHOP_ACTION_COUNTRY_UPDATE);
//    }
//    
//    function CountryInsert($post){
//        global $wpdb;
//		extract(wopshop_add_trigger(get_defined_vars(), "before"));
//        $count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$this->table_name);
//        
//        $post['ordering'] = $count+1;
//        $wpdb->insert( 
//            $this->table_name, 
//            $post
//        );
//        wopshopAddMessage(WOPSHOP_ACTION_COUNTRY_INSERT);
//    }
}