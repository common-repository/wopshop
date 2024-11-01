<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if (count ($this->demofiles)){?>
    <div class="list_product_demo">
        <table>
            <?php foreach($this->demofiles as $demo){?>
                <tr>
                    <td class="descr"><?php print wp_kses_post($demo->demo_descr)?></td>
                    <?php if ($this->config->demo_type == 1) { ?>
                    <td class="download"><a target="_blank" href="<?php print esc_url($this->config->demo_product_live_path."/".$demo->demo)?>" onClick="popupWin = window.open('<?php print esc_url(wopshopSEFLink("controller=product&task=showmedia&media_id=".$demo->id))?>', 'video', 'width=<?php print esc_attr($this->config->video_product_width);?>,height=<?php print esc_attr($this->config->video_product_height);?>,top=0,resizable=no,location=no'); popupWin.focus(); return false;"><img src = "<?php print esc_url($this->config->live_path.'/assets/images/play.gif')?>" alt = "play" title = "play"/></a></td>
                    <?php } else { ?>
                        <td class="download"><a target="_blank" href="<?php print esc_url($this->config->demo_product_live_path."/".$demo->demo)?>"><?php print esc_html(WOPSHOP_DOWNLOAD)?></a></td>
                    <?php }?>
                </tr>
            <?php }?>
        </table>
    </div>
<?php } ?>