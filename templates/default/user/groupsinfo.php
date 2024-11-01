<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop" id="wshop_plugin">
    <h1><?php print esc_html(WOPSHOP_USER_GROUPS_INFO)?></h1>
    
    <?php echo $this->_tmpl_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    <table class="groups_list">
    <tr>
        <th class="title"><?php print esc_html(WOPSHOP_TITLE)?></th>
        <th class="discount"><?php print esc_html(WOPSHOP_DISCOUNT)?></th>
    </tr>
    <?php foreach($this->rows as $row) : ?>
        <tr>
            <td class="title"><?php print esc_html($row->name)?></td>
            <td class="discount"><?php print esc_html(floatval($row->usergroup_discount))?>%</td>
        </tr>
    <?php endforeach; ?>
    </table>
    
    <?php echo $this->_tmpl_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>