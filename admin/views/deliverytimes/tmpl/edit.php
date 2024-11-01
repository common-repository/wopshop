<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$row=$this->deliveryTimes;
?>
<div class="wrap">
    <div class="form-wrap">
		<h3><?php echo  esc_html($row->id ? WOPSHOP_DELIVERY_TIME_EDIT . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_DELIVERY_TIME_NEW); ?></h3>
        <form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes&task=save'))?>" id="editdeliverytime">
            <table>
                <?php
                foreach($this->languages as $i=>$v){
                    $name = 'name_'.$v->language;
                ?>
                <tr>
                    <td>
                        <label for="name_<?php echo esc_attr($v->language); ?>"><?php echo esc_html(WOPSHOP_NAME); ?> (<?php echo esc_html($v->name);?>)*</label>
                    </td>
                    <td>
                        <input id="name_<?php echo esc_attr($v->language); ?>" type="text" size="40" value="<?php echo esc_attr($row->$name); ?>" name="name_<?php echo esc_attr($v->language); ?>">
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <label for="days"><?php echo esc_html(WOPSHOP_DAYS); ?> *</label>
                    </td>
                    <td>
                        <input id="days" type="text" size="40" value="<?php echo esc_attr($row->days); ?>" name="days">
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
                <a class="button" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=deliverytimes'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
            </p>
            <input type="hidden" value="<?php echo esc_attr($row->id); ?>" name="id">
            <?php wp_nonce_field('deliverytimes_edit','name_of_nonce_field'); ?>
        </form>
    </div>
</div>