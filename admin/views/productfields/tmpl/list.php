<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('productfields');
$rows      = $this->rows;
$count     = count( $rows );
$i         = 0;
$lists     = $this->lists;
$saveOrder = $this->filter_order_Dir == "desc" && $this->filter_order == "F.ordering";
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_PRODUCT_EXTRA_FIELDS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldgroups'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_GROUP); ?></a>
    </h2>
    <form id="listing" method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields'))?>" name ="ExtraFieldsFilter" >
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <p class="search-box wopshop_admin_products_filters_elements"><?php echo esc_html(WOPSHOP_GROUP). ": " . $lists['group'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>&nbsp;&nbsp;&nbsp;</p>
        <?php print $this->tmp_html_filter;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
    <form id="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields'))?>" method = "post" name = "adminForm"  class="adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table width="100%" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if( $this->filter_order == 'name' ) $class_name='sorted'; else $class_name='sortable'; ?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if( $this->filter_order == 'F.type' ) $class_name='sorted'; else $class_name='sortable'; ?>
                    <th id="order_type" class="manage-column column-order_type <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&filter_order=F.type&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TYPE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th align="left">
                        <?php echo esc_html(WOPSHOP_OPTIONS); ?>
                    </th>
                    <th align="left">
                        <?php echo esc_html(WOPSHOP_CATEGORIES); ?>
                    </th>
                    <?php if( $this->filter_order == 'groupname' ) $class_name='sorted'; else $class_name='sortable'; ?>
                    <th id="order_type" class="manage-column column-order_type <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&filter_order=groupname&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_GROUP); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>				
                    <?php if( $this->filter_order == 'F.ordering' ) $class_name='sorted'; else $class_name='sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&filter_order=F.ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="100">
                    <?php if( $saveOrder ) { ?>
                        <a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
                    <?php } ?>						
                    </th>
                    <?php if( $this->filter_order == 'id' ) $class_name='sorted'; else $class_name='sortable'; ?>
                    <th id="order_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" align="center" width="50px">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&filter_order=id&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <?php foreach( $rows as $index => $row ) { ?>
            <tr class="<?php if( $index % 2 ) echo 'alt';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                <th class="check-column" scope="col">
                  <input id="user_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
                </th>
                <td class="column-primary name-column" scope="col">
                    <?php if( !$row->count_option && $row->type == 0 ) { ?><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-denyinactive.png')?>" alt="" /><?php } ?>
                    <strong><?php echo esc_html($row->name); ?></strong>
                    <div class="row-actions">
                        <span class="edit">
                            <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=edit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                            |
                        </span>
                        <span class="trash">
                            <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=delete&rows[]='.$row->id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                        </span>
                    </div>
                </td>
                <td>
                    <?php print $this->types[$row->type];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
                <td>
                    <?php if( $row->type == 0 ) { ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldvalues&field_id='.$row->id))?>"><?php echo esc_html(WOPSHOP_OPTIONS); ?></a>
                        (<?php if(isset($this->vals[$row->id]) && is_array( $this->vals[$row->id] ) ) echo implode( ", ", $this->vals[$row->id]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
                <td>
                    <?php print $row->printcat;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>        
                </td>
                <td>
                    <?php print esc_html($row->groupname); ?>
                </td>
                <td align="right" width="10">
                    <?php
                        if( $index != 0 && $saveOrder ) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=order&id=' . $row->id . '&order=up')).'"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL . 'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </td>
                <td align="left" width="10">
                    <?php
                        if( $index != $count - 1 && $saveOrder ) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=order&id=' . $row->id . '&order=down')).'"><img alt="' . WOPSHOP_DOWN . '" src="' . esc_url(WOPSHOP_PLUGIN_URL . 'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </td>
                <td align="center">
                    <input type="text" name="order[]" id="ord<?php echo esc_attr($row->id); ?>" size="3" value="<?php echo esc_attr($row->ordering); ?>" <?php if( !$saveOrder ) echo 'disabled';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
                </td>		   
                <td align="center">
                    <?php print esc_html($row->id); ?>
                </td>
            </tr>
        <?php
         $i++;
         }
        ?>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<input type="hidden" value="options" name="page">
	<input type="hidden" value="productfields" name="tab">
	<input type="hidden" name="task" value="<?php echo esc_attr(WopshopRequest::getVar( 'task', 0 )); ?>" />	
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>