<?php                       
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="wshop_plugin">
	<?php print wp_kses_post($this->checkout_navigator);?>
	<?php print wp_kses_post($this->small_cart);?>
	<?php
    $pm = new stdClass();
    foreach($this->payment_methods as  $payment){
	    $pm->{$payment->payment_class} = $payment->existentcheckform;
    }
	wp_add_inline_script('wopshop-functions.js', '
	    var payment_type_check = '.wp_json_encode($pm).';');
    ?>

    <div class="wshop checkout_payment_block">
        <form id = "payment_form" name = "payment_form" action = "<?php print esc_url($this->action)?>" method = "post" autocomplete="off" enctype="multipart/form-data">
            <?php print wp_kses_post($this->_tmp_ext_html_payment_start)?>
            <div id="table_payments">
                <?php 
                $payment_class = "";
                foreach($this->payment_methods as  $payment){
                    if ($this->active_payment==$payment->payment_id) $payment_class = $payment->payment_class;
                    ?>                    
                    <div class="name">
                        <input type = "radio" name = "payment_method" id = "payment_method_<?php print esc_attr($payment->payment_id) ?>" onclick = "showPaymentForm('<?php print esc_js($payment->payment_class) ?>')" value = "<?php print esc_attr($payment->payment_class) ?>" <?php if ($this->active_payment==$payment->payment_id){?>checked<?php } ?> />
                        <label for = "payment_method_<?php print esc_attr($payment->payment_id) ?>"><?php
                            if ($payment->image){
                                ?><span class="payment_image"><img src="<?php print esc_url($payment->image)?>" alt="<?php print esc_attr($payment->name)?>" /></span><?php
                            }
                            ?><b><?php print $payment->name;?></b> 
                            <?php if ($payment->price_add_text!=''){?>
                                <span class="payment_price">(<?php print wp_kses_post($payment->price_add_text)?>)</span>
                            <?php }?>
                        </label>
                    </div>                    
                    <div class="paymform" id="tr_payment_<?php print $payment->payment_class ?>" <?php if ($this->active_payment != $payment->payment_id){?>style = "display:none"<?php } ?>>
                        <div class = "wshop_payment_method">
                            <?php print wp_kses_post($payment->payment_description);?>
                            <?php print $payment->form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        
            <?php print $this->_tmp_ext_html_payment_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <input type = "button" id = "payment_submit" class = "btn btn-primary button" name = "payment_submit" value = "<?php print esc_html(WOPSHOP_NEXT) ?>" onclick="checkPaymentForm();" />
        </form>
    </div>
</div>
<?php if ($payment_class){ 
	wp_add_inline_script('wopshop-functions.js', "showPaymentForm('".esc_js($payment_class)."');");
}