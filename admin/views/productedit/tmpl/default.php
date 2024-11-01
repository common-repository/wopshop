<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}

$row = $this->product;
$lists = $this->lists;
$tax_value = $this->tax_value;
$config = $this->config;
$currency = $this->currency;
?>
<script>
    jQuery("document").ready(function() {
        jQuery('form[name="product"]').submit(function(){
            if (jQuery('#product_width_image').val() == '' && jQuery('#product_height_image').val() == ''){
                alert('<?php echo esc_html(WOPSHOP_WRITE_SIZE_BAD); ?>');
                return false;
            }
            if (document.getElementById('category_id').selectedIndex == -1){
                alert('<?php echo esc_html(WOPSHOP_WRITE_SELECT_CATEGORY); ?>');
                return false;
            }
        })
    })
</script>
<script type="text/javascript">var lang_delete="<?php echo esc_html(WOPSHOP_DELETE); ?>";</script>
<form name="product" method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=save'))?>" enctype="multipart/form-data">
<div class="wrap">
	<h3><?php echo  esc_html($row->product_id ? WOPSHOP_EDIT_PRODUCT . ' "' . $row->{WopshopFactory::getLang()->get('name')}. '"' :  WOPSHOP_NEW_PRODUCT); ?></h3>
    <div class="tabs">
        <ul class="tab-links">
			<?php if ($this->product->parent_id==0){?>
            <li class="active"><a href="#tabInfo"><?php echo esc_html(WOPSHOP_INFO_PRODUCT); ?></a></li>
			<?php }?>
			<?php if ($this->product->parent_id==0){?>
            <li><a href="#tabDescription"><?php echo esc_html(WOPSHOP_DESCRIPTION); ?></a></li>
			<?php }?>
            <?php if ($config->admin_show_attributes && $this->product->parent_id==0){?>
                <li><a href="#tabAttributes"><?php echo esc_html(WOPSHOP_ATTRIBUTES); ?></a></li>
            <?php }?>
            <?php if ($config->admin_show_freeattributes && $this->product->parent_id==0){?>
                <li><a href="#tabFreeattribute" data-toggle="tab"><?php echo esc_html(WOPSHOP_FREE_ATTRIBUTES);?></a></li>
            <?php }?>
                <li <?php if ($this->product->parent_id!=0){?>class="active"<?php }?>><a href="#tabImages"><?php echo esc_html(WOPSHOP_IMAGE); ?></a></li>
            <?php if ($config->admin_show_product_video && $this->product->parent_id==0){?>
                <li><a href="#product_videos" data-toggle="tab"><?php echo esc_html(WOPSHOP_PRODUCT_VIDEOS);?></a></li>
            <?php }?>
            <?php if ($config->admin_show_product_related && $this->product->parent_id==0){?>
                <li><a href="#product_related" data-toggle="tab"><?php echo esc_html(WOPSHOP_PRODUCT_RELATED);?></a></li>
            <?php }?>
            <?php if ($config->admin_show_product_files){?>
                <li><a href="#product_files" data-toggle="tab"><?php echo esc_html(WOPSHOP_FILES);?></a></li>
            <?php }?>
            <?php if ($config->admin_show_product_extra_field && $this->product->parent_id==0){?>
                <li><a href="#tabExtraFields"><?php echo esc_html(WOPSHOP_EXTRA_FIELDS); ?></a></li>
            <?php }?>
            <?php if ($this->product->parent_id == 0) : ?>
                <?php do_action_ref_array('onDisplayProductEditTabsEndTab', array(&$row, &$lists, &$tax_value)); ?>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
			<?php if ($this->product->parent_id==0){?>
            <div id="tabInfo" class="tab active">
                <?php include(dirname(__FILE__)."/info.php"); ?>
            </div>
			<?php }?>
			<?php if ($this->product->parent_id==0){?>
            <div id="tabDescription" class="tab">
                <?php if ($this->multilang) : ?>
                    <div class="tabs">
                        <ul class="tab-links">
                            <?php foreach($this->languages as $index => $language) : ?>
                            <li>
                                <a href="#tab<?php echo esc_attr($index); ?>" data-toggle="tab">
                                    <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php echo esc_html($language->name); ?>
                                </a>
                            </li>        
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content">
                <?php endif; ?>
                        <?php
                            foreach($this->languages as $index=>$language){
                                include(dirname(__FILE__)."/description.php");
                            }
                        ?>
                <?php if ($this->multilang) : ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php }?>
            <?php 
            if ($config->admin_show_attributes && $this->product->parent_id==0){
                include(dirname(__FILE__)."/attribute.php");
            }
            if ($config->admin_show_freeattributes && $this->product->parent_id==0) {
                include(dirname(__FILE__)."/freeattribute.php");
            }
            include(dirname(__FILE__)."/images.php");
            if ($config->admin_show_product_video && $this->product->parent_id==0) {
                include(dirname(__FILE__)."/videos.php");
            }
            if ($config->admin_show_product_related && $this->product->parent_id==0) {
                include(dirname(__FILE__)."/related.php");
            }
            if ($config->admin_show_product_files) {
                include(dirname(__FILE__)."/files.php");
            }
            if ($config->admin_show_product_extra_field && $this->product->parent_id==0){
                include(dirname(__FILE__)."/extrafields.php");
            }
            if ($this->product->parent_id == 0){
                do_action_ref_array('onDisplayProductEditTabsEnd', array(&$row, &$lists, &$tax_value, &$currency));
            }else{
				do_action_ref_array('onDisplayExtAttributProductEditTabsEnd', array(&$row, &$lists, &$tax_value, &$currency));
			}
            ?>
        </div>
    </div>
</div>
<br>    
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
		<?php if ($this->product->parent_id == 0){?>
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
		<?php }?>
    </p> 
</div>
    <input type="hidden" value="<?php echo esc_attr($this->product->product_id); ?>" name="product_id">
	<input type="hidden" name="parent_id" value="<?php echo esc_attr($row->parent_id)?>" />
    <?php wp_nonce_field('productedit'); ?>
</form>

<script type="text/javascript">
function showHideAddPrice(){
    $_('tr_add_price').style.display=($_('product_is_add_price').checked)  ? ('') : ('none');
}
<?php if ($this->product->parent_id==0){?>
showHideAddPrice();
<?php }?>
</script>