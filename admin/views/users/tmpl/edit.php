<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$user = $this->user;
$lists = $this->lists;
$config_fields = $this->config_fields;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});	
function enableFields(val){
    if (val==1){
        jQuery('.endes').removeAttr("disabled");    
    }else{
        jQuery('.endes').attr('disabled','disabled'); 
    }
}
</script>
<form autocomplete="off" method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&task=save'))?>" enctype="multipart/form-data">
<div class="wrap">
	<h3><?php echo  esc_html($user->user_id ? WOPSHOP_EDIT . ' / ' . $user->u_name :  WOPSHOP_NEW); ?></h3>
    <div class="tabs">
        <ul class="tab-links">
            <li class="active"><a href="#firstpage1"><?php echo esc_html(WOPSHOP_GENERAL); ?></a></li>
            <li><a href="#firstpage2"><?php echo esc_html(WOPSHOP_BILL_TO); ?></a></li>
            <li><a href="#firstpage3"><?php echo esc_html(WOPSHOP_SHIP_TO); ?></a></li>
        </ul>
        <div class="tab-content">
            <div id="firstpage1" class="tab active">
                <div class="col100">
                    <fieldset class="adminform">
                    <table class="admintable">
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_USERNAME);?>*
                            </td>
                            <td>
                                <input type = "text" class = "inputbox" name = "u_name" value = "<?php echo esc_attr($user->u_name )?>" />
                            </td>
                        </tr>
                        <tr>
                          <td class="key">
                            <?php echo esc_html(WOPSHOP_EMAIL)?>*
                          </td>
                          <td>
                            <input type = "text" class = "inputbox" name = "email" value = "<?php echo esc_attr($user->email )?>" />
                          </td>
                        </tr>
                        <?/*<tr>
                          <td class="key">
                            <?php echo esc_html(WOPSHOP_NUMBER)?>
                          </td>
                          <td>
                            <input type="text" class="inputbox" name="number" value="<?php echo esc_attr($user->number)?>" />
                          </td>
                        </tr>*/?>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_NEW_PASSWORD )?>
                            </td>
                            <td>
                                <input class="inputbox" type="password" name="password" id="password" size="40" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_PASSWORD_2 )?>
                            </td>
                            <td>
                                <input class="inputbox" type="password" name="password2" id="password2" size="40" value=""/>
                            </td>
                        </tr>
                        <?php /*if ($this->me->authorize( 'wshop_users', 'block user' )) {*/ ?>
