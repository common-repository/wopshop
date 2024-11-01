<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WopshopWishlistController extends WshopController{

	public function __construct(){
        parent::__construct();
		do_action_ref_array('onConstructWshopControllerWishlist', array(&$this));
    }

	public function display(){
        $this->view();
    }

	public function view(){
	    $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        //$params = $mainframe->getParams();
        $ajax = WopshopRequest::getInt('ajax');

        $cart = WopshopFactory::getModel('cart');
        $cart->load("wishlist");
        $cart->wopshopAddLinkToProducts(1, "wishlist");
        //freeAtribs
        $cart->setDisplayFreeAttributes();

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("wishlist");

        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);         

        $shopurl = esc_url(wopshopSEFLink('controller=products',1));
        if ($config->cart_back_to_shop=="product"){
            $endpagebuyproduct = wopshopXhtmlUrl($session->get('wshop_end_page_buy_product'));
        }elseif ($config->cart_back_to_shop=="list"){
            $endpagebuyproduct = wopshopXhtmlUrl($session->get('wshop_end_page_list_product'));
        }
        if (isset($endpagebuyproduct) && $endpagebuyproduct){
            $shopurl = $endpagebuyproduct;
        }
        $products = $cart->getProductsPrepare($cart->products);
        $view_name = "cart";
        $view=$this->getView($view_name);
        $view->setLayout("wishlist");        
        $view->assign('config', $config);
        $view->assign('products', $products);
        $view->assign('image_product_path', $config->image_product_live_path);
        $view->assign('image_path', $config->live_path);
        $view->assign('no_image', $config->noimage);
        $view->assign('href_shop', $shopurl);
        $view->assign('href_checkout', esc_url(wopshopSEFLink('controller=cart&task=view',1)));
        $view->_tmp_html_before_buttons = "";
        $view->_tmp_html_after_buttons = "";
		do_action_ref_array('onBeforeDisplayWishlistView', array(&$view));
        $view->display();
        if ($ajax) die();
    }

	public function delete(){
        header("Cache-Control: no-cache, must-revalidate");
        $ajax = WopshopRequest::getInt('ajax');
        $cart = WopshopFactory::getModel('cart');
        $cart->load('wishlist');    
        $cart->delete(WopshopRequest::getInt('number_id'));
        if ($ajax){
            print wopshopGetOkMessageJson($cart); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            die();
        }
        $this->setRedirect(esc_url(wopshopSEFLink('controller=wishlist&task=view',0,1)));
    }

	public function remove_to_cart(){
        header("Cache-Control: no-cache, must-revalidate");
        $ajax = WopshopRequest::getInt('ajax');
        $number_id = WopshopRequest::getInt('number_id');
        do_action_ref_array('onBeforeLoadWishlistRemoveToCart', array(&$number_id));
        
        $cart = WopshopFactory::getModel('cart');
        $cart->load("wishlist");
        $prod = $cart->products[$number_id];
        $attr = json_decode($prod['attributes'], 1);
        $freeattribut = json_decode($prod['freeattributes'], 1);
        $cart->delete($number_id);
                        
        $cart = WopshopFactory::getModel('cart');
        $cart->load("cart");        
        $cart->add($prod['product_id'], $prod['quantity'], $attr, $freeattribut);
        do_action_ref_array('onAfterWishlistRemoveToCart', array(&$cart));
        if ($ajax){
            print wopshopGetOkMessageJson($cart); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            die();
        }
        $this->setRedirect( esc_url(wopshopSEFLink('controller=cart&task=view',1,1) ));
    }
}
?>