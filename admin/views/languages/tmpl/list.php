<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('languages');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_LIST_LANGUAGE); ?>
    </h2>
    <form id="listing" class="adminForm" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
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
                    <th id="order_title" class="manage-column column-order_title" scope="col" width="50%">
                        <?php echo esc_html(WOPSHOP_LANGUAGE_NAME); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_DEFAULT_FRONT_LANG); ?>
                    </th>
                    <th class="manage-column" scope="col">
                        <?php echo esc_html(WOPSHOP_DEFAULT_LANG_FOR_COPY); ?>
                    </th>

                    <th class="manage-column column-order_status" scope="col">
                        <?php echo esc_html(WOPSHOP_STATUS); ?>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($this->rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($this->rows as $index=>$language){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <th class="check-column" scope="col">
                            <input id="user_<?php echo esc_attr($language->id); ?>" type="checkbox" value="<?php echo esc_attr($language->id); ?>" name="rows[]">
                        </th>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($language->name); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit"></span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php 
                            if($language->favorite) echo '<center><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-default.png').'" ></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            else echo '<center><a href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=languages&task=favorite_save&lang_id='.$language->id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-notdefault.png').'" ></a></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </td>
                        <td class="code2-column " scope="col">
                            <?php
                            if($language->favorite_copy) echo '<center><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-default.png').'" ></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            else echo '<center><a href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=languages&task=favorite_copy_save&lang_id='.$language->id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-notdefault.png').'" ></a></center>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </td>
                        <td class="status-column">
                            <?php echo $language->publish ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=languages&task=unpublish&rows[]='.$language->id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=languages&task=publish&rows[]='.$language->id)).'"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="options" name="page">
        <input type="hidden" value="languages" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


