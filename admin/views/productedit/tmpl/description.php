<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php if ($this->multilang) : ?>
    <div class="tab" id="tab<?php echo esc_attr($index); ?>">
<?php endif; ?>
    <?php 
    $val_name = 'name_'.$language->language;
    $val_alias = 'alias_'.$language->language;
    $val_short_description = 'short_description_'.$language->language;
    $val_description = 'description_'.$language->language;
    $val_meta_title = 'meta_title_'.$language->language;
    $val_meta_description = 'meta_description_'.$language->language;
    $val_meta_keyword = 'meta_keyword_'.$language->language;
     ?>
     <table class="admintable">
         <tr>
             <td><label for="title_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_TITLE); ?></label></td>
             <td><input id="title_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php echo esc_attr($this->product->$val_name);?>" name="name_<?php echo esc_attr($language->language); ?>"></td>
         </tr>
         <tr>
             <td><label for="alias_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_ALIAS); ?></label></td>
             <td><input id="alias_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php echo esc_attr($this->product->$val_alias);?>" name="alias_<?php echo esc_attr($language->language); ?>"></td>
         </tr>
         <tr>
             <td><label for="short_description_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_SHORT_DESCRIPTION); ?></label></td>
             <td><textarea id="short_description_<?php echo esc_attr($language->language); ?>" type="text" size="40" name="short_description_<?php echo esc_attr($language->language); ?>"><?php echo wp_kses_post($this->product->$val_short_description);?></textarea></td>
         </tr>
         <tr>
             <td><label for="description_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_DESCRIPTION); ?></label></td>
             <td>
                 <?php 
                     $args = array('media_buttons' => 1,'textarea_name' => "description".$language->id,'textarea_rows' => 20,'tabindex'      => null,'tinymce'       => 1);
                     wp_editor( $this->product->$val_description, "description".$language->id, $args );
                 ?>
             </td>
         </tr>
         <tr>
             <td><label for="meta_title_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_META_TITLE); ?></label></td>
             <td><input id="meta_title_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php echo esc_attr($this->product->$val_meta_title);?>" name="meta_title_<?php echo esc_attr($language->language); ?>"></td>
         </tr>
         <tr>
             <td><label for="meta_description_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_META_DESCRIPTION); ?></label></td>
             <td><input id="meta_description_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php echo esc_attr($this->product->$val_meta_description);?>" name="meta_description_<?php echo esc_attr($language->language); ?>"></td>
         </tr>
         <tr>
             <td><label for="meta_keyword_<?php echo esc_attr($language->language); ?>"><?php echo esc_html(WOPSHOP_META_KEYWORDS); ?></label></td>
             <td><input id="meta_keyword_<?php echo esc_attr($language->language); ?>" type="text" size="40" value="<?php echo esc_attr($this->product->$val_meta_keyword);?>" name="meta_keyword_<?php echo esc_attr($language->language); ?>"></td>
         </tr>
		 <?php $pkey='plugin_template_description_'.$language->language; if ($this->$pkey){ print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
     </table>
<?php if ($this->multilang) : ?>
    </div>
<?php endif; ?>