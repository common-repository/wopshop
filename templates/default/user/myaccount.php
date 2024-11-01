<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$config_fields = $this->config_fields;
?>
<div class="wshop" id="wshop_plugin">

    <h1><?php print esc_html(WOPSHOP_MY_ACCOUNT)?></h1>

    <?php echo $this->tmpl_my_account_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    <div class="wshop_profile_data">
    
        <?php if ($config_fields['f_name']['display'] || $config_fields['l_name']['display']){?>
            <div class="name"><?php print esc_html($this->user->f_name." ".$this->user->l_name);?></div>
        <?php }?>
        
        <?php if ($config_fields['city']['display']){?>
            <div class="city"><span><?php print esc_html(WOPSHOP_CITY)?>:</span> <?php print esc_html($this->user->city)?></div>
        <?php }?>
        
        <?php if ($config_fields['state']['display']){?>
            <div class="state"><span><?php print esc_html(WOPSHOP_STATE)?>:</span> <?php print esc_html($this->user->state)?></div>
        <?php }?>
        
        <?php if ($config_fields['country']['display']){?>
            <div class="country"><span><?php print esc_html(WOPSHOP_COUNTRY)?>:</span> <?php print esc_html($this->user->country)?></div>
        <?php }?>
        
        <?php if ($config_fields['email']['display']){?>
            <div class="email"><span><?php print esc_html(WOPSHOP_EMAIL)?>:</span> <?php print esc_html($this->user->email)?></div>
        <?php }?>
        
        <?php if ($this->config->display_user_group){?>
            <div class="group">
                <span><?php print esc_html(WOPSHOP_GROUP)?>:</span>
                <?php print esc_html($this->user->groupname)?>
                <span class="subinfo">(<?php print esc_html(WOPSHOP_DISCOUNT)?>: <?php print esc_html($this->user->discountpercent)?>%)</span>
                
                <?php if ($this->config->display_user_groups_info){?>
                    <a class="wshop_user_group_info" target="_blank" href="<?php print esc_url($this->href_user_group_info)?>"><?php print esc_html(WOPSHOP_USER_GROUPS_INFO)?></a>
                <?php }?>
                
            </div>
        <?php }?>
    </div>
    
    <div class="myaccount_urls">
        <div class="editdata">
            <a href = "<?php print esc_url($this->href_edit_data)?>"><?php print esc_html(WOPSHOP_EDIT_DATA )?></a>
        </div>
        <div class="showorders">
            <a href = "<?php print esc_url($this->href_show_orders)?>"><?php print esc_html(WOPSHOP_SHOW_ORDERS )?></a>
        </div>
	    <?php echo $this->tmpl_my_account_html_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <div class="urllogout">
            <a href = "<?php print esc_url($this->href_logout)?>"><?php print esc_html(WOPSHOP_LOGOUT )?></a>
        </div>
    </div>
	<?php echo $this->tmpl_my_account_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
</div>