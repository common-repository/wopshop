<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row = $this->row;
?>
<div class="form-wrap">
	<h3><?php echo  esc_html($row->field_id ? WOPSHOP_EDIT . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW); ?></h3>
    <form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldvalues&task=save'))?>" method="post" name="adminForm" id="adminForm">
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
    </table> 
    
    <div clas="submit">
        <p class="submit">
            <input class="button button-primary" type="submit" value="<?php echo esc_html(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            <a style="margin-left:50px;" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=productfieldvalues&field_id='.$this->field_id))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
        </p> 
    </div>
    <input type = "hidden" name = "field_id" value = "<?php print esc_attr($this->field_id)?>" />
        <input type = "hidden" name = "id" value = "<?php echo esc_attr($row->id)?>" />
    <?php wp_nonce_field('productfieldvalues_edit','name_of_nonce_field'); ?>
    </form>        
</div>