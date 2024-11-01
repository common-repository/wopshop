<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row=$this->productLabel;
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($row->id ? WOPSHOP_PRODUCT_LABEL_EDIT . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_PRODUCT_LABEL_NEW); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels&task=save'))?>" id="editproductlabel" enctype="multipart/form-data">
            <div class="wrap shopping">
                <div id="icon-shopping" class="icon32 icon32-shopping-settings"><br></div>
                <div class="wrap">
                    <?php 
                    foreach($this->languages as $index=>$language){?>
                        <div class="form-field form-required term-name-wrap">
                            <label for="name_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_TITLE); ?> <?php echo esc_html($language->name); ?></label>
                            <input id="name_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php $n = 'name_'.$language->language; echo esc_attr($row->$n); ?>" name="name_<?php echo esc_attr($language->language); ?>">
                        </div>
                    <?php
                    }?>
                    <div class="form-field form-required term-code-wrap" id="images_container">
                        <?php if($row->image){?>
                        <div>
                            <div>
                                <img src="<?php echo esc_url($this->config->image_labels_live_path."/".$row->image)?>">
                            </div>
                            <div class="link_delete_foto">
                                <a onclick="if (confirm('<?php echo esc_attr(WOPSHOP_DELETE_IMAGE); ?>')) deleteFotoProductlabel(<?php echo esc_attr($row->id); ?>);return false;" href="#">
                                    <img src="<?php print esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>">
                                    <?php echo esc_html(WOPSHOP_DELETE_IMAGE); ?>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                
                    <div class="form-field form-required term-code-wrap">
                        <label for="productlabel_image"><?php echo esc_html(WOPSHOP_IMAGE_SELECT); ?></label>
                        <input id="productlabel_image" type="file" name="productlabel_image">
                    </div>                    
					<p><?php print $this->etemplatevar;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>	
                    <p class="submit">
                        <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                        <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productlabels'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
                    </p> 
                </div>
            </div>
            <input type="hidden" value="<?php echo esc_attr($row->id); ?>" name="id">
            <?php wp_nonce_field('productlabel_edit','name_of_nonce_field'); ?>
        </form>
    </div>
</div>