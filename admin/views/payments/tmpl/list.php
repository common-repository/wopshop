<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('payments');
$saveOrder = $this->filter_order_Dir=="desc" && $this->filter_order=="payment_ordering";
$count = count($this->rows);
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_PAYMENTS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_PAYMENT); ?></a>
    </h2>
	<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments'))?>" method = "post" name = "adminForm">
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
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="50%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_CODE); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_ALIAS); ?>
                    </th>
                    <?php if($this->filter_order == 'payment_ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&filter_order=payment_ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>						
                    </th>
					
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="8%">
        <?php if ($saveOrder){?>
						<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
        <?php }?>						
                    </th>					
                    <?php if($this->filter_order == 'payment_publish') $class_publish = 'sorted'; else $class_publish = 'sortable';?>
                    <th class="manage-column column-order_status <?php echo esc_attr($class_publish); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&filter_order=payment_publish&filter_order_Dir='.$this->filter_order_Dir))?>">
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
                    foreach($this->rows as $k=>$v){?>
                    <tr class="<?php if($k%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($v->payment_id); ?>" type="checkbox" value="<?php echo esc_attr($v->payment_id); ?>" name="rows[]">
                        </th>
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <?php echo esc_html($v->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=edit&rows='.$v->payment_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=delete&rows[]='.$v->payment_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php 
                                echo esc_html($v->payment_code); 
                            ?>
                        </td>
                        <td class="code2-column " scope="col">
                            <?php echo esc_html($v->payment_class); ?>
                        </td>
						<td align="right" width="10">
						<?php
							 if ($k != 0 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=order&id=' . $v->payment_id . '&order=up')).'"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="left" width="10">
						<?php
							 if ($k!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=order&id=' . $v->payment_id . '&order=down')).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="center">
						 <input type="text" name="order[]" id="ord<?php echo esc_attr($v->payment_id);?>" size="3" value="<?php echo esc_attr($v->payment_ordering)?>" <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
						</td>							
						<td class="center">
							<?php echo $published=($v->payment_publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=unpublish&rows[]='.$v->payment_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=publish&rows[]='.$v->payment_id)).'"><img  src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>						
						<td class="center">
						 <?php echo esc_html($v->payment_id); ?>
						</td>						
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="payments" name="tab">
		<input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>