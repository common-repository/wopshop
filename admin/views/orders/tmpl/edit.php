<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}

wp_enqueue_script('jquery-ui-datepicker');

?>
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker({
        dateFormat : 'dd.mm.yy'
    });
});

</script>	

<?php
$order = $this->order;
$order_item = $this->order_items;
$config_fields = $this->config_fields;
?>

<script type="text/javascript">
var admin_show_attributes = <?php print $this->config->admin_show_attributes // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
var admin_show_freeattributes = <?php print $this->config->admin_show_freeattributes // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
var admin_order_edit_more = <?php print $this->config->admin_order_edit_more // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
var hide_tax = <?php print intval($this->config->hide_tax) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
var lang_load='<?php print esc_html(WOPSHOP_LOAD)?>';
var lang_price='<?php print esc_html(WOPSHOP_PRICE)?>';
var lang_tax='<?php print esc_html(WOPSHOP_TAX)?>';
var lang_weight='<?php print esc_html(WOPSHOP_PRODUCT_WEIGHT)?>';
var lang_vendor='<?php print esc_html(WOPSHOP_VENDOR)?>';
function selectProductBehaviour(pid, eName){
    var currency_id = jQuery('#currency_id').val();
    loadProductInfoRowOrderItem(pid, eName, currency_id);
    jQuery("#my-dialog").empty();
    jQuery("#my-dialog").dialog('close');
}
var userinfo_fields = {};
<?php foreach ($config_fields as $k=>$v){
    if ($v['display']) echo "userinfo_fields['".$k."']='';"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}?>
