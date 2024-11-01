<?php 
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wrap">
    <div class="feedback_block">
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-feedback&task=sendform'))?>">
            <h1><?php echo esc_html(WOPSHOP_FEEDBACK_HEADER) ?></h1>
            <div class="data">
                <div class="data_block">
                    <span><?php echo esc_html(WOPSHOP_FIELD_F_NAME) ?> *</span>
                    <input type="text" required name="name" value="" />
                </div>
                <div class="data_block">
                    <span><?php echo esc_html(WOPSHOP_FIELD_FIRMA_NAME) ?></span>
                    <input type="text" name="firma" value="" />
                </div>
                <div class="data_block">
                    <span><?php echo esc_html(WOPSHOP_FIELD_EMAIL) ?> *</span>
                    <input type="text" required name="email" value="" />
                </div>
                <div class="textarea">
                    <span><?php echo esc_html(WOPSHOP_FEEDBACK_HEADER_TEXTAREA) ?></span>
                    <textarea name="feedback_text" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div class="send_button">
                <button type="submit" class="button button-primary"><?php echo esc_html(WOPSHOP_FEEDBACK_SUBMIT) ?></button>
            </div>
        </form>
    </div>    
</div>