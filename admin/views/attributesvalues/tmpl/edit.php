<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&task=save'))?>" enctype="multipart/form-data">
<div class="wrap">
	<h2><?php echo esc_html($this->attributValue->value_id ? WOPSHOP_EDIT . ' / ' . $this->attributValue->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW); ?></h2>
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <table class="admintable" width = "100%" >
        <?php 
        foreach($this->languages as $lang){
        $field = "name_".$lang->language;
        ?>
         <tr>
           <td class="key">
             <?php echo esc_html(WOPSHOP_NAME_ATTRIBUT_VALUE);?> <?php if ($this->multilang) print "(".esc_html($lang->lang).")";?>* 
           </td>
           <td>
             <input type = "text" class = "inputbox" name = "<?php print esc_attr($field)?>" value = "<?php echo esc_attr($this->attributValue->$field)?>" />
           </td>
         </tr>
      <?php } ?>
      <tr>
        <td class="key"><?php print esc_html(WOPSHOP_IMAGE_ATTRIBUT_VALUE)?></td>
        <td>
        <?php if ($this->attributValue->image) {?>
        <div id="image_attrib_value">
            <div ><img src = "<?php echo esc_url($this->config->image_attributes_live_path."/".$this->attributValue->image)?>" alt = "" style="max-width: 500px"/></div>
            <div style="padding-bottom:5px;" class="link_delete_foto">
                <a href="#" onclick="if (confirm('<?php print esc_attr(WOPSHOP_DELETE_IMAGE);?>')) deleteFotoAttribValue('<?php echo esc_attr($this->attributValue->value_id)?>');return false;">
                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"> <?php print esc_html(WOPSHOP_DELETE_IMAGE);?>
                </a>
            </div>
        </div>
        <?php }?>
        <div style="clear:both"></div>
        <input type = "file" name = "image" />
        </td>
      </tr>
    </table>
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&attr_id='.$this->attr_id))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    
    <input type="hidden" name="old_image" value="<?php print esc_attr($this->attributValue->image);?>" />
    <input type="hidden" name="value_id" value="<?php echo esc_attr($this->attributValue->value_id);?>" />
    <input type="hidden" name="attr_id" value="<?php echo esc_attr($this->attr_id);?>" />
    <?php wp_nonce_field('attributesvalues_edit'); ?>
</form>
