<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php print esc_html(WOPSHOP_PRODUCT)?>: <?php print esc_html($this->product_name);?><br/>
<?php print esc_html(WOPSHOP_REVIEW_USER_NAME)?>: <?php print esc_html($this->user_name);?><br/>
<?php print esc_html(WOPSHOP_REVIEW_USER_EMAIL)?>: <?php print esc_html($this->user_email);?><br/>
<?php print esc_html(WOPSHOP_REVIEW_MARK_PRODUCT)?>: <?php print esc_html($this->mark);?><br/>
<?php print esc_html(WOPSHOP_COMMENT)?>:<br/>
<?php print wp_kses_post(nl2br($this->review));

