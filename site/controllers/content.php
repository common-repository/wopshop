<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WopshopContentController extends WshopController{
    
    public function __construct($config = array()){
        parent::__construct($config);
        do_action_ref_array('onConstructWshopControllerContent', array(&$this));
    }
    
    public function display(){
        $this->view();
    }

    public function view(){
        $config = WopshopFactory::getConfig();
        $page = WopshopRequest::getVar('content-page');
        $pathway = '';
        switch($page){
            case 'agb':
                $pathway = WOPSHOP_AGB;
            break;
            case 'return_policy':
                $pathway = WOPSHOP_RETURN_POLICY;
            break;
            case 'shipping':
                $pathway = WOPSHOP_SHIPPING;
            break;
            case 'privacy_statement':
                $pathway = WOPSHOP_PRIVACY_STATEMENT;
            break;
        }
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("content-".$page);
        if ($seodata->title == "" && $pathway){
            $seodata->title = $pathway;
        }
        if(isset($seodata->description)){
            $this->addMetaTag('description', $seodata->description);
        }
        if(isset($seodata->keyword)){
            $this->addMetaTag('keyword', $seodata->keyword);
        }
        if(isset($seodata->title)){
            $this->addMetaTag('title', $seodata->title);
        }     
        
        $statictext = WopshopFactory::getTable("statictext");
        $order_id = WopshopRequest::getInt('order_id');
        $cartp = WopshopRequest::getInt('cart');
        
        if ($config->return_policy_for_product && $page=='return_policy' && ($cartp || $order_id)){
            if ($cartp){
                $cart = WopshopFactory::getModel('cart');
                $cart->load();
                $list = $cart->getReturnPolicy();
            }else{
                $order = WopshopFactory::getTable('order');
                $order->load($order_id);
                $list = $order->getReturnPolicy();
            }
            $listtext = array();
            foreach($list as $v){
                $listtext[] = $v->text;
            }
            $row = new stdClass();
            $row->id = -1;
            $row->text = implode('<div class="return_policy_space"></div>', $listtext);
        }else{
            $row = $statictext->loadData($page);
        }
                
        if (!$row->id){
            wopshopAddMessage(WOPSHOP_PAGE_NOT_FOUND, 'error');
            return;
        }
        $text = $row->text;
        do_action_ref_array('onBeforeDisplayContent', array($page, &$text));
        
        $view_name = "content";
        $view=$this->getView($view_name);
        $view->setLayout("content");        
        $view->assign('text', $text);
        do_action_ref_array('onBeforeDisplayContentView', array(&$view));
        $view->display();
		$tmpl = WopshopRequest::getVar('tmpl');
		if($tmpl)
			die();
    }
}
?>