<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <form enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-update&task=update'))?>" method="post" name="adminForm" id="adminForm">
        <fieldset class="uploadform">
            <legend><?php echo esc_html(WOPSHOP_UPDATE_PACKAGE_FILE); ?></legend>
                <table>
                    <tr>
                        <td width="160">
                            <label for="install_package"><?php echo esc_html(WOPSHOP_UPDATE_PACKAGE_FILE); ?>:</label>
                        </td>
                        <td>
                            <input class="input_box" id="install_package" name="install_package" type="file" size="57" />
                            <input type="hidden" name="installtype" value="package" />
                            <input class="button" type="submit" onclick="doLoader();" value="<?php echo esc_attr(WOPSHOP_UPDATE_PACKAGE_UPLOAD); ?>" />
                        </td>
                    </tr>
                </table>
        </fieldset>   
    </form>
    <br /><br /><br />
    <form enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-update&task=update'))?>" method="post" name="adminForm" id="adminForm">
        <fieldset class="uploadform">
            <legend><?php echo esc_html(WOPSHOP_UPDATE_UPLOAD_FROM_URL_PACKAGE_FILE); ?></legend>
            <table>
                <tr>
                    <td width="160">
                        <label for="install_url"><?php echo esc_html(WOPSHOP_UPDATE_UPLOAD_FROM_URL_PACKAGE_FILE); ?>:</label>
                    </td>
                    <td>
                        <input class="input_box" id="install_url" name="install_url" type="text" value="http://" size="57" />
                        <input type="hidden" name="installtype" value="url" />
                        <input class="button" type="submit" value="<?php echo esc_attr(WOPSHOP_UPDATE_PACKAGE_UPLOAD); ?>" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>