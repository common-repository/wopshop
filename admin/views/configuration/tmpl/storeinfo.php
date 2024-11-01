<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
$config = $this->config;
$vendor = $this->vendor;
wopshopDisplaySubmenuConfigs('storeinfo');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<!--<input type="hidden" name="layout" value="storeinfo">-->
<input type="hidden" name="tabs" value="5">
<input type="hidden" name="vendor_id" value="<?php print esc_attr($vendor->id);?>">


<div class="col100" id="storeinfo">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_STORE_INFO )?></legend>
    <table class="admintable table-striped" width="100%" >
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_NAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="shop_name" value="<?php echo esc_attr($vendor->shop_name)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_COMPANY);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="company_name" value="<?php echo esc_attr($vendor->company_name)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_URL);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="url" value="<?php echo esc_attr($vendor->url)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_LOGO);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="logo" value="<?php echo esc_attr($vendor->logo)?>" />
     </td>
    </tr>    
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_ADRESS);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="adress" value="<?php echo esc_attr($vendor->adress)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_CITY);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="city" value="<?php echo esc_attr($vendor->city)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_ZIP);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="zip"  value="<?php echo esc_attr($vendor->zip)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_STATE);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="state" value="<?php echo esc_attr($vendor->state)?>" />
     </td>
    </tr>
    <tr>
     <td class="key">
       <?php echo esc_html(WOPSHOP_STORE_COUNTRY);?>
     </td>
     <td>
       <?php echo $lists['countries']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
     </td>
    </tr>    
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100" id="contactinfo">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_CONTACT_INFO )?></legend>
    <table class="admintable table-striped" width="100%" >
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_CONTACT_FIRSTNAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="f_name" value="<?php echo esc_attr($vendor->f_name)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_CONTACT_LASTNAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="l_name" value="<?php echo esc_attr($vendor->l_name)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_CONTACT_MIDDLENAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="middlename" value="<?php echo esc_attr($vendor->middlename)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_CONTACT_PHONE);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="phone" value="<?php echo esc_attr($vendor->phone)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_CONTACT_FAX);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="fax" value="<?php echo esc_attr($vendor->fax)?>" />
     </td>
    </tr> 
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_EMAIL);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="email" value="<?php echo esc_attr($vendor->email)?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100" id="bankinfo">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_BANK) ?></legend>
    <table class="admintable table-striped" width="100%" >
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_BANK_NAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_bank_info" value="<?php echo esc_attr($vendor->benef_bank_info)?>" />
     </td>
    </tr>

    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_BIC);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_bic" value="<?php echo esc_attr($vendor->benef_bic)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_CONTO);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_conto" value="<?php echo esc_attr($vendor->benef_conto)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_PAYEE);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_payee" value="<?php echo esc_attr($vendor->benef_payee)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_IBAN);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_iban" value="<?php echo esc_attr($vendor->benef_iban)?>" />
     </td>
    </tr>
	<tr>
		<td class="key">
			<?php echo esc_html(WOPSHOP_BIC_BIC);?>
		</td>
		<td>
			<input size="55" type = "text" class = "inputbox" name = "benef_bic_bic" value = "<?php echo esc_attr($vendor->benef_bic_bic)?>" />
		</td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_BENEF_SWIFT);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="benef_swift" value="<?php echo esc_attr($vendor->benef_swift)?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100" id="bank2info">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_INTERM_BANK )?></legend>
    <table class="admintable table-striped" width="100%" >
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_INTERM_NAME);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="interm_name" value="<?php echo esc_attr($vendor->interm_name)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_INTERM_SWIFT);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="interm_swift" value="<?php echo esc_attr($vendor->interm_swift)?>" />
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100" id="taxinfo">
<fieldset class="adminform">
    <table class="admintable table-striped" width="100%" >
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_IDENTIFICATION_NUMBER);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="identification_number" value="<?php echo esc_attr($vendor->identification_number)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_TAX_NUMBER);?>
     </td>
     <td>
       <input size="55" type="text" class="inputbox" name="tax_number" value="<?php echo esc_attr($vendor->tax_number)?>" />
     </td>
    </tr>
    <tr>
     <td  class="key">
       <?php echo esc_html(WOPSHOP_ADDITIONAL_INFORMATION);?>
     </td>
     <td>
        <textarea rows="5" cols="55" name="additional_information"><?php echo esc_html($vendor->additional_information)?></textarea>
     </td>
    </tr>
    </table>
</fieldset>
</div>
<div class="clr"></div>

<div class="col100" id="pdfinfo">
<fieldset class="adminform">
    <legend><?php echo esc_html(WOPSHOP_PDF_CONFIG )?></legend>
    <table class="admintable table-striped" width="100%" >
    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_PDF_HEADER)?>
    </td>
    <td>
        <input size="55" type="file" name="header" value="" />
    </td>
    </tr>

    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_IMAGE_WIDTH)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_header_width]" value="<?php echo esc_attr($config->pdf_header_width)?>" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_IMAGE_HEIGHT)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_header_height]" value="<?php echo esc_attr($config->pdf_header_height)?>" />
    </td>
    </tr>
    <tr>
    <td> </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_PDF_FOOTER)?>
    </td>
    <td>
        <input size="55" type="file" name="footer" value="" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_IMAGE_WIDTH)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_footer_width]" value="<?php echo esc_attr($config->pdf_footer_width)?>" />
    </td>
    </tr>
    <tr>
    <td  class="key">
       <?php echo esc_html(WOPSHOP_IMAGE_HEIGHT)?>
    </td>
    <td>
        <input size="55" type="text" class="inputbox" name="pdf_parameters[pdf_footer_height]" value="<?php echo esc_attr($config->pdf_footer_height)?>" />
    </td>
    </tr>
    <tr>
    <td></td>
    <td >
        <?php print esc_html(WOPSHOP_PDF_PREVIEW_INFO1);?>
        <a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=preview_pdf&config_id='.$config->id))?>"><?php echo esc_html(WOPSHOP_PDF_PREVIEW)?></a>
    </td>
    </tr>
    </table>

</fieldset>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<p class="submit">
<input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
</p>
</form>