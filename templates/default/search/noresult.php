<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop">
<h1><?php print esc_html(WOPSHOP_SEARCH_RESULT)?> <?php if ($this->search) print esc_html('"'.$this->search.'"');?></h1>

<?php echo esc_html(WOPSHOP_NO_SEARCH_RESULTS);?>
</div>