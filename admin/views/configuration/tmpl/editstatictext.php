<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
wopshopDisplaySubmenuConfigs('statictext');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<input type="hidden" name="layout" value="statictext">
<input type="hidden" name="id" value="<?php print esc_attr($this->row->id)?>">

<div class="col100">
<fieldset class="adminform">
    <legend><?php if (defined("_JSHP_STPAGE_".$this->row->alias)) print esc_html(constant("_JSHP_STPAGE_".$this->row->alias)); else print esc_html($this->row->alias);?></legend>
<table class="admintable" width="100%">
<?php if (!$this->row->id){?>
<tr>
   <td class="key" style="width:220px;">
     <?php echo esc_html(WOPSHOP_ALIAS); ?>
   </td>
   <td>
     <input type="text" class="inputbox" name="alias" size="40" value="<?php echo esc_attr($this->row->alias)?>" />
   </td>
</tr>
<?php }
foreach($this->languages as $lang){
$field="text_".$lang->language;
?>
<tr>
   <td class="key" >
     <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
     <div style="font-size:10px;"><?php if (defined("_JSHP_STPAGE_INFO_".$this->row->alias)) print esc_html(constant("_JSHP_STPAGE_INFO_".$this->row->alias));?></div>
   </td>
   <td>
     <?php print $editor->display( 'text'.$lang->id,  $this->row->$field , '100%', '350', '75', '20' );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   </td>
</tr>
<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php } ?>
<tr>
   <td class="key">
     <?php echo esc_html(WOPSHOP_USE_FOR_RETURN_POLICY); ?>
   </td>
   <td>
     <input type = "checkbox"  name = "use_for_return_policy" size="40" value = "1"  <?php if($this->row->use_for_return_policy) echo 'checked = "checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
   </td>
</tr>    
</table>
</fieldset>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</form>