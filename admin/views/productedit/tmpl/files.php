<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="product_files" class="tab-pane"> 
   <div class="col100">
    <table class="admintable" >
        <?php 
        foreach ($lists['files'] as $file){
            //FilterOutput::objectHTMLSafe( $file, ENT_QUOTES);
        ?> 
        <tr class="rows_file_prod_<?php esc_attr(print $file->id)?>">
            <td class="key" style="width:250px;"><?php print esc_html(WOPSHOP_DEMO_FILE)?></td>
            <td id='product_demo_<?php print esc_attr($file->id)?>'>
            <?php if ($file->demo){?>
                <a target="_blank" href="<?php print esc_url($config->demo_product_live_path."/".$file->demo)?>"><?php print esc_html($file->demo)?></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="if (confirm('<?php print esc_attr(WOPSHOP_DELETE);?>')) deleteFileProduct('<?php echo esc_attr($file->id)?>','demo');return false;"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"> <?php print esc_html(WOPSHOP_DELETE);?></a>
            <?php } ?>
            </td>
        </tr>
        <tr class="rows_file_prod_<?php print esc_attr($file->id)?>">
           <td class="key">
             <?php echo esc_html(WOPSHOP_DESCRIPTION_DEMO_FILE);?>
           </td>
           <td>
             <input type="text" size="100" name="product_demo_descr[<?php print esc_attr($file->id);?>]" value="<?php print esc_attr($file->demo_descr);?>"/>
           </td>
         </tr>
        <tr class="rows_file_prod_<?php print esc_attr($file->id)?>">
            <td class="key"><?php print esc_html(WOPSHOP_FILE_SALE)?></td>
            <td id='product_file_<?php print esc_attr($file->id)?>'>
            <?php if ($file->file){?>
                <a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=getfilesale&id='.$file->id))?>">
                    <?php print esc_html($file->file)?>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="if (confirm('<?php print esc_attr(WOPSHOP_DELETE);?>')) deleteFileProduct('<?php echo esc_attr($file->id)?>','file');return false;"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"> <?php print esc_html(WOPSHOP_DELETE);?></a>
            <?php } ?>
            </td>
        </tr>
        <tr class="rows_file_prod_<?php print esc_attr($file->id)?>">
           <td class="key">
             <?php echo esc_html(WOPSHOP_DESCRIPTION_FILE_SALE);?>
           </td>
           <td>
             <input type="text" size="100" name="product_file_descr[<?php print esc_attr($file->id);?>]" value="<?php print esc_attr($file->file_descr);?>" />
           </td>
        </tr>
        <tr class="rows_file_prod_<?php print esc_attr($file->id)?>">
           <td class="key">
             <?php echo esc_html(WOPSHOP_ORDERING);?>
           </td>
           <td>
             <input type="text" size="25" name="product_file_sort[<?php print esc_attr($file->id);?>]" value="<?php print esc_attr($file->ordering);?>" />
           </td>
        </tr>
        <tr class="rows_file_prod_<?php print esc_attr($file->id)?>">
            <td style="height:5px;font-size:1px;" colspan="2"><hr/></td>
        </tr>
        <?php } ?>                
        <?php 
        $config->product_file_upload_count;
        $sort=count($lists['files']);
        for ($i=0; $i<$config->product_file_upload_count; $i++){?>
        <tr>
            <td class="key" style="width:250px;"><?php print esc_html(WOPSHOP_DEMO_FILE)?></td>
            <td>
                <?php if ($config->product_file_upload_via_ftp!=1){?>
                <input type="file" name="product_demo_file_<?php print esc_attr($i);?>" />
                <?php }?>
                <?php if ($config->product_file_upload_via_ftp){?>
                <div style="padding-top:3px;"><input size="34" type="text" name="product_demo_file_name_<?php print esc_attr($i);?>" title="<?php print esc_html(WOPSHOP_UPLOAD_FILE_VIA_FTP)?>" /></div>
                <?php }?>
            </td>
        </tr>
        <tr>
           <td class="key">
             <?php echo esc_html(WOPSHOP_DESCRIPTION_DEMO_FILE);?>
           </td>
           <td>
             <input type="text" size="100" name="product_demo_descr_<?php print esc_attr($i);?>" value=""/>
           </td>
         </tr>
        <tr>
            <td class="key"><?php print esc_html(WOPSHOP_FILE_SALE)?></td>
            <td>
                <?php if ($config->product_file_upload_via_ftp!=1){?>
                <input type="file" name="product_file_<?php print esc_attr($i);?>" />
                <?php }?>
                <?php if ($config->product_file_upload_via_ftp){?>
                <div style="padding-top:3px;"><input size="34" type="text" name="product_file_name_<?php print esc_attr($i);?>" title="<?php print esc_attr(WOPSHOP_UPLOAD_FILE_VIA_FTP)?>" /></div>
                <?php }?>
            </td>
        </tr>
        <tr>
           <td class="key">
             <?php echo esc_html(WOPSHOP_DESCRIPTION_FILE_SALE);?>
           </td>
           <td>
             <input type="text" size="100" name="product_file_descr_<?php print esc_attr($i);?>" value=""/>
           </td>
        </tr>
        <tr>
           <td class="key">
             <?php echo esc_html(WOPSHOP_ORDERING);?>
           </td>
           <td>
               <input type="text" size="25" name="product_file_sort_<?php echo esc_attr( $i );?>" value="<?php echo esc_html( $sort + $i )?>" />
           </td>
        </tr>
        <tr>
            <td style="height:5px;font-size:1px;" colspan="2"><hr/></td>
        </tr>
        <?php }?>
    </table>
    </div>
    <div class="clr"></div>
    <br/>    
    <br/>
    <div class="helpbox">
        <div class="head"><?php echo esc_html(WOPSHOP_ABOUT_UPLOAD_FILES);?></div>
        <div class="text">
            <?php echo wp_kses_post( sprintf(WOPSHOP_SIZE_FILES_INFO, ini_get("upload_max_filesize"), ini_get("post_max_size")) );?>
        </div>
    </div>
</div>