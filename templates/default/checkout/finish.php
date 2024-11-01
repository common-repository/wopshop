<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if (!empty($this->text)){?>
<?php echo wp_kses_post($this->text);?>
<?php }else{?>
<p><?php print esc_html(WOPSHOP_THANK_YOU_ORDER)?></p>
<?php }?>