<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="product_related" class="tab-pane">
    <div class="col100">
        <fieldset class="adminform">
            <legend><?php echo esc_html(WOPSHOP_PRODUCT_RELATED )?></legend>
            <div id="list_related">
                <?php
                foreach($this->related_products as $row_related){
                    $prefix_image = 'thumb'; 
                    if (!$row_related->image) {
                        $row_related->image = $config->noimage;
                        $prefix_image = '';
                    } 
                ?>
                    <div class="block_related" id="related_product_<?php print esc_attr($row_related->product_id);?>">
                        <div class="block_related_inner">
                            <div class="name"><?php echo esc_attr($row_related->name);?> (ID:&nbsp;<?php print esc_attr($row_related->product_id)?>)</div>
                            <div class="image">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=edit&product_id='.$row_related->product_id))?>">
                                    <img src="<?php print esc_url(wopshopGetPatchProductImage($row_related->image, $prefix_image, 1))?>" width="90" border="0" />
                                </a>
                            </div>
                            <div style="padding-top:5px;"><input type="button" value="<?php print esc_attr(WOPSHOP_DELETE);?>" onclick="delete_related(<?php print esc_attr($row_related->product_id);?>)"></div>
                            <input type="hidden" name="related_products[]" value="<?php print esc_attr($row_related->product_id);?>"/>
                            </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </fieldset>
    </div>
    <div class="clr"></div>
   <br/>
   <div class="col100">
    <fieldset class="adminform">
        <legend><?php echo esc_html(WOPSHOP_SEARCH )?></legend>
       <div>
            <input type="text" size="35" id="related_search" value="" />
            &nbsp;
            <input type="button" class="button" value="<?php echo esc_attr(WOPSHOP_SEARCH);?>" onclick="releted_product_search(0, '<?php echo esc_attr($row->product_id)?>');" />
        </div>
        <br/>
        <div id="list_for_select_related"></div>
    </fieldset>
    </div>
    <div class="clr"></div>
</div>