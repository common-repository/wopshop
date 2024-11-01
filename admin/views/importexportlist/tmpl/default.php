<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wopshopDisplaySubmenuOptions('importexport');

$rows=$this->rows;
$i=0;
?>
<div class="wrap">
    <h2>
        <?php echo esc_html(WOPSHOP_PANEL_IMPORT_EXPORT); ?>
    </h2>
    <form id="listing" method="GET" action="<?php echo esc_url(admin_url('admin.php'))?>">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th class="title" width ="10">
                      #
                    </th>
                    <th class="center" width="25%">
                        <?php echo esc_html(WOPSHOP_TITLE); ?>
                    </th>
                    <th class="center">
                        <?php echo esc_html(WOPSHOP_DESCRIPTION); ?>
                    </th>
                    <th class="center" width="10%">
                        <?php echo esc_html(WOPSHOP_AUTOMATIC_EXECUTION); ?>
                    </th>
                    <th class="center" width="10%">
                        <?php echo esc_html(WOPSHOP_DELETE); ?>
                    </th>
                    <th class="center" width="10%">
                        <?php echo esc_html(WOPSHOP_ID); ?>
                    </th>                    
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if(count($rows) == 0){ ?>
                <tr class="no-items">
                <td class="colspanchange" colspan="6"><?php echo esc_html(WOPSHOP_QUERY_RESULT_NULL); ?></td>
                </tr>
                <?php 
                }else{
                    foreach($rows as $row){?>
                    <tr>
                        <td>
                            <?php echo esc_html($i+1);?>
                        </td>

                        <td class="name-column" scope="col">
                            <strong>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=view&ie_id='.$row->id))?>"><?php echo esc_html($row->name); ?></a>
                            </strong>
                        </td>

                        <td>
                            <?php echo wp_kses_post($row->description);?>
                        </td>
                        <td class="center">
                            <a href='<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=setautomaticexecution&cid='.$row->id))?>'>
                                <?php if ($row->steptime>0){?>
                                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_x.png')?>">
                                <?php }else{ ?>
                                    <img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/tick.png')?>">
                                <?php }?>
                            </a>
                        </td> 
                        <td class="center">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=wopshop-options&tab=importexport&task=remove&cid='.$row->id))?>"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/trash.png')?>"></a>
                        </td>                         
                        <td class="center">
                            <?php print esc_html($row->id);?>
                        </td>    
                    </tr>
                    <?php $i++; ?>
                <?php }
                } ?>
            </tbody>
        </table>

        <input type="hidden" value="options" name="page">
        <input type="hidden" value="importexport" name="tab">
    </form>
    <div id="ajax-response"></div>
    <br class="clear">
</div>