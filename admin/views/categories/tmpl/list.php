<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$categories = $this->categories; 
$i = 0;
$count = count($categories); 
$saveOrder = $this->filter_order_Dir=="desc" && $this->filter_order=="ordering";
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_MENU_CATEGORIES); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_CATEGORY); ?></a>
    </h2>
    <form action="" method="POST">
    	<?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
    	
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </form>    
	<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-categories'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <br class="clear">          
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="column-primary manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="200">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column column-order_description" scope="col">
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_DESCRIPTION); ?></span>
                    </th>
                    <th width="80">
                      <?php echo esc_html(WOPSHOP_CATEGORY_PRODUCTS);?>
                    </th>
					<?php if($this->filter_order == 'ordering') $class_name = 'sorted'; else $class_name = 'sortable';?>
					<th colspan="2" id="ordering" class="ordering center manage-column column-ordering <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="100">
						<a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&filter_order=ordering&filter_order_Dir='.$this->filter_order_Dir))?>">
							<span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDERING); ?></span>
							<span class="sorting-indicator"></span>
						</a>						
					</th>
					<th id="saveorder" class="save_ordering center manage-column" scope="col" width="100">
		<?php if ($saveOrder){?>
						<a class="saveorder" onclick="saveorder();" href="#"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/save.png')?>"/></a>
		<?php }?>						
					</th>					
                    <th class="manage-column" scope="col" width="100">
                        <?php echo esc_html(WOPSHOP_PUBLISH); ?>
                    </th>  
                    <?php if($this->filter_order == 'id') $class_id = 'sorted'; else $class_id = 'sortable';?>
                    <th class="manage-column column-order_id <?php echo esc_attr($class_id); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="50">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&filter_order=id&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>                  
                </tr>
            </thead>
            <tbody id="the-list">
                <?php 
                    foreach($categories as $k=>$category) {?>
                <tr class="<?php if($k%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <td class="check-column wopshop-admin-list-check" scope="col">
                            <input id="cid_<?php echo esc_attr($category->category_id); ?>" type="checkbox" value="<?php echo esc_attr($category->category_id); ?>" name="rows[]">
                        </td> 
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <?php print wp_kses_post($category->space); ?><?php echo esc_html($category->name);?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&task=edit&category_id='.$category->category_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-categories&task=delete&rows[]='.$category->category_id.'&action=-1'))?>"><?php echo esc_attr(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>                            
                        </td>
                        <td class="name-column" scope="col">
                            <?php echo wp_kses_post($category->short_description);?>
                        </td>
                        <td align="center">
                          <?php if (isset($this->countproducts[$category->category_id])){?>
                          <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&category_id='.$category->category_id))?>">
                            (<?php print intval($this->countproducts[$category->category_id]);?>) <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tree.gif')?>" border="0" />
                          </a>
                          <?php }else{?>
                          (0)
                          <?php }?>
                        </td>                        
                        <td align = "right" width = "20">
                             <?php if ($saveOrder && $category->isPrev) echo '<a href = "'.esc_url(admin_url('admin.php?page=wopshop-categories&task=order&id='.$category->category_id.'&move=-1')).'"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                         </td>
                         <td align = "left" width = "20"> 
                             <?php if ($saveOrder && $category->isNext) echo '<a href = "'.esc_url(admin_url('admin.php?page=wopshop-categories&task=order&id='.$category->category_id.'&move=1')).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                         </td>

                        <td align="center">
                            <input   <?php if (!$saveOrder) echo 'disabled' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> type="text" name="order[]" id="ord<?php echo esc_attr($category->category_id);?>" size="3" value="<?php echo esc_attr($category->ordering);?>" class="inputordering" />
                        </td>
												
                        <td class="name-column" scope="col" align="center">
                            <?php
                              echo $published=($category->category_publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-categories&task=unpublish&rows[]='.$category->category_id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-categories&task=publish&rows[]='.$category->category_id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>                            
                        </td>
                        <td class="name-column" scope="col" align="center">
                            <?php echo esc_html($category->category_id)?>
                        </td>
                </tr>                      
				<?php   } ?>                
            </tbody>
        </table>        
        <input type="hidden" value="categories" name="page">
        <input type = "hidden" name = "task" value = "display" />	
    </form>
<div id="ajax-response"></div>
<br class="clear">
</div>