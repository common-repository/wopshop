<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop myorders_list" id="wshop_plugin">

    <h1><?php print esc_html(WOPSHOP_MY_ORDERS )?></h1>
    
    <?php print $this->_tmp_html_before_user_order_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    
    <?php if (count($this->orders)) {?>
        <?php foreach ($this->orders as $order){?>
            <div class="myorders_block_info">
            
                <div class="order_number">
                    <b><?php print esc_html(WOPSHOP_ORDER_NUMBER )?>:</b>
                    <span><?php print esc_html($order->order_number)?></span>
                </div>
                <div class="order_status">
                    <b><?php print esc_html(WOPSHOP_ORDER_STATUS )?>:</b>
                    <span><?php print esc_html($order->status_name)?></span>
                </div>
                
                <div class="table_order_list">
                    <div class="row-fluid">
                        <div class="span6 users">
                            <div>
                                <b><?php print esc_html(WOPSHOP_ORDER_DATE )?>:</b>
                                <span><?php print esc_html(wopshop_formatdate($order->order_date, 0)); ?></span>
                            </div>
                            <div>
                                <b><?php print esc_html(WOPSHOP_EMAIL_BILL_TO )?>:</b>
                                <span><?php print esc_html($order->f_name) ?> <?php print esc_html($order->l_name) ?></span>
                            </div>
                            <div>
                                <b><?php print esc_html(WOPSHOP_EMAIL_SHIP_TO )?>:</b>
                                <span><?php print esc_html($order->d_f_name) ?> <?php print esc_html($order->d_l_name) ?></span>
                            </div>
                            <?php print $order->_tmp_ext_user_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                        <div class="span3 products">
                            <div>
                                <b><?php print esc_html(WOPSHOP_PRODUCTS )?>:</b>
                                <span><?php print esc_html($order->count_products) ?></span>
                            </div>
                            <div>
                                <b></b> 
                                <span><?php print esc_html(wopshopFormatprice($order->order_total, $order->currency_code))?></span>
                                <?php print $order->_ext_price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                            </div>
                            <?php print $order->_tmp_ext_prod_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                        <div class="span3 buttons">
                            <a class="btn" href = "<?php print esc_url($order->order_href)?>"><?php print esc_html(WOPSHOP_DETAILS)?></a>
                            <?php print $order->_tmp_ext_but_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                    </div>
                    <?php print $order->_tmp_ext_row_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </div>
            </div>
        <?php } ?>
        
        <div class="myorders_total">
            <span class="name"><?php print esc_html(WOPSHOP_TOTAL)?>:</span>
            <span class="price"><?php print esc_html(wopshopFormatprice($this->total, wopshopGetMainCurrencyCode()))?></span>
        </div>
        
    <?php }else{ ?>
        <div class="myorders_no_orders">
            <?php print esc_html(WOPSHOP_NO_ORDERS )?>
        </div>
    <?php } ?>
    
    <?php print $this->_tmp_html_after_user_order_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>