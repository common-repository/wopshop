<?php

class FeedbackWshopAdminController extends WshopAdminController {
   
    public function __construct() {
        parent::__construct();
    }
    
    public function display() {
        
        $view = $this->getView('feedback');
		do_action_ref_array('onBeforeDisplayFeedback', array(&$view));
        $view->display();
    }

    public function sendform() {

        $name          = WopshopRequest::getString('name');
        $firma         = WopshopRequest::getString('firma');
        $email         = WopshopRequest::getString('email');        
        $text_feedback = WopshopRequest::getString('feedback_text');

        $config    = WopshopFactory::getConfig();
        $mainframe = WopshopFactory::getApplication();                
        
        if (empty($name) ) {
            wopshopAddMessage(_JHOP_ERROR_FEEDBACK_NAME, 'error');
            $this->setRedirect('admin.php?page=wopshop-feedback');
            return 0;
        } elseif (empty($email)) {
            wopshopAddMessage(_JHOP_ERROR_FEEDBACK_EMAIL, 'error');
            $this->setRedirect('admin.php?page=wopshop-feedback');
            return 0;
        } else {
            $to = 'anfrage@agentur-wp.com'; 
            $subject = WOPSHOP_FEEDBACK_SUBJECT;
            $headers[] = 'From: '.  get_bloginfo().' <'.get_option('admin_email') . ">\r\n"; 
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            
            $message = "<div>
                            <span>".WOPSHOP_FIELD_F_NAME.": ".$name."</span>
                        </div>
                        <div>
                            <span>".WOPSHOP_FIELD_EMAIL.": ".$email."</span>
                        </div>";
            if (!empty($firma)) {
                $message .= "<div>
                                <span>".WOPSHOP_FIELD_FIRMA_NAME.": ".$firma."</span>
                            </div>";
            }            
                        
            if (!empty($text_feedback)) {
                $message .= "<br />
                            <div>
                                <span>".$text_feedback."</span>
                            </div>";
            }

            wp_mail($to, $subject, $message, $headers);            

            $this->setRedirect('admin.php?page=wopshop-feedback', WOPSHOP_FEEDBACK_SEND);
        }        
    }

}