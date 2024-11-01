<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$countprod = count($this->products);
?>
<div class="wshop" id="wshop_plugin">
    <form action="<?php print esc_url(wopshopSEFLink('controller=cart&task=refresh'))?>" method="post" name="updateCart">

<?php print $this->_tmp_ext_html_cart_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<?php if ($countprod > 0) : ?>
    <table class="wshop cart">
    <tr>
        <th class="wshop_img_description_center" width="20%">
            <?php print esc_html(WOPSHOP_IMAGE) ?>
        </th>
        <th class="product_name">
            <?php print esc_html(WOPSHOP_ITEM) ?>
        </th>    
        <th class="single_price" width="15%">
            <?php print esc_html(WOPSHOP_SINGLEPRICE) ?>
        </th>
        <th class="quantity" width="15%">
            <?php print esc_html(WOPSHOP_NUMBER) ?>
        </th>
        <th class="total_price" width="15%">
            <?php print esc_html(WOPSHOP_PRICE_TOTAL) ?>
        </th>
        <th class="remove" width="10%">
            <?php print esc_html(WOPSHOP_REMOVE) ?>
        </th>
    </tr>
    <?php
    $i = 1;
    foreach ($this->products as $key_id => $prod){
    ?> 
    <tr class="wshop_prod_cart <?php if ($i % 2 == 0) print "even"; else print "odd"?>">
        <td class="wshop_img_description_center">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_IMAGE); ?>
            </div>
            <div class="data">
                <a href="<?php print esc_url($prod['href'])?>">
                    <?php
                        $prod_image = $this->image_product_path . '/';
                        $prod_image .= $prod['thumb_image'] ? $prod['thumb_image'] : $this->no_image;
                    ?>
                    <img src="<?php print esc_url($prod_image)?>" alt="<?php echo esc_attr($prod['product_name']); ?>" class="wshop_img" />
                </a>
            </div>
        </td>
        <td class="product_name">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_ITEM); ?>
            </div>
            <div class="data">
                <a href="<?php print esc_url($prod['href'])?>">
                    <?php print esc_html($prod['product_name']); ?>
                </a>
                <?php if ($this->config->show_product_code_in_cart) { ?>
                    <span class="wshop_code_prod">(<?php print esc_html($prod['ean']) ?>)</span>
                <?php } ?>
                <?php if ($prod['manufacturer'] != '') { ?>
                    <div class="manufacturer"><?php print esc_html(WOPSHOP_MANUFACTURER) ?>: <span><?php print esc_html($prod['manufacturer']) ?></span></div>
                <?php } ?>
                <?php print wopshopSprintAtributeInCart($prod['attributes_value']);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print wopshopSprintFreeAtributeInCart($prod['free_attributes_value']);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print wopshopSprintFreeExtraFiledsInCart($prod['extra_fields']);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php print $prod['_ext_attribute_html'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </div>
        </td> 
        <td class="single_price">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_SINGLEPRICE); ?>
            </div>
            <div class="data">
                <?php print esc_html(wopshopFormatprice($prod['price'])); ?>
                <?php print $prod['_ext_price_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php if ($this->config->show_tax_product_in_cart && $prod['tax'] > 0) { ?>
                    <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod['tax'])); ?></span>
                <?php } ?>
                <?php if ($this->config->cart_basic_price_show && $prod['basicprice'] > 0) { ?>
                    <div class="basic_price">
                        <?php print esc_html(WOPSHOP_BASIC_PRICE) ?>:
                        <span><?php print esc_html(wopshopSprintBasicPrice($prod)); ?></span>
                    </div>
                <?php } ?>
            </div>
        </td>
        <td class="quantity">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_NUMBER); ?>
            </div>
            <div class="data">
                <input type = "text" name = "quantity[<?php print esc_attr($key_id) ?>]" value = "<?php print esc_attr($prod['quantity']) ?>" class = "inputbox" />
                <?php print esc_html($prod['_qty_unit']); ?>
                <span class = "cart_reload">
                    <img src="<?php print esc_url($this->image_path.'/assets/images/reload.png')?>" title="<?php print esc_attr(WOPSHOP_UPDATE_CART) ?>" alt = "<?php print esc_html(WOPSHOP_UPDATE_CART) ?>" onclick="document.updateCart.submit();" />
                </span>
            </div>
        </td>
        <td class="total_price">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_PRICE_TOTAL); ?>
            </div>
            <div class="data">
                <?php print esc_html(wopshopFormatprice($prod['price'] * $prod['quantity'])); ?>
                <?php print $prod['_ext_price_total_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php if ($this->config->show_tax_product_in_cart && $prod['tax'] > 0) { ?>
                    <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($prod['tax'])); ?></span>
                <?php } ?>
            </div>
        </td>
        <td class="remove">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_REMOVE); ?>
            </div>
            <div class="data">
                <a class="button-img" href="<?php print esc_url($prod['href_delete'])?>" onclick="return confirm('<?php print esc_html(WOPSHOP_CONFIRM_REMOVE)?>')">
                    <img src = "<?php print esc_url($this->image_path.'/assets/images/remove.png')?>" alt = "<?php print esc_attr(WOPSHOP_DELETE)?>" title = "<?php print esc_attr(WOPSHOP_DELETE)?>" />
                </a>
            </div>
        </td>
    </tr>
    <?php
    $i++;
    }
    ?>
    </table>

    <?php if ($this->config->show_weight_order) : ?>
        <div class = "weightorder">
            <?php print esc_html(WOPSHOP_WEIGHT_PRODUCTS)?>: <span><?php print esc_html(wopshop_formatweight($this->weight));?></span>
        </div>
    <?php endif; ?>
      
    <?php if ($this->config->summ_null_shipping > 0) : ?>
        <div class = "shippingfree">
            <?php esc_html(printf(WOPSHOP_FROM_PRICE_SHIPPING_FREE, wopshopFormatprice($this->config->summ_null_shipping, null, 1)));?>
        </div>
    <?php endif; ?>
      
    <div class = "cartdescr"><?php print wp_kses_post($this->cartdescr); ?></div>

    <table class="wshop wshop_subtotal">
        <?php if (!$this->hide_subtotal){?>
            <tr class="subtotal">
                <td class="name">
                    <?php print esc_html(WOPSHOP_SUBTOTAL) ?>
                </td>
                <td class="value">
                    <?php print esc_html(wopshopFormatprice($this->summ));?><?php print $this->_tmp_ext_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        
        <?php print $this->_tmp_html_after_subtotal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if ($this->discount > 0){ ?>
            <tr class="discount">
                <td class="name">
                    <?php print esc_html(WOPSHOP_RABATT_VALUE) ?>
                </td>
                <td class="value">
                    <?php print esc_html(wopshopFormatprice(-$this->discount));?><?php print $this->_tmp_ext_discount; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </td>
            </tr>
        <?php } ?>
        <?php if (!$this->config->hide_tax){?>
            <?php foreach($this->tax_list as $percent=>$value){ ?>
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
                <?php print esc_html(WOPSHOP_PRICE_TOTAL) ?>
            </td>
            <td class = "value">
                <?php print esc_html(wopshopFormatprice($this->fullsumm))?><?php print $this->_tmp_ext_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </td>
        </tr>
        
        <?php print $this->_tmp_html_after_total; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if ($this->config->show_plus_shipping_in_product){?>  
            <tr class="plusshipping">
                <td colspan="2" align="right">    
                    <span class="plusshippinginfo"><?php print wp_kses_post(sprintf(WOPSHOP_PLUS_SHIPPING, $this->shippinginfo));?></span>
                </td>
            </tr>
        <?php }?>
        
        <?php if ($this->free_discount > 0){?>  
            <tr class="free_discount">
                <td colspan="2" align="right">    
                    <span class="free_discount"><?php print esc_html(WOPSHOP_FREE_DISCOUNT);?>: <?php print esc_html(wopshopFormatprice($this->free_discount)); ?></span>
                </td>
            </tr>
        <?php }?>
        
    </table>
<?php else : ?>
    <div class="cart_empty_text"><?php print esc_html(WOPSHOP_CART_EMPTY)?></div>
<?php endif; ?>

<?php print $this->_tmp_html_before_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
<div class = "wshop cart_buttons">
    <div id = "checkout">
        <div class = "pull-left td_1">
            <a href = "<?php print esc_url($this->href_shop)?>" class = "btn">
                <?php print esc_html(WOPSHOP_BACK_TO_SHOP) ?>
            </a>
        </div>
        <div class = "pull-right td_2">
        <?php if ($countprod>0) : ?>
            <a href = "<?php print esc_url($this->href_checkout)?>" class = "btn">
                <?php print esc_html(WOPSHOP_CHECKOUT) ?>
            </a>
        <?php endif; ?>
        </div>
        <div class = "clearfix"></div>
    </div>
</div>

<?php print $this->_tmp_html_after_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

</form>

<?php print $this->_tmp_ext_html_before_discount; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

<?php if ($this->use_rabatt && $countprod>0) : ?>
    <div class="cart_block_discount">
        <form name="rabatt" method="post" action="<?php print esc_url(wopshopSEFLink('controller=cart&task=discountsave'))?>">
            <div class = "row-fluid wshop">
                <div class = "span12">
                    <div class="name"><?php print esc_html(WOPSHOP_RABATT) ?></div>
                    <input type = "text" class = "inputbox" name = "rabatt" value = "" />
                    <input type = "submit" class = "button btn" value = "<?php print esc_html(WOPSHOP_RABATT_ACTIVE) ?>" />
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>
            
</div>