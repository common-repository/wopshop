<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('manufacturers');
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="ordering";
$count = count($this->rows);
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_MANUFACTURERS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_MANUFACTURER); ?></a>
    </h2>
		<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped">
            <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
					
                    <?php if($this->filter_order == 'ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&filter_order=ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>						
                    </th>
					
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="8%">
        <?php if ($saveOrder){?>
						<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
        <?php }?>						
                    </th>					
					
                    <?php if($this->filter_order == 'manufacturer_publish') $class_publish = 'sorted'; else $class_publish = 'sortable';?>
                    <th width="100" class="manage-column column-order_status <?php echo esc_attr($class_publish); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&filter_order=manufacturer_publish&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_STATUS); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th width="40" class="center">
                        <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>					
                </tr>
            </thead>
            <tbody id="the-list">
                <?php 
                    foreach($this->rows as $index=>$manufacturer){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <td class="check-column wopshop-admin-list-check" scope="col">
                            <input id="man_<?php echo esc_attr($manufacturer->manufacturer_id); ?>" type="checkbox" value="<?php echo esc_attr($manufacturer->manufacturer_id); ?>" name="rows[]">
                        </th>
                        <td class="column-primary" scope="col">
                            <strong>
                            <?php echo esc_html($manufacturer->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=edit&row='.$manufacturer->manufacturer_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_html(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=delete&rows[]='.$manufacturer->manufacturer_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
						
						<td align="right" width="10">
						<?php
							 if ($index != 0 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=order&id=' . $manufacturer->manufacturer_id . '&order=up&number=' . $manufacturer->ordering)) . '"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="left" width="10">
						<?php
							 if ($index!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=order&id=' . $manufacturer->manufacturer_id . '&order=down&number=' . $manufacturer->ordering)) . '"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="center">
						 <input type="text" name="order[]" id="ord<?php echo esc_attr($manufacturer->manufacturer_id);?>" size="3" value="<?php echo esc_attr($manufacturer->ordering)?>" <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
						</td>						
						
						<td class="center">
							<?php echo $published=($manufacturer->manufacturer_publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=unpublish&rows[]='.$manufacturer->manufacturer_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=manufacturers&task=publish&rows[]='.$manufacturer->manufacturer_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>						
						
					<td class="center">
					 <?php print esc_html($manufacturer->manufacturer_id);?>
					</td>						
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <input type="hidden" value="options" name="page">
        <input type="hidden" value="manufacturers" name="tab">
		<input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


