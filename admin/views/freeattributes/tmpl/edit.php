<form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes&task=save'))?>" enctype="multipart/form-data">
<div class="wrap">
	<h3><?php echo esc_html($this->attribut->id ? WOPSHOP_EDIT_ATTRIBUT . ' / ' . $this->attribut->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW_ATTRIBUT); ?></h3>
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <table class="admintable" width = "100%" >
<?php 
    foreach($this->languages as $lang){
    $name="name_".$lang->language;
    ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_TITLE); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>* 
       </td>
       <td>
         <input type="text" class="inputbox" name="<?php print esc_attr($name)?>" value="<?php echo esc_attr($this->attribut->$name)?>" />
       </td>
     </tr>
    <?php } ?>
    <?php 
    foreach($this->languages as $lang){
    $description="description_".$lang->language;
    ?>
     <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
       </td>
       <td>
         <input type="text" class="inputbox" name="<?php print esc_attr($description)?>" value="<?php echo esc_attr($this->attribut->$description)?>" />
       </td>
     </tr>
    <?php } ?>
    <tr>
       <td class="key">
         <?php echo esc_html(WOPSHOP_REQUIRED);?>
       </td>
       <td>
         <input type="checkbox" name="required" value="1" <?php if ($this->attribut->required) print "checked"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
       </td>
    </tr>
    <?php if (isset($this->type)){print $this->type;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </table>
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
		<a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=freeattributes'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <input type="hidden" value="<?php echo esc_attr($this->attribut->id); ?>" name="id">
    <?php wp_nonce_field('freeattributes_edit','name_of_nonce_field'); ?>
</form>
