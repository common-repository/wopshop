<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="tabFreeattribute" class="tab-pane">   
   <div class="col100">
   <table class="admintable" width="90%">
   <?php foreach($this->listfreeattributes as $freeattrib){?>
     <tr>
       <td class="key">
         <?php echo esc_html($freeattrib->name);?>
       </td>
       <td>
         <input type="checkbox" name="freeattribut[<?php print esc_attr($freeattrib->id)?>]" value="1" <?php if (isset($freeattrib->pactive) && $freeattrib->pactive) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
       </td>
     </tr>
   <?php }?>
   </table>
   </div>
   <div class="clr"></div>
</div>