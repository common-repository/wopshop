<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('shippingsprices');
$shipping_prices = $this->rows;
$i = 0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_SHIPPING_PRICES_LIST); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&task=edit&sh_pr_method_id=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_SHIPPING); ?></a>
    </h2>
    <form id="listing" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
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
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&filter_order=name&filter_order_Dir='.$this->filter_order_Dir.'&shipping_id_back='.$this->shipping_id_back))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th id="order_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="70%">
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_COUNTRIES); ?></span>
                    </th>
                    <?php if($this->filter_order == 'shipping_price.shipping_stand_price') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="shipping_price_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="90px">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&filter_order=shipping_price.shipping_stand_price&filter_order_Dir='.$this->filter_order_Dir.'&shipping_id_back='.$this->shipping_id_back))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_PRICE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->filter_order == 'shipping_price.sh_pr_method_id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="40px">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&filter_order=shipping_price.sh_pr_method_id&filter_order_Dir='.$this->filter_order_Dir.'&shipping_id_back='.$this->shipping_id_back))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($this->rows) == 0){ ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                $count = count($shipping_prices);
                if ($count)
                    foreach ($shipping_prices as $sh_price) { ?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($sh_price->sh_pr_method_id); ?>" type="checkbox" value="<?php echo esc_attr($sh_price->sh_pr_method_id); ?>" name="rows[]">
                        </th>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($sh_price->name);?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&task=edit&sh_pr_method_id='.$sh_price->sh_pr_method_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&task=delete&rows[]='.$sh_price->sh_pr_method_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php print $sh_price->countries;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <td class="code-column " scope="col">
                            <?php print wopshopFormatprice($sh_price->shipping_stand_price); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <td class="code-column " scope="col">
                            <?php print  esc_html($sh_price->sh_pr_method_id);?>
                        </td>
                    </tr>
                <?php }
                } ?>
            </tbody>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </table>

        <input type="hidden" value="options" name="page">
        <input type="hidden" value="shippingsprices" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>