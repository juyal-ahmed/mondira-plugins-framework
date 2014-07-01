<?php       
if(!class_exists('SettingsGenerator')){                     
    class SettingsGenerator {
	var $title;
	var $docs;
    var $config;
    var $plugins_slug;
	function SettingsGenerator($config, $plugins_slug, $title, $docs, $settings_only) {
        $this->config = $config;
        $this->plugins_slug = $plugins_slug;
        $this->settings_only = $settings_only;
		$this->title = $title;
		$this->docs = $docs;
        
        $this->processPost();
		$this->render();
	}
	
    function processPost(){ 
        
        if(!empty($_POST['slug'])){
            
            $options = get_option( $this->config[ $this->plugins_slug ][ 'MONDIRA_PLUGINS_SLUG' ] . '_' . $_POST[ 'slug' ] );
            $post_option_values_array = $_POST[ $_POST[ 'slug' ] ];
            
            //Validating $post_option_values_array
            $tmpArr = $post_option_values_array;
            foreach ( $post_option_values_array as $key => $value ) {
                $tmpval = stripslashes( $value );
                $tmpArr[ $key ] = $tmpval;
            }
            
            //Fix for WP 3.9 as It is not taking any id as array for wp_editor
            foreach ( $_POST as $key => $value ) {
                if( !is_array( $value) ) {
                    $tmpval = stripslashes( $value );
                    $tmpArr[ $key ] = $tmpval;
                }
            }
            
            if ( !empty( $options ) && is_array( $options ) ) {
                update_option( $this->config[ $this->plugins_slug ][ 'MONDIRA_PLUGINS_SLUG' ] . '_' . $_POST[ 'slug' ], $tmpArr );
            } else {
                add_option( $this->config[ $this->plugins_slug ][ 'MONDIRA_PLUGINS_SLUG' ] . '_' . $_POST[ 'slug' ], $tmpArr );
            }

            
        }
    }
    
	function render() {
        //$this->settings_only
        if($this->settings_only){
            $settings_page_url = $this->plugins_slug;
        } else {
            $settings_page_url = 'mondira-plugins-settings-' . $this->plugins_slug;
        }
		echo '<div class="wrap mondira-docs-page">';
		echo '<div id="icon-'.$this->plugins_slug.'" class="icon32 icon32-posts-'.$this->plugins_slug.'"><br></div><h2>'.$this->title.'</h2>';
		
		echo '<div id="mondira-options-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all"><ul class="mondira-docs-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
        $i = 0;
        if(!empty($this->docs))
		foreach($this->docs as $docs) {
            
            if(empty($_GET['section'])){
                if($i==0)
                echo '<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="admin.php?page='.$settings_page_url.'&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                else
                echo '<li class="ui-state-default ui-corner-top"><a href="admin.php?page='.$settings_page_url.'&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                
                $i++;
            } else {
                if($docs['section']==$_GET['section'])
                echo '<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="admin.php?page='.$settings_page_url.'&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
                else
                echo '<li class="ui-state-default ui-corner-top"><a href="admin.php?page='.$settings_page_url.'&section='.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
            }
            
		}
		echo '</ul>';
        
        $i = 0;
        if(!empty($this->docs))
		foreach($this->docs as $docs) {
            
            if(empty($_GET['section'])){
                if($i==0)
                    $this->renderSection($docs['section']);
                
                $i++;
            } else {
                if($docs['section']==$_GET['section'])
                    $this->renderSection($docs['section']);
            }
            
			//$this->renderSection($docs['section']);
		}
		echo '<div class="clear"></div>';
		echo '</div>';
		echo '</div>';
	}
	
	function renderSection($section) {
		echo '<div id="'.$section.'" class="block">';   
        
        $html = new Html(array('slug'=>$section));
        $options = get_option($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_SLUG'] . '_' . $section);
        
        if(!empty($options))
            extract($options);
       
        
        if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_OPTIONS_DIR'].'/'.$section.'.php'))
            include $this->config[$this->plugins_slug]['ONDIRA_PLUGINS_FRAMEWORK_ADMIN_OPTIONS_DIR'].'/'.$section.'.php';
        else if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'].'/'.$section.'.php'))
		    include $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'].'/'.$section.'.php';
            
        
        
		echo '<div class="clear"></div>';
		echo '</div>';
	}
}
}
