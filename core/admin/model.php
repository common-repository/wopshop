<?php
class WshopAdminModel{
    
    public $lang;
    protected $tableFieldPublish = 'publish';
    protected $tableFieldOrdering = 'ordering';
    protected $tablename;
    
    public function __construct() {
        $this->lang = WopshopFactory::getConfig()->getLang();
    }

    function search($search){
        return '<p class="search-box">
            <input id="plugin-search-input" type="search" value="'.$search.'" name="s">
            <input id="search-submit" class="button" type="submit" value="'.WOPSHOP_SEARCH.'" name="search-submit">
        </p>';
    }
    
    function countersList($publish, $page, $tab, $count_all, $count_publish, $count_unpublish){
        if($publish == '') $class_all = 'class="current"'; else $class_all = '';
        if($publish == '1') $class_publish = 'class="current"'; else $class_publish = '';
        if($publish == '0') $class_unpublish = 'class="current"'; else $class_unpublish = '';
        $html = 
        '<ul class="subsubsub">
            <li class="all">
                <a '.$class_all.' href="'.esc_url(admin_url('admin.php?page=wopshop-'.$page.'&tab='.$tab)).'">'.WOPSHOP_QUERY_ALL.' <span class="count">('.$count_all.')</span></a>
                |
            </li>
            <li class="active">
                <a '.$class_publish.' href="'.esc_url(admin_url('admin.php?page=wopshop-'.$page.'&tab='.$tab.'&publish=1')).'">'.WOPSHOP_QUERY_PUBLISH.' <span class="count">('.$count_publish.')</span></a>
                |
            </li>
            <li class="inactive">
                <a '.$class_unpublish.' href="'.esc_url(admin_url('admin.php?page=wopshop-'.$page.'&tab='.$tab.'&publish=0')).'">'.WOPSHOP_QUERY_UNPUBLISH.' <span class="count">('.$count_unpublish.')</span></a>
            </li>
        </ul>';
        return $html;
    }

    function getListLanguages(){
        global $wpdb;
        $name_table = $wpdb->prefix.'wshop_languages';
        return $listLanguages = $wpdb->get_results( "SELECT * FROM ".$name_table." WHERE `publish` > 0", ARRAY_A);
    } 
    
    public function getBulkActions($actions, $which = 'top' ) {
        $two = '';
        if ( empty( $actions ) )
                return;
        $output =  "<label for='bulk-action-selector-" . esc_attr( $which ) . "' class='screen-reader-text'>" . __( 'Select bulk action' ) . "</label>";
        $output .= "<select name='action$two' id='bulk-action-selector-" . esc_attr( $which ) . "'>\n";
        $output .= "<option value='-1' selected='selected'>" . WOPSHOP_ACTION_ACTIONS . "</option>\n";
        foreach ( $actions as $name => $title ) {
                $class = 'edit' == $name ? ' class="hide-if-no-js"' : '';

                $output .= "\t<option value='" . esc_attr( $name ) . "'" . esc_attr( $class ) . ">" . esc_attr( $title ) ."</option>\n";
        }
        $output .= "</select>\n";
        $output .= get_submit_button( WOPSHOP_ACTION_APPLY, 'action', false, false, array( 'id' => "doaction$two" ) );
        return $output."\n";
    }     
    
    public static function getPagination($total_items, $per_page = 20, $which = 'top' ) {
        $total_pages = ceil($total_items / $per_page);
        $selectItems = [];
        $selectItems[] = WopshopHtml::_('select.option', 5, '5', 'id', 'value');
        $selectItems[] = WopshopHtml::_('select.option', 10, '10', 'id', 'value');
        $selectItems[] = WopshopHtml::_('select.option', 20, '20', 'id', 'value');
        $selectItems[] = WopshopHtml::_('select.option', 50, '50', 'id', 'value');
        $selectItems[] = WopshopHtml::_('select.option', 100, '100', 'id', 'value');
        $select = WopshopHtml::_('select.genericlist', $selectItems, 'per_page',' class="inputbox" onchange="document.getElementById(\'listing\').submit();" size="1"','id','value', $per_page);
        $infinite_scroll = false;
        $output = '<span class="displaying-num">' . sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';

        $current = self::get_pagenum();
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
         if(is_ssl()){
             $current_url = set_url_scheme( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
         }else{
             $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
         }       

        $current_url = remove_query_arg( array( 'hotkeys_highlight_last', 'hotkeys_highlight_first' ), $current_url );

        $page_links = array();

        $disable_first = $disable_last = '';
        if ( $current == 1 ) {
                $disable_first = ' disabled';
        }
        if ( $current == $total_pages ) {
                $disable_last = ' disabled';
        }
        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
                'first-page' . $disable_first,
                esc_attr__( 'Go to the first page' ),
                //esc_url( remove_query_arg( 'paged', $current_url ) ),
                esc_url( add_query_arg( 'paged', 1, $current_url ) ),
                '&laquo;'
        );

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
                'prev-page' . $disable_first,
                esc_attr__( 'Go to the previous page' ),
                esc_url( add_query_arg( 'paged', max( 1, $current-1 ), $current_url ) ),
                '&lsaquo;'
        );

