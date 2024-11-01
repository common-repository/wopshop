<?php
$row=$this->payment;
$params=$this->params;
$lists=$this->lists;
?>
<div class="wrap">
    <h3><?php echo  esc_html($row->payment_id ? WOPSHOP_EDIT_PAYMENT . ' / ' . $row->{WopshopFactory::getLang()->get('name')} :  WOPSHOP_NEW_PAYMENT); ?></h3>
	<form action="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments&task=save'))?>" method="post" name="adminForm" id="adminForm">
    <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="tabs">
            <ul class="tab-links">
                <li class="active"><a href="#tab1"><?php echo esc_html(WOPSHOP_PAYMENT_GENERAL)?></a></li>
                <li><a href="#tab2"><?php echo esc_html(WOPSHOP_PAYMENT_CONFIG)?></a></li>
            </ul>
            <div class="tab-content">
                <div id="tab1" class="tab active">                   
                    <table class="admintable" width="100%" >
                        <tr>
                            <td class="key" width="30%">
                                <?php echo esc_html(WOPSHOP_PUBLISH)?>
                            </td>
                            <td>
                                <input type="checkbox" name="payment_publish" value="1" <?php if ($row->payment_publish) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_CODE)?>
                            </td>
                            <td>
                                <input type="text" class="inputbox" id="payment_code" name="payment_code" value="<?php echo esc_attr($row->payment_code);?>" />
                            </td>
                        </tr>
                    <?php foreach($this->languages as $lang){ $field="name_".$lang->language; ?>
                        <tr>
                          <td class="key">
                                <?php echo esc_html(WOPSHOP_TITLE); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>*
                          </td>
                          <td>
                            <input type="text" class="inputbox" id="<?php print esc_attr($field)?>" name="<?php print esc_attr($field)?>" value="<?php echo esc_attr($row->$field);?>" />
                          </td>
                        </tr>
                    <?php }?>
                        <tr>
                          <td class="key">
                            <?php echo esc_html(WOPSHOP_ALIAS);?>*
                          </td>
                          <td>
                            <input type="text" class="inputbox" name="payment_class" value="<?php echo esc_attr($row->payment_class);?>" />
                          </td>
                        </tr>
                        <tr>
                          <td class="key">
                            <?php echo esc_html(WOPSHOP_SCRIPT_NAME)?>
                          </td>
                          <td>       
                                <input type="text" class="inputbox" name="scriptname" value="<?php echo esc_attr($row->scriptname);?>" <?php if ($this->config->shop_mode==0 && $row->payment_id){?>readonly <?php }?> />
                          </td>
                        </tr>
                    <?php if ($this->config->tax){?>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_SELECT_TAX);?>*
                            </td>
                            <td>
                                <?php echo $lists['tax']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </td>
                        </tr>
                    <?php }?>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_PRICE);?>
                            </td>
                            <td>
                                <input type="text" class="inputbox" name="price" value="<?php echo esc_attr($row->price);?>" />
                                <?php echo $lists['price_type']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_IMAGE_URL);?>
                            </td>
                            <td>
                                <input type="text" class="inputbox" name="image" value="<?php echo esc_attr($row->image);?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_TYPE_PAYMENT);?>
                            </td>
                            <td>
                                <?php echo $lists['type_payment']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </td>
                        </tr>
                    <?php
                    foreach($this->languages as $lang){
                    $field="description_".$lang->language;
                    ?>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_DESCRIPTION); ?> <?php if ($this->multilang) print esc_html("(".$lang->lang.")");?>
                            </td>
                            <td>
                                <?php
                                  $args = array('media_buttons' => 1, 'textarea_name' => "description".$lang->id, 'textarea_rows' => 20, 'tabindex' => null, 'tinymce' => 1,);
                                  wp_editor( $row->$field, "description".$lang->id, $args );
                                ?>
                            </td>
                        </tr>
                    <?php }?>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_SHOW_DESCR_IN_EMAIL);?>
                            </td>
                            <td>
                                <input type="checkbox" name="show_descr_in_email" value="1" <?php if ($row->show_descr_in_email) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_SHOW_DEFAULT_BANK_IN_BILL);?>
                            </td>
                            <td>
                                <input type="hidden" name="show_bank_in_order" value="0">
                                <input type="checkbox" name="show_bank_in_order" value="1" <?php if ($row->show_bank_in_order) echo 'checked="checked"' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_DESCRIPTION_IN_BILL);?>
                            </td>
                            <td>
                                <textarea name="order_description" rows="6" cols="30"><?php print wp_kses_post($row->order_description)?></textarea>
                            </td>
                        </tr>
						<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </table> 
                </div>
                <div id="tab2" class="tab">
                <?php
                if ($lists['html']!=""){
                    echo $lists['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
                </div>
           </div>
        </div>
    <input type="hidden" name="payment_id" value="<?php echo esc_attr($row->payment_id)?>" />
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php wp_nonce_field('payment_edit','wop_shop'); ?>
    <p class="submit">
    <input id="submit" class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">

    <a style="margin-left:50px;" id="back" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=payments'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
    </form>
</div>