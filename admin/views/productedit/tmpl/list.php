<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rows = $this->products; 
$i = 0;
$text_search = $this->text_search;
$count = count($rows);
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="ordering";
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_MENU_PRODUCTS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_PRODUCT); ?></a>
    </h2>
    <?php echo $this->top_counters;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <form action="" method="POST">
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </form>    
    <form id="listing" action="" method="get">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <br class="clear">
            
        </div>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->orderby == 'name_image') $class_image= 'sorted'; else $class_image= 'sortable';?>
                    <th class="manage-column column-order_image <?php echo esc_attr($class_image); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=name_image&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_IMAGE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=name&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'category') $class_category = 'sorted'; else $class_category = 'sortable';?>
                    <th class="manage-column column-order_category <?php echo esc_attr($class_category); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=category&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_CATEGORY); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'manufacturer') $class_manufacturer = 'sorted'; else $class_manufacturer = 'sortable';?>
                    <th class="manage-column column-order_manufacturer <?php echo esc_attr($class_manufacturer); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=manufacturer&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_MANUFACTURER); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'ean') $class_ean = 'sorted'; else $class_ean = 'sortable';?>
                    <th class="manage-column column-order_ean <?php echo esc_attr($class_ean); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=ean&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_EAN_PRODUCT); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'qty') $class_qty = 'sorted'; else $class_qty = 'sortable';?>
                    <th class="manage-column column-order_qty <?php echo esc_attr($class_qty); ?> <?php echo esc_attr($this->order); ?>" scope="col" >
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=qty&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_QUANTITY_PRODUCT); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'price') $class_price = 'sorted'; else $class_price = 'sortable';?>
                    <th class="manage-column column-order_price <?php echo esc_attr($class_price); ?> <?php echo esc_attr($this->order); ?>" scope="col" width="50">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=price&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_PRODUCT_PRICE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'hits') $class_hits = 'sorted'; else $class_hits = 'sortable';?>
                    <th class="manage-column column-order_hits <?php echo esc_attr($class_hits); ?> <?php echo esc_attr($this->order); ?>" scope="col" width="50">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=hits&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_HITS); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'date') $class_date = 'sorted'; else $class_date = 'sortable';?>
                    <th class="manage-column column-order_date <?php echo esc_attr($class_date); ?> <?php echo esc_attr($this->order); ?>" scope="col" width="100">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=date&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_DATE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'id') $class_date = 'sorted'; else $class_date = 'sortable';?>
                    <th class="manage-column column-order_id <?php echo esc_attr($class_id); ?> <?php echo esc_attr($this->order); ?>" scope="col" width="50">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&orderby=id&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>                  
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($rows) == 0){ ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($rows as $k=>$row) {/*print_r($row);*/?>
                <tr class="<?php if($k%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="cid_<?php echo esc_attr($row->product_id); ?>" type="checkbox" value="<?php echo esc_attr($row->product_id); ?>" name="cid[]">
                        </th> 
                        <th class="check-column" scope="col">
                            <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'files/img_products/thumb/'.$row->image)?>">
                        </th>
                        </td>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($row->name);?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=edit&product_id='.$row->product_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=delete&rows[]='.$row->product_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>                            
                        </td>
                        <td class="name-column" scope="col">
                            <?php echo esc_html($row->namescats);?>
                        </td>
                        <td align="center">
                          <?php echo esc_html($row->man_name);?>
                        </td>                        
                        <td align = "center">
                            <?php echo esc_html($row->ean);?>
                        </td>
                        <td align = "left" width = "20"> 
                            <?php if ($row->unlimited){
                                print esc_html(WOPSHOP_UNLIMITED);
                            }else{
                                echo esc_html($row->qty);
                            }
                            ?>
                        </td>
                        <td align="center" width="10">
                            <?php echo wopshopFormatprice($row->product_price);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <td class="name-column" scope="col" align="center">
                            <?php echo esc_html($row->hits);?>
                        </td>
                        <td class="name-column" scope="col" align="center">
                            <?php echo esc_html($row->product_date_added);?>
                        </td>
                        <td class="name-column" scope="col" align="center">
                            <?php echo esc_html($row->product_id)?>
                        </td>
                </tr>                      
<?php                        
                    }                   
                }
?>                
            </tbody>
        </table>        
        <input type="hidden" value="products" name="page">      
    </form>
<div id="ajax-response"></div>
<br class="clear">
</div>