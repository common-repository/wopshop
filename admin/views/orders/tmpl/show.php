<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$order=$this->order;
$order_history=$this->order_history;
$order_item=$this->order_items;
$lists=$this->lists;
$print=$this->print;
?>
<div class="wrap wopshop_edit">
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders'))?>" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<input type="hidden" name="order_id" value="<?php print esc_attr($order->order_id)?>">
<table class="adminlist" width="100%">
<tr>
  <td width="50%" style="vertical-align:top;padding-top:14px;">
    <table class="table table-striped">
    <thead>    
      <tr>
        <th colspan="2">
          <?php echo esc_html(WOPSHOP_ORDER_PURCHASE);?>
        </th>
      </tr>
     </thead> 
      <tr>
        <td width="50%">
          <b><?php echo esc_html(WOPSHOP_NUMBER);?></b>
        </td>
        <td>
          <?php echo esc_html($order->order_number);?>
        </td>
      </tr>
      <tr>
        <td width="50%">
          <b><?php echo esc_html(WOPSHOP_DATE);?></b> 
       </td>
        <td>
          <?php echo wopshop_formatdate($order->order_date,1); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </td>
      </tr>
      <tr>
        <td>
          <b><?php echo esc_html(WOPSHOP_STATUS);?></b> 
       </td>
        <td>
          <?php echo esc_html($order->status_name);?>
        </td>
      </tr>
      <tr>
        <td>
          <b><?php echo esc_html(WOPSHOP_IPADRESS);?></b>
       </td>
        <td>
          <?php echo esc_html($order->ip_address);?>
        </td>
      </tr>
      <?php print $this->tmp_html_info // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </table>
  </td>
  <?php if (!$print){?>
  <td width="50%" style="vertical-align: top">
    <div class="tabs">
        <ul class="tab-links">
            <li class="active"><a href="#first-page" data-toggle="tab"><?php echo esc_html(WOPSHOP_STATUS_CHANGE);?></a></li>
            <li><a href="#second-page" data-toggle="tab"><?php echo esc_html(WOPSHOP_ORDER_HISTORY);?></a></li>
        </ul>
        <div class="tab-content">
            <div id="first-page" class="tab-pane active">
                <table width="100%">
                  <tr>
                    <th colspan="2" align="center">
                      <?php echo esc_html(WOPSHOP_STATUS_CHANGE)?>:
                    </th>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <?php echo esc_html(WOPSHOP_ORDER_STATUS)?>
                      <?php echo $lists['status']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                      <input type="button" class="button" name="update_status" onclick="verifyStatus(<?php echo esc_attr($order->order_status)?>, <?php echo esc_attr($order->order_id)?>, '<?php echo esc_attr(WOPSHOP_CHANGE_ORDER_STATUS);?>', 1)" value="<?php echo esc_attr(WOPSHOP_UPDATE_STATUS)?>" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <table>
                        <tr>
                          <td>
                            <?php echo esc_html(WOPSHOP_COMMENT);?>:
                          </td>
                          <td>
                            <textarea id="comments" name="comments"></textarea>
                          </td>
                          <td>
                            <input type="checkbox" class="inputbox"  name="notify" id="notify" /><label for="notify">  <?php echo esc_html(WOPSHOP_NOTIFY_USER);?></label><br />
                            <input type="checkbox" class="inputbox"  name="include" id="include" /><label for="include">  <?php echo esc_html(WOPSHOP_INCLUDE_COMMENT);?></label>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
        <div id="second-page" class="tab-pane">
        <table>
            <tr class="bold">
              <td>
                <?php echo esc_html(WOPSHOP_DATE_ADDED);?>
              </td>
              <td>
                <?php echo esc_html(WOPSHOP_NOTIFY_CUSTOMER);?>
              </td>
              <td>
                <?php echo esc_html(WOPSHOP_STATUS);?>
              </td>
              <td>
                <?php echo esc_html(WOPSHOP_COMMENT);?>
              </td>
            </tr>
          <?php foreach($order_history as $history) {?>
            <tr>
              <td>
                <?php echo esc_html($history->status_date_added)?>
              </td>
              <td class="center">
                <?php $notify_customer=($history->customer_notify) ? ('tick.png'): ('publish_x.png');?>
                <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/'.$notify_customer)?>" alt="notify_customer" border="0" />
              </td>
              <td>
                <?php echo esc_html($history->status_name)?>
              </td>
              <td>
                <?php echo wp_kses_post($history->comments)?>
              </td>
              <?php if (isset($history->tmp_html_table_history_field)) echo $history->tmp_html_table_history_field // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </tr>
          <?php }?>
        </table>
      </div>
    </div>
  </td>
  <?php }?>
