<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$rows = $this->rows;
$lists = $this->lists;
$config = $this->config;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_MENU_ORDERS); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=edit&order_id=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
    </h2>
    <form action="" method="POST" name="search">
        <?php print $this->tmp_html_start // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        
        <div class="order_search_box">
            <div class="search-box">
                <div class="search-box1"><?php echo $this->text_search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                <div class="sort_block">
                    <p class="search-box1" style="line-height: 30px; margin:0 20px 0px 0px;"><?php echo esc_html(WOPSHOP_NOT_FINISHED); ?>:</p>
                    <p class="search-box1"><?php echo $this->lists['notfinished'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>            
                    <p class="search-box1" style="line-height: 30px;margin:0 20px;"><?php echo esc_html(WOPSHOP_ORDER_STATUS); ?>:</p>
                    <p class="search-box1"><?php echo $this->lists['changestatus'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>            
                    <p class="search-box1" style="line-height: 30px;margin:0 20px 0px 20px;"><?php echo esc_html(WOPSHOP_SORT_DATE); ?>:</p>
                    <p class="search-box1" style="margin-right:10px;"><?php echo $this->lists['year'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                    <p class="search-box1" style="margin-right:10px;"><?php echo $this->lists['month'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                    <p class="search-box1" style="margin-right:10px;"><?php echo $this->lists['day'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                </div>   
                <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            
            
        </div>
    </form>    
    <form id="listing" action="" method="post" action = "<?php echo esc_url(admin_url('admin.php?page=wopshop-orders'))?>">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php 
                echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>            
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox" />
                    </th>
                    <?php if($this->filter_order == 'order_number') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="column-primary manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" >
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=order_number&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_NUMBER); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_1 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if($this->filter_order == 'name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col" >
                        <a href="<?php esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=name&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_USER); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_after_user // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if($this->filter_order == 'email') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=email&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_EMAIL); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_3 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if ($this->show_vendor){?>
                    <th>
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_VENDOR); ?></span>
                    </th>
                    <?php } ?>
                    <th>
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDER_PRINT_VIEW); ?></span>
                    </th>
                    <?php print $this->_tmp_cols_4 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if($this->filter_order == 'order_date') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=order_date&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_DATE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    
                    <?php if($this->filter_order == 'order_m_date') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=order_m_date&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDER_MODIFY_DATE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_5 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if (!$config->without_payment){?>
                    <th>
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_PAYMENT); ?></span>
                    </th>
                    <?php }?>
                    <?php if (!$config->without_shipping){?>
                    <th>
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_SHIPPINGS); ?></span>
                    </th>
                    <?php } ?>
                    <?php if($this->filter_order == 'order_status') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=order_status&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_STATUS); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_6 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <th>
                        <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDER_UPDATE); ?></span>
                    </th>
                    <?php print $this->_tmp_cols_7 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if($this->filter_order == 'order_total') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th class="manage-column <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->filter_order_Dir); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&filter_order=order_total&filter_order_Dir='.$this->filter_order_Dir))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ORDER_TOTAL); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php print $this->_tmp_cols_8 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if ($config->shop_mode==1){?>
                    <th>
                      <?php echo esc_html(WOPSHOP_TRANSACTIONS)?>
                    </th>
                    <?php }?>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($rows) == 0){ ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    $i = 0; 
                    foreach($rows as $row){
                        $display_info_order = $row->display_info_order;
                ?>
                    <tr class="row<?php echo esc_attr(($i  %2));?>" <?php if (!$row->order_created) print "style='font-style:italic; color: #b00;'" // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                        <td class="check-column wopshop-admin-list-check" scope="col">
                            <?php if ($row->blocked) : ?>
                                <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/checked_out.png')?>" />
                            <?php else : ?>
                                <input id="cid_<?php echo esc_attr($row->order_id); ?>" type="checkbox" value="<?php echo esc_attr($row->order_id); ?>" name="rows[]" />
                            <?php endif; ?>
                        </td>
                        <td class="column-primary name-column" scope="col">
                            <strong>
                            <a class="" title="<?php echo esc_attr(WOPSHOP_SHOW); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=show&order_id='.$row->order_id))?>"><?php echo esc_html($row->order_number);?></a>
                            <?php if (!$row->order_created) print esc_html("(".WOPSHOP_NOT_FINISHED.")");?>
                            </strong>
                            <?php print $row->_tmp_ext_info_order_number // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=edit&order_id='.$row->order_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=delete&rows[]='.$row->order_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>                            
                        </td>
                        <?php print $row->_tmp_cols_1 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td>        
                            <?php echo esc_html($row->name)?>
                        </td>
                        <?php print $row->_tmp_cols_after_user // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td><?php echo esc_html($row->email)?></td>
                        <?php print $row->_tmp_cols_3 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php if ($this->show_vendor){?>
                        <td>
                           <?php print esc_html($row->vendor_name);?>
                        </td>
                        <?php }?>     
                        <td class = "center">
                           <?php if ($config->order_send_pdf_client || $config->order_send_pdf_admin){?>
                               <?php if ($display_info_order && $row->order_created && $row->pdf_file!=''){?>
                                   <a href = "javascript:void window.open('<?php echo esc_url($config->pdf_orders_live_path."/".$row->pdf_file)?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');">
                                       <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/print.png')?>">
                                   </a>
                               <?php }?>
                           <?php }else{?>
                               <a href = "javascript:void window.open('<?php echo esc_url(admin_url('admin-ajax.php?page=orders&task=printOrder&action=wopshop_printOrder&order_id='.$row->order_id))?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=yes,resizable=yes,width=800,height=600,directories=no,location=no');">
                                   <img border="0" src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/print.png')?>" alt="printhtml" />
                               </a>
                           <?php }?>
                           <?php if (isset($row->_ext_order_info)) echo $row->_ext_order_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <?php print $row->_tmp_cols_4 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td>
                          <?php echo esc_html(wopshop_formatdate($row->order_date, 1));?>
                        </td>
                        <td>
                          <?php echo esc_html(wopshop_formatdate($row->order_m_date, 1));?>
                        </td>
                        <?php print $row->_tmp_cols_5 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php if (!$config->without_payment){?>
                        <td>
                          <?php echo esc_html($row->payment_name)?>
                        </td>
                        <?php }?>
                        <?php if (!$config->without_shipping){?>
                        <td>
                          <?php echo esc_html($row->shipping_name)?>
                        </td>
                        <?php }?>
                        <td>
                           <?php if ($display_info_order && $row->order_created){
                               echo WopshopHtml::_('select.genericlist', $lists['status_orders'], 'select_status_id['.$row->order_id.']', 'class="inputbox" id = "status_id_'.$row->order_id.'"', 'status_id', 'name', $row->order_status ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                           }else{
                               print $this->list_order_status[$row->order_status]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                           }
                           ?>
                            <?php print $row->_tmp_ext_info_status // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <?php print $row->_tmp_cols_6 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td>
                        <?php if ($row->order_created && $display_info_order){?>
                           <input class = "inputbox" type = "checkbox" name = "order_check_id[<?php echo esc_attr($row->order_id)?>]" id = "order_check_id_<?php echo esc_attr($row->order_id)?>" />
                           <label for = "order_id_<?php echo esc_attr($row->order_id)?>"><?php echo esc_html(WOPSHOP_NOTIFY_CUSTOMER)?></label><br />
                           <input class = "button" type = "button" name = "" value = "<?php echo esc_attr(WOPSHOP_UPDATE_STATUS)?>" onclick = "verifyStatus(<?php echo esc_attr($row->order_status); ?>, <?php echo esc_attr($row->order_id); ?>, '<?php echo esc_html(WOPSHOP_CHANGE_ORDER_STATUS);?>', 0, '');" />
                        <?php }?>
                        <?php if ($display_info_order && !$row->order_created && !$row->blocked){
                            echo esc_html($row->order_id); ?>
                           <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=finish&order_id='.$row->order_id))?>"><?php print esc_html(WOPSHOP_FINISH_ORDER)?></a>
                        <?php }?>
                        <?php print $row->_tmp_ext_info_update // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <?php print $row->_tmp_cols_7 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td>
                          <?php if ($display_info_order) echo wopshopFormatprice( $row->order_total,$row->currency_code) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php print $row->_tmp_ext_info_order_total // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <?php print $row->_tmp_cols_8 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php if ($config->shop_mode==1){?>
                        <td align="center">
                          <a href='<?php echo esc_url(admin_url('admin.php?page=wopshop-orders&task=transactions&order_id='.$row->order_id))?>'><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/icons/configurations.png')?>"></a>
                        </td>
                        <?php }?>
                      </tr>
                      <?php
                      $i++;
                      }
                      ?>
                    <tr>
                        <?php 
                        $cols = 9;
                        if (!$config->without_payment) $cols++;
                        if (!$config->without_shipping) $cols++;
                        ?>
                        <?php print $this->tmp_html_col_before_td_foot // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <td colspan="<?php print esc_attr($cols+(int)$this->deltaColspan0)?>" align="right"><b><?php print esc_html(WOPSHOP_TOTAL)?></b></td>
                        <td><b><?php print wopshopFormatprice($this->total, wopshopGetMainCurrencyCode()) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></td
                        <?php print $this->tmp_html_col_after_td_foot // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </tr>
                <?php }
                ?>    
            </tbody>
        </table>
        <?php print $this->tmp_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <input type="hidden" value="wopshop-orders" name="page" />
        <input type = "hidden" name = "task" value = "display" />

    </form>
    <br class="clear">
    <?php print $this->_tmp_order_list_html_end // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped;?>
</div>