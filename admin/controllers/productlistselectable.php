<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class ProductListSelectableWshopAdminController extends WshopAdminController {
    function __construct() {
        parent::__construct();
    }
    function display(){
        WopshopFactory::loadLanguageFile(null, 1);
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $prodMdl = $this->getModel('products');

        $context = "admin.productselectable.";
        //$limit = wopshopGetStateFromRequest($context.'limit', 'limit', 'list_limit');
        $limit = wopshopGetStateFromRequest( $context.'per_page', 'per_page', 999);
        $limitstart = wopshopGetStateFromRequest($context.'limitstart', 'limitstart', 0);

        $paged = wopshopGetStateFromRequest($context.'paged', 'paged', 1);
        $per_page = wopshopGetStateFromRequest('categories_per_page', 'per_page', 999);
        $cat_id = WopshopRequest::get('category_id');

        if (isset($cat_id) && $cat_id === "0"){
            $mainframe->setUserState($context.'category_id', 0);
            $mainframe->setUserState($context.'manufacturer_id', 0);
            $mainframe->setUserState($context.'label_id', 0);
            $mainframe->setUserState($context.'publish', 0);
            $mainframe->setUserState($context.'text_search', '');
        }

        $category_id = wopshopGetStateFromRequest($context.'category_id', 'category_id', 0);
        $manufacturer_id = wopshopGetStateFromRequest($context.'manufacturer_id', 'manufacturer_id', 0);
        $label_id = wopshopGetStateFromRequest($context.'label_id', 'label_id', 0);
        $publish = wopshopGetStateFromRequest($context.'publish', 'publish', 0);
        $text_search = wopshopGetStateFromRequest($context.'text_search', 'text_search', '');
        $eName = WopshopRequest::getVar('e_name');
        $jsfname = WopshopRequest::getVar('jsfname');
        $eName = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);        
        if (!$jsfname) $jsfname = 'selectProductBehaviour';

        $filter = array("category_id" => $category_id,"manufacturer_id" => $manufacturer_id,"label_id" => $label_id,"publish" => $publish,"text_search" => $text_search);
        $total = $prodMdl->getCountAllProducts($filter);
        //$pagination = new WopshopPagination($total, $limitstart, $limit);
        if(($paged-1) > ($total/$limit) ) $paged = 1;
        $limitstart = ($paged-1)*$limit;
        $pagination = $prodMdl->getPagination($total, $per_page);
        $search = $prodMdl->search($text_search);
        
        $rows = $prodMdl->getAllProducts($filter, $limitstart, $limit);

        $parentTop = new stdClass();
        $parentTop->category_id = 0;
        $parentTop->name = " - - - ";
        $categories_select = wopshopBuildTreeCategory(0,1,0);

        array_unshift($categories_select, $parentTop);  

        $lists['treecategories'] = WopshopHtml::_('select.genericlist', $categories_select, 'category_id', 'style="width: 150px;"', 'category_id', 'name', $category_id);

        $manuf1 = array();
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = " - - - ";
        $manufs = $this->getModel('Manufacturers')->getList();

        $manufs = array_merge($manuf1, $manufs);
        $lists['manufacturers'] = WopshopHtml::_('select.genericlist', $manufs, 'manufacturer_id', 'style="style="width: 150px;" onchange="document.search.submit();"', 'manufacturer_id', 'name', $manufacturer_id);

        if ($config->admin_show_product_labels) {
            $alllabels = $this->getModel('ProductLabels')->getList();
            $first = array();
            $first[] = WopshopHtml::_('select.option', '0'," - - - ", 'id','name');        
            $lists['labels'] = WopshopHtml::_('select.genericlist', array_merge($first, $alllabels), 'label_id', 'style="width: 80px;" onchange="document.search.submit();"','id','name', $label_id);
        }
        $f_option = array();
        $f_option[] = WopshopHtml::_('select.option', 0, " - - - ", 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 1, WOPSHOP_PUBLISH, 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 2, WOPSHOP_UNPUBLISH, 'id', 'name');
        $lists['publish'] = WopshopHtml::_('select.genericlist', $f_option, 'publish', 'style="width: 100px;" onchange="document.search.submit();"', 'id', 'name', $publish);
        $search = $prodMdl->search($text_search);
        $view = $this->getView('products');
        $view->setLayout("selectable");
        $view->assign('rows', $rows);
        $view->assign('lists', $lists);
        $view->assign('category_id', $category_id);
        $view->assign('manufacturer_id', $manufacturer_id);
        $view->assign('pagination', $pagination);
        $view->assign('text_search', $text_search);
        $view->assign('search', $search);
        $view->assign('config', $config);        
        $view->assign('eName', $eName);
        
        $view->assign('jsfname', $jsfname);
        do_action_ref_array('onBeforeDisplayProductListSelectable', array(&$view));
        $view->display();
    }

}
?>		