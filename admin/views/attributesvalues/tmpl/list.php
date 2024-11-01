<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('attributes');

$rows        = $this->rows;
$count       = count ($rows);
$saveOrder   = $this->filter_order_Dir=="desc" && $this->filter_order=="value_ordering";
$this->page .= '&attr_id=' . $this->attr_id;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html( WOPSHOP_LIST_ATTRIBUT_VALUES ); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&task=edit&attr_id='.$this->attr_id))?>" class="add-new-h2"><?php echo esc_html( WOPSHOP_NEW ); ?></a>
    </h2>

    <form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&attr_id='.$this->attr_id))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" >
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&filter_order=name&filter_order_Dir='.$this->filter_order_Dir.'&attr_id='.$this->attr_id))?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_NAME_ATTRIBUT_VALUE ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th width="100px" >
                        <?php echo esc_html( WOPSHOP_IMAGE_ATTRIBUT_VALUE ); ?>
                    </th>
                    <?php if($this->filter_order == 'value_ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>

                    <th colspan="2" id="ordering" class="ordering center manage-column column-attr_ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&filter_order=value_ordering&filter_order_Dir='.$this->filter_order_Dir.'&attr_id='.$this->attr_id))?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_ORDERING ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th id="saveorder" class="save_ordering center manage-column" scope="col" width="100">
                        <?php if ($saveOrder && $count != 0){?>
                            <a class="saveorder" onclick="saveorder();" href="#">
                                <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/>
                            </a>
                        <?php }?>
                    </th>
                    <?php if($this->filter_order == 'value_id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_id" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="60px" align="center">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&filter_order=value_id&filter_order_Dir='.$this->filter_order_Dir.'&attr_id='.$this->attr_id))?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_ID ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if($count == 0) :  ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="3"><?php echo esc_html( WOPSHOP_QUERY_RESULT_NULL ); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($rows as $index => $row) : ?>
                        <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                            <td class="check-column wopshop-admin-list-check" scope="col">
                                <input id="attr_<?php echo esc_attr($row->attr_id); ?>" type="checkbox" value="<?php echo esc_attr($row->value_id); ?>" name="rows[]" />
                            </td>
                            <td>
                             <strong><?php echo esc_html($row->name);?></strong>
                             <div class="row-actions">
                                 <span class="edit">
                                     <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&task=edit&value_id='.$row->value_id.'&attr_id='.$this->attr_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                     |
                                 </span>
                                 <span class="trash">
                                     <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributesvalues&task=delete&rows[]='.$row->value_id.'&attr_id='.$this->attr_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                 </span>
                             </div>
                            </td>
                            <td align="center">
                                 <?php if ($row->image) {?>
                                     <img src = "<?php echo esc_url($this->config->image_attributes_live_path."/".$row->image)?>"  alt = "" width="20" height="20" />
                                 <?php }?>
                            </td>


                            <td align="right" width="10">
                            <?php
                                 if ($index != 0 && $saveOrder) echo $this->renderOrderUp($row->value_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                            </td>
                            <td align="left" width="10">
                            <?php
                                 if ($index != $count-1 && $saveOrder) echo $this->renderOrderDown($row->value_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                            </td>
                            <td align="center">
                             <input type="text" name="order[]" id="ord<?php echo esc_attr($row->id);?>" size="5" value="<?php echo esc_attr($row->value_ordering)?>"  <?php if (!$saveOrder) echo 'disabled'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
                            </td>


<!--                            <td align="center">
                                <?php print esc_html($row->value_ordering);?>
                            </td>-->
                            <td align="center">
                             <?php print esc_html($row->value_id);?>
                            </td>
                       </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
    <br class="clear">
</div>
<div class="submit">
    <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=attributes'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
</div>