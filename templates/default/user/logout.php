<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
};
?>
<div class="wshop" id="wshop_plugin">
    <h1><?php print esc_html(WOPSHOP_LOGOUT) ?></h1>
    <?php print wp_kses_post($this->checkout_navigator)?>
    
    <input type="button" class="btn button" value="<?php print esc_html(WOPSHOP_LOGOUT) ?>" onclick="location.href='<?php print esc_url(wopshopSEFLink("controller=user&task=logout")); ?>'" />
</div>