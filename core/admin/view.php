<?php
/**
* @version      1.0.0 01.06.2016
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class WshopAdminView {
    protected $controller;
    protected $templatePath;
    protected $layout = 'default';
    
    public function __construct($controller, $templatePath = null) {
        $this->controller = $controller;
        $this->templatePath = $templatePath;
    }
    
    public function setLayout($layout) {
        $this->layout = $layout;
    }
 
    public function assign($name, &$val) {
        $this->$name = $val;
    }    
    
    public function display() {
        $result = $this->loadTemplate();
        
        if ($result instanceof Exception) {
			return $result;
		}
		echo $result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    
    public function loadTemplate(){
        $template = $this->getTemplatePath() . $this->layout . ".php";
        
        if (file_exists($template)){
            // Start capturing output into a buffer
			ob_start();

			// Include the requested template filename in the local scope
			// (this will execute the view logic).
			include $template;

			// Done with the requested template; get the buffer and
			// clear it.
			$this->_output = ob_get_contents();
			ob_end_clean();

			return $this->_output;           
        } else {
            throw new Exception('File not found: '.$template, 500);
        }              
    }
    
    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;
    }
    
    public function getTemplatePath(){
        if ($this->templatePath !== null){
            return trailingslashit($this->templatePath);
        }
        
        return WOPSHOP_PLUGIN_ADMIN_DIR . '/views/' . $this->controller . '/tmpl/';
    }
    
    
    public function renderSortableCol($name, $title, $extraClasses='', $span = 1, $width='auto'){
        $class_name = $this->filter_order == $name? 'sorted' : 'sortable';
        $colspan = $span > 1 ? 'colspan="' . $span . '" ' : '';
        $w = $width == 'auto'? '' : ' width="'.$width .'" ';
        $link = esc_url( admin_url( 'admin.php?page=wopshop-'.$this->page.'&filter_order='.$name.'&filter_order_Dir='.$this->filter_order_Dir ) );
        return '<th '. $colspan . $w . 'scope="col" id="' .  esc_attr( $name ) . '" class="manage-column column-'.esc_attr( $name ) . ' ' . esc_attr( $extraClasses ) . ' ' . esc_attr( $class_name ) . ' ' . esc_attr( $this->filter_order_Dir ) . '"><a href="' . $link . '"><span>' . esc_html( $title ) . '</span><span class="sorting-indicator"></span></a></th>';
    }
    
    public function renderCol($name, $title, $extraClasses=''){
        return '<th scope="col" id="'. esc_attr( $name ) .'" class="manage-column column-' .esc_attr( $name ) . ' ' . esc_attr( $extraClasses ) . '">' . esc_html( $title ) . '</th>';
    }
    
    public function renderRowActionEdit($id){
        $link = esc_url( admin_url( 'admin.php?page=wopshop-'.$this->page.'&task=edit&row='.$id ) );
        return '<span class="edit"><a title="'. esc_attr( WOPSHOP_EDIT ).'" href="'.$link.'">'._MAXX_EDIT.'</a></span>';
    }
    
    public function renderRowActionDelete($id){
        $link = esc_url( admin_url( 'admin.php?page=wopshop-'.$this->page.'&task=delete&row='.$id ) );
        return '<span class="edit"><a title="'. esc_attr( WOPSHOP_EDIT ).'" href="'.$link.'">'._MAXX_EDIT.'</a></span>';
    }
    
    public function renderOrderDown($id){
        $link = esc_url( admin_url( 'admin.php?page=wopshop-'.$this->page.'&task=order&order=down&id='.$id ) );
        return '<a class="btn btn-micro" href="' . $link . '"><img alt="' . esc_attr( WOPSHOP_UP ) . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; 
    }
    
    public function renderOrderUp($id){
        $link = esc_url( admin_url( 'admin.php?page=wopshop-'.$this->page.'&task=order&order=up&id='.$id ) );
        return '<a class="btn btn-micro" href="' .$link . '"><img alt="' . esc_attr( WOPSHOP_UP ) . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; 
    }
    
}