<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuConfigs('seo');
$rows=$this->rows;
$i=0;
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm" class="shopTable">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<table class="wp-list-table widefat fixed striped">
<thead>
  <tr>
    <th class="title" width ="30">
      #
    </th>
    <th align="left" width="35%">
      <?php echo esc_html(WOPSHOP_PAGE); ?>
    </th>
    <th align="left">
      <?php echo esc_html(WOPSHOP_TITLE); ?>
    </th>
    <th width="40">
        <?php echo esc_html(WOPSHOP_ID);?>
    </th>
  </tr>
</thead>  
<?php foreach($rows as $row){?>
  <tr class="row<?php echo esc_attr($i % 2);?>">
   <td>
     <?php echo esc_html($i+1);?>
   </td>
   <td>
        <strong>
        <?php if (defined("_JSHP_SEOPAGE_".$row->alias)) print esc_html(constant("_JSHP_SEOPAGE_".$row->alias)); else print esc_html($row->alias);?>
        </strong>
        <div class="row-actions">
            <span class="edit">
            <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=seoedit&id='.$row->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
            </span>
        </div>
   </td>
   <td>
        <?php print esc_html($row->title);?>
   </td>
   <td align="center">
        <?php print esc_html($row->id);?>
   </td>
   </tr>
<?php
$i++;
}
?>
</table>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</form>