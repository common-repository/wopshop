<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<div class="wrap wshop-content wshop-info">
   <?php echo  wp_kses_post($this->tmp_html_start); ?>
    <table width="100%" class="wshop-table">
        <tr>
            <td width="50%" valign="top" style="padding:10px;">
                <p style="margin-top:0px;">Anschrift und andere Angaben zum Unternehmen:<br>
                    <br>
                    <strong>MAXX <em>marketing GmbH</em></strong>
                    <br>Englschalkinger Str. 224<br>
                    D-81927 München<br><br>
                    Tel: +49 (0)89 - 929286-0<br>
                    Fax:+49 (0)89 - 929286-75<br>
                    eMail: <strong><a class="link" href="mailto:anfrage@agentur-wp.com">anfrage@agentur-wp.com</a></strong><br><br>
                    </p>
                <p><strong>Steueridentifikationsnummer:<br></strong>
                    DE221510498<br><br>
                    <strong>Umsatzsteuer Nummer:<br></strong>
                    143/160/40099
                    <br><br>
                </p>
                <p>
                    <strong>Geschaftsfuhrer:</strong> 
                    <br>Klaus Huber
                </p>
            </td>
            <td valign="top" style="padding:10px;">
                <div style="padding-bottom:20px;">
                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/logo.png')?>" />
                    <div style="padding-top:5px;font-size:14px;"><b>Version <?php echo esc_html( $this->version ); ?></b></div>
                </div>
                <div class="info-row clearfix">
                    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                    <div><a href="https://www.agentur-wp.com/" target="_blank">https://www.agentur-wp.com</a></div>
                </div>
                <div class="info-row clearfix">
                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                    <div><a href="mailto:anfrage@agentur-wp.com">anfrage@agentur-wp.com</a></div>
                </div>
                <div class="info-row clearfix">
                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                    <div><a href="https://www.agentur-wp.com/forum" target="_blank">Hilfe / Support</a></div>
                </div>
                <div class="info-row clearfix">
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    <div><a href="https://www.agentur-wp.com/shop/category/view/extensions/" target="_blank">WPshopping extensions</a></div>
                </div>
            </td>
    </table>
    <?php do_action( 'wopshop_admin_displayaboutus_tmp_html_start' ); ?>
    <?php echo wp_kses_post($this->tmp_html_end); ?>
</div>