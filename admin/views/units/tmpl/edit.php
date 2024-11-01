<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row=$this->units; 
$edit=$this->edit; 
?>
<div class="wrap">
    <div class="form-wrap">
        <h3><?php echo esc_html(($this->edit) ? (WOPSHOP_UNITS_MEASURE_EDIT.' / '.$this->units->{WopshopFactory::getLang()->get('name')}) : (WOPSHOP_UNITS_MEASURE_NEW)); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units&task=save'))?>" id="edittax">
			<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <table width = "100%" class="admintable">
			<?php
			   foreach($this->languages as $lang){
			   $field="name_".$lang->language;
			   ?>
			   <tr>
				 <td class="key">
				   <?php echo esc_html(WOPSHOP_TITLE);?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>*
				 </td>
				 <td>
				   <input type="text" class="inputbox" name="<?php print esc_attr($field);?>" value="<?php echo esc_attr($row->$field);?>" />
				 </td>
			   </tr>
			   <?php }?>				
				<tr>
				  <td class="key">
					<?php echo esc_html(WOPSHOP_BASIC_QTY);?>*
				  </td>
				  <td>
					<input type="text" class="inputbox" name="qty" value="<?php echo esc_attr($row->qty);?>" />
				  </td>
				</tr>
				<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             </table>
            <?php echo $this->tmp_html_end;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <input type="hidden" value="<?php echo esc_attr($row->id); ?>" name="id">
            <?php wp_nonce_field('unit_edit','name_of_nonce_field'); ?>
            
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=units'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
            </p> 
        </form>
    </div>
</div>