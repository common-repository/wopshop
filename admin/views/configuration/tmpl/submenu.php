<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$menu=wopshopGetItemsConfigPanelMenu(); 
?>
<div class="clear"></div>
<div class="wshop-content">
    <div class="wopsubmenu">
        <div class="m">
            <ul id="submenu">
            <?php foreach($menu as $key=>$el){
                if (!$el[3]) continue;
            ?>
                <li>
                    <a <?php print $key == $active? 'class="active"' : ''?> href="<?php print esc_url($el[1])?>"><?php print $el[0] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                </li>
            <?php }?>        
            </ul>    
            <div class="clear"></div>
        </div>
    </div>
</div>