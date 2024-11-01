<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$fields=$this->fields;
$current_fields=$this->current_fields;
wopshopDisplaySubmenuConfigs('fieldregister');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<?/*<input type="hidden" name="layout" value="fieldregister">*/ ?>
<input type="hidden" name="tabs" value="9">
<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1"><?php echo esc_html(WOPSHOP_REGISTER)?></a></li>
        <li><a href="#tab2"><?php echo esc_html(WOPSHOP_CHECKOUT_ADDRESS)?></a></li>
        <li><a href="#tab3"><?php echo esc_html(WOPSHOP_EDIT_ACCOUNT)?></a></li>
    </ul>
 
    <div class="tab-content">
        <div id="tab1" class="tab active">
            <div class="col100">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_REGISTER)?></legend>
            <table class="admintable">
            <tr>
                <td class="key" style="width:220px">
                    &nbsp;
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_DISPLAY);?>
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_REQUIRE);?>
                </td>
            </tr>
            <?php foreach($fields['register'] as $field){?>
            <tr>
                <td class="key" style="width:220px">
                    <?php 
                    $constant="WOPSHOP_FIELD_".strtoupper($field);
                    if (defined($constant)) echo esc_html(constant($constant)); else print esc_html($constant);
                    ?>
                </td>
                <td align="center"><input type="checkbox" name="field[register][<?php print esc_attr($field)?>][display]" class="inputbox" value="1" <?php if (isset($current_fields['register'][$field]['display']) && $current_fields['register'][$field]['display']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['register'])){?>disabled="disabled"<?php }?> /></td>
                <td align="center"><input type="checkbox" name="field[register][<?php print esc_attr($field)?>][require]" class="inputbox" value="1" <?php if (isset($current_fields['register'][$field]['require']) && $current_fields['register'][$field]['require']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['register'])){?>disabled="disabled"<?php }?> /></td>
            </tr>
            <?php } ?>

            </table>
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            </p>                
            </fieldset>
            </div>
        </div>
 
        <div id="tab2" class="tab">
            <div class="col100">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_CHECKOUT_ADDRESS)?></legend>
            <table class="admintable">
            <tr>
                <td class="key" style="width:220px">
                    &nbsp;
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_DISPLAY);?>
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_REQUIRE);?>
                </td>
            </tr>
            <?php 
            $display_delivery=0;
            foreach($fields['address'] as $field){?>
            <?php if (!$display_delivery && substr($field,0,2)=="d_"){?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_FIELD_DELIVERY_ADRESS);?></td>
            </tr>    
            <?php $display_delivery=1; } ?>
            <tr>
                <td class="key" style="width:220px">
                    <?php
                    $field_c=$field; 
                    if (substr($field_c,0,2)=="d_") $field_c=substr($field_c,2,strlen($field_c)-2);
                    $constant="WOPSHOP_FIELD_".strtoupper($field_c);
                    if (defined($constant)) echo esc_html(constant($constant)); else print esc_html($constant);
                    ?>
                </td>
                <td align="center"><input type="checkbox" name="field[address][<?php print esc_attr($field)?>][display]" class="inputbox" value="1" <?php if (isset($current_fields['address'][$field]['display']) && $current_fields['address'][$field]['display']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['address'])){?>disabled="disabled"<?php }?> /></td>
                <td align="center"><input type="checkbox" name="field[address][<?php print esc_attr($field)?>][require]" class="inputbox" value="1" <?php if (isset($current_fields['address'][$field]['require']) && $current_fields['address'][$field]['require']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['address'])){?>disabled="disabled"<?php }?> /></td>
            </tr>
            <?php } ?>

            </table>
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            </p>                
            </fieldset>
            </div>
        </div>
 
        <div id="tab3" class="tab">
            <div class="col100">
            <fieldset class="adminform">
                <legend><?php echo esc_html(WOPSHOP_EDIT_ACCOUNT)?></legend>
            <table class="admintable">
            <tr>
                <td class="key" style="width:220px">
                    &nbsp;
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_DISPLAY);?>
                </td>
                <td>
                    <?php echo esc_html(WOPSHOP_REQUIRE);?>
                </td>
            </tr>
            <?php 
            $display_delivery=0;
            foreach($fields['editaccount'] as $field){?>
            <?php if (!$display_delivery && substr($field,0,2)=="d_"){?>
            <tr>
                <td class="key"><?php print esc_html(WOPSHOP_FIELD_DELIVERY_ADRESS);?></td>
            </tr>    
            <?php $display_delivery=1; } ?>
            <tr>
                <td class="key" style="width:220px">
                    <?php
                    $field_c=$field; 
                    if (substr($field_c,0,2)=="d_") $field_c=substr($field_c,2,strlen($field_c)-2);
                    $constant="WOPSHOP_FIELD_".strtoupper($field_c);
                    if (defined($constant)) echo esc_html(constant($constant)); else print esc_html($constant);
                    ?>
                </td>
                <td align="center"><input type="checkbox" name="field[editaccount][<?php print esc_attr($field)?>][display]" class="inputbox" value="1" <?php if (isset($current_fields['editaccount'][$field]['display']) && $current_fields['editaccount'][$field]['display']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['editaccount'])){?>disabled="disabled"<?php }?> /></td>
                <td align="center"><input type="checkbox" name="field[editaccount][<?php print esc_attr($field)?>][require]" class="inputbox" value="1" <?php if (isset($current_fields['editaccount'][$field]['require']) && $current_fields['editaccount'][$field]['require']) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php if (in_array($field, $this->fields_sys['editaccount'])){?>disabled="disabled"<?php }?> /></td>
            </tr>
            <?php } ?>
            <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

            </table>
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
            </p> 
            </fieldset>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="1" name="field[register][email][display]">
<input type="hidden" value="1" name="field[register][email][require]">
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</form>