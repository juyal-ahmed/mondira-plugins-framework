<?php 

if ( !function_exists( 'get_post_templates') ) { 
    function get_post_templates()  {
        $themes = get_themes();
        
        $theme = get_current_theme();
        $templates = $themes[ $theme ][ 'Template Files' ];

        $post_templates = array();

        if ( is_array( $templates ) ) {
            $theme_root = dirname(get_theme_root());
            $base = array( trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()) );

            foreach ( $templates as $template ) {
                // Some setups seem to pass the templates without the theme root,
                // so we conditionally prepend the root for the theme files.
                if ( stripos( $template, $theme_root ) === false )
                    $template = $theme_root . $template;
                $basename = str_replace($base, '', $template);

                // Don't allow template files in subdirectories
                if ( false !== strpos($basename, '/') )
                    continue;

                // Get the file data and collapse it into a single string
                $template_data = implode( '', file( $template ));

                $name = '';
                if ( preg_match( '|Template Name Posts:(.*)$|mi', $template_data, $name ) ){
                    echo $name[1];
                    exit;
                    $name = _cleanup_header_comment( $name[1] );
                }
                    

                if ( !empty( $name ) )
                    $post_templates[trim( $name )] = $basename;
            }
        }

        return $post_templates;
    }
}  