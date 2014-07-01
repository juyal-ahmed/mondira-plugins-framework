<?php            
global $mp_config, $mp_plugins_slug;
$mp_config = $this->config;
$mp_plugins_slug = $this->plugins_slug;                                    

if (!function_exists('mondira_plugins_admin_head')) {
function mondira_plugins_admin_head() {   
    global $mp_config, $mp_plugins_slug;             
        ?>
        <script>
        var MONDIRA_PLUGINS_FRAMEWORK_ADMIN_RESOURCES_URI = "<?php echo $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_RESOURCES_URI'];?>";
        var MONDIRA_PLUGINS_FRAMEWORK_LIB_PLUGINS_URI = "<?php echo $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_LIB_PLUGINS_URI'];?>";
        </script>
        <?php
    }
}
add_action('admin_head', 'mondira_plugins_admin_head');

if (!function_exists('load_mondira_plugins_admin_enqueue_script')) {
    function load_mondira_plugins_admin_enqueue_script() {  
        global $mp_config, $mp_plugins_slug;                 
        wp_enqueue_script('mondira-plugins-admin-script', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_JS_URI'] . '/mondira-admin.js');
        wp_enqueue_script('mondira-media-uploader', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_JS_URI'] . '/mondira-image-uploader-3.5.js');
        add_thickbox();
        
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-tabs'); 
        wp_enqueue_script('wp-color-picker'); 
        wp_enqueue_script('mondira-plugins-jquery-ibutton-script', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_JS_URI'] . '/plugins/jquery.ibutton.js');
        wp_enqueue_script('mondira-plugins-jquery-rangeinput-script', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_JS_URI'] . '/plugins/jquery.rangeinput.js');
    }   
}
if(is_admin()){
    add_action('admin_init', 'load_mondira_plugins_admin_enqueue_script');
}

if (!function_exists('load_mondira_plugins_admin_enqueue_style')) {
    function load_mondira_plugins_admin_enqueue_style() {
        global $mp_config, $mp_plugins_slug; 
        //wp_enqueue_style('mondira-plugins-jqurey-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);
        wp_enqueue_style('wp-color-picker'); 
        wp_admin_css();  
        wp_enqueue_style('mondira-plugins-admin-style', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_CSS_URI'] . '/style.css');
        wp_enqueue_style('mondira-plugins-jquery-ibutton-style', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_CSS_URI'] . '/jquery.ibutton.css');
        wp_enqueue_style('mondira-plugins-jquery-rangeinput-style', $mp_config[$mp_plugins_slug]['MONDIRA_PLUGINS_FRAMEWORK_ADMIN_CSS_URI'] . '/jquery.rangeinput.css');
    }
}
if(is_admin()){
    add_action('admin_init', 'load_mondira_plugins_admin_enqueue_style');
}

if (!function_exists('mondira_plugins_admin_tinymce_dialog')) {
    function mondira_plugins_admin_tinymce_dialog() {       
        if (function_exists('add_thickbox')) add_thickbox();
        wp_print_scripts('media-upload');
        wp_admin_css();
        wp_enqueue_script('utils');
    }
}
if(is_admin()){
    add_filter('admin_head','mondira_plugins_admin_tinymce_dialog');
}

if (!function_exists('mondira_plugins_admin_tinymce')) {
    function mondira_plugins_admin_tinymce() { 
        wp_print_scripts('editor');
        if (function_exists('add_thickbox')) add_thickbox();
        wp_print_scripts('media-upload');
        //if (function_exists('wp_tiny_mce')) wp_tiny_mce();
        wp_admin_css();
        wp_enqueue_script('utils');
    }
}

if(is_admin()){
    add_filter('admin_head','mondira_plugins_admin_tinymce');
} 