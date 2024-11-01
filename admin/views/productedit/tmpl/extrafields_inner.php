<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
    $groupname="";
?>
<table class="admintable" >
<?php foreach($this->fields as $field){ ?>
<?php if ($groupname!=$field->groupname){ $groupname=$field->groupname;?>
<tr>
    <td><b><?php print esc_html($groupname);?></b></td>
</tr>
<?php }?>
<tr>
   <td class="key">
     <div style="padding-left:10px;"><?php echo esc_html($field->name);?></div>
   </td>
   <td>
     <?php echo $field->values; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
   </td>
</tr>
<?php }?>
</table>
