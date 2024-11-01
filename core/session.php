<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
* @class        WshopAddon
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Session handler class.
 */
class WopshopSession extends WopshopWobject {
	
    /**
	 * Customer ID.
	 *
	 * @var int $_customer_id Customer ID.
	 */
	protected $_customer_id;

	/**
	 * Session Data.
	 *
	 * @var array $_data Data array.
	 */
	protected $_data = array();

	/**
	 * Dirty when the session needs saving.
	 *
	 * @var bool $_dirty When something changes
	 */
	protected $_dirty = false;
    
	/**
	 * Cookie name used for the session.
	 *
	 * @var string cookie name
	 */   
	protected $_cookie;

	/**
	 * Stores session expiry.
	 *
	 * @var string session due to expire timestamp
	 */
	protected $_session_expiring;

	/**
	 * Stores session due to expire timestamp.
	 *
	 * @var string session expiration timestamp
	 */
	protected $_session_expiration;

	/**
	 * True when the cookie exists.
	 *
	 * @var bool Based on whether a cookie exists.
	 */
	protected $_has_cookie = false;

	/**
	 * Table name for session data.
	 *
	 * @var string Custom session table name
	 */
	protected $_table;

	/**
	 * wpdb
	 *
	 */
	protected $_db;
    
    protected static $instance;
    
    
	public function __construct() {
		$this->_cookie = apply_filters( 'wopshop_cookie', 'wp_wopshop_session_' . COOKIEHASH );
		$this->_db  = $GLOBALS['wpdb'];
		$this->_table  = $GLOBALS['wpdb']->prefix . 'wshop_sessions';

        //$this->createTable();
        $this->init();
	}
    
//    public function createTable() {
//            $collate = '';
//            if ($this->_db->has_cap('collation')) {
//                $collate = $this->_db->get_charset_collate();
//            }
//
//            $table = "CREATE TABLE  IF NOT EXISTS {$this->_table} (
//                    `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
//                    `session_key` char(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
//                    `session_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
//                    `session_expiry` bigint(20) unsigned NOT NULL,
//                    PRIMARY KEY (`session_id`),
//                    UNIQUE KEY `session_key` (`session_key`)
//		    ) $collate;";
//
//            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//            dbDelta($table);
//    }
    
    
	public static function getInstance() {            
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
	}
    
	/**
	 * Init hooks and session data.
	 *
	 */
	public function init() {
		$this->initSessionCookie();

		add_action( 'shutdown', array( $this, 'saveData' ), 20 );
		add_action( 'wp_logout', array( $this, 'destroySession' ) );

		if ( ! is_user_logged_in() ) {
			add_filter( 'nonce_user_logged_out', array( $this, 'nonceUserLoggedOut' ) );
		}
	}
    
	public function initSessionCookie() {
		$cookie = $this->getSessionCookie();

		if ( $cookie ) {
			$this->_customer_id        = $cookie[0];
			$this->_session_expiration = $cookie[1];
			$this->_session_expiring   = $cookie[2];
			$this->_has_cookie         = true;
			$this->_data               = $this->getSessionData();

			// If the user logs in, update session.
			if ( is_user_logged_in() && strval( get_current_user_id() ) !== $this->_customer_id ) {
				$guest_session_id   = $this->_customer_id;
				$this->_customer_id = strval( get_current_user_id() );
				$this->_dirty       = true;
				$this->saveData( $guest_session_id );
				$this->setCustomerSessionCookie( true );
			}

			// Update session if its close to expiring.
			if ( time() > $this->_session_expiring ) {
				$this->setSessionExpiration();
				$this->updateSessionTimestamp( $this->_customer_id, $this->_session_expiration );
			}
		} else {
			$this->setSessionExpiration();
			$this->_customer_id = $this->generateCustomerId();
			$this->_data        = $this->getSessionData();
		}
	}
    
	protected function useSecureCookie() {
		return apply_filters( 'wopshop_session_use_secure_cookie', is_ssl() );
	}

	/**
	 * Return true if the current user has an active session, i.e. a cookie to retrieve values.
	 *
	 * @return bool
	 */
	public function hasSession() {
		return isset( $_COOKIE[ $this->_cookie ] ) || $this->_has_cookie || is_user_logged_in(); // @codingStandardsIgnoreLine.
	}

