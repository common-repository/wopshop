<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('deliverytimes');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_DELIVERY_TIME); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_DELIVERY_TIME_NEW); ?></a>
    </h2>
    <form id="listing" class="adminForm" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="90%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_DELIVERY_TIME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->filter_order == 'id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&filter_order=id&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($this->rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($this->rows as $index=>$row){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
                        </th>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($row->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&task=edit&row='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                
                                </span>
                                <span class="trash">
                                   <a class="submitdelete" title="<?php echo esc_html(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&task=delete&rows[]='.$row->id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                               </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_html($row->id); ?>
                        </td>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="deliverytimes" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


