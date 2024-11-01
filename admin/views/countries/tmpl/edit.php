<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo esc_html($this->country->country_id ? WOPSHOP_EDIT_COUNTRY . ' / ' . $this->country->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW_COUNTRY); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries&task=save'))?>" id="editcountry">
            <div class="shopping">
                <div id="icon-shopping" class="icon32 icon32-shopping-settings"><br></div>
                <div class="form-field form-required term-publish-wrap">
                    <label for="publish">
                        <?php echo esc_html(WOPSHOP_ACTION_PUBLISH); ?>: 
                        <input id="publish" type="checkbox" value="1" name="country_publish" <?php if($this->country->country_publish > 0) echo 'checked="checked"';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> >
                    </label>
                </div>
                <?php 
                foreach($this->languages as $index=>$language){?>
                    <div class="form-field form-required term-name-wrap">
                        <label for="name_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_TITLE); ?> <?php echo esc_html($language->name); ?></label>
                        <input id="name_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php $n = 'name_'.$language->language; echo esc_attr($this->country->$n); ?>" name="name_<?php echo esc_attr($language->language); ?>">
                    </div>
                <?php
                }?>
                <div class="form-field form-required term-code-wrap">
                    <label for="code"><?php echo esc_html(WOPSHOP_CODE); ?></label>
                    <input id="code" type="text" size="40" value="<?php echo esc_attr($this->country->country_code);?>" name="country_code">
                </div>
                <div class="form-field form-required term-code-wrap">
                    <label for="code2"><?php echo esc_html(WOPSHOP_CODE); ?> 2</label>
                    <input id="code2" type="text" size="40" value="<?php echo esc_attr($this->country->country_code_2); ?>" name="country_code_2">
                </div>
                <p class="submit">
                    <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                    <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=countries'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
                </p>
            </div>
            <input type="hidden" value="<?php echo esc_attr($this->country->country_id); ?>" name="country_id">
            <?php wp_nonce_field('coutry_edit','name_of_nonce_field'); ?>
        </form>
    </div>
</div>