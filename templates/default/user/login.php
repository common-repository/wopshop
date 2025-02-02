<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class = "wshop pagelogin" id="wshop_plugin">    
    <h1><?php print esc_html(WOPSHOP_LOGIN )?></h1>
    <?php print wp_kses_post($this->checkout_navigator)?>
    
    <?php if ($this->config->shop_user_guest && $this->show_pay_without_reg) : ?>
    <span class = "text_pay_without_reg"><?php print esc_html(WOPSHOP_ORDER_WITHOUT_REGISTER_CLICK)?> <a href="<?php print esc_url(wopshopSEFLink('controller=checkout&task=step2',1,0, $this->config->use_ssl))?>"><?php print esc_html(WOPSHOP_HERE)?></a></span>
    <?php endif; ?>
    
    <?php echo $this->tmpl_login_html_1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    <div class = "row-fluid">
        <div class = "span6 login_block">
			<?php echo $this->tmpl_login_html_2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <div class="small_header"><?php print esc_html(WOPSHOP_HAVE_ACCOUNT )?></div>
            <div class="logintext"><?php print esc_html(WOPSHOP_PL_LOGIN )?></div>
            
            <form method="post" action="<?php print esc_url(wopshopSEFLink('controller=user&task=loginsave', 1,0, $this->config->use_ssl))?>" name="jlogin" class="form-horizontal">
                <div class="control-group">
                    <div class="control-label">
                        <label id="username-lbl" for="jlusername">
                            <?php print esc_html(WOPSHOP_USERNAME )?>:
                        </label>
                    </div>
                    <div class="controls">
                        <input type="text" id="jlusername" name="username" value="" class="inputbox" />
                    </div>
                </div>
                
                <div class="control-group rowpasword">
                    <div class="control-label">
                        <label id="password-lbl" class="" for="jlpassword"><?php print esc_html(WOPSHOP_PASSWORT )?>:</label>
                    </div>
                    <div class="controls">
                        <input type="password" id="jlpassword" name="passwd" value="" class="inputbox" />
                    </div>
                </div>
                
                <div class="control-group checkbox rowremember">
                    <div class="controls">
                        <input type="checkbox" name="remember" id="remember_me" value="yes" />
                        <label for = "remember_me"><?php print esc_html(WOPSHOP_REMEMBER_ME )?></label>
                    </div>
                </div>
                
                <div class="control-group rowbutton">
                    <div class="controls">
                        <input type="submit" class="btn btn-primary button" value="<?php print esc_html(WOPSHOP_LOGIN )?>" />
                    </div>
                </div>
                
                <div class="control-group rowlostpassword">
                    <div class="controls">
                        <a href = "<?php echo esc_url(WopshopUri::root().'wp-login.php?action=lostpassword')?>"><?php print esc_html(WOPSHOP_LOST_PASSWORD )?></a>
                    </div>
                </div>
                
                <input type = "hidden" name = "return" value = "<?php print esc_attr($this->return )?>" />
				<?php echo $this->tmpl_login_html_3; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            </form>   
        </div>
        <div class = "span6 register_block">
			<?php echo $this->tmpl_login_html_4; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <span class="small_header"><?php print esc_html(WOPSHOP_HAVE_NOT_ACCOUNT )?></span>
            <div class="logintext"><?php print esc_html(WOPSHOP_REGISTER )?></div>
            <?php if (!$this->config->show_registerform_in_logintemplate){?>
                <div class="block_button_register">
                    <input type="button" class="btn button" value="<?php print esc_html(WOPSHOP_REGISTRATION) ?>" onclick="document.location.href='<?php print esc_url_raw($this->href_register)?>';" />
                </div>
            <?php }else{?>
                <?php $hideheaderh1 = 1; include(dirname(__FILE__)."/register.php"); ?>
            <?php }?>
			<?php echo $this->tmpl_login_html_5; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </div>
    </div>
	<?php echo $this->tmpl_login_html_6; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>    