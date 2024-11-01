<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="col100">
    <fieldset class="adminform">
        <table class="admintable" width = "100%" >
            <tr>
                <td style="width:250px;" class="key">
                    <?php echo esc_html(WOPSHOP_TESTMODE)?>
                </td>
                <td>
                    <?php
                    echo WopshopHtml::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', esc_attr($params['testmode'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                    <?php echo wp_kses_post(WopshopHtml::tooltip(WOPSHOP_PAYPAL_TESTMODE_DESCRIPTION));?>
                </td>
            </tr>
            <tr>
                <td  class="key">
                    <?php echo esc_html(WOPSHOP_PAYPAL_EMAIL)?>
                </td>
                <td>
                    <input type = "text" class = "inputbox" name = "pm_params[email_received]" size="45" value = "<?php echo esc_attr($params['email_received']) ?>" />
                    <?php echo wp_kses_post(WopshopHtml::tooltip(WOPSHOP_PAYPAL_EMAIL_DESCRIPTION));?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_TRANSACTION_END)?>
                </td>
                <td>
                    <?php
                    echo WopshopHtml::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', esc_attr($params['transaction_end_status'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo " " . wp_kses_post(WopshopHtml::tooltip(WOPSHOP_PAYPAL_TRANSACTION_END_DESCRIPTION));
                    ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_TRANSACTION_PENDING)?>
                </td>
                <td>
                    <?php
                    echo WopshopHtml::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', esc_attr($params['transaction_pending_status'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo " " . wp_kses_post(WopshopHtml::tooltip(WOPSHOP_PAYPAL_TRANSACTION_PENDING_DESCRIPTION)); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_TRANSACTION_FAILED)?>
                </td>
                <td>
                    <?php
                    echo WopshopHtml::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', esc_attr($params['transaction_failed_status'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo " " . wp_kses_post(WopshopHtml::tooltip(WOPSHOP_PAYPAL_TRANSACTION_FAILED_DESCRIPTION));
                    ?>
                </td>
            </tr>
          <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_CHECK_DATA_RETURN)?>
                </td>
                <td>
                    <?php echo WopshopHtml::_('select.booleanlist', 'pm_params[checkdatareturn]', 'class = "inputbox"', esc_attr($params['checkdatareturn'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped)?>
                </td>
            </tr>
            <tr>
              <td class="key">
                <?php echo 'Return method GET';?>
              </td>
              <td>
                <?php              
                echo WopshopHtml::_('select.booleanlist', 'pm_params[rm1]', 'class = "inputbox"', esc_attr($params['rm1'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped   
                ?>
              </td>
            </tr>            
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_OVERRIDING_ADDRESSES )?>
                </td>
                <td>
                    <?php echo WopshopHtml::_('select.booleanlist', 'pm_params[address_override]', 'class = "inputbox"', esc_attr($params['address_override'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped )?>
                </td>
            </tr>
            <tr>
              <td class="key">
                <?php echo esc_html(WOPSHOP_NOTIFY_URL_SEF)?>
              </td>
              <td>
                <?php              
                echo WopshopHtml::_('select.booleanlist', 'pm_params[notifyurlsef]', 'class = "inputbox"', esc_attr($params['notifyurlsef'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
              </td>
            </tr>
        </table>
    </fieldset>
</div>
<div class="clr"></div>