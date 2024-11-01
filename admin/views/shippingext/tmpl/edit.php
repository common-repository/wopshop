<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row=$this->row;

?>
<form action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice&task=save'))?>" method = "post" name = "adminForm">
<div class="wrap">
    <div class="form-wrap">
		
		<h3><?php echo esc_html(WOPSHOP_SHIPPING_EXT_PRICE_CALC); ?></h3>		
		<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>		
        <fieldset class="adminform">
            <table class="admintable" width = "100%" >
   	<tr>
     	<td class="key" width="30%">
       		<?php echo esc_html(WOPSHOP_PUBLISH);?>
     	</td>
     	<td>
            <input type="hidden" name="published" value="0" />
       		<input type="checkbox" name="published" value="1" <?php if ($row->published) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
     	</td>
   	</tr>    
   	<tr>
     	<td class="key">
       		<?php echo esc_html(WOPSHOP_TITLE);?>*
     	</td>
     	<td>
       		<input type="text" class="inputbox" name="name" value="<?php echo esc_attr($row->name)?>" />
     	</td>
   	</tr>
    <tr>
         <td class="key">
               <?php echo esc_html(WOPSHOP_DESCRIPTION);?>
         </td>
         <td>
            <textarea name="description" cols="40" rows="5"><?php echo wp_kses_post($row->description)?></textarea>               
         </td>
       </tr>
    <tr>
         <td class="key">
            <?php echo esc_html(WOPSHOP_SHIPPINGS);?>
         </td>
         <td>
            <?php foreach($this->list_shippings as $shipping){?>
                <div style="padding:5px 0px;">
                    <input type="hidden" name="shipping[<?php print esc_attr($shipping->shipping_id)?>]" value="0">
                    <input type="checkbox" name="shipping[<?php print esc_attr($shipping->shipping_id)?>]" value="1" <?php if (isset($this->shippings_conects[$shipping->shipping_id]) && $this->shippings_conects[$shipping->shipping_id]!=="0") print "checked" // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>                    
                    <?php print esc_html($shipping->name);?>
                </div>
            <?php }?>
         </td>
    </tr>
    <?php        
        $row->exec->showConfigForm($row->getParams(), $row, $this);
    ?>
   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </table>
            <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </fieldset>
    </div>
    <div class="clr"></div>
</div>
<input type = "hidden" name = "id" value = "<?php echo esc_attr($row->id)?>" />

<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingextprice'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>

</form>
