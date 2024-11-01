<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap shopping page-options">
    <div class="panel panel-2">
        <?php echo esc_html(WOPSHOP_OPTIONS)?>
    </div>
    <?php if(is_array($this->items)) { ?>
        <div class="list_options">
            <?php foreach($this->items as $key=>$item){ ?>              
                <div class="item_option">            
                    <div class="carbonads">
                        <div class="carbonads-image">
                             <a href="<?php echo esc_attr(esc_url($item[1]))?>" class="carbon-img" rel="noopener">
                                <img class="img-responsive" src="<?php print esc_url(WOPSHOP_PLUGIN_URL.'assets/images/'.$item[2])?>" alt="" border="0">
                            </a>                           
                        </div>
                        <div class="carbonads-text">
                                <a href="<?php echo esc_url($item[1])?>" class="carbon-text" rel="noopener"><?php echo $item[0];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                                <div class="carbon-poweredby" rel="noopener"><?php echo $item[4];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>                            
                        </div>                  
                    </div>
                </div>            
            <?php } ?>
        </div>
    <?php } ?>
</div>