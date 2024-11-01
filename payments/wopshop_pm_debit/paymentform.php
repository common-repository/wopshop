<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$adv_user = WopshopFactory::getUser();
?>
<table>
   <tr>
     <td width="200">
       <?php echo esc_html(WOPSHOP_ACCOUNT_HOLDER)?>
     </td>
     <td>
       <input type="text" class="inputbox" name="params[pm_debit][acc_holder]" id="params_pm_debit_acc_holder" value="<?php print esc_attr($adv_user->kontonummer)?>"/>
     </td>
   </tr>
   <tr>
     <td>
       <?php echo esc_html(WOPSHOP_IBAN)?>
     </td>
     <td>
       <input type="text" class="inputbox" name="params[pm_debit][bank_iban]" id="params_pm_debit_bank_iban" value="<?php print esc_attr($adv_user->bankleitzahl)?>"/>
     </td>
   </tr>
   <tr>
     <td>
       <?php echo esc_html(WOPSHOP_BANK)?> <?php echo esc_html(WOPSHOP_CITY)?>
     </td>
     <td>
       <input type="text" class="inputbox" name="params[pm_debit][bank_bic]" id="params_pm_debit_bank_bic" value="<?php print esc_attr($adv_user->bankort)?>"/>
     </td>
   </tr>
   <tr>
     <td>
       <?php echo esc_html(WOPSHOP_BANK)?>
     </td>
     <td>
       <input type="text" class="inputbox" name="params[pm_debit][bank]" id="params_pm_debit_bank" value="<?php print esc_attr($adv_user->bankname)?>"/>
     </td>
   </tr>
</table>
<script type="text/javascript">
  function check_wopshop_pm_debit(){
    var ar_focus=new Array();
    var error=0;
    unhighlightField('payment_form');
    if (isEmpty($F_("params_pm_debit_acc_holder"))) {
        ar_focus[ar_focus.length]="params_pm_debit_acc_holder";
        error=1;
    }
    if (isEmpty($F_("params_pm_debit_bank_iban"))) {
        ar_focus[ar_focus.length]="params_pm_debit_bank_iban";
        error=1;
    }
    if (isEmpty($F_("params_pm_debit_bank"))) {
        ar_focus[ar_focus.length]="params_pm_debit_bank";
        error=1;
    }
    if (error){
        $_(ar_focus[0]).focus();
        for (var i=0; i<ar_focus.length; i++ ){
           highlightField(ar_focus[i]);
        }
        return false;
    }
    jQuery('#payment_form').submit();
  }
 </script>