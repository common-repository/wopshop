<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_USER_LIST); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&task=edit&user_id=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
    </h2>
	<form action="" method="POST" name="search">
        <?php echo $this->search; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    </form>
    <?php //echo $this->top_counters;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <form action="<?php echo esc_url(admin_url('admin.php'))?>" id="listing" method="GET" name = "adminForm">
        <input type="hidden" name="page" value="clients">
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <?php echo $this->bulk; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php print $this->tmp_html_filter // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php //echo $this->pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column wopshop-admin-list-check" scope="col">
                        <input id="cb-select-all-1" type="checkbox" />
                    </th>
                    <?php if($this->orderby == 'u_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_u_name" class="manage-column column-order_u_name <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&orderby=u_name&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_USERNAME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'f_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_f_name" class="manage-column column-order_f_name <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&orderby=f_name&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_NAME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'l_name') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_l_name" class="manage-column column-order_l_name <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&orderby=l_name&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_L_NAME); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <?php if($this->orderby == 'U.email') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_Uemail" class="manage-column column-order_Uemail <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&orderby=U.email&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_EMAIL); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column" scope="col" width="80px">
                        <span class="status_head"><?php echo esc_html(WOPSHOP_GROUP); ?></span>
                    </th>
                    <th class="manage-column" scope="col" width="80px">
                        <span class="status_head"><?php echo esc_html(WOPSHOP_ORDERS); ?></span>
                    </th>
                    <th class="manage-column" scope="col" width="50px">
                        <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>                    
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($this->rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="8"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($this->rows as $k=>$v){?>
                    <tr class="<?php if($k%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <td class="check-column wopshop-admin-list-check" scope="col">
                            <input id="user_<?php echo esc_attr($v->user_id); ?>" type="checkbox" value="<?php echo esc_attr($v->user_id); ?>" name="rows[]" />
                        </td>
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($v->u_name)?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&task=edit&user_id='.$v->user_id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                |
                                </span>
                                <span class="trash">
                                <a class="submitdelete" title="<?php echo esc_attr(WOPSHOP_DELETE); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-clients&task=delete&rows[]='.$v->user_id))?>"><?php echo esc_html(WOPSHOP_DELETE); ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_html($v->f_name)?>
                        </td>
                        <td class="code2-column " scope="col">
                            <?php echo esc_html($v->l_name)?>
                        </td>
                        <td class="status-column">
                            <?php echo esc_html($v->email); ?>
                        </td>
                        <td class="status-column">
                            <?php echo esc_html($v->usergroup_name); ?>
                        </td>
                        <td class="status-column">
                            <?php echo "<a href='".esc_url(admin_url('admin.php?page=wopshop-orders&client_id='.$v->user_id))."' target='_blank'>".esc_html(WOPSHOP_ORDERS)."</a>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                        <td class="code-column " scope="col">
                            <?php echo esc_html($v->user_id); ?>
                        </td>                        
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>