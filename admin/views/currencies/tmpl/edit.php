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
	<h2><?php echo  esc_html($this->currency->currency_id ? WOPSHOP_EDIT_CURRENCY . ' / ' . $this->currency->currency_name :  WOPSHOP_NEW_CURRENCY); ?></h2>
    <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=save'))?>" id="editcurrency" class="form-horizontal">
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="col100">
            <fieldset class="adminform">
                <table width="100%" class="admintable">
                    <tr>
                      <td class="key" style="width: 250px;">
                        <?php echo esc_html(WOPSHOP_ACTION_PUBLISH);?>
                      </td>
                      <td>
                       <input id="currency_publish" type="checkbox" class="form-control" name="currency_publish" value="1" <?php if ($this->currency->currency_publish) echo 'checked="checked"';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> >
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_TITLE);?>*
                      </td>
                      <td>
                        <input id="currency_name" type="text" class="form-control" value="<?php echo esc_attr($this->currency->currency_name);?>" name="currency_name">
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_ORDERING_CURRENCY);?>*
                      </td>
                      <td>
                        <?php echo $this->lists['order_currencies']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_CODE);?>*
                      </td>
                      <td>
                        <input id="currency_code" type="text" class="form-control" value="<?php echo esc_attr($this->currency->currency_code);?>" name="currency_code">
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                       <?php echo esc_html(WOPSHOP_CODE." (ISO)");?>*:
                      </td>
                      <td>
                        <input id="currency_code_iso" type="text" class="form-control" value="<?php echo esc_attr($this->currency->currency_code_iso);?>" name="currency_code_iso">
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_CODE." (".WOPSHOP_NUMERIC.")");?>:
                      </td>
                      <td>
                        <input id="currency_code_num" type="text" class="form-control" value="<?php echo esc_attr($this->currency->currency_code_num);?>" name="currency_code_num">
                      </td>
                    </tr>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_VALUE_CURRENCY);?>
                      </td>
                      <td>
                        <input id="currency_value" type="text" class="form-control" value="<?php echo esc_attr($this->currency->currency_value ? $this->currency->currency_value : 1); ?>" name="currency_value">
                      </td>
                    </tr>
                </table>
            </fieldset>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        
        <input type="hidden" value="<?php echo esc_attr($this->currency->currency_id); ?>" name="currency_id">
        <?php wp_nonce_field('coutry_edit','name_of_nonce_field'); ?>
        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
        </p> 
    </form>
</div>