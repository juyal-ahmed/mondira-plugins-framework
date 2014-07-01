<?php
if(!class_exists('Mondira_Plugins_Admin')){ 
    class Mondira_Plugins_Admin extends Mondira_Plugins{
    
        function admin_init($name, $slug, $config, $plugin_menus, $no_posttype=false, $settings_only=false, $settings_available=false, $documentation_available=false){
            $this->plugins_name = $name;
            $this->plugins_slug = $slug;
            $this->config = $config;
            $this->plugin_menus = $plugin_menus;
            
            $this->no_posttype = $no_posttype;
            $this->settings_only = $settings_only;
            $this->settings_available = $settings_available;
            $this->documentation_available = $documentation_available;
            add_action('admin_menu', array(&$this,'admin_menus'));
            add_action('parent_file', array(&$this,'admin_mondira_plugins_menu_correction'));
            
            $this->admin_functions();
        }
        
        function admin_menus(){
            
            if($this->settings_only){
                add_menu_page( $page_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $capability='edit_theme_options', $this->plugins_slug, array(&$this,'_mondira_plugins_load_options_page'));
            } else {
                if ( $this->no_posttype ) {
                    add_menu_page( $page_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $capability='edit_theme_options', 'mondira-plugins-settings-' . $this->plugins_slug, array(&$this,'_mondira_plugins_load_options_page'));
                } else {
                    add_menu_page( $page_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title=$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $capability='edit_theme_options', $this->plugins_slug);
                }
            }
            
            //Adding Plugins other menus
            if(!empty($this->plugin_menus)){
                foreach($this->plugin_menus as $key=>$value){
                    if(!is_array($value) || empty($value[0]) || empty($value[1]) || empty($value[2]) || empty($value[3])){
                        continue;
                    }
                    add_submenu_page( $this->plugins_slug, $value[0], $value[1], $value[2], $value[3]);        
                }
            }
            
            
            if($this->settings_available) {
                add_submenu_page( $this->plugins_slug, $page_title='Settings | '. $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title='Settings', $capability='edit_theme_options', $menu_slug='mondira-plugins-settings-'.$this->plugins_slug, array(&$this,'_mondira_plugins_load_options_page'));
            }
            
            if($this->documentation_available) {
                if ( $this->no_posttype ) {
                    add_submenu_page( 'mondira-plugins-settings-' . $this->plugins_slug, $page_title='Documentation | '. $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title='Documentation', $capability='edit_theme_options', $menu_slug='mondira-plugins-docs-'.$this->plugins_slug, array(&$this,'_mondira_plugins_load_docs_page'));
                } else {
                    add_submenu_page( $this->plugins_slug, $page_title='Documentation | '. $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'], $menu_title='Documentation', $capability='edit_theme_options', $menu_slug='mondira-plugins-docs-'.$this->plugins_slug, array(&$this,'_mondira_plugins_load_docs_page'));
                }
            }
        }
        
        // highlight the proper top level menu
        function admin_mondira_plugins_menu_correction($parent_file) {
            global $current_screen;
            $taxonomy = $current_screen->taxonomy;
            
            if(!empty($this->plugin_menus)){
                foreach($this->plugin_menus as $key=>$value){
                    if(!is_array($value) || empty($value[0]) || empty($value[1]) || empty($value[2]) || empty($value[3]) || empty($value[4])){
                        continue;
                    }
                    if($taxonomy == $value[4]) {
                        $parent_file = $this->plugins_slug;
                        return $parent_file;
                    }
                }
            }
        }
        
        
        function _mondira_plugins_load_options_page(){
            wp_enqueue_media();   
            include_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_HELPERS_DIR'] . '/SettingsGenerator.php');
            $options = array();
            
            if(!empty($_GET['page']) && file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_OPTIONS_DIR'] . "/" . $_GET['page'] . '.php'))
                $options_admin = include($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_OPTIONS_DIR'] . "/" . $_GET['page'] . '.php');
            
            if(!empty($_GET['page']) && file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . "/" . $_GET['page'] . '.php'))
                $options_public = include($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . "/" . $_GET['page'] . '.php');
                
            if(!empty($options_admin))
                $options = array_merge($options, $options_admin);
            
            //updating the options list
            if(!empty($options_public)){
                $options['title']=$options_public['title'];
                foreach($options_public['list'] as $key=>$value){
                     $options['list'][]=$value;
                }
            } 
            new SettingsGenerator($this->config, $this->plugins_slug, $options['title'], $options['list'], $this->settings_only);
        }
        
        function _mondira_plugins_load_docs_page(){
            include_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_HELPERS_DIR'] . '/DocsGenerator.php');
            $options = array(); 
            
            if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_DOCS_DIR'] . "/" . $_GET['page'] . '.php'))
                $options_admin = include($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_DOCS_DIR'] . "/" . $_GET['page'] . '.php');
            
            if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_DOCS_DIR'] . "/" . $_GET['page'] . '.php'))
                $options_public = include($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_DOCS_DIR'] . "/" . $_GET['page'] . '.php');
                
            if(!empty($options_admin))
                $options = array_merge($options, $options_admin);
            
            //updating the options list
            if(!empty($options_public)){
                $options['title']=$options_public['title'];
                foreach($options_public['docs'] as $key=>$value){
                     $options['docs'][]=$value;
                }
            } 
            new DocsGenerator($this->config, $this->plugins_slug, $options['title'],$options['docs']);
        }
        
        
        function admin_functions(){
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_LIB_FUNCTIONS_DIR'] . '/mondira-admin-general.php');
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_LIB_FUNCTIONS_DIR'] . '/mondira-admin-head.php');
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_LIB_FUNCTIONS_DIR'] . '/mondira-media-upload.php');
        }
    }
}