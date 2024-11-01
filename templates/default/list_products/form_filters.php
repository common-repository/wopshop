<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<form action="<?php print esc_url($this->action)?>" method="post" name="sort_count" id="sort_count">
<?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
<div class="block_sorting_count_to_page">
    <?php if ($this->config->show_sort_product){?>
        <span class="box_products_sorting">
            <?php print WOPSHOP_ORDER_BY.": ".$this->sorting; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
            <img src="<?php print esc_url($this->path_image_sorting_dir)?>" alt="orderby" onclick="submitListProductFilterSortDirection()" /></span>
    <?php }?>
    <?php if ($this->config->show_count_select_products){?>
        <span class="box_products_count_to_page">
            <?php print WOPSHOP_DISPLAY_NUMBER.": ".$this->product_count; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </span>
    <?php }?>
</div>
<?php }?>

<?php if ($this->config->show_product_list_filters && $this->filter_show){?>
    <?php if ($this->config->show_sort_product || $this->config->show_count_select_products){?>
    <div class="margin_filter"></div>
    <?php }?>
    
    <div class="wshop filters">    
        <?php if ($this->filter_show_category){?>
        <span class="box_category">
            <?php print WOPSHOP_CATEGORY.": ".$this->categorys_sel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </span>
        <?php }?>
        <?php if ($this->filter_show_manufacturer){?>
        <span class="box_manufacrurer">
            <?php print WOPSHOP_MANUFACTURER.": ".$this->manufacuturers_sel; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        </span>
        <?php }?>
        <?php print $this->_tmp_ext_filter_box; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        
        <?php if (wopshopGetDisplayPriceShop()){?>
        <span class="filter_price"><?php print esc_html(WOPSHOP_PRICE)?>:
            <span class="box_price_from"><?php print esc_html(WOPSHOP_FROM)?> <input type="text" class="inputbox" name="fprice_from" id="price_from" size="7" value="<?php if ($this->filters['price_from']>0) print esc_attr($this->filters['price_from'])?>" /></span>
            <span class="box_price_to"><?php print esc_html(WOPSHOP_TO)?> <input type="text" class="inputbox" name="fprice_to"  id="price_to" size="7" value="<?php if ($this->filters['price_to']>0) print esc_attr($this->filters['price_to'])?>" /></span>
            <?php print esc_html($this->config->currency_code)?>
        </span>
        <?php }?>
        
        <?php print $this->_tmp_ext_filter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <input type="button" class="button" value="<?php print esc_html(WOPSHOP_GO)?>" onclick="submitListProductFilters();" />
        <span class="clear_filter"><a href="#" onclick="clearProductListFilter();return false;"><?php print esc_html(WOPSHOP_CLEAR_FILTERS)?></a></span>
    </div>
<?php }?>
<input type="hidden" name="orderby" id="orderby" value="<?php print esc_attr($this->orderby);?>" />
<input type="hidden" name="limitstart" value="0" />
</form>