<?php 
/**
* @version      1.0.0 30.01.2017
* @author       MAXXmarketing GmbH
* @package      WOPshop
* @copyright    Copyright (C) 2010 http://www.wop-agentur.com. All rights reserved.
* @license      GNU/GPL
*/
//defined('_JEXEC') or die('Restricted access');

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wshop vendordetailinfo" id="wshop_plugin">
    <?php if ($this->header) : ?>
        <h1><?php print esc_html($this->header) ?></h1>
    <?php endif; ?>
    
    <div class = "row-fluid">
        <div class = "span6">
            <table class="vendor_info">
            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_F_NAME) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->f_name) ?>
                </td>
            </tr>

            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_L_NAME) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->l_name) ?>
                </td>
            </tr>        
            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_FIRMA_NAME) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->company_name) ?>
                </td>
            </tr>

            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_EMAIL) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->email) ?>
                </td>
            </tr>        
            <tr>
                <td  class="name">
                    <?php print esc_html(WOPSHOP_STREET_NR) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->adress) ?>
                </td>
            </tr>

            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_ZIP) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->zip) ?>
                </td>
            </tr>        
            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_CITY) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->city) ?>
                </td>
            </tr>        
            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_STATE) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->state) ?>
                </td>
            </tr>

            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_COUNTRY) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->country) ?>
                </td>
            </tr>

            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_TELEFON) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->phone) ?>
                </td>
            </tr>
            
            <tr>
                <td class="name">
                    <?php print esc_html(WOPSHOP_FAX) ?>:
                </td>
                <td>
                    <?php print esc_html($this->vendor->fax) ?>
                </td>
            </tr>
            </table>
        </div>
        <div class = "span6 vendor_logo">
            <?php if ($this->vendor->logo!="") : ?>
                <img src="<?php print esc_url($this->vendor->logo)?>" alt="<?php print esc_attr($this->vendor->shop_name);?>" />
            <?php endif; ?>
        </div>
    </div>
</div>    