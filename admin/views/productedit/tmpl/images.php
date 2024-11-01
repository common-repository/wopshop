<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
?>
<div id="tabImages" class="tab<?php if ($this->product->parent_id!=0){?> active<?php }?>">
    <table>
       <tr>
          <?php
          $i = 0;
          $count_in_row = 4;
          if (count($this->lists['images']))
          foreach ($this->lists['images'] as $image){
          ?>
            <td style = "vertical-align: top; text-align: center;">
                <div id="foto_product_<?php print esc_attr($image->image_id)?>">
                    <input type="text" name="old_image_descr[<?php print esc_attr($image->image_id)?>]" value="<?php print esc_attr(htmlspecialchars($image->name));?>" size="22" />
                    <div style="height:3px;"></div>
                    <div style="padding-bottom:5px;padding-right:5px;">
                        <a href="<?php echo esc_url(wopshopGetPatchProductImage($image->image_name, 'full', 1))?>" rel="{handler: 'image'}">
                            <img style="cursor:pointer" src="<?php echo esc_url(wopshopGetPatchProductImage($image->image_name, 'thumb', 1))?>" alt="" />
                        </a>
                    </div>
                    <?php print esc_html(WOPSHOP_ORDERING)?>: <input type="text" name="old_image_ordering[<?php print esc_attr($image->image_id)?>]" value="<?php print esc_attr($image->ordering);?>" size="3" />
                    <div style="height:3px;"></div>
                    <input type="radio" name="set_main_image" id="set_main_image_<?php echo esc_attr($image->image_id)?>" value="<?php echo esc_attr($image->image_id)?>" <?php if ($row->image == $image->image_name) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>/> <label style="min-width: 50px;float:none;" for="set_main_image_<?php echo esc_attr($image->image_id)?>"><?php echo esc_html(WOPSHOP_SET_MAIN_IMAGE);?></label>
                    <div class="link_delete_foto"><a href="#" onclick="if (confirm('<?php print esc_attr(WOPSHOP_DELETE_IMAGE);?>')) deleteFotoProduct('<?php echo esc_attr($image->image_id)?>');return false;"><img src="<?php print esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"> <?php print esc_html(WOPSHOP_DELETE_IMAGE);?></a></div>
                </div>
            </td>
          <?php
           if (++$i % $count_in_row == 0) echo '</tr><tr>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          }
          ?>
       </tr>
    </table>

    <div class="clearfix">
        <div class="col width-45" style="float:left">
            <fieldset class="adminform">
            <legend><?php echo esc_html(WOPSHOP_UPLOAD_IMAGE)?></legend>
            <div style="height:4px;"></div>
            <?php for($i = 0; $i < $this->config->product_image_upload_count; $i++){?>
            <div style="padding-bottom:6px;">
                <input type="text" name="product_image_descr_<?php print esc_attr($i);?>" size="35" title="<?php print esc_attr(WOPSHOP_TITLE)?>" />
                <input type="file" class="product_image" name="product_image_<?php print esc_attr($i);?>" />
            </div>
            <?php } ?>        
            </fieldset>
        </div>

        <div class="col width-55" style="float:left">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_IMAGE_THUMB_SIZE)?></legend>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_im_product" id = "size_1" checked = "checked" onclick = "setDefaultSize(<?php echo esc_attr($this->config->image_product_width); ?>,<?php echo esc_attr($this->config->image_product_height); ?>, 'product')" value = "1" /></td>
                <td><label for = "size_1" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_1);?></label></td>
                </tr></table>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_im_product" value = "3" id = "size_3" onclick = "setOriginalSize('product')" value = "3"/></td>
                <td><label for = "size_3" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_3);?></label></td>
                </tr></table>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_im_product" id = "size_2" onclick = "setManualSize('product')" value = "2" /></td>
                <td><label for = "size_2" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_2);?></label></td>
                </tr></table>            
                <div class="key1"><?php echo esc_html(WOPSHOP_IMAGE_WIDTH)?></div>
                <div class="value1"><input type = "text" id = "product_width_image" name = "product_width_image" value = "<?php echo esc_attr($this->config->image_product_width)?>" disabled = "disabled" /></div>
                <div class="key1"><?php echo esc_html(WOPSHOP_IMAGE_HEIGHT)?></div>
                <div class="value1"><input type = "text" id = "product_height_image" name = "product_height_image" value = "<?php echo esc_attr($this->config->image_product_height)?>" disabled = "disabled" /></div>
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_IMAGE_SIZE )?></legend>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_full_product" id = "size_full_1" onclick = "setDefaultSize(<?php echo esc_attr($this->config->image_product_full_width); ?>,<?php echo esc_attr($this->config->image_product_full_height); ?>, 'product_full')" value = "1" checked = "checked" /></td>
                <td><label for = "size_full_1" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_1);?></label></td>
                </tr></table>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_full_product" id = "size_full_3" onclick = "setFullOriginalSize('product_full')" value = "3" /></td>
                <td><label for = "size_full_3" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_3);?></label></td>
                </tr></table>
                <table class="tmiddle"><tr>
                <td><input type = "radio" name = "size_full_product" id = "size_full_2" onclick = "setFullManualSize('product_full')" value = "2"/></td>
                <td><label for = "size_full_2" style="margin:0px;"><?php echo esc_html(WOPSHOP_IMAGE_SIZE_2);?></label></td>
                </tr></table>            
                <div class="key1"><?php echo esc_html(WOPSHOP_IMAGE_WIDTH)?></div>
                <div class="value1"><input type = "text" id = "product_full_width_image" name = "product_full_width_image" value = "<?php echo esc_attr($this->config->image_product_full_width); ?>" disabled = "disabled" /></div>
                <div class="key1"><?php echo esc_html(WOPSHOP_IMAGE_HEIGHT)?></div>
                <div class="value1"><input type = "text" id = "product_full_height_image" name = "product_full_height_image" value = "<?php echo esc_attr($this->config->image_product_full_height); ?>" disabled = "disabled" /></div>
            </fieldset>
        </div>
    </div>
</div>
    
    