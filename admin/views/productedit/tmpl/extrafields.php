<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}
$groupname = "";
?>
<div id="tabExtraFields" class="tab">

    <div id="product_extra_fields" class="tab-pane">
        <div class="col100" id="extra_fields_space">
            <?php print $this->tmpl_extra_fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <div class="clr"></div>
    </div>
</div>
