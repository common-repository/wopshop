<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row=$this->order_status;
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($row->status_id ? WOPSHOP_EDIT_ORDER_STATUS . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW_ORDER_STATUS); ?></h3>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus&task=save'))?>" id="edit">
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
                    <div class="form-field form-required term-code-wrap">
                        <label for="code"><?php echo esc_html(WOPSHOP_CODE); ?></label>
                        <input id="code" type="text" size="40" value="<?php echo esc_attr($row->status_code);?>" name="status_code">
                    </div>
                    <p class="submit">
                        <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                        <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=orderstatus'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
                    </p> 
                </div>
            </div>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <input type="hidden" value="<?php echo esc_attr($row->status_id); ?>" name="status_id">
            <?php wp_nonce_field('status_edit','name_of_nonce_field'); ?>
        </form>
    </div>
</div>