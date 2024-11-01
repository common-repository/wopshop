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

global $wshop, $pagenow;
if($pagenow == 'nav-menus.php') { ?>
    <style>.customlinkdiv > div{margin: 10px;}  .customlinkdiv{margin:20px 0px;} #add-wopshop .menu-item-textbox, #add-wopshop .chzn-done, #add-wopshop label.howto{width: 100%;}</style>
    <div class="customlinkdiv">
		    <label class="howto" for="custom-menu-item-category">
                <span><?php echo 'Category ID'; ?>* </span>
                <input id="custom-menu-item-category" class="code menu-item-textbox" type="text" value="" name="category_id">
            </label>
        <a href="#" id="wshop_add_nav_category" class="button secondary-button"><?php echo esc_html(WOPSHOP_CATEGORY)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
		    <label class="howto" for="custom-menu-item-user">
                <span><?php echo 'User'; ?> </span>
                <select id="custom-menu-item-user" class="chzn-done" name="user">
                    <option selected="selected" value="myaccount"><?php echo esc_html(WOPSHOP_MY_ACCOUNT)?></option>
                    <option value="register"><?php echo esc_html(WOPSHOP_REGISTRATION)?></option>
                    <option value="login"><?php echo esc_html(WOPSHOP_LOGIN)?></option>
                    <option value="logout"><?php echo esc_html(WOPSHOP_LOGOUT)?></option>
                    <option value="editaccount"><?php echo esc_html(WOPSHOP_EDIT_ACCOUNT)?></option>
                    <option value="orders"><?php echo esc_html(WOPSHOP_MY_ORDERS)?></option>
                </select>
            </label>
        <div><a href="#" id="wshop_add_nav_user" class="button secondary-button"><?php echo esc_html(WOPSHOP_CLIENTS)?></a></div>
    </div>
	<hr>

    <div class="customlinkdiv">
        <a href="#" id="wshop_add_nav_cart" class="button secondary-button"><?php echo esc_html(WOPSHOP_CART)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
        <a href="#" id="wshop_add_nav_wishlist" class="button secondary-button"><?php echo esc_html(WOPSHOP_WISHLIST)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
        <a href="#" id="wshop_add_nav_search" class="button secondary-button"><?php echo esc_html(WOPSHOP_SEARCH_VIEW_DEFAULT_TITLE)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
        <a href="#" id="wshop_add_nav_listmanufacturer" class="button secondary-button"><?php echo esc_html(WOPSHOP_MAN_LIST)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
		<label class="howto" for="custom-menu-item-manufacturer">
			<span><?php echo 'Manufacturer ID'; ?> </span>
			<input id="custom-menu-item-manufacturer" class="code menu-item-textbox" type="text" value="" name="manufacturer_id">
		</label>		
        <a href="#" id="wshop_add_nav_manufacturer_id" class="button secondary-button"><?php echo esc_html(WOPSHOP_MAN)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
        <a href="#" id="wshop_add_nav_listcategory" class="button secondary-button"><?php echo esc_html(WOPSHOP_CATEGORY_LIST)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
		<label class="howto" for="custom-menu-item-listproducts">
			<span><?php echo 'List'; ?> </span>
			<select id="custom-menu-item-listproducts" class="chzn-done" name="listproducts">
				<option value=""><?php echo esc_html(WOPSHOP_QUERY_ALL)?></option>
				<option value="tophits"><?php echo esc_html(WOPSHOP_TOP_HITS)?></option>
				<option value="toprating"><?php echo esc_html(WOPSHOP_TOP_RATING)?></option>
				<option value="label"><?php echo esc_html(WOPSHOP_LABEL_PRODUCTS)?></option>
				<option value="bestseller"><?php echo esc_html(WOPSHOP_BESTSELLER)?></option>
				<option value="random"><?php echo esc_html(WOPSHOP_RANDOM)?></option>
				<option value="last"><?php echo esc_html(WOPSHOP_LAST_PRODUCTS)?></option>
			</select>
		</label>
		<label class="howto" for="custom-menu-item-category">
			<span><?php echo 'Category ID'; ?> </span>
			<input id="custom-menu-item-listproducts-category" class="code menu-item-textbox" type="text" value="" name="listproducts_category_id">
		</label>
		<label class="howto" for="custom-menu-item-manufacturer">
			<span><?php echo 'Manufacturer ID'; ?> </span>
			<input id="custom-menu-item-listproducts-manufacturer" class="code menu-item-textbox" type="text" value="" name="listproducts_manufacturer_id">
		</label>
		<label class="howto" for="custom-menu-item-label">
			<span><?php echo 'Label ID'; ?> </span>
			<input id="custom-menu-item-listproducts-labelid" class="code menu-item-textbox" type="text" value="" name="listproducts_label_id">
		</label>		
        <a href="#" id="wshop_add_nav_listproducts" class="button secondary-button"><?php echo esc_html(WOPSHOP_PRODUCTS_LIST)?></a>
    </div>
	<hr>
    <div class="customlinkdiv">
		<label class="howto" for="custom-menu-item-product">
			<span><?php echo 'Product ID'; ?> </span>
			<input id="custom-menu-item-product" class="code menu-item-textbox" type="text" value="" name="oneproduct_product_id">
		</label>		
        <a href="#" id="wshop_add_nav_product" class="button secondary-button"><?php echo esc_html(WOPSHOP_PRODUCT)?></a>
    </div>
    <?php
} 