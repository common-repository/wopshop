<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="wrap">
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
        <div class="buttons">
            <input type="submit" class="button-primary" value="<?php echo esc_html(WOPSHOP_IMPORT." '".$name."'");?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport'))?>" class="button-secondary"><?php echo esc_html(WOPSHOP_BACK_TO.' "'.WOPSHOP_PANEL_IMPORT_EXPORT.'"'); ?></a>
        </div>
        <br/>
        <input type="hidden" name="ie_id" value="<?php print esc_html($ie_id);?>" />

        <?php print esc_html(WOPSHOP_FILE)?> (*.csv):
        <input type="file" name="file">
    </form>
</div>