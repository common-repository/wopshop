<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('orderstatus');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_ORDER_STATUSS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_ORDER_STATUS); ?></a>
    </h2>
    <form id="listing" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
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
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_NAME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_CODE); ?>
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
                    foreach($this->rows as $index=>$data){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($data->status_id); ?>" type="checkbox" value="<?php echo esc_attr($data->status_id); ?>" name="rows[]">
                        </th>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($data->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus&task=edit&row='.$data->status_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus&task=delete&rows[]='.$data->status_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_html($data->status_code); ?>
                        </td>
                        <?php /*<td class="status-column">
                            <?php if($data->publish) echo esc_html(WOPSHOP_ACTION_PUBLISHED); else echo esc_html(WOPSHOP_ACTION_UNPUBLISHED); ?>
                        </td>*/?>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="orderstatus" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