var path = "<?php echo esc_html(WOPSHOP_PLUGIN_URL); ?>";
var userinfo_ajax = null;
var userinfo_link = ajaxurl + "<?php print "?page=wopshop-clients&task=get_userinfo&action=wopshop_userinfo_json" // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
</script>
<div class="wrap">
    <form action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=save'))?>" method = "post" name = "adminForm">
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php if (!$this->display_info_only_product){?>
    <?php if (!$order->order_created){?>
        <div><?php print esc_html(WOPSHOP_FINISHED)?>: <input type="checkbox" name="order_created" value="1"></div>
    <?php }?>
    <div><?php print esc_html(WOPSHOP_USER)?>: <?php echo $this->users_list_select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    <?php if ($this->config->date_invoice_in_invoice){?>
      <div>
        <?php print esc_html(WOPSHOP_INVOICE_DATE)?>: 
        <input id="invoice_date" class="datepicker invoice_date" data-format="dd-MM-yyyy hh:mm:ss" type="text" name="invoice_date" value="<?php echo esc_attr($order->invoice_date);?>">
      </div>
    <?php } ?>
    <table class="wopshop_address" width="100%">
      <tr>
        <td width="50%" valign="top">
            <table width = "100%" class = "adminlist">
            <thead>
            <tr>
              <th colspan="2" align="center"><?php print esc_html(WOPSHOP_BILL_TO); ?></th>
            </tr>
            </thead>
            <?php if ($config_fields['title']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_USER_TITLE)?>:</b></td>
              <td><?php print $this->select_titles // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['firma_name']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</b></td>
              <td><input type="text" name="firma_name" value="<?php print esc_attr($order->firma_name)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['f_name']['display']){?>
            <tr>
              <td width = "40%"><b><?php print esc_html(WOPSHOP_FULL_NAME)?>:</b></td>
              <td width = "60%"><input type="text" name="f_name" value="<?php print esc_attr($order->f_name)?>" /> <input type="text" name="l_name" value="<?php print esc_attr($order->l_name)?>" /> <?php if ($config_fields['m_name']['display']){?><input type="text" name="m_name" value="<?php print esc_attr($order->m_name)?>" /><?php }?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['client_type']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_CLIENT_TYPE)?>:</b></td>
              <td><?php print $this->select_client_types; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['firma_code']['display']){?>
            <tr id="tr_field_firma_code" <?php if ($config_fields['client_type']['display'] && $order->client_type!="2"){?>style="display:none;"<?php } ?>>
              <td><b><?php print esc_html(WOPSHOP_FIRMA_CODE)?>:</b></td>
              <td><input type="text" name="firma_code" value="<?php print esc_attr($order->firma_code)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['tax_number']['display']){?>
            <tr id="tr_field_tax_number" <?php if ($config_fields['client_type']['display'] && $order->client_type!="2"){?>style="display:none;"<?php } ?>>
              <td><b><?php print esc_html(WOPSHOP_VAT_NUMBER)?>:</b></td>
              <td><input type="text" name="tax_number" value="<?php print esc_attr($order->tax_number)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['birthday']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</b></td>
              <td>
                  <input id="birthday" class="datepicker birthday" type="text" size="40" name="birthday" value="<?php echo esc_attr($order->birthday);?>">
                  <?php /*echo JWopshopHtml::_('calendar', $order->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['home']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIELD_HOME)?>:</b></td>
              <td><input type="text" name="home" value="<?php print esc_attr($order->home)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['apartment']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIELD_APARTMENT)?>:</b></td>
              <td><input type="text" name="apartment" value="<?php print esc_attr($order->apartment)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['street']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_STREET_NR)?>:</b></td>
              <td>
              <input type="text" name="street" value="<?php print esc_attr($order->street)?>" />
              <?php if ($config_fields['street_nr']['display']){?>
              <input type="text" name="street_nr" value="<?php print esc_attr($order->street_nr)?>" />
              <?php }?>
              </td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['city']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_CITY)?>:</b></td>
              <td><input type="text" name="city" value="<?php print esc_attr($order->city)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['state']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_STATE)?>:</b></td>
              <td><input type="text" name="state" value="<?php print esc_attr($order->state)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['zip']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_ZIP)?>:</b></td>
              <td><input type="text" name="zip" value="<?php print esc_attr($order->zip)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['country']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_COUNTRY)?>:</b></td>
              <td><?php print $this->select_countries // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['phone']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_TELEFON)?>:</b></td>
              <td><input type="text" name="phone" value="<?php print esc_attr($order->phone)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['mobil_phone']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</b></td>
              <td><input type="text" name="mobil_phone" value="<?php print esc_attr($order->mobil_phone)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['fax']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FAX)?>:</b></td>
              <td><input type="text" name="fax" value="<?php print esc_attr($order->fax)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['email']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EMAIL)?>:</b></td>
              <td><input type="text" name="email" value="<?php print esc_attr($order->email)?>" /></td>
            </tr>
            <?php } ?>

            <?php if ($config_fields['ext_field_1']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</b></td>
              <td><input type="text" name="ext_field_1" value="<?php print esc_attr($order->ext_field_1)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['ext_field_2']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</b></td>
              <td><input type="text" name="ext_field_2" value="<?php print esc_attr($order->ext_field_2)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['ext_field_3']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</b></td>
              <td><input type="text" name="ext_field_3" value="<?php print esc_attr($order->ext_field_3)?>" /></td>
            </tr>
            <?php } ?>
            <?php echo $this->tmp_fields // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </table>
        </td>
        <td width="50%"  valign="top">
        <?php if ($this->count_filed_delivery >0) {?>
            <table width = "100%" class = "adminlist">
            <thead>
            <tr>
              <th colspan="2" align="center"><?php print esc_html(WOPSHOP_SHIP_TO )?></th>
            </tr>
            </thead>
            <?php if ($config_fields['d_title']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_USER_TITLE)?>:</b></td>
              <td><?php print $this->select_d_titles // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_firma_name']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIRMA_NAME)?>:</b></td>
              <td><input type="text" name="d_firma_name" value="<?php print esc_attr($order->d_firma_name)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_f_name']['display']){?>
            <tr>
              <td width = "40%"><b><?php print esc_html(WOPSHOP_FULL_NAME)?>:</b></td>
              <td width = "60%"><input type="text" name="d_f_name" value="<?php print esc_attr($order->d_f_name)?>" /> <input type="text" name="d_l_name" value="<?php print esc_attr($order->d_l_name)?>" /> <?php if ($config_fields['d_m_name']['display']){?><input type="text" name="d_m_name" value="<?php print esc_attr($order->d_m_name)?>" /><?php }?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_birthday']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_BIRTHDAY)?>:</b></td>
              <td>
                  <input id="d_birthday" class="datepicker d_birthday" type="text" size="40" name="d_birthday" value="<?php echo esc_attr($order->d_birthday);?>">
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_home']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIELD_HOME)?>:</b></td>
              <td><input type="text" name="d_home" value="<?php print esc_attr($order->d_home)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_apartment']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FIELD_APARTMENT)?>:</b></td>
              <td><input type="text" name="d_apartment" value="<?php print esc_attr($order->d_apartment)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_street']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_STREET_NR)?>:</b></td>
              <td>
              <input type="text" name="d_street" value="<?php print esc_attr($order->d_street)?>" />
              <?php if ($config_fields['d_street_nr']['display']){?>
              <input type="text" name="d_street_nr" value="<?php print esc_attr($order->d_street_nr)?>" />
              <?php }?>
              </td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_city']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_CITY)?>:</b></td>
              <td><input type="text" name="d_city" value="<?php print esc_attr($order->d_city)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_state']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_STATE)?>:</b></td>
              <td><input type="text" name="d_state" value="<?php print esc_attr($order->d_state)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_zip']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_ZIP)?>:</b></td>
              <td><input type="text" name="d_zip" value="<?php print esc_attr($order->d_zip)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_country']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_COUNTRY)?>:</b></td>
              <td><?php print $this->select_d_countries // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_phone']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_TELEFON)?>:</b></td>
              <td><input type="text" name="d_phone" value="<?php print esc_attr($order->d_phone)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_mobil_phone']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_MOBIL_PHONE)?>:</b></td>
              <td><input type="text" name="d_mobil_phone" value="<?php print esc_attr($order->d_mobil_phone)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_fax']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_FAX)?>:</b></td>
              <td><input type="text" name="d_fax" value="<?php print esc_attr($order->d_fax)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_email']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EMAIL)?>:</b></td>
              <td><input type="text" name="d_email" value="<?php print esc_attr($order->d_email)?>" /></td>
            </tr>
            <?php } ?>

            <?php if ($config_fields['d_ext_field_1']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_1)?>:</b></td>
              <td><input type="text" name="d_ext_field_1" value="<?php print esc_attr($order->d_ext_field_1)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_ext_field_2']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_2)?>:</b></td>
              <td><input type="text" name="d_ext_field_2" value="<?php print esc_attr($order->d_ext_field_2)?>" /></td>
            </tr>
            <?php } ?>
            <?php if ($config_fields['d_ext_field_3']['display']){?>
            <tr>
              <td><b><?php print esc_html(WOPSHOP_EXT_FIELD_3)?>:</b></td>
              <td><input type="text" name="d_ext_field_3" value="<?php print esc_attr($order->d_ext_field_3)?>" /></td>
            </tr>
            <?php } ?>
            <?php echo $this->tmp_d_fields // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </table>
        <?php } ?>  
        </td>
    </tr>
    <?php print $this->_tmp_html_after_customer_info;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </table>
    <?php } ?>
    <br/>
    <div>
    <?php print esc_html(WOPSHOP_CURRENCIES)?>: <?php print $this->select_currency // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> &nbsp;
    <?php print esc_html(WOPSHOP_DISPLAY_PRICE)?>: <?php print $this->display_price_select // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> &nbsp;
    <?php print esc_html(WOPSHOP_LANGUAGE_NAME)?>: <?php print $this->select_language // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
    <br/>
    <table class="adminlist" width="100%" id='list_order_items' border="1">
    <thead>
    <tr>
     <th>
       <?php echo esc_html(WOPSHOP_NAME_PRODUCT)?>
     </th>
     <th>
       <?php echo esc_html(WOPSHOP_EAN_PRODUCT)?>
     </th>
     <th>
       <?php echo esc_html(WOPSHOP_QUANTITY)?>
     </th> 
     <th width="16%">
       <?php echo esc_html(WOPSHOP_PRICE)?>
     </th>
     <th width="4%">
       <?php echo esc_html(WOPSHOP_DELETE)?>
     </th>
    </tr>
    </thead>
    <?php $i = 0;?>
    <?php foreach ($order_item as $item){ $i++; ?>
    <tr valign="top" id="order_item_row_<?php echo esc_attr($i); ?>">
     <td>
       <input type="text" name="product_name[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_name)?>" size="44" title="<?php print esc_attr(WOPSHOP_TITLE)?>" />
       <a class="modal modalInsertProduct" href="javascript:void(0)" attr-data-i = "<?php echo esc_attr($i); ?>" <?/*onclick="modalInsertProduct('<?php echo esc_attr($i); ?>'); return false;"*/?> ><?php print esc_html(WOPSHOP_LOAD)?></a>

       <br />
       <?php if ($this->config->admin_show_attributes){?>
       <textarea rows="2" cols="24" name="product_attributes[<?php echo esc_attr($i)?>]" title="<?php print esc_attr(WOPSHOP_ATTRIBUTES)?>"><?php print esc_html($item->product_attributes)?></textarea><br />
       <?php }?>
       <?php if ($this->config->admin_show_freeattributes){?>
       <textarea rows="2" cols="24" name="product_freeattributes[<?php echo esc_attr($i)?>]" title="<?php print esc_attr(WOPSHOP_FREE_ATTRIBUTES)?>"><?php print esc_html($item->product_freeattributes)?></textarea>
       <?php }?>
       <input type="hidden" name="product_id[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_id)?>" />
       <input type="hidden" name="delivery_times_id[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->delivery_times_id)?>" />
       <input type="hidden" name="thumb_image[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->thumb_image)?>" />
       <?php if ($this->config->admin_order_edit_more){?>
       <div>
       <?php echo esc_html(WOPSHOP_PRODUCT_WEIGHT)?> <input type="text" name="weight[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->weight)?>" />
       </div>
       <div>   
       <?php echo esc_html(WOPSHOP_VENDOR)?> ID <input type="text" name="vendor_id[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->vendor_id)?>" />
       </div>
       <?php }else{?>
       <input type="hidden" name="weight[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->weight)?>" />
       <input type="hidden" name="vendor_id[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->vendor_id)?>" />
       <?php }?>
     </td>
     <td>
       <input type="text" name="product_ean[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_ean)?>" />
     </td>
     <td>
       <input type="text" name="product_quantity[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_quantity)?>" onkeyup="updateOrderSubtotalValue();"/>   
     </td>
     <td>
       <div class="price"><?php print esc_html(WOPSHOP_PRICE)?>: <input type="text" name="product_item_price[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_item_price);?>" onkeyup="updateOrderSubtotalValue();"/><?php echo esc_attr(' '.$order->currency_code);?></div>
       <?php if (!$this->config->hide_tax){?>
       <div class="tax"><?php print esc_html(WOPSHOP_TAX)?>: <input type="text" name="product_tax[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->product_tax)?>" /> %</div>
       <?php }?>
       <input type="hidden" name="order_item_id[<?php echo esc_attr($i)?>]" value="<?php echo esc_attr($item->order_item_id)?>" />
     </td>
     <td><a href='#' onclick="jQuery('#order_item_row_<?php echo esc_attr($i)?>').remove();updateOrderSubtotalValue();return false;"><img src='<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>' border='0'></a></td>
    </tr>
    <?php }?>
    </table>
    <div style="text-align:right;padding-top:3px;">
        <input type="button" value="<?php print esc_html(WOPSHOP_ADD." ".WOPSHOP_PRODUCT)?>" onclick="addOrderItemRow();">
    </div>
    <script>var end_number_order_item=<?php echo esc_html($i)?>;</script>

    <br/>
    <table class="adminlist" width="100%">
    <tr class="bold">
     <td class="right">
        <?php echo esc_html(WOPSHOP_SUBTOTAL)?>
     </td>
     <td class="left">
       <input type="text" name="order_subtotal" value="<?php echo esc_attr($order->order_subtotal);?>" onkeyup="updateOrderTotalValue();"/> <?php echo esc_html($order->currency_code);?>
     </td>
    </tr>
    <?php print $this->_tmp_html_after_subtotal // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <tr class="bold">
     <td class="right">
       <?php echo esc_html(WOPSHOP_COUPON_DISCOUNT)?>
     </td>
     <td class="left">
       <input type="text" name="order_discount" value="<?php echo esc_attr($order->order_discount);?>" onkeyup="updateOrderTotalValue();"/> <?php echo esc_html($order->currency_code);?>
     </td>
    </tr>

    <?php if (!$this->config->without_shipping){?>
    <tr class="bold">
     <td class = "right">
        <?php echo esc_html(WOPSHOP_SHIPPING_PRICE)?>
     </td>
     <td class = "left">
        <input type="text" name="order_shipping" value="<?php echo esc_attr($order->order_shipping);?>" onkeyup="updateOrderTotalValue();"/> <?php echo esc_html($order->currency_code);?> 
     </td>
    </tr>
    <tr class="bold">
     <td class = "right">
        <?php echo WOPSHOP_PACKAGE_PRICE?>
     </td>
     <td class = "left">
        <input type="text" name="order_package" value="<?php echo esc_attr($order->order_package);?>" onkeyup="updateOrderTotalValue();"/> <?php echo esc_html($order->currency_code);?> 
     </td>
    </tr>
    <?php }?>
    <?php if (!$this->config->without_payment){?>
    <tr class="bold">
     <td class="right">
         <?php print esc_html(($order->payment_name) ? $order->payment_name : WOPSHOP_PAYMENT);?>
     </td>
     <td class="left">
       <input type="text" name="order_payment" value="<?php echo esc_attr($order->order_payment)?>" onkeyup="updateOrderTotalValue();"/> <?php echo esc_html($order->currency_code);?>
     </td>
    </tr>
    <?php }?>

    <?php $i = 0; if (!$this->config->hide_tax){?>
    <?php if(is_array($order->order_tax_list) and count($order->order_tax_list))
            foreach($order->order_tax_list as $percent=>$value){ $i++;?>
      <tr class="bold">
        <td class="right">
          <?php print esc_html(wopshopDisplayTotalCartTaxName($order->display_price));?>
          <input type="text" name="tax_percent[]" value="<?php print esc_attr($percent)?>" /> %
        </td>
        <td class="left">
          <input type="text" name="tax_value[]" value="<?php print esc_attr($value); ?>" /> <?php print esc_html($order->currency_code)?>
        </td>
      </tr>
    <?php }?>
      <tr class="bold" id='row_button_add_tax'>
        <td></td>
        <td class="left">
        <input type="button" value="<?php print esc_html(WOPSHOP_ADD." ".WOPSHOP_TAX)?>" onclick="addOrderTaxRow();">
        </td>
      </tr>
    <?php }?>

    <tr class="bold">
     <td class="right">
        <?php echo esc_html(WOPSHOP_TOTAL)?>
     </td>
     <td class="left" width="20%">
       <input type="text" name="order_total" value="<?php echo esc_attr($order->order_total);?>" /> <?php echo esc_html($order->currency_code);?>
     </td>
    </tr>
    <?php print $this->_tmp_html_after_total // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </table>

    <table width="100%" border="1" class="adminlist">
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
        <?php if ($this->config->show_delivery_time){?>
        <th width="33%">
        <?php echo esc_html(WOPSHOP_DELIVERY_TIME)?>
        </th>
        <?php } ?>
    </tr>
    </thead>
    <tr>
        <?php if (!$this->config->without_shipping){?>
        <td valign="top"><?php echo $this->shippings_select // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
        <?php } ?>
        <?php if (!$this->config->without_payment){?>
        <td valign="top">
            <div style="padding-bottom:4px;"><?php print $this->payments_select // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
            <div><textarea name="payment_params"><?php echo esc_html($order->payment_params)?></textarea></div>
        </td>
        <?php } ?>
        <?php if ($this->config->show_delivery_time){?>
        <td valign="top"><?php echo $this->delivery_time_select// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
        <?php } ?>
    </tr>
    </table>
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php wp_nonce_field('order_edit','name_of_nonce_field'); ?>
    <input type="hidden" name="js_nolang" value="1" />
    <input type="hidden" name="order_id" value="<?php echo esc_attr($this->order_id);?>" />
    <input type="hidden" name="client_id" value="<?php echo esc_attr($this->client_id)?>" />

    <div clas="submit">
        <p class="submit">
            <input class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            <a style="margin-left:50px;" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
        </p> 
    </div>
        <?php wp_nonce_field('order_edit','name_of_nonce_field'); ?>
    </form>    
</div>
<?php //wp_add_inline_script('jquery-ui-datepicker', "jQuery('#birthday').datepicker({dateFormat: '".$this->config->field_birthday_format."'});");


