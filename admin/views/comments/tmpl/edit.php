<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('reviews');
?>
<h3><?php echo esc_html($this->review->review_id ? WOPSHOP_EDIT_REVIEW :  WOPSHOP_NEW_REVIEW); ?></h3>
<div class="wop_shop_edit">
<form name="product" method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews&task=save'))?>" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
     <div class="col100">
     <table class="admintable" >
       <?php if ($this->review->review_id){ ?>
       <tr>
         <td class="key" style="width:180px;">
           <?php echo esc_html(WOPSHOP_NAME_PRODUCT); ?>
         </td>
         <td>
           <?php echo esc_html($this->review->name)?>     
           <input type="hidden" name="product_id" value="<?php print esc_attr($this->review->product_id);?>">
         </td>
       </tr>
       <?php }else { ?>
       <tr>
         <td class="key" style="width:180px;">
           <?php echo esc_html(WOPSHOP_PRODUCT_ID);?>*
         </td>
         <td>
           <input type="text" name="product_id" value="">    
         </td>
       </tr>    
       <?php } ?>
       <tr>
         <td class="key" style="width:180px;">
           <?php echo esc_html(WOPSHOP_USER); ?>*
         </td>
         <td>
           <input type="text" class="inputbox" size="50" name="user_name" value="<?php echo esc_attr($this->review->user_name)?>" />
         </td>
       </tr>
       <tr>
         <td class="key" style="width:180px;">
           <?php echo esc_html(WOPSHOP_EMAIL); ?>*
         </td>
         <td>
           <input type="text" class="inputbox" size="50" name="user_email" value="<?php echo esc_attr($this->review->user_email)?>" />
         </td>
       </tr>       
              
       <tr>
         <td  class="key">
           <?php echo esc_html(WOPSHOP_PRODUCT_REVIEW); ?>*
         </td>
         <td>
           <textarea name="review" cols="35"><?php echo $this->review->review  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></textarea>
         </td>
       </tr>
       <tr>
        <td class="key">
          <?php echo esc_html(WOPSHOP_REVIEW_MARK); ?> 
        </td>
        <td>
            <?php print $this->mark; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </td>
       </tr>
     </table>
     </div>
     <div class="clr"></div>
     <input type="hidden" name="review_id" value="<?php echo esc_attr($this->review->review_id)?>">
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
     <br>    
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a  class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=reviews'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <?php wp_nonce_field('review_edit','name_of_nonce_field'); ?>

</form>
</div>