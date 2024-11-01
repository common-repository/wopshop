<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
wopshopDisplaySubmenuConfigs('statictext');
$rows=$this->rows;
$i=0;
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<table class="table table-striped">
<thead>
  <tr>
    <th class="title" width ="10">
      #
    </th>
    <th width="20">
      <input type="checkbox" name="checkall-toggle" value="" title="<?php echo esc_html(JText::_('JGLOBAL_CHECK_ALL')); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th align="left">
      <?php echo esc_html(WOPSHOP_PAGE); ?>
    </th>
    <th width = "50">
        <?php echo esc_html(WOPSHOP_USE_FOR_RETURN_POLICY);?>
    </th>
    <th width="50">
        <?php echo esc_html(WOPSHOP_EDIT);?>
    </th>
    <th width = "50">
        <?php echo esc_html(WOPSHOP_DELETE);?>
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
     <?php echo JWopshopHtml::_('grid.id', $i, $row->id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   </td>
   <td>
    <a href='<?php echo esc_url('admin.php?wopshop-configuration&task=statictextedit&id='.$row->id)?>'>
    <?php if (defined("_JSHP_STPAGE_".$row->alias)) print esc_html(constant("_JSHP_STPAGE_".$row->alias)); else print esc_html($row->alias);?>
    </a>
   </td>
   <td align="center">
     <?php
       echo $use_for_return_policy=($row->use_for_return_policy) ? ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb'.$i. '\', \'unpublish\')"><img title="' . WOPSHOP_YES . '" alt="" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'"></a>') : ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb' . $i . '\', \'publish\')"><img title="'.WOPSHOP_NO.'" alt="" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'"></a>'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
     ?>       
   </td>
   <td align="center">
        <a href='<?php echo esc_url('admin.php?wopshop-configuration&task=statictextedit&id='.$row->id)?>'><img src='<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icon-16-edit.png')?>'></a>
   </td>
   <td align="center">
   <?php if (!in_array($row->alias, $config->sys_static_text)){?>
    <a href='<?php echo esc_url('admin.php?page=wopshop-configuration&task=deletestatictext&id='.$row->id)?>' onclick="return confirm('<?php print esc_html(WOPSHOP_DELETE)?>')"><img src='<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>'></a>
    <?php }?>
   </td>
   <td align="center">
    <?php print esc_html($row->id);?>
   </td>
   </tr>
<?php
$i++;
}
?>
<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</table>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</form>