<!--                        <tr>
                            <td class="key">
                                <?php echo esc_html(WOPSHOP_BLOCK_USER )?>
                            </td>
                            <td>
                                <?php echo $this->lists['block'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </td>
                        </tr>-->
                        <?php /*}*/ ?>
                        <tr>
                          <td class="key">
                            <?php echo esc_html(WOPSHOP_USERGROUP_NAME);?>*
                          </td>
                          <td>
                            <?php echo $lists['usergroups']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                          </td>
                        </tr>
                    </table>
                    </fieldset>
                    </div>
                <div class="clr"></div>
            </div>
            <div id="firstpage2" class="tab">
                <div class="col100">
                    <fieldset class="adminform">
                    <table class="admintable">
                    <?php if ($config_fields['title']['display']){?>
                    <tr>
                        <td class="key">
                            <?php echo esc_html(WOPSHOP_USER_TITLE )?>
                        </td>
                        <td>
                            <?php echo $lists['select_titles']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['f_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_USER_FIRSTNAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "f_name" value = "<?php echo esc_attr($user->f_name )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['l_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_USER_LASTNAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "l_name" value = "<?php echo esc_attr($user->l_name )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['m_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_M_NAME )?>
                      </td>
                      <td>
                        <input type = "text" name = "m_name" id = "m_name" value = "<?php print esc_attr($user->m_name )?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['firma_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIRMA_NAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "firma_name" value = "<?php echo esc_attr($user->firma_name )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['client_type']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_CLIENT_TYPE);?>
                      </td>
                      <td>
                        <?php print $lists['select_client_types']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                      </td>
                    </tr>
                    <?php } ?>

                    <?php if ($config_fields['firma_code']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_FIRMA_CODE )?> 
                      </td>
                      <td>
                        <input type = "text" name = "firma_code" id = "firma_code" value = "<?php print esc_attr($user->firma_code )?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['tax_number']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_VAT_NUMBER )?>
                      </td>
                      <td>
                        <input type = "text" name = "tax_number" id = "tax_number" value = "<?php print esc_attr($user->tax_number )?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['birthday']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_BIRTHDAY)?>
                      </td>
                      <td>
						  <input id="birthday" class="datepicker birthday" type="text" name="birthday" value="<?php echo esc_attr($user->birthday);?>">
                        <?php /*echo JWopshopHtml::_('calendar', $user->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));*/?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['home']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIELD_HOME)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "home" value = "<?php echo esc_attr($user->home )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['apartment']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIELD_APARTMENT)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "apartment" value = "<?php echo esc_attr($user->apartment )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['street']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_STREET_NR)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "street" value = "<?php echo esc_attr($user->street )?>" />
                        <?php if ($config_fields['street_nr']['display']){?>
                        <input type = "text" class = "inputbox" name = "street_nr" value = "<?php echo esc_attr($user->street_nr) ?>" />
                        <?php }?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['city']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_CITY)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "city" value = "<?php echo esc_attr($user->city) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['zip']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_ZIP)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "zip" value = "<?php echo esc_attr($user->zip) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['state']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_STATE)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "state" value = "<?php echo esc_attr($user->state) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['country']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_COUNTRY)?>
                      </td>
                      <td>
                        <?php echo $lists['country']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['phone']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_TELEFON)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "phone" value = "<?php echo esc_attr($user->phone )?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['mobil_phone']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_MOBIL_PHONE )?>
                      </td>
                      <td>
                        <input type = "text" name = "mobil_phone" id = "mobil_phone" value = "<?php print esc_attr($user->mobil_phone) ?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['fax']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FAX)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "fax" value = "<?php echo esc_attr($user->fax) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['ext_field_1']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_1) ?>
                      </td>
                      <td>
                        <input type = "text" name = "ext_field_1" id = "ext_field_1" value = "<?php print esc_attr($user->ext_field_1) ?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['ext_field_2']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_2) ?>
                      </td>
                      <td>
                        <input type = "text" name = "ext_field_2" id = "ext_field_2" value = "<?php print esc_attr($user->ext_field_2) ?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['ext_field_3']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_3) ?>
                      </td>
                      <td>
                        <input type = "text" name = "ext_field_3" id = "ext_field_3" value = "<?php print esc_attr($user->ext_field_3) ?>" class = "inputbox" />
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                    </fieldset>
                </div>
                <div class="clr"></div>
            </div>
            <div id="firstpage3" class="tab">
                <div class="col100">
                    <fieldset class="adminform">
                    <table class="admintable">
                    <tr>
                        <td class="key">
                            <?php echo esc_html(WOPSHOP_DELIVERY_ADRESS);?>
                        </td>
                        <td>
                            <input type="radio" name="delivery_adress" <?php if ($user->delivery_adress==0) {?> checked="checked" <?php } ?> value="0" onchange="enableFields(this.value)"> <?php echo esc_html(WOPSHOP_NO);?>
                            &nbsp;
                            <input type="radio" name="delivery_adress" <?php if ($user->delivery_adress==1) {?> checked="checked" <?php } ?> value="1" onchange="enableFields(this.value)"> <?php echo esc_html(WOPSHOP_YES);?>
                        </td>
                    </tr>
                    <?php if ($config_fields['d_title']['display']){?>
                    <tr>
                        <td class="key">
                            <?php echo esc_html(WOPSHOP_USER_TITLE) ?>
                        </td>
                        <td>
                            <?php echo $lists['select_d_titles']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_f_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_USER_FIRSTNAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_f_name" value = "<?php echo esc_attr($user->d_f_name) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_l_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_USER_LASTNAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_l_name" value = "<?php echo esc_attr($user->d_l_name) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_m_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_M_NAME) ?>
                      </td>
                      <td>
                        <input type = "text" name = "d_m_name" id = "d_m_name" value = "<?php print esc_attr($user->d_m_name) ?>" class = "inputbox endes" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_firma_name']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIRMA_NAME);?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_firma_name" value = "<?php echo esc_attr($user->d_firma_name) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_birthday']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_BIRTHDAY)?>
                      </td>
                      <td>
						  <input id="d_birthday" class="datepicker d_birthday endes" type="text" name="d_birthday" value="<?php echo esc_attr($user->d_birthday);?>">
                        <?php /*echo JWopshopHtml::_('calendar', $user->d_birthday, 'd_birthday', 'd_birthday', $this->config->field_birthday_format, array('class'=>'inputbox endes', 'size'=>'25', 'maxlength'=>'19')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped*/?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_home']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIELD_HOME)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "d_home" value = "<?php echo esc_attr($user->d_home) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_apartment']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FIELD_APARTMENT)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox" name = "d_apartment" value = "<?php echo esc_attr($user->d_apartment) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_street']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_STREET_NR)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_street" value = "<?php echo esc_attr($user->d_street) ?>" />
                        <?php if ($config_fields['d_street_nr']['display']){?>
                        <input type = "text" class = "inputbox endes" name = "d_street_nr" value = "<?php echo esc_attr($user->d_street_nr) ?>" />
                        <?php }?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_city']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_CITY)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_city" value = "<?php echo esc_attr($user->d_city) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_zip']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_ZIP)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_zip" value = "<?php echo esc_attr($user->d_zip) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_state']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_STATE)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_state" value = "<?php echo esc_attr($user->d_state) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_country']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_COUNTRY)?>
                      </td>
                      <td>
                        <?php echo $lists['d_country']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_phone']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_TELEFON)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_phone" value = "<?php echo esc_attr($user->d_phone) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_mobil_phone']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_MOBIL_PHONE) ?>
                      </td>
                      <td>
                        <input type = "text" name = "d_mobil_phone" id = "d_mobil_phone" value = "<?php print esc_attr($user->d_mobil_phone )?>" class = "inputbox endes" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_fax']['display']){?>
                    <tr>
                      <td class="key">
                        <?php echo esc_html(WOPSHOP_FAX)?>
                      </td>
                      <td>
                        <input type = "text" class = "inputbox endes" name = "d_fax" value = "<?php echo esc_attr($user->d_fax) ?>" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_ext_field_1']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_1) ?>
                      </td>
                      <td>
                        <input type = "text" name = "d_ext_field_1" id = "d_ext_field_1" value = "<?php print esc_attr($user->d_ext_field_1) ?>" class = "inputbox endes" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_ext_field_2']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_2) ?>
                      </td>
                      <td>
                        <input type = "text" name = "d_ext_field_2" id = "d_ext_field_2" value = "<?php print esc_attr($user->d_ext_field_2) ?>" class = "inputbox endes" />
                      </td>
                    </tr>
                    <?php } ?>
                    <?php if ($config_fields['d_ext_field_3']['display']){?>
                    <tr>
                      <td class="key">
                        <?php print esc_html(WOPSHOP_EXT_FIELD_3) ?>
                      </td>
                      <td>
                        <input type = "text" name = "d_ext_field_3" id = "d_ext_field_3" value = "<?php print esc_attr($user->d_ext_field_3) ?>" class = "inputbox endes" />
                      </td>
                    </tr>
                    <?php } ?>
                    </table>
                    </fieldset>
                </div>
                <script type="text/javascript">
                    enableFields(<?php echo esc_html($user->delivery_adress)?>);
                </script>
                <div class="clr"></div>
            </div>
        </div>
</div>
<br>    
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <input type="hidden" value="<?php echo esc_attr($this->user->user_id); ?>" name="user_id">
    <?php wp_nonce_field('client_edit'); ?>
</form>
