<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <div class="home_panel">
        <div class="home_panel_left">                      
            <div class="panel-setting">
                <div class="panel panel-1">
                   <?php echo esc_html(WOPSHOP_MENU_CUSTOMER_TITLE)?>                    
                </div>
                <div class="panel-items">
                    <?php wopshopDisplayPanelSettings(); ?>
                </div>
            </div>
            <div class="panel-setting panel-setting-mt">  
                <div class="panel panel-1">
                    <?php echo esc_html(WOPSHOP_MENU_PRODUCTS_TITLE)?>
                </div>
                <div class="panel-items">
                    <?php wopshopDisplayMainPanelIco(); ?>   
                </div>
            </div>
        </div>
        <div class="home_panel_right">
            <div class="panel-information">
                <div id="contact-info">
                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL . 'assets/images/wopshop.png') ?>" />
                    <div class="clearfix">
                        <div>
                            <span class="whop-version-info"><?php echo esc_html(WOPSHOP_HOME_CURRENT_VERSION )?> <?php echo esc_html(get_option('wopshop_version')); ?></span>
                        </div>
                    </div>
                    <div class="clearfix">
                            <div><a class="whop-version-info-link" href="mailto:info@wop-agentur.com">info@wop-agentur.com</a></div>
                            <div><a class="whop-version-info-link" href="http://www.wop-agentur.com/" target="_blank">www.wop-agentur.com</a></div>
                    </div>
                </div>
            </div>
            <div class="feedback feedback-1">
                <div class="blue-ciel">
                    <div class="info-text"><?php echo esc_html(WOPSHOP_HOME_SUGGESTIONS_IMPROVEMENT) ?></div>
                    <div class="info-text"><?php echo esc_html(WOPSHOP_HOME_AND_YOUR )?></div>
                    <div class="info-text"><?php echo esc_html(WOPSHOP_HOME_FUNCTION_REQUESTS_FEEDBACK )?></div>                    
                </div>

                <div class="info-link">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-feedback'))?>" class="btn btn_home_panel btn-lg einstellen"><?php echo esc_html(WOPSHOP_HOME_SET_HERE )?></a>
                </div>               
            </div>
            <div class="feedback feedback-2">
                <div class="blue-ciel">
                    <div class="info-text"><?php echo esc_html(WOPSHOP_HOME_EXTENSIONS_INTERFACES_ADDONS) ?></div>
                    <div class="info-text"><?php echo esc_html(WOPSHOP_HOME_TOWOPSHOP) ?></div>                    
                </div>

                <div class="info-link">
                    <a href="https://www.wop-agentur.com/shop/category/view/extensions/" target="_blank" type="button" class="btn btn_home_panel btn-lg finden"><?php echo esc_html(WOPSHOP_HOME_FIND_HERE) ?></a>
                </div>      
            </div>
            <div class="feedback feedback-3">
                <div class="listbox blue-ciel">
                    <div class="head-info">
                        <h3><?php echo esc_html(WOPSHOP_HOME_OUR_SERVICES) ?>:</h3> 
                    </div>
                </div>      
                <div class="body-text">  
                    <ul style="list-style-type:none">
                        <li class="list"><?php echo esc_html(WOPSHOP_HOME_SHOP_PROGRAMMING); ?></li>
                        <li class="list"><?php echo esc_html(WOPSHOP_HOME_SHOP_WEBDESIGN); ?></li>
                        <li class="list"><?php echo esc_html(WOPSHOP_HOME_SHOP_ONLINE_MARKETING); ?></li>
                        <li class="list"><?php echo esc_html(WOPSHOP_HOME_SHOP_SERCH_ENGINE_OPTIMIZATION); ?></li>
                        <li class="list"><?php echo esc_html(WOPSHOP_HOME_SHOP_CONTENT_CREATION); ?></li>
                    </ul>
                </div>  
                <div class="info-link">
                    <a href="https://www.wop-agentur.com/wp-kontakt/" target="_blank" type="button" class="btn btn_home_panel btn-lg hier"><?php echo esc_html(WOPSHOP_HOME_INQUIRE_HERE) ?></a>
                </div>
                
            </div>                    
        </div>                                                                                                   
    </div>
</div>