</tr>
</table>
<br/>

<table width="100%">
<tr>
    <td width="50%" valign="top">
        <table width="100%" class="table table-striped">
        <thead>
        <tr>
          <th colspan="2" align="center"><?php print esc_html(WOPSHOP_BILL_TO )?></th>
        </tr>
        </thead>
        <?php if ($this->config_fields['title']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_USER_TITLE)?>:</b></td>
          <td><?php print esc_html($this->order->title)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['firma_name']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</b></td>
          <td><?php print esc_html($this->order->firma_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['f_name']['display']){?>
        <tr>
          <td width="40%"><b><?php print esc_html(WOPSHOP_FULL_NAME)?>:</b></td>
		  <td width="60%"><?php print esc_html($this->order->f_name)?> <?php print esc_html($this->order->l_name)?> <?php print esc_html($this->order->m_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['client_type']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_CLIENT_TYPE)?>:</b></td>
          <td><?php print esc_html($this->order->client_type_name);?></td>
        </tr>
        <?php } ?>        
        <?php if ($this->config_fields['firma_code']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIRMA_CODE)?>:</b></td>
          <td><?php print esc_html($this->order->firma_code)?></td>
        </tr>
        <?php } ?>        
        <?php if ($this->config_fields['tax_number']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_VAT_NUMBER)?>:</b></td>
          <td><?php print esc_html($this->order->tax_number)?></td>
        </tr>
        <?php } ?>
		<?php if ($this->config_fields['birthday']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</b></td>
          <td><?php print esc_html($this->order->birthday)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['home']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIELD_HOME)?>:</b></td>
          <td><?php print esc_html($this->order->home)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['apartment']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIELD_APARTMENT)?>:</b></td>
          <td><?php print esc_html($this->order->apartment)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['street']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_STREET_NR)?>:</b></td>          
          <td><?php print esc_html($this->order->street)?> <?php if ($this->config_fields['street_nr']['display']){?><?php print esc_html($this->order->street_nr)?><?php }?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['city']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_CITY)?>:</b></td>
          <td><?php print esc_html($this->order->city)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['state']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_STATE)?>:</b></td>
          <td><?php print esc_html($this->order->state)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['zip']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_ZIP)?>:</b></td>
          <td><?php print esc_html($this->order->zip)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['country']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_COUNTRY)?>:</b></td>
          <td><?php print esc_html($this->order->country)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['phone']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_TELEFON)?>:</b></td>
          <td><?php print esc_html($this->order->phone)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['mobil_phone']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</b></td>
          <td><?php print esc_html($this->order->mobil_phone)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['fax']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FAX)?>:</b></td>
          <td><?php print esc_html($this->order->fax)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['email']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EMAIL)?>:</b></td>
          <td><?php print esc_html($this->order->email)?></td>
        </tr>
        <?php } ?>
        
        <?php if ($this->config_fields['ext_field_1']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</b></td>
          <td><?php print esc_html($this->order->ext_field_1)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_2']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</b></td>
          <td><?php print esc_html($this->order->ext_field_2)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_3']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</b></td>
          <td><?php print esc_html($this->order->ext_field_3)?></td>
        </tr>
        <?php } ?> 
        <?php echo $this->tmp_fields // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </table>
    </td>
    <td width="50%"  valign="top">
    <?php if ($this->count_filed_delivery >0) {?>
        <table width="100%" class="table table-striped">
        <thead>
        <tr>
          <th colspan="2" align="center"><?php print esc_html(WOPSHOP_SHIP_TO )?></th>
        </tr>
        </thead>
        <?php if ($this->config_fields['d_title']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_USER_TITLE)?>:</b></td>
          <td><?php print esc_html($this->order->d_title)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_firma_name']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</b></td>
            <td><?php print esc_html($this->order->d_firma_name)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_f_name']['display']){?>
        <tr>
            <td width="40%"><b><?php print esc_html(WOPSHOP_FULL_NAME)?>:</b></td>
			<td width="60%"><?php print esc_html($this->order->d_f_name)?> <?php print esc_html($this->order->d_l_name)?> <?php print esc_html($this->order->d_m_name)?></td>
        </tr>
        <?php } ?>
		<?php if ($this->config_fields['d_birthday']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</b></td>
          <td><?php print esc_html($this->order->d_birthday)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_home']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIELD_HOME)?>:</b></td>
          <td><?php print esc_html($this->order->d_home)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_apartment']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_FIELD_APARTMENT)?>:</b></td>
          <td><?php print esc_html($this->order->d_apartment)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_street']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_STREET_NR)?>:</b></td>            
            <td><?php print esc_html($this->order->d_street)?> <?php if ($this->config_fields['d_street_nr']['display']){?><?php print esc_html($this->order->d_street_nr)?><?php }?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_city']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_CITY)?>:</b></td>
            <td><?php print esc_html($this->order->d_city)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_state']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_STATE)?>:</b></td>
            <td><?php print esc_html($this->order->d_state)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_zip']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_ZIP) ?>:</b></td>
            <td><?php print esc_html($this->order->d_zip) ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_country']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_COUNTRY) ?>:</b></td>
            <td><?php print esc_html($this->order->d_country) ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_phone']['display']){?>
        <tr>
            <td><b><?php print esc_html(WOPSHOP_TELEFON) ?>:</b></td>
            <td><?php print esc_html($this->order->d_phone) ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_mobil_phone']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</b></td>
          <td><?php print esc_html($this->order->d_mobil_phone)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_fax']['display']){?>
        <tr>
        <td><b><?php print esc_html(WOPSHOP_FAX) ?>:</b></td>
        <td><?php print esc_html($this->order->d_fax) ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_email']['display']){?>
        <tr>
        <td><b><?php print esc_html(WOPSHOP_EMAIL) ?>:</b></td>
        <td><?php print esc_html($this->order->d_email) ?></td>
        </tr>
        <?php } ?>                            
        <?php if ($this->config_fields['d_ext_field_1']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</b></td>
          <td><?php print esc_html($this->order->d_ext_field_1)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_2']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</b></td>
          <td><?php print esc_html($this->order->d_ext_field_2)?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_3']['display']){?>
        <tr>
          <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</b></td>
          <td><?php print esc_html($this->order->d_ext_field_3)?></td>
        </tr>
        <?php } ?>
        <?php echo $this->tmp_d_fields // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      </table>
    <?php } ?>  
    </td>
