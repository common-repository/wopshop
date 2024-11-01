<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('labels');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_PRODUCT_LABELS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_PRODUCT_LABEL_NEW); ?></a>
    </h2>
    <?php /*<form id="listing" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">*/?>
    <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels'))?>">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php //echo $this->pagination;?>
            <br class="clear">
            
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col" width="40px">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th width="80%" id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column column-order_status" width="120px">
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_IMAGE); ?></span>
                        <span class="sorting-indicator"></span>
                    </th>
                    <?php if($this->filter_order == 'id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&filter_order=id&filter_order_Dir='.$this->filter_order_Dir))?>">
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
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&task=edit&row='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&task=delete&rows[]='.$row->id.'&action=-1'))?>"><?php echo esc_attr(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <th class="image-column">
                            <?php if($row->image){?>
                            <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'files/img_labels/'.$row->image)?>">
                            <?php } ?>
                        </th>
                        <th class="id-column">
                            <?php echo esc_html($row->id);?>
                        </th>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="productlabels" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


