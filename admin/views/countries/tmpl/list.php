<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('countries');
$saveOrder = $this->filter_order_Dir=="desc" && $this->filter_order=="ordering";
$count = count($this->rows);
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_COUNTRY); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_COUNTRY); ?></a>
    </h2>
		<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="50%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_COUNTRY); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_CODE); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_CODE); ?> 2
                    </th>
                    <?php if($this->filter_order == 'ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&filter_order=ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>						
                    </th>
					
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="100">
        <?php if ($saveOrder){?>
						<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
        <?php }?>						
                    </th>					
                    <?php if($this->filter_order == 'country_publish') $class_publish = 'sorted'; else $class_publish = 'sortable';?>
                    <th class="manage-column column-order_status <?php echo esc_attr($class_publish); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&filter_order=country_publish&filter_order_Dir='.$this->filter_order_Dir))?>">
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
                    foreach($this->rows as $index=>$country){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($country->country_id); ?>" type="checkbox" value="<?php echo esc_attr($country->country_id); ?>" name="rows[]">
                        </th>
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <?php echo esc_html($country->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=edit&row='.$country->country_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_html(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=delete&rows[]='.$country->country_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_html($country->country_code); ?>
                        </td>
                        <td class="code2-column " scope="col">
                            <?php echo esc_html($country->country_code_2); ?>
                        </td>
						<td align="right" width="10">
						<?php
							 if ($index != 0 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=order&id=' . $country->country_id . '&order=up&number=' . $country->ordering)).'"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="left" width="10">
						<?php
							 if ($index!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=order&id=' . $country->country_id . '&order=down&number=' . $country->ordering)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="center">
						 <input type="text" name="order[]" id="ord<?php echo esc_attr($country->country_id);?>" size="3" value="<?php echo esc_attr($country->ordering)?>" <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
						</td>						
						<td class="center">
							<?php echo $published=($country->country_publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=unpublish&rows[]='.$country->country_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=publish&rows[]='.$country->country_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>						
                        <td class="code-column " scope="col">
                            <?php print  esc_html($country->country_id);?>
                        </td>						
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <input type="hidden" value="options" name="page">
        <input type="hidden" value="countries" name="tab">
		<input type = "hidden" name = "task" value = "<?php echo esc_html(WopshopRequest::getVar('task', 0))?>" />
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


