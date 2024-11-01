<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php if ($this->allow_review || $this->config->show_hits){?>
<div class="block_rating_hits">
    <table>
        <tr>
            <?php if ($this->config->show_hits){?>
                <td><?php print esc_html(WOPSHOP_HITS)?>: </td>
                <td><?php print esc_html($this->product->hits);?></td>
            <?php } ?>
            
            <?php if ($this->allow_review && $this->config->show_hits){?>
                <td> | </td>
            <?php } ?>
            
            <?php if ($this->allow_review){?>
                <td>
                    <?php print esc_html(WOPSHOP_RATING)?>:
                </td>
                <td>
                    <?php print wp_kses_post(wopshopShowMarkStar($this->product->average_rating));?>
                </td>
            <?php } ?>
        </tr>
    </table>
</div>
<?php } ?>