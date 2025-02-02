<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class WshopUserGust extends WopshopWobject{
    
    var $user_id = null;
    var $usergroup_id = null;
    var $payment_id = null;
    var $shipping_id = null;
    
    var $title = null;
    var $u_name = null;
    var $f_name = null;
    var $l_name = null;
	var $m_name = null;
    var $firma_name = null;
    var $client_type = null;
    var $firma_code = null;
    var $tax_number = null;
    var $email = null;    
	var $birthday = null;
    var $home = null;
    var $apartment = null;
    var $street = null;
    var $street_nr = null;
    var $zip = null;
    var $city = null;
    var $state = null;
    var $country = null;
    var $phone = null;
    var $mobil_phone = null;
    var $fax = null;
    var $ext_field_1 = null;
    var $ext_field_2 = null;
    var $ext_field_3 = null;
    
    var $delivery_adress = null;
    var $d_title = null;
    var $d_f_name = null;
    var $d_l_name = null;
	var $d_m_name = null;
    var $d_firma_name = null;
    var $d_email = null;
	var $d_birthday = null;
    var $d_home = null;
    var $d_apartment = null;
    var $d_street = null;
    var $d_street_nr = null;
    var $d_city = null;
    var $d_zip = null;
    var $d_state = null;
    var $d_country = null;
    var $d_phone = null;
    var $d_mobil_phone = null;    
    var $d_fax = null;
    var $d_ext_field_1 = null;
    var $d_ext_field_2 = null;
    var $d_ext_field_3 = null;

	public function __construct() {
		parent::__construct();
	}

	public function load(){
        $config = WopshopFactory::getConfig();
        $session = WopshopFactory::getSession();
        $objuser = $session->get('user_shop_guest');
        if (isset($objuser) && $objuser!=''){
            $tmp = json_decode($objuser);
            foreach($tmp as $k=>$v){
                $this->$k = $v;
            }
        }
        $this->user_id = -1;
        $usergroup = WopshopFactory::getTable('usergroup');
        $this->usergroup_id = isset($config->default_usergroup_id_guest) ? intval($config->default_usergroup_id_guest) : 0;
        do_action_ref_array('onLoadWshopUserGust', array(&$this));
    return true;
    }

	public function bind($from, $ignore=array()){
        $fromArray = is_array($from);
        $fromObject = is_object($from);

        if (!$fromArray && !$fromObject){
            return false;
        }
        
        foreach ($this->getProperties() as $k => $v){
            if (!in_array( $k, $ignore )){
                if ($fromArray && isset( $from[$k] )) {
                    $this->$k = $from[$k];
                } else if ($fromObject && isset( $from->$k )) {
                    $this->$k = $from->$k;
                }
            }
        }
        return true;
    }

	public function store(){
        $this->user_id = -1;
        $session = WopshopFactory::getSession();
        $session->set('user_shop_guest', json_encode($this));
        do_action_ref_array('onAfterStoreWshopUserGust', array(&$this));
    return true;
    }

	public function check($type){
        
        $config = WopshopFactory::getConfig();
        $tmp_fields = $config->getListFieldsRegister();
        $config_fields = $tmp_fields[$type];
        
        do_action_ref_array('onBeforeCheckWshopUserGust', array(&$this, &$type, &$config_fields));
        
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
        
        if ($type == "address" || $type == "editaccount"){
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
                
                if ($config_fields['d_firma_code']['require']){
                    if(trim($this->d_firma_code) == '') {
                        $this->_error = addslashes(WOPSHOP_REGWARN_FIRMA_CODE_DELIVERY);
                        return false;
                    }
                }
                
                if ($config_fields['d_tax_number']['require']){
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

	public function saveTypePayment($id){
        $this->payment_id = $id;
        $this->store();
        return 1;
    }

	public function saveTypeShipping($id){
        $this->shipping_id = $id;
        $this->store();
        return 1;
    }

	public function getError($i = null, $toString = true){
        return $this->_error;
    }
}

?>