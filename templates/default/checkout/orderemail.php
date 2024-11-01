<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php $order = $this->order;?>
<html>
<title></title>
<head>
<style type = "text/css">
html{
    font-family:Tahoma;
    line-height:100%;
}
body, td{
    font-size:12px;
    font-family:Tahoma;
}
td.bg_gray, tr.bg_gray td {
    background-color: #CCCCCC;
}
table {
    border-collapse:collapse;
    border:0;
}
td{
    padding-left:3px;
    padding-right: 3px;
    padding-top:0px;
    padding-bottom:0px;
}
tr.bold td{
    font-weight:bold;
}
tr.vertical td{
    vertical-align:top;
    padding-bottom:10px;
}
h3{
    font-size:14px;
    margin:2px;
}
.wshop_cart_attribute{
    padding-top: 5px;
    font-size:11px;
}
.taxinfo{
    font-size:11px;
}
</style>
</head>
<body>
<?php print wp_kses_post($this->_tmp_ext_html_ordermail_start);?>
<table width="794px" align="center" border="0" cellspacing="0" cellpadding="0" style="line-height:100%;">
  <tr valign="top">
     <td colspan = "2">
       <?php print wp_kses_post($this->info_shop);?>
     </td>
  </tr>
  <?php if ($this->client){?>
  <tr>
     <td colspan = "2" style="padding-bottom:10px;">
       <?php print wp_kses_post($this->order_email_descr);?>
     </td>
  </tr>
  <?php }?>
  <tr class = "bg_gray">
     <td colspan = "2">
        <h3><?php print esc_html(WOPSHOP_EMAIL_PURCHASE_ORDER)?></h3>
     </td>
  </tr>
  <tr><td style="height:10px;font-size:1px;">&nbsp;</td></tr>
  <tr>
     <td width="50%">
        <?php print esc_html(WOPSHOP_ORDER_NUMBER)?>:
     </td>
     <td width="50%">
        <?php print esc_html($this->order->order_number)?>
     </td>
  </tr>
  <tr>
     <td>
        <?php print esc_html(WOPSHOP_ORDER_DATE)?>:
     </td>
     <td>
        <?php print esc_html($this->order->order_date)?>
     </td>
  </tr>
  <tr>
     <td>
        <?php print esc_html(WOPSHOP_ORDER_STATUS)?>:
     </td>
     <td>
        <?php print esc_html($this->order->status)?>
     </td>
  </tr>
