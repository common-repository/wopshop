<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('freeattributes');

$rows = $this->rows;
$saveOrder = $this->filter_order_Dir=="desc" && $this->filter_order=="ordering";
$count = count ($rows);
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_ATTRIBUTES); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_ATTRIBUT); ?></a>
    </h2>
	<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes'))?>" method = "post" name = "adminForm">
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
            <th width="80%" id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                    <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th width="10%">
                <?php echo esc_html(WOPSHOP_REQUIRED); ?>
            </th>
			<?php if($this->filter_order == 'ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
			<th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
				<a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&filter_order=ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
					<span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
					<span class="sorting-indicator"></span>
				</a>						
			</th>

			<th id="saveorder" class="save_ordering center manage-column" scope="col" width="100">
<?php if ($saveOrder){?>
				<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
<?php }?>						
			</th>
			
            <?php if($this->filter_order == 'id') $class_name = 'sorted'; else $class_name = 'sortable';?>
            <th id="attr_id" class="manage-column column-attr_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="30px">
                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&filter_order=id&filter_order_Dir='.$this->filter_order_Dir))?>">
                    <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>			
        </tr>
    </thead>
    <?php 
        foreach ($rows as $index=>$row){
     ?>
        <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
            <th class="check-column" scope="col">
                <input id="attr_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
            </th>
            <td class="column-primary name-column">
                <strong><?php echo esc_html($row->name);?></strong>
                <div class="row-actions">
                    <span class="edit">
                    <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=edit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                    |
                    </span>
                    <span class="trash">
                        <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=delete&rows[]='.$row->id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                    </span>
                </div>
            </td>
            <td>
                <?php if ($row->required){?>
                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-allow.png')?>" >
                <?php }?>
            </td>
			<td align="right" width="10">
			<?php
				 if ($index != 0 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=order&id=' . $row->id . '&order=up')).'"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			</td>
			<td align="left" width="10">
			<?php
				 if ($index!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=order&id=' . $row->id . '&order=down')).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			</td>
			<td align="center">
			 <input type="text" name="order[]" id="ord<?php echo esc_attr($row->id);?>" size="3" value="<?php echo esc_attr($row->ordering)?>" <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
			</td>			
			<td align="center">
                <?php print esc_html($row->id);?>
            </td>
        </tr>
    <?php  }  ?>
    </table>
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<input type="hidden" value="options" name="page">
	<input type="hidden" value="freeattributes" name="tab">
	<input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />		
    </form>
<br class="clear">
</div>