	/**
	 * Set session expiration.
	 */
	public function setSessionExpiration() {
		$this->_session_expiring   = time() + intval( apply_filters( 'wopshop_session_expiring', 60 * 60 * 47 ) ); // 47 Hours.
		$this->_session_expiration = time() + intval( apply_filters( 'wopshop_session_expiration', 60 * 60 * 48 ) ); // 48 Hours.
	}
    
    
	/**
	 * Generate a unique customer ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @return string
	 */
	public function generateCustomerId() {
		$customer_id = '';

		if ( is_user_logged_in() ) {
			$customer_id = strval( get_current_user_id() );
		}

		if ( empty( $customer_id ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher      = new PasswordHash( 8, false );
			$customer_id = md5( $hasher->get_random_bytes( 32 ) );
		}

		return $customer_id;
	}
    
	/**
	 * Get session unique ID for requests if session is initialized or user ID if logged in.
	 * Introduced to help with unit tests.
	 *
	 * @return string
	 */
	public function get_customer_unique_id() {
		$customer_id = '';

		if ( $this->hasSession() && $this->_customer_id ) {
			$customer_id = $this->_customer_id;
		} elseif ( is_user_logged_in() ) {
			$customer_id = (string) get_current_user_id();
		}

		return $customer_id;
	}
    
	/**
	 * Get the session cookie, if set. Otherwise return false.
	 *
	 * Session cookies without a customer ID are invalid.
	 *
	 * @return bool|array
	 */
	public function getSessionCookie() {
		$cookie_value = isset( $_COOKIE[ $this->_cookie ] ) ? wp_unslash( $_COOKIE[ $this->_cookie ] ) : false; // @codingStandardsIgnoreLine.

		if ( empty( $cookie_value ) || ! is_string( $cookie_value ) ) {
			return false;
		}

		list( $customer_id, $session_expiration, $session_expiring, $cookie_hash ) = explode( '||', $cookie_value );

		if ( empty( $customer_id ) ) {
			return false;
		}

		// Validate hash.
		$to_hash = $customer_id . '|' . $session_expiration;
		$hash    = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );

		if ( empty( $cookie_hash ) || ! hash_equals( $hash, $cookie_hash ) ) {
			return false;
		}

		return array( $customer_id, $session_expiration, $session_expiring, $cookie_hash );
	}
    
	/**
	 * Get session data.
	 *
	 * @return array
	 */
	public function getSessionData() {
		return $this->hasSession() ? (array) $this->getSession( $this->_customer_id, array() ) : array();
	}
    
	/**
	 * Save data and delete guest session.
	 *
	 * @param int $old_session_key session ID before user logs in.
	 */
	public function saveData( $old_session_key = 0 ) {
        //Don't save on cron tasks
        if( defined( 'DOING_CRON' ) && !defined( 'WOPSHOP_DOING_CRON' )){
            return;
        }
        
        //Create session if is something to store
        if($this->_dirty && !$this->hasSession()){
            $this->setCustomerSessionCookie( true );
        }
		// Dirty if something changed - prevents saving nothing new.
		if ( $this->_dirty && $this->hasSession() ) {
			$this->_db->query(
				$this->_db->prepare(
					"INSERT INTO {$this->_table} (`session_key`, `session_value`, `session_expiry`) VALUES (%s, %s, %d)
 					ON DUPLICATE KEY UPDATE `session_value` = VALUES(`session_value`), `session_expiry` = VALUES(`session_expiry`)",
					$this->_customer_id,
					maybe_serialize( $this->_data ),
					$this->_session_expiration
				)
			);

			$this->_dirty = false;
			if ( get_current_user_id() != $old_session_key && ! is_object( get_user_by( 'id', $old_session_key ) ) ) {
				$this->deleteSession( $old_session_key );
			}
		}
	}

	/**
	 * Destroy all session data.
	 */
	public function destroySession() {
		$this->deleteSession( $this->_customer_id );
		$this->forgetSession();
	}

	/**
	 * Forget all session data without destroying it.
	 */
	public function forgetSession() {      
        
        if ( ! headers_sent() ) {
            setcookie( $this->_cookie, '', time() - YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $this->useSecureCookie(), true  );
        } elseif (defined( 'WP_DEBUG' )  &&  ( WP_DEBUG === 1 || WP_DEBUG === true ) ) {
            headers_sent( $file, $line );
        }

		//wc_empty_cart();

		$this->_data        = array();
		$this->_dirty       = false;
		$this->_customer_id = $this->generateCustomerId();
	}
    
    
	/**
	 * Returns the session.
	 *
	 * @param string $customer_id Custo ID.
	 * @param mixed  $default Default session value.
	 * @return string|array
	 */
	public function getSession( $customer_id, $default = false ) {

		if ( defined( 'WP_SETUP_CONFIG' ) ) {
			return false;
		}

        $value = $this->_db->get_var( $this->_db->prepare( "SELECT session_value FROM $this->_table WHERE session_key = %s", $customer_id ) ); // @codingStandardsIgnoreLine.

        if ( is_null( $value ) ) {
            $value = $default;
        }

		return maybe_unserialize( $value );
	}