<?php if ($this->show_customer_info){?>
  <tr><td style="height:10px;font-size:1px;">&nbsp;</td></tr>
  <tr class="bg_gray">
    <td colspan="2" width = "50%">
       <h3><?php print esc_html(WOPSHOP_CUSTOMER_INFORMATION)?></h3>
    </td>
  </tr>
  <tr>
    <td  style="vertical-align:top;padding-top:10px;" width = "50%">
      <table cellspacing="0" cellpadding="0" style="line-height:100%;">
        <tr>
          <td colspan="2"><b><?php print esc_html(WOPSHOP_EMAIL_BILL_TO)?></b></td>
        </tr>
        <?php if ($this->config_fields['title']['display']){?>
        <tr>
          <td width="100"><?php print esc_html(WOPSHOP_REG_TITLE)?>:</td>
          <td><?php print esc_html($this->order->title)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['firma_name']['display']){?>
        <tr>
          <td width="100"><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</td>
          <td><?php print esc_html($this->order->firma_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['f_name']['display']){?>
        <tr>
          <td width="100"><?php print esc_html(WOPSHOP_FULL_NAME)?>:</td>
          <td><?php print esc_html($this->order->f_name)?> <?php print esc_html($this->order->l_name)?> <?php print esc_html($this->order->m_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['birthday']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</td>
          <td><?php print esc_html($this->order->birthday);?></td>
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
          <td><?php print esc_html($this->order->street)?> <?php if ($this->config_fields['street_nr']['display']){?><?php print esc_html($this->order->street_nr)?><?php }?></td>
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
    </td>
    <td style="vertical-align:top;padding-top:10px;" width = "50%">
    <?php if ($this->count_filed_delivery >0) {?>
    <table cellspacing="0" cellpadding="0" style="line-height:100%;">
        <tr>
            <td colspan=2><b><?php print esc_html(WOPSHOP_EMAIL_SHIP_TO)?></b></td>
        </tr>
        <?php if ($this->config_fields['d_title']['display']){?>
        <tr>
          <td width="100"><?php print esc_html(WOPSHOP_REG_TITLE)?>:</td>
          <td><?php print esc_html($this->order->d_title)?></td>
        </tr>
        <?php } ?>      
        <?php if ($this->config_fields['d_firma_name']['display']){?>
        <tr>
            <td width="100"><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</td>
            <td ><?php print esc_html($this->order->d_firma_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_f_name']['display']){?>
        <tr>
            <td width="100"><?php print esc_html(WOPSHOP_FULL_NAME)?> </td>
            <td><?php print esc_html($this->order->d_f_name)?> <?php print esc_html($this->order->d_l_name)?> <?php print esc_html($this->order->d_m_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['birthday']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</td>
          <td><?php print esc_html($this->order->d_birthday);?></td>
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
            <td><?php print esc_html($this->order->d_street)?> <?php if ($this->config_fields['d_street_nr']['display']){?><?php print esc_html($this->order->d_street_nr);?><?php }?></td>
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
            <td><?php print esc_html(WOPSHOP_ZIP) ?>:</td>
            <td><?php print esc_html($this->order->d_zip); ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_country']['display']){?>
        <tr>
            <td><?php print esc_html(WOPSHOP_COUNTRY); ?>:</td>
            <td><?php print esc_html($this->order->d_country); ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_phone']['display']){?>
        <tr>
            <td><?php print esc_html(WOPSHOP_TELEFON); ?>:</td>
            <td><?php print esc_html($this->order->d_phone);?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_mobil_phone']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_MOBIL_PHONE);?>:</td>
          <td><?php print esc_html($this->order->d_mobil_phone);?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_fax']['display']){?>
        <tr>
        <td><?php print esc_html(WOPSHOP_FAX); ?>:</td>
        <td><?php print esc_html($this->order->d_fax); ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_email']['display']){?>
        <tr>
        <td><?php print esc_html(WOPSHOP_EMAIL); ?>:</td>
        <td><?php print esc_html($this->order->d_email); ?></td>
        </tr>
        <?php } ?>                            
        <?php if ($this->config_fields['d_ext_field_1']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_EXT_FIELD_1);?>:</td>
          <td><?php print esc_html($this->order->d_ext_field_1);?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_2']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_EXT_FIELD_2);?>:</td>
          <td><?php print esc_html($this->order->d_ext_field_2);?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_3']['display']){?>
        <tr>
          <td><?php print esc_html(WOPSHOP_EXT_FIELD_3);?>:</td>
          <td><?php print esc_html($this->order->d_ext_field_3);?></td>
        </tr>
        <?php } ?>            
    </table>
    <?php }?> 
    </td>
  </tr>
<?php }?>
  <tr>
    <td colspan = "2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan = "2" class="bg_gray">
      <h3><?php print esc_html(WOPSHOP_ORDER_ITEMS) ?></h3>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="padding:0px;padding-top:10px;">
       <table width="100%" cellspacing="0" cellpadding="0" class="table_items">
        <tr><td colspan="5" style="vertical-align:top;padding-bottom:5px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td></tr>
         <tr class = "bold">            
            <td width="45%" style="padding-left:10px;padding-bottom:5px;"><?php print esc_html(WOPSHOP_NAME_PRODUCT)?></td>
            <td width="15%" style="padding-bottom:5px;"><?php if ($this->config->show_product_code_in_order){?><?php print esc_html(WOPSHOP_EAN_PRODUCT)?><?php } ?></td>
            <td width="10%" style="padding-bottom:5px;"><?php print esc_html(WOPSHOP_QUANTITY)?></td>
            <td width="15%" style="padding-bottom:5px;"><?php print esc_html(WOPSHOP_SINGLEPRICE)?></td>
            <td width="15%" style="padding-bottom:5px;"><?php print esc_html(WOPSHOP_PRICE_TOTAL)?></td>
         </tr>
         <tr><td colspan="5" style="vertical-align:top;padding-bottom:10px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td></tr>
         <?php 
         foreach($this->products as $key_id=>$prod){
             $files = json_decode($prod->files);
         ?>
         <tr class="vertical">
           <td>
                <img src="<?php print esc_url($this->config->image_product_live_path.'/'.($prod->thumb_image ? $prod->thumb_image : $this->noimage))?>" align="left" style="margin-right:5px;">
                <?php print esc_html($prod->product_name);?>
                <?php if ($prod->manufacturer!=''){?>
                <div class="manufacturer"><?php print esc_html(WOPSHOP_MANUFACTURER)?>: <span><?php print esc_html($prod->manufacturer)?></span></div>
                <?php }?>
                <div class="wshop_cart_attribute">
                <?php print wp_kses_post(wopshopSprintAtributeInOrder($prod->product_attributes)); ?>
                <?php print wp_kses_post(wopshopSprintFreeAtributeInOrder($prod->product_freeattributes));?>
                <?php print wp_kses_post(wopshopSprintExtraFiledsInOrder($prod->extra_fields));?>
                </div>
                <?php print wp_kses_post($prod->_ext_attribute_html);?>
                <?php if ($this->config->display_delivery_time_for_product_in_order_mail && $prod->delivery_time){?>
                <div class="deliverytime"><?php print esc_html(WOPSHOP_DELIVERY_TIME)?>: <?php print esc_html($prod->delivery_time)?></div>
                <?php }?>
           </td>           
           <td><?php if ($this->config->show_product_code_in_order){?><?php print esc_html($prod->product_ean);?><?php } ?></td>
           <td><?php print esc_html(wopshop_formatqty($prod->product_quantity));?><?php print esc_html($prod->_qty_unit);?></td>
           <td>
                <?php print esc_html(wopshopFormatprice($prod->product_item_price, $order->currency_code)); ?>
                <?php print wp_kses_post($prod->_ext_price_html);?>
                <?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
                    <div class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod->product_tax, $order->display_price));?></div>
                <?php }?>
				<?php if ($this->config->cart_basic_price_show && $prod->basicprice>0){?>
                    <div class="basic_price"><?php print esc_html(WOPSHOP_BASIC_PRICE)?>: <span><?php print esc_html(wopshopSprintBasicPrice($prod));?></span></div>
                <?php }?>
           </td>
           <td>
                <?php print esc_html(wopshopFormatprice($prod->product_item_price*$prod->product_quantity, $order->currency_code)); ?>
                <?php print wp_kses_post($prod->_ext_price_total_html)?>
                <?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
                    <div class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod->product_tax, $order->display_price));?></div>
                <?php }?>
            </td>
         </tr>
         <?php if (count($files) && $files!==false){?>
         <tr>
            <td colspan="5">
            <?php foreach($files as $file){?>
                <div><?php print wp_kses_post($file->file_descr)?> <a href="<?php print esc_url(wopshopSEFLink('controller=product&task=getfile&oid='.$this->order->order_id.'&id='.$file->id.'&hash='.$this->order->file_hash.'&rl=1'))?>"><?php print esc_html(WOPSHOP_DOWNLOAD)?></a></div>
            <?php }?>    
            </td>
         </tr>
         <?php }?>
         <tr><td colspan="5" style="vertical-align:top;padding-bottom:10px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td></tr>
         <?php } ?>
         <?php if ($this->show_weight_order && $this->config->show_weight_order){?>
         <tr>
            <td colspan="5" style="text-align:right;font-size:11px;">            
                <?php print esc_html(WOPSHOP_WEIGHT_PRODUCTS)?>: <span><?php print esc_html(wopshop_formatweight($this->order->weight));?></span>
            </td>
         </tr>   
         <?php }?>
      <?php if ($this->show_total_info){?>
         <tr>
           <td colspan="5">&nbsp;</td>
         </tr>
         <?php if (!$this->hide_subtotal){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html(WOPSHOP_SUBTOTAL) ?>:</td>
           <td class="price"><?php print esc_html(wopshopFormatprice($this->order->order_subtotal, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_subtotal)?></td>
         </tr>
         <?php } ?>
		 <?php print wp_kses_post($this->_tmp_html_after_subtotal);?>
         <?php if ($this->order->order_discount > 0){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html(WOPSHOP_RABATT_VALUE); ?>: </td>
           <td class="price">-<?php print esc_html(wopshopFormatprice($this->order->order_discount, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_discount)?></td>
         </tr>
         <?php } ?>
         <?php if (!$this->config->without_shipping){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html(WOPSHOP_SHIPPING_PRICE) ?>:</td>
           <td class="price"><?php print esc_html(wopshopFormatprice($this->order->order_shipping, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_shipping)?></td>
         </tr>
         <?php } ?>
         <?php if (!$this->config->without_shipping && ($order->order_package>0 || $this->config->display_null_package_price)){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html(WOPSHOP_PACKAGE_PRICE)?>:</td>
           <td class="price"><?php print esc_html(wopshopFormatprice($this->order->order_package, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_shipping_package);?></td>
         </tr>
         <?php } ?>
         <?php if ($this->order->order_payment != 0){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html($this->order->payment_name);?>:</td>
           <td class="price"><?php print esc_html(wopshopFormatprice($this->order->order_payment, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_payment);?></td>
         </tr>
         <?php } ?>
         <?php if (!$this->config->hide_tax){ ?>                           
         <?php foreach($this->order->order_tax_list as $percent=>$value){?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><?php print esc_html(wopshopDisplayTotalCartTaxName($order->display_price));?><?php if ($this->show_percent_tax) print " ".esc_html(wopshop_formattax($percent))."%";?>:</td>
             <td class="price"><?php print esc_html(wopshopFormatprice($value, $order->currency_code)); ?><?php print wp_kses_post($this->_tmp_ext_tax[$percent])?></td>
         </tr>
         <?php } ?>
         <?php } ?>
         <tr>
           <td colspan="4" align="right" style="padding-right:15px;"><b><?php print wp_kses_post($this->text_total) ?>:</b></td>
           <td class="price"><b><?php print esc_html(wopshopFormatprice($this->order->order_total, $order->currency_code))?><?php print wp_kses_post($this->_tmp_ext_total)?></b></td>
         </tr>
		 <?php print wp_kses_post($this->_tmp_html_after_total);?>
         <tr>
           <td colspan="5">&nbsp;</td>
         </tr>
         <?php if (!$this->client){?>
         <tr>
           <td colspan="5" class="bg_gray"><?php print esc_html(WOPSHOP_CUSTOMER_NOTE) ?></td>
         </tr>
         <tr>
           <td colspan="5" style="padding-top:10px;"><?php print wp_kses_post($this->order->order_add_info); ?></td>
         </tr>
         <tr><td>&nbsp;</td></tr>
         <?php } ?>
      <?php }?>
       </table>
    </td>
  </tr>
<?php if ($this->show_payment_shipping_info){?>
  <?php if (!$this->config->without_payment || !$this->config->without_shipping){?>  
  <tr class = "bg_gray">
    <?php if (!$this->config->without_payment){?>
    <td>
        <h3><?php print esc_html(WOPSHOP_PAYMENT_INFORMATION) ?></h3>
    </td>    
    <?php }?>
    <td <?php if ($this->config->without_payment){?> colspan="2" <?php }?>>
        <?php if (!$this->config->without_shipping){?>
        <h3><?php print esc_html(WOPSHOP_SHIPPING_INFORMATION) ?></h3>
        <?php } ?>
    </td>    
  </tr>
  <tr><td style="height:5px;font-size:1px;">&nbsp;</td></tr>
  <tr>
    <?php if (!$this->config->without_payment){?>
    <td valign="top">    
        <div style="padding-bottom:4px;"><?php print esc_html($this->order->payment_name);?></div>
        <div style="font-size:11px;">
        <?php
            print esc_html(nl2br($this->order->payment_information));
            print wp_kses_post($this->order->payment_description);
        ?>
        </div>
    </td>
    <?php }?>
    <td valign="top" <?php if ($this->config->without_payment){?> colspan="2" <?php }?>>
        <?php if (!$this->config->without_shipping){?>
            <div style="padding-bottom:4px;">
                <?php print esc_html(nl2br($this->order->shipping_information));?>
            </div>
            <div style="font-size:11px;">
                <?php print esc_html(nl2br($this->order->shipping_params));?>
            </div>
            <?php if ($this->config->show_delivery_time_checkout && $this->order->order_delivery_time){
                print "<div>".esc_html(WOPSHOP_ORDER_DELIVERY_TIME).": ".esc_html($this->order->order_delivery_time)."</div>";
            }            
            if ($this->config->show_delivery_date && $order->delivery_date_f){
                print "<div>".esc_html(WOPSHOP_DELIVERY_DATE).": ".esc_html($order->delivery_date_f)."</div>";
            }
        }
        ?>
    </td>  
  </tr>
  <?php }?>
<?php }?>
  <?php if ($this->config->show_return_policy_in_email_order){?>
  <tr>
    <td colspan="2"><br/><br/><a class = "policy" target="_blank" href="<?php print esc_url(wopshopSEFLink('controller=content&task=view&page=return_policy&order_id='.$this->order->order_id, 1))?>"><?php print esc_html(WOPSHOP_RETURN_POLICY)?></a></td>
  </tr>
  <?php }?>
  <?php if ($this->client){?>
  <tr>
     <td colspan = "2" style="padding-bottom:10px;">
       <?php print wp_kses_post($this->order_email_descr_end);?>
     </td>
  </tr>
  <?php }?>
</table>
<?php print wp_kses_post($this->_tmp_ext_html_ordermail_end);?>
<br>    
</body>
</html>