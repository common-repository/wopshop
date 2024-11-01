<?php
/**
 * Plugin Name: WPshopping
 * Plugin URI: https://www.agentur-wp.com/wordpress-shop/
 * Description: WPshopping is an Open Source E-Commerce solution which offers a lot of Features.
 * Version: 1.5.1
 * Author: MAXXmarketing GmbH
 * Author URI: https://www.agentur-wp.com/
 *
 * Open Source License, GNU GPL
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define ('WSHOP_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)));

if (!class_exists('Wopshop')) {
    
    /**
     * Main WOPshop Class
     *
     * @class Wopshop
     */
    final class Wopshop {
        
        /**
         * The single instance of the class
         *
         * @var Wopshop
         */
        protected static $_instance = null;
        
        /**
         * Wopshop version.
         *
         * @var string
         */
        public $version = '1.5.1';
        
        /**
         * Application object
         *
         * @var Application
         */
        protected $app = null;
        
        /**
         * Minimum Wordpress version.
         *
         * @var string
         */
        protected $minimumWordpressVersion = '3.6';

        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            
            return self::$_instance;
        }

        /**
         * WOPshop Constructor.
         */
        public function __construct() {
            register_activation_hook(__FILE__, array($this, 'activate'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate'));

            $this->loadCoreFiles();
            $this->define_constants();
            
            // Register shop routes
            WshopRouter::getInstance()->registerRewriteRules();
            
			$this->loadAddons();
            add_action('init', array($this, 'initialise'));
            // Close the session before $wpdb destructs itself.
            add_action( 'shutdown', array( $this, 'close' ), 100 );
        }
        
        /**
         * WOPshop shutdown
         */
        public function close() {
            WopshopFactory::getSession()->close();
        }

        public function initialise() {
            $this->app = WopshopFactory::getApplication();
            $this->app->initialise();
            $ajax = WopshopRequest::getInt('ajax', 0);
            
            WopshopFactory::initLanguageFile();
            WopshopFactory::getConfig();


            if ($this->app->isAdmin()) {
				if (!$ajax){
					$wshopInstaller = new WshopInstaller();
					$wshopInstaller->installNewLanguages();
				}
                
                require_once WOPSHOP_PLUGIN_DIR . 'admin/wshopadmin.php';
                add_action('admin_menu', array($this, 'wopshop_admin_menu'));
                add_action('admin_init', array($this, 'wopshop_admin_init'));
                add_action('admin_head-nav-menus.php', array(&$this, 'add_menu_meta_boxes'));
                add_action('save_post', array('WshopRouter', 'regenerateRewriteRulesForPages'));

                //add link to settings page
                $plugin = plugin_basename(__FILE__);
                add_filter("plugin_action_links_".$plugin, array($this, 'plugin_add_settings_link'));
            } else {
                WshopRouter::getInstance()->generatePage();
            }
            //disable delete destination folder
            add_filter( "upgrader_package_options", array($this, 'upgrader_package_options') );
        }

        /**
         * Define WOPshop Constants.
         */
        private function define_constants() {
            $this->define('WOP_WP_CNT', content_url());
            
            $admin_url = rtrim(admin_url(), '/');
            $this->define('WOP_URL_ADMIN', $admin_url);    
            $this->define('WOPSHOP_PLUGIN_URL', plugin_dir_url(__FILE__));
            $this->define('WOPSHOP_PLUGIN_DIR', plugin_dir_path(__FILE__));
            $this->define('WOPSHOP_PLUGIN_INCLUDE_DIR', plugin_dir_path(__FILE__).'includes');
            $this->define('WOPSHOP_PLUGIN_ADMIN_DIR', plugin_dir_path(__FILE__).'admin');
            $this->define('WOP_WP_CNT', content_url());
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
        
        /**
         * Disable deleting destination folder during updating `WOPshop` plugin
         */
        public function upgrader_package_options( $options ) {
            if(isset($options['hook_extra']['plugin']) && $options['hook_extra']['plugin'] == 'wopshop/wopshop.php'){
                $options['abort_if_destination_exists'] = false;
                $options['clear_destination'] = false;        
            }
            return $options;
        }
    
        public function wopshop_admin_init() {
            WopshopFactory::loadJQuery();
            WopshopFactory::loadDatepicker();
            
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-button');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-dialog');

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-datepicker');         
            
            wp_enqueue_style('wopshop-admin.css', WOPSHOP_PLUGIN_URL.'assets/css/admin.css');
            wp_enqueue_script('wopshop-admin.js', WOPSHOP_PLUGIN_URL.'assets/js/system/admin.js');
			wp_enqueue_style('wopshop-jquery.ui.css', WOPSHOP_PLUGIN_URL.'assets/css/jquery.ui.css');
        }

    
        public function plugin_add_settings_link($links) {
            $settings_link = '<a href="'.esc_url(admin_url('admin.php?page=wopshop-configuration')).'">' . __('Settings') . '</a>';
            array_unshift($links, $settings_link);
            
            return $links;
        }

        public function wopshop_admin_menu() {
            add_menu_page('wopshop', 'WPshopping', 'edit_posts', 'wopshop-panel', array('WshopAdmin', 'actions'), WOPSHOP_PLUGIN_URL . 'assets/images/icons/wopshop.png', 56);

            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_PANEL, 'edit_posts', 'wopshop-panel', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_CATEGORIES, 'edit_posts', 'wopshop-categories', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_PRODUCTS, 'edit_posts', 'wopshop-products', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_ORDERS, 'edit_posts', 'wopshop-orders', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_CLIENTS, 'edit_posts', 'wopshop-clients', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_OTHER, 'edit_posts', 'wopshop-options', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_CONFIG, 'manage_options', 'wopshop-configuration', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_INSTALL_AND_UPDATE, 'manage_options', 'wopshop-update', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_MENU_INFO, 'edit_posts', 'wopshop-aboutus', array('WshopAdmin', 'actions'));
            add_submenu_page('wopshop-panel', 'WPshopping', '<span style="margin-left:15px;"></span>'.WOPSHOP_FEEDBACK, 'edit_posts', 'wopshop-feedback', array('WshopAdmin', 'actions'));
        }

        public function add_menu_meta_boxes() {
            add_meta_box('add-wopshop', __('Wopshop','wshop'), array(&$this, 'meta_box_display'), 'nav-menus', 'side', 'default');
			wp_enqueue_script('admin_menu.js', WOPSHOP_PLUGIN_URL.'assets/js/system/admin_menu.js');
        }
        
        public function meta_box_display($post, $data) {
            include(WSHOP_PLUGIN_BASE_DIR . '/lib/form.meta_boxes.php');
        }
        
        public function activate(){
            if (version_compare($GLOBALS['wp_version'], $this->minimumWordpressVersion, '<')) {
                echo '<strong>'.sprintf('WOPshop %s requires WordPress %s or higher.', $this->version, $this->minimumWordpressVersion).'</strong> '.sprintf('Please <a href="%1$s">upgrade WordPress</a> to a current version', esc_url('https://codex.wordpress.org/Upgrading_WordPress'));
                exit();
            } else {
                $loadLang =1;
                if (get_option('wopshop_version') === false){
                    $wshopInstaller = new WshopInstaller();
                    $wshopInstaller->install($this->version);
                    $loadLang = 0;
                }
                if($loadLang){
                    WopshopFactory::loadLanguageFile(null, 1);
                }
				flush_rewrite_rules();
            }		
        }
        
        public function deactivate(){
            WshopRouter::getInstance()->unregisterRewriteRules();
            flush_rewrite_rules();
        }
        
        private function loadCoreFiles(){
            require_once dirname(__FILE__) . "/autoload.php";
            require_once dirname(__FILE__) . "/functions.php";
        }
        
        private function loadAddons(){
            global $wpdb;
            require_once WSHOP_PLUGIN_BASE_DIR ."/core/addon.php";
            
            $query = "SELECT `id`, `alias` FROM `".$wpdb->prefix."wshop_addons` WHERE `publish` = 1";
            $addons = $wpdb->get_results($query);
            
            foreach ($addons as $addon){
                $addonPath = WSHOP_PLUGIN_BASE_DIR ."/site/addons/".$addon->alias."/$addon->alias.php";
                
                if (file_exists($addonPath)){
                    require_once $addonPath;
                    $addonClass = ucfirst(str_replace('_', '', $addon->alias)).'WshopAddon';
                    
                    if (class_exists($addonClass) && method_exists($addonClass, 'loadActions')){
                        $addonObj = new $addonClass();
                        $addonObj->loadActions();
                    }                    
                }
            }
        }
    }
}

$wshop = new Wopshop();