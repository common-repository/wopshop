<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('usergroups');
$rows = $this->rows;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_PANEL_USERGROUPS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW_USERGROUP); ?></a>
    </h2>
    <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups'))?>">
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
                    <?php if($this->filter_order == 'usergroup_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" width="30%">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&filter_order=usergroup_name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_NAME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col" width="30%">
                        <?php echo esc_html(WOPSHOP_DESCRIPTION); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_DISCOUNT); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_USERGROUP_IS_DEFAULT_DESCRIPTION); ?>
                    </th>
                    <?php if($this->filter_order == 'usergroup_id') $class_publish = 'sorted'; else $class_publish = 'sortable';?>
                    <th class="manage-column column-order_status <?php echo esc_attr($class_publish); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&filter_order=usergroup_id&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($rows as $index=>$data){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($data->usergroup_id); ?>" type="checkbox" value="<?php echo esc_attr($data->usergroup_id); ?>" name="rows[]">
                        </th>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($data->usergroup_name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&task=edit&row='.$data->usergroup_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&task=delete&rows[]='.$data->usergroup_id.'&action=-1'))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo wp_kses_post($data->usergroup_description); ?>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_attr($data->usergroup_discount); ?> %
                        </td>
                        <td class="code-column " scope="col">
                            <?php
                            if($data->usergroup_is_default) echo '<center><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-default.png').'" ></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            else echo '<center><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-notdefault.png').'" ></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </td>                        
                        <td class="status-column">
                            <?php echo esc_html($data->usergroup_id); ?>
                        </td>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="usergroups" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


