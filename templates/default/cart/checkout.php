<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop">
<table class = "wshop cart cartcheckout">
    <tr>
        <th class="wshop_img_description_center" width = "20%">
            <?php print esc_html(WOPSHOP_IMAGE)?>
        </th>
        <th class="product_name">
            <?php print esc_html(WOPSHOP_ITEM)?>
        </th>    
        <th class="single_price" width = "15%">
            <?php print esc_html(WOPSHOP_SINGLEPRICE) ?>
        </th>
        <th class="quantity" width = "15%">
            <?php print esc_html(WOPSHOP_NUMBER) ?>
        </th>
        <th class="total_price" width = "15%">
            <?php print esc_html(WOPSHOP_PRICE_TOTAL) ?>
        </th>
    </tr>
    <?php
    $i=1;
    foreach($this->products as $key_id=>$prod){
    ?> 
    <tr class = "wshop_prod_cart <?php if ($i%2==0) print "even"; else print "odd"?>">
        <td class = "wshop_img_description_center">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_IMAGE); ?>
            </div>
            <div class="data">
                <a href = "<?php print esc_url($prod['href'])?>">
                    <img src = "<?php print esc_url($this->image_product_path.'/'.($prod['thumb_image'] ? $prod['thumb_image'] : $this->no_image))?>" alt = "<?php print esc_attr($prod['product_name']);?>" class = "wshop_img" />
                </a>
            </div>
        </td>
        <td class="product_name">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_ITEM); ?>
            </div>
            <div class="data">
                <a href="<?php print esc_url($prod['href'])?>">
                    <?php print esc_html($prod['product_name'])?>
                </a>
                <?php if ($this->config->show_product_code_in_cart){?>
                    <span class="wshop_code_prod">(<?php print esc_html($prod['ean'])?>)</span>
                <?php }?>
                <?php if ($prod['manufacturer']!=''){?>
                    <div class="manufacturer">
                        <?php print esc_html(WOPSHOP_MANUFACTURER)?>:
                        <span><?php print esc_html($prod['manufacturer'])?></span>
                    </div>
                <?php }?>
                <?php print wopshopSprintAtributeInCart($prod['attributes_value']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print wopshopSprintFreeAtributeInCart($prod['free_attributes_value']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print wopshopSprintFreeExtraFiledsInCart($prod['extra_fields']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print $prod['_ext_attribute_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php if ($this->config->show_delivery_time_step5 && $this->step==5 && $prod['delivery_times_id']){?>
                    <div class="deliverytime">
                        <?php print esc_html(WOPSHOP_DELIVERY_TIME)?>:
                        <?php print wp_kses_post($this->deliverytimes[$prod['delivery_times_id']]);?>
                    </div>
                <?php }?>
            </div>
        </td>    
        <td class="single_price">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_SINGLEPRICE); ?>
            </div>
            <div class="data">
                <?php print esc_html(wopshopFormatprice($prod['price']))?>
                <?php print $prod['_ext_price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
                    <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod['tax']));?></span>
                <?php }?>
                <?php if ($this->config->cart_basic_price_show && $prod['basicprice']>0){?>
                    <div class="basic_price">
                        <?php print esc_html(WOPSHOP_BASIC_PRICE)?>:
                        <span><?php print esc_html(wopshopSprintBasicPrice($prod));?></span>
                    </div>
                <?php }?>
            </div>
        </td>
        <td class="quantity">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_NUMBER); ?>
            </div>
            <div class="data">
                <?php print esc_html($prod['quantity'])?><?php print esc_html($prod['_qty_unit']);?>
            </div>
        </td>
        <td class="total_price">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_PRICE_TOTAL); ?>
            </div>
            <div class="data">
                <?php print esc_html(wopshopFormatprice($prod['price']*$prod['quantity']));?>
                <?php print $prod['_ext_price_total_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
                    <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod['tax']));?></span>
                <?php }?>
            </div>
        </td>
    </tr>
    <?php 
    $i++;
    }
    ?>
</table>
  
<?php if ($this->config->show_weight_order){?>  
    <div class="weightorder">
        <?php print esc_html(WOPSHOP_WEIGHT_PRODUCTS)?>: <span><?php print esc_html(wopshop_formatweight($this->weight));?></span>
    </div>
<?php }?>
  
<div class="cartdescr"><?php print wp_kses_post($this->checkoutcartdescr);?></div>
  
<table class = "wshop wshop_subtotal">
    <?php if (!$this->hide_subtotal){?>
        <tr class="subtotal">    
            <td class = "name">
                <?php print esc_html(WOPSHOP_SUBTOTAL) ?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($this->summ));?><?php print $this->_tmp_ext_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
    <?php } ?>

    <?php print $this->_tmp_html_after_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

    <?php if ($this->discount > 0){ ?>
        <tr class="discount">
            <td class = "name">
                <?php print esc_html(WOPSHOP_RABATT_VALUE) ?><?php print $this->_tmp_ext_discount_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
            <td class = "value">
                <?php esc_html(print wopshopFormatprice(-$this->discount));?><?php print $this->_tmp_ext_discount; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
    <?php } ?>

    <?php if (isset($this->summ_delivery)){?>
        <tr class="shipping">
            <td class = "name">
                <?php print esc_html(WOPSHOP_SHIPPING_PRICE);?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($this->summ_delivery));?><?php print $this->_tmp_ext_shipping; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
    <?php } ?>

    <?php if (isset($this->summ_package)){?>
        <tr class="package">
            <td class = "name">
                <?php print esc_html(WOPSHOP_PACKAGE_PRICE);?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($this->summ_package));?><?php print $this->_tmp_ext_shipping_package; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
    <?php } ?>

    <?php if ($this->summ_payment != 0){ ?>
        <tr class="payment">
            <td class = "name">
                <?php print esc_html($this->payment_name);?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($this->summ_payment));?><?php print $this->_tmp_ext_payment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
    <?php } ?>

    <?php if (!$this->config->hide_tax){ ?>
        <?php foreach($this->tax_list as $percent=>$value){?>
            <tr class="tax">
                <td class = "name">
                    <?php print esc_html(wopshopDisplayTotalCartTaxName());?>
                    <?php if ($this->show_percent_tax) print esc_html(wopshop_formattax($percent))."%"?>
                </td>
                <td class = "value">
                    <?php print esc_html(wopshopFormatprice($value));?><?php print $this->_tmp_ext_tax[$percent]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>

    <tr class="total">
        <td class = "name">
            <?php print $this->text_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </td>
        <td class = "value">
            <?php print esc_html(wopshopFormatprice($this->fullsumm))?><?php print $this->_tmp_ext_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </td>
    </tr>

    <?php print $this->_tmp_html_after_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

    <?php if ($this->free_discount > 0){?>  
        <tr class="free_discount">
            <td colspan="2" align="right">    
                <span class="free_discount">
                    <?php print esc_html(WOPSHOP_FREE_DISCOUNT);?>:
                    <span><?php print esc_html(wopshopFormatprice($this->free_discount)); ?></span>
                </span>
            </td>
        </tr>
    <?php }?>  
</table>

<?php print $this->_tmp_html_after_checkout_cart; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

</div>