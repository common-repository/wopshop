<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$order = $this->order;
?>
<div class="wshop myorderinfo">
    
    <?php print $this->_tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
    <?php if ($this->config->order_send_pdf_client) : ?>
        <div class="downlod_order_invoice">
            <a class="btn" target="_blank" href="<?php print esc_url($this->config->pdf_orders_live_path."/".$order->pdf_file)?>">
                <?php print esc_html(WOPSHOP_DOWNLOAD_INVOICE)?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class = "row-fluid order_number">
        <div class = "span12">
            <b><?php print esc_html(WOPSHOP_ORDER_NUMBER )?>:</b>
            <span><?php print esc_html($order->order_number)?></span>
        </div>
    </div>
    <div class = "row-fluid order_status">
        <div class = "span12">
            <b><?php print esc_html(WOPSHOP_ORDER_STATUS )?>:</b>
            <span><?php print esc_html($order->status_name) ?></span>
        </div>
    </div>
    <div class = "row-fluid order_date">
        <div class = "span12">
            <b><?php print esc_html(WOPSHOP_ORDER_DATE )?>:</b>
            <span><?php print esc_html(wopshop_formatdate($order->order_date, 0)) ?></span>
        </div>
    </div>
    <div class = "row-fluid order_total">
        <div class = "span12">
            <b><?php print esc_html(WOPSHOP_PRICE_TOTAL )?>:</b>
            <span><?php print esc_html(wopshopFormatprice($order->order_total, $order->currency_code)); ?></span>
        </div>
    </div>   
    
    <div class = "row-fluid userinfo">
        <div class = "span6 userbillinfo">
            <table class = "wshop">
                <tr>
                    <td colspan=2><b><?php print esc_html(WOPSHOP_EMAIL_BILL_TO )?></b></td>
                </tr>
                <?php if ($this->config_fields['firma_name']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</td>
                    <td><?php print esc_html($this->order->firma_name)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['f_name']['display']){?>
                <tr>
                    <td width = "40%"><?php print esc_html(WOPSHOP_FULL_NAME)?>:</td>
                    <td width = "60%"><?php print esc_html($this->order->f_name)?> <?php print esc_html($this->order->l_name)?> <?php print esc_html($this->order->m_name)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['client_type']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_CLIENT_TYPE)?>:</td>
                    <td><?php print esc_html($this->order->client_type_name);?></td>
                </tr>
                <?php } ?>        
                <?php if ($this->config_fields['firma_code']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_FIRMA_CODE)?>:</td>
                    <td><?php print esc_html($this->order->firma_code)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['tax_number']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_VAT_NUMBER)?>:</td>
                    <td><?php print esc_html($this->order->tax_number)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['birthday']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</td>
                    <td><?php print esc_html($this->order->birthday)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['home']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_HOME)?>:</td>
                    <td><?php print esc_html($this->order->home)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['apartment']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_APARTMENT)?>:</td>
                    <td><?php print esc_html($this->order->apartment)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['street']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_STREET_NR)?>:</td>
                    <td><?php print esc_html($this->order->street)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['city']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_CITY)?>:</td>
                    <td><?php print esc_html($this->order->city)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['state']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_STATE)?>:</td>
                    <td><?php print esc_html($this->order->state)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['zip']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_ZIP)?>:</td>
                    <td><?php print esc_html($this->order->zip)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['country']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_COUNTRY)?>:</td>
                    <td><?php print esc_html($this->order->country)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['phone']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_TELEFON)?>:</td>
                    <td><?php print esc_html($this->order->phone)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['mobil_phone']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</td>
                    <td><?php print esc_html($this->order->mobil_phone)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['fax']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_FAX)?>:</td>
                    <td><?php print esc_html($this->order->fax)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['email']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EMAIL)?>:</td>
                    <td><?php print esc_html($this->order->email)?></td>
                </tr>
                <?php } ?>

                <?php if ($this->config_fields['ext_field_1']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</td>
                    <td><?php print esc_html($this->order->ext_field_1)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['ext_field_2']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</td>
                    <td><?php print esc_html($this->order->ext_field_2)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['ext_field_3']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</td>
                    <td><?php print esc_html($this->order->ext_field_3)?></td>
                </tr>
                <?php } ?>                        
            </table>
        </div>
        <div class = "span6 userdeliveryinfo">
        <?php if ($this->count_filed_delivery >0) {?>
            <table class="wshop userdeliveryinfo">
                <tr>
                    <td colspan="2"><b><?php print esc_html(WOPSHOP_EMAIL_SHIP_TO )?></b></td>
                </tr>
                <?php if ($this->config_fields['d_firma_name']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</td>
                    <td><?php print esc_html($this->order->d_firma_name)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_f_name']['display']){?>
                <tr>
                    <td width = "40%"><?php print esc_html(WOPSHOP_FULL_NAME)?> </td>
                    <td width = "60%"><?php print esc_html($this->order->d_f_name)?> <?php print esc_html($this->order->d_l_name)?> <?php print esc_html($this->order->d_m_name)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_birthday']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</td>
                    <td><?php print esc_html($this->order->d_birthday)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_home']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_HOME)?>:</td>
                    <td><?php print esc_html($this->order->d_home)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_apartment']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_APARTMENT)?>:</td>
                    <td><?php print esc_html($this->order->d_apartment)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_street']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_STREET_NR)?>:</td>
                    <td><?php print esc_html($this->order->d_street)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_city']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_CITY)?>:</td>
                    <td><?php print esc_html($this->order->d_city)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_state']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_STATE)?>:</td>
                    <td><?php print esc_html($this->order->d_state)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_zip']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_ZIP )?>:</td>
                    <td><?php print esc_html($this->order->d_zip )?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_country']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_COUNTRY )?>:</td>
                    <td><?php print esc_html($this->order->d_country )?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_phone']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_TELEFON )?>:</td>
                    <td><?php print esc_html($this->order->d_phone )?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_mobil_phone']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</td>
                    <td><?php print esc_html($this->order->d_mobil_phone)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_fax']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_FAX )?>:</td>
                    <td><?php print esc_html($this->order->d_fax )?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_email']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EMAIL )?>:</td>
                    <td><?php print esc_html($this->order->d_email )?></td>
                </tr>
                <?php } ?>                            
                <?php if ($this->config_fields['d_ext_field_1']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</td>
                    <td><?php print esc_html($this->order->d_ext_field_1)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_ext_field_2']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</td>
                    <td><?php print esc_html($this->order->d_ext_field_2)?></td>
                </tr>
                <?php } ?>
                <?php if ($this->config_fields['d_ext_field_3']['display']){?>
                <tr>
                    <td><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</td>
                    <td><?php print esc_html($this->order->d_ext_field_3)?></td>
                </tr>
                <?php } ?>
            </table>
        <?php } ?>  
        </div>
    </div>
    
    <div class="product_head">
        <strong><?php echo esc_html(WOPSHOP_PRODUCTS)?></strong>
    </div>
    
    <div class = "order_items">        
        <table class="wshop cart">
        <tr>
            <th class="product_name">
                <?php print esc_html(WOPSHOP_ITEM )?>
            </th>
            <?php if ($this->config->show_product_code_in_order){?>
                <th class="product_code" width = "15%">
                    <?php print esc_html(WOPSHOP_EAN_PRODUCT)?>
                </th>
            <?php }?>
            <th class="single_price" width="15%">
                <?php print esc_html(WOPSHOP_SINGLEPRICE )?>
            </th>
            <th class="quantity" width="15%">
                <?php print esc_html(WOPSHOP_NUMBER )?>
            </th>
            <th class="total_price" width="15%">
                <?php print esc_html(WOPSHOP_PRICE_TOTAL )?>
            </th>
        </tr>
        
        <?php
        $i=1; $countprod = count($order->items);
        foreach($order->items as $key_id=>$prod){
            $files = json_decode($prod->files);
            ?>            
            <tr class="wshop_prod_cart <?php if ($i % 2 == 0) print "even"; else print "odd"?>">
                <td class="product_name">
                    <div class="mobile-cart">
                        <?php print esc_html(WOPSHOP_ITEM);?>
                    </div>
                    <div class="data">
                        <div class="name">
                            <?php print esc_html($prod->product_name)?>
                        </div>
                        <?php if ($prod->manufacturer!=''){?>
                            <div class="manufacturer">
                                <?php print esc_html(WOPSHOP_MANUFACTURER)?>:
                                <span><?php print esc_html($prod->manufacturer)?></span>
                            </div>
                        <?php }?>
                        <div class="attribs">
                            <?php 
                            //print wopshopSprintAtributeInOrder($prod->product_attributes).wopshopSprintFreeAtributeInOrder($prod->product_freeattributes);
                            $r= wopshopSprintAtributeInOrder($prod->product_attributes).wopshopSprintFreeAtributeInOrder($prod->product_freeattributes);
                            $r = str_replace('\n', '<br>',$r);
                            echo wp_kses_post($r);
                            print $prod->_ext_attribute_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                        <?php if (count($files)){?>
                            <div class="filelist">
                                <?php foreach($files as $file){?>
                                    <div class="file">
                                        <span class="descr">
                                            <?php print wp_kses_post($file->file_descr)?>
                                        </span>
										<a class="download" href="<?php print esc_url(wopshopSEFLink('controller=product&task=getfile&oid='.$this->order->order_id.'&id='.$file->id.'&hash='.$this->order->file_hash))?>">
                                            <?php print esc_html(WOPSHOP_DOWNLOAD)?>
                                        </a>
                                    </div>
                                <?php }?>
                            </div>
                         <?php }?>        
                        <?php print $prod->_ext_file_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
                    </div>
                </td>
                <?php if ($this->config->show_product_code_in_order){?>
                    <td class="product_code">
                        <div class="mobile-cart">
                            <?php print esc_html(WOPSHOP_EAN_PRODUCT)?>
                        </div>
                        <div class="data">
                            <?php print esc_html($prod->product_ean)?>
                        </div>
                    </td>
                <?php } ?>
                <td class="single_price">
                    <div class="mobile-cart">
                        <?php print esc_html(WOPSHOP_SINGLEPRICE);?>
                    </div>                    
                    <div class="data">
                        <?php print esc_html(wopshopFormatprice($prod->product_item_price, $order->currency_code)) ?>
                        <?php print $prod->_ext_price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        
                        <?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
                            <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod->product_tax, $order->display_price));?></span>
                        <?php }?>
                        
                        <?php if ($this->config->cart_basic_price_show && $prod->basicprice>0){?>
                            <div class="basic_price">
                                <?php print esc_html(WOPSHOP_BASIC_PRICE)?>:
                                <span><?php print esc_html(wopshopSprintBasicPrice($prod));?></span>
                            </div>
                        <?php }?>
                        
                    </div>
                </td>
                <td class="quantity">
                    <div class="mobile-cart">
                        <?php print esc_html(WOPSHOP_NUMBER);?>
                    </div>
                    <div class="data">
                        <?php print esc_html(wopshop_formatqty($prod->product_quantity));?><?php print esc_html($prod->_qty_unit);?>
                    </div>
                </td>
                <td class="total_price">
                    <div class="mobile-cart">
                        <?php print esc_html(WOPSHOP_PRICE_TOTAL);?>
                    </div>
                    <div class="data">
                        <?php print esc_html(wopshopFormatprice($prod->product_item_price * $prod->product_quantity, $order->currency_code)); ?>
                        <?php print $prod->_ext_price_total_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        <?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
                            <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod->product_tax, $order->display_price));?></span>
                        <?php }?>
                    </div>
                </td>
            </tr>
        <?php $i++; } ?>
        </table>
    </div>
    
    <?php if ($this->config->show_weight_order){?>  
        <div class="weightorder">
            <?php print esc_html(WOPSHOP_WEIGHT_PRODUCTS)?>:
            <span><?php print esc_html(wopshop_formatweight($this->order->weight));?></span>
        </div>
    <?php }?>

    <table class = "wshop wshop_subtotal">
        <?php if (!$this->hide_subtotal){?>
            <tr class="subtotal">    
                <td class = "name">
                    <?php print esc_html(WOPSHOP_SUBTOTAL )?>
                </td>
                <td class = "value">
                    <?php print esc_html(wopshopFormatprice($order->order_subtotal, $order->currency_code));?>
                    <?php print $this->_tmp_ext_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        
        <?php print $this->_tmp_html_after_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if ($order->order_discount > 0){ ?>
            <tr class="discount">
                <td class = "name">
                    <?php print esc_html(WOPSHOP_RABATT_VALUE )?>
                </td>
                <td class = "value">
                    <?php print esc_html(wopshopFormatprice(-$order->order_discount, $order->currency_code));?>
                    <?php print $this->_tmp_ext_discount; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        
        <?php if (!$this->config->without_shipping || $order->order_shipping > 0){?>
            <tr class="shipping">
                <td class = "name">
                    <?php print esc_html(WOPSHOP_SHIPPING_PRICE);?>
                </td>
                <td class = "value">
                    <?php print esc_html(wopshopFormatprice($order->order_shipping, $order->currency_code));?>
                    <?php print $this->_tmp_ext_shipping; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        
        <?php if (!$this->config->without_shipping && ($order->order_package>0 || $this->config->display_null_package_price)){?>
            <tr class="package">
                <td class="name">
                    <?php print esc_html(WOPSHOP_PACKAGE_PRICE)?>
                </td>
                <td class="value">
                    <?php print esc_html(wopshopFormatprice($order->order_package, $order->currency_code)); ?>
                    <?php print $this->_tmp_ext_shipping_package; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        
        <?php if ($this->order->order_payment > 0){?>
            <tr class="payment">
                <td class = "name">
                    <?php print esc_html($this->order->payment_name);?>
                </td>
                <td class = "value">
                    <?php print esc_html(wopshopFormatprice($this->order->order_payment, $order->currency_code));?>
                    <?php print $this->_tmp_ext_payment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
          
        <?php if (!$this->config->hide_tax){ ?>
            <?php foreach($order->order_tax_list as $percent=>$value){?>
                <tr class="tax">
                    <td class = "name">
                        <?php print esc_html(wopshopDisplayTotalCartTaxName($order->display_price));?>
                        <?php if ($this->show_percent_tax) print esc_html(wopshop_formattax($percent))."%"?>
                    </td>
                    <td class = "value">
                        <?php print esc_html(wopshopFormatprice($value, $order->currency_code));?>
                        <?php print esc_html($this->_tmp_ext_tax[$percent])?>
                    </td>
                </tr>
            <?php }?>
        <?php }?>
        <tr class="total">
            <td class = "name">
                <?php print esc_html($this->text_total);?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($order->order_total, $order->currency_code));?>
                <?php print $this->_tmp_ext_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
        <?php print $this->_tmp_html_after_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </table>

    <?php if (!$this->config->without_shipping){?>
        <div class="shipping_block_info">
            <div class="shipping_head">
                <b><?php print esc_html(WOPSHOP_SHIPPING_INFORMATION )?></b>
            </div>
            
            <div class="shipping_info">
                <?php print esc_html(nl2br($order->shipping_info));?>
            </div>
            
            <div class="order_shipping_params">
                <?php print esc_html(nl2br($order->shipping_params));?>
            </div>
            
            <?php if ($order->delivery_time_name){?>
                <div class="delivery_time">
                    <?php echo esc_html(WOPSHOP_DELIVERY_TIME.": ".$order->delivery_time_name)?>
                </div>
            <?php }?>
            
            <?php if ($order->delivery_date_f){?>
                <div class="delivery_date">
                    <?php echo esc_html(WOPSHOP_DELIVERY_DATE.": ".$order->delivery_date_f)?>
                </div>
            <?php }?>
        </div>
    <?php }?>
    
    <?php if (!$this->config->without_payment){?>
        <div class="payment_block_info">
            <div class="payment_head">
                <b><?php print esc_html(WOPSHOP_PAYMENT_INFORMATION )?></b>
            </div>
            <div class="payment_info">
                <?php print esc_html($order->payment_name);?>
            </div>
            <div class="order_payment_params">
                <?php print wp_kses_post(nl2br($order->payment_params));?>
                <?php print wp_kses_post($order->payment_description);?>
            </div>
        </div>
    <?php }?>

    <?php if ($order->order_add_info){ ?>
        <div class="order_comment">
            <div class="order_comment_head">
                <b><?php print esc_html(WOPSHOP_ORDER_COMMENT )?></b>
            </div>
            <div class="order_comment_info">
                <?php print wp_kses_post($order->order_add_info) ?>
            </div>
        </div>
    <?php } ?>
    
    <?php print $this->_tmp_html_after_comment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

    <div class="history">
        <div class="history_head">
            <b><?php print esc_html(WOPSHOP_ORDER_HISTORY )?></b>
        </div>
        <div class="order_history">
            <table>
                <?php foreach($order->history as $history){?>
                    <tr>
                        <td class="date">
                            <?php  print esc_html(wopshop_formatdate($history->status_date_added, 0)); ?>
                        </td>
                        <td class="name">
                            <?php print esc_html($history->status_name) ?>
                        </td>
                        <td class="comment">
                            <?php print wp_kses_post(nl2br($history->comments))?>
                        </td>
                    </tr>
                <?php } ?>
             </table>
        </div>
    </div>
    
    <?php print $this->_tmp_html_after_history; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    
    <?php if ($this->allow_cancel){?>
        <div class="button_cancel">
            <a href="<?php print esc_url(wopshopSEFLink('controller=user&task=cancelorder&order_id='.$order->order_id))?>" class = "btn">
                <?php print esc_html(WOPSHOP_CANCEL_ORDER)?>
            </a>
        </div>
    <?php }?>
    
    <?php print $this->_tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>