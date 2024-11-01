<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=save'))?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="ie_id" value="<?php print esc_attr($ie_id);?>" />
    <div class="buttons">
        <input type="submit" class="button-primary" value="<?php echo esc_attr(WOPSHOP_EXPORT." '".$name."'");?>">
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport'))?>" class="button-secondary"><?php echo esc_html(WOPSHOP_BACK_TO.' "'.WOPSHOP_PANEL_IMPORT_EXPORT.'"'); ?></a>
    </div>
    <br/>
    <?php print esc_html(WOPSHOP_FILE_NAME)?>: <input type="text" name="params[filename]" value="<?php print $ie_params['filename'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" size="45"><br/>
    <br/>
    <?php if($count) {?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="title" width ="10">
                      #
                    </th>
                    <th class="center" width="40%">
                        <?php echo esc_html(WOPSHOP_NAME); ?>
                    </th>
                    <th class="center">
                        <?php echo esc_html(WOPSHOP_DATE); ?>
                    </th>
                    <th class="center">
                        <?php echo esc_html(WOPSHOP_DELETE); ?>
                    </th>                  
                </tr>
            </thead>
            <tbody id="the-list">
            <?php
            $i=0;
            foreach($files as $row){
            ?>
            <tr class="row<?php echo esc_html($i % 2);?>">
                <td>
                    <?php echo esc_html($i+1);?>
                </td>    
                <td>
                    <a target="_blank" href="<?php echo esc_url($config->importexport_live_path.$_importexport->alias."/".$row)?>"><?php echo esc_html($row);?></a>
                </td>
                <td>
                    <?php print esc_html(date("d.m.Y H:i:s", filemtime($config->importexport_path.$_importexport->alias."/".$row))); ?>
                </td>    
                <td class="center">
                    <a href='<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=filedelete&ie_id='.$ie_id.'&file='.$row))?>' onclick="return confirm('<?php print esc_attr(WOPSHOP_DELETE)?>');">
                        <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/trash.png')?>">
                    </a>
                </td>
            </tr>
            <?php
            $i++;  
            }
            ?>
            </tbody>
        </table>    
    <?php }?>

    </form>
</div>    