<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('coupons');
$rows=$this->rows;
$i=0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_COUPONS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_COUPON); ?></a>
    </h2>
    <form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons'))?>" method = "post" name = "adminForm">
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </div>
            <?php echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php if($this->filter_order == 'coupon_code') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_code" class="manage-column column-order_code <?php echo esc_attr($class_name); ?> <?php echo $this->filter_order_Dir; ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&filter_order=coupon_code&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_CODE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th width = "80" align = "left">
                        <?php echo esc_html(WOPSHOP_VALUE);?>
                    </th>
                      <th width = "80">
                          <?php echo esc_html(WOPSHOP_START_DATE_COUPON) ?>
                      </th>
                      <th width = "80">
                          <?php echo esc_html(WOPSHOP_EXPIRE_DATE_COUPON) ?>
                      </th>
                      <th width = "80">
                          <?php echo esc_html(WOPSHOP_FINISHED_AFTER_USED) ?>
                      </th>
                      <th width = "80">
                          <?php echo esc_html(WOPSHOP_FOR_USER) ?>
                      </th>
                      <th width = "80">
                          <?php echo esc_html(WOPSHOP_COUPON_USED) ?>
                      </th>
                          <?php echo $this->tmp_extra_column_headers // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                      <th width = "50">
                          <?php echo esc_html(WOPSHOP_PUBLISH);?>
                      </th>
                      <th width = "50" id="order_coupon_id" class="manage-column column-order_coupon_id <?php echo $class_name; ?> <?php echo $this->filter_order_Dir; ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&filter_order=coupon_id&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php
                    foreach($rows as $row){
                        $finished=0; $date=date('Y-m-d');
                        if ($row->used) $finished=1;
                        if ($row->coupon_expire_date < $date && $row->coupon_expire_date!='0000-00-00' ) $finished=1;
                    ?>
                      <tr class="row<?php echo $i % 2;?>" <?php if ($finished) print "style='font-style:italic; color: #999;'"?>>
                       <th class="check-column" scope="col">
                            <input id="user_<?php echo $row->coupon_id; ?>" type="checkbox" value="<?php echo $row->coupon_id; ?>" name="rows[]">
                       </th>
                       <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($row->coupon_code);?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&task=edit&row='.$row->coupon_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&task=delete&rows[]='.$row->coupon_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                       <td class="code-column " scope="col">
                         <?php echo esc_html($row->coupon_value); ?>
                         <?php if ($row->coupon_type==0) print "%"; else print esc_html($this->currency);?>
                       </td>
                       <td class="code-column " scope="col">
                        <?php if ($row->coupon_start_date!='0000-00-00') print esc_html(wopshop_formatdate($row->coupon_start_date));?>
                       </td>
                       <td class="code2-column " scope="col">
                        <?php if ($row->coupon_expire_date!='0000-00-00')  print esc_html(wopshop_formatdate($row->coupon_expire_date));?>
                       </td>
                       <td align="center">
                        <?php if ($row->finished_after_used) print esc_html(WOPSHOP_YES); else print esc_html(WOPSHOP_NO)?>
                       </td>
                       <td align="center">
                        <?php if ($row->for_user_id) print esc_html($row->f_name." ".$row->l_name); else print esc_html(WOPSHOP_ALL);?>
                       </td>
                       <td align="center">
                        <?php if ($row->used) print esc_html(WOPSHOP_YES); else print esc_html(WOPSHOP_NO)?>
                       </td>
                       <?php echo $row->tmp_extra_column_cells // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                       <td align="center">
                         <?php echo $published=($row->coupon_publish) ? ('<a href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&task=unpublish&rows[]='.$row->coupon_id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'" title="'.esc_attr(WOPSHOP_PUBLISH).'" ></a>') : ('<a href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=coupons&task=publish&rows[]='.$row->coupon_id)).'"><img title="'.WOPSHOP_UNPUBLISH.'" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"></a>'); ?>
                       </td>
                       <td align="center">
                         <?php echo esc_html($row->coupon_id) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                       </td>
                      </tr>
                    <?php
                    $i++;
                    }
                    ?>
            </tbody>
        </table>

        <input type="hidden" value="options" name="page">
        <input type="hidden" value="coupons" name="tab">
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>





