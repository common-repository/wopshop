<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
$config = $this->config;

wopshopDisplaySubmenuConfigs('general');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<input type="hidden" value="1" name="tabs">
<div class="col100">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_GENERAL_PARAMETERS )?></legend>
<table class="admintable">
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_EMAIL_ADMIN);?>
    </td>
    <td>
        <input type="text" name="contact_email" class="inputbox" value="<?php echo esc_attr($config->contact_email);?>" />
        <?php echo WopshopHtml::tooltip(WOPSHOP_EMAIL_ADMIN_INFO); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_DEFAULT_LANGUAGE);?>
    </td>
    <td>
        <?php echo $lists['languages'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php echo WopshopHtml::tooltip(WOPSHOP_INFO_DEFAULT_LANGUAGE); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_TEMPLATE);?>
    </td>
    <td>
        <?php echo $lists['template']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_DISPLAY_PRICE_ADMIN);?>
    </td>
    <td>
        <?php echo $lists['display_price_admin'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>        
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_DISPLAY_PRICE_FRONT);?>
    </td>
    <td>
        <?php echo $lists['display_price_front'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>    
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_USE_SSL)?>
    </td>
    <td>
        <input type="checkbox" name="use_ssl"  value="1" <?php if ($config->use_ssl) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_SAVE_INFO_TO_LOG)?>
    </td>
    <td>
        <input type="checkbox" name="savelog" id="savelog" value="1" <?php if ($config->savelog) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> onclick="if (!jQuery('#savelog').prop('checked')) jQuery('#savelogpaymentdata').prop('checked',false);" />
    </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_SAVE_PAYMENTINFO_TO_LOG)?>
    </td>
    <td>
        <input type="checkbox" name="savelogpaymentdata" id="savelogpaymentdata" value="1" <?php if ($config->savelogpaymentdata) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> onclick="if (!jQuery('#savelog').prop('checked')) this.checked=false;" />
        <?php echo WopshopHtml::tooltip(WOPSHOP_SAVE_PAYMENTINFO_TO_LOG_INFO); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
</tr>
<tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_DATE_FORMAT);?>
     </td>
     <td>
       <input size="50" type="text" class="inputbox" name="store_date_format" value="<?php echo esc_attr($config->store_date_format)?>" />
     </td>
</tr>
<tr>
    <td class="key">
        <?php echo esc_html(WOPSHOP_SECURITYKEY)?>
    </td>
    <td>
        <input type="text" name="securitykey" class="inputbox" size="50" value="<?php print esc_attr($config->securitykey);?>" />
    </td>
</tr>
</table>
</fieldset>
</div>
<div class="clear"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<p class="submit">
<input id="submit" class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
</p>
</form>