<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuConfigs('seo');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=saveseo'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<input type="hidden" name="id" value="<?php print esc_attr($this->row->id)?>">

<div class="col100">
<fieldset class="adminform">
    <h2><legend><?php if (defined("WOPSHOP_SEOPAGE_".$this->row->alias)) print esc_html(constant("WOPSHOP_SEOPAGE_".$this->row->alias)); else print esc_html($this->row->alias);?></legend></h2>
<table class="admintable">
<?php if (!$this->row->id){?>
<tr>
   <td class="key" style="width:220px;">
     <?php echo esc_html(WOPSHOP_ALIAS); ?>
   </td>
   <td>
     <input type="text" class="inputbox" name="alias" size="40" value="<?php echo esc_attr($this->row->alias)?>" />
   </td>
</tr>
    <?php if ($this->multilang){?>
    <tr><td>&nbsp;</td></tr>
    <?php
    }
}
foreach($this->languages as $lang){
$field="title_".$lang->language;
?>
<tr>
   <td class="key" >
     <?php echo esc_html(WOPSHOP_META_TITLE); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?> 
   </td>
   <td>
     <input type="text" class="inputbox" name="<?php print esc_attr($field)?>" size="80" value="<?php echo esc_attr($this->row->$field)?>" />
   </td>
</tr>
<?php }
if ($this->multilang){?>
<tr><td>&nbsp;</td></tr>
<?php     
}
foreach($this->languages as $lang){
$field="keyword_".$lang->language;
?>
 <tr>
   <td class="key">
     <?php echo esc_html(WOPSHOP_META_KEYWORDS); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?> 
   </td>
   <td>
    <textarea name="<?php print esc_attr($field)?>" cols="60" rows="3"><?php echo esc_html($this->row->$field)?></textarea>
   </td>
 </tr>
<?php }
if ($this->multilang){?>
<tr><td>&nbsp;</td></tr>
<?php
}
foreach($this->languages as $lang){
$field="description_".$lang->language;
?>
 <tr>
   <td class="key">
     <?php echo esc_html(WOPSHOP_META_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?> 
   </td>
   <td>
     <textarea name="<?php print esc_attr($field)?>" cols="60" rows="3"><?php echo esc_html($this->row->$field)?></textarea>
   </td>
 </tr>
<?php } ?>
<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    
</table>
</fieldset>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<p class="submit">
<input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
</p>
</form>