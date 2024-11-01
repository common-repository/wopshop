<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WopshopManufacturerController extends WshopController{
    
    function __construct($config = array()){
        parent::__construct($config);
        do_action_ref_array('onConstructWshopControllerManufacturer', array(&$this));
    }
	
	public function display(){
        $config = WopshopFactory::getConfig();
        $manufacturer = WopshopFactory::getTable('manufacturer');
        $ordering = $config->manufacturer_sorting==1 ? "ordering" : "name";
        $rows = $manufacturer->getAllManufacturers(1, $ordering, 'asc');

        do_action_ref_array('onBeforeDisplayListManufacturers', array(&$rows));

        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("manufacturers");
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);          
        $statictext = WopshopFactory::getTable("statictext");
        $rowstatictext = $statictext->loadData("manufacturer");
        $manufacturer->description = $rowstatictext->text;
        
        $view_name = "manufacturer";
        $view = $this->getView($view_name);
	$view->setLayout("manufacturers");
	$view->assign("rows", $rows);
	$view->assign("image_manufs_live_path", $config->image_manufs_live_path);
        $view->assign('noimage', $config->noimage);
        $view->assign('count_manufacturer_to_row', $config->count_manufacturer_to_row);       
	$view->assign('manufacturer', $manufacturer);
        do_action_ref_array('onBeforeDisplayManufacturerView', array(&$view) );
	$view->display();
	}	
	
	public function view(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        do_action_ref_array('onBeforeLoadProductList', array());
		
        $manufacturer_id = WopshopRequest::getInt('manufacturer_id');
        $category_id = WopshopRequest::getInt('category_id');
        $label_id = WopshopRequest::getInt('label_id');
        $vendor_id = WopshopRequest::getInt('vendor_id');
        $manufacturer = WopshopFactory::getTable('manufacturer');		
        $manufacturer->load($manufacturer_id);
        $manufacturer->getDescription();

        do_action_ref_array('onBeforeDisplayManufacturer', array(&$manufacturer));
        
        if ($manufacturer->manufacturer_publish==0){
            return;
        }

        if ($manufacturer->meta_title=="") $manufacturer->meta_title = $manufacturer->name;
        $this->addMetaTag('description', $manufacturer->meta_description);
        $this->addMetaTag('keyword', $manufacturer->meta_keyword);
        $this->addMetaTag('title', $manufacturer->meta_title); 
			
		
        $action = wopshopXhtmlUrl($_SERVER['REQUEST_URI']);
        
        if (!$manufacturer->products_page){
		    $manufacturer->products_page = $config->count_products_to_page; 
        }
        $count_product_to_row = $manufacturer->products_row;
        if (!$count_product_to_row){
		    $count_product_to_row = $config->count_products_to_row;
        }
				
		$context = "wshop.manufacturlist.front.product";
        $contextfilter = "wshop.list.front.product.manf.".$manufacturer_id;
        $orderby = $mainframe->getUserStateFromRequest($context.'orderby', 'orderby', $config->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest($context.'order', 'order', $config->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest($context.'limit', 'limit', $manufacturer->products_page, 'int');
        if (!$limit) $limit = $manufacturer->products_page;
        $limitstart = WopshopRequest::getInt('limitstart');
        if ($order==4){
            $order = 1;
        }

        $orderbyq = wopshopGetQuerySortDirection($order, $orderby);
        $image_sort_dir = wopshopGetImgSortDirection($order, $orderby);
        $field_order = $config->sorting_products_field_s_select[$order];
        $filters = wopshopGetBuildFilterListProduct($contextfilter, array("manufacturers"));

        $total = $manufacturer->getCountProducts($filters);
       
        $pagination = new WopshopPagination($total, $limitstart, $limit);
		$pagination->setAdditionalUrlParam('manufacturer_id', $manufacturer_id);
        $pagenav = $pagination->getPagesLinks();
        
        do_action_ref_array('onBeforeFixLimitstartDisplayProductList', array(&$limitstart, &$total, 'manufacturer'));
        if ($limitstart>=$total) $limitstart = 0;

            $rows = $manufacturer->getProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
            wopshopAddLinkToProducts($rows, 0, 1);		

            foreach($config->sorting_products_name_s_select as $key => $value){
            $sorts[] = WopshopHtml::_('select.option', $key, $value, 'sort_id', 'sort_value' );
        }

        wopshopInsertValueInArray($manufacturer->products_page, $config->count_product_select); //insert products_page count
        foreach($config->count_product_select as $key => $value){            
            $product_count[] = WopshopHtml::_('select.option',$key, $value, 'count_id', 'count_value' );
        }        
        $sorting_sel = WopshopHtml::_('select.genericlist', $sorts, 'order', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $order );
        $product_count_sel = WopshopHtml::_('select.genericlist', $product_count, 'limit', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit );
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        
        if ($config->show_product_list_filters){
            $filter_categorys = $manufacturer->getCategorys();
            $first_category = array();
            $first_category[] = WopshopHtml::_('select.option', 0, WOPSHOP_ALL, 'id', 'name');
            if (isset($filters['categorys'][0])){
                $active_category = $filters['categorys'][0];
            }else{
                $active_category = 0;
            }
            $categorys_sel = WopshopHtml::_('select.genericlist', array_merge($first_category, $filter_categorys), 'categorys[]', 'class = "inputbox" onchange = "submitListProductFilters()"','id', 'name', $active_category);
        }else{
            $categorys_sel = '';
        }
        
//        if ($config->use_plugin_content){
//            changeDataUsePluginContent($manufacturer, "manufacturer");
//        }
        
        $wopshopWillBeUseFilter = wopshopWillBeUseFilter($filters);
        $display_list_products = (count($rows)>0 || $wopshopWillBeUseFilter);

        do_action_ref_array('onBeforeDisplayProductList', array(&$rows));

        $view_name = "manufacturer";
        $view = $this->getView($view_name);
        $view->setLayout("products");
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_no_list_product', "list_products/no_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign('path_image_sorting_dir', $config->live_path.'/assets/images/'.$image_sort_dir);
        $view->assign('filter_show', 1);
        $view->assign('filter_show_category', 1);
        $view->assign('filter_show_manufacturer', 0);
        $view->assign('pagination', $pagenav);
        $view->assign('pagination_obj', $pagination);
        $view->assign('display_pagination', $pagenav!="");
        $view->assign("rows", $rows);
        $view->assign("count_product_to_row", $count_product_to_row);
        $view->assign("manufacturer", $manufacturer);
        $view->assign('action', $action);
        $view->assign('allow_review', $allow_review);
        $view->assign('orderby', $orderby);		
        $view->assign('product_count', $product_count_sel);
        $view->assign('sorting', $sorting_sel);
        $view->assign('categorys_sel', $categorys_sel);
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
?>