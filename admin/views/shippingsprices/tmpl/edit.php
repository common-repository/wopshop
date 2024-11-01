<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row = $this->sh_method_price;
$lists = $this->lists;
?>
<form action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices&task=save'))?>" method = "post" name = "adminForm">
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($row->sh_pr_method_id ? WOPSHOP_EDIT_SHIPPING_PRICES :  WOPSHOP_NEW_SHIPPING_PRICES); ?></h3>
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <table class="admintable" width = "100%" >
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_TITLE);?>*
                </td>
                <td>
                    <?php echo $lists['shipping_methods'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_COUNTRY)."*"."<br/><br/><span style='font-weight:normal'>".esc_html(WOPSHOP_MULTISELECT_INFO)."</span>";  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
                <td>
                    <?php echo $lists['countries']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <?php if ($this->config->admin_show_delivery_time) { ?>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_DELIVERY_TIME);?>
                </td>
                <td>
                    <?php echo $lists['deliverytimes']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <?php }?>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_PRICE)?>*
                </td>
                <td>
                    <input type = "text" class = "inputbox" name = "shipping_stand_price" value = "<?php echo esc_attr($row->shipping_stand_price)?>" />
                    <?php echo esc_html($this->currency->currency_code); ?>
                </td>
            </tr>
            <?php if ($this->config->tax){?>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_TAX)?>*
                </td>
                <td>
                    <?php echo $lists['taxes'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <?php }?>
            <tr>
                <td class="key">
                    <?php echo esc_html(WOPSHOP_PACKAGE_PRICE)?>*
                </td>
                <td>
                    <input type = "text" class = "inputbox" name = "package_stand_price" value = "<?php echo esc_attr($row->package_stand_price)?>" />
                    <?php echo esc_html($this->currency->currency_code); ?>
                </td>
            </tr>
            <?php if ($this->config->tax){?>
            <tr>
                <td class="key">
                   <?php echo esc_html(WOPSHOP_PACKAGE_TAX)?>*
                </td>
                <td>
                    <?php echo $lists['package_taxes'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </td>
            </tr>
            <?php }?>

        <?php foreach($this->extensions as $extension){
            $extension->exec->showShippingPriceForm($row->getParams(), $extension, $this);
            }
        ?>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="clr"></div>
    </div>
</div>

<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=shippingsprices'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <input type = "hidden" name = "sh_pr_method_id" value = "<?php echo esc_attr($row->sh_pr_method_id)?>" />
    <input type = "hidden" name = "shipping_id_back" value = "<?php echo esc_attr($this->shipping_id_back)?>" />

    <?php wp_nonce_field('shippingsprices_edit','name_of_nonce_field'); ?>
</form>