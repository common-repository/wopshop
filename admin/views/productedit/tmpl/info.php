<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
?>

<table class="admintable" width="90%">
     <tr>
       <td class="key" style="width:180px;">
         <?php echo esc_html(WOPSHOP_PUBLISH);?>
       </td>
       <td>
         <input type="checkbox" name="product_publish" id="product_publish" value="1" <?php if ($row->product_publish) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
       </td>
     </tr>
<!--     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_ACCESS);?>*
       </td>
       <td>
         <?php print $this->lists['access']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>     -->
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_PRODUCT_PRICE);?>*
       </td>
       <td>
         <input type="text" name="product_price" id="product_price" value="<?php echo esc_attr($row->product_price)?>" <?php if (!$this->withouttax){?> onkeyup="updatePrice2(<?php print esc_attr($config->display_price_admin);?>)" <?php }?> /> <?php echo $this->lists['currency']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php if (!$this->withouttax){?>
     <tr>
       <td class="key">
         <?php if ($config->display_price_admin==0) echo esc_html(WOPSHOP_PRODUCT_NETTO_PRICE); else echo esc_html(WOPSHOP_PRODUCT_BRUTTO_PRICE);?>
       </td>
       <td>
         <input type="text" id="product_price2" value="<?php echo esc_attr($row->product_price2);?>" onkeyup="updatePrice(<?php print esc_attr($config->display_price_admin);?>)" />
       </td>
     </tr>
     <?php }?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_PRODUCT_ADD_PRICE);?>
       </td>
       <td>
         <input type="checkbox" name="product_is_add_price" id="product_is_add_price" value="1" <?php if ($row->product_is_add_price) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>  onclick="showHideAddPrice()" />
       </td>
     </tr>
     <tr id="tr_add_price">
        <td class="key"><?php echo esc_html(WOPSHOP_PRODUCT_ADD_PRICE);?></td>
         <td>
            <table id="table_add_price" class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <?php echo esc_html(WOPSHOP_PRODUCT_QUANTITY_START);?>    
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_PRODUCT_QUANTITY_FINISH);?>    
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_DISCOUNT);?>
                        (<?php if ($config->product_price_qty_discount==1) echo ""; else print "%"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_PRODUCT_PRICE);?>
                    </th>                    
                    <th>
                        <?php echo esc_html(WOPSHOP_DELETE);?>    
                    </th>
                </tr>
                </thead>                
                <?php 
                $add_prices=$row->product_add_prices;
                $count=count($add_prices);
                for ($i=0; $i < $count; $i++){
                    if ($config->product_price_qty_discount==1){
                        $_add_price=$row->product_price - $add_prices[$i]->discount;
                    }else{
                        $_add_price=$row->product_price - ($row->product_price * $add_prices[$i]->discount / 100);
                    }
                    $_add_price=number_format($_add_price,2,".","");
                    ?>
                    <tr id="add_price_<?php print esc_attr($i)?>">
                        <td>
                            <input type="text" name="quantity_start[]" id="quantity_start_<?php print esc_attr($i)?>" value="<?php echo esc_attr($add_prices[$i]->product_quantity_start);?>" />    
                        </td>
                        <td>
                            <input type="text" name="quantity_finish[]" id="quantity_finish_<?php print esc_attr($i)?>" value="<?php echo esc_attr($add_prices[$i]->product_quantity_finish);?>" />    
                        </td>
                        <td>
                            <input type="text" name="product_add_discount[]" id="product_add_discount_<?php print esc_attr($i)?>" value="<?php echo esc_attr($add_prices[$i]->discount);?>" onkeyup="productAddPriceupdateValue(<?php print esc_attr($i)?>)" />    
                        </td>
                        <td>
                            <input type="text" id="product_add_price_<?php print esc_attr($i)?>" value="<?php echo esc_attr($_add_price);?>" onkeyup="productAddPriceupdateDiscount(<?php print esc_attr($i)?>)" />
                        </td>
                        <td align="center">
                            <a href="#" onclick="delete_add_price(<?php print esc_attr($i)?>);return false;"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>" border="0"/></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>                
            </table>
            <table class="table table-striped">
            <tr>
                <td><?php echo $lists['add_price_units']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> - <?php echo esc_html(WOPSHOP_UNIT_MEASURE);?></td>
                <td align="right" width="100">
                    <input class="button" type="button" name="add_new_price" onclick="addNewPrice()" value="<?php echo esc_html(WOPSHOP_PRODUCT_ADD_PRICE_ADD);?>" />
                </td>
            </tr>
            </table>
            <script type="text/javascript">
            <?php 
            print "var add_price_num=$i;"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            print "var config_product_price_qty_discount=".$config->product_price_qty_discount.";"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>             
            </script>
        </td>
     </tr>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_OLD_PRICE);?>
       </td>
       <td>
         <input type="text" name="product_old_price" id="product_old_price" value="<?php echo esc_attr($row->product_old_price)?>" />
       </td>
     </tr>
     
     <?php if ($config->admin_show_product_bay_price) { ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_PRODUCT_BUY_PRICE);?>
       </td>
       <td>
         <input type="text" name="product_buy_price" id="product_buy_price" value="<?php echo esc_attr($row->product_buy_price)?>" />
       </td>
     </tr>
     <?php } ?>
     
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_PRODUCT_WEIGHT);?>
       </td>
       <td>
         <input type="text" name="product_weight" id="product_weight" value="<?php echo esc_attr($row->product_weight)?>" /> <?php print wopshopSprintUnitWeight(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>     
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_EAN_PRODUCT);?>
       </td>
       <td>
         <input type="text" name="product_ean" id="product_ean" value="<?php echo esc_attr($row->product_ean)?>" onkeyup="updateEanForAttrib()"; />
       </td>
     </tr>
     <?php if ($config->stock){?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_QUANTITY_PRODUCT);?>*
       </td>
       <td>
         <div id="block_enter_prod_qty" style="padding-bottom:2px;<?php if ($row->unlimited) print "display:none;"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
             <input type="text" name="product_quantity" id="product_quantity" value="<?php echo esc_attr($row->product_quantity)?>" <?php if ($this->product_with_attribute){?>readonly="readonly"<?php }?> />
             <?php if ($this->product_with_attribute){ echo esc_html(WOPSHOP_INFO_PLEASE_EDIT_AMOUNT_FOR_ATTRIBUTE); } ?>
         </div>
         <div>         
            <input type="checkbox" name="unlimited" value="1" onclick="ShowHideEnterProdQty(this.checked)" <?php if ($row->unlimited) print "checked"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /> <?php print esc_html(WOPSHOP_UNLIMITED);?>
         </div>         
       </td>
     </tr>
     <?php }?>
     <tr>
       <td class="key"><?php echo esc_html(WOPSHOP_URL); ?></td>
       <td>
         <input type="text" name="product_url" id="product_url" value="<?php echo esc_attr($row->product_url)?>" size="80" />
       </td>
     </tr>
     
     <?php if ($config->use_different_templates_cat_prod) { ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_TEMPLATE_PRODUCT);?>
       </td>
       <td>
         <?php echo $lists['templates']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php } ?>
     
     <?php if (!$this->withouttax){?>
     <tr>     
       <td class="key">
         <?php echo esc_html(WOPSHOP_TAX);?>*
       </td>
       <td>
         <?php echo $lists['tax']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php }?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_NAME_MANUFACTURER);?>
       </td>
       <td>
         <?php echo $lists['manufacturers']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_CATEGORIES);?>*
       </td>
       <td>
         <?php echo $lists['categories']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php if ($config->admin_show_vendors && $this->display_vendor_select) { ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_VENDOR);?>
       </td>
       <td>
         <?php echo $lists['vendors']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php }?>
     
     <?php if ($config->admin_show_delivery_time) { ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_DELIVERY_TIME);?>
       </td>
       <td>
         <?php echo $lists['deliverytimes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php }?>
     
     <?php if ($config->admin_show_product_labels) { ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_LABEL);?>
       </td>
       <td>
         <?php echo $lists['labels']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php }?>
     
     <?php if ($config->admin_show_product_basic_price) { ?>
     <tr>
       <td class="key"><br/><?php echo esc_html(WOPSHOP_BASIC_PRICE);?></td>
     </tr>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_WEIGHT_VOLUME_UNITS);?>
       </td>
       <td>
         <input type="text" name="weight_volume_units" value="<?php echo esc_attr($row->weight_volume_units)?>" />
       </td>
     </tr>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_UNIT_MEASURE);?>
       </td>
       <td>
         <?php echo $lists['basic_price_units']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php }?>
     <?php if ($config->return_policy_for_product){?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_RETURN_POLICY_FOR_PRODUCT);?>
       </td>
       <td>
         <?php echo $lists['return_policy']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
     </tr>
     <?php if (!$config->no_return_all){?>  
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_NO_RETURN);?>
       </td>
       <td>
         <input type="hidden" name="options[no_return]"  value="0" />
         <input type="checkbox" name="options[no_return]" value="1" <?php if ($row->product_options['no_return']) echo 'checked = "checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
       </td>
     </tr>
     <?php }?>
     <?php }?>
     <?php $pkey='plugin_template_info'; if ($this->$pkey){ print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   </table>