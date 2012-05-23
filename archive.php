<?php get_header(); ?>
	
	<div class="wrapper">
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

		<?php /* If this is a category archive */ if (is_category()) { ?>
			<!-- <h1>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1> -->

		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
			<!-- <h1>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1> -->

		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<!-- <h1>Archive for <?php the_time('F jS, Y'); ?></h1> -->

		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<!-- <h1>Archive for <?php the_time('F, Y'); ?></h1> -->

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<!-- <h1>Archive for <?php the_time('Y'); ?></h1> -->

		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<!-- <h1>Author Archive</h1> -->

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<!-- <h1>Blog Archives</h1> -->
		
		<?php } ?>

  		<section class="main">

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>
			
				<article>
			      <a href="<?php the_permalink() ?>"><?php the_post_thumbnail(); ?></a>
			      <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
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
			      
			      
			      
		    	</article>

			<?php endwhile; ?>
			
	<?php else : ?>

		<h1>Nothing found</h1>

	<?php endif; ?>

	</section> 
  		<!--! end of section.main -->
  		<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>
  	</div>
  	
<?php get_footer(); ?>
