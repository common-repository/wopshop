<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="wshop_plugin">
	<?php print wp_kses_post($this->checkout_navigator);?>
	<?php print wp_kses_post($this->small_cart);?>

    <div class="wshop checkout_shipping_block">
        <form id = "shipping_form" name = "shipping_form" action = "<?php print esc_url($this->action)?>" method = "post" onsubmit = "return validateShippingMethods()" autocomplete="off" enctype="multipart/form-data">
            <?php print $this->_tmp_ext_html_shipping_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <div id = "table_shippings">
                <?php foreach($this->shipping_methods as $shipping){?>
                    <div class="name">
                        <input type = "radio" name = "sh_pr_method_id" id = "shipping_method_<?php print esc_attr($shipping->sh_pr_method_id)?>" value="<?php print esc_attr($shipping->sh_pr_method_id )?>" <?php if ($shipping->sh_pr_method_id==$this->active_shipping){ ?>checked = "checked"<?php } ?> onclick="showShippingForm(<?php print esc_js($shipping->shipping_id)?>)" />
                        <label for = "shipping_method_<?php print esc_attr($shipping->sh_pr_method_id) ?>"><?php
                        if ($shipping->image){
                            ?><span class="shipping_image"><img src="<?php print esc_url($shipping->image)?>" alt="<?php print esc_attr($shipping->name)?>" /></span><?php
                        }
                        ?><b><?php print esc_html($shipping->name)?></b>
                        <span class="shipping_price">(<?php print esc_html(wopshopFormatprice($shipping->calculeprice)); ?>)</span>
                        </label>
                        
                        <?php if ($this->config->show_list_price_shipping_weight && count($shipping->shipping_price)){ ?>
                            <table class="shipping_weight_to_price">
                                <?php foreach($shipping->shipping_price as $price){?>
                                    <tr>
                                        <td class="weight">
                                            <?php if ($price->shipping_weight_to!=0){?>
                                                <?php print esc_html(wopshop_formatweight($price->shipping_weight_from));?> - <?php print esc_html(wopshop_formatweight($price->shipping_weight_to));?>
                                            <?php }else{ ?>
                                                <?php print esc_html(WOPSHOP_FROM)." ".esc_html(wopshop_formatweight($price->shipping_weight_from));?>
                                            <?php } ?>
                                        </td>
                                        <td class="price">
                                            <?php print esc_html(wopshopFormatprice($price->shipping_price)); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                        
                        <div class="shipping_descr"><?php print wp_kses_post($shipping->description)?></div>
                        
                        <div id="shipping_form_<?php print esc_attr($shipping->shipping_id)?>" class="shipping_form <?php if ($shipping->sh_pr_method_id==$this->active_shipping) print 'shipping_form_active'?>">
                            <?php print $shipping->form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                        
                        <?php if ($shipping->delivery){?>
                            <div class="shipping_delivery"><?php print esc_html(WOPSHOP_DELIVERY_TIME).": ".esc_html($shipping->delivery)?></div>
                        <?php }?>
                        
                        <?php if ($shipping->delivery_date_f){?>
                            <div class="shipping_delivery_date"><?php print esc_html(WOPSHOP_DELIVERY_DATE).": ".esc_html($shipping->delivery_date_f)?></div>
                        <?php }?>      
                    </div>
                <?php } ?>
            </div>

            <?php print $this->_tmp_ext_html_shipping_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <input type = "submit" class = "btn btn-primary button" value = "<?php print esc_attr(WOPSHOP_NEXT) ?>" />
        </form>
    </div>
</div>