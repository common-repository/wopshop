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
<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=save' ) )?>" enctype="multipart/form-data" name="adminForm">
    <div class="wrap">
        <h2><?php echo esc_html(WOPSHOP_EDIT_ADDON ); ?>: <?php echo esc_html( $this->row->name ); ?></h2>
        <hr />
        <?php echo $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        
        <?php if ($this->config_file_exist) : ?>
            <?php include $this->config_file_patch; ?>
        <?php endif; ?>
        
        <div class="submit">
            <p class="submit">
                <input class="button button-primary" type="submit" value="<?php echo esc_html( WOPSHOP_ACTION_SAVE ); ?>" name="submit">
                <a class="button" href="<?php echo esc_url(admin_url( 'admin.php?page=wopshop-options&tab=addons' ) );?>"><?php echo esc_html( WOPSHOP_BACK ); ?></a>
            </p> 
        </div>
        <input type="hidden" value="<?php echo esc_attr( $this->row->id ); ?>" name="id">
        <?php wp_nonce_field('addon_edit'); ?>
        
        <?php echo $this->tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</form>