<?php
/**
* @version      1.0.0 30.01.2017
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wshop" id="wshop_plugin">   
    <?php if (count($this->rows)) : ?>
    <div class="wshop_list_vendors">
        <div class = "wshop">
            <?php foreach($this->rows as $k=>$row) : ?>
                <?php if ($k % $this->count_to_row == 0) : ?>
                    <div class = "row-fluid">
                <?php endif; ?>
                
                <div class = "sblock<?php echo esc_attr($this->count_to_row)?> wshop_categ vendor">
                    <div class = "sblock2 image">
                        <a class = "product_link" href = "<?php print esc_url($row->link)?>">
                            <img class = "wshop_img" src = "<?php print esc_url($row->logo)?>" alt="<?php print esc_attr($row->shop_name);?>" />
                        </a>                    
                    </div>
                    <div class = "sblock2">
                        <div class="vendor_name">
                            <a class = "product_link" href = "<?php print esc_url($row->link)?>">
                                <?php print esc_html($row->shop_name)?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if ($k % $this->count_to_row == $this->count_to_row - 1) : ?>
                    <div class = "clearfix"></div>
                    </div>
                <?php endif; ?>
                
             <?php endforeach; ?>
             
             <?php if ($k % $this->count_to_row != $this->count_to_row - 1) : ?>
                <div class = "clearfix"></div>
                </div>
            <?php endif; ?>
            
        </div>
        <?php if ($this->display_pagination) : ?>
            <div class="wshop_pagination">
                <div class="pagination"><?php print wp_kses_post($this->pagination)?></div>
            </div>
        <?php endif;  ?>
    </div>
    <?php endif; ?>
</div>