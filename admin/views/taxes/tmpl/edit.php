<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($this->edit ? WOPSHOP_EDIT_TAX . ' / ' . $this->tax->tax_name :  WOPSHOP_NEW_TAX); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&task=save'))?>" id="edittax">
            <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <table width = "100%" class="admintable">
               <tr>
                 <td class="key" style="width:250px;">
                   <?php echo esc_html(WOPSHOP_TITLE);?>*
                 </td>
                 <td>
                   <input type = "text" class = "inputbox" id = "tax_name" name = "tax_name" value = "<?php echo esc_attr($this->tax->tax_name);?>" />
                 </td>
               </tr>
               <tr>
                 <td  class="key">
                   <?php echo esc_html(WOPSHOP_VALUE);?>*
                 </td>
                 <td>
                   <input type = "text" class = "inputbox" id = "tax_value" name = "tax_value" value = "<?php echo esc_attr($this->tax->tax_value);?>" /> %
                   <?php echo WopshopHtml::tooltip(WOPSHOP_VALUE_TAX_INFO); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                 </td>
               </tr>
             </table>
            <input type="hidden" value="<?php echo esc_attr($this->tax->tax_id); ?>" name="tax_id">
            <?php wp_nonce_field('tax_edit','name_of_nonce_field'); ?>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
            </p> 
        </form>
    </div>
</div>