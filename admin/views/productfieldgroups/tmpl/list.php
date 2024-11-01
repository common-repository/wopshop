<?php

if( !defined( 'ABSPATH' ) ) {
    
    exit; // Exit if accessed directly
    
}
wopshopDisplaySubmenuOptions('productfields');
$rows  = $this->rows;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_PRODUCT_EXTRA_FIELDS_GROUPS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
    </h2>
    
    <form id="listing" class="adminForm" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups'))?>" method="post" name="adminForm">
        
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_TITLE); ?>
                    </th>
                    <th colspan="2" id="ordering" scope="col" width="10%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&filter_order=A.attr_ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips">
                                <?php echo esc_html(WOPSHOP_ORDERING); ?>
                            </span>
                            <span class="sorting-indicator"></span>
                        </a>						
                    </th>
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="8%">
                        <a class="saveorder" onclick="saveorder();" href="#">
                            <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/>
                        </a>					
                    </th>
                    <th width="60px" align="center">
                       <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>
                </tr>
            </thead>
    
            <?php if(!count( $rows )) { ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="6"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
            <?php } else {
                foreach( $rows as $index => $row ) { ?>
                    <tr class="<?php if( $index % 2 ) echo 'alt';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
                        </th>
                        <td>
                            <strong><?php echo esc_html($row->name); ?></strong>
                            <div class="row-actions">
                                <span class="edit">
                                    <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&task=edit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                    |
                                </span>
                                <span class="trash">
                                    <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&task=delete&rows[]='.$row->id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td align="right" width="10">
                            <?php
                                if( $index != 0 ) {
                                    echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&task=orderup&id=' . $row->id)) . '"><img alt="' . WOPSHOP_UP . '" src="' . esc_url(WOPSHOP_PLUGIN_URL . 'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            ?>
                        </td>
                        <td align="left" width="10">
                            <?php
                                if( $index != count($rows) - 1 ) {
                                    echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups&task=orderdown&id=' . $row->id)) . '"><img alt="' . WOPSHOP_DOWN . '" src="' . esc_url(WOPSHOP_PLUGIN_URL . 'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            ?>
                        </td>
                        <td align="center">
                            <input type="text" name="order[]" id="ord<?php echo esc_attr($row->id); ?>" size="5" value="<?php echo esc_attr($row->ordering); ?>" class="inputordering" style="text-align: center;" />
                        </td>
                        <td align="center">
                            <?php print esc_html($row->id);?>
                        </td>
                    </tr>
                <?php
                }
            } ?>
        </table>
        
        <input type="hidden" name="task" value="<?php echo esc_attr(WopshopRequest::getVar( 'task', 0 )); ?>" />
        
    </form>

    <br class="clear">
    
</div>

<div clas="submit">
    <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
</div>