	/**
	 * Delete the session from the cache and database.
	 *
	 * @param int $customer_id Customer ID.
	 */
	public function deleteSession( $customer_id ) {
		$this->_db->delete(
			$this->_table,
			array(
				'session_key' => $customer_id,
			)
		);
	}

	/**
	 * Update the session expiry timestamp.
	 *
	 * @param string $customer_id Customer ID.
	 * @param int    $timestamp Timestamp to expire the cookie.
	 */
	public function updateSessionTimestamp( $customer_id, $timestamp ) {
		$this->_db->update(
			$this->_table,
			array(
				'session_expiry' => $timestamp,
			),
			array(
				'session_key' => $customer_id,
			),
			array(
				'%d',
			)
		);
	}
    
    
	/**
	 * Get a session variable.
	 *
	 * @param string $key Key to get.
	 * @param mixed  $default used if the session variable isn't set.
	 * @return array|string value of session variable
	 */
	public function get( $key, $default = null ) {
		$key = sanitize_key( $key );
		return isset( $this->_data[ $key ] ) ? maybe_unserialize( $this->_data[ $key ] ) : $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $key Key to set.
	 * @param mixed  $value Value to set.
	 */
	public function set( $key, $value = null ) {
        $old = $this->get( $key );
        if(null === $value){
            unset($this->_data[ sanitize_key( $key ) ]);
            $this->_dirty = true;
        }else{
            if ( $value !== $this->get( $key ) ) {
                $this->_data[ sanitize_key( $key ) ] = maybe_serialize( $value );
                $this->_dirty = true;
            }            
        }
        
        return $old;
	}

	/**
	 * Get customer ID.
	 *
	 * @return int
	 */
	public function getCustomerId() {
		return $this->_customer_id;
	}
    
	/**
	 * When a user is logged out, ensure they have a unique nonce by using the customer/session ID.
	 *
	 * @param int $uid User ID.
	 * @return int|string
	 */
	public function nonceUserLoggedOut( $uid ) {
		return $this->hasSession() && $this->_customer_id ? $this->_customer_id : $uid;
	}

	/**
	 * Close Session
	 *
	 * deprecated
	 */    
    
	public function close() {
        //TODO
        // add wp_schedule_event
        $this->cleanupSessions();
		return true;
	}
    
	public function setCustomerSessionCookie( $set ) {
		if ( $set ) {
			$to_hash           = $this->_customer_id . '|' . $this->_session_expiration;
			$cookie_hash       = hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );
			$cookie_value      = $this->_customer_id . '||' . $this->_session_expiration . '||' . $this->_session_expiring . '||' . $cookie_hash;
			$this->_has_cookie = true;

			if ( ! isset( $_COOKIE[ $this->_cookie ] ) || $_COOKIE[ $this->_cookie ] !== $cookie_value ) {
                
                if ( ! headers_sent() ) {
                    setcookie( $this->_cookie, $cookie_value, $this->_session_expiration, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $this->useSecureCookie(), true );
                } elseif (defined( 'WP_DEBUG' ) &&  ( WP_DEBUG === 1 || WP_DEBUG === true ) ) {
                    headers_sent( $file, $line );
                }                
                
			}
		}
	}

	public function has($key) {
		return isset( $this->_data[ $key ] );
	}

	public function clear($key) {		
		unset($this->_data[ sanitize_key( $key ) ]);
		if (isset($this->_data[ sanitize_key( $key ) ])) {
			unset($this->_data[ sanitize_key( $key ) ]);
            $this->_dirty = true;
		}
		
		return true;
	}
    
	/**
	 * Cleanup session data from the database and clear caches.
	 */
	public function cleanupSessions() {
		$this->_db->query( $this->_db->prepare( "DELETE FROM $this->_table WHERE session_expiry < %d", time() ) ); // @codingStandardsIgnoreLine.
	}
}