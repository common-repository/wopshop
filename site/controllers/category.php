<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WopshopCategoryController extends WshopController{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function maincategory(){
        
        $config = WopshopFactory::getConfig();
        $category_id = 0;
        
        $ordering = $config->category_sorting==1 ? "ordering" : "name";
        $category = WopshopFactory::getTable('category');
        $category->load($category_id);
        $categories = $category->getChildCategories($ordering, 'asc', 1);
        $category->getDescription();

        do_action_ref_array('onBeforeDisplayMainCategory', array(&$category, &$categories));

        $this->addMetaTag('description', $category->description);
        $this->addMetaTag('keyword', $category->keyword);
        $this->addMetaTag('title', $category->title);           
        $view_name = "category";
        $view = $this->getView($view_name);
        $view->setLayout("maincategory");
        $view->assign('category', $category);
        $view->assign('image_category_path', $config->image_category_live_path);
        $view->assign('noimage', $config->noimage);
        $view->assign('categories', $categories);
        $view->assign('count_category_to_row', $config->count_category_to_row);
        do_action_ref_array('onBeforeDisplayCategoryView', array(&$view) );
        $view->display();        
    }
            
    public function display(){
        $this->view();
    }

	public function view(){
        $mainframe = WopshopFactory::getApplication();
        $user = WopshopFactory::getUser();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());

        $category_id = WopshopRequest::getInt('category_id');
        $category = WopshopFactory::getTable('category');
        $category->load($category_id);
        $category->getDescription();
        do_action_ref_array('onAfterLoadCategory', array(&$category, &$user));

//	if (!$category->category_id || $category->category_publish==0 || !in_array($category->access, $user->getAuthorisedViewLevels())){
	if (!$category->category_id || $category->category_publish==0 ){
	        global $wp_query;
	        $wp_query->set_404();
	        status_header(404);
	        get_template_part(404); exit();
            return;
        }
        
        $manufacturer_id = WopshopRequest::getInt('manufacturer_id');
        $label_id = WopshopRequest::getInt('label_id');
        $vendor_id = WopshopRequest::getInt('vendor_id');

        $view_name = "category";
        $view = $this->getView($view_name);
		if ($category->category_template=="") $category->category_template="default";
        $view->setLayout("category_".$category->category_template);

        $config->count_products_to_page = $category->products_page;

        $context = "wshop.list.front.product";
        $contextfilter = "wshop.list.front.product.cat.".$category_id;
        $orderby = $mainframe->getUserStateFromRequest( $context.'orderby', 'orderby', $config->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest( $context.'order', 'order', $config->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $category->products_page, 'int');
        if (!$limit) $limit = $category->products_page;
        $limitstart = WopshopRequest::getInt('limitstart');

        $orderbyq = wopshopGetQuerySortDirection($order, $orderby);
        $image_sort_dir = wopshopGetImgSortDirection($order, $orderby);
        $field_order = $config->sorting_products_field_select[$order];
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array("categorys"));

        $orderfield = $config->category_sorting==1 ? "ordering" : "name";
        $sub_categories = $category->getChildCategories($orderfield, 'asc', $publish = 1);
        do_action_ref_array( 'onBeforeDisplayCategory', array(&$category, &$sub_categories) );

        if ($category->meta_title=="") $category->meta_title = $category->name;
        $this->addMetaTag('description', $category->meta_description);
        $this->addMetaTag('keyword', $category->meta_keyword);
        $this->addMetaTag('title', $category->meta_title);  
        
        $total = $category->getCountProducts($filters);
        $action = wopshopXhtmlUrl($_SERVER['REQUEST_URI']);
		
		do_action_ref_array('onBeforeFixLimitstartDisplayProductList', array(&$limitstart, &$total, 'category'));
        if ($limitstart>=$total) $limitstart = 0;

        $products = $category->getProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
		wopshopAddLinkToProducts($products, $category_id);

        $pagination = new WopshopPagination($total, $limitstart, $limit);
		$pagination->setAdditionalUrlParam('category_id', $category_id);
        $pagenav = $pagination->getPagesLinks();
        
        foreach($config->sorting_products_name_select as $key=>$value){
            $sorts[] = WopshopHtml::_('select.option', $key, $value, 'sort_id', 'sort_value' );
        }

        wopshopInsertValueInArray($category->products_page, $config->count_product_select); //insert category count
        foreach ($config->count_product_select as $key => $value){
            $product_count[] = WopshopHtml::_('select.option',$key, $value, 'count_id', 'count_value' );
        }
        $sorting_sel = WopshopHtml::_('select.genericlist', $sorts, 'order', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $order );
        $product_count_sel = WopshopHtml::_('select.genericlist', $product_count, 'limit', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit );
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        
        if (!$category->category_ordertype) $category->category_ordertype = 1;
        
        $manufacuturers_sel = '';
        if ($config->show_product_list_filters){
            $filter_manufactures = $category->getManufacturers();
            $first_manufacturer = array();
            $first_manufacturer[] = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'id', 'name');
            if (isset($filters['manufacturers'][0])){
                $active_manufacturer = $filters['manufacturers'][0];            
            }else{
                $active_manufacturer = 0;
            }
            $manufacuturers_sel = WopshopHtml::_('select.genericlist', array_merge($first_manufacturer, $filter_manufactures), 'manufacturers[]', 'class = "inputbox" onchange = "submitListProductFilters()"','id', 'name', $active_manufacturer);
        }
        
//        if ($config->use_plugin_content){
//            changeDataUsePluginContent($category, "category");
//        }

        $wopshopWillBeUseFilter = wopshopWillBeUseFilter($filters);
        $display_list_products = (count($products)>0 || $wopshopWillBeUseFilter);

        do_action_ref_array('onBeforeDisplayProductList', array(&$products));
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_no_list_product', "list_products/no_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign('path_image_sorting_dir', $config->live_path.'/assets/images/'.$image_sort_dir);
        $view->assign('filter_show', 1);
        $view->assign('filter_show_category', 0);
        $view->assign('filter_show_manufacturer', 1);
        $view->assign('pagination', $pagenav);
        $view->assign('pagination_obj', $pagination);
        $view->assign('display_pagination', $pagenav!="");
        $view->assign('rows', $products);
        $view->assign('count_product_to_row', $category->products_row);
        $view->assign('image_category_path', $config->image_category_live_path);
        $view->assign('noimage', $config->noimage);
        $view->assign('category', $category);
        $view->assign('categories', $sub_categories);
        $view->assign('count_category_to_row', $config->count_category_to_row);
        $view->assign('allow_review', $allow_review);
        $view->assign('product_count', $product_count_sel);
        $view->assign('sorting', $sorting_sel);
        $view->assign('action', $action);
        $view->assign('orderby', $orderby);
        $view->assign('manufacuturers_sel', $manufacuturers_sel);
        $view->assign('filters', $filters);
        $view->assign('wopshopWillBeUseFilter', $wopshopWillBeUseFilter);
        $view->assign('display_list_products', $display_list_products);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl,1)));
        $view->_tmp_category_html_start = ""; 
        $view->_tmp_category_html_before_products = ""; 
        $view->_tmp_category_html_end = "";
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end = "";
        $view->_tmp_ext_filter_box = "";
        $view->_tmp_ext_filter = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view) );
        $view->display();
    }
}