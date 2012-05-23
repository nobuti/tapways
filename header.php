<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<?php if (is_search()) { ?>
		<meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

  	<title>
	   <?php
	      if (function_exists('is_tag') && is_tag()) {
	         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
	      elseif (is_archive()) {
	         wp_title(''); echo ' Archive - '; }
	      elseif (is_search()) {
	         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
	      elseif (!(is_404()) && (is_single()) || (is_page())) {
	         wp_title(''); echo ' - '; }
	      elseif (is_404()) {
	         echo 'Not Found - '; }
	      if (is_home()) {
	         bloginfo('name'); echo ' - '; bloginfo('description'); }
	      else {
	          bloginfo('name'); }
	      if ($paged>1) {
	         echo ' - page '. $paged; }
	   ?>
	</title>


  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="author" href="humans.txt" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />	
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <script type="text/javascript">
	var adminURL = "<?php echo admin_url('admin-ajax.php'); ?>";
  </script>

  <?php wp_head(); ?>

</head>

<body>

  
  <header>

    <div class="left">
      <h1><a href="<?php site_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/logo.png" alt="Tapways"></a></h1>
      <div class="nav clearfix">
      	<ul>
      	<?php 
      		if ( is_home() ) {
      			echo '<li class="selected"><a href="'.site_url().'">All</a></li>';
      		} else {
      		 	echo '<li><a href="'.site_url().'">All</a></li>';
      		}
      	 
      		$args=array(
		  		'orderby' => 'name',
		  		'order' => 'ASC'
		    );
			$categories=get_categories($args);
      		$last_key = end(array_keys($categories));
			foreach($categories as $key => $category) { 
				$category_link = get_category_link( $category->cat_ID );
				$list_item = '';
				if (is_category($category->cat_name)){
					$list_item = '<li class="selected">';
				} else {
					$list_item = '<li>';
				}
				$list_item .= '<a href="'.esc_url( $category_link ).'">'.$category->cat_name.'</a></li>';
				echo $list_item;
			} 
		?>
        </ul>
      </div>
      <!-- end .nav -->
    </div>

    <div class="subscribe">
      Subscribe <a href="<?php bloginfo('rss2_url'); ?>" class="rss"><img src="<?php bloginfo('template_directory'); ?>/img/rss_icon.png" alt="Subscribe"></a>
      
      <?php get_search_form(); ?>

      <a href="#" class="button submit-design">Submit design</a>
    </div>
    <!-- end .subscriber -->

  </header>

  <!-- end header -->

  <div class="panel">
    <div class="wrapper submit">
      <a href="#" class="close"><img src="<?php bloginfo('template_directory'); ?>/img/close_icon.png" alt="Close"></a>
      <h2>Submit a design</h2>  
      <form action="<?php the_permalink(); ?>" method="post" accept-charset="utf-8">
        <div class="column">
          <label for="email">Your email</label>
          <input type="text" id="email" name="email" value="">
        </div>
        <div class="column">
          <label for="url">URL</label>
          <input type="text" id="url" name="url" value="">
        </div>
        <div class="column last">
          <button type="submit"><img src="<?php bloginfo('template_directory'); ?>/img/arrow_icon.png" alt="Submit"></button>
          <input type="hidden" name="submitted" id="submitted" value="true" />
        </div>
      </form>
    </div>
    <!-- end .wrapper.submit -->
  </div>
  <!-- end panel -->
