<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row = $this->row;
?>
<div class="form-wrap">
	<h3><?php echo  esc_html($row->id ? WOPSHOP_EDIT . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW); ?></h3>
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields&task=save'))?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>    
    <table width = "100%" class="admintable">
    <?php 
        foreach($this->languages as $lang){
        $name = "name_".$lang->language;
    ?>
        <tr>
            <td class="key" style="width:250px;">
                <?php echo esc_html(WOPSHOP_TITLE); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>*
            </td>
            <td>
                <input type = "text" class = "inputbox" id = "<?php print esc_attr($name)?>" name = "<?php print esc_attr($name)?>" value = "<?php echo esc_attr($row->$name);?>" />
            </td>
        </tr>
    <?php }?>
    <?php 
        foreach($this->languages as $lang){
        $description = "description_".$lang->language;
        ?>
        <tr>
            <td class="key" style="width:250px;">
                <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
            </td>
            <td>
                <input type = "text" class = "inputbox" id = "<?php print esc_attr($description)?>" name = "<?php print esc_attr($description)?>" value = "<?php echo esc_attr($row->$description);?>" />
            </td>
        </tr>
        <?php }?>
        <tr>
         <td  class="key">
           <?php echo esc_html(WOPSHOP_SHOW_FOR_CATEGORY);?>*
         </td>
         <td>
           <?php echo $this->lists['allcats']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
         </td>
        </tr>
        <tr id="tr_categorys" <?php if ($row->allcats=="1" and $row->id > 0) print "style='display:none;'"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
         <td  class="key">
           <?php echo esc_html(WOPSHOP_CATEGORIES);?>*
         </td>
         <td>
           <?php echo $this->lists['categories']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
         </td>
        </tr>
        <tr>
         <td  class="key">
           <?php echo esc_html(WOPSHOP_TYPE);?>*
         </td>
         <td>
           <?php echo $this->lists['type']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
         </td>
        </tr>
          <tr>
         <td  class="key">
           <?php echo esc_html(WOPSHOP_GROUP);?>
         </td>
         <td>
           <?php echo $this->lists['group']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
         </td>
        </tr>
		<tr>
            <td><?php print $this->etemplatevar  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
        </tr>
    </table> 
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <div clas="submit">
        <p class="submit">
            <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            <a style="margin-left:50px;" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfields'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
        </p> 
    </div>
    <input type="hidden" value="<?php echo esc_attr($this->row->id); ?>" name="id">
    <?php wp_nonce_field('productfields_edit','name_of_nonce_field'); ?>
    </form>        
</div>