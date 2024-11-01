<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('shippings');
$rows = $this->rows;
$i = 0;
?>
<style>
	.adminForm .wp-list-table{
		vertical-align: middle;
	}
</style>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_SHIPPING_EXT_PRICE_CALC); ?>
    </h2>
	<form id="listing" class="adminForm" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice'))?>" method = "post" name = "adminForm">
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
					<th align="left" width="300">
					  <?php echo esc_html(WOPSHOP_TITLE);?>
					</th>
					<th>
						<?php echo esc_html(WOPSHOP_DESCRIPTION);?>
					</th>
					<th>
					  <?php echo esc_html(WOPSHOP_ORDERING);?>
					</th>
					<th width="100">
					  <?php echo esc_html(WOPSHOP_PUBLISH);?>
					</th>
					<th width="140">
						<?php echo esc_html(WOPSHOP_CONFIG);?>
					</th>
					<th width="80">
						<?php echo esc_html(WOPSHOP_DELETE);?>
					</th>
					<th width="40">
						<?php echo esc_html(WOPSHOP_ID);?>
					</th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php 
                $count = count($rows);
                foreach($rows as $i=>$row){
                ?>
                    <tr class="<?php if($i%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <?php echo esc_html($row->name);?>
                            </strong>
                        </td>
                        <td class="column-primary description-column" scope="col">
                            <strong>
                            <?php echo wp_kses_post($row->description);?>
                            </strong>
                        </td>
                        <td class="column-primary ordering-column" scope="col">
						<?php
							 if ($i != 0)
								 echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=orderup&id=' . $row->id . '&number=' . $row->ordering)) . '"><img alt="' . WOPSHOP_UP . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/uparrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<?php
							 if ($i!=$count-1 ) echo '<a class="btn btn-micro" href="'.esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=orderdown&id=' . $row->id . '&number=' . $row->ordering)) . '"><img alt="' . WOPSHOP_DOWN . '" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/downarrow.png').'"/></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>							
                        </td>
                        <td class="center column-primary publish-column" scope="col">
							<?php echo $published=($row->published) ? ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=unpublish&id='.$row->id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"/></a>') : ('<a href = "'.esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=publish&id='.$row->id)).'"><img src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"/></a>');  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <td class="column-primary config-column" scope="col">
                            <a class="btn btn-micro" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=edit&id='.$row->id))?>">
                                <img src=<?php echo esc_url(WOPSHOP_PLUGIN_URL)?>assets/images/icon-16-edit.png'>
							</a>
                        </td>
                        <td class="column-primary delete-column" scope="col">
                            <a class="btn btn-micro" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=delete&id='.$row->id))?>">
								<img src=<?php echo esc_url(WOPSHOP_PLUGIN_URL)?>assets/images/publish_r.png'>
							</a>
                        </td>
                        <td class="column-primary id-column" scope="col">
                            <strong>
                            <?php echo esc_html($row->id);?>
                            </strong>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>