<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class UserShopWshopTable extends WshopTable{

    function __construct(){
        global $wpdb;
        parent::__construct($wpdb->prefix.'wshop_users', 'user_id');
    }
    
	function isUserInShop($id) {
		$query = "SELECT COUNT(user_id) FROM `$this->_tbl` WHERE `user_id`='".esc_sql($id)."'";
		return $this->_db->get_var($query);
		//return $this->_db->getNumRows($res);
	}
    	
	function addUserToTableShop($user) {
		//$this->u_name = $user->username;
		$this->u_name = $user->user_login;
		$this->email = $user->email;
		$this->user_id = $user->id;
        $number = $this->getNewUserNumber();
        
        $usergroup = WopshopFactory::getTable('usergroup');
        $default_usergroup = $usergroup->getDefaultUsergroup();
        
		$query = "INSERT INTO `$this->_tbl` SET `usergroup_id`='".$default_usergroup."', `u_name`='".esc_sql($user->user_login)."', `email`='".esc_sql($user->email)."', `user_id`='".esc_sql($user->id)."', f_name='".esc_sql($user->name)."', `number`='".esc_sql($number)."'";
		$this->_db->query($query);
        do_action_ref_array('onAfterAddUserToTableShop', array(&$this));
	}
    
    public function store($upwopshop_datenulls = false){
        if (isset($this->percent_discount)) {
            $tmp = $this->percent_discount;
            unset($this->percent_discount);
        }
        do_action_ref_array('onBeforeStoreTableShop', array(&$this));
        $res = parent::store($upwopshop_datenulls);
        if (isset($tmp)) {
            $this->percent_discount = $tmp;
        }
        return $res;
    }

	function check($type = null){
        $types = explode(".",$type);
        $type = $types[0];
        if (isset($types[1])){
            $type2 = $types[1];
        }else{
            $type2 = '';
        }

        $config = WopshopFactory::getConfig();
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields[$type];
        do_action_ref_array('onBeforeCheckWshopUserShop', array(&$this, &$type, &$config_fields, &$type2));

        if ($config_fields['title']['require']){
            if (!intval($this->title)) {
                $this->_error = addslashes(WOPSHOP_REGWARN_TITLE);
                return false;
            }
        }
                		
        if ($config_fields['f_name']['require']){
		    if(trim($this->f_name) == '') {
			    $this->_error = addslashes(WOPSHOP_REGWARN_NAME);
			    return false;
		    }
        }

        if ($config_fields['l_name']['require']){
		    if(trim($this->l_name) == '') {
			    $this->_error = addslashes(WOPSHOP_REGWARN_LNAME);
			    return false;
		    }
        }
		
		if ($config_fields['m_name']['require']){
            if(trim($this->m_name) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_MNAME);
                return false;
            }
        }
        
        if ($config_fields['firma_name']['require']){
            if(trim($this->firma_name) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_FIRMA_NAME);
                return false;
            }
        }
        
        if ($config_fields['client_type']['require']){
            if(trim($this->client_type) == 0) {
                $this->_error = addslashes(WOPSHOP_REGWARN_CLIENT_TYPE);
                return false;
            }
        }

        if ($this->client_type==2 || !$config_fields['client_type']['display']){
            if ($config_fields['firma_code']['require']){
                if(trim($this->firma_code) == '') {
                    $this->_error = addslashes(WOPSHOP_REGWARN_FIRMA_CODE);
                    return false;
                }
            }
            
            if ($config_fields['tax_number']['require']){
                if(trim($this->tax_number) == '') {
                    $this->_error = addslashes(WOPSHOP_REGWARN_TAX_NUMBER);
                    return false;
                }
            }
        }        

		if ($config_fields['email']['require'] || $this->email!=''){
		    if ((trim($this->email=="")) || !is_email($this->email)){
			    $this->_error = addslashes(WOPSHOP_REGWARN_MAIL);
			    return false;
		    }
        }

        if ($config_fields['birthday']['require']){
            if(trim($this->birthday) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_BIRTHDAY);
                return false;
            }
        }		

		if ($type == "register"){
            if ($config_fields['u_name']['require']){
				if(trim($this->u_name) == '') {
					$this->_error = addslashes(WOPSHOP_REGWARN_UNAME);
					return false;
				}
			}
			if ($this->u_name!=''){
				if (preg_match("#[<>\"'%;()&]#i", $this->u_name) || strlen(utf8_decode($this->u_name )) < 2) {
					$this->_error = sprintf(addslashes(WOPSHOP_VALID_AZ09),addslashes(WOPSHOP_USERNAME),2);
					return false;
				}
				// check for existing username
				$query = "SELECT `user_id` FROM $this->_tbl WHERE username = '".  esc_sql($this->u_name)."' AND `user_id` != ".(int)$this->user_id;
                do_action_ref_array('onBeforeCheckUserNameExistWshopUserShop', array(&$this, &$type, &$config_fields, &$type2, &$query));
				$xid = intval($this->_db->get_var($query));
				if($xid && $xid != intval($this->user_id)){
					$this->_error = addslashes(WOPSHOP_REGWARN_INUSE);
					return false;
				}
			}
            if ($config_fields['password']['require'] && trim($this->password)==''){
                $this->_error = WOPSHOP_REGWARN_PASSWORD;
                return false;
            }

            if (($this->password || $this->password2) && $config_fields['password_2']['display'] && $this->password!=$this->password2){
                $this->_error = WOPSHOP_REGWARN_PASSWORD_NOT_MATCH;
                return false;
            }
		}
        
        if ($type=='editaccount'){
            if ($config_fields['password']['require']){
                if(trim($this->password) == '') {
                    $this->_error = addslashes(WOPSHOP_REGWARN_PASSWORD);
                    return false;
                }
            }
            if (($this->password || $this->password2) && $config_fields['password_2']['display'] && $this->password!=$this->password2){
                $this->_error = WOPSHOP_REGWARN_PASSWORD_NOT_MATCH;
                return false;
            }
        }
        
        if ($type2 == "edituser"){
			if ($config_fields['u_name']['require']){
				if(trim($this->u_name) == '') {
					$this->_error = addslashes(WOPSHOP_REGWARN_UNAME);
					return false;
				}
            }
			if ($this->u_name!=''){
				if (preg_match("#[<>\"'%;()&]#i", $this->u_name) || strlen(utf8_decode($this->u_name )) < 2){
					$this->_error = sprintf(addslashes(WOPSHOP_VALID_AZ09),addslashes(WOPSHOP_USERNAME),2);
					return false;
				}
				// check for existing username
				$query = "SELECT `user_id` FROM $this->_tbl WHERE username = '".  esc_sql($this->u_name)."' AND `user_id` != ".(int)$this->user_id;
                do_action_ref_array('onBeforeCheckUserNameExistWshopUserShop', array(&$this, &$type, &$config_fields, &$type2, &$query));
				$xid = intval($this->_db->get_var($query));
				if($xid && $xid != intval($this->user_id)){
					$this->_error = addslashes(WOPSHOP_REGWARN_INUSE);
					return false;
				}
            }
            if ($this->password && $this->password!=$this->password2){
                $this->_error = WOPSHOP_REGWARN_PASSWORD_NOT_MATCH;
                return false;
            }
        }
        
		if ($this->email!=''){
			// check for existing email
			$query = "SELECT `user_id` FROM $this->_tbl WHERE email='".  esc_sql($this->email)."' AND `user_id` != ".(int)$this->user_id;
            do_action_ref_array('onBeforeCheckUserEmailExistWshopUserShop', array(&$this, &$type, &$config_fields, &$type2, &$query));
			$xid = intval($this->_db->get_var($query));
			if($xid && $xid != intval($this->id)){
				$this->_error = addslashes(WOPSHOP_REGWARN_EMAIL_INUSE);
				return false;
			}
		}
		
        if ($config_fields['home']['require']){
            if(trim($this->home) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_HOME);
                return false;
            }
        }
        
        if ($config_fields['apartment']['require']){
            if(trim($this->apartment) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_APARTMENT);
                return false;
            }
        }
        
        if ($config_fields['street']['require']){
		    if(trim($this->street) == '') {
			    $this->_error = addslashes(WOPSHOP_REGWARN_STREET);
			    return false;
		    }
        }
        
        if ($config_fields['street_nr']['require']){
            if(trim($this->street_nr) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_STREET);
                return false;
            }
        }
		
        if ($config_fields['zip']['require']){
		    if (trim($this->zip) == ""){
		        $this->_error = addslashes( WOPSHOP_REGWARN_ZIP );
		        return false;
		    }
        }
        
        if ($config_fields['city']['require']){
		    if (trim($this->city) == ''){
		        $this->_error = addslashes( WOPSHOP_REGWARN_CITY );
		        return false;
		    }
        }		
        
        if ($config_fields['state']['require']){
            if (trim($this->state) == ''){
                $this->_error = addslashes( WOPSHOP_REGWARN_STATE ); //region
                return false;
            }
        }
        
        if ($config_fields['country']['require']){
		    if(!intval($this->country)) {
			    $this->_error = addslashes(WOPSHOP_REGWARN_COUNTRY);
			    return false;
		    }
        }		
        	
        if ($config_fields['phone']['require']){	
		    if(trim($this->phone) == '') {
		        $this->_error = addslashes(WOPSHOP_REGWARN_PHONE);
		        return false;
		    }
        }
        
        if ($config_fields['mobil_phone']['require']){    
            if(trim($this->mobil_phone) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_MOBIL_PHONE);
                return false;
            }
        }
        
        if ($config_fields['fax']['require']){    
            if(trim($this->fax) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_FAX);
                return false;
            }
        }
        
        if ($config_fields['ext_field_1']['require']){
            if(trim($this->ext_field_1) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_1);
                return false;
            }
        }
        
        if ($config_fields['ext_field_2']['require']){
            if(trim($this->ext_field_2) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_2);
                return false;
            }
        }
        
        if ($config_fields['ext_field_3']['require']){
            if(trim($this->ext_field_3) == '') {
                $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_3);
                return false;
            }
        }
        
		if ($type == "address" || $type == "editaccount") {
			if ($this->delivery_adress) {
                            
                if ($config_fields['d_title']['require']){
                    if(!intval($this->d_title)) {
                        $this->_error = addslashes(WOPSHOP_REGWARN_TITLE_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_f_name']['require']){
				    if(trim($this->d_f_name) == '') {
					    $this->_error = addslashes(WOPSHOP_REGWARN_NAME_DELIVERY);
					    return false;
				    }
                }

                if ($config_fields['d_l_name']['require']){
				    if(trim($this->d_l_name) == '') {
					    $this->_error = addslashes(WOPSHOP_REGWARN_LNAME_DELIVERY);
					    return false;
				    }
                }
				
				if ($config_fields['d_m_name']['require']){
                    if(trim($this->d_m_name) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_MNAME_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_firma_name']['require']){
                    if(trim($this->d_firma_name) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_FIRMA_NAME_DELIVERY);
                        return false;
                    }
                }
                
                if (isset($config_fields['d_firma_code']) && $config_fields['d_firma_code']['require']){
                    if(trim($this->d_firma_code) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_FIRMA_CODE_DELIVERY);
                        return false;
                    }
                }
                
                if (isset($config_fields['d_tax_number']) && $config_fields['d_tax_number']['require']){
                    if(trim($this->d_tax_number) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_TAX_NUMBER_DELIVERY);
                        return false;
                    }
                }                

                if ($config_fields['d_email']['require']){
                    if ( (trim($this->d_email) == "") || !is_email($this->d_email)) {
                        $this->_error = addslashes(WOPSHOP_REGWARN_MAIL_DELIVERY);
                        return false;
                    }
                }
				
				if ($config_fields['d_birthday']['require']){
                    if(trim($this->d_birthday) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_BIRTHDAY_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_home']['require']){
                    if(trim($this->d_home) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_HOME_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_apartment']['require']){
                    if(trim($this->d_apartment) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_APARTMENT_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_street']['require']){
				    if(trim($this->d_street) == '') {
					    $this->_error = addslashes(WOPSHOP_REGWARN_STREET_DELIVERY);
					    return false;
				    }
                }
                
                if ($config_fields['d_street_nr']['require']){
                    if(trim($this->d_street_nr) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_STREET_DELIVERY);
                        return false;
                    }
                }
				
                if ($config_fields['d_zip']['require']){
                    if (trim($this->d_zip) == ""){
				        $this->_error = addslashes( WOPSHOP_REGWARN_ZIP_DELIVERY );
				        return false;
				    }
                }
                
                if ($config_fields['d_city']['require']){
                    if (trim($this->d_city) == ''){
                        $this->_error = addslashes( WOPSHOP_REGWARN_CITY_DELIVERY );
                        return false;
                    }
                }

                if ($config_fields['d_state']['require']){
				    if (trim($this->d_state) == ''){
				        $this->_error = addslashes( WOPSHOP_REGWARN_STATE_DELIVERY );
				        return false;
				    }
                }
                
                if ($config_fields['d_country']['require']){
				    if(!intval($this->d_country)) {
                        $this->_error = addslashes(WOPSHOP_REGWARN_COUNTRY_DELIVERY);
                        return false;
                    }
                }                                
				
                if ($config_fields['d_phone']['require']){
				    if(trim($this->d_phone) == '') {
				        $this->_error = addslashes(WOPSHOP_REGWARN_PHONE_DELIVERY);
				        return false;
				    }
                }
                
                if ($config_fields['d_mobil_phone']['require']){    
                    if(trim($this->d_mobil_phone) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_MOBIL_PHONE_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_fax']['require']){    
                    if (trim($this->d_fax) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_FAX_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_ext_field_1']['require']){
                    if(trim($this->d_ext_field_1) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_1_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_ext_field_2']['require']){
                    if(trim($this->d_ext_field_2) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_2_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_ext_field_3']['require']){
                    if(trim($this->d_ext_field_3) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_EXT_FIELD_3_DELIVERY);
                        return false;
                    }
                }
                                
			}
		}        

		return true;
	}
    
	function getCountryId($id_user) {
		$query = "SELECT country FROM `$this->_tbl` WHERE user_id = '" . esc_sql($id_user) . "'";
		return $this->_db->get_var();

	}
	
	function getDiscount(){
		$query = "SELECT usergroup.usergroup_discount FROM `".$this->_db->prefix."wshop_usergroups` AS usergroup
				  INNER JOIN `".$this->_db->prefix."wshop_users` AS users ON users.usergroup_id = usergroup.usergroup_id
				  WHERE users.user_id = '".  esc_sql($this->user_id)."' ";
		return floatval($this->_db->get_var($query));
	}
    
    function saveTypePayment($id){
        $this->payment_id = $id;
        $this->store();
        return 1;
    }
    
    function saveTypeShipping($id){
        $this->shipping_id = $id;
        $this->store();
        return 1;
    }
    
    function getError($i = null, $toString = true){
        return $this->_error;
    }
    
    function setError($error){
        $this->_error = $error;
    }
    
    function getNewUserNumber(){
        $number = $this->user_id;
        do_action_ref_array('onBeforeGetNewUserNumber', array(&$this, &$number));
        return $number;
    }
}