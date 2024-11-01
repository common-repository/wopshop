<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop wshopcontent" id="wshop_plugin">
<?php print wp_kses_post($this->text);?>
</div>