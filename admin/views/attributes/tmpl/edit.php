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
?>
<form method="POST" action="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributes&task=save' ) )?>" enctype="multipart/form-data">
    <div class="wrap">
		<h2><?php echo esc_html ( $this->attribut->attr_id ? WOPSHOP_EDIT_ATTRIBUT . ' / ' . $this->attribut->{ WopshopFactory::getLang()->get('name') } : WOPSHOP_NEW_ATTRIBUT ); ?></h2>
        <?php print $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="admintable" width = "100%" >
            <?php 
            foreach($this->languages as $lang){
                $name = "name_".$lang->language;
            ?>
            <tr>
               <td class="key">
                 <?php echo esc_html(WOPSHOP_TITLE ); ?> <?php if ( $this->multilang ) print "(" . esc_html ( $lang->lang ) . ")"; ?>*
               </td>
               <td>
                 <input type = "text" class = "inputbox" name = "<?php print esc_attr($name)?>" value = "<?php echo esc_attr($this->attribut->$name)?>" />
               </td>
            </tr>
            <?php } ?>
            <?php 
            foreach($this->languages as $lang){
            $description = "description_".$lang->language;
            ?>
            <tr>
               <td class="key">
                 <?php echo esc_html(WOPSHOP_DESCRIPTION ); ?> <?php if ($this->multilang) print esc_html($lang->lang).")";?>
               </td>
               <td>
                 <input type = "text" class = "inputbox" name = "<?php print esc_attr($description)?>" value = "<?php echo esc_attr($this->attribut->$description)?>" />
               </td>
            </tr>
            <?php } ?>

            <tr>
                <td class="key">
                    <?php echo esc_html( WOPSHOP_TYPE_ATTRIBUT );?>*
                </td>
                <td>
                    <?php echo $this->type_attribut; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php echo WopshopHtml::tooltip(WOPSHOP_INFO_TYPE_ATTRIBUT); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>

            <tr>
                <td class="key">
                    <?php echo esc_html( WOPSHOP_DEPENDENT );?>*
                </td>
                <td>
                    <?php echo $this->dependent_attribut; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php echo WopshopHtml::tooltip(WOPSHOP_INFO_DEPENDENT_ATTRIBUT); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <tr>
             <td  class="key">
               <?php echo esc_html( WOPSHOP_GROUP );?>
             </td>
             <td>
               <?php echo $this->lists['group']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
            </tr>
            <tr>
             <td  class="key">
               <?php echo esc_html( WOPSHOP_SHOW_FOR_CATEGORY );?>*
             </td>
             <td>
               <?php echo $this->lists['allcats']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
           </tr>
           <tr id="tr_categorys" <?php if ($this->attribut->allcats=="1") print "style='display:none;'"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
             <td  class="key">
               <?php echo esc_html( WOPSHOP_CATEGORIES );?>*
             </td>
             <td>
               <?php echo $this->lists['categories']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </td>
           </tr>
           <?php $pkey="etemplatevar"; if ($this->$pkey){?>
           <?php print $this->$pkey; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
           <?php }?>
        </table>
        <?php echo $this->tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
    
    <div class="submit">
        <p class="submit">
            <input class="button button-primary" type="submit" value="<?php echo esc_attr( WOPSHOP_ACTION_SAVE ); ?>" name="submit">
            <a class="button" href="<?php echo esc_url (admin_url( 'admin.php?page=wopshop-options&tab=attributes' ) )?>"><?php echo esc_html( WOPSHOP_BACK ); ?></a>
        </p> 
    </div>
    <input type="hidden" value="<?php echo esc_attr($this->attribut->attr_id); ?>" name="attr_id">
    <?php wp_nonce_field('attributes_edit'); ?>
</form>