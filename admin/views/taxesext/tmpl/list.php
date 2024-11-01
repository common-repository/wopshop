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
$i=0;

wopshopDisplaySubmenuOptions("taxes");?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_TAXES); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&task=edit&row=0&back_tax_id=1'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_TAX); ?></a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=taxes'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_LIST_TAXES); ?></a>
    </h2>
    <div id="wshop-main-container">
        <form method="post" id="listing">
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
                    <th align = "left">
                        <?php echo esc_html(WOPSHOP_TITLE); ?>
                    </th>
                    <th>
                        <?php echo esc_html(WOPSHOP_COUNTRY); ?>
                    </th>
                    <?php if($this->filter_order == 'ET.tax') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_exttaxes_tax" class="manage-column column-order_exttaxes_tax <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id.'&filter_order=ET.tax&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_TAX); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->filter_order == 'ET.firma_tax') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th  id="order_exttaxes_firm" class="manage-column column-order_exttaxes_firm <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <?php
                            if ($this->config->ext_tax_rule_for==1){ ?>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id.'&filter_order=ET.firma_tax&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                                    <span class="status_head tips"><?php echo esc_html(WOPSHOP_USER_WITH_TAX_ID_TAX); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                        <?php }
                            else {
                        ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id.'&filter_order=ET.firma_tax&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                                <span class="status_head tips"><?php echo esc_html(WOPSHOP_FIRMA_TAX); ?></span>
                                <span class="sorting-indicator"></span>
                            </a>
                        <?php } ?>
                    </th>
                    <?php if($this->filter_order == 'id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th width = "50" id="order_exttaxes_id" class="manage-column column-order_exttaxes_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id.'&filter_order=id&filter_order_Dir='.$this->filter_order_Dir.'&paged=1'))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
            <?php foreach($this->rows as $row) { ?>
                <tr class="row<?php echo esc_attr($i % 2);?>">
                    <th class="check-column" scope="col">
                        <input id="exttax_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
                    </th>
                    <td>
                      <?php echo esc_html($row->tax_name);?>
                        <div class="row-actions">
                            <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&task=edit&back_tax_id='.$this->back_tax_id.'&row='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                            </span>
                            <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=exttaxes&back_tax_id='.$this->back_tax_id.'&task=delete&rows[]='.$row->id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                            </span>
                        </div>                    
                    </td>
                    <td>
                     <?php echo $row->countries; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </td>
                    <td>
                     <?php echo esc_html($row->tax);?> %
                    </td>
                    <td>
                     <?php echo esc_html($row->firma_tax);?> %
                    </td>
                    <td class="center">
                         <?php print esc_html($row->id);?>
                    </td>
                </tr>
            <?php
            $i++;
            }
            ?>
            </tbody>
        </table>
        <input type="hidden" name="filter_order" value="<?php echo esc_attr($this->filter_order)?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo esc_attr($this->filter_order_Dir)?>" />
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="exttaxes" name="tab">
        <input type="hidden" value="<?php print esc_attr($this->back_tax_id);?>" name="back_tax_id">

        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
    </div>
</div>