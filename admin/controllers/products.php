<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ProductsWshopAdminController extends WshopAdminController {

    public function __construct() {
        parent::__construct();
    }

    public function display() {
        $mainframe = WopshopFactory::getApplication();
        $config = WopshopFactory::getConfig();
        $products = $this->getModel("products");
        $id_vendor_cuser = wopshopGetIdVendorForCUser();

        $context = "admin.product.";
        $limit = wopshopGetStateFromRequest($context . 'per_page', 'per_page', 20);
        $paged = wopshopGetStateFromRequest($context . 'paged', 'paged', 1);

        $filter_order = wopshopGetStateFromRequest($context . 'filter_order', 'filter_order', $config->adm_prod_list_default_sorting);
        $filter_order_Dir = wopshopGetStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', $config->adm_prod_list_default_sorting_dir);
        $cat_id = WopshopRequest::get('category_id');
        if (isset($cat_id) && $cat_id === "0") {
            $mainframe->setUserState($context . 'category_id', 0);
            $mainframe->setUserState($context . 'manufacturer_id', 0);
            $mainframe->setUserState($context . 'vendor_id', -1);
            $mainframe->setUserState($context . 'label_id', 0);
            $mainframe->setUserState($context . 'publish', 0);
            $mainframe->setUserState($context . 'text_search', '');
        }

        $category_id = wopshopGetStateFromRequest($context . 'category_id', 'category_id', 0);
        $manufacturer_id = wopshopGetStateFromRequest($context . 'manufacturer_id', 'manufacturer_id', 0);
        $vendor_id = wopshopGetStateFromRequest($context . 'vendor_id', 'vendor_id', -1);
        $label_id = wopshopGetStateFromRequest($context . 'label_id', 'label_id', 0);
        $publish = wopshopGetStateFromRequest($context . 'publish', 'publish', 0);
        $text_search = wopshopGetStateFromRequest($context . 'text_search', 's', '');
        $per_page = wopshopGetStateFromRequest('categories_per_page', 'per_page', 20);

        if ($category_id && $filter_order == 'category')
            $filter_order = 'product_id';

        $filter = array("category_id" => $category_id, "manufacturer_id" => $manufacturer_id, "vendor_id" => $vendor_id, "label_id" => $label_id, "publish" => $publish, "text_search" => $text_search);
        if ($id_vendor_cuser) {
            $filter["vendor_id"] = $id_vendor_cuser;
        }

        $show_vendor = $config->admin_show_vendors;
        if ($id_vendor_cuser)
            $show_vendor = 0;

        $total = $products->getCountAllProducts($filter);
        if (($paged - 1) > ($total / $limit))
            $paged = 1;
        $limitstart = ($paged - 1) * $limit;
        $pagination = $products->getPagination($total, $per_page);
        $search = $products->search($text_search);
        $rows = $products->getAllProducts($filter, $limitstart, $limit, $filter_order, $filter_order_Dir);

        if ($show_vendor) {
            $main_vendor = WopshopFactory::getTable('vendor');
            $main_vendor->loadMain();
            $vendorsModel = $this->getModel('vendors');

            $vendors = $vendorsModel->getAllVendorsNames(1);

            $firstVendor = array();
            $firstVendor[0] = new stdClass();
            $firstVendor[0]->id = -1;
            $firstVendor[0]->name = " - - - ";
            $lists['vendors'] = WopshopHtml::_('select.genericlist', array_merge($firstVendor, $vendors), 'vendor_id', 'onchange="document.search.submit();"', 'id', 'name', $vendor_id);

            foreach ($rows as $k => $v) {
                if ($v->vendor_id) {
                    $rows[$k]->vendor_name = $v->v_f_name . " " . $v->v_l_name;
                } else {
                    $rows[$k]->vendor_name = $main_vendor->f_name . " " . $main_vendor->l_name;
                }
            }
        }
        $model_categories = $this->getModel('categories');
        $parentTop = new stdClass();
        $parentTop->category_id = 0;
        $parentTop->name = "- " . WOPSHOP_CATEGORY . " -";

        $categories_select = wopshopBuildTreeCategory(0, 1, 0);
        array_unshift($categories_select, $parentTop);

        $lists['treecategories'] = WopshopHtml::_('select.genericlist', $categories_select, 'category_id', 'class="chosen-select" onchange="document.search.submit();"', 'category_id', 'name', $category_id);
        $manuf1 = array();
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = WOPSHOP_NAME_MANUFACTURER;

        $actions = array(
            'delete' => WOPSHOP_DELETE,
            'publish' => WOPSHOP_ACTION_PUBLISH,
            'unpublish' => WOPSHOP_ACTION_UNPUBLISH,
            'copy' => WOPSHOP_DUPLICATE,
        );
        $bulk = $model_categories->getBulkActions($actions);

        $_manufacturer = $this->getModel('manufacturers');
        $manufs = $_manufacturer->getList();
        $manufs = array_merge($manuf1, $manufs);
        $lists['manufacturers'] = WopshopHtml::_('select.genericlist', $manufs, 'manufacturer_id', 'style="width: 150px;" onchange="document.search.submit();"', 'manufacturer_id', 'name', $manufacturer_id);

        // product labels
        if ($config->admin_show_product_labels) {
            $_labels = $this->getModel("productlabels");
            $alllabels = $_labels->getList();
            $first = array();
            $first[] = WopshopHtml::_('select.option', '0', WOPSHOP_LABEL, 'id', 'name');
            $lists['labels'] = WopshopHtml::_('select.genericlist', array_merge($first, $alllabels), 'label_id', 'style="width: 80px;" onchange="document.search.submit();"', 'id', 'name', $label_id);
        }

        $f_option = array();
        $f_option[] = WopshopHtml::_('select.option', 0, WOPSHOP_SHOW, 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 1, WOPSHOP_PUBLISH, 'id', 'name');
        $f_option[] = WopshopHtml::_('select.option', 2, WOPSHOP_UNPUBLISH, 'id', 'name');
        $lists['publish'] = WopshopHtml::_('select.genericlist', $f_option, 'publish', 'style="width: 100px;" onchange="document.search.submit();"', 'id', 'name', $publish);

        foreach ($rows as $key => $v) {
            if ($rows[$key]->label_id) {
                $image = wopshopGetNameImageLabel($rows[$key]->label_id);
                if ($image) {
                    $rows[$key]->_label_image = $config->image_labels_live_path . "/" . $image;
                }
                $rows[$key]->_label_name = wopshopGetNameImageLabel($rows[$key]->label_id, 2);
            }
        }
        do_action_ref_array('onBeforeDisplayListProducts', array(&$rows));
        if ($filter_order_Dir == 'asc')
            $filter_order_Dir = 'desc';
        else
            $filter_order_Dir = 'asc';
        $view = $this->getView("products");
        $view->setLayout('list');
        $view->assign('bulk', $bulk);
        $view->assign('rows', $rows);
        $view->assign('lists', $lists);
        $view->assign('filter_order', $filter_order);
        $view->assign('filter_order_Dir', $filter_order_Dir);
        $view->assign('category_id', $category_id);
        $view->assign('manufacturer_id', $manufacturer_id);
        $view->assign('pagination', $pagination);
        $view->assign('text_search', $text_search);
        $view->assign('config', $config);
        $view->assign('show_vendor', $show_vendor);
        $view->assign('pagination', $pagination);
        $view->assign('search', $search);
        $view->tmp_html_filter = "";

        do_action_ref_array('onBeforeDisplayListProductsView', array(&$view));
        $view->display();
    }

    public function publish() {
        $this->_publishProduct(1);
        $this->setRedirect("admin.php?page=wopshop-products");
    }

    public function unpublish() {
        $this->_publishProduct(0);
        $this->setRedirect("admin.php?page=wopshop-products");
    }

    private function _publishProduct($flag) {
        global $wpdb;
        $cid = WopshopRequest::getVar('rows');

        if (empty($cid)) {
            wopshopAddMessage(WOPSHOP_PRODUCT_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-products');
            return 0;
        }

        do_action_ref_array('onBeforePublishProduct', array(&$cid, &$flag));
        if (isset($cid)) {
            foreach ($cid as $key => $value) {
                $wpdb->update($wpdb->prefix . 'wshop_products', array('product_publish' => esc_sql($flag)), array('product_id' => esc_sql($value)));
            }
        }
        do_action_ref_array('onAfterPublishProduct', array(&$cid, &$flag));
    }

    public function delete() {
        $config = WopshopFactory::getConfig();
        global $wpdb;
        $text = array();
        $cid = WopshopRequest::getVar('rows');
        do_action_ref_array('onBeforeRemoveProduct', array(&$cid));
        if (empty($cid)) {
            wopshopAddMessage(WOPSHOP_PRODUCT_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-products');
            return 0;
        }
        if (isset($cid)) {
            foreach ($cid as $key => $value) {
                $product = WopshopFactory::getTable('product');
                $product->load($value);

                $query = "DELETE FROM `" . $wpdb->prefix . "wshop_products` WHERE `product_id` = '" . esc_sql($value) . "' or `parent_id` = '" . esc_sql($value) . "' ";
                $wpdb->get_results($query);

                $wpdb->delete($wpdb->prefix . "wshop_products_attr", array('product_id' => esc_sql($value)));
                $wpdb->delete($wpdb->prefix . "wshop_products_attr2", array('product_id' => esc_sql($value)));
                $wpdb->delete($wpdb->prefix . "wshop_products_prices", array('product_id' => esc_sql($value)));

                $query = "DELETE FROM `" . $wpdb->prefix . "wshop_products_relations` WHERE `product_id` = '" . esc_sql($value) . "' OR `product_related_id` = '" . esc_sql($value) . "'";
                $wpdb->get_results($query);

                $wpdb->delete($wpdb->prefix . "wshop_products_to_categories", array('product_id' => esc_sql($value)));

                $images = $product->getImages();
                $videos = $product->getVideos();
                $files = $product->getFiles();

                if (count($images)) {
                    foreach ($images as $image) {
                        $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_images where image_name='" . esc_sql($image->image_name) . "' and product_id!='" . esc_sql($value) . "'";
                        if (!$wpdb->get_var($query)) {
                            @unlink(wopshopGetPatchProductImage($image->image_name, 'thumb', 2));
                            @unlink(wopshopGetPatchProductImage($image->image_name, '', 2));
                            @unlink(wopshopGetPatchProductImage($image->image_name, 'full', 2));
                        }
                    }
                }

                $wpdb->delete($wpdb->prefix . "wshop_products_images", array('product_id' => esc_sql($value)));

                if (count($videos)) {
                    foreach ($videos as $video) {
                        $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_videos where video_name='" . esc_sql($video->video_name) . "' and product_id!='" . esc_sql($value) . "'";
                        if (!$wpdb->get_var($query)) {
                            @unlink($config->video_product_path . "/" . $video->video_name);
                            if ($video->video_preview) {
                                @unlink($config->video_product_path . "/" . $video->video_preview);
                            }
                        }
                    }
                }

                $wpdb->delete($wpdb->prefix . "wshop_products_videos", array('product_id' => esc_sql($value)));

                if (count($files)) {
                    foreach ($files as $file) {
                        $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_files where demo='" . esc_sql($file->demo) . "' and product_id!='" . esc_sql($value) . "'";
                        if (!$wpdb->get_var($query)) {
                            @unlink($config->demo_product_path . "/" . $file->demo);
                        }

                        $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_files where file='" . esc_sql($file->file) . "' and product_id!='" . esc_sql($value) . "'";
                        if (!$wpdb->get_var($query)) {
                            @unlink($config->files_product_path . "/" . $file->file);
                        }
                    }
                }
                $wpdb->delete($wpdb->prefix . "wshop_products_files", array('product_id' => esc_sql($value)));

                $text[] = sprintf(WOPSHOP_PRODUCT_DELETED, $value) . "<br>";
            }
        }
        do_action_ref_array('onAfterRemoveProduct', array(&$cid));
        $this->setRedirect("admin.php?page=wopshop-products", implode("</p><p>", $text));
    }

    public function edit() {
        $config = WopshopFactory::getConfig();
        $lang = $config->cur_lang;

        do_action_ref_array('onLoadEditProduct', array());
        $id_vendor_cuser = wopshopGetIdVendorForCUser();
        $category_id = WopshopRequest::getInt('category_id');

        $tmpl_extra_fields = null;

        $product_id = WopshopRequest::getInt('product_id');
        $product_attr_id = WopshopRequest::getInt('product_attr_id');

        //parent product
        if ($product_attr_id) {
            $product_attr = WopshopFactory::getTable('productattribut');
            $product_attr->load($product_attr_id);
            if ($product_attr->ext_attribute_product_id) {
                $product_id = $product_attr->ext_attribute_product_id;
            } else {
                $product = WopshopFactory::getTable('product');
                $product->parent_id = $product_attr->product_id;
                $product->store();
                $product_id = $product->product_id;
                $product_attr->ext_attribute_product_id = $product_id;
                $product_attr->store();
            }
        }

        if ($id_vendor_cuser && $product_id) {
            checkAccessVendorToProduct($id_vendor_cuser, $product_id);
        }

        $products = $this->getModel("products");

        $product = WopshopFactory::getTable('product');
        $product->load($product_id);
        $_productprice = WopshopFactory::getTable('productprice');
        $product->product_add_prices = $_productprice->getAddPrices($product_id);
        $product->product_add_prices = array_reverse($product->product_add_prices);
        $name = "name_" . $lang;
        $product->name = $product->$name;

        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;

        $nofilter = array();
        //FilterOutput::objectHTMLSafe( $product, ENT_QUOTES, $nofilter);

        $edit = intval($product_id);

        if (!$product_id) {
            $rows = array();
            $product->product_quantity = 1;
            $product->product_publish = 1;
        }

        $product->product_quantity = floatval($product->product_quantity);
        $_tax = $this->getModel("taxes");
        $all_taxes = $_tax->getAllTaxes();

        if ($edit) {
            $images = $product->getImages();
            $videos = $product->getVideos();
            $files = $product->getFiles();
            $categories_select = $product->getCategories();
            $categories_select_list = array();
            foreach ($categories_select as $v) {
                $categories_select_list[] = $v->category_id;
            }
            $related_products = $products->getRelatedProducts($product_id);
        } else {
            $images = array();
            $videos = array();
            $files = array();
            $categories_select = null;
            if ($category_id) {
                $categories_select = $category_id;
            }
            $related_products = array();
            $categories_select_list = array();
        }
        if ($config->tax) {
            $list_tax = array();
            foreach ($all_taxes as $tax) {
                $list_tax[] = WopshopHtml::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)', 'tax_id', 'tax_name');
            }
            $withouttax = 0;
        } else {
            $withouttax = 1;
        }

        $categories = wopshopBuildTreeCategory(0, 1, 0);
        if (count($categories) == 0)
            wopshopAddMessage(WOPSHOP_PLEASE_ADD_CATEGORY);
        $lists['images'] = $images;
        $lists['videos'] = $videos;
        $lists['files'] = $files;

        $manuf1 = array();
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = WOPSHOP_NONE;

        $_manufacturer = $this->getModel('manufacturers');
        $manufs = $_manufacturer->getList();
        $manufs = array_merge($manuf1, $manufs);

        //Attributes
        $_attribut = $this->getModel('attribut');
        $list_all_attributes = $_attribut->getAllAttributes(2, $categories_select_list);
        $_attribut_value = $this->getModel('attributvalue');
        $lists['attribs'] = $product->getAttributes();
        $lists['ind_attribs'] = $product->getAttributes2();
        $lists['attribs_values'] = $_attribut_value->getAllAttributeValues(2);
        $all_attributes = $list_all_attributes['dependent'];

        $lists['ind_attribs_gr'] = array();
        foreach ($lists['ind_attribs'] as $v) {
            $lists['ind_attribs_gr'][$v->attr_id][] = $v;
        }

        foreach ($lists['attribs'] as $key => $attribs) {
            $lists['attribs'][$key]->count = floatval($attribs->count);
        }

        $first = array();
        $first[] = WopshopHtml::_('select.option', '0', WOPSHOP_SELECT, 'value_id', 'name');

        foreach ($all_attributes as $key => $value) {
            $values_for_attribut = $_attribut_value->getAllValues($value->attr_id);
            $all_attributes[$key]->values_select = WopshopHtml::_('select.genericlist', array_merge($first, $values_for_attribut), 'value_id[' . $value->attr_id . ']', 'class = "inputbox" size = "5" multiple="multiple" id = "value_id_' . $value->attr_id . '"', 'value_id', 'name');
            $all_attributes[$key]->values = $values_for_attribut;
        }
        $lists['all_attributes'] = $all_attributes;
        $product_with_attribute = (count($lists['attribs']) > 0);

        //independent attribute
        $all_independent_attributes = $list_all_attributes['independent'];

        $price_modification = array();
        $price_modification[] = WopshopHtml::_('select.option', '+', '+', 'id', 'name');
        $price_modification[] = WopshopHtml::_('select.option', '-', '-', 'id', 'name');
        $price_modification[] = WopshopHtml::_('select.option', '*', '*', 'id', 'name');
        $price_modification[] = WopshopHtml::_('select.option', '/', '/', 'id', 'name');
        $price_modification[] = WopshopHtml::_('select.option', '=', '=', 'id', 'name');
        $price_modification[] = WopshopHtml::_('select.option', '%', '%', 'id', 'name');

        foreach ($all_independent_attributes as $key => $value) {
            $values_for_attribut = $_attribut_value->getAllValues($value->attr_id);
            $all_independent_attributes[$key]->values_select = WopshopHtml::_('select.genericlist', array_merge($first, $values_for_attribut), 'attr_ind_id_tmp_' . $value->attr_id . '', 'class = "inputbox" ', 'value_id', 'name');
            $all_independent_attributes[$key]->values = $values_for_attribut;
            $all_independent_attributes[$key]->price_modification_select = WopshopHtml::_('select.genericlist', $price_modification, 'attr_price_mod_tmp_' . $value->attr_id . '', 'class = "inputbox" ', 'id', 'name');
            $all_independent_attributes[$key]->submit_button = '<input type = "button" onclick = "addAttributValue2(' . $value->attr_id . ');" value = "' . WOPSHOP_ADD_ATTRIBUT . '" />';
        }
        $lists['all_independent_attributes'] = $all_independent_attributes;
        $lists['dep_attr_button_add'] = '<input type="button" onclick="addAttributValue()" value="' . WOPSHOP_ADD . '" />';
        // End work with attributes and values
        // delivery Times
        if ($config->admin_show_delivery_time) {
            $_deliveryTimes = $this->getModel("deliverytimes");
            $all_delivery_times = $_deliveryTimes->getDeliveryTimes();
            $all_delivery_times0 = array();
            $all_delivery_times0[0] = new stdClass();
            $all_delivery_times0[0]->id = '0';
            $all_delivery_times0[0]->name = WOPSHOP_NONE;
            $lists['deliverytimes'] = WopshopHtml::_('select.genericlist', array_merge($all_delivery_times0, $all_delivery_times), 'delivery_times_id', 'class = "inputbox" size = "1"', 'id', 'name', $product->delivery_times_id);
        }

        // units
        $_units = $this->getModel("units");
        $allunits = $_units->getUnits();
        if ($config->admin_show_product_basic_price) {
            $lists['basic_price_units'] = WopshopHtml::_('select.genericlist', $allunits, 'basic_price_unit_id', 'class = "inputbox"', 'id', 'name', $product->basic_price_unit_id);
        }
        if (!$product->add_price_unit_id)
            $product->add_price_unit_id = $config->product_add_price_default_unit;
        $lists['add_price_units'] = WopshopHtml::_('select.genericlist', $allunits, 'add_price_unit_id', 'class = "inputbox"', 'id', 'name', $product->add_price_unit_id);

        // product labels
        if ($config->admin_show_product_labels) {
            $_labels = $this->getModel("productlabels");
            $alllabels = $_labels->getList();
            $first = array();
            $first[] = WopshopHtml::_('select.option', '0', WOPSHOP_SELECT, 'id', 'name');
            $lists['labels'] = WopshopHtml::_('select.genericlist', array_merge($first, $alllabels), 'label_id', 'class = "inputbox" size = "1"', 'id', 'name', $product->label_id);
        }

        // access rights
        //$accessgroups = getAccessGroups();
        //$lists['access'] = WopshopHtml::_('select.genericlist', $accessgroups, 'access','class = "inputbox" size = "1"','id','title', $product->access);
        //currency
        $current_currency = $product->currency_id;
        if (!$current_currency)
            $current_currency = $config->mainCurrency;
        $_currency = $this->getModel("currencies");
        $currency_list = $_currency->getAllCurrencies();
        $lists['currency'] = WopshopHtml::_('select.genericlist', $currency_list, 'currency_id', 'class = "inputbox"', 'currency_id', 'currency_code', $current_currency);

        // vendors
        $display_vendor_select = 0;
        if ($config->admin_show_vendors) {
            $_vendors = $this->getModel("vendors");
            $listvebdorsnames = $_vendors->getAllVendorsNames(1);
            $first = array();
            $lists['vendors'] = WopshopHtml::_('select.genericlist', array_merge($first, $listvebdorsnames), 'vendor_id', 'class = "inputbox" size = "1"', 'id', 'name', $product->vendor_id);

            $display_vendor_select = 1;
            if ($id_vendor_cuser > 0)
                $display_vendor_select = 0;
        }

        //product extra field
        if ($config->admin_show_product_extra_field) {
            $categorys_id = array();
            if (is_array($categories_select)) {
                foreach ($categories_select as $tmp) {
                    $categorys_id[] = $tmp->category_id;
                }
            }
            $tmpl_extra_fields = $this->_getHtmlProductExtraFields($categorys_id, $product);
        }

        //free attribute
        if ($config->admin_show_freeattributes) {
            $_freeattributes = $this->getModel("freeattribut");
            $listfreeattributes = $_freeattributes->getAll();
            $activeFreeAttribute = $product->getListFreeAttributes();
            $listIdActiveFreeAttribute = array();
            foreach ($activeFreeAttribute as $_obj) {
                $listIdActiveFreeAttribute[] = $_obj->id;
            }
            foreach ($listfreeattributes as $k => $v) {
                if (in_array($v->id, $listIdActiveFreeAttribute)) {
                    $listfreeattributes[$k]->pactive = 1;
                }
            }
        }

        $lists['manufacturers'] = WopshopHtml::_('select.genericlist', $manufs, 'product_manufacturer_id', 'class = "inputbox" size = "1"', 'manufacturer_id', 'name', $product->product_manufacturer_id);
        $tax_value = 0;
        if ($config->tax) {
            foreach ($all_taxes as $tax) {
                if ($tax->tax_id == $product->product_tax_id) {
                    $tax_value = $tax->tax_value;
                    break;
                }
            }
        }

        if ($product_id) {
            $product->product_price = round($product->product_price, $config->product_price_precision);
            if ($config->display_price_admin == 0) {
                $product->product_price2 = round($product->product_price / (1 + $tax_value / 100), $config->product_price_precision);
            } else {
                $product->product_price2 = round($product->product_price * (1 + $tax_value / 100), $config->product_price_precision);
            }
        } else {
            $product->product_price2 = '';
        }

        $category_select_onclick = "";
        if ($config->admin_show_product_extra_field)
            $category_select_onclick = 'onclick="reloadProductExtraField(\'' . $product_id . '\')"';

        if ($config->tax) {
            $lists['tax'] = WopshopHtml::_('select.genericlist', $list_tax, 'product_tax_id', 'class = "inputbox" size = "1" onchange = "updatePrice2(' . $config->display_price_admin . ');"', 'tax_id', 'tax_name', $product->product_tax_id);
        }
        $lists['categories'] = WopshopHtml::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox" size="10" multiple = "multiple" ' . $category_select_onclick, 'category_id', 'name', $categories_select);

        $lists['templates'] = wopshopGetTemplates('product', $product->product_template);

        $_product_option = WopshopFactory::getTable('productoption');
        $product_options = $_product_option->getProductOptions($product_id);
        $product->product_options = $product_options;

        if ($config->return_policy_for_product) {
            $_statictext = $this->getModel("statictext");
            $first = array();
            $first[] = WopshopHtml::_('select.option', '0', _JSHP_STPAGE_return_policy, 'id', 'alias');
            $statictext_list = $_statictext->getList(1);
            $product_options['return_policy'] = isset($product_options['return_policy']) ? $product_options['return_policy'] : "";
            $lists['return_policy'] = WopshopHtml::_('select.genericlist', array_merge($first, $statictext_list), 'options[return_policy]', 'class = "inputbox"', 'id', 'alias', $product_options['return_policy']);
        }

        do_action_ref_array('onBeforeDisplayEditProduct', array(&$product, &$related_products, &$lists, &$listfreeattributes, &$tax_value));

        $nul = '';
        $view = $this->getView("productedit");
        $view->setLayout("default");
        $view->assign('product', $product);
        $view->assign('lists', $lists);
        $view->assign('related_products', $related_products);
        $view->assign('edit', $edit);
        $view->assign('product_with_attribute', $product_with_attribute);
        $view->assign('tax_value', $tax_value);
        $view->assign('languages', $languages);
        $view->assign('multilang', $multilang);
        $view->assign('tmpl_extra_fields', $tmpl_extra_fields);
        $view->assign('withouttax', $withouttax);
        $view->assign('display_vendor_select', $display_vendor_select);
        $view->assign('listfreeattributes', $listfreeattributes);
        $view->assign('product_attr_id', $product_attr_id);
        $view->currency = $current_currency;
        $view->ind_attr_td_header = "";
        $view->dep_attr_td_header = "";
        $view->dep_attr_td_row_empty = "";
        $view->dep_attr_td_footer = "";
        $view->dep_attr_td_row = array();
        $view->ind_attr_td_row = array();
        $view->ind_attr_td_footer = array();
        foreach ($languages as $l) {
            $view->assign('plugin_template_description_' . $l->language, $nul);
        }
        $view->assign('plugin_template_info', $nul);
        $view->assign('plugin_template_attribute', $nul);
        $view->assign('plugin_template_freeattribute', $nul);
        $view->assign('plugin_template_images', $nul);
        $view->assign('plugin_template_related', $nul);
        $view->assign('plugin_template_files', $nul);
        $view->assign('plugin_template_extrafields', $nul);
        $view->assign('config', $config);
   

        do_action_ref_array('onBeforeDisplayEditProductView', array(&$view));
        $view->display();
    }

    public function save() {
        check_admin_referer('productedit');
        $post = WopshopRequest::get('post');
        $config = WopshopFactory::getConfig();
        require_once($config->path . 'lib/image.lib.php');
        require_once($config->path . 'lib/uploadfile.class.php');

        $_products = $this->getModel("products");
        $product = WopshopFactory::getTable('product');
        $_alias = $this->getModel("alias");
        $_lang = $this->getModel("languages");
        $id_vendor_cuser = wopshopGetIdVendorForCUser();

        if ($id_vendor_cuser && $post['product_id']) {
            checkAccessVendorToProduct($id_vendor_cuser, $post['product_id']);
        }
        $post['different_prices'] = 0;
        if (isset($post['product_is_add_price']) && $post['product_is_add_price'])
            $post['different_prices'] = 1;

        if (!isset($post['product_publish']))
            $post['product_publish'] = 0;
        if (!isset($post['product_is_add_price']))
            $post['product_is_add_price'] = 0;
        if (!isset($post['unlimited']))
            $post['unlimited'] = 0;
        $post['product_price'] = wopshopSaveAsPrice($post['product_price']);
        $post['product_old_price'] = wopshopSaveAsPrice($post['product_old_price']);
        if (isset($post['product_buy_price']))
            $post['product_buy_price'] = wopshopSaveAsPrice($post['product_buy_price']);
        else
            $post['product_buy_price'] = null;
        $post['product_weight'] = wopshopSaveAsPrice($post['product_weight']);
        if (!isset($post['related_products']))
            $post['related_products'] = array();
        if (!$post['product_id'])
            $post['product_date_added'] = wopshopGetJsDate();
        if (!isset($post['attrib_price']))
            $post['attrib_price'] = null;
        if (!isset($post['attrib_ind_id']))
            $post['attrib_ind_id'] = null;
        if (!isset($post['attrib_ind_price']))
            $post['attrib_ind_price'] = null;
        if (!isset($post['attrib_ind_price_mod']))
            $post['attrib_ind_price_mod'] = null;
        if (!isset($post['freeattribut']))
            $post['freeattribut'] = null;
        $post['date_modify'] = wopshopGetJsDate();
        $post['edit'] = intval($post['product_id']);
        if (!isset($post['product_add_discount']))
            $post['product_add_discount'] = 0;
        $post['min_price'] = $_products->getMinimalPrice($post['product_price'], $post['attrib_price'], array($post['attrib_ind_id'], $post['attrib_ind_price_mod'], $post['attrib_ind_price']), $post['product_is_add_price'], $post['product_add_discount']);
        if ($id_vendor_cuser) {
            $post['vendor_id'] = $id_vendor_cuser;
        }

        if (isset($post['attr_count']) && is_array($post['attr_count'])) {
            $qty = 0;
            foreach ($post['attr_count'] as $key => $_qty) {
                $post['attr_count'][$key] = wopshopSaveAsPrice($_qty);
                if ($_qty > 0)
                    $qty += $post['attr_count'][$key];
            }

            $post['product_quantity'] = $qty;
        }

        if ($post['unlimited']) {
            $post['product_quantity'] = 1;
        }

        $post['product_quantity'] = wopshopSaveAsPrice($post['product_quantity']);

        if (isset($post['productfields']) && is_array($post['productfields'])) {
            foreach ($post['productfields'] as $productfield => $val) {
                if (is_array($val)) {
                    $post[$productfield] = implode(',', $val);
                }
            }
        }
        if ($config->admin_show_product_extra_field) {
            $_productfields = $this->getModel("productfields");
            $list_productfields = $_productfields->getList(1);
            foreach ($list_productfields as $v) {
                if ($v->type == 0 && !isset($post['extra_field_' . $v->id])) {
                    $post['extra_field_' . $v->id] = '';
                }
            }
        }

        if (is_array($post['attrib_price'])) {
            if (count(array_unique($post['attrib_price'])) > 1)
                $post['different_prices'] = 1;
        }
        if (is_array($post['attrib_ind_price'])) {
            $tmp_attr_ind_price = array();
            foreach ($post['attrib_ind_price'] as $k => $v) {
                $tmp_attr_ind_price[] = $post['attrib_ind_price_mod'][$k] . $post['attrib_ind_price'][$k];
            }
            if (count(array_unique($tmp_attr_ind_price)) > 1)
                $post['different_prices'] = 1;
        }

        $languages = $_lang->getAllLanguages(1);
        foreach ($languages as $lang) {
            $post['name_' . $lang->language] = trim($post['name_' . $lang->language]);
            if ($config->create_alias_product_category_auto && $post['alias_' . $lang->language] == "")
                $post['alias_' . $lang->language] = $post['name_' . $lang->language];
            $post['alias_' . $lang->language] = sanitize_title_with_dashes($post['alias_' . $lang->language]);
            if ($post['alias_' . $lang->language] != "" && !$_alias->checkExistAlias2Group($post['alias_' . $lang->language], $lang->language, $post['product_id'])) {
                $post['alias_' . $lang->language] = "";
                wopshopAddMessage(WOPSHOP_ERROR_ALIAS_ALREADY_EXIST);
            }
            $post['description_' . $lang->language] = WopshopRequest::getVar('description' . $lang->id, '', 'post', "string", 2);
            $post['short_description_' . $lang->language] = WopshopRequest::getVar('short_description_' . $lang->language, '', 'post', "string", 2);
        }

        do_action_ref_array('onBeforeDisplaySaveProduct', array(&$post, &$product));

        if (!$product->bind($post)) {
            wopshopAddMessage(WOPSHOP_ERROR_BIND, 'error');
            $this->setRedirect("admin.php?page=wopshop-products");
            return 0;
        }

        if (($product->min_price == 0 || $product->product_price == 0) && !$config->user_as_catalog && $product->parent_id == 0) {
            wopshopAddMessage(WOPSHOP_YOU_NOT_SET_PRICE);
        }

        if (isset($post['set_main_image'])) {
            $image = WopshopFactory::getTable('image');
            $image->load($post['set_main_image']);
            if ($image->image_id) {
                $product->image = $image->image_name;
            }
        }

        if (!$product->store()) {
            wopshopAddMessage(WOPSHOP_ERROR_SAVE_DATABASE . "<br>" . $product->_error, 'error');

            $this->setRedirect("admin.php?page=wopshop-products&task=edit&product_id=" . $product->product_id);
            return 0;
        }

        $product_id = $product->product_id;

        do_action_ref_array('onAfterSaveProduct', array(&$product));

        if ($config->admin_show_product_video && $product->parent_id == 0) {
            $_products->uploadVideo($product, $product_id, $post);
        }

        $_products->uploadImages($product, $product_id, $post);

        if ($config->admin_show_product_files) {
            $_products->uploadFiles($product, $product_id, $post);
        }

        $_products->saveAttributes($product, $product_id, $post);

        if ($config->admin_show_freeattributes) {
            $_products->saveFreeAttributes($product_id, $post['freeattribut']);
        }

        if ($post['product_is_add_price']) {
            $_products->saveAditionalPrice($product_id, $post['product_add_discount'], $post['quantity_start'], $post['quantity_finish']);
        }

        if ($product->parent_id == 0) {
            $_products->setCategoryToProduct($product_id, $post['category_id']);
        }

        $_products->saveRelationProducts($product, $product_id, $post);
        if(!isset($post['options'])){
            $post['options'] = [];
        }
        $_products->saveProductOptions($product_id, (array) $post['options']);

        do_action_ref_array('onAfterSaveProductEnd', array($product->product_id));

        if ($product->parent_id != 0) {
            print "<script type='text/javascript'>window.close();</script>";  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            die();
        }

        $this->setRedirect('admin.php?page=wopshop-products', WOPSHOP_PRODUCT_SAVED);
    }

    public function delete_foto() {
        $image_id = WopshopRequest::getInt("id");
        global $wpdb;

        $query = "SELECT * FROM `" . $wpdb->prefix . "wshop_products_images` WHERE image_id = '" . esc_sql($image_id) . "'";
        $row = $wpdb->get_row($query);

        $wpdb->delete($wpdb->prefix . 'wshop_products_images', array('image_id' => $image_id));

        $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_images where image_name='" . esc_sql($row->image_name) . "' and product_id!='" . esc_sql($row->product_id) . "'";

        if (!$wpdb->get_var($query)) {
            @unlink(wopshopGetPatchProductImage($row->image_name, 'thumb', 2));
            @unlink(wopshopGetPatchProductImage($row->image_name, '', 2));
            @unlink(wopshopGetPatchProductImage($row->image_name, 'full', 2));
        }

        $product = WopshopFactory::getTable('product');
        $product->load($row->product_id);
        if ($product->image == $row->image_name) {
            $product->image = '';
            $list_images = $product->getImages();
            if (count($list_images)) {
                $product->image = $list_images[0]->image_name;
            }
            $product->store();
        }

        die();
    }

    public function delete_file() {
        $config = WopshopFactory::getConfig();
        global $wpdb;
        $id = WopshopRequest::getInt("id");
        $type = WopshopRequest::getVar("type");

        $query = "SELECT * FROM `" . $wpdb->prefix . "wshop_products_files` WHERE `id` = '" . esc_sql($id) . "'";
        $row = $wpdb->get_results($query);
        $row = $row[0];

        $delete_row = 0;

        if ($type == "demo") {
            if ($row->file == "") {
                $wpdb->delete($wpdb->prefix . "wshop_products_files", array('id' => $id));
                $delete_row = 1;
            } else {
                $wpdb->update($wpdb->prefix . "wshop_products_files", array('demo' => '', 'demo_descr' => ''), array('id' => $id));
            }

            $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_files where demo='" . esc_sql($row->demo) . "'";
            if (!$wpdb->get_var($query)) {
                @unlink($config->demo_product_path . "/" . $row->demo);
            }
        }

        if ($type == "file") {
            if ($row->demo == "") {
                $wpdb->delete($wpdb->prefix . "wshop_products_files", array('id' => $id));
                $delete_row = 1;
            } else {
                $wpdb->update($wpdb->prefix . "wshop_products_files", array('file' => '', 'file_descr' => ''), array('id' => $id));
            }

            $query = "select count(*) as k from " . $wpdb->prefix . "wshop_products_files where file='" . esc_sql($row->file) . "'";

            if (!$wpdb->get_var($query)) {
                @unlink($config->files_product_path . "/" . $row->file);
            }
        }
        print esc_html($delete_row);
        die();
    }

    public function delete_video() {
        $video_id = WopshopRequest::getInt("id");
        $config = WopshopFactory::getConfig();
        global $wpdb;

        $query = "SELECT * FROM `" . $wpdb->prefix . "wshop_products_videos` WHERE video_id = '" . esc_sql($video_id) . "'";
        $rows = $wpdb->get_results($query);
        $row = $rows[0];

        $query = "select count(*) from " . $wpdb->prefix . "wshop_products_videos where video_name='" . esc_sql($row->video_name) . "' and product_id!='" . esc_sql($row->product_id) . "'";

        if (!$wpdb->get_var($query)) {
            @unlink($config->video_product_path . "/" . $row->video_name);
            if ($row->video_preview) {
                @unlink($config->video_product_path . "/" . $row->video_preview);
            }
        }
        $wpdb->delete($wpdb->prefix . "wshop_products_videos", array('video_id' => esc_sql($video_id)));
        die();
    }

    public function loadproductinfo() {
        do_action_ref_array('onLoadInfoProduct', array());
        $id_vendor_cuser = wopshopGetIdVendorForCUser();
        $product_id = WopshopRequest::getInt('product_id');
        $currency_id = WopshopRequest::getInt('currency_id');
        $layout = WopshopRequest::getVar('layout', 'productinfo_json');

        if ($id_vendor_cuser && $product_id) {
            checkAccessVendorToProduct($id_vendor_cuser, $product_id);
        }

        $product = WopshopFactory::getTable('product');
        $product->load($product_id);
        $product->getDescription();

        $currency = WopshopFactory::getTable('currency');
        $currency->load($currency_id);
        if ($currency_id) {
            $currency_value = $currency->currency_value;
        } else {
            $currency_value = 1;
        }

        $res = array();
        $res['product_id'] = $product->product_id;
        $res['product_ean'] = $product->product_ean;
        $res['product_price'] = $product->product_price * $currency_value;
        $res['delivery_times_id'] = $product->delivery_times_id;
        $res['vendor_id'] = wopshopFixRealVendorId($product->vendor_id);
        $res['product_weight'] = $product->product_weight;
        $res['product_tax'] = $product->getTax();
        $res['product_name'] = $product->name;
        $res['thumb_image'] = wopshopGetPatchProductImage($product->image, 'thumb');

        $view = $this->getView("productedit");
        $view->setLayout($layout);
        $view->assign('res', $res);
        $view->assign('product', $product);
        do_action_ref_array('onBeforeDisplayLoadInfoProduct', array(&$view));
        $view->display();
        die();
    }

    public function product_extra_fields() {
        $product_id = WopshopRequest::getInt("product_id");
        $cat_id = WopshopRequest::getVar("cat_id");
        $product = WopshopFactory::getTable('product');
        $product->load($product_id);

        $categorys = array();
        if (is_array($cat_id)) {
            foreach ($cat_id as $cid) {
                $categorys[] = intval($cid);
            }
        }

        print $this->_getHtmlProductExtraFields($categorys, $product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        die();
    }

    private function _getHtmlProductExtraFields($categorys, $product) {
        $_productfields = $this->getModel('productfields');
        $list = $_productfields->getList(1);

        $_productfieldvalues = $this->getModel('productfieldvalues');
        $listvalue = $_productfieldvalues->getAllList();

        $f_option = array();
        $f_option[] = WopshopHtml::_('select.option', 0, " - - - ", 'id', 'name');

        $fields = array();
        foreach ($list as $v) {
            $insert = 0;
            if ($v->allcats == 1) {
                $insert = 1;
            } else {
                $cats = json_decode($v->cats, 1);
                foreach ($categorys as $catid) {
                    if (in_array($catid, $cats))
                        $insert = 1;
                }
            }
            if ($insert) {
                $obj = new stdClass();
                $obj->id = $v->id;
                $obj->name = $v->name;
                $obj->groupname = $v->groupname;
                $tmp = array();
                foreach ($listvalue as $lv) {
                    if ($lv->field_id == $v->id)
                        $tmp[] = $lv;
                }
                $name = 'extra_field_' . $v->id;
                if ($v->type == 0) {
                    if ($v->multilist == 1) {
                        $attr = 'multiple="multiple" size="10"';
                    } else {
                        $attr = "";
                    }
                    $obj->values = WopshopHtml::_('select.genericlist', array_merge($f_option, $tmp), 'productfields[' . $name . '][]', $attr, 'id', 'name', explode(',', $product->$name));
                } else {
                    $obj->values = "<input type='text' name='" . $name . "' value='" . $product->$name . "' />";
                }
                $fields[] = $obj;
            }
        }
        ob_start();
        $view = $this->getView("productedit");
        $view->setLayout("extrafields_inner");
        $view->assign('fields', $fields);
        do_action_ref_array('onBeforeLoadTemplateHtmlProductExtraFields', array(&$view));
        $view->display();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function search_related() {
        $config = WopshopFactory::getConfig();
        $products = $this->getModel("products");

        $text_search = WopshopRequest::getVar("text");
        $limitstart = WopshopRequest::getInt("start");
        $no_id = WopshopRequest::getInt("no_id");
        $limit = 20;

        $filter = array("without_product_id" => $no_id, "text_search" => $text_search);
        $total = $products->getCountAllProducts($filter);
        $rows = $products->getAllProducts($filter, $limitstart, $limit);
        $page = ceil($total / $limit);

        $view = $this->getView("products");
        $view->setLayout("search_related");
        $view->assign('rows', $rows);
        $view->assign('config', $config);
        $view->assign('limit', $limit);
        $view->assign('pages', $page);
        $view->assign('no_id', $no_id);
        $view->display();
        die();
    }

    public function copy() {
        $cid = WopshopRequest::getVar('rows');
        if (empty($cid)) {
            wopshopAddMessage(WOPSHOP_PRODUCT_EMPTY_POST_CHECBOX, 'error');
            $this->setRedirect('admin.php?page=wopshop-products');
            return 0;
        }
        $text = $this->getModel("products")->copyProducts($cid);
        $this->setRedirect('admin.php?page=wopshop-products', implode("</li><li>", $text));
    }

    function order() {
        $order = WopshopRequest::getVar("order");
        $product_id = WopshopRequest::getInt("product_id");
        $number = WopshopRequest::getInt("number");
        $category_id = WopshopRequest::getInt("category_id");

        global $wpdb;
        switch ($order) {
            case 'up':
                $query = "SELECT a.*
                       FROM `" . $wpdb->prefix . "wshop_products_to_categories` AS a
                       WHERE a.product_ordering < '" . $number . "' AND a.category_id = '" . $category_id . "'
                       ORDER BY a.product_ordering DESC
                       LIMIT 1";
                break;
            case 'down':
                $query = "SELECT a.*
                       FROM `" . $wpdb->prefix . "wshop_products_to_categories` AS a
                       WHERE a.product_ordering > '" . $number . "' AND a.category_id = '" . $category_id . "'
                       ORDER BY a.product_ordering ASC
                       LIMIT 1";
        }
        $row = $wpdb->get_row($query);

        $query1 = "UPDATE `" . $wpdb->prefix . "wshop_products_to_categories` AS a
                     SET a.product_ordering = '" . $row->product_ordering . "'
                     WHERE a.product_id = '" . $product_id . "' AND a.category_id = '" . $category_id . "'";
        $query2 = "UPDATE `" . $wpdb->prefix . "wshop_products_to_categories` AS a
                     SET a.product_ordering = '" . $number . "'
                     WHERE a.product_id = '" . $row->product_id . "' AND a.category_id = '" . $category_id . "'";
        $wpdb->query($query1);
        $wpdb->query($query2);
        $this->setRedirect("admin.php?page=wopshop-products&category_id=" . $category_id);
    }

    function saveorder() {
        global $wpdb;
        $category_id = WopshopRequest::getInt("category_id");
        $cid = WopshopRequest::getVar("rows");
        $order = WopshopRequest::getVar('order', array(), 'post', 'array');

        foreach ($cid as $k => $product_id) {
            $query = "UPDATE `" . $wpdb->prefix . "wshop_products_to_categories`
                     SET product_ordering = '" . intval($order[$k]) . "'
                     WHERE product_id = '" . intval($product_id) . "' AND category_id = '" . intval($category_id) . "'";
            $wpdb->query($query);
        }
        $this->setRedirect("admin.php?page=wopshop-products&category_id=" . $category_id);
    }

}
