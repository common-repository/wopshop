<?php
class ConfigurationWshopAdminController extends WshopAdminController {
    
    const CONFIGURATION_ID = 1;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function display() {
        //WopshopFactory::loadNunitoFonts();
        $view = $this->getView('configuration');
        $submenu = wopshopGetItemsConfigPanelMenu();
        $view->items = $submenu;
        $view->display();
    }
    
    public function adminfunction() {
        $config = WopshopFactory::getConfig();
        $shop_register_type = array();
        $shop_register_type[] = WopshopHtml::_('select.option', 0, "-", 'id', 'name' );
        $shop_register_type[] = WopshopHtml::_('select.option', 1, WOPSHOP_MEYBY_SKIP_REGISTRATION, 'id', 'name' );
        $shop_register_type[] = WopshopHtml::_('select.option', 2, WOPSHOP_WITHOUT_REGISTRATION, 'id', 'name' );
        $lists['shop_register_type'] = WopshopHtml::_('select.genericlist', $shop_register_type, 'shop_user_guest','class = "inputbox" size = "1"','id','name', $config->shop_user_guest);
        
        $opt = array();
        $opt[] = WopshopHtml::_('select.option', 0, WOPSHOP_NORMAL, 'id', 'name');
        $opt[] = WopshopHtml::_('select.option', 1, WOPSHOP_DEVELOPER, 'id', 'name');
        $lists['shop_mode'] = WopshopHtml::_('select.genericlist', $opt, 'shop_mode','class = "inputbox"','id','name', $config->shop_mode);

        $view = $this->getView('configuration');
        $view->setLayout("adminfunction");
        $view->assign("lists", $lists);
        $view->assign("config", $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditConfigAdminFunction', array(&$view));
        $view->display();
    }
    
    public function general() {
        $config = WopshopFactory::getConfig();
        $model = $this->getModel('configuration');
        $langanguages = $model->getListLanguages();
        $lists['languages'] = WopshopHtml::_('select.genericlist', $langanguages, 'defaultLanguage', '', 'language', 'name', $config->defaultLanguage);
        
        $display_price_list = array();
        $display_price_list[] = WopshopHtml::_('select.option', 0, WOPSHOP_PRODUCT_BRUTTO_PRICE, 'id', 'name');
        $display_price_list[] = WopshopHtml::_('select.option', 1, WOPSHOP_PRODUCT_NETTO_PRICE, 'id', 'name');
        
        $lists['display_price_admin'] = WopshopHtml::_('select.genericlist', $display_price_list, 'display_price_admin', '', 'id', 'name', $config->display_price_admin);
        $lists['display_price_front'] = WopshopHtml::_('select.genericlist', $display_price_list, 'display_price_front', '', 'id', 'name', $config->display_price_front);
        $lists['template'] = wopshopGetShopTemplatesSelect($config->template);

        $view = $this->getView('configuration');
        $view->setLayout("general");
        $view->assign("lists", $lists);
        $view->assign("config", $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditConfigGeneral', array(&$view));
        $view->display();
    }
    
    public function catprod() {
        $config = WopshopFactory::getConfig();

        $displayprice = array();
        $displayprice[] = WopshopHtml::_('select.option', 0, WOPSHOP_YES, 'id', 'value');
        $displayprice[] = WopshopHtml::_('select.option', 1, WOPSHOP_NO, 'id', 'value');
        $displayprice[] = WopshopHtml::_('select.option', 2, WOPSHOP_ONLY_REGISTER_USER, 'id', 'value');
        $lists['displayprice'] = WopshopHtml::_('select.genericlist', $displayprice, 'displayprice','','id','value', $config->displayprice);

        $catsort = array();
        $catsort[] = WopshopHtml::_('select.option', 1, WOPSHOP_SORT_MANUAL, 'id','value');
        $catsort[] = WopshopHtml::_('select.option', 2, WOPSHOP_SORT_ALPH, 'id','value');
        $lists['category_sorting'] = WopshopHtml::_('select.genericlist', $catsort, 'category_sorting','','id','value', $config->category_sorting);
        $lists['manufacturer_sorting'] = WopshopHtml::_('select.genericlist', $catsort, 'manufacturer_sorting','','id','value', $config->manufacturer_sorting);

        $sortd = array();
        $sortd[] = WopshopHtml::_('select.option', 0, WOPSHOP_A_Z, 'id','value');
        $sortd[] = WopshopHtml::_('select.option', 1, WOPSHOP_Z_A, 'id','value');
        $lists['product_sorting_direction'] = WopshopHtml::_('select.genericlist', $sortd, 'product_sorting_direction','','id','value', $config->product_sorting_direction);

        $opt = array();
        $opt[] = WopshopHtml::_('select.option', 'V.value_ordering', WOPSHOP_SORT_MANUAL, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'value_name', WOPSHOP_SORT_ALPH, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'PA.price', WOPSHOP_SORT_PRICE, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'PA.ean', WOPSHOP_EAN_PRODUCT, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'PA.count', WOPSHOP_QUANTITY_PRODUCT, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'PA.product_attr_id', WOPSHOP_SPECIFIED_IN_PRODUCT, 'id','value');
        $lists['attribut_dep_sorting_in_product'] = WopshopHtml::_('select.genericlist', $opt, 'attribut_dep_sorting_in_product','','id','value', $config->attribut_dep_sorting_in_product);

        $opt = array();
        $opt[] = WopshopHtml::_('select.option', 'V.value_ordering', WOPSHOP_SORT_MANUAL, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'value_name', WOPSHOP_SORT_ALPH, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'addprice', WOPSHOP_SORT_PRICE, 'id','value');
        $opt[] = WopshopHtml::_('select.option', 'PA.id', WOPSHOP_SPECIFIED_IN_PRODUCT, 'id','value');
        $lists['attribut_nodep_sorting_in_product'] = WopshopHtml::_('select.genericlist', $opt, 'attribut_nodep_sorting_in_product','','id','value', $config->attribut_nodep_sorting_in_product);

        $select = array();

        foreach ($config->sorting_products_name_select as $key => $value) {
            $select[] = WopshopHtml::_('select.option', $key, $value, 'id', 'value');
        }
        $lists['product_sorting'] = WopshopHtml::_('select.genericlist',$select, "product_sorting", '', 'id','value', $config->product_sorting);

        if ($config->admin_show_product_extra_field){
            $_productfields = $this->getModel("productFields");
            $rows = $_productfields->getList();

            $lists['product_list_display_extra_fields'] = WopshopHtml::_('select.genericlist', $rows, "product_list_display_extra_fields[]", ' size="10" multiple = "multiple" ', 'id','name', $config->wopshopGetProductListDisplayExtraFields());
            $lists['filter_display_extra_fields'] = WopshopHtml::_('select.genericlist', $rows, "filter_display_extra_fields[]", ' size="10" multiple = "multiple" ', 'id','name', $config->getFilterDisplayExtraFields());
            $lists['product_hide_extra_fields'] = WopshopHtml::_('select.genericlist', $rows, "product_hide_extra_fields[]", ' size="10" multiple = "multiple" ', 'id','name', $config->getProductHideExtraFields());
            $lists['cart_display_extra_fields'] = WopshopHtml::_('select.genericlist', $rows, "cart_display_extra_fields[]", ' size="10" multiple = "multiple" ', 'id','name', $config->getCartDisplayExtraFields());
        }

        $_units = $this->getModel("units");
        $list_units = $_units->getUnits();
        $lists['units'] = WopshopHtml::_('select.genericlist',$list_units, "main_unit_weight", '', 'id','name', $config->main_unit_weight);

        $view = $this->getView('configuration');
        $view->setLayout("categoryproduct");
        $view->assign("lists", $lists);
        $view->assign("config", $config); 
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
		do_action_ref_array('onBeforeEditConfigCatProd', array(&$view));
        $view->display();
    } 

    public function checkout() {
        $config = WopshopFactory::getConfig();
        $_orders = $this->getModel("orders");
        $order_status = $_orders->getAllOrderStatus();
        $lists['status'] = WopshopHtml::_('select.genericlist', $order_status,'default_status_order','class = "inputbox" size = "1"','status_id','name', $config->default_status_order);
        $currency_code = wopshopGetMainCurrencyCode();        
        $_countries = $this->getModel("countries");
        $countries = $_countries->getAllCountries(0);
        $first = WopshopHtml::_('select.option', 0,WOPSHOP_SELECT,'country_id','name' );
        array_unshift($countries,$first);
        $lists['default_country'] = WopshopHtml::_('select.genericlist', $countries, 'default_country','class = "inputbox" size = "1"','country_id','name', $config->default_country);
        
        $vendor_order_message_type = array();
        $vendor_order_message_type[] = WopshopHtml::_('select.option', 0, WOPSHOP_NOT_SEND_MESSAGE, 'id', 'name' );
        $vendor_order_message_type[] = WopshopHtml::_('select.option', 1, WOPSHOP_WE_SEND_MESSAGE, 'id', 'name' );
        $vendor_order_message_type[] = WopshopHtml::_('select.option', 2, WOPSHOP_WE_SEND_ORDER, 'id', 'name' );
        $vendor_order_message_type[] = WopshopHtml::_('select.option', 3, WOPSHOP_WE_ALWAYS_SEND_ORDER, 'id', 'name' );
        $lists['vendor_order_message_type'] = WopshopHtml::_('select.genericlist', $vendor_order_message_type, 'vendor_order_message_type','class = "inputbox" size = "1"','id','name', $config->vendor_order_message_type);

        $option = array();
        $option[] = WopshopHtml::_('select.option', 0, WOPSHOP_STEP_3_4, 'id', 'name');
        $option[] = WopshopHtml::_('select.option', 1, WOPSHOP_STEP_4_3, 'id', 'name');
        $lists['step_4_3'] = WopshopHtml::_('select.genericlist', $option, 'step_4_3','class = "inputbox"','id','name', $config->step_4_3);
        
        $view = $this->getView('configuration');
        $view->assign("config", $config); 
        $view->assign("lists", $lists);
        $view->setLayout('checkout');
        $view->currency_code = $currency_code;
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
		do_action_ref_array('onBeforeEditConfigCheckout', array(&$view));
        $view->display();
    }

    public function fieldregister() {
        $config = WopshopFactory::getConfig();
        $view = $this->getView("configuration");
        $view->setLayout("fieldregister");
        include($config->path.'lib/default_config.php');

        $current_fields = $config->getListFieldsRegister();
        $view->assign("fields", $fields_client);
        $view->assign("current_fields", $current_fields);
        $view->assign("fields_sys", $fields_client_sys);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
		do_action_ref_array('onBeforeEditConfigFieldRegister', array(&$view));
        $view->display();
    } 

    public function currency() {
        $config = WopshopFactory::getConfig();
        $_currencies = $this->getModel("currencies");
        $currencies = $_currencies->getAllCurrencies();
        $lists['currencies'] = WopshopHtml::_('select.genericlist', $currencies,'mainCurrency','class = "inputbox" size = "1"','currency_id','currency_name',$config->mainCurrency);
        $i = 0;
        foreach ($config->format_currency as $key => $value) {
            $currenc[$i] = new stdClass();
            $currenc[$i]->id_cur = $key;
            $currenc[$i]->format = $value;
            $i++;
        }
        $lists['format_currency'] = WopshopHtml::_('select.genericlist', $currenc,'currency_format','class = "inputbox" size = "1"','id_cur','format',$config->currency_format);

        $view = $this->getView('configuration');
        $view->setLayout('currency');
        $view->assign("lists", $lists);
        $view->assign("config", $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
		do_action_ref_array('onBeforeEditConfigCurrency', array(&$view));
        $view->display();
    }
    
    public function image() {    
        $config = WopshopFactory::getConfig();
        
        $resize_type = array();
        $resize_type[] = WopshopHtml::_('select.option', 0, WOPSHOP_CUT, 'id', 'name' );
        $resize_type[] = WopshopHtml::_('select.option', 1, WOPSHOP_FILL, 'id', 'name' );
        $resize_type[] = WopshopHtml::_('select.option', 2, WOPSHOP_STRETCH, 'id', 'name' );
        $select_resize_type = WopshopHtml::_('select.genericlist', $resize_type, 'image_resize_type','class = "inputbox" size = "1"','id','name', $config->image_resize_type);
        
        $view = $this->getView('configuration');
        $view->setLayout('image');
        $view->assign("config", $config); 
        $view->assign("select_resize_type", $select_resize_type);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
		do_action_ref_array('onBeforeEditConfigImage', array(&$view));
        $view->display();
    } 

    public function seo() {
        $config = WopshopFactory::getConfig();
        $_seo = WopshopFactory::getAdminModel("seo");
        $rows = $_seo->getList();
        $view = $this->getView('configuration');
        $view->setLayout('listseo');
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->assign("rows", $rows); 
		do_action_ref_array('onBeforeDisplaySeo', array(&$view));
        $view->display();
    } 

    public function seoedit(){
        $id = WopshopRequest::getInt("id");

        $seo = WopshopFactory::getTable("seo");
        $seo->load($id);

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;

        
        $view = $this->getView("configuration");
        $view->setLayout("editseo");
        $view->assign('row', $seo);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
		
        do_action_ref_array('onBeforeDisplaySeoEdit', array(&$view));
        $view->display();
    } 

    public function saveseo(){
        $id = WopshopRequest::getInt("id");
        $post = WopshopRequest::get("post");
        do_action_ref_array( 'onBeforeSaveConfigSeo', array(&$post) );
        
        $seo = WopshopFactory::getTable("seo");
        $seo->load($id);
        $seo->bind($post);        
        if (!$id){
            $seo->ordering = null;
            $seo->ordering = $seo->getNextOrder();            
        }        
        $result = $seo->store($post);
		do_action_ref_array( 'onAfterSaveConfigSeo', array(&$seo) );
        if ($result){
            $this->setRedirect('admin.php?page=wopshop-configuration&task=seo', WOPSHOP_CONFIG_SUCCESS, 'updated');
        } else{
            $this->setRedirect('admin.php?page=wopshop-configuration&task=seoedit&id='.$id, WOPSHOP_ERROR_CONFIG, 'error');
        }
    }

    public function storeinfo() {
        $config = WopshopFactory::getConfig();
        $vendor = WopshopFactory::getTable('vendor');
        $vendor->loadMain();

    	$_countries = $this->getModel("countries");
        $countries = $_countries->getAllCountries(0);
        $first = WopshopHtml::_('select.option', 0,WOPSHOP_SELECT,'country_id','name' );
        array_unshift($countries, $first);
        $lists['countries'] = WopshopHtml::_('select.genericlist', $countries, 'country', 'class = "inputbox"', 'country_id', 'name', $vendor->country);

        $nofilter = array();

    	$view=$this->getView("configuration");
        $view->setLayout("storeinfo");
        $view->assign("lists", $lists); 
        $view->assign("vendor", $vendor);
        $view->assign("config", $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";        
		do_action_ref_array('onBeforeEditConfigStoreInfo', array(&$view));
        $view->display();
    } 

    public function otherconfig(){
        $wconfig = WopshopFactory::getConfig();
        $config = new stdClass();
        include($wconfig->path.'lib/default_config.php');
        $tax_rule_for = array();
        $tax_rule_for[] = WopshopHtml::_('select.option', 0, WOPSHOP_FIRMA_CLIENT, 'id', 'name' );
        $tax_rule_for[] = WopshopHtml::_('select.option', 1, WOPSHOP_VAT_NUMBER, 'id', 'name' );
        $lists['tax_rule_for'] = WopshopHtml::_('select.genericlist', $tax_rule_for, 'ext_tax_rule_for','class = "inputbox" size = "1"','id','name', $wconfig->ext_tax_rule_for);

        $view=$this->getView("configuration");
        $view->setLayout("otherconfig");
        $view->assign("other_config", $other_config);
        $view->assign("other_config_checkbox", $other_config_checkbox);
        $view->assign("other_config_select", $other_config_select);
        $view->assign("config", $wconfig);
        $view->assign("lists", $lists);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";
        $view->etemplatevar = "";
        do_action_ref_array('onBeforeEditConfigOtherConfig', array(&$view));
        $view->display();
    }
    
    public function save(){
        $config = WopshopFactory::getConfig();
        $tab = WopshopRequest::getInt('tabs');
		
        switch ($tab){
            case 1: $layout = "general"; break;
            case 2: $layout = "currency"; break;
            case 3: $layout = "image"; break;
            case 5: $layout = "storeinfo"; break;
            case 6: $layout = "catprod"; break;
            case 7: $layout = "checkout"; break;
            case 8: $layout = "adminfunction"; break;
            case 9: $layout = "fieldregister"; break;
            case 10: $layout = "otherconfig"; break;
            case 11: $layout = "permalinks"; break;
        }

        global $wpdb;

        $post = WopshopRequest::get("post");
		do_action_ref_array('onBeforeSaveConfig', array(&$post, &$extconf));
        //$row = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix.'wshop_config');
        //$row_id = $row->id;

        //general
        $array = array('display_price_admin', 'display_price_front','use_ssl','savelog','savelogpaymentdata');
        if ($tab == 1){
            foreach ($array as $key => $value) {
                if (!isset($post[$value])) {
                    $post[$value] = 0;
                }
            }
        }

        if ($tab == 5){
            $vendor = WopshopFactory::getTable('vendor');
            $post = WopshopRequest::get("post");
            $vendor->id = $post['vendor_id'];
            $vendor->main = 1;
            $vendor->bind($post);
            $vendor->store();
        }
        //category/product
        $array = array('show_buy_in_category','show_tax_in_product','show_tax_product_in_cart','show_plus_shipping_in_product','hide_product_not_avaible_stock','hide_buy_not_avaible_stock','show_sort_product','show_count_select_products','show_delivery_time','demo_type','product_show_manufacturer_logo','product_show_weight',
                       'product_attribut_first_value_empty', 'show_hits', 'allow_reviews_prod', 'allow_reviews_only_registered','hide_text_product_not_available','use_plugin_content', 'product_list_show_weight', 'product_list_show_manufacturer','show_product_code','product_list_show_min_price', 'show_product_list_filters',
                       'product_list_show_vendor','product_show_vendor','product_show_vendor_detail','product_show_button_back','product_list_show_product_code','radio_attr_value_vertical','attr_display_addprice','product_list_show_price_description','display_button_print','product_list_show_price_default');
        if ($tab == 6){
            foreach ($array as $key => $value) {
                if (!isset($post[$value])) $post[$value] = 0;
            }
            $result = new stdClass();
            if ($config->other_config != ''){
                $result = json_decode($config->other_config);
            }

            //$config = new stdClass();
            include($config->path.'lib/default_config.php');

            foreach($catprod_other_config as $k){
                $result->$k = $post[$k];
            }
            $post['other_config'] = json_encode($result);
        }

        //case
        $array = array('hide_shipping_step', 'hide_payment_step', 'order_send_pdf_client','order_send_pdf_admin','hide_tax', 'show_registerform_in_logintemplate','sorting_country_in_alphabet','show_weight_order', 'discount_use_full_sum','show_cart_all_step_checkout',"show_product_code_in_cart",'show_return_policy_in_email_order',
                        'client_allow_cancel_order', 'admin_not_send_email_order_vendor_order','not_redirect_in_cart_after_buy','calcule_tax_after_discount');
        if ($tab == 7){
            if (!$post['next_order_number']){
                unset($post['next_order_number']);
            }
            foreach($array as $key=>$value){
                if (!isset($post[$value])) $post[$value] = 0;
            }
            $result = new stdClass();
            if ($config->other_config!=''){
                $result = json_decode($config->other_config);
            }
            //$conf = new stdClass();
            include($config->path.'lib/default_config.php');
            foreach($checkout_other_config as $k){
                $result->$k = $post[$k];
            }
            $post['other_config'] = json_encode($result);
        }
        //shop function
        $array = array('without_shipping', 'without_payment', 'enable_wishlist', 'shop_user_guest','user_as_catalog', 'use_rabatt_code', 'admin_show_product_basic_price','admin_show_attributes','admin_show_delivery_time','admin_show_languages','use_different_templates_cat_prod','admin_show_product_video','admin_show_product_related','admin_show_product_files','admin_show_product_bay_price','admin_show_product_basic_price', 'admin_show_product_labels', 'admin_show_product_extra_field','admin_show_vendors','admin_show_freeattributes','use_extend_attribute_data');
        if ($tab == 8){
            foreach ($array as $key => $value) {
                if (!isset($post[$value])) $post[$value] = 0;
            }

            $post['without_shipping'] = intval(!$post['without_shipping']);
            $post['without_payment'] = intval(!$post['without_payment']);
            
            $result = new stdClass();
            if ($config->other_config!=''){
                $result = json_decode($config->other_config);
            }
            //$config = new stdClass();
            include($config->path.'lib/default_config.php');
            foreach($adminfunction_other_config as $k){
                $result->$k = $post[$k];
            }
            $post['other_config'] = json_encode($result);
        }

        if ($tab == 9){
            //$config = new stdClass();
            include($config->path.'lib/default_config.php');
            foreach($fields_client_sys as $k=>$v){
                if(is_array($v))
                foreach($v as $v2){
                    //$post['field'][$k][$v2]['require'] = 1;
                    //$post['field'][$k][$v2]['display'] = 1;
                }
            }

            if(is_array($post['field']))
            foreach($post['field'] as $k=>$v){
                foreach($v as $k2=>$v2){
                    if (!$post['field'][$k][$k2]['display']){
                        $post['field'][$k][$k2]['require'] = 0;
                    }
                }
            }

            $post['fields_register'] = json_encode($post['field']);
        }

        if ($tab == 10){
            $result = new stdClass();
            //$config = new stdClass();
            include($config->path.'lib/default_config.php');

            if ($config->other_config != ''){
                $result = json_decode($config->other_config);
            }

            if(is_array($other_config))
            foreach ($other_config as $k) {
                $result->$k = $post[$k];
            }

            $post['other_config'] = json_encode($result);
        }
        
        if ($tab != 4){
            $configuration = WopshopFactory::getTable('configuration');
            $configuration->load(self::CONFIGURATION_ID);
            if (!$configuration->bind($post)) {
                $this->setRedirect('admin.php?page=wopshop-configuration', WOPSHOP_ERROR_BIND);
                return 0;
            }

            if ($tab == 6 && $config->admin_show_product_extra_field){
                if (!isset($post['product_list_display_extra_fields'])) $post['product_list_display_extra_fields'] = array();
                if (!isset($post['filter_display_extra_fields'])) $post['filter_display_extra_fields'] = array();
                if (!isset($post['product_hide_extra_fields'])) $post['product_hide_extra_fields'] = array();
                if (!isset($post['cart_display_extra_fields'])) $post['cart_display_extra_fields'] = array();
                $configuration->setProductListDisplayExtraFields($post['product_list_display_extra_fields']);
                $configuration->setFilterDisplayExtraFields($post['filter_display_extra_fields']);
                $configuration->setProductHideExtraFields($post['product_hide_extra_fields']);
                $configuration->setCartDisplayExtraFields($post['cart_display_extra_fields']);
            }

            $configuration->transformPdfParameters();

            if (!$configuration->store()) {
                $this->setRedirect('admin.php?page=wopshop-configuration&task='.$layout, WOPSHOP_ERROR_SAVE_DATABASE);
                return 0;
            }            
        }
        
        if ($tab == 11){
            $config->shop_base_page = $configuration->shop_base_page;
            flush_rewrite_rules();
        }

        if (isset($_FILES['header'])){
            if ($_FILES['header']['size']){
                @unlink($config->path."/assets/images/header.jpg");
                move_uploaded_file( $_FILES['header']['tmp_name'],$config->path."/assets/images/header.jpg");
            }
        }

        if (isset($_FILES['footer'])){
            if ($_FILES['footer']['size']){
                @unlink($config->path."/assets/images/footer.jpg");
                move_uploaded_file( $_FILES['footer']['tmp_name'],$config->path."images/footer.jpg");
            }
        }

        if (isset($post['update_count_prod_rows_all_cats']) && $tab == 6 && $post['update_count_prod_rows_all_cats']){
            $count_products_to_page = intval($post['count_products_to_page']);
            $count_products_to_row = intval($post['count_products_to_row']);
            
            $wpdb->query("UPDATE ".$wpdb->prefix."wshop_categories SET products_page = ".$count_products_to_page.", products_row = ".$count_products_to_row);
            $wpdb->query("UPDATE ".$wpdb->prefix."wshop_manufacturers SET products_page = ".$count_products_to_page.", products_row = ".$count_products_to_row);
            
        }
        
		do_action_ref_array('onAfterSaveConfig', array());
        $this->setRedirect('admin.php?page=wopshop-configuration&task='.$layout, WOPSHOP_CONFIG_SUCCESS);
    }
    
    public function preview_pdf(){
        $config = WopshopFactory::getConfig();
        $config->currency_code = "USD";
        $file_generete_pdf_order = $config->file_generete_pdf_order;		
        $order = WopshopFactory::getTable('order');
        $order->prepareOrderPrint = 1;
        $order->firma_name = "Firma";
        $order->f_name = "Fname";
        $order->l_name = 'Lname';
        $order->street = 'Street';
        $order->zip = "Zip"; 
        $order->city = "City";
        $order->country = "Country";
        $order->order_number = wopshopOutputDigit(0,8);
        $order->order_date = strftime($config->store_date_format, time());
        $order->products = array();
        $prod = new stdClass();
        $prod->product_name = "Product name";
        $prod->product_ean = "12345678";
        $prod->product_quantity = 1;
        $prod->product_item_price = 125;
        $prod->product_tax = 19;
        $prod->manufacturer = '';
        $prod->manufacturer_code = '';
        $prod->product_attributes = '';
        $prod->product_freeattributes = '';
        $prod->delivery_time = '';
        $prod->extra_fields = '';
        $prod->_qty_unit = '';
        ////
        $config->user_number_in_invoice = 0;
        ////
        $order->products[] = $prod;
        $order->order_subtotal = 125;
        $order->order_shipping = 20;        
        $display_price = $config->display_price_front;
        if ($display_price==0){
            $order->display_price = 0;
            $order->order_tax_list = array(19 => 23.15);
            $order->order_total = 145;
        }else{
            $order->display_price = 1;
            $order->order_tax_list = array(19 => 27.55);
            $order->order_total = 172.55;
        }
        do_action_ref_array('onBeforeCreateDemoPreviewPdf', array(&$order, &$file_generete_pdf_order));
        require_once($file_generete_pdf_order);
        $order->pdf_file = wopshop_generatePdf($order, $config);
        $config->pdf_orders_live_path."/".$order->pdf_file; 
        //header("Location: ".$config->pdf_orders_live_path."/".$order->pdf_file);
        $this->setRedirect($config->pdf_orders_live_path."/".$order->pdf_file);
        die();
    }
    
    public function permalinks() {
        $config = WopshopFactory::getConfig();
        $pages = get_pages();
        
        $firstPage = array();
        $firstPage[0] = new stdClass();
        $firstPage[0]->ID = 0;
        $firstPage[0]->post_title = "-";       
        
        $lists['shopBasePages'] = WopshopHtml::_('select.genericlist', array_merge($firstPage, $pages), 'shop_base_page', 'class="inputbox"', 'ID', 'post_title', $config->shop_base_page);

        $view = $this->getView('configuration');
        $view->setLayout("permalinks");
        $view->assign("lists", $lists);
        $view->assign("config", $config);
        $view->tmp_html_start = "";
        $view->tmp_html_end = "";        
		do_action_ref_array('onBeforeEditConfigPermalinks', array(&$view));
        $view->display();
    }
}