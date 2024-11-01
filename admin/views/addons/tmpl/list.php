<?php 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wopshopDisplaySubmenuOptions('addons');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html( WOPSHOP_ADDONS ); ?>
    </h2>
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=addons'))?>" method = "post" name = "adminForm">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <br class="clear">
        </div>
        <?php echo $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>                    
                    <th class="manage-column column-cb check-column wopshop-admin-list-check" width="50">
                        <input id="cb-select-all-1" type="checkbox" />
                    </th>
                    <th align="left">
                        <?php echo esc_html( WOPSHOP_TITLE ); ?>
                    </th>
                    <th style="width: 100px; text-align: center;">
                        <?php echo esc_html( WOPSHOP_STATUS ); ?>
                    </th>
                    <th style="width: 120px; text-align: center;">
                        <?php echo esc_html( WOPSHOP_VERSION ); ?>
                    </th>
                    <th style="width: 60px; text-align: center;">
                        <?php echo esc_html( WOPSHOP_KEY ); ?>
                    </th>
                    <th style="width: 40px; text-align: center;">
                        <?php echo esc_html( WOPSHOP_ID ); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($this->rows) == 0) : ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="6"><?php echo esc_html( WOPSHOP_QUERY_RESULT_NULL ); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($this->rows as $index => $row) : ?>
                        <tr class="<?php echo esc_html(($index % 2) ? 'alt' : ''); ?>">
                            <td class="check-column wopshop-admin-list-check">
                                <input id="cid_<?php echo esc_attr( $row->id ); ?>" type="checkbox" value="<?php echo esc_attr( $row->id ); ?>" name="cid[]" />
                            </td>
                            <td>
                                <strong>
                                <?php echo esc_html( $row->name ); ?>
                                </strong>
                                <div class="row-actions">
                                    <span class="edit">
                                    <a class="" title="<?php echo esc_html( WOPSHOP_EDIT ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=edit&id=' . $row->id ) );?>"><?php echo esc_html( WOPSHOP_EDIT ); ?></a>
                                    |
                                    </span>
                                    <span class="trash">
                                    <a class="submitdelete" title="<?php echo esc_attr( WOPSHOP_DELETE ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=delete&action=-1&id=' .$row->id ) ); ?>" onclick="return confirm('<?php print esc_html( WOPSHOP_DELETE_ALL_DATA ) ?>');"><?php echo esc_html( WOPSHOP_DELETE ); ?></a>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($row->publish) : ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=unpublish&cid[]=' . $row->id ) ); ?>" title="<?php echo esc_html( WOPSHOP_ACTION_UNPUBLISH ); ?>">
                                        <span class="glyphicon wshop-icon glyphicon-ok-sign wshop-green-icon" aria-hidden="true"></span>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=publish&cid[]=' . $row->id ) ); ?>" title="<?php echo esc_html( WOPSHOP_ACTION_PUBLISH ); ?>">
                                        <span class="glyphicon wshop-icon glyphicon-remove-sign wshop-red-icon" aria-hidden="true"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo esc_html( $row->version );?>
                                <?php if ($row->version_file_exist) : ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=addons&task=version&id=' . $row->id ) ); ?>">
                                        <span class="glyphicon wshop-icon glyphicon-info-sign" aria-hidden="true"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
   
                            <td style="text-align: center;">
                                <?php if ($row->usekey) : ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=licensekeyaddon&alias=' . $row->alias . '&back=' . $this->back ) ); ?>">
                                        <span class="glyphicon wshop-icon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo esc_html( $row->id );?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>   
            </tbody>
        </table>
        <?php echo $this->tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
</div>
<br class="clear" />