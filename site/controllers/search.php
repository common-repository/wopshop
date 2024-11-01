<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WopshopSearchController extends WshopController{
    
    function __construct(){
        parent::__construct();
        do_action_ref_array('onConstructWshopControllerSearch', array(&$this));
        WopshopFactory::loadDatepicker();
    }
    
    function view(){
    	$config = WopshopFactory::getConfig();
        $category_id = WopshopRequest::getInt('category_id');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("search");
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);         

        if ($config->admin_show_product_extra_field){
            $urlsearchcaracters = esc_url(wopshopSEFLink("controller=search&task=get_html_characteristics&ajax=1",0,1));
            $change_cat_val = "onchange='updateSearchCharacteristic(\"".$urlsearchcaracters."\",this.value);'";
        }else{
            $change_cat_val = "";
        }
	$categories = wopshopBuildTreeCategory(1);
        $first = WopshopHtml::_('select.option', 0, WOPSHOP_SEARCH_ALL_CATEGORIES, 'category_id', 'name' );
		array_unshift($categories, $first);
        $list_categories = WopshopHtml::_('select.genericlist', $categories, 'category_id', 'class = "inputbox" size = "1" '.$change_cat_val, 'category_id', 'name' );
		
        $first = WopshopHtml::_('select.option', 0, WOPSHOP_SEARCH_ALL_MANUFACTURERS, 'manufacturer_id', 'name');
        $_manufacturers = WopshopFactory::getTable('manufacturer');
        $manufacturers = $_manufacturers->getList();
		array_unshift($manufacturers, $first);
        $list_manufacturers = WopshopHtml::_('select.genericlist', $manufacturers, 'manufacturer_id', 'class = "inputbox" size = "1"','manufacturer_id','name' );
        
        if ($config->admin_show_product_extra_field){
            $characteristic_fields = WopshopFactory::wopshopGetAllProductExtraField();
            $characteristic_fieldvalues = WopshopFactory::wopshopGetAllProductExtraFieldValueDetail();
            $characteristic_displayfields = WopshopFactory::getDisplayFilterExtraFieldForCategory($category_id);
        }
        
        $characteristics = "";
        if ($config->admin_show_product_extra_field){ 
            $view_name = "search";
            $view = $this->getView($view_name);
            $view->setLayout("characteristics");
            $view->assign('characteristic_fields', $characteristic_fields);
            $view->assign('characteristic_fieldvalues', $characteristic_fieldvalues);
            $view->assign('characteristic_displayfields', $characteristic_displayfields);
            $view->tmp_ext_search_html_characteristic_start = "";
            $view->tmp_ext_search_html_characteristic_end = "";
            $characteristics = $view->loadTemplate();
        }

        $view_name = "search";
        $view = $this->getView($view_name);
        $view->setLayout("form");
        $view->assign('list_categories', $list_categories);
        $view->assign('list_manufacturers', $list_manufacturers);
        $view->assign('characteristics', $characteristics);
        $view->assign('config', $config);
        $view->assign('action', esc_url(wopshopSEFLink("controller=search&task=result")));
        $view->_tmp_ext_search_html_start = "";
        $view->_tmp_ext_search_html_end = "";
        do_action_ref_array('onBeforeDisplaySearchFormView', array(&$view));
        $view->display();
    }
    
    function result(){
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        global $wpdb;
        $user = WopshopFactory::getUser();
        $session = WopshopFactory::getSession();
        $session->set("wshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("wshop_end_page_list_product", $_SERVER['REQUEST_URI']);
        do_action_ref_array('onBeforeLoadProductList', array());
        
        $product = WopshopFactory::getTable('product');
        $seo = WopshopFactory::getTable("seo");
        $seodata = $seo->loadData("search-result");
        $this->addMetaTag('description', $seodata->description);
        $this->addMetaTag('keyword', $seodata->keyword);
        $this->addMetaTag('title', $seodata->title);  
        
        $post = WopshopRequest::get('request');
        if (isset($post['setsearchdata']) && $post['setsearchdata']==1){
            $session->set("wshop_end_form_data", $post);
        }else{
            $data = $session->get("wshop_end_form_data");
            if (count($data)){
                $post = $data;
            }
        }

        $category_id = intval($post['category_id']);
        $manufacturer_id = intval($post['manufacturer_id']);
        if (isset($post['date_to'])) 
            $date_to = $post['date_to'];
        else 
            $date_to = null;
        
        if (isset($post['date_from'])) 
            $date_from = $post['date_from'];
        else 
            $date_from = null;
        
        if (isset($post['price_to'])) 
            $price_to = wopshopSaveAsPrice($post['price_to']);
        else 
            $price_to = null;
        
        if (isset($post['price_from'])) 
            $price_from = wopshopSaveAsPrice($post['price_from']);
        else 
            $price_from = null;
        
        if (isset($post['include_subcat']))
            $include_subcat = intval($post['include_subcat']);
        else
            $include_subcat = 0;

        $search = trim($post['search']);
        $search_type = $post['search_type'];
        if (!$search_type) $search_type = "any";

        $context = "wshop.searclist.front.product";
        $orderby = $mainframe->getUserStateFromRequest($context.'orderby', 'orderby', $config->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest($context.'order', 'order', $config->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest($context.'limit', 'limit', $config->count_products_to_page, 'int');
        if (!$limit) $limit = $config->count_products_to_page;
        $limitstart = WopshopRequest::getInt('limitstart', 0);
        if ($order==4){
            $order = 1;
        }

        if ($config->admin_show_product_extra_field){
            if (isset($post['extra_fields'])) 
                $extra_fields = $post['extra_fields'];
            else
                $extra_fields = array();
            $extra_fields = wopshopFilterAllowValue($extra_fields, "array_int_k_v+");
        }
        
        $categorys = array();
        if ($category_id) {
            if ($include_subcat){
                $_category = WopshopFactory::getTable('category');
                $all_categories = $_category->getAllCategories();
                $cat_search[] = $category_id;
                wopshopSearchChildCategories($category_id, $all_categories, $cat_search);
                foreach ($cat_search as $key=>$value) {
                    $categorys[] = $value;
                }
            }else{
                $categorys[] = $category_id;
            }
        }
        
        $orderbyq = wopshopGetQuerySortDirection($order, $orderby);
        $image_sort_dir = wopshopGetImgSortDirection($order, $orderby);
        
        $filters = array();
        $filters['categorys'] = $categorys;
        if ($manufacturer_id){
            $filters['manufacturers'][] = $manufacturer_id;
        }
        $filters['price_from'] = $price_from;
        $filters['price_to'] = $price_to;
        if ($config->admin_show_product_extra_field){
            $filters['extra_fields'] = $extra_fields;
        }

        $adv_query = ""; $adv_from = ""; $adv_result = $product->getBuildQueryListProductDefaultResult();
        $product->getBuildQueryListProduct("search", "list", $filters, $adv_query, $adv_from, $adv_result);        

        if ($date_to && wopshopCheckDate($date_to)) {
            $adv_query .= " AND prod.product_date_added <= '".esc_sql($date_to)."'";
        }
        if ($date_from && wopshopCheckDate($date_from)) {
            $adv_query .= " AND prod.product_date_added >= '".esc_sql($date_from)."'";
        }
        
        $where_search = "";
        if ($search_type=="exact"){
            $word = addcslashes(esc_sql($search), "_%");
            $tmp = array();
            foreach($config->product_search_fields as $field){
                $tmp[] = "LOWER(".wopshopGetDBFieldNameFromConfig($field).") LIKE '%".$word."%'";
            }
            $where_search = implode(' OR ', $tmp);
        }else{        
            $words = explode(" ", $search);
            $search_word = array();
            foreach($words as $word){
                $word = addcslashes(esc_sql($word), "_%");
                $tmp = array();
                foreach($config->product_search_fields as $field){
                    $tmp[] = "LOWER(".wopshopGetDBFieldNameFromConfig($field).") LIKE '%".$word."%'";
                }
                $where_search_block = implode(' OR ', $tmp);
                $search_word[] = "(".$where_search_block.")";
            }
            if ($search_type=="any"){
                $where_search = implode(" OR ", $search_word);
            }else{
                $where_search = implode(" AND ", $search_word);
            }
        }
        if ($where_search) $adv_query .= " AND ($where_search)";

        $orderbyf = $config->sorting_products_field_s_select[$order];
        $order_query = $product->getBuildQueryOrderListProduct($orderbyf, $orderbyq, $adv_from);
        
        do_action_ref_array('onBeforeQueryGetProductList', array("search", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters));
              
        $query = "SELECT count(distinct prod.product_id) FROM `".$wpdb->prefix."wshop_products` AS prod
                  LEFT JOIN `".$wpdb->prefix."wshop_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `".$wpdb->prefix."wshop_categories` AS cat ON pr_cat.category_id = cat.category_id                  
                  $adv_from
                  WHERE prod.product_publish = '1' AND cat.category_publish='1'
                  $adv_query";
        $total = $wpdb->get_var($query);
        
        if (!$total) {
            $view_name = "search";
            $view = $this->getView($view_name);
            $view->setLayout("noresult");
            $view->assign('search', $search);
            $view->display();
            return 0;
        }
        
		do_action_ref_array('onBeforeFixLimitstartDisplayProductList', array(&$limitstart, &$total, 'search'));
        if ($limitstart>=$total) $limitstart = 0;
        

        $query = "SELECT $adv_result FROM `".$wpdb->prefix."wshop_products` AS prod
                  LEFT JOIN `".$wpdb->prefix."wshop_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `".$wpdb->prefix."wshop_categories` AS cat ON pr_cat.category_id = cat.category_id                  
                  $adv_from
                  WHERE prod.product_publish = '1' AND cat.category_publish='1'
                  $adv_query
                  GROUP BY prod.product_id ".$order_query;
        if($total>$limit)
            $query .= ' LIMIT '.$limitstart.','.$limit;
        $rows = $wpdb->get_results($query);
        $rows = wopshopListProductUpdateData($rows);
        wopshopAddLinkToProducts($rows, 0, 1);
        
        $pagination = new WopshopPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        
        foreach ($config->sorting_products_name_s_select as $key => $value) {
            $sorts[] = WopshopHtml::_('select.option', $key, $value, 'sort_id', 'sort_value' );
        }

        wopshopInsertValueInArray($config->count_products_to_page, $config->count_product_select);
        foreach ($config->count_product_select as $key => $value) {
            $product_count[] = WopshopHtml::_('select.option',$key, $value, 'count_id', 'count_value' );
        }
        $sorting_sel = WopshopHtml::_('select.genericlist', $sorts, 'order', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $order );
        $product_count_sel = WopshopHtml::_('select.genericlist', $product_count, 'limit', 'class = "inputbox" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit );
        
        $_review = WopshopFactory::getTable('review');
        $allow_review = $_review->getAllowReview();
        
        $action = wopshopXhtmlUrl($_SERVER['REQUEST_URI']);
        
        do_action_ref_array('onBeforeDisplayProductList', array(&$rows));

        $view_name = "search";
        $view=$this->getView($view_name);
        $view->setLayout("products");
        $view->assign('search', $search);
        $view->assign('total', $total);
        $view->assign('config', $config);
        $view->assign('template_block_list_product', "list_products/list_products.php");
        $view->assign('template_block_form_filter', "list_products/form_filters.php");
        $view->assign('template_block_pagination', "list_products/block_pagination.php");
        $view->assign('path_image_sorting_dir', $config->live_path.'/assets/images/'.$image_sort_dir);
        $view->assign('filter_show', 0);
        $view->assign('filter_show_category', 0);
        $view->assign('filter_show_manufacturer', 0);
        $view->assign('pagination', $pagenav);
        $view->assign('pagination_obj', $pagination);
        $view->assign('display_pagination', $pagenav!="");
        $view->assign('product_count', $product_count_sel);
        $view->assign('sorting', $sorting_sel);
        $view->assign('action', $action);
        $view->assign('orderby', $orderby);
        $view->assign('count_product_to_row', $config->count_products_to_row);
        $view->assign('rows', $rows);
        $view->assign('allow_review', $allow_review);
        $view->assign('shippinginfo', esc_url(wopshopSEFLink($config->shippinginfourl)));
        $view->_tmp_list_products_html_start = "";
        $view->_tmp_list_products_html_end  = "";
        do_action_ref_array('onBeforeDisplayProductListView', array(&$view));
        $view->display();
    }
    
    function get_html_characteristics(){
        $config = WopshopFactory::getConfig();
        $category_id = WopshopRequest::getInt("category_id");
        if ($config->admin_show_product_extra_field){
            $characteristic_fields = WopshopFactory::wopshopGetAllProductExtraField();
            $characteristic_fieldvalues = WopshopFactory::wopshopGetAllProductExtraFieldValueDetail();
            $characteristic_displayfields = WopshopFactory::getDisplayFilterExtraFieldForCategory($category_id);              
            $view_name = "search";
            $view=$this->getView($view_name);
            $view->setLayout("characteristics");
            $view->assign('characteristic_fields', $characteristic_fields);
            $view->assign('characteristic_fieldvalues', $characteristic_fieldvalues);
            $view->assign('characteristic_displayfields', $characteristic_displayfields);
            $view->tmp_ext_search_html_characteristic_start = "";
            $view->tmp_ext_search_html_characteristic_end = "";
            do_action_ref_array('onBeforeDisplaySearchHtmlCharacteristics', array(&$view));
            $view->display();
        }
    die();
    }
}
?>