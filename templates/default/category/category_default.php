<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo $this->_tmp_category_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="wshop" id="wshop_plugin">
    <div class="category_description">
        <?php print wp_kses_post($this->category->description);?>
    </div>

    <div class="wshop_list_category">
    <?php if (count($this->categories)) : ?>
        <div class = "wshop list_category">
            <?php foreach($this->categories as $k=>$category) : ?>
            
                <?php if ($k % $this->count_category_to_row == 0) : ?>
                    <div class = "row-fluid">
                <?php endif; ?>
                
                <div class = "sblock<?php echo esc_attr($this->count_category_to_row);?> wshop_categ category">
                    <div class = "sblock2 image">
                        <a href = "<?php print esc_url($category->category_link)?>">
                            <img class="wshop_img" src="<?php print esc_url($this->image_category_path.'/'.($category->category_image ? $category->category_image : $this->noimage))?>" alt="<?php print esc_attr($category->name)?>" title="<?php print esc_attr($category->name)?>" />
                        </a>
                    </div>
                    <div class = "sblock2">
                        <div class="category_name">
                            <a class = "product_link" href = "<?php print esc_url($category->category_link)?>">
                                <?php print esc_html($category->name)?>
                            </a>
                        </div>
                        <p class = "category_short_description">
                            <?php print wp_kses_post($category->short_description)?>
                        </p>                       
                    </div>
                </div>
                
                <?php if ($k % $this->count_category_to_row == $this->count_category_to_row - 1) : ?>
                    <div class = "clearfix"></div>
                    </div>
                <?php endif; ?>
                
            <?php endforeach; ?>
            
            <?php if ($k % $this->count_category_to_row != $this->count_category_to_row - 1) : ?>
                <div class = "clearfix"></div>
                </div>
            <?php endif; ?>
            
        </div>
    <?php endif; ?>
    </div>

    <?php print $this->_tmp_category_html_before_products; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    <?php include(dirname(__FILE__)."/products.php");?>

    <?php print $this->_tmp_category_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>