<?php
class CouponsWshopAdminModel extends WshopAdminModel {
    public $string;
    protected $tablename = 'coupon';
    protected $tableFieldPublish = 'coupon_publish';    
 
    public function __construct() {
        global $wpdb;

        parent::__construct();
    }

    function getAllCoupons($limitstart, $limit, $order = null, $orderDir = null) {
        global $wpdb;
        $queryorder = 'ORDER BY C.used, C.coupon_id desc';
        if ($order && $orderDir){
            $queryorder = "ORDER BY ".$order." ".$orderDir;
        }
        $query = "SELECT C.*, U.f_name, U.l_name FROM `".$wpdb->prefix."wshop_coupons` as C left join ".$wpdb->prefix."wshop_users as U on C.for_user_id=U.user_id ".$queryorder;
        if($limit) $query.= ' LIMIT '.$limitstart.', '.$limit;

        extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_results($query, OBJECT);
    }
    
    function getCountCoupons(){
        global $wpdb;
        $query = "SELECT count(*) FROM `".$wpdb->prefix."wshop_coupons`";
		extract(wopshop_add_trigger(get_defined_vars(), "before"));
        return $wpdb->get_var($query);
    }
    
    public function deleteList(array $cid, $msg = 1){
        global $wpdb;
        do_action_ref_array( 'onBeforeRemoveCoupon', array(&$cid) );
        foreach($cid as $id){
            $wpdb->delete( $wpdb->prefix.'wshop_coupons', array( 'coupon_id' => esc_sql($id) ));
        }
        if ($msg){
            $app = WopshopFactory::getApplication();
            $app->enqueueMessage(WOPSHOP_COUPON_DELETED, 'updated');
        }
        do_action_ref_array( 'onAfterRemoveCoupon', array(&$cid) );
    }
    
    public function publish(array $cid, $flag){
        do_action_ref_array( 'onBeforePublishCoupon', array(&$cid,&$flag) );
        parent::publish($cid, $flag);
        do_action_ref_array( 'onAfterPublishCoupon', array(&$cid,&$flag) );
    }    
}