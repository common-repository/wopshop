<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WopshopProductsController extends WshopController {
    public function __construct() {
        parent::__construct();
		do_action_ref_array('onConstructWshopControllerProducts', array(&$this));
    }
    
    public function display(){
        $config = WopshopFactory::getConfig();
        $mainframe = WopshopFactory::getApplication();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());

        $product = WopshopFactory::getTable("product");

        $action = wopshopXhtmlUrl($_SERVER['REQUEST_URI']);
        $products_page = $config->count_products_to_page;
        $count_product_to_row = $config->count_products_to_row;

        $context = "wshop.alllist.front.product";
        $contextfilter = "wshop.list.front.product.fulllist";
        $orderby = $mainframe->getUserStateFromRequest( $context.'orderby', 'orderby', $config->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest( $context.'order', 'order', $config->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $products_page, 'int');
        if (!$limit) $limit = $products_page;
        $limitstart = WopshopRequest::getInt('limitstart');

        $orderbyq = wopshopGetQuerySortDirection($order, $orderby);
        $image_sort_dir = wopshopGetImgSortDirection($order, $orderby);
        $field_order = $config->sorting_products_field_s_select[$order];
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());

        $total = $product->getCountAllProducts($filters);  

        $pagination = new WopshopPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();

        if ($limitstart>=$total) $limitstart = 0;

        $rows = $product->getAllProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
        wopshopAddLinkToProducts($rows);		

        foreach ($config->sorting_products_name_s_select as $key => $value) {
            $sorts[] = WopshopHtml::_('select.option', $key, $value, 'sort_id', 'sort_value' );
        }

        wopshopInsertValueInArray($products_page, $config->count_product_select); //insert products_page count
        foreach ($config->count_product_select as $key => $value) {
            $product_count[] = WopshopHtml::_('select.option',$key, $value, 'count_id', 'count_value' );
        }
        $sorting_sel = WopshopHtml::_('select.genericlist', $sorts, 'order', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $order );
        $product_count_sel = WopshopHtml::_('select.genericlist', $product_count, 'limit', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit );
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();         

        if ($config->show_product_list_filters){
            $first_el = new stdClass();
            $first_el->manufacturer_id = 0;
            $first_el->name = WOPSHOP_ALL;
            $first_el = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'manufacturer_id', 'name' );
            $_manufacturers = WopshopFactory::getTable('manufacturer');
            if ($config->manufacturer_sorting==2){
                $morder = 'name';
            }else{
                $morder = 'ordering';
            }
            $listmanufacturers = $_manufacturers->getList();
            array_unshift($listmanufacturers, $first_el);
            if (isset($filters['manufacturers'][0])){
                $active_manufacturer = $filters['manufacturers'][0];
            }else{
                $active_manufacturer = '';
            }
            $manufacuturers_sel = WopshopHtml::_('select.genericlist', $listmanufacturers, 'manufacturers[]', 'class = "inputbox" onchange = "submitListProductFilters()"','manufacturer_id','name', $active_manufacturer);
 
            
            
            $first_el = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'category_id', 'name' );
            $categories = wopshopBuildTreeCategory(1);
            array_unshift($categories, $first_el);
            if (isset($filters['categorys'][0])){
                $active_category = $filters['categorys'][0];
            }else{
                $active_category = 0;
            }
            $categorys_sel = WopshopHtml::_('select.genericlist', $categories, 'categorys[]', 'class = "inputbox" onchange = "submitListProductFilters()"', 'category_id', 'name', $active_category);
        }else{
            $manufacuturers_sel = '';
            $categorys_sel = '';
        }
        
        $wopshopWillBeUseFilter = wopshopWillBeUseFilter($filters);
        $display_list_products = (count($rows)>0 || $wopshopWillBeUseFilter); 
		do_action_ref_array( 'onBeforeDisplayProductList', array(&$rows) );
        $view=$this->getView("products");
        //$view_config = array("template_path"=>$config->template_path.$config->template."/".$view_name);
        //$view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', 'list_products/list_products.php');
        $view->assign('template_no_list_product', "list_products/no_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign('path_image_sorting_dir', $config->live_path.'/assets/images/'.$image_sort_dir);
        $view->assign('filter_show', 1);
        $view->assign('filter_show_category', 1);
        $view->assign('filter_show_manufacturer', 1);
        $view->assign('pagination', $pagenav);
        $view->assign('pagination_obj', $pagination);
        $view->assign('display_pagination', $pagenav!="");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('action', $action);
        $view->assign('allow_review', $allow_review);
        $view->assign('orderby', $orderby);		
        $view->assign('product_count', $product_count_sel);
        $view->assign('sorting', $sorting_sel);
        $view->assign('categorys_sel', $categorys_sel);
        $view->assign('manufacuturers_sel', $manufacuturers_sel);
        $view->assign('filters', $filters);
        $view->assign('wopshopWillBeUseFilter', $wopshopWillBeUseFilter);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl)));  
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
		do_action_ref_array('onBeforeDisplayProductListView', array(&$view));		
        $view->display();    
    }   

    public function tophits(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());
        
        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("tophitsproducts");
        //setMetaData($seodata->title, $seodata->keyword, $seodata->description, $params);

        $count_product_to_row = $config->count_products_to_row_tophits;
        $contextfilter = "wshop.list.front.product.tophits";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getTopHitsProducts($config->count_products_to_page_tophits, null, $filters);
        wopshopAddLinkToProducts($rows, 0, 1);

        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;
		
		do_action_ref_array( 'onBeforeDisplayProductList', array(&$rows) );
        $view_name = "products";
        $view=$this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->display();
    }

    public function toprating(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());
        
        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("topratingproducts");

        $count_product_to_row = $config->count_products_to_row_toprating;
        $contextfilter = "wshop.list.front.product.toprating";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getTopRatingProducts($config->count_products_to_page_toprating, null, $filters);
        wopshopAddLinkToProducts($rows, 0, 1);
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;

        do_action_ref_array( 'onBeforeDisplayProductList', array(&$rows) );

        $view_name = "products";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
    
    public function label(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());
        
        $product = WopshopFactory::getTable('product');;
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("labelproducts");

        $label_id = WopshopRequest::getInt("label_id");
        $count_product_to_row = $config->count_products_to_row_label;
        $contextfilter = "wshop.list.front.product.label";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getProductLabel($label_id, $config->count_products_to_page_label, null, $filters);
        wopshopAddLinkToProducts($rows, 0, 1);
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;
        
        do_action_ref_array('onBeforeDisplayProductList', array(&$rows));

        $view_name = "products";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("header", $header);
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
    
    public function bestseller(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);
        
        do_action_ref_array('onBeforeLoadProductList', array());
        
        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("bestsellerproducts");
        //setMetaData($seodata->title, $seodata->keyword, $seodata->description, $params);

        $count_product_to_row = $config->count_products_to_row_bestseller;
        $contextfilter = "wshop.list.front.product.bestseller";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getBestSellers($config->count_products_to_page_bestseller, null, $filters);

        wopshopAddLinkToProducts($rows, 0, 1);
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;

        do_action_ref_array( 'onBeforeDisplayProductList', array(&$rows) );

        $view_name = "products";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
    
    public function random(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());

        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("randomproducts");
        //setMetaData($seodata->title, $seodata->keyword, $seodata->description, $params);

        $count_product_to_row = $config->count_products_to_row_random;
        $contextfilter = "wshop.list.front.product.random";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getRandProducts($config->count_products_to_page_random, null, $filters);
        wopshopAddLinkToProducts($rows);
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;

        do_action_ref_array( 'onBeforeDisplayProductList', array(&$rows) );

        $view_name = "products";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
    
    public function last(){
        $config = WopshopFactory::getConfig();

        do_action_ref_array('onBeforeLoadProductList', array());

        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("lastproducts");
        //setMetaData($seodata->title, $seodata->keyword, $seodata->description, $params);

        $count_product_to_row = $config->count_products_to_row_last;
        $contextfilter = "wshop.list.front.product.last";
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array());
        $rows = $product->getLastProducts($config->count_products_to_page_last, null, $filters);
        wopshopAddLinkToProducts($rows, 0, 1);

        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $config->show_sort_product = 0;
        $config->show_count_select_products = 0;
        $config->show_product_list_filters = 0;
        
        do_action_ref_array('onBeforeDisplayProductList', array(&$rows));

        $view_name = "products";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('display_pagination', 0);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
}