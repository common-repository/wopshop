<?php
class ManufacturersWshopAdminController extends WshopAdminController {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUrlListItems(){
        return "admin.php?page=wopshop-options&tab=manufacturers";
    }

    public function display() {
        $context = "admin.manufacturers.";
        $filter_order = wopshopGetStateFromRequest($context.'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = wopshopGetStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc');

        $model = $this->getModel("manufacturers");

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
        );
        $bulk = $model->getBulkActions($actions);

        if($filter_order_Dir == 'asc') $filter_order_Dir = 'desc'; else $filter_order_Dir = 'asc';

        $rows = $model->getAllManufacturers(0, $filter_order, $filter_order_Dir);
        $view = $this->getView('manufacturers');
        $view->setLayout("list");
        $view->assign('rows', $rows);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('bulk', $bulk);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeDisplayManufacturers', array(&$view));
        $view->display();
    }
    
    public function edit(){
        $man_id = WopshopRequest::getInt('row');

        $manufacturer = WopshopFactory::getTable('manufacturer');
        $manufacturer->load($man_id);
        $edit = $man_id ? 1 : 0;
        
        if (!$man_id){
            $manufacturer->manufacturer_publish = 1;
        }
        
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;
        $nofilter = array();
        //FilterOutput::objectHTMLSafe( $manufacturer, ENT_QUOTES, $nofilter);

        $view = $this->getView('manufacturers');
        $view->setLayout('edit');
        $view->assign('manufacturer', $manufacturer);
        $view->assign('listlanguages', $languages);
        $view->assign('multilang', $multilang);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditManufacturers', array(&$view));
        $view->display();
    }
    
    public function save(){
        check_admin_referer('manufacturer_edit','name_of_nonce_field');
        $config = WopshopFactory::getConfig();

        require_once ($config->path.'lib/image.lib.php');
        require_once ($config->path.'lib/uploadfile.class.php');
        
        $_alias = $this->getModel("alias");
        global $wpdb;
        $man = WopshopFactory::getTable('manufacturer');
        $man_id = WopshopRequest::getInt("manufacturer_id");

        $post = WopshopRequest::get("post");
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
            $post['name_'.$lang->language] = trim($post['name_'.$lang->language]);
            if ($config->create_alias_product_category_auto && $post['alias_'.$lang->language]=="") $post['alias_'.$lang->language] = $post['name_'.$lang->language];
			$post['alias_'.$lang->language] = sanitize_title_with_dashes($post['alias_'.$lang->language]);
            if ($post['alias_'.$lang->language]!="" && !$_alias->checkExistAlias1Group($post['alias_'.$lang->language], $lang->language, 0, $man_id)){
                $post['alias_'.$lang->language] = "";
                wopshopAddMessage(WOPSHOP_ERROR_ALIAS_ALREADY_EXIST, 'error');
            }
            $post['description_'.$lang->language] = WopshopRequest::getVar('description'.$lang->id,'','post',"string", 2);
            $post['short_description_'.$lang->language] = WopshopRequest::getVar('short_description_'.$lang->language,'','post',"string", 2);
        }
        
        if (!$post['manufacturer_publish']){
            $post['manufacturer_publish'] = 0;
        }
		do_action_ref_array( 'onBeforeSaveManufacturer', array(&$post) );
        if (!$man->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=manufacturers");
            return 0;
        }
        
        if (!$man_id){
            $man->ordering = null;
            $man->ordering = $man->getNextOrder();            
        }        
        
        $upload = new WopshopUploadFile($_FILES['manufacturer_logo']);
        $upload->setAllowFile(array('jpeg','jpg','gif','png'));
        $upload->setDir($config->image_manufs_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        if ($upload->upload()){            
            if ($post['old_image']){
                @unlink($config->image_manufs_path."/".$post['old_image']);
            }
            $name = $upload->getName();
            @chmod($config->image_manufs_path."/".$name, 0777);
            
            if($post['size_im_category'] < 3){
                if($post['size_im_category'] == 1){
                    $category_width_image = $config->image_category_width; 
                    $category_height_image = $config->image_category_height;
                }else{
                    $category_width_image = WopshopRequest::getInt('category_width_image'); 
                    $category_height_image = WopshopRequest::getInt('category_height_image');
                }

                $path_full = $config->image_manufs_path."/".$name;
                $path_thumb = $config->image_manufs_path."/".$name;

                if (!ImageLib::resizeImageMagic($path_full, $category_width_image, $category_height_image, $config->image_cut, $config->image_fill, $path_thumb, $config->image_quality, $config->image_fill_color)) {
                    wopshopAddMessage(WOPSHOP_ERROR_CREATE_THUMBAIL);
                }
                @chmod($config->image_manufs_path."/".$name, 0777);    
                unset($img);
            }
            $man->manufacturer_logo = $name;
        }else{
            if ($upload->getError() != 4){
                wopshopAddMessage(WOPSHOP_ERROR_UPLOADING_IMAGE, 'error');
            }
        }
        if (!$man->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE, 'error');
            $this->setRedirect("admin.php?page=wopshop-options&tab=manufacturers");
            return 0;
        }
		do_action_ref_array( 'onAfterSaveManufacturer', array(&$man) );
        $this->setRedirect("admin.php?page=wopshop-options&tab=manufacturers", WOPSHOP_MESSAGE_SAVEOK);
    }
    
    public function deleteFoto(){
        $id = WopshopRequest::getInt("id");
        $config = WopshopFactory::getConfig();
        $manuf = WopshopFactory::getTable('manufacturer');
        $manuf->load($id);
        @unlink($config->image_manufs_path.'/'.$manuf->manufacturer_logo);
        $manuf->manufacturer_logo = "";
        $manuf->store();        
        die();
    }
}