<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="product_videos" class="tab-pane">
   <table><tr>
    <?php foreach ($lists['videos'] as $video){ 
		if (!$video->video_preview) $video->video_preview="video.gif";
		$show_video_code=($video->video_code != '') ? 1 : 0;
    ?>
        <td style="padding-right:5px;">
            <div id="video_product_<?php esc_attr(print $video->video_id)?>">
                <div style="padding-bottom:5px;">
                    <?php if ($show_video_code) : ?>
                        <a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-products&task=getvideocode&video_id='.$video->video_id))?>">
                    <?php else : ?>
                        <a target="_blank" href="<?php echo esc_url($config->video_product_live_path."/".$video->video_name)?>">
                    <?php endif; ?>
                        <img width="80" src="<?php echo esc_url($config->video_product_live_path."/".$video->video_preview)?>" border="0" />
                    </a>
                </div>
                <div class="link_delete_foto"><a href="#" onclick="if (confirm('<?php print esc_attr(WOPSHOP_DELETE_VIDEO);?>')) deleteVideoProduct('<?php echo esc_attr($video->video_id)?>');return false;"><img src="<?php echo esc_url(WOPSHOP_PLUGIN_URL.'assets/images/publish_r.png')?>"> <?php print esc_html(WOPSHOP_DELETE_VIDEO);?></a></div>
            </div>
        </td>
    <?php } ?>
    </tr></table>
    <div class="col100">
    <table class="admintable" >
        <?php for ($i=0; $i < $config->product_video_upload_count; $i++){?>
        <tr>
            <td class="key" style="width:250px;"><?php print esc_html(WOPSHOP_UPLOAD_VIDEO)?></td>
            <td>
            <input type="file" name="product_video_<?php print esc_attr($i);?>" /><textarea rows="5" cols="22" name="product_video_code_<?php print esc_attr($i);?>" style="display: none;"></textarea>
			<?php if ($config->show_insert_code_in_product_video) { ?>
            <div style="padding-top:3px;"><input type="checkbox" value="1" name="product_insert_code_<?php print esc_attr($i);?>" id="product_insert_code_<?php print esc_attr($i);?>" onclick="changeVideoFileField(this);"/><label for="product_insert_code_<?php print esc_attr($i);?>"><?php print esc_html(WOPSHOP_INSERT_CODE)?></label></div>
			<?php } ?>
            </td>
        </tr>
        <tr>
            <td class="key"><?php print esc_html(WOPSHOP_UPLOAD_VIDEO_IMAGE)?></td>
            <td><input type="file" name="product_video_preview_<?php print esc_attr($i);?>" /></td>
        </tr>
        <tr>
            <td style="height:5px;font-size:1px;">&nbsp;</td>
        </tr>
        <?php }?>
    </table>
	<?php /*if ($config->show_insert_code_in_product_video) { ?>
	<script type="text/javascript">
		updateAllVideoFileField();
	</script>
	<?php } */?>
    </div>
    <div class="clr"></div>
    <br/>
    <?php $pkey='plugin_template_attribute'; if ($this->$pkey){ print $this->$pkey;} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <div class="helpbox">
        <div class="head"><?php echo esc_html(WOPSHOP_ABOUT_UPLOAD_FILES);?></div>
        <div class="text">
            <?php print wp_kses_post(sprintf(WOPSHOP_SIZE_FILES_INFO, ini_get("upload_max_filesize"), ini_get("post_max_size")));?>
        </div>
    </div>
</div>