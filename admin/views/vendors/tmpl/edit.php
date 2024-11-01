<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
$vendor=$this->vendor;
$lists=$this->lists;
?>

<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo esc_html($vendor->id ? WOPSHOP_VENDORS.' / '.$vendor->shop_name : WOPSHOP_VENDORS); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors&task=save'))?>" enctype="multipart/form-data">
            <div class="col100">
            <fieldset class="adminform">
            <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>    
            <table class="admintable">
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_PUBLISH);?>
                 </td>
                 <td>
                   <input type="checkbox" name="publish" value="1" <?php if ($vendor->publish) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                 </td>
               </tr>
                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_USER_FIRSTNAME);?>*
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="f_name" value="<?php echo esc_attr($vendor->f_name) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_USER_LASTNAME);?>*
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="l_name" value="<?php echo esc_attr($vendor->l_name) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_STORE_NAME);?>*
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="shop_name" value="<?php echo esc_attr($vendor->shop_name) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_STORE_COMPANY);?>*
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="company_name" value="<?php echo esc_attr($vendor->company_name) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_LOGO." (".WOPSHOP_URL.")");?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="80" name="logo" value="<?php echo esc_attr($vendor->logo) ?>" />
                  </td>
                </tr>    

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_URL);?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="80" name="url" value="<?php echo esc_attr($vendor->url) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_ADRESS)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="adress" value="<?php echo esc_attr($vendor->adress) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_CITY)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="city" value="<?php echo esc_attr($vendor->city) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_ZIP)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="zip" value="<?php echo esc_attr($vendor->zip) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_STATE)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="state" value="<?php echo esc_attr($vendor->state) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_COUNTRY)?>*
                  </td>
                  <td>
                    <?php echo $lists['country']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_TELEFON)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="phone" value="<?php echo esc_attr($vendor->phone) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_FAX)?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="fax" value="<?php echo esc_attr($vendor->fax) ?>" />
                  </td>
                </tr>

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_EMAIL)?>*
                  </td>
                  <td>
                    <input type="text" class="inputbox" size="40" name="email" value="<?php echo esc_attr($vendor->email) ?>" />
                  </td>
                </tr>  

                <tr>
                  <td class="key">
                    <?php echo esc_html(WOPSHOP_USER_ID." (".WOPSHOP_MANAGER.")")?>
                  </td>
                  <td>
                    <input type="text" class="inputbox" name="user_id" value="<?php echo esc_attr($vendor->user_id) ?>" />
                  </td>
                </tr>   

            </table>
            </fieldset>
            </div>
            <div class="clr"></div>

            <div class="col100">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_BANK) ?></legend>
                <table class="admintable" width="100%" >
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_BENEF_BANK_NAME);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="benef_bank_info" value="<?php echo esc_attr($vendor->benef_bank_info)?>" />
                 </td>
                </tr>

                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_BENEF_BIC);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="benef_bic" value="<?php echo esc_attr($vendor->benef_bic)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_BENEF_CONTO);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="benef_conto" value="<?php echo esc_attr($vendor->benef_conto)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_BENEF_PAYEE);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="benef_payee" value="<?php echo esc_attr($vendor->benef_payee)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
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
                 <td class="key">
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

            <div class="col100">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_INTERM_BANK )?></legend>
                <table class="admintable" width="100%" >
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_INTERM_NAME);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="interm_name" value="<?php echo esc_attr($vendor->interm_name)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
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

            <div class="col100">
            <fieldset class="adminform">
                <table class="admintable" width="100%" >
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_IDENTIFICATION_NUMBER);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="identification_number" value="<?php echo esc_attr($vendor->identification_number)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_TAX_NUMBER);?>
                 </td>
                 <td>
                   <input size="55" type="text" class="inputbox" name="tax_number" value="<?php echo esc_attr($vendor->tax_number)?>" />
                 </td>
                </tr>
                <tr>
                 <td class="key">
                   <?php echo esc_html(WOPSHOP_ADDITIONAL_INFORMATION);?>
                 </td>
                 <td>
                    <textarea rows="5" cols="55" name="additional_information"><?php echo esc_html($vendor->additional_information)?></textarea>
                 </td>
                </tr>
                <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </table>
                <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </fieldset>
            </div> 
            <div clas="submit">
                <p class="submit">
                    <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                    <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
                </p> 
            </div>
            <input type="hidden" value="<?php echo esc_attr($vendor->id); ?>" name="id">
       </form>        
    </div>
</div>