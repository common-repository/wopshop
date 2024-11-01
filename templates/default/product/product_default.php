<?php
$product = $this->product;
include(dirname(__FILE__)."/load.js.php");
?>
<div class="wshop productfull" id="wshop_plugin">
    <form name="product" method="post" action="<?php print esc_url($this->action)?>" enctype="multipart/form-data" autocomplete="off">
    
        <h1><?php print esc_html($this->product->name)?><?php if ($this->config->show_product_code){?> <span class="wshop_code_prod">(<?php print esc_html(WOPSHOP_EAN)?>: <span id="product_code"><?php print esc_html($this->product->getEan());?></span>)</span><?php }?></h1>
        
        <?php print $this->_tmp_product_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscape?>
        

        <?php include(dirname(__FILE__)."/ratingandhits.php");?>

        <div class="row-fluid wshop">
            <div class="span4 image_middle">
            
                <?php print $this->_tmp_product_html_before_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscape?>
                
                <?php if ($product->label_id){?>
                    <div class="product_label">
                        <?php if ($product->_label_image){?>
                            <img src="<?php print esc_url($product->_label_image)?>" alt="<?php print esc_attr($product->_label_name)?>" />
                        <?php }else{?>
                            <span class="label_name"><?php print esc_html($product->_label_name);?></span>
                        <?php }?>
                    </div>
                <?php }?>
                
                <?php if (count($this->videos)){?>
                    <?php foreach($this->videos as $k=>$video){?>
                        <?php if ($video->video_code){ ?>
                            <div style="display:none" class="video_full" id="hide_video_<?php print esc_attr($k)?>">
                                <?php echo $video->video_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                            </div>
                        <?php } else { ?>
                            <a style="display:none" class="video_full" id="hide_video_<?php print esc_attr($k)?>" href=""></a>
                        <?php } ?>
                    <?php } ?>
                <?php }?>

                <span id='list_product_image_middle'>
                    <?php print $this->_tmp_product_html_body_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                    
                    <?php if(!count($this->images)){?>
                        <img id = "main_image" src = "<?php print esc_url($this->image_product_path.'/'.$this->noimage)?>" alt = "<?php print esc_attr($this->product->name)?>" />
                    <?php }?>
                    
                    <?php foreach($this->images as $k=>$image){?>
                        <a class="lightbox" id="main_image_full_<?php print esc_attr($image->image_id)?>" href="<?php print esc_url($this->image_product_path.'/'.$image->image_full)?>" <?php if ($k!=0){?>style="display:none"<?php }?> title="<?php print esc_attr($image->_title)?>">
                            <img id = "main_image_<?php print esc_attr($image->image_id)?>" src = "<?php print esc_url($this->image_product_path.'/'.$image->image_name)?>" alt="<?php print esc_attr($image->_title)?>" title="<?php print esc_attr($image->_title)?>" />
                            <div class="text_zoom">
                                <img src="<?php print esc_url($this->path_to_image.'search.png')?>" alt="zoom" />
                                <?php print esc_html(WOPSHOP_ZOOM_IMAGE)?>
                            </div>
                        </a>
                    <?php }?>
                </span>
                
                <?php print $this->_tmp_product_html_after_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

                <?php if ($this->config->product_show_manufacturer_logo && $this->product->manufacturer_info->manufacturer_logo!=""){?>
                <div class="manufacturer_logo">
                    <a href="<?php print esc_url(wopshopSEFLink('controller=manufacturer&task=view&manufacturer_id='.$this->product->product_manufacturer_id, 2))?>">
                        <img src="<?php print esc_url($this->config->image_manufs_live_path."/".$this->product->manufacturer_info->manufacturer_logo)?>" alt="<?php print esc_attr($this->product->manufacturer_info->name);?>" title="<?php print esc_attr($this->product->manufacturer_info->name);?>" border="0" />
                    </a>
                </div>
                <?php }?>
            </div>
            
            <div class = "span8 wshop_img_description">
                <?php print $this->_tmp_product_html_before_image_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                
                <span id='list_product_image_thumb'>
                    <?php if ( (count($this->images)>1) || (count($this->videos) && count($this->images)) ) {?>
                        <?php foreach($this->images as $k=>$image){?>
                            <img class="wshop_img_thumb" src="<?php print esc_url($this->image_product_path.'/'.$image->image_thumb)?>" alt="<?php print esc_attr($image->_title)?>" title="<?php print esc_attr($image->_title)?>" onclick="showImage(<?php print esc_js($image->image_id)?>)" />
                        <?php }?>
                    <?php }?>
                </span>
                
                <?php print $this->_tmp_product_html_after_image_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                
                <?php if (count($this->videos)){?>
                    <?php foreach($this->videos as $k=>$video){?>
                        <?php if ($video->video_code) { ?>
                            <a href="#" id="video_<?php print esc_attr($k)?>" onclick="showVideoCode(this.id);return false;"><img class="wshop_video_thumb" src="<?php print esc_url($this->video_image_preview_path."/".($video->video_preview ? $video->video_preview : 'video.gif'))?>" alt="video" /></a>
                        <?php } else { ?>
                            <a href="<?php print esc_url($this->video_product_path.'/'.$video->video_name)?>" id="video_<?php print esc_attr($k)?>" onclick="showVideo(this.id, '<?php print esc_js($this->config->video_product_width);?>', '<?php print esc_js($this->config->video_product_height);?>'); return false;"><img class="wshop_video_thumb" src="<?php print esc_url($this->video_image_preview_path."/".($video->video_preview ? $video->video_preview : 'video.gif'))?>" alt="video" /></a>
                        <?php } ?>
                    <?php } ?>
                <?php }?>
                
                <?php print $this->_tmp_product_html_after_video; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </div>
        </div>

        <div class="wshop_prod_description">
            <?php print wp_kses_post(nl2br($this->product->description)); ?>
        </div>        

        <?php if ($this->product->product_url!=""){?>
            <div class="prod_url">
                <a target="_blank" href="<?php print esc_url($this->product->product_url)?>"><?php print esc_html(WOPSHOP_READ_MORE)?></a>
            </div>
        <?php }?>

        <?php if ($this->config->product_show_manufacturer && $this->product->manufacturer_info->name!=""){?>
            <div class="manufacturer_name">
                <?php print esc_html(WOPSHOP_MANUFACTURER)?>: <span><?php print esc_html($this->product->manufacturer_info->name)?></span>
            </div>
        <?php }?>
        
        <?php print $this->_tmp_product_html_before_atributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if (count($this->attributes)) : ?>
            <div class="wshop_prod_attributes wshop">
                <?php foreach($this->attributes as $attribut) : ?>
                    <?php if ($attribut->grshow){?>
                        <div>
                            <span class="attributgr_name"><?php print esc_html($attribut->groupname)?></span>
                        </div>
                    <?php }?>               
                    <div class = "row-fluid">
                        <div class="span2 attributes_title">
                            <span class="attributes_name"><?php print wp_kses_post($attribut->attr_name)?>:</span>
                            <span class="attributes_description"><?php print wp_kses_post($attribut->attr_description);?></span>
                        </div>
                        <div class = "span10">
                            <span id='block_attr_sel_<?php print esc_attr($attribut->attr_id)?>'>
                                <?php print $attribut->selects; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php print $this->_tmp_product_html_after_atributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if (!empty($this->product->freeattributes) && count($this->product->freeattributes)){?>
            <div class="prod_free_attribs wshop">
                <?php foreach($this->product->freeattributes as $freeattribut){?>
                    <div class = "row-fluid">
                        <div class="span2 name">
                            <span class="freeattribut_name"><?php print esc_html($freeattribut->name);?></span>
                            <?php if ($freeattribut->required){?><span>*</span><?php }?>
                            <span class="freeattribut_description"><?php print wp_kses_post($freeattribut->description);?></span>
                        </div>
                        <div class="span10 field">
                            <?php print $freeattribut->input_field; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                    </div>
                <?php }?>
                <?php if ($this->product->freeattribrequire) {?>
                    <div class="requiredtext">* <?php print esc_html(WOPSHOP_REQUIRED)?></div>
                <?php }?>
            </div>
        <?php }?>
        
        <?php print $this->_tmp_product_html_after_freeatributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if ($this->product->product_is_add_price){?>
            <div class="price_prod_qty_list_head"><?php print esc_html(WOPSHOP_PRICE_FOR_QTY)?></div>
            <table class="price_prod_qty_list">
                <?php foreach($this->product->product_add_prices as $k=>$add_price){?>
                    <tr>
                        <td class="qty_from" <?php if ($add_price->product_quantity_finish==0){?>colspan="3"<?php } ?>>
                            <?php if ($add_price->product_quantity_finish==0) print esc_html(WOPSHOP_FROM)?>
                            <?php print esc_html($add_price->product_quantity_start)?>
                            <?php print esc_html($this->product->product_add_price_unit)?>
                        </td>
                        
                        <?php if ($add_price->product_quantity_finish > 0){?>
                            <td class="qty_line"> - </td>
                        <?php } ?>
                        
                        <?php if ($add_price->product_quantity_finish > 0){?>
                            <td class="qty_to">
                                <?php print esc_html($add_price->product_quantity_finish)?> <?php print esc_html($this->product->product_add_price_unit)?>
                            </td>
                        <?php } ?>
                        
                        <td class="qty_price">            
                            <span id="pricelist_from_<?php print esc_html($add_price->product_quantity_start)?>">
                                <?php print esc_html(wopshopFormatprice($add_price->price))?><?php print esc_html($add_price->ext_price)?>
                            </span> 
                            <span class="per_piece">/ <?php print esc_html($this->product->product_add_price_unit)?></span>
                        </td>
                    </tr>
                <?php }?>
            </table>
        <?php }?>

        <?php if ($this->product->product_old_price > 0){?>
            <div class="old_price">
                <?php print esc_html(WOPSHOP_OLD_PRICE)?>:
                <span class="old_price" id="old_price">
                    <?php print esc_html(wopshopFormatprice($this->product->product_old_price))?>
                    <?php print $this->product->_tmp_var_old_price_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </span>
            </div>
        <?php }?>

        <?php if (isset($this->product->product_price_default) && $this->product->product_price_default > 0 && $this->config->product_list_show_price_default){?>
            <div class="default_price"><?php print esc_html(WOPSHOP_DEFAULT_PRICE)?>: <span id="pricedefault"><?php print esc_html(wopshopFormatprice($this->product->product_price_default))?></span></div>
        <?php }?>
        
        <?php print $this->_tmp_product_html_before_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if ($this->product->_display_price){?>
            <div class="prod_price">
                <?php print esc_html(WOPSHOP_PRICE)?>:
                <span id="block_price">
                    <?php print esc_html(wopshopFormatprice($this->product->getPriceCalculate()))?>
                    <?php print $this->product->_tmp_var_price_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </span>
            </div>
        <?php }?>
        
        <?php print $this->product->_tmp_var_bottom_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if ($this->config->show_tax_in_product && $this->product->product_tax > 0){?>
            <span class="taxinfo"><?php print esc_html(wopshopProductTaxInfo($this->product->product_tax));?></span>
        <?php }?>
        
        <?php if ($this->config->show_plus_shipping_in_product){?>
            <span class="plusshippinginfo"><?php print wp_kses_post(sprintf(WOPSHOP_PLUS_SHIPPING, $this->shippinginfo));?></span>
        <?php }?>
        
        <?php if ($this->product->delivery_time != ''){?>
            <div class="deliverytime" <?php if ($product->hide_delivery_time){?>style="display:none"<?php }?>><?php print esc_html(WOPSHOP_DELIVERY_TIME)?>: <?php print esc_html($this->product->delivery_time)?></div>
        <?php }?>
        
        <?php if ($this->config->product_show_weight && $this->product->product_weight > 0){?>
            <div class="productweight"><?php print esc_html(WOPSHOP_WEIGHT)?>: <span id="block_weight"><?php print esc_html(wopshop_formatweight($this->product->getWeight()))?></span></div>
        <?php }?>

        <?php if ($this->product->product_basic_price_show){?>
            <div class="prod_base_price"><?php print esc_html(WOPSHOP_BASIC_PRICE)?>: <span id="block_basic_price"><?php print esc_html(wopshopFormatprice($this->product->product_basic_price_calculate))?></span> / <?php print esc_html($this->product->product_basic_price_unit_name)?></div>
        <?php }?>
        
        <?php print $this->product->_tmp_var_bottom_allprices; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if (is_array($this->product->extra_field)){?>
            <div class="extra_fields">
            <?php foreach($this->product->extra_field as $extra_field){?>
                <?php if ($extra_field['grshow']){?>
                    <div class='block_efg'>
                    <div class='extra_fields_group'><?php print wp_kses_post($extra_field['groupname'])?></div>
                <?php }?>
                
                <div class="extra_fields_el">
                    <span class="extra_fields_name"><?php print esc_html($extra_field['name']);?></span><?php if ($extra_field['description']){?>
                        <span class="extra_fields_description">
                            <?php print wp_kses_post($extra_field['description']);?>
                        </span><?php } ?>:
                    <span class="extra_fields_value">
                        <?php print esc_html($extra_field['value']);?>
                    </span>
                </div>
                                
                <?php if (isset($extra_field['grshowclose']) && $extra_field['grshowclose']){?>
                    </div>
                <?php }?>
            <?php }?>
            </div>
        <?php }?>
        
        <?php print $this->_tmp_product_html_after_ef; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <?php if ($this->product->vendor_info){?>
            <div class="vendorinfo">
                <?php print esc_html(WOPSHOP_VENDOR)?>: <?php print esc_html($this->product->vendor_info->shop_name)?> (<?php print esc_html($this->product->vendor_info->l_name." ".$this->product->vendor_info->f_name)?>),
                ( 
                <?php if ($this->config->product_show_vendor_detail){?><a href="<?php print esc_url($this->product->vendor_info->urlinfo)?>"><?php print esc_html(WOPSHOP_ABOUT_VENDOR)?></a>,<?php }?>
                <a href="<?php print esc_url($this->product->vendor_info->urllistproducts)?>"><?php print esc_html(WOPSHOP_VIEW_OTHER_VENDOR_PRODUCTS)?></a> )
            </div>
        <?php }?>

        <?php if (!$this->config->hide_text_product_not_available){ ?>
            <div class = "not_available" id="not_available"><?php print wp_kses_post($this->available)?></div>
        <?php }?>

        <?php if ($this->config->product_show_qty_stock){?>
            <div class="qty_in_stock">
                <?php print esc_html(WOPSHOP_QTY_IN_STOCK)?>:
                <span id="product_qty"><?php print esc_html(wopshopSprintQtyInStock($this->product->qty_in_stock));?></span>
            </div>
        <?php }?>

        <?php print $this->_tmp_product_html_before_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if (!$this->hide_buy){?>                         
            <div class="prod_buttons" style="<?php print wp_kses_post($this->displaybuttons)?>">
                
                <div class="prod_qty">
                    <?php print esc_html(WOPSHOP_QUANTITY)?>:
                </div>
                
                <div class="prod_qty_input">
                    <input type="text" name="quantity" id="quantity" onkeyup="reloadPrices();" class="inputbox" value="<?php print $this->default_count_product?>" /><?php print $this->_tmp_qty_unit; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </div>
                        
                <div class="buttons">            
                    <input type="submit" class="btn btn-primary button" value="<?php print esc_html(WOPSHOP_ADD_TO_CART)?>" onclick="jQuery('#to').val('cart');" />
                    
                    <?php if ($this->enable_wishlist){?>
                        <input type="submit" class="btn button" value="<?php print esc_html(WOPSHOP_ADD_TO_WISHLIST)?>" onclick="jQuery('#to').val('wishlist');" />
                    <?php }?>
                    
                    <?php print $this->_tmp_product_html_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </div>
                
                <div id="wshop_image_loading" style="display:none"></div>
            </div>
        <?php }?>
        
        <?php print $this->_tmp_product_html_after_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>

        <input type="hidden" name="to" id='to' value="cart" />
        <input type="hidden" name="product_id" id="product_id" value="<?php print esc_attr($this->product->product_id)?>" />
    </form>

    <?php print $this->_tmp_product_html_before_demofiles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    
    <div id="list_product_demofiles"><?php include(dirname(__FILE__)."/demofiles.php");?></div>
    
    <?php if ($this->config->product_show_button_back){?>
        <div class="button_back">
            <input type="button" class="btn button" value="<?php print esc_html(WOPSHOP_BACK);?>" onclick="<?php print $this->product->button_back_js_click; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>" />
        </div>
    <?php }?>
    
    <?php
        print $this->_tmp_product_html_before_related; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        include(dirname(__FILE__)."/related.php");
        print $this->_tmp_product_html_before_review; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        include(dirname(__FILE__)."/review.php");
    ?>
    
    <?php print $this->_tmp_product_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>