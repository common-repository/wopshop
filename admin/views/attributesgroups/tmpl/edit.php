<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<form method="POST" action="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributesgroups&task=save' ) );?>" enctype="multipart/form-data">
<div class="wrap">
	<h2><?php echo esc_html( $this->row->id ? WOPSHOP_EDIT . ' / ' . $this->row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW ); ?></h2>
    <?php print $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <table width="100%" class="admintable">
    <?php 
    foreach($this->languages as $lang){
        $field = "name_".$lang->language;
    ?>
       <tr>
         <td class="key" style="width:250px;">
            <?php echo esc_html( WOPSHOP_TITLE ); ?> <?php if ($this->multilang) print "(".esc_html( $lang->lang ).")";?>*
         </td>
         <td>
            <input type="text" class="inputbox" id="<?php print esc_attr( $field )?>" name="<?php print esc_attr( $field )?>" value="<?php echo esc_html( $this->row->$field );?>" />
         </td>
       </tr>
    <?php }?>
    </table>
    <?php print $this->tmp_html_end; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<div class="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_html( WOPSHOP_ACTION_SAVE ); ?>" name="submit">
        <a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wopshop-options&tab=attributesgroups' ) );?>"><?php echo esc_html( WOPSHOP_BACK ); ?></a>
    </p> 
</div>
    <input type="hidden" value="<?php echo esc_attr( $this->row->id ); ?>" name="id">
    <?php wp_nonce_field('attributesgroups_edit'); ?>
</form>
