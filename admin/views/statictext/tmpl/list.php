<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuConfigs('statictext');
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_STATIC_TEXT); ?>
        <a href="<?php esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&task=edit&row=0'))?>" class="add-new-h2"><?php echo esc_html(WOPSHOP_NEW); ?></a>
    </h2>
    <form id="listing" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <?php if($this->orderby == 'alias') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_title" class="manage-column column-order_title <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&orderby=alias&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_STATIC_TEXT_PAGE); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th width="10%"></th>
                    <?php if($this->orderby == 'id') $class_name = 'sorted'; else $class_name = 'sortable';?>
                    <th id="order_id" class="manage-column column-order_id <?php echo esc_attr($class_name); ?> <?php echo esc_attr($this->order); ?>" scope="col" width="60">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&orderby=id&order='.$this->order))?>">
                            <span class="status_head tips"><?php echo esc_html(WOPSHOP_ID); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($this->statictext) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="3"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php  
                }else{
                    foreach($this->statictext as $index=>$st){?>
                    <tr class="<?php if($index%2) echo 'alt'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
                        <td class="name-column" scope="col">
                            <strong>
                            <?php echo esc_html($st->alias); ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit">
                                <a class="" title="<?php echo esc_attr(WOPSHOP_EDIT); ?>" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&task=edit&row='.$st->id))?>"><?php echo esc_html(WOPSHOP_EDIT); ?></a>
                                </span>
                            </div>
                        </td>
                        <td align="center">
                            <?php if (!in_array($st->alias, $this->config->sys_static_text)){?>
                                <a href='<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&task=delete&row='.$st->id))?>'><img alt="<?php echo esc_html(WOPSHOP_DOWN); ?>" src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"/></a>
                            <?php }?>
                        </td>
                        <td align="center">
                            <?php print esc_html($st->id); ?>
                        </td>
                    </tr>
                <?php }
                } ?>
            </tbody>
        </table>

        <input type="hidden" value="configuration" name="page">
        <input type="hidden" value="statictext" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>


