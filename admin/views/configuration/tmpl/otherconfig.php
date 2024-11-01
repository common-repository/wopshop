<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$lists=$this->lists;
$config = $this->config;
wopshopDisplaySubmenuConfigs('otherconfig');
?>
<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&task=save'))?>" method="POST" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php wp_nonce_field('config','config_nonce_field'); ?>
<input type="hidden" name="tabs" value="10">
<div class="col100">
    <fieldset class="adminform">
        <legend><?php echo esc_html(WOPSHOP_OC);?></legend>
        <table class="admintable">
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_EXTENDED_TAX_RULE_FOR)?>
                </td>
                <td>
                    <?php print $lists['tax_rule_for']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_SAVE_ALIAS_AUTOMATICAL)?>
                </td>
                <td>
                    <input type="hidden" name="create_alias_product_category_auto" value="0">
                    <input type="checkbox" name="create_alias_product_category_auto" value="1" <?php if ($config->create_alias_product_category_auto) echo 'checked="checked"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                </td>
            </tr>
            <?php foreach($this->other_config as $k){?>
            <tr>
                <td class="key">
                    <?php if (defined("WOPSHOP_OC_".$k)) print esc_html(constant("WOPSHOP_OC_".$k)); else print esc_html($k);?>
                </td>
                <td>
                <?php if (in_array($k, $this->other_config_checkbox)){?>
                    <input type="hidden" name="<?php print esc_attr($k)?>" value="0">
                    <input type="checkbox" name="<?php print esc_attr($k)?>" value="1" <?php if ($config->$k==1) print 'checked' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                <?php }elseif (isset($this->other_config_select[$k])){?>
                    <?php 
                    $option = array();
                    foreach($this->other_config_select[$k] as $k2=>$v2){
                        $option_name = $v2;
                        if (defined("WOPSHOP_OC_".$k."_".$v2)){
                            $option_name = constant("WOPSHOP_OC_".$k."_".$v2);
                        }
                        $option[] = WopshopHtml::_('select.option', $k2, $option_name, 'id', 'name');
                    }
                    print WopshopHtml::_('select.genericlist', $option, $k, 'class = "inputbox"', 'id', 'name', $config->$k); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                <?php }else{?>
                            <input type="text" name="<?php print esc_attr($k)?>" value="<?php echo esc_attr($config->$k)?>">
                <?php }?>
                </td>
        </tr>
        <?php } ?>
        <?php /*foreach($this->other_config as $k){?>
            <tr>
                <td class="key">
                    <?php if (defined("WOPSHOP_OC_".$k)) print esc_html(constant("WOPSHOP_OC_".$k)); else print esc_html($k);?>
                </td>
                <td>
                    <?php if (in_array($k, $this->other_config_checkbox)){?>
                    <input type="hidden" name="<?php print esc_html($k)?>" value="0">
                    <input type="checkbox" name="<?php print esc_html($k)?>" value="1" <?php if ($config->$k==1) print 'checked' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php }else{?>
                            <input type="text" name="<?php print $k?>" value="<?php echo $config->$k?>">
                    <?php }?>
                            <?php if (defined("WOPSHOP_OC_".$k."_INFO")) echo WopshopHtml::tooltip(constant("WOPSHOP_OC_".$k."_INFO")); ?>
                </td>
            </tr>
        <?php } */?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<p class="submit">
<input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
</p>
</form>
