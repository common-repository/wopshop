<?php
class ReviewsWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=reviews";
    }
   
    function display(){
        $mainframe = WopshopFactory::getApplication();
        $id_vendor_cuser = wopshopGetIdVendorForCUser();
        $reviews_model = $this->getModel("reviews");
        $products_model = $this->getModel("products");
        $context = "list.admin.reviews";
        //$limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', 'list_limit');
        //$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0);
        $category_id = $mainframe->getUserStateFromRequest( $context.'category_id', 'category_id', 0);
        $text_search = $mainframe->getUserStateFromRequest( $context.'text_search', 's', '');
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "pr_rew.review_id");
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'order');

        $limit = wopshopGetStateFromRequest( $context.'per_page', 'per_page', 20);
        $paged = $mainframe->getUserStateFromRequest($context.'paged', 'paged', '1');

        if ($category_id){
            $product_id = $mainframe->getUserStateFromRequest( $context.'product_id', 'product_id', 0, 'int' );
        } else {
            $product_id = null;
        }

        $products_select = "";

        if ($category_id){
            $prod_filter = array("category_id"=>$category_id);
            if ($id_vendor_cuser) $prod_filter['vendor_id'] = $id_vendor_cuser;
            $products = $products_model->getAllProducts($prod_filter, 0, 100);
            if (count($products)) {
                $start_pr_option = JWopshopHtml::_('select.option', '0', WOPSHOP_SELECT_PRODUCT , 'product_id', 'name');
                array_unshift($products, $start_pr_option);   
                $products_select = JWopshopHtml::_('select.genericlist', $products, 'product_id', 'class="chosen-select" onchange="document.adminForm.submit();" size = "1" ', 'product_id', 'name', $product_id);
            }
        }

        $total = $reviews_model->getAllReviews($category_id, $product_id, NULL, NULL, $text_search, "count", $id_vendor_cuser, $filter_order, $filter_order_Dir);

        if(($paged-1) > ($total/$limit) ) $paged = 1;
        $limitstart = ($paged-1)*$limit;

        $pagination = $products_model->getPagination($total, $limit);
        $search = $products_model->search($text_search);

        $reviews = $reviews_model->getAllReviews($category_id, $product_id, $limitstart, $limit, $text_search, "list", $id_vendor_cuser, $filter_order, $filter_order_Dir);

        $start_option = WopshopHtml::_('select.option', '0', WOPSHOP_SELECT_CATEGORY,'category_id','name'); 

        $categories_select = wopshopBuildTreeCategory(0,1,0);
        array_unshift($categories_select, $start_option);

        $categories = WopshopHtml::_('select.genericlist', $categories_select, 'category_id', 'class="chosen-select" onchange="document.adminForm.submit();" size = "1" ', 'category_id', 'name', $category_id);

        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';
        
        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        
        $bulk = $reviews_model->getBulkActions($actions);

        $view=$this->getView("comments");
        $view->setLayout("list");
        $view->assign('categories', $categories);
        $view->assign('reviews', $reviews); 
        $view->assign('limit', $limit);
        $view->assign('limitstart', $limitstart);
        $view->assign('text_search', $text_search); 
        $view->assign('pagination', $pagination); 
        $view->assign('products_select', $products_select);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('search', $search);
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        do_action_ref_array('onBeforeDisplayReviews', array(&$view));		
        $view->display();
     }

     function remove(){
        $reviews_model = $this->getModel("reviews");
        $rows = WopshopRequest::getVar('rows');

        do_action_ref_array('onBeforeRemoveReview', array(&$rows) );

        foreach($rows as $key => $value) {
             $review = WopshopFactory::getTable('review');
             $review->load($value);
             $reviews_model->deleteReview($value);
             $product = WopshopFactory::getTable('product');
             $product->load($review->product_id);
             $product->loadAverageRating();
             $product->loadReviewsCount();
             $product->store();
             unset($product);
             unset($review);
        }
        do_action_ref_array('onAfterRemoveReview', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
     }
     
     function edit(){
        $mainframe = WopshopFactory::getApplication();
        $reviews_model = $this->getModel("reviews");
        //$cid = WopshopRequest::getVar('cid');
        $row = WopshopRequest::getInt('row');
        $review = $reviews_model->getReview($row);

        $config = WopshopFactory::getConfig();
        $options = array();
        $options[] = WopshopHtml::_('select.option', 0, 'none','value','text');
        for($i=1;$i<=$config->max_mark;$i++){
            $options[] = WopshopHtml::_('select.option', $i, $i,'value','text'); 
        }

        $mark = WopshopHtml::_('select.genericlist', $options, 'mark', 'class = "inputbox" size = "1" ', 'value', 'text', $review->mark); 
        
        $view=$this->getView("comments", 'html');
        $view->setLayout("edit");
        /*if ($this->getTask()=='edit'){
            $view->assign('edit', 1);
        }*/
        $view->assign('review', $review); 
        $view->assign('mark', $mark);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->tmp_html_filter = "";
        
        do_action_ref_array('onBeforeEditReviews', array(&$view));
        $view->display();
     }
     
     function save(){
        $review = WopshopFactory::getTable('review');
        $post = WopshopRequest::get('post');
        if (intval($post['review_id'])==0) $post['time'] = wopshopGetJsDate();
        do_action_ref_array( 'onBeforeSaveReview', array(&$post) );
        if (!$post['product_id']){
            wopshopAddMessage(WOPSHOP_ERROR_DATA, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
            return 0;
        }

        if (!$review->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
            return 0;
        }
        if (!$review->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=reviews&task=edit&cid[]=".$review->review_id);
            return 0;
        }

        $product = WopshopFactory::getTable('product');
        $product->load($review->product_id);
        $product->loadAverageRating();
        $product->loadReviewsCount();
        $product->store();
        do_action_ref_array( 'onAfterSaveReview', array(&$review) );
		$this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
    }
     
    function publish(){
        $this->_publish(1);
        $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
    }
    
    function unpublish(){
        $this->_publish(0);
        $this->setRedirect("admin.php?page=wopshop-options&tab=reviews");
    }    
    
    function _publish($flag) {
        $config = WopshopFactory::getConfig();
        global $wpdb;
        $cid = WopshopRequest::getVar('rows');

        do_action_ref_array( 'onBeforePublishReview', array(&$cid, &$flag) );
        foreach ($cid as $key => $value) {
            $wpdb->update( $wpdb->prefix."wshop_products_reviews", array( 'publish' => esc_sql($flag) ), array( 'review_id' => esc_sql($value) ));
            $review = WopshopFactory::getTable('review');
            $review->load($value);
            $product = WopshopFactory::getTable('product');
            $product->load($review->product_id);
            $product->loadAverageRating();
            $product->loadReviewsCount();
            $product->store();
            unset($product);
            unset($review);
        }
        
        do_action_ref_array('onAfterPublishReview', array(&$cid, &$flag) );
    }
}