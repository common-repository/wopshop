<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row = $this->shipping; 
$edit = $this->edit; 
?>
<form action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippings&task=save'))?>" method = "post" name = "adminForm">
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($row->shipping_id ? WOPSHOP_EDIT_SHIPPING . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW_SHIPPING); ?></h3>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <fieldset class="adminform">
            <table class="admintable" width = "100%" >
                <tr>
                <td class="key" width = "30%">
                        <?php echo esc_html(WOPSHOP_PUBLISH);?>
                </td>
                <td>
                        <input type = "checkbox" name = "published" value = "1" <?php if ($row->published) echo 'checked = "checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                </td>
                </tr>
            <?php 
            foreach($this->languages as $lang){
            $field = "name_".$lang->language;
            ?>
                <tr>
                <td class="key">
                        <?php echo esc_html(WOPSHOP_TITLE);?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>*
                </td>
                <td>
                        <input type = "text" class = "inputbox" id = "<?php print esc_attr($field)?>" name = "<?php print esc_attr($field)?>" value = "<?php echo esc_attr($row->$field);?>" />
                </td>
                </tr>
            <?php }?>
            <tr>
             <td class="key">
               <?php echo esc_html(WOPSHOP_ALIAS);?>
             </td>
             <td>
               <input type="text" class="inputbox" name="alias" value="<?php echo esc_attr($row->alias)?>" <?php if ($this->config->shop_mode==0 && $row->shipping_id){?>readonly <?php }?> />
             </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_PAYMENTS);?>
                </td>
                <td>
                   <?php print $this->lists['payments'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_IMAGE_URL);?>
                </td>
                <td>
                    <input type="text" class="inputbox" name="image" value="<?php echo esc_attr($row->image);?>" />
                </td>
            </tr>
            <?php 
            foreach($this->languages as $lang){
            $field = "description_".$lang->language;
            ?>
                <tr>
                <td class="key">
                        <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
                </td>
                <td>
                <?php $args = array('media_buttons' => 1,
                                    'textarea_name' => "description".$lang->id,
                                    'textarea_rows' => 20,
                                    'tabindex'      => null,
                                    'tinymce'       => 1,
                              );
                              wp_editor( $row->$field, "description".$lang->id, $args );
                        ?>
                </td>
                </tr>
            <?php }?>
        </table>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </fieldset>
    </div>
    <div class="clr"></div>
</div>
<input type = "hidden" name = "edit" value = "<?php echo esc_attr($edit);?>" />
<?php if ($edit) {?>
  <input type = "hidden" name = "shipping_id" value = "<?php echo esc_attr($row->shipping_id)?>" />
<?php }?>
    
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippings'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <?php wp_nonce_field('shippings_edit','name_of_nonce_field'); ?>

</form>
