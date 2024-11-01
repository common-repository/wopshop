<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
$config = $this->config;
wopshopDisplaySubmenuConfigs('currency');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<input type="hidden" name="layout" value="currency">
<input type="hidden" value="2" name="tabs">
<div class="col100">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_CURRENCY_PARAMETERS )?></legend>
<table class="admintable">
  <tr>
    <td class="key" >
      <?php echo esc_html(WOPSHOP_MAIN_CURRENCY);?>
    </td>
    <td>
      <?php echo $lists['currencies']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
  </tr>
  <tr>
    <td class="key" >
      <?php echo esc_html(WOPSHOP_DECIMAL_COUNT);?>
    </td>
    <td>
      <input type="text" name="decimal_count" id="decimal_count" value ="<?php echo esc_attr($config->decimal_count)?>" />
    </td>
  </tr>
  <tr>
    <td class="key" >
      <?php echo esc_html(WOPSHOP_DECIMAL_SYMBOL);?>
    </td>
    <td>
      <input type="text" name="decimal_symbol" id="decimal_symbol" value ="<?php echo esc_attr($config->decimal_symbol)?>" />
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td class="key" >
      <?php echo esc_html(WOPSHOP_THOUSAND_SEPARATOR); ?>
    </td>
    <td>
      <input type="text" name="thousand_separator" id="thousand_separator" value ="<?php echo esc_attr($config->thousand_separator)?>" />
    </td>
    <td>
    </td>
  </tr>
  <tr>
    <td class="key" >
      <?php echo esc_html(WOPSHOP_CURRENCY_FORMAT); ?>
    </td>
    <td>
      <?php echo $lists['format_currency']; echo " "  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </td>
    <td>
    </td>
  </tr>
  <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</table>
</fieldset>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<p class="submit">
    <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
</p> 
</form>