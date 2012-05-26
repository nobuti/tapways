<?php
	
    function get_text(){
        global $post;
        $content = get_the_content();
        $postOutput = preg_replace('/<img[^>]+./','', $content);
        echo $postOutput;
    }
    function get_images(){
        global $post;
        
        $szPostContent = $post->post_content;
        $szSearchPattern = '~<img [^\>]*\ />~';

        preg_match_all( $szSearchPattern, $szPostContent, $aPics );
        $iNumberOfPics = count($aPics[0]);

        if ( $iNumberOfPics > 0 ) {
             for ( $i=0; $i < $iNumberOfPics ; $i++ ) {
                  echo $aPics[0][$i];
             };
        };
    }
    
    // Head js inserts
    function my_scripts_method() {
       
        wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false);
        wp_enqueue_script('jquery');

        wp_register_script('pinterest', ("http://assets.pinterest.com/js/pinit.js"), false);
        wp_enqueue_script('pinterest');
    
        wp_enqueue_script(
            'modernizr',
            get_template_directory_uri() . '/js/libs/modernizr-2.5.3.min.js',
            false
        );
        wp_enqueue_script(
            'respond',
            get_template_directory_uri() . '/js/respond.min.js',
            false
        );
        wp_enqueue_script(
            'fite',
            get_template_directory_uri() . '/js/jquery.fite.min.js',
            array('jquery')
        );
        wp_enqueue_script(
            'touchdown',
            get_template_directory_uri() . '/js/jquery.touchdown.min.js',
            array('jquery')
        );
        wp_enqueue_script(
            'smoke',
            get_template_directory_uri() . '/js/smoke.min.js',
            false
        );
        wp_enqueue_script(
            'plugin',
            get_template_directory_uri() . '/js/plugins.js',
            false
        );
        wp_enqueue_script(
            'script',
            get_template_directory_uri() . '/js/script.js',
            array('jquery')
        );
    }
    
    // Ajax send form callback
    function submit_design_callback() {
        
        $result['type'] = 'success';

        if(trim($_POST['email']) === '')  {
            $result['type'] = "error";
            $result['message'] = 'Please enter your email address.';
        } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
            $result['type'] = "error";
            $result['message'] = 'You entered an invalid email address.';
        } else {
            $email = trim($_POST['email']);
        }

        if(trim($_POST['url']) === '') {
            $result['type'] = "error";
            $result['message'] = 'Please enter a url.';
        } else if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", trim($_POST['url']))) {
            $result['type'] = "error";
            $result['message'] = 'You entered an invalid URL.';
        } else {
            $url = trim($_POST['url']);
        }
        if($result['type'] == 'success') {
            $emailTo = get_option('admin_email');
            
            $subject = 'Tapways submitted design';
            $body = "Email: $email \nURL: $url";
            $headers = 'From: Tapways <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

            wp_mail($emailTo, $subject, $body, $headers);
            $result['type'] = "success";
            $result['message'] = "Thank you!";
        }
        echo json_encode($result);
        die();
    }

	add_theme_support( 'automatic-feed-links' );
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

    // Includes js scripts
    add_action('wp_enqueue_scripts', 'my_scripts_method');

    // Set featured images' sizes
    if ( function_exists( 'add_theme_support' ) ) {
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 240 );
    }

    add_action('wp_ajax_sbdesign', 'submit_design_callback');
    add_action('wp_ajax_nopriv_sbdesign', 'submit_design_callback');
    
    // Search only in posts
    function SearchFilter($query) {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }
    add_filter('pre_get_posts','SearchFilter');

    // Clean p>a>img
    function filter_ptags_on_images($content){
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
    add_filter('the_content', 'filter_ptags_on_images');

?>