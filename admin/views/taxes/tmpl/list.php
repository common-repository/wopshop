<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('taxes');
$taxes = $this->rows;
$i = 0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_TAXES); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&task=edit'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_TAX); ?></a>
    </h2>
    <form id="listing" method="GET">
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <br class="clear">
    </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                <input id="cb-select-all-1" type="checkbox">
            </th>
            <?php if($this->filter_order == 'tax_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
            <th id="order_tax_name" class="manage-column column-order_tax_name <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&filter_order=tax_name&filter_order_Dir='.$this->filter_order_Dir))?>">
                    <span class="status_head tips"><?php echo esc_html(WOPSHOP_TITLE); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th width="150">
                <?php echo esc_html(WOPSHOP_EXTENDED_RULE_TAX); ?>
            </th>
            <?php if($this->filter_order == 'tax_id') $class_name = 'sorted'; else $class_name = 'sortable';?>
            <th id="order_tax_id" class="manage-column column-order_tax_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="50px">
                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&filter_order=tax_id&filter_order_Dir='.$this->filter_order_Dir))?>">
                    <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
    </thead>  
    <?php if(count($taxes) == 0){ ?>
        <tr class="no-items">
            <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
        </tr>
    <?php 
          }else{
        foreach($taxes as $tax){
      ?>
        <tr class = "row<?php echo esc_attr($i % 2);?>">
            <th class="check-column" scope="col">
                <input id="user_<?php echo esc_attr($tax->tax_id); ?>" type="checkbox" value="<?php echo esc_attr($tax->tax_id); ?>" name="rows[]">
            </th>
            <td>
                <strong>
                    <?php echo esc_html($tax->tax_name); ?> (<?php echo esc_html($tax->tax_value);?> %)
                </strong>
                <div class="row-actions">
                    <span class="edit">
                        <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&task=edit&tax_id='.$tax->tax_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                        |
                    </span>
                    <span class="trash">
                        <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes&task=delete&rows[]='.$tax->tax_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                    </span>
                </div>
            </td>
            <td>
                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$tax->tax_id))?>">
                    <?php echo esc_html(WOPSHOP_EXTENDED_RULE_TAX); ?>
                </a>
           </td>
            <td align="center">
                <?php print esc_html($tax->tax_id);?>
            </td>
        </tr>
      <?php
        }
          }   
    ?>
    </table>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="taxes" name="tab">
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
</div>
