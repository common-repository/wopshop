<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<?php print esc_html(WOPSHOP_HI)?> <?php print esc_html($this->order->f_name);?> <?php print esc_html($this->order->l_name);?>,
<?php esc_html(printf(WOPSHOP_YOUR_ORDER_STATUS_CHANGE, $this->order->order_number));?>

<?php print esc_html(WOPSHOP_NEW_STATUS_IS)?>: <?php print esc_html($this->order_status)?>
<?php if ($this->order_detail){?>
<?php print esc_html(WOPSHOP_ORDER_DETAILS)?>: <?php print esc_html($this->order_detail)?>
<?php }?> 
 
<?php if ($this->comment!=""){?>
<?php print esc_html(WOPSHOP_COMMENT_YOUR_ORDER)?>: <?php print esc_html($this->comment);?>

<?php }?>
<?php print esc_html($this->vendorinfo->company_name)?>
<?php print esc_html($this->vendorinfo->adress)?>
<?php print esc_html($this->vendorinfo->zip)?> <?php print esc_html($this->vendorinfo->city)?>
<?php print esc_html($this->vendorinfo->country)?>
<?php print esc_html(WOPSHOP_CONTACT_PHONE)?>: <?php print esc_html($this->vendorinfo->phone)?>
<?php print esc_html(WOPSHOP_CONTACT_FAX)?>: <?php print esc_html($this->vendorinfo->fax)?>