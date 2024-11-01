<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop" id="wshop_plugin">

    <div class="manufacturer_description">
        <?php print wp_kses_post($this->manufacturer->description)?>
    </div>

    <?php if (count($this->rows)) : ?>
    <div class="wshop_list_manufacturer">
        <div class = "wshop">
            <?php foreach($this->rows as $k=>$row) : ?>
            
                <?php if ($k % $this->count_manufacturer_to_row == 0) : ?>
                    <div class = "row-fluid">
                <?php endif; ?>
                
                <div class = "sblock<?php echo esc_attr($this->count_manufacturer_to_row)?> wshop_categ manufacturer">
                    <div class = "sblock2 image">
                        <a href = "<?php print esc_url($row->link)?>">
                            <img class = "wshop_img" src = "<?php print esc_url($this->image_manufs_live_path.'/'.($row->manufacturer_logo ? $row->manufacturer_logo : $this->noimage))?>" alt="<?php print esc_attr($row->name);?>" />
                        </a>
                    </div>
                    <div class = "sblock2">
                        <div class="manufacturer_name">
                            <a class = "product_link" href = "<?php print esc_url($row->link)?>">
                                <?php print esc_html($row->name)?>
                            </a>
                        </div>
                        <p class = "manufacturer_short_description">
                            <?php print wp_kses_post($row->short_description)?>
                        </p>
                        <?php if ($row->manufacturer_url != "") : ?>
                            <div class="manufacturer_url">
                                <a target="_blank" href="<?php print esc_url($row->manufacturer_url)?>">
                                    <?php print esc_html(WOPSHOP_MANUFACTURER_INFO)?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($k % $this->count_manufacturer_to_row == $this->count_manufacturer_to_row - 1) : ?>
                    <div class = "clearfix"></div>
                    </div>
                <?php endif; ?>
                
            <?php endforeach; ?>
            
            <?php if ($k % $this->count_manufacturer_to_row != $this->count_manufacturer_to_row - 1) : ?>
                <div class = "clearfix"></div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    <?php endif; ?>
</div>