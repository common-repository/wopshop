<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('reviews');
$count = count($this->reviews);
$rows = $this->reviews;
$i = 0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_REVIEWS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
    </h2>
    <form  action="" method="POST" name="search">
        <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </form>
    <form id="listing" class="adminForm" action="<?php echo esc_url(admin_url('admin.php'))?>" id="listing" method="GET">
        <input type="hidden" name="page" value="options">
        <input type="hidden" name="tab" value="reviews">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php 
                echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            </div>
            <?php 
                echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
            <br class="clear">
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                 <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th width = "100" align = "left">
                        <?php echo esc_html(WOPSHOP_NAME_PRODUCT); ?>
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_USER); ?>
                    </th>        
                    <th>
                        <?php echo esc_html(WOPSHOP_EMAIL); ?>
                    </th>
                    <th align = "left">
                        <?php echo esc_html(WOPSHOP_PRODUCT_REVIEW); ?>
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_REVIEW_MARK); ?>
                    </th> 
                    <th>
                        <?php echo esc_html(WOPSHOP_DATE); ?> 
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_IP); ?>
                    </th>
                    <th width="50" class="center">
                        <?php echo esc_html(WOPSHOP_PUBLISH);?>       
                    </th>
                    <th width="40" class="center">
                        <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($rows) == 0){ ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php
                }else
                foreach ($rows as $row){$j = $i+1;?>
                <tr class="row<?php echo esc_attr($i % 2);?>">
                    <td class="check-column wopshop-admin-list-check" scope="col">
                        <input id="cid_<?php echo esc_attr($row->review_id); ?>" type="checkbox" value="<?php echo esc_attr($row->review_id); ?>" name="rows[]">
                   </td>
                   <td>
                        <strong><?php echo esc_html($row->name);?></strong>
                        <div class="row-actions">
                            <span class="edit">
                            <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=edit&row='.$row->review_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                            |
                            </span>
                            <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=delete&rows[]='.$row->review_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                            </span>
                        </div>
                   </td>
                   <td>
                     <?php echo esc_html($row->user_name);?>
                   </td> 
                   <td>
                     <?php echo esc_html($row->user_email);?>
                   </td>     
                   <td>
                     <?php echo wp_kses_post($row->review);?>
                   </td> 
                   <td>
                     <?php echo esc_html($row->mark);?>
                   </td> 
                   <td>
                     <?php echo esc_html($row->dateadd);?>
                   </td>
                   <td>
                     <?php echo esc_html($row->ip);?>
                   </td>
                   <td class="center">
                       <?php echo $published=($row->publish) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=unpublish&rows[]='.$row->review_id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=publish&rows[]='.$row->review_id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                   </td> 
                   <td class="center">
                    <?php print esc_html($row->review_id);?>
                   </td>
                </tr>
                <?php
                $i++;
                }
                ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" name="filter_order" value="<?php echo esc_attr($this->filter_order)?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo esc_attr($this->filter_order_Dir)?>" />      
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>