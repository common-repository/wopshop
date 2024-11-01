<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/
?>
<div class="wrap">
    <h2><?php echo esc_html(WOPSHOP_EDIT_TAX_EXT); ?></h2>
    <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&task=save&back_tax_id='.$this->back_tax_id))?>" id="edittax">
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="col100">
        <fieldset class="adminform">
        <table width="100%" class="admintable">
           <tr>
             <td class="key" style="width:250px;">
               <?php echo esc_html(WOPSHOP_TITLE);?>*
             </td>
             <td>
               <?php print $this->lists['taxes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
           </tr>
           <tr>
            <td class="key">
                <?php echo esc_html(WOPSHOP_COUNTRY)."*<br/><br/><span style='font-weight:normal'>".WOPSHOP_MULTISELECT_INFO."</span>"; ?>
            </td>
            <td>
                <?php echo $this->lists['countries']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </td>
           </tr>
           <tr>
             <td  class="key">
               <?php echo esc_html(WOPSHOP_TAX);?>*
             </td>
             <td>
               <input type="text" class="inputbox" name="tax" value="<?php echo esc_attr($this->tax->tax);?>" /> % <?php echo WopshopHtml::tooltip(WOPSHOP_VALUE_TAX_INFO); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
           </tr>
           <tr>
             <td class="key">
               <?php 
                if ($this->config->ext_tax_rule_for==1) 
                    echo esc_html(WOPSHOP_USER_WITH_TAX_ID_TAX);
                else
                    echo esc_html(WOPSHOP_FIRMA_TAX);
                ?>*
             </td>
             <td>
               <input type="text" class="inputbox" name="firma_tax" value="<?php echo esc_attr($this->tax->firma_tax);?>" /> % <?php echo WopshopHtml::tooltip(WOPSHOP_VALUE_TAX_INFO); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
           </tr>
         </table>
        </fieldset>
        </div>
        <div class="clr"></div>
        <input type="hidden" name="id" value="<?php echo esc_attr($this->tax->id)?>" />
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php wp_nonce_field('taxext_edit','name_of_nonce_field'); ?>
        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
        </p> 
    </form>
</div>