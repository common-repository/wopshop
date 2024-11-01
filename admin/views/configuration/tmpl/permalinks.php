<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuConfigs('permalinks');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php wp_nonce_field('config', 'config_nonce_field'); ?>
    <input type="hidden" value="11" name="tabs">
    <div class="wrap">
        <fieldset class="adminform">
            <legend><?php echo esc_html(WOPSHOP_PERMALINKS); ?></legend>
            <table class="admintable wp-list-table widefat striped">
                <tr>
                    <td class="key">
                        <?php echo esc_html(WOPSHOP_BASE_SHOP_PAGE); ?>
                    </td>
                    <td>
                        <?php echo $this->lists['shopBasePages']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="clear"></div>
    <?php print $this->tmp_html_end  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p class="submit">
        <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
    </p>
</form>