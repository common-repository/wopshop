<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop" id="wshop_plugin">

<table class = "wshop cart cartwishlist" id="wshop_plugin">
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
        <th class="remove_to_cart" width = "10%">
            <?php print esc_html(WOPSHOP_REMOVE_TO_CART) ?>
        </th>
        <th class="remove" width = "10%">
            <?php print esc_html(WOPSHOP_REMOVE) ?>
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
                        <?php print esc_html(WOPSHOP_BASIC_PRICE)?>: <span><?php print esc_html(wopshopSprintBasicPrice($prod));?></span>
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
        <td class="remove_to_cart">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_REMOVE_TO_CART); ?>
            </div>
            <div class="data">
                <a class="button-img" href = "<?php print esc_url($prod['remove_to_cart'])?>" >
                    <img src = "<?php print esc_url($this->image_path.'/assets/images/reload.png')?>" alt = "<?php print esc_attr(WOPSHOP_REMOVE_TO_CART)?>" title = "<?php print esc_attr(WOPSHOP_REMOVE_TO_CART)?>" />
                </a>
                <a class="btn btn-primary" href = "<?php print esc_url($prod['remove_to_cart'])?>" >
                    <?php print esc_html(WOPSHOP_REMOVE_TO_CART)?>
                </a>
            </div>
        </td>
        <td class="remove">
            <div class="mobile-cart">
                <?php print esc_html(WOPSHOP_REMOVE); ?>
            </div>
            <div class="data">
                <a class="button-img" href="<?php print esc_url($prod['href_delete'])?>" onclick="return confirm('<?php print esc_js(WOPSHOP_CONFIRM_REMOVE)?>')">
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

<?php print $this->_tmp_html_before_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

<div class = "wshop wishlish_buttons">
    <div id = "checkout">
    
        <div class = "pull-left td_1">
            <a href = "<?php print esc_url($this->href_shop)?>" class = "btn">
                <?php print esc_html(WOPSHOP_BACK_TO_SHOP) ?>
            </a>
        </div>
        
        <div class = "pull-right td_2">
            <a href = "<?php print esc_url($this->href_checkout)?>" class = "btn">
                <?php print esc_html(WOPSHOP_GO_TO_CART) ?>
            </a>
        </div>
        
        <div class = "clearfix"></div>
    </div>
</div>

<?php print $this->_tmp_html_after_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

</div>