        if ( 'bottom' == $which ) {
                $html_current_page = $current;
        } else {
                $html_current_page = sprintf( "%s<input class='current-page' id='current-page-selector' title='%s' type='text' name='paged' value='%s' size='%d' />",
                        '<label for="current-page-selector" class="screen-reader-text">' . __( 'Select Page' ) . '</label>',
                        esc_attr__( 'Current page' ),
                        $current,
                        strlen( $total_pages )
                );
        }
        $html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
        $page_links[] = '<span class="paging-input">' . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . '</span>';

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
                'next-page' . $disable_last,
                esc_attr__( 'Go to the next page' ),
                esc_url( add_query_arg( 'paged', min( $total_pages, $current+1 ), $current_url ) ),
                '&rsaquo;'
        );

        $page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
                'last-page' . $disable_last,
                esc_attr__( 'Go to the last page' ),
                esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
                '&raquo;'
        );

        $pagination_links_class = 'pagination-links';
        if ( ! empty( $infinite_scroll ) ) {
                $pagination_links_class = ' hide-if-js';
        }
        $links = $total_pages < 2 ? "\n<span class='" . esc_attr( $pagination_links_class ) ."'>" .$select . '</span>' : "\n<span class='" . esc_attr( $pagination_links_class ) . ">" . join( "\n", $page_links ) .$select . '</span>';
        $output .= $links;

        if ( $total_pages ) {
                $page_class = $total_pages < 1 ? ' one-page' : '';
        } else {
                $page_class = ' no-pages';
        }
        return "<div class='tablenav-pages{$page_class}'>$output</div>";
    } 
    
    public static function get_pagenum() {
        //$pagenum = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0;
        $pagenum = WopshopRequest::getInt('paged');
        return max( 1, $pagenum );
    }
    
    public function publish(array $cid, $flag){
        $field = $this->tableFieldPublish;
        
        foreach($cid as $id){
            $table = WopshopFactory::getTable($this->getTableName());
            $table->load($id);
            $table->$field = $flag;
            $table->store();
		}
    }
    
    protected function getTableName(){
		if (empty($this->tablename)){
			$r = null;
            preg_match('/(.*)WshopAdminModel/i', get_class($this), $r);
            $this->tablename = strtolower($r[1]);
		}
        
		return $this->tablename;
	}  
    
    protected function setTableName($tableName){
        $this->tablename = $tableName;
    }
       
    public function getDefaultTable(){
        return WopshopFactory::getTable($this->getTableName());
    }  
    
    public function order($id, $move, $where){
        $table = $this->getDefaultTable();
        $table->load($id);
        $table->move($move, $where, $this->tableFieldOrdering);
    }

    public function saveorder(array $cid, array $order, $where = ''){
        $field = $this->tableFieldOrdering;
        foreach($cid as $k=>$id){
            $table = $this->getDefaultTable();
            $table->load($id);
            if ($table->$field != $order[$k]){
                $table->$field = $order[$k];
                $table->store();
            }
        }
        $table = $this->getDefaultTable();
        $table->$field = null;
        $table->reorder($where, $this->tableFieldOrdering);
    }
    
    public function deleteList(array $cid, $msg = 1){
        $app = WopshopFactory::getApplication();
        $res = array();
		foreach($cid as $id){
            $table = $this->getDefaultTable();
            $table->delete($id);
            if ($msg){
                $app->enqueueMessage(WOPSHOP_ITEM_DELETED, 'message');
            }
            $res[$id] = true;
		}
        return $res;
    }
}