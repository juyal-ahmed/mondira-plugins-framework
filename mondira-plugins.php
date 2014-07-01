<?php
/*
Framework: Mondira Plugin Framework
Plugin URI: http://mondira.com/
Description: A WordPress Plugins development framework.
Author: Jewel Ahmed
Author URI: http://www.codeatomic.com
Version: 1.0.0 beta
Compatible WordPress Version: 3.9.x
Last Updated: 01 Jul, 2014
Remark: Optimized folder structures and constants
*/

if(!class_exists('Mondira_Plugins')){
    class Mondira_Plugins {
        var $enqueue_reources = array();
        var $theme_supports = array('post', 'page');
        var $plugins_name = '';
        var $plugins_slug = '';
        var $plugins_url = '';
        var $author_url = '';
        var $no_posttype = false;
        var $settings_only = false;
        var $settings_available = false;
        var $documentation_available = false;
        var $config = array();
        var $plugin_menus = array();
        
        function init() {
            
            $this->constants();
            $this->setup();
            
            add_action('init', array(&$this,'site_init')); 
            
            $this->loadcore();
                 
            $this->functions();
            
            $this->admin();     
            
        }
        
        function mondira_add_theme_support($post_type = array()){
            foreach($post_type as $k=>$v)
                $this->theme_supports[] = $v;
        }
                                                     
        function site_init(){
            global $current_user;
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('thickbox'); 
        }
        
        function constants() {
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_SLUG'] = $this->plugins_slug;
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_NAME'] = $this->plugins_name;
            
            
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_DIR'] = WP_PLUGIN_DIR.'/'.$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_SLUG'];
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_URI'] = plugins_url().'/'.$this->config[$this->plugins_slug]['MONDIRA_PLUGINS_SLUG'];
            
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_DIR'] = $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_DIR'] . '/mondira-plugins-framework';
            $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_URI'] = $this->config[$this->plugins_slug]['MONDIRA_PLUGINS_URI'] . '/mondira-plugins-framework';
            
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_DIR'] . '/constants.php');   
            
        }
        
        function setup(){
            add_action('after_setup_theme', array(&$this, 'addsupports'));
        }
        
        function loadcore(){
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_LIB_CORE_DIR'] . '/Helper.php');
            include_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_HELPERS_DIR'] . '/HtmlHelper.php');
            include_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_HELPERS_DIR'] . '/MetaboxHelper.php');
            
        }
        
        function addsupports() {
            if (function_exists('add_theme_support')) {
                add_theme_support('post-thumbnails', $this->theme_supports);
            }
        }
        
        function functions() {
            $this->options();   
            
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_FUNCTIONS_DIR'] . '/general.php');
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_FUNCTIONS_DIR'] . '/templates.php');
            require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_FUNCTIONS_DIR'] . '/image.php');
            
            
        }
        
        function options() {
            global $mondira_options;
            $mondira_options = array();
            
            if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . '/mondira-plugins-settings-'.$this->plugins_slug.'.php')) {
                $page = include ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . '/mondira-plugins-settings-'.$this->plugins_slug.'.php');
            } else if(file_exists($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . '/'.$this->plugins_slug.'.php')) {
                $page = include ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_OPTIONS_DIR'] . '/'.$this->plugins_slug.'.php');
            }
            
            if(!empty($page) && is_array($page) && !empty($page['list']) && is_array($page['list']))
            foreach($page['list'] as $option) {
                
                $section_array = !empty($mondira_options[$option['section']])?$mondira_options[$option['section']]:array();
                if(empty($section_array) || !is_array($section_array))
                    $section_array = array();
                
                $option_array = get_option($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_SLUG'] . '_' . $option['section']);
                if(empty($option_array) || !is_array($option_array))
                    $option_array = array();
                
                $mondira_options[$option['section']] = array_merge((array) $section_array, (array) $option_array);
            }
            
            $this->config[$this->plugins_slug]['PLUGINS_OPTIONS'] = $mondira_options;
        }
        
        function admin() {
            if (is_admin()) {
                require_once ($this->config[$this->plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_DIR'] . '/mondira-plugins-admin.php');
                $plugins_admin = new Mondira_Plugins_Admin();
                $plugins_admin->admin_init($this->plugins_name, $this->plugins_slug, $this->config, $this->plugin_menus, $this->no_posttype, $this->settings_only, $this->settings_available, $this->documentation_available);
            }
        }     
    }
}