<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<?php print $this->_tmp_product_html_body_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscape?>
<?php if(!count($this->images)){?>
    <img id="main_image" src="<?php print esc_url($this->image_product_path.'/'.$this->noimage)?>" alt="<?php print esc_attr($this->product->name)?>" />
<?php }?>
<?php foreach($this->images as $k=>$image){?>
    <a class="lightbox" id="main_image_full_<?php print esc_attr($image->image_id)?>" href="<?php print esc_url($this->image_product_path.'/'.$image->image_full)?>" <?php if ($k!=0){?>style="display:none"<?php }?> title="<?php print esc_attr($image->_title)?>">
        <img id = "main_image_<?php print esc_attr($image->image_id)?>" src = "<?php print esc_url($this->image_product_path.'/'.$image->image_name)?>" alt="<?php print esc_attr($image->_title)?>" title="<?php print esc_attr($image->_title)?>" />
        <div class="text_zoom">
            <img src="<?php print esc_url($this->path_to_image.'search.png')?>" alt="zoom" /> <?php print esc_html(WOPSHOP_ZOOM_IMAGE)?>
        </div>
    </a>
<?php }?>