</tr>
<?php print $this->_tmp_html_after_customer_info;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</table>

<br/>
<table class="table table-striped" width="100%">
<thead>
<tr>
 <th>
   <?php echo esc_html(WOPSHOP_NAME_PRODUCT)?>
 </th>
 <?php if ($this->config->show_product_code_in_order){?>
 <th>
   <?php echo esc_html(WOPSHOP_EAN_PRODUCT)?>
 </th>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <th>
   <?php echo esc_html(WOPSHOP_VENDOR)?>
 </th>
 <?php }?>
 <th>
   <?php echo esc_html(WOPSHOP_PRICE)?>
 </th>
 <th>
   <?php echo esc_html(WOPSHOP_QUANTITY)?>
 </th> 
 <th>
   <?php echo esc_html(WOPSHOP_TOTAL)?>
 </th>
</tr>
</thead>
<?php foreach ($order_item as $item){ ?>
<tr>
 <td>
   <a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=edit&product_id='.$item->product_id))?>">
    <?php echo esc_html($item->product_name)?>
   </a><br />
   <?php print wopshopSprintAtributeInOrder($item->product_attributes).wopshopSprintFreeAtributeInOrder($item->product_freeattributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
 <?php if ($this->config->show_product_code_in_order){?>
 <td>
   <?php echo esc_html($item->product_ean)?>
 </td>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <td>
   <?php echo $this->order_vendors[$item->vendor_id]->f_name." ".$this->order_vendors[$item->vendor_id]->l_name;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
 <?php }?>
 <td>
   <?php echo wopshopFormatprice($item->product_item_price, $order->currency_code); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   <?php if (isset($item->_ext_price_html)) print $item->_ext_price_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
 <td>
   <?php if (isset($item->product_quantity)) echo wopshop_formatqty($item->product_quantity)?><?php if (isset($item->_qty_unit)) print $item->_qty_unit // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td> 
 <td>
   <?php echo wopshopFormatprice($item->product_quantity * $item->product_item_price, $order->currency_code); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   <?php if (isset($item->_ext_price_total_html)) print $item->_ext_price_total_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php }?>
</table>

<?php if (!$this->display_info_only_product){?>
<table class="table table-striped" width="100%">
<tr>
 <td colspan="5" style="height: 20px">
    <?php if ($this->config->show_weight_order){?>  
    <div style="text-align:right;">
        <i><?php print esc_html(WOPSHOP_WEIGHT_PRODUCTS)?>: <span><?php print wopshop_formatweight($this->order->weight); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></i>
    </div><br/>
  <?php }?>
 </td>
</tr>
<tr class="bold">
 <td colspan="4" class="right">
    <?php echo esc_html(WOPSHOP_SUBTOTAL)?>
 </td>
 <td class="left" width="18%">
   <?php if (isset($order->order_subtotal) && isset($order->currency_code)) echo wopshopFormatprice($order->order_subtotal, $order->currency_code);?><?php if (isset($this->_tmp_ext_subtotal)) print $this->_tmp_ext_subtotal // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php print $this->_tmp_html_after_subtotal // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php if ($order->order_discount > 0){?>
<tr class="bold">
 <td colspan="4" class="right">
    <?php echo esc_html(WOPSHOP_COUPON_DISCOUNT)?>
    <?php if ($order->coupon_id){?>(<?php print esc_html($order->coupon_code)?>)<?php }?>
    <?php print $this->_tmp_ext_discount_text // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
 <td class="left">
   <?php echo wopshopFormatprice(-$order->order_discount, $order->currency_code); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php print $this->_tmp_ext_discount // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php } ?>

<?php if (!$this->config->without_shipping || $order->order_shipping > 0){?>
<tr class="bold">
 <td colspan="4" class="right">
    <?php echo esc_html(WOPSHOP_SHIPPING_PRICE)?>
 </td>
 <td class="left">
   <?php if (isset($order->order_shipping) && isset($order->currency_code)) echo wopshopFormatprice($order->order_shipping, $order->currency_code);?><?php if (isset($this->_tmp_ext_shipping)) print $this->_tmp_ext_shipping // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php } ?>
<?php if (!$this->config->without_shipping || $order->order_package > 0){?>
<tr class = "bold">
 <td colspan = "4" class = "right">
    <?php echo esc_html(WOPSHOP_PACKAGE_PRICE)?>
 </td>
 <td class = "left">
   <?php echo wopshopFormatprice($order->order_package, $order->currency_code); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php print $this->_tmp_ext_shipping_package // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php } ?>

<?php if ($order->order_payment > 0){?>
<tr class="bold">
 <td colspan="4" class="right">
     <?php print esc_html($order->payment_name);?>
 </td>
 <td class="left">
   <?php if (isset($order->order_payment) && isset($order->currency_code)) echo wopshopFormatprice($order->order_payment, $order->currency_code);?><?php if (isset($this->_tmp_ext_payment)) print $this->_tmp_ext_payment // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php } ?>

<?php if (!$this->config->hide_tax){?>
    <?php foreach($order->order_tax_list as $percent=>$value){?>
      <tr class="bold">
        <td  colspan="4" class="right">
          <?php print wopshopDisplayTotalCartTaxName($order->display_price); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          <?php print esc_html($percent."%")?>
        </td>
        <td  class="left">
          <?php if (isset($value) && isset($order->currency_code)) print wopshopFormatprice($value, $order->currency_code);?><?php if (isset($this->_tmp_ext_tax[$percent])) print $this->_tmp_ext_tax[$percent] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </td>
      </tr>
    <?php }?>
<?php }?>
<tr class="bold">
 <td colspan="4" class="right">
    <?php echo esc_html(WOPSHOP_TOTAL)?>
 </td>
 <td class="left">
   <?php if (isset($order->order_total) && isset($order->currency_code)) echo wopshopFormatprice($order->order_total, $order->currency_code);?><?php if (isset($this->_tmp_ext_total)) print $this->_tmp_ext_total // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
 </td>
</tr>
<?php print $this->_tmp_html_after_total // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</table>
<?php }?>
<br/>

<table class="table table-striped">
<thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <th width="33%">
    <?php echo esc_html(WOPSHOP_SHIPPING_INFORMATION)?>
    </th>
    <?php }?>
    <?php if (!$this->config->without_payment){?>
    <th width="33%">
    <?php echo esc_html(WOPSHOP_PAYMENT_INFORMATION)?>
    </th>
    <?php } ?>
    <th width="34%">
    <?php echo esc_html(WOPSHOP_CUSTOMER_COMMENT)?>
    </th>
</tr>
</thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php echo $order->shipping_info // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
        <?php if ($order->delivery_time_name){?>
        <div><?php echo esc_html(WOPSHOP_DELIVERY_TIME.": ".$order->delivery_time_name)?></div>
        <?php }?>
        <?php if ($order->delivery_date_f){?>
        <div><?php echo esc_html(WOPSHOP_DELIVERY_DATE.": ".$order->delivery_date_f)?></div>
        <?php }?>
    </td>
    <?php } ?>
    <?php if (!$this->config->without_payment){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php print esc_html($order->payment_name); ?></div>
        <div><i><?php echo esc_html(nl2br($order->payment_params))?></i></div>
    </td>
    <?php } ?>
    <td valign="top"><?php echo esc_html($order->order_add_info)?></td>    
</tr>
</table>

<?php if (count($this->stat_download)){?>
<br/>
<table class="adminlist">
<thead>
<tr>
    <th width="50%">
        <?php echo esc_html(WOPSHOP_FILE_SALE)?>
    </th>
    <th>
        <?php echo esc_html(WOPSHOP_COUNT_DOWNLOAD)?>
    </th>
</tr>
</thead>
<?php foreach($this->stat_download as $v){?>
<tr>
    <td><?php print wp_kses_post($v->file_descr)?></td>
    <td><?php print esc_html($v->count_download)?></td>
</tr>
<?php }?>
</table>
<div class="order_stat_file_download_clear">
    <a onclick="return confirm('<?php print esc_attr(WOPSHOP_CLEAR)?>')" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=stat_file_download_clear&order_id='.$order->order_id))?>"><?php print esc_html(WOPSHOP_CLEAR)?></a>
</div>
<?php }?>
<?php print $this->_ext_end_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<input type="hidden" name="js_nolang" id='js_nolang' value="0" />
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</form>
</div>