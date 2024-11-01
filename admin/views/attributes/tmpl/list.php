<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions( 'attributes' );

$rows = $this->rows;
$count = count($rows);
$saveOrder = $this->filter_order_Dir == "desc" && $this->filter_order == "A.attr_ordering";
?>

<div class="wrap">
    <h2>
        <?php echo esc_html( WOPSHOP_ATTRIBUTES ); ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&task=edit' ) )?>" class="add-new-h2"><?php echo esc_html( WOPSHOP_NEW_ATTRIBUT ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributesgroups' ) ) ?>" class="add-new-h2"><?php echo esc_html( WOPSHOP_NEW_GROUP ); ?></a>
    </h2>

    <form id="listing" class="adminForm" action = "<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes' ) ) ?>" method = "post" name = "adminForm">
        
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <?php print $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed posts striped">
            <thead>
                <tr>                
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&filter_order=name&filter_order_Dir=' . $this->filter_order_Dir ) ); ?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_TITLE ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th width="15%" class="manage-column">
                        <?php echo esc_html( WOPSHOP_OPTIONS ); ?>
                    </th>
                    <?php if($this->filter_order == 'A.independent') $class_independent = 'sorted'; else $class_independent = 'sortable';?>
                    <th class="manage-column column-independent <?php echo esc_attr( $class_independent ); ?> <?php echo esc_attr( $this->filter_order_Dir ); ?>" scope="col" width="15%">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&filter_order=A.independent&filter_order_Dir=' . $this->filter_order_Dir ) ); ?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_DEPENDENT ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->filter_order == 'A.groupname') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="groupname" class="manage-column column-groupname <?php echo esc_attr( $class_name ); ?> <?php echo esc_attr( $this->filter_order_Dir ); ?>" scope="col" width="15%">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&filter_order=groupname&filter_order_Dir=' . $this->filter_order_Dir ) )?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_GROUP ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
					
                    <?php if($this->filter_order == 'A.attr_ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th colspan="2" id="ordering" class="ordering center manage-column column-attr_ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&filter_order=A.attr_ordering&filter_order_Dir=' . $this->filter_order_Dir ) ); ?>">
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
					
                    <?php if($this->filter_order == 'A.attr_id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="attr_id" class="manage-column column-attr_id <?php echo esc_attr( $class_name ); ?> <?php echo esc_attr( $this->filter_order_Dir ); ?>" scope="col" style="width: 50px">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&filter_order=A.attr_id&filter_order_Dir=' . $this->filter_order_Dir ) ); ?>">
                            <span class="status_head tips"><?php echo esc_html( WOPSHOP_ID ); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($count == 0) : ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="9"><?php echo esc_html( WOPSHOP_QUERY_RESULT_NULL ); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($rows as $index => $row) : ?>
                        <tr class="<?php if($index%2) echo 'alt';?>">
                            <td class="check-column wopshop-admin-list-check" scope="col">
                                <input id="attr_<?php echo esc_attr( $row->attr_id ); ?>" type="checkbox" value="<?php echo esc_attr( $row->attr_id ); ?>" name="rows[]" />
                            </td>
                            <td class="column-primary">
                                <?php if (!$row->count_values) {?><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/disabled.png')?>" alt="" /><?php }?>
                                <strong><?php echo esc_html( $row->name );?></strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a class="" title="<?php echo esc_attr( WOPSHOP_EDIT ); ?>" href = "<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&task=edit&attr_id=' . $row->attr_id ) ); ?>"><?php echo esc_html(WOPSHOP_EDIT ); ?></a>
                                        |
                                    </span>
                                    <span class="trash">
                                        <a class="submitdelete" title="<?php echo esc_attr( WOPSHOP_DELETE ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&task=delete&action=-1&rows[]=' . $row->attr_id ) ); ?>"><?php echo esc_html( WOPSHOP_DELETE ); ?></a>
                                    </span>
                                </div>
                            </td>
                       <td>
                         <a href = "<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributesvalues&attr_id=' . $row->attr_id ) )?>"><?php echo esc_html( WOPSHOP_OPTIONS );?></a>
                         <?php echo esc_html( $row->values );?>
                       </td>
                       <td>
                        <?php if ($row->independent==0){
                            print esc_html( WOPSHOP_YES );
                        }else{
                            print esc_html( WOPSHOP_NO );
                        }?>
                       </td>
                       <td>
                        <?php print esc_html( $row->groupname )?>
                       </td>
					   
						<td align="right" width="10">
							<?php
							if ($index != 0 && $saveOrder) echo $this->renderOrderUp($row->attr_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
                        </td>
                        <td align="left" width="10">
                            <?php
                            if ($index != $count-1 && $saveOrder) echo $this->renderOrderDown($row->attr_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
						</td>
						<td align="center">
						 <input type="text" name="order[]" id="ord<?php echo esc_attr( $row->attr_id );?>" size="5" value="<?php echo esc_attr( $row->attr_ordering );?>" <?php if (!$saveOrder) echo 'disabled'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="inputordering" style="text-align: center" />
						</td>					   
					   
					   
                       <td align="center">
                        <?php print esc_html( $row->attr_id );?>
                       </td>
                      </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
		<input type = "hidden" name = "task" value = "<?php echo esc_attr( WopshopRequest::getVar('task', 0) )?>" />
        <?php print $this->tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
<br class="clear">
</div>