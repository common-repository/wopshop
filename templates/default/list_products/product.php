<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php print $product->_tmp_var_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
<div class="product productitem_<?php print esc_attr($product->product_id)?>">
    
    <div class="name">
        <a href="<?php print esc_url($product->product_link)?>">
            <?php print esc_html($product->name);?>
        </a>
        <?php if ($this->config->product_list_show_product_code){?>
            <span class="wshop_code_prod">(<?php print esc_html(WOPSHOP_EAN)?>: <span><?php print esc_html($product->product_ean);?></span>)</span>
        <?php }?>
    </div>
    
    <div class = "image">
        <?php if ($product->image){?>
            <div class="image_block">
			    <?php print $product->_tmp_var_image_block; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <?php if ($product->label_id){?>
                    <div class="product_label">
                        <?php if ($product->_label_image){?>
                            <img src="<?php print esc_url($product->_label_image)?>" alt="<?php print esc_attr($product->_label_name)?>" />
                        <?php }else{?>
                            <span class="label_name"><?php print esc_html($product->_label_name);?></span>
                        <?php }?>
                    </div>
                <?php }?>
                <a href="<?php print esc_url($product->product_link)?>">
                    <img class="wshop_img" src="<?php print esc_url($product->image)?>" alt="<?php print esc_attr($product->name);?>" title="<?php print esc_attr($product->name);?>"  />
                </a>
            </div>
        <?php }?>

        <?php if ($this->allow_review){?>
            <div class="review_mark">
                <?php print wp_kses_post(wopshopShowMarkStar($product->average_rating));?>
            </div>
            <div class="count_commentar">
                <?php print esc_html(sprintf(WOPSHOP_X_COMENTAR, $product->reviews_count));?>
            </div>
        <?php }?>
        
        <?php print $product->_tmp_var_bottom_foto; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </div>
    
    <div class = "oiproduct">
        
        <?php if ($product->product_quantity <=0 && !$this->config->hide_text_product_not_available){?>
            <div class="not_available"><?php print esc_html(WOPSHOP_PRODUCT_NOT_AVAILABLE)?></div>
        <?php }?>
        
        <?php if ($product->product_old_price > 0){?>
            <div class="old_price">
                <?php if ($this->config->product_list_show_price_description) print esc_html(WOPSHOP_OLD_PRICE.": ")?>
                <span><?php print esc_html(wopshopFormatprice($product->product_old_price))?></span>
            </div>
        <?php }?>
        
		<?php print $product->_tmp_var_bottom_old_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if ($product->product_price_default > 0 && $this->config->product_list_show_price_default){?>
            <div class="default_price">
                <?php print esc_html(WOPSHOP_DEFAULT_PRICE.": ")?>
                <span><?php print esc_html(wopshopFormatprice($product->product_price_default))?></span>
            </div>
        <?php }?>
        
        <?php if ($product->_display_price){?>
            <div class = "wshop_price">
                <?php if ($this->config->product_list_show_price_description) print esc_html(WOPSHOP_PRICE.": ")?>
                <?php if ($product->show_price_from) print esc_html(WOPSHOP_FROM." ")?>
                <span><?php print esc_html(wopshopFormatprice($product->product_price));?>
                    <?php print $product->_tmp_var_price_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></span>
            </div>
        <?php }?>
        
        <?php print $product->_tmp_var_bottom_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <div class="price_extra_info">
            <?php if ($this->config->show_tax_in_product && $product->tax > 0){?>
                <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($product->tax));?></span>
            <?php }?>
            
            <?php if ($this->config->show_plus_shipping_in_product){?>
                <span class="plusshippinginfo"><?php print wp_kses_post(sprintf(WOPSHOP_PLUS_SHIPPING, $this->shippinginfo));?></span>
            <?php }?>
        </div>
        
        <?php if ($product->basic_price_info['price_show']){?>
            <div class="base_price">
                <?php print esc_html(WOPSHOP_BASIC_PRICE)?>:
                <?php if ($product->show_price_from && !$this->config->hide_from_basic_price) print esc_html(WOPSHOP_FROM)?>
                <span><?php print esc_html(wopshopFormatprice($product->basic_price_info['basic_price']))?> / <?php print esc_html($product->basic_price_info['name']);?></span>
            </div>
        <?php }?>
        
        <?php if ($product->manufacturer->name){?>
            <div class="manufacturer_name">
                <?php print esc_html(WOPSHOP_MANUFACTURER)?>:
                <span><?php print esc_html($product->manufacturer->name)?></span>
            </div>
        <?php }?>
        
        <?php if ($this->config->product_list_show_weight && $product->product_weight > 0){?>
            <div class="productweight">
                <?php print esc_html(WOPSHOP_WEIGHT)?>:
                <span><?php print esc_html(wopshop_formatweight($product->product_weight))?></span>
            </div>
        <?php }?>
        
        <?php if ($product->delivery_time != ''){?>
            <div class="deliverytime">
                <?php print esc_html(WOPSHOP_DELIVERY_TIME)?>:
                <span><?php print esc_html($product->delivery_time)?></span>
            </div>
        <?php }?>
        
        <?php if (is_array($product->extra_field)){?>
            <div class="extra_fields">
                <?php foreach($product->extra_field as $extra_field){?>
                    <div>
                        <span class="label-name"><?php print esc_html($extra_field['name']);?>:</span>
                        <span class="data"><?php print esc_html($extra_field['value']);?></span>
                    </div>
                <?php }?>
            </div>            
        <?php }?>
        
        <?php if ($product->vendor){?>
            <div class="vendorinfo">
                <?php print esc_html(WOPSHOP_VENDOR)?>:
                <a href="<?php print esc_url($product->vendor->products)?>"><?php print esc_html($product->vendor->shop_name)?></a>
            </div>
        <?php }?>
        
        <?php if ($this->config->product_list_show_qty_stock){?>
            <div class="qty_in_stock">
                <?php print esc_html(WOPSHOP_QTY_IN_STOCK)?>:
                <span><?php print esc_html(wopshopSprintQtyInStock($product->qty_in_stock))?></span>
            </div>
        <?php }?>
        
        <div class="description">
            <?php print wp_kses_post($product->short_description)?>
        </div>
        
        <?php print $product->_tmp_var_top_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <div class="buttons">
            <?php if ($product->buy_link){?>
               <a class="btn btn-success button_buy" href="<?php print esc_url($product->buy_link)?>">
                    <?php print esc_html(WOPSHOP_BUY)?>
                </a>
            <?php }?>
            <a class="btn button_detail" href="<?php print esc_url($product->product_link)?>">
                <?php print esc_html(WOPSHOP_DETAIL)?>
            </a>
            <?php print $product->_tmp_var_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </div>
        
        <?php print $product->_tmp_var_bottom_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </div>
</div>
<?php print $product->_tmp_var_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>