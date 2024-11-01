<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WopshopVendorController extends WshopController{
    
    public function __construct() {
        parent::__construct();
		do_action_ref_array('onConstructJshoppingControllerVendor', array(&$this));
    }

	public function display(){
		$mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();       
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("vendors");
        if ($seodata->title==""){
            $seodata->title = WOPSHOP_VENDOR;
        }		
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title); 		

        $context = "list.front.vendor";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $config->count_products_to_page, 'int');
        //if (!$limit) $limit = $products_page;
        $limitstart = WopshopRequest::getInt('limitstart');		

        
        $vendor = WopshopFactory::getTable('vendor');
        $total = $vendor->getCountAllVendors();
        
        if ($limitstart>=$total) $limitstart = 0;
        
        $rows = $vendor->getAllVendors(1, $limitstart, $limit);

        do_action_ref_array( 'onBeforeDisplayListVendors', array(&$rows) );
        
        $pagination = new WopshopPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        
        foreach($rows as $k=>$v){
            $rows[$k]->link = esc_url(wopshopSEFLink("controller=vendor&task=products&vendor_id=".$v->id));
            if (!$v->logo){
                $rows[$k]->logo = $config->image_vendors_live_path."/".$config->noimage;
            }
        }
		$view_name = "vendor";
        $view = $this->getView($view_name);
        $view->setLayout("vendors");
        $view->assign("rows", $rows);        
        $view->assign('count_to_row', $config->count_category_to_row);
        $view->assign('pagination', $pagenav);
        $view->assign('display_pagination', $pagenav!="");
        do_action_ref_array('onBeforeDisplayVendorView', array(&$view) );
        $view->display();
    }

	public function info(){
        $config = WopshopFactory::getConfig();
        if (!$config->product_show_vendor_detail){
	        global $wp_query;
	        $wp_query->set_404();
	        status_header(404);
	        get_template_part(404); exit();
	        //echo WOPSHOP_PAGE_NOT_FOUND;
	        return 0;
        }
        $vendor_id = WopshopRequest::getInt("vendor_id");
        $vendor = WopshopFactory::getTable('vendor');
        $vendor->load($vendor_id);

        do_action_ref_array( 'onBeforeDisplayVendorInfo', array(&$vendor) );
        
        $title =  $vendor->shop_name;
        $header =  $vendor->shop_name;

        
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("vendor-info-".$vendor_id);
        if (!isset($seodata)) {
            $seodata = new stdClass();
            $seodata->title = '';
            $seodata->keyword = '';
            $seodata->description = '';
        }
        if ($seodata->title==""){
            $seodata->title = $title;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);
        
        $lang = WopshopFactory::getLang();
        $country = WopshopFactory::getTable('country');
        $country->load($vendor->country);
        $_name = $lang->get("name");
        $vendor->country = $country->$_name;

        $view_name = "vendor";
		$view = $this->getView($view_name);
        $view->setLayout("info");
        $view->assign('vendor', $vendor);
        $view->assign('header', $header);
        do_action_ref_array('onBeforeDisplayVendorInfoView', array(&$view) );
        $view->display();       
    }

	public function products(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());
        
        $vendor_id = WopshopRequest::getInt("vendor_id");
        $vendor = WopshopFactory::getTable('vendor');
        $vendor->load($vendor_id);

        do_action_ref_array( 'onBeforeDisplayVendor', array(&$vendor) );
        
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("vendor-product-".$vendor_id);
        if (!isset($seodata->title) || $seodata->title==""){
            $seodata = new stdClass();
            $seodata->title = $vendor->shop_name;
            $seodata->keyword = $vendor->shop_name;
            $seodata->description = $vendor->shop_name;
        }
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);
        
        $action = wopshopXhtmlUrl($_SERVER['REQUEST_URI']);
        
        $products_page = $config->count_products_to_page;
        $count_product_to_row = $config->count_products_to_row;

        $context = "wshop.vendor.front.product";
        $contextfilter = "wshop.list.front.product.vendor.".$vendor_id;
        $orderby = $mainframe->getUserStateFromRequest( $context.'orderby', 'orderby', $config->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest( $context.'order', 'order', $config->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $products_page, 'int');		
		
        if (!$limit) $limit = $products_page;
        $limitstart = WopshopRequest::getInt('limitstart');
        if ($order==4){
            $order = 1;
        }

        $orderbyq = wopshopGetQuerySortDirection($order, $orderby);
        $image_sort_dir = wopshopGetImgSortDirection($order, $orderby);
        $field_order = $config->sorting_products_field_s_select[$order];
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array("vendors"));

        $total = $vendor->getCountProducts($filters);
		
        $pagination = new WopshopPagination($total, $limitstart, $limit);
		$pagination->setAdditionalUrlParam('vendor_id', $vendor_id);
        $pagenav = $pagination->getPagesLinks();		
       
        
		do_action_ref_array('onBeforeFixLimitstartDisplayProductList', array(&$limitstart, &$total, 'vendor'));
        if ($limitstart>=$total) $limitstart = 0;

        $rows = $vendor->getProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
        wopshopAddLinkToProducts($rows, 0, 1);
    
        foreach ($config->sorting_products_name_s_select as $key => $value) {
            $sorts[] = WopshopHtml::_('select.option', $key, $value, 'sort_id', 'sort_value' );
        }

        wopshopInsertValueInArray($products_page, $config->count_product_select);
        foreach ($config->count_product_select as $key => $value) {
            $product_count[] = WopshopHtml::_('select.option',$key, $value, 'count_id', 'count_value' );
        }
        $sorting_sel = WopshopHtml::_('select.genericlist', $sorts, 'order', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $order );
        $product_count_sel = WopshopHtml::_('select.genericlist', $product_count, 'limit', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit );

        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();

        if ($config->show_product_list_filters){
            $first_el = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'manufacturer_id', 'name' );
            $_manufacturers = WopshopFactory::getTable('manufacturer');
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
        } else {
            $categorys_sel = null;
            $manufacuturers_sel = null;
        }

        $wopshopWillBeUseFilter = wopshopWillBeUseFilter($filters);
        $display_list_products = (count($rows)>0 || $wopshopWillBeUseFilter);
        
        do_action_ref_array('onBeforeDisplayProductList', array(&$rows));

        $view_name = "vendor";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_no_list_product', "list_products/no_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign('path_image_sorting_dir', $config->live_path.'assets/images/'.$image_sort_dir);
        $view->assign('filter_show', 1);
        $view->assign('filter_show_category', 1);
        $view->assign('filter_show_manufacturer', 1);
        $view->assign('pagination', $pagenav);
		$view->assign('pagination_obj', $pagination);
        $view->assign('display_pagination', $pagenav!="");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign("vendor", $vendor);
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
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }	

}