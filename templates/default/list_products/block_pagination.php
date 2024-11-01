<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wop_shop_pagination">
    <div class="pagination"><?php print wp_kses_post($this->pagination)?></div>
</div>