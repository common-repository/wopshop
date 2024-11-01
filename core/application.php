<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
* @class        Application
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class WopshopApplication extends WopshopWobject {

    protected $is_admin = null;
    protected $_messageQueue = array();
    public $tags = array();
    protected $_name = null;
    public $scope = null;
    public $input = null;
    protected static $instances = array();

    public function __construct($config = array()) {
        // Set the view name.
        $this->_name = $this->getName();

        // Only set the clientId if available.
        if (isset($config['clientId'])) {
            $this->is_admin = $config['clientId'];
        } else {
			if (defined('DOING_AJAX')){
                if (isset($_GET['client']) and $_GET['client'] == 'admin'){
                    $this->is_admin = 1;
                } else{
                    $this->is_admin = 0;
                }

                return;
            }
		
            if (is_admin()) {
                $this->is_admin = 1;
            } else {
                $this->is_admin = 0;
            }
        }

        // Create the input object
        if (class_exists('WopshopRequest')) {
            $this->input = new WopshopRequest();
        }

    }

    public static function getInstance($client, $config = array()) {
        if (empty(self::$instances[$client])) {
            $instance = new WopshopApplication($config);

            self::$instances[$client] = $instance;
        }

        return self::$instances[$client];
    }

    public function initialise($options = array()) {
        if ($this->get('initialised') == true) {
            return;
        }
		
		#for-multi-db
        if (!defined('COOKIEHASH')) {
            wp_cookie_constants();
        }

        $this->set('initialised', true);
    }

    public function route($component = null) {
        if ($this->get('routed') == true) {
            return;
        }

        $vars = $this->parse($component);

        WopshopRequest::set($vars, 'get', false);

        $this->set('routed', true);
    }

    public function parse($component = null) {
        if ($this->get('parsed') == true) {
            $result = $this->get('parsed_vars');
        }
        else {
            if (!empty($component)){
                WopshopRequest::setVar('option', $component);
            }

            $uri = clone WopshopUri::getInstance();

            $router = $this->getRouter();
            $result = $router->parse($uri);

            $this->set('parsed_vars', $result);
            $this->set('parsed', true);
        }

        if (!is_array($result)) {
            $result = array();
        }

        return $result;
    }

    public function redirect($url, $msg = '', $msgType = 'message') {
        // If the message exists, enqueue it.
        if (trim($msg)) {
            $this->enqueueMessage($msg, $msgType);
        }

        // Persist messages if they exist.
        if (count($this->_messageQueue)) {
            $session = WopshopFactory::getSession();
            $session->set('application.queue', $this->_messageQueue);
        }
        if (headers_sent()) {
            echo "<script>document.location.href='".esc_url_raw($url)."';</script>\n";
        } else {            
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( 'Location: ' . esc_url($url) );
            die();
        }
    }

    public function enqueueMessage($msg, $type = 'updated') {
        $session      = WopshopFactory::getSession();
        // Enqueue the message.
        $this->_messageQueue[] = array('message' => $msg, 'type' => strtolower($type));
        $session->set('application.queue', $this->_messageQueue);
    }

    public function getMessageQueue() {
        // For empty queue, if messages exists in the session, enqueue them.
        if (!count($this->_messageQueue)) {
            $session      = WopshopFactory::getSession();
            $sessionQueue = $session->get('application.queue');
            
            if (!empty($sessionQueue) && count($sessionQueue)) {
                $this->_messageQueue = $sessionQueue;
                $session->set('application.queue', null);
            }
        }

        return $this->_messageQueue;
    }

    public function getName() {
        $name = $this->_name;

        if (empty($name)) {
            $r = null;
            if (!preg_match('/(.*)/i', get_class($this), $r)) {
            }
            $name = strtolower($r[1]);
        }

        return $name;
    }

    public function getUserState($key, $default = null) {
        return WopshopFactory::getSession()->get($key, $default);
    }

    public function setUserState($key, $value) {
        return WopshopFactory::getSession()->set($key, $value);
    }

    public function getUserStateFromRequest($key, $request, $default = null, $type = 'none') {
        $cur_state = $this->getUserState($key, $default);
        $new_state = WopshopRequest::getVar($request, null, 'default', $type);

        // Save the new value only if it was set in this request.
        if ($new_state !== null) {
            $this->setUserState($key, $new_state);
        }
        else {
            $new_state = $cur_state;
        }
        return $new_state;
    }

    public function login($credentials, $secure_cookie = false) {
        $creds = array();
        $creds['user_login'] = $credentials['username'];
        $creds['user_password'] = $credentials['password'];
        $creds['remember'] = $credentials['remember'];
        return wp_signon($creds, $secure_cookie);
    }    
    
    public static function getHash($seed) {
        return md5($seed);
    }

    protected function _createSession($name) {
	    $options = array();
	    $options['name'] = $name;
	    $session = WopshopFactory::getSession($options);

	    return $session;
    }

    public function getClientId() {
        return $this->is_admin;
    }

    public function isAdmin() {
        return ($this->is_admin == 1);
    }

    public function isSite() {
        return ($this->is_admin == 0);
    }

    public static function isWinOS() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    public function __toString() {
        $compress = $this->getCfg('gzip', false);

        return Response::toString($compress);
    }

    public function getParams($option = null) {
        static $params = array();

        $hash = '__default';
        if (!empty($option)) {
            $hash = $option;
        }

        if (!isset($params[$hash])) {
            // Get component parameters
            if (!$option) {
                $option = WopshopRequest::getCmd('page');
            }
            // Get new instance of component global parameters
        }

        return $params[$hash];
    }

    public function getPageParameters($option = null) {
        return $this->getParams($option);
    }

}