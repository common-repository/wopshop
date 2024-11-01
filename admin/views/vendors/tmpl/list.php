<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('vendors');
$rows = $this->rows;
$limitstart = $this->limitstart;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_VENDOR_LIST); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors&task=edit'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_ADD); ?></a>
    </h2>
    <form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <br class="clear">
            <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                     <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
                            <input id="cb-select-all-1" type="checkbox">
                     </th>
                     </th>
                     <th width="150" align="left">
                       <?php echo esc_html(WOPSHOP_USER_FIRSTNAME)?>
                     </th>
                     <th width="150" align="left">
                       <?php echo esc_html(WOPSHOP_USER_LASTNAME)?>
                     </th>
                     <th align="left">
                       <?php echo esc_html(WOPSHOP_STORE_NAME)?>
                     </th>
                     <th width="150">
                       <?php echo esc_html(WOPSHOP_EMAIL)?>
                     </th>
                     <th width="60" class="center">
                        <?php echo esc_html(WOPSHOP_DEFAULT);?>    
                    </th>	 	      
                     <th width="40" class="center">
                        <?php echo esc_html(WOPSHOP_ID);?>
                    </th>
                </tr>
            </thead> 
            <tbody id="the-list">
                <?php foreach($rows as $row){?>
                    <tr>
                        <td class="check-column" scope="col" align="center">
                            <input id="vendor_<?php echo esc_attr($row->id); ?>" type="checkbox" value="<?php echo esc_attr($row->id); ?>" name="rows[]">
                        </td>
                         <td>
                            <?php echo esc_html($row->f_name)?>
                                <div class="row-actions">
                                    <span class="edit">
                                    <a class="" title="<?php echo esc_html(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors&task=edit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                    |
                                    </span>
                                    <span class="trash">
                                    <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=vendors&task=delete&rows[]='.$row->id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                    </span>
                                </div>		 
                         </td>
                         <td>
                            <?php echo esc_html($row->l_name);?>
                         </td>
                         <td>
                            <?php echo esc_html($row->shop_name);?>
                         </td>
                         <td>
                            <?php echo esc_html($row->email);?>
                         </td>
                         <td class="center">
                         <?php if ($row->main==1) {?>
                            <img src='<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-default.png')?>'>
                         <?php }?>
                         </td>
                         <td class="center">
                            <?php print esc_html($row->id)?>
                         </td>
                    </tr>

                <?php }?>
            </tbody>            
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="vendors" name="tab">
        <input type = "hidden" name = "task" value = "<?php echo esc_attr(WopshopRequest::getVar('task', 0))?>" />
    </form>
</div>


