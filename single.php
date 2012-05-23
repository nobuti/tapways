<?php get_header(); ?>
	
	<?php while (have_posts()) : the_post(); ?>

	<div class="wrapper">
    <div class="info">
      <div class="nav">
        <div class="left"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/grid_icon.png" alt=""></a></div>
        <div class="right"><?php previous_post_link('%link', '« Previous', false); ?> <?php next_post_link('%link', 'Next »', false); ?></div>
        <div class="clearfix"></div>
      </div>
      <h1><?php the_title(); ?></h1>
      <ul>
      	<?php 
      		$categories = get_the_category();
      		$last_key = end(array_keys($categories));
			foreach($categories as $key => $category) {
				$category_link = get_category_link( $category->cat_ID );
				$list_item = '<li><a href="'.esc_url( $category_link ).'">'.$category->cat_name.'</a>';
				if ($key != $last_key) {
					$list_item .= '<span class="divider">/</span>';
				}
				$list_item .= '</li>';
				echo $list_item;
			} 
		?>
      </ul>
      <hr>
      <p class="descripcion"><?php get_text(); ?></p>
      <?php edit_post_link('Edit this entry','','.'); ?>
      <hr>
      <div class="share">
        <div class="left">
          <p>Share</p>
        </div>  
        <div class="right">
          <ul>
            <li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/fb_icon.png" alt="Facebook"></a></li>
            <li><a href="http://twitter.com/home?status=Currently reading on Tapways: <?php the_title(); ?> <?php the_permalink(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/tw_icon.png" alt="Twitter"></a></li>
            <li><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id()); ?>&description=<?php echo get_the_title(); ?>" class="pin-it-button" count-layout="none"><img src="<?php bloginfo('template_directory'); ?>/img/pt_icon.png" alt="Pinterest"></a></li>
            <li><a href="http://www.tumblr.com/share/link?url=<?php echo urlencode(the_permalink()) ?>&name=<?php echo urlencode(the_title()) ?>&description=<?php echo urlencode(the_excerpt()) ?>"><img src="<?php bloginfo('template_directory'); ?>/img/tblr_icon.png" alt="Tumblr"></a></li>
            <li><a href="mailto:type%20email%20address%20here?subject=I%20wanted%20to%20share%20this%20post%20with%20you%20from%20<?php bloginfo('name'); ?>&body=<?php the_title(); ?> - <?php the_permalink(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/mail_icon.png" alt="Mail"></a></li>
          </ul>
        </div>
      </div>
    </div>
    <section class="main">
      <div class="post">
        <?php 
        	get_images();
        ?>
      </div> 
    </section>
    <!--! end of div.main -->

  </div>

  <?php endwhile; ?>

<?php get_footer(); ?>