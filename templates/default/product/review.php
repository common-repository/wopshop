<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if ($this->allow_review){?>

    <div class="review_header"><?php print esc_html(WOPSHOP_REVIEWS)?></div>
    
    <?php foreach($this->reviews as $curr){?>
        <div class="review_item">
            <div>
                <span class="review_user"><?php print esc_html($curr->user_name)?></span>,
                <span class='review_time'><?php print esc_html(wopshop_formatdate($curr->time));?></span>
            </div>
            <div class="review_text"><?php print wp_kses_post(nl2br($curr->review))?></div>
            <?php if ($curr->mark) {?>
                <div class="review_mark">
                    <?php print wopshopShowMarkStar($curr->mark); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                </div>
            <?php } ?> 
        </div>
    <?php }?>
    
    <?php if ($this->display_pagination){?>
        <table class="wshop_pagination">
            <tr>
                <td><div class="pagination"><?php print wp_kses_post($this->pagination)?></div></td>
            </tr>
        </table>
    <?php }?>
    <?php if ($this->allow_review > 0){?>
    
         
        <div class="review"><?php print esc_html(WOPSHOP_ADD_REVIEW_PRODUCT)?></div>
        <form action="<?php print esc_url(wopshopSEFLink('controller=product&task=reviewsave'))?>" name="add_review" method="post" onsubmit="return validateReviewForm(this.name)">
            <input type="hidden" name="product_id" value="<?php print esc_attr($this->product->product_id)?>" />
            <input type="hidden" name="back_link" value="<?php print esc_url($_SERVER['REQUEST_URI'])?>" />
            
            <div id="wshop_review_write" >
                <div class = "row-fluid">
                    <div class = "span3">
                        <?php print esc_html(WOPSHOP_REVIEW_USER_NAME)?>
                    </div>
                    <div class = "span9">
                        <input type="text" name="user_name" id="review_user_name" class="inputbox" value="<?php print esc_attr($this->user->u_name)?>"/>
                    </div>
                </div>
                <div class = "row-fluid">
                    <div class = "span3">
                        <?php print esc_html(WOPSHOP_REVIEW_USER_EMAIL)?>
                    </div>
                    <div class = "span9">
                        <input type="text" name="user_email" id="review_user_email" class="inputbox" value="<?php print esc_attr($this->user->email)?>" />
                    </div>
                </div>
                <div class = "row-fluid">
                    <div class = "span3">
                        <?php print esc_html(WOPSHOP_REVIEW_REVIEW)?>
                    </div>
                    <div class = "span9">
                        <textarea name="review" id="review_review" rows="4" cols="40" class="wshop inputbox"></textarea>
                    </div>
                </div>
                <div class = "row-fluid">
                    <div class = "span3">
                        <?php print esc_html(WOPSHOP_REVIEW_MARK_PRODUCT)?>
                    </div>
                    <div class = "span9">
                        <?php for($i=1; $i<=$this->stars_count*$this->parts_count; $i++){?>
                            <input name="mark" type="radio" class="star {split:<?php print esc_attr($this->parts_count)?>}" value="<?php print esc_attr($i)?>" <?php if ($i==$this->stars_count*$this->parts_count){?>checked="checked"<?php }?>/>
                        <?php } ?>
                    </div>
                </div>
                <?php print $this->_tmp_product_review_before_submit; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                <div class = "row-fluid">
                    <div class = "span3"></div>
                    <div class = "span9">
                        <input type="submit" class="btn btn-primary button validate" value="<?php print esc_html(WOPSHOP_REVIEW_SUBMIT)?>" />
                    </div>
                </div>
            </div>
        </form>
    <?php }else{?>
        <div class="review_text_not_login"><?php print esc_html($this->text_review)?></div>
    <?php } ?>
<?php }?>