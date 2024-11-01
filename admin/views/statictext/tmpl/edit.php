<form method="POST" action="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext&task=save'))?>" enctype="multipart/form-data">
<div class="wrap">
    <h2><?php echo esc_html(WOPSHOP_EDIT_STATICTEXT); echo esc_html(" "); print esc_html($this->statictext->alias); ?></h2>
    <?php if (!$this->statictext->id){?>
    <ul>
       <li class="key">
         <?php echo esc_html(WOPSHOP_ALIAS); ?>
       </li>
       <li>
         <input type="text" class="inputbox" name="alias" size="40" value="<?php echo esc_attr($this->statictext->alias); ?>" />
       </li>
    </ul>
    <?php } ?>
    <div class="tabs">
        <ul class="tab-links">
            <?php 
            foreach($this->languages as $index=>$language){?>
                <li><a <?php if($index=0) echo ' class="active" ';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> href="<?php echo esc_html('#tab'.$language->language); ?>"><?php echo esc_html(WOPSHOP_DESCRIPTION.' '); echo esc_html($language->name); ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
        <?php foreach($this->languages as $index=>$language){?>
        <div class="tab <?php if($index=0) echo ' active ';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" id="tab<?php echo esc_attr($language->language); ?>" style="<?php if($index != 0) echo ' display:none; ';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
            <div class="form-field form-required term-code-wrap">
                <?php 
                $val_description = 'text_'.$language->language;
				$args = array('media_buttons' => 1,'textarea_name' => "text".$language->id,'textarea_rows' => 20,'tabindex' => null,'tinymce' => 1);
				wp_editor( $this->statictext->$val_description, "text_".$language->language, $args );
                ?>
            </div>
        </div>
    <?php } ?>
        </div>
    </div>
<div clas="submit">
    <p class="submit">
        <input class="button button-primary" type="submit" value="<?php echo esc_attr(WOPSHOP_ACTION_SAVE); ?>" name="submit">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=wopshop-configuration&tab=statictext'))?>"><?php echo esc_html(WOPSHOP_BACK); ?></a>
    </p> 
</div>
    <input type="hidden" value="<?php echo esc_attr($this->statictext->id); ?>" name="statictext_id">
    <?php wp_nonce_field('statictext_edit','name_of_nonce_field'); ?>
</form>
