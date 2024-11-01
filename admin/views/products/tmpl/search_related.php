<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$start=intval(WopshopRequest::getInt("start")/$this->limit)+1;
print $this->tmp_html_start; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
foreach($this->rows as $row){ ?>      
<div class="block_related" id="serched_product_<?php print esc_attr($row->product_id);?>">
    <div class="block_related_inner">
        <div class="name"><?php echo esc_html($row->name);?> (ID:&nbsp;<?php print esc_html($row->product_id)?>)</div>
        <div class="image">
            <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=edit&product_id='.$row->product_id))?>">
            <?php if ( strlen($row->image) > 0 ) { ?>
                <img src="<?php print esc_url(wopshopGetPatchProductImage($row->image, 'thumb', 1))?>" width="90" border="0" />
            <?php } else { ?>
                <img src="<?php print esc_url(wopshopGetPatchProductImage($this->config->noimage, '', 1))?>" width="90" border="0" />
            <?php } ?>
            </a>
        </div>
        <div style="padding-top:5px;"><input type="button" value="<?php print esc_attr(WOPSHOP_ADD);?>" onclick="add_to_list_relatad(<?php print esc_attr($row->product_id);?>)"></div>
    </div>
</div>
<?php
}
?>
<div class="clr"></div>

<?php if ($this->pages>1){?>
<table align="center">
<tr>
    <td><?php print esc_html(WOPSHOP_PAGE)?>: </td>
    <td>
    <div class="pagination">
        <div class="button2-left">
        <div class="page">
            <?php
            $pstart=$start - 9;
            if ($pstart<1) $pstart=1;
            $pfinish=$start + 9;
            if ($pfinish>$this->pages) $pfinish=$this->pages;
            ?>
            <?php if ($pstart>1){?>
                <a onclick="return false;" href="#">...</a>
            <?php }?>
            <?php for($i=$pstart;$i<=$pfinish; $i++){?>
                <a onclick="releted_product_search(<?php print esc_attr(($i-1)*$this->limit);?>, <?php print esc_attr($this->no_id)?>);return false;" href="#"><?php print esc_attr($i);?></a>
            <?php } ?>
            <?php if ($pfinish<$this->pages){?>
                <a onclick="return false;" href="#">...</a>
            <?php }?>
        </div>
        </div>
    </div>
    </td>
</tr>    
</table>
<div class="clr"></div>
<?php }?>
<?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>