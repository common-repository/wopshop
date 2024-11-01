<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('currencies');
$saveOrder = $this->filter_order_Dir=="desc" && $this->filter_order=="currency_ordering";
$count = count($this->rows);
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_CURRENCY); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_CURRENCY); ?></a>
    </h2>
	<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies'))?>" method = "post" name = "adminForm">
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
                    <?php if($this->filter_order == 'currency_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="50%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&filter_order=currency_name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_DEFAULT); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_VALUE_CURRENCY); ?>
                    </th>
                    <?php if($this->filter_order == 'currency_ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&filter_order=currency_ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>						
                    </th>
					
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="8%">
        <?php if ($saveOrder){?>
						<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
        <?php }?>						
                    </th>
					
                    <?php if($this->filter_order == 'currency_publish') $class_publish = 'sorted'; else $class_publish = 'sortable';?>
                    <th class="manage-column column-order_status <?php echo esc_attr($class_publish); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&filter_order=currency_publish&filter_order_Dir='.$this->filter_order_Dir))?>">
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
                <?php if(count($this->rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="9"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($this->rows as $k=>$v){?>
                    <tr class="<?php if($k%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($v->currency_id); ?>" type="checkbox" value="<?php echo esc_attr($v->currency_id); ?>" name="rows[]">
                        </th>
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <?php echo esc_html($v->currency_name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=edit&rows='.$v->currency_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_html(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=delete&rows[]='.$v->currency_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php 
                            if($v->currency_id == $this->config->mainCurrency) echo '<center><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-default.png').'" ></center>'; 
                            else echo '<center><a href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=setdefault&currency_id='.$v->currency_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-notdefault.png').'" ></a></center>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </td>
                        <td class="code2-column " scope="col">
                            <?php echo esc_html($v->currency_value); ?>
                        </td>
						<td align="right" width="10">
						<?php
							 if ($k != 0 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=order&id=' . $v->currency_id . '&order=up&number=' . $v->currency_ordering)) . '"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="left" width="10">
						<?php
							 if ($k!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=order&id=' . $v->currency_id . '&order=down&number=' . $v->currency_ordering)) . '"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						</td>
						<td align="center">
						 <input type="text" name="order[]" id="ord<?php echo esc_attr($v->currency_id);?>" size="3" value="<?php echo esc_attr($v->currency_ordering)?>" <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
						</td>						
						
						
						<td class="center">
							<?php echo $published=($v->currency_publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=unpublish&rows[]='.$v->currency_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=currencies&task=publish&rows[]='.$v->currency_id)).'"><img  src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
						<td class="center">
						 <?php echo esc_html($v->currency_id); ?>
						</td>						                       
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="currencies" name="tab">
		<input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>