<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<table class = "wshop" id = "wshop_menu_order">
  <tr>
    <?php foreach($this->steps as $k=>$step){?>
      <td class = "wshop_order_step <?php print esc_attr($this->cssclass[$k])?>">
        <?php print wp_kses_post($step);?>
      </td>
    <?php }?>
  </tr>
</table>