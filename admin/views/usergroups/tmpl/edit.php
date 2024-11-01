<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$usergroup=$this->usergroup;
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html(($this->edit) ? WOPSHOP_EDIT_USERGROUP . ' / ' . $usergroup->usergroup_name :  WOPSHOP_NEW_USERGROUP); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups&task=save'))?>" id="edit">
            <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <div class="col100">
            <fieldset class="adminform">
                <table class="admintable" width="100%">
                <?php
                foreach($this->languages as $lang){
                $name = "name_".$lang->language;
                ?>
                <tr>
                   <td class="key" width = "20%">
                     <?php echo esc_html(WOPSHOP_TITLE);?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>* 
                   </td>
                   <td>
                     <input type = "text" class = "inputbox" name = "<?php print esc_attr($name)?>" value = "<?php echo esc_attr($usergroup->$name)?>" />
                   </td>
                </tr>
                <?php } ?>	
                <tr>
                <tr>
                    <td class="key">
                        <?php echo esc_html(WOPSHOP_USERGROUP_IS_DEFAULT);?>                
                    </td>
                    <td>
                        <input type="checkbox" name="usergroup_is_default" <?php if ($usergroup->usergroup_is_default) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> value="1" />
                        <?php echo WopshopHtml::tooltip(WOPSHOP_USERGROUP_IS_DEFAULT_DESCRIPTION); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo esc_html(WOPSHOP_USERGROUP_DISCOUNT);?>*    
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="usergroup_discount" value="<?php echo esc_attr($usergroup->usergroup_discount);?>" /> %
                    </td>
                </tr>
                <?php
                foreach($this->languages as $lang){
                $name = "description_".$lang->language;
                ?>
                <tr>
                    <td class="key">
                        <?php echo esc_html(WOPSHOP_DESCRIPTION);?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
                    </td>
                    <td>
                        <?php 
                        $args = array('media_buttons' => 1,'textarea_name' => 'description'.$lang->id,'textarea_rows' => 20,'tabindex'=> null,'tinymce'=> 1);
                        wp_editor($usergroup->$name, "description".$lang->id, $args);
                        ?>
                    </td>
                </tr>
                <?php }?>
                <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </table>
            </fieldset>
            </div>
            <div class="clr"></div>

            <?php wp_nonce_field('usergroups_edit','name_of_nonce_field'); ?>
            <input type="hidden" name="usergroup_id" value="<?php echo esc_attr($usergroup->usergroup_id);?>" />
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>            
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=usergroups'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
            </p>             
        </form>
    </div>
</div>