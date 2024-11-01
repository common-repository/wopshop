<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<?php if ( (count($this->images)>1) || (count($this->videos) && count($this->images)) ) {?>
    <?php foreach($this->images as $k=>$image){?>
        <img class="wshop_img_thumb" src="<?php print esc_url($this->image_product_path.'/'.$image->image_thumb)?>" alt="<?php print esc_attr($image->_title)?>" title="<?php print esc_attr($image->_title)?>" onclick="showImage(<?php print esc_js($image->image_id)?>)" />
    <?php }?>
<?php }?>