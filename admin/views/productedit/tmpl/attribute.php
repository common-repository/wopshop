<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
?>
<div id="tabAttributes" class="tab">
<?php if ( (count($lists['all_independent_attributes'])+count($lists['all_attributes']))>0 ){?>
    <script type="text/javascript">
        var lang_error_attribute = "<?php print esc_html(WOPSHOP_ERROR_ADD_ATTRIBUTE); ?>";
        var lang_attribute_exist = "<?php print esc_html(WOPSHOP_ATTRIBUTE_EXIST); ?>";
        var folder_image_attrib = "<?php print esc_html($config->image_attributes_live_path)?>";
        var use_basic_price = "<?php print $config->admin_show_product_basic_price // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
        var use_bay_price = "<?php print $config->admin_show_product_bay_price // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
        var use_stock = "<?php print esc_html(intval($config->stock))?>";
        var attrib_images = new Object();
        <?php foreach($lists['attribs_values'] as $k=>$v){?>
        attrib_images[<?php print esc_html($v->value_id)?>] = "<?php print esc_html($v->image)?>";
        <?php }?>
    </script>
<?php }?>
<?php if (count($lists['all_attributes'])){ ?>
    <script type="text/javascript">
        var attrib_ids = new Array();
        var attrib_exist = new Object();
        <?php $i=0; foreach($lists['all_attributes'] as $key=>$value){ ?>
            attrib_ids[<?php print esc_html($i++);?>] = "<?php echo esc_html($value->attr_id) ?>";            
       <?php } ?>
       
       <?php
       $attr_tmp_row_num = 0;
       if (count($lists['attribs'])){
           
           foreach($lists['attribs'] as $k=>$v){
               $attr_tmp_row_num++;
               print "attrib_exist[".$attr_tmp_row_num."]={};\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               foreach($lists['all_attributes'] as $key=>$value){
                    $tmp_field = "attr_".$value->attr_id;
                    $tmp_val = $v->$tmp_field;
                    print "attrib_exist[".$attr_tmp_row_num."][".$value->attr_id."]='".$tmp_val."';\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }
           
           }
       }
       print "var attr_tmp_row_num = $attr_tmp_row_num;\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       ?>       
       </script>
       <table class = "adminlist" id="list_attr_value">
       <thead>
       <tr>
       <?php foreach($lists['all_attributes'] as $key=>$value){ ?>
            <th width="120"><?php echo esc_html($value->name)?></th>
       <?php } ?>
            <th width="120"><?php print esc_html(WOPSHOP_PRICE); ?></th>
			<?php print $this->dep_attr_td_header // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php if ($config->stock){?>
            <th width="120"><?php print esc_html(WOPSHOP_QUANTITY_PRODUCT )?></th>
            <?php }?>
            <th width="120"><?php print esc_html(WOPSHOP_EAN_PRODUCT )?></th>
            <th width="120"><?php print esc_html(WOPSHOP_PRODUCT_WEIGHT)?> (<?php print wopshopSprintUnitWeight() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)</th>
            <?php if ($config->admin_show_product_basic_price){?>
                <th width="120"><?php print esc_html(WOPSHOP_WEIGHT_VOLUME_UNITS )?></th>
            <?php }?>
            <th width="120"><?php print esc_html(WOPSHOP_OLD_PRICE); ?></th>
            <?php if ($config->admin_show_product_bay_price){?>
            <th width="120"><?php print esc_html(WOPSHOP_PRODUCT_BUY_PRICE); ?></th>
            <?php }?>
            <th></th>
            <th width="60"><input type='checkbox' id='ch_attr_delete_all' onclick="selectAllListAttr(this.checked)"></th>
       </tr>
       </thead>
       <?php       
       if (count($lists['attribs'])){
           $attr_tmp_row_num = 0;
           foreach($lists['attribs'] as $k=>$v){
               $attr_tmp_row_num++;
               print "<tr id='attr_row_".$attr_tmp_row_num."'>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               foreach($lists['all_attributes'] as $key=>$value){
                    $tmp_field = "attr_".$value->attr_id;
                    $tmp_val = $v->$tmp_field;
                    $tmp_val_val = $lists['attribs_values'][$tmp_val]->name;
                    $image_ = "";
                    if ($lists['attribs_values'][$tmp_val]->image!=''){
                        $image_ = "<img src='".esc_url($config->image_attributes_live_path."/".$lists['attribs_values'][$tmp_val]->image)."' align='left' hspace='5' width='16' height='16' style='margin-right:5px;' class='img_attrib'>";
                    }
                    print "<td><input type='hidden' name='attrib_id[".$value->attr_id."][]' value='".$tmp_val."'>".$image_.$tmp_val_val."</td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }			   
               print "<td><input type='text' name='attrib_price[]' value='".floatval($v->price)."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			   print isset($this->dep_attr_td_row[$k]) ? $this->dep_attr_td_row[$k] : ""; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               if ($config->stock){
               print "<td><input type='text' name='attr_count[]' value='".$v->count."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }
               print "<td><input type='text' name='attr_ean[]' value='".$v->ean."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               print "<td><input type='text' name='attr_weight[]' value='".$v->weight."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               if ($config->admin_show_product_basic_price){
                print "<td><input type='text' name='attr_weight_volume_units[]' value='".$v->weight_volume_units."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }
               print "<td><input type='text' name='attrib_old_price[]' value='".$v->old_price."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               if ($config->admin_show_product_bay_price){
                  print "<td><input type='text' name='attrib_buy_price[]' value='".floatval($v->buy_price)."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }
               print "<td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               if ($config->use_extend_attribute_data){
                   print "<a class='btn btn-mini button' target='_blank' href='".esc_url(admin_url('admin.php?page=wopshop-products&task=edit&product_attr_id='.$v->product_attr_id))."' onclick='editAttributeExtendParams(".$v->product_attr_id.");return false;'>".WOPSHOP_ATTRIBUTE_EXTEND_PARAMS."</a>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               }
               print "</td>";		    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               print "<td><input type='hidden' name='product_attr_id[]' value='".$v->product_attr_id."'><input type='checkbox' class='ch_attr_delete' value='".$attr_tmp_row_num."'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
               print "</tr>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
           }           
       }
       print "<tr id='attr_row_end'>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       foreach($lists['all_attributes'] as $key=>$value){
           print "<td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       }
       if ($config->stock){
       print "<td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       }
       print "<td></td><td></td><td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	   print $this->dep_attr_td_row_empty; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       if ($config->admin_show_product_basic_price) print "<td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       print "<td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       if ($config->admin_show_product_bay_price) print "<td></td>";               // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       print "<td></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       print "<td><input type='button' value='".WOPSHOP_DELETE."' onclick='deleteListAttr()'></td>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       print "</tr>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
       ?>
       </table>
       <br/>
       <div class="col width-55">
        <fieldset class="adminform" style="margin-left:0px;">
        <legend><?php echo esc_html(WOPSHOP_ADD_ATTRIBUT)?></legend>
            <table class="admintable">
            <?php foreach($lists['all_attributes'] as $key=>$value){ ?>
            <tr>
                <td class="key"><?php echo esc_html($value->name)?></td>
                <td><?php echo $value->values_select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>    
            <?php } ?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_PRICE);?>*</td>
                <td><input type="text" id="attr_price" value="<?php echo esc_attr($row->product_price)?>" style="width:100px;"></td>
            </tr>
			<?php print $this->dep_attr_td_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php if ($config->stock){?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_QUANTITY_PRODUCT)?>*</td>
                <td><input type="text" id="attr_count"  style="width:100px;" value="1"></td> 
            </tr>
            <?php }?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_EAN_PRODUCT)?></td>
                <td><input type="text" id="attr_ean" style="width:100px;" value="<?php echo esc_attr($row->product_ean)?>"></td>
            </tr>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_PRODUCT_WEIGHT)?></td>
                <td><input type="text" id="attr_weight" style="width:100px;" value="<?php echo esc_attr($row->product_weight)?>"> <?php print wopshopSprintUnitWeight(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
            <?php if ($config->admin_show_product_basic_price){?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_WEIGHT_VOLUME_UNITS)?></td>
                <td><input type="text" id="attr_weight_volume_units" style="width:100px;" value="<?php echo esc_attr($row->weight_volume_units)?>"></td>
            </tr>
            <?php }?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_OLD_PRICE);?></td>
                <td><input type="text" id="attr_old_price" value="<?php echo esc_attr($row->product_old_price)?>" style="width:100px;"></td>
            </tr>
            <?php if ($config->admin_show_product_bay_price){?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_PRODUCT_BUY_PRICE);?></td>
                <td><input type="text" id="attr_buy_price" value="<?php echo esc_attr($row->product_buy_price)?>" style="width:100px;"> </td>
            </tr>
            <?php }?>
            <tr>
                <td></td>
                <td>
                <div style="width:100px;text-align:right;">                
		<?php print $lists['dep_attr_button_add'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                </td>
            </tr>            
            </table>
        </fieldset>    
       </div>
       <div class="clr"></div>
       <br/>
   <?php
   }
   
   if (count($lists['all_independent_attributes'])){
   ?>
   <?php foreach($lists['all_independent_attributes'] as $ind_attr){?>
        <div style="padding-top:20px;">
        <table class = "adminlist" id="list_attr_value_ind_<?php print esc_attr($ind_attr->attr_id)?>">
        <thead>
        <tr>
            <th width="150"><?php print esc_html($ind_attr->name)?></th>
            <th width="120"><?php print esc_html(WOPSHOP_PRICE_MODIFICATION)?></th>
            <th width="120"><?php print esc_html(WOPSHOP_PRICE); ?></th>
            <?php print $this->ind_attr_td_header // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <th><?php print esc_html(WOPSHOP_DELETE)?></th>
        </tr>
        </thead>        
        <?php 
        if (isset($lists['ind_attribs_gr'][$ind_attr->attr_id]) && is_array($lists['ind_attribs_gr'][$ind_attr->attr_id])){
        foreach($lists['ind_attribs_gr'][$ind_attr->attr_id] as $ind_attr_val){ 
        ?>
        <tr id='attr_ind_row_<?php print esc_attr($ind_attr_val->attr_id)?>_<?php print esc_attr($ind_attr_val->attr_value_id)?>'>
            <td>
            <?php if ($lists['attribs_values'][$ind_attr_val->attr_value_id]->image!=''){?>
                <img src='<?php print esc_url($config->image_attributes_live_path."/".$lists['attribs_values'][$ind_attr_val->attr_value_id]->image)?>' align='left' hspace='5' width='16' height='16' style='margin-right:5px;' class='img_attrib'>
            <?php }?>
            <input type='hidden' id='attr_ind_<?php print esc_attr($ind_attr_val->attr_id)?>_<?php print esc_attr($ind_attr_val->attr_value_id)?>' name='attrib_ind_id[]' value='<?php print esc_attr($ind_attr_val->attr_id)?>'>
            <input type='hidden' name="attrib_ind_value_id[]" value='<?php print esc_attr($ind_attr_val->attr_value_id)?>'>
            <?php print $lists['attribs_values'][$ind_attr_val->attr_value_id]->name;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </td>
            <td><input type='text' name='attrib_ind_price_mod[]' value='<?php print esc_attr($ind_attr_val->price_mod)?>'></td>
            <td><input type='text' name='attrib_ind_price[]' value='<?php print esc_attr(floatval($ind_attr_val->addprice))?>'></td>
            <?php if (isset($this->ind_attr_td_row[$ind_attr_val->attr_value_id])) print $this->ind_attr_td_row[$ind_attr_val->attr_value_id] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <td><a href='#' onclick="jQuery('#attr_ind_row_<?php print esc_attr($ind_attr_val->attr_id)?>_<?php print esc_attr($ind_attr_val->attr_value_id)?>').remove();return false;"><img src="<?php print esc_attr(esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png'))?>"></a></td>
        </tr>
        <?php }
        }
        ?>
        </table>
        </div>
        
        <div style="padding-top:5px;">
        <table cellpadding="4">
            <tr>
                <td width="150"><?php print $ind_attr->values_select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                <td width="120"><?php print $ind_attr->price_modification_select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                <td width="120"><input type="text" id="attr_ind_price_tmp_<?php print esc_attr($ind_attr->attr_id)?>" value="0"></td>
                <?php if (isset($this->ind_attr_td_footer[$ind_attr->attr_id])) print $this->ind_attr_td_footer[$ind_attr->attr_id] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <td><?php print $ind_attr->submit_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>
        </table>
        </div>
    <?php }?>
   <br/><br/>
   <?php
   }   
   ?>
   

   <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributes'))?>" target="_blank"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/shop_attributes_s.png')?>" border='0' align="left" style="margin-right:5px"><?php print esc_html(WOPSHOP_LIST_ATTRIBUTES);?></a>
   </div>
