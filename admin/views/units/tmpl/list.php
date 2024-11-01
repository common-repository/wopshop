<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('units');

$i = 0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_UNITS_MEASURE); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units&task=edit'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_ADD); ?></a>
    </h2>
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <?php echo $this->tmp_html_start;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>    
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>                    
                    <th class="manage-column column-cb check-column wopshop-admin-list-check" width="50">
                        <input id="cb-select-all-1" type="checkbox" />
                    </th>
                    <th align="left">
                        <?php echo esc_html(WOPSHOP_TITLE); ?>
                    </th>
                    <th style="width: 40px; text-align: center;">
                        <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>
                </tr>
            </thead> 
            <tbody>
                <?php if (count($this->rows) == 0) : ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($this->rows as $index => $row) : ?>
                        <tr class="<?php echo ($index % 2) ? 'alt' : '';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                            <td class="check-column wopshop-admin-list-check">
                                <input id="cid_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="cid[]" />
                            </td>
                            <td>
                                <strong>
                                <?php echo esc_html($row->name); ?>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit">
                                    <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units&task=edit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                    |
                                    </span>
                                    <span class="trash">
                                    <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units&task=delete&rows[]='.$row->id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                    </span>
                                </div>
 
                            </td>
                            <td align="center">
                                <?php echo esc_html($row->id);?>
                            </td>					
                        </tr>
                    <?php endforeach; ?>			
                <?php endif; ?> 
            </tbody>
        </table>
        <?php echo $this->tmp_html_end;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>	    
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="units" name="tab">
        <input type="hidden" name="task" value="<?php echo esc_attr(WopshopRequest::getVar( 'task', 0 )); ?>" />        
    </form>
</div>
