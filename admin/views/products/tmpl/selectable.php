<?php
if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly
}

$rows = $this->rows;
$lists = $this->lists;
$pageNav = $this->pagination;
$text_search = $this->text_search;
$category_id = $this->category_id;
$manufacturer_id = $this->manufacturer_id;
$count = count($rows);
$eName = $this->eName;
$jsfname = $this->jsfname;
$i = 0;
?>
<div class="wrap">
    
    <form action="" id="selectablesearch" method="post" name="search">
        <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
        <p class="search-box wopshop_admin_products_filters_elements"><?php echo $this->lists['treecategories'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <p class="search-box wopshop_admin_products_filters_elements"><?php echo $this->lists['manufacturers'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <p class="search-box wopshop_admin_products_filters_elements"><?php echo $this->lists['labels'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <p class="search-box wopshop_admin_products_filters_elements"><?php echo $this->lists['publish'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    </form>    
    
    <form action="'<?php echo esc_url(admin_url('admin-ajax.php?page=products&tab=productlistselectable&action=wopshop_modal_insert_product_to_order&order_id=1'))?>" method="post" name="search">
    <table width="100%" style="padding-bottom:5px;">
    </table>

        <table class = "adminlist" style="width:100%">
    <thead> 
      <tr>
        <th class = "title" width  = "10">
          #
        </th>
        <th width="93">
            <?php print esc_html(WOPSHOP_IMAGE); ?>
        </th>
        <th>
          <?php echo esc_html(WOPSHOP_TITLE); ?>
        </th>
        <?php print $this->tmp_html_col_after_title // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php if (!$category_id){?>
        <th width="80">
          <?php echo esc_html(WOPSHOP_CATEGORY);?>
        </th>
        <?php }?>
        <?php if (!$manufacturer_id){?>
        <th width="80">
          <?php echo esc_html(WOPSHOP_MANUFACTURER);?>
        </th>
        <?php }?>
        <th width="60">
            <?php echo esc_html(WOPSHOP_PRICE);?>
        </th>
<!--        <th width="60">
            <?php echo esc_html(WOPSHOP_DATE);?>
        </th>-->
        <th width = "40">
          <?php echo esc_html(WOPSHOP_PUBLISH);?>
        </th>
        <th width = "30">
          <?php echo esc_html(WOPSHOP_ID);?>
        </th>
      </tr>
    </thead> 
    <?php foreach ($rows as $row){?>
      <tr class = "row<?php echo esc_attr($i % 2);?>">
       <td>
         <?php /*echo $pageNav->getRowOffset($i);*/?>
       </td>
       <td>
        <?php if ($row->image){?>
            <a href="#" onclick="window.parent.<?php print esc_attr($jsfname)?>(<?php echo esc_attr($row->product_id); ?>, '<?php echo esc_attr($eName); ?>')">
                <img src="<?php print esc_url($this->config->image_product_live_path."/".$row->image)?>" width="90" border="0" />
            </a>
        <?php }?>
       </td>
       <td>
         <b><a href="#" onclick="window.parent.<?php print esc_attr($jsfname)?>(<?php echo esc_attr($row->product_id); ?>, '<?php echo esc_attr($eName); ?>')"><?php echo esc_html($row->name);?></a></b>
         <br/><?php echo wp_kses_post($row->short_description);?>
       </td>
       <?php print $row->tmp_html_col_after_title // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       <?php if (!$category_id){?>
       <td>
          <?php echo $row->namescats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
       <?php }?>
       <?php if (!$manufacturer_id){?>
       <td>
          <?php echo esc_html($row->man_name);?>
       </td>
       <?php }?>
       <td>		
        <?php echo wopshopFormatprice($row->product_price); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
<!--       <td>
        <?php echo esc_html($row->product_date_added);?>
       </td>-->
       <td align="center">
         <?php echo $published = ($row->product_publish) ? ('<img title = "'.WOPSHOP_PUBLISH.'" border="0" alt="" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png').'">') : ('<img title = "'.WOPSHOP_UNPUBLISH.'" border="0" alt="" src="'.esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png').'">'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
       </td>
       <td align="center">
         <?php echo esc_html($row->product_id); ?>
       </td>
      </tr>
     <?php
     $i++;
     }
     ?>
     <tfoot>
     <tr>
        <?php print $this->tmp_html_col_before_td_foot // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php print $this->tmp_html_col_after_td_foot // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>	
     </tr>
     </tfoot>   
    </table>
    <input type="hidden" name="order_id" value="1" />
    <input type="hidden" name="e_name" value="<?php print esc_attr($eName)?>" />
    <input type="hidden" name="jsfname" value="<?php print esc_attr($jsfname)?>" />
    <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </form>    
</div>
<script>
    var selectablesearch = document.querySelector('#selectablesearch');
    var selectablesearchselects = document.querySelectorAll('#selectablesearch select');
    for (let i = 0; i < selectablesearchselects.length; ++i) {
        selectablesearchselects[i].addEventListener('change', () => {
            selectablesearch.submit();
        });
    }
</script>