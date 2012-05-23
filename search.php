<?php get_header(); ?>
	
	<div class="wrapper">
  		<h1>Search results for <span class="query"><?php echo $_GET['s']; ?></span></h1>
  		<section class="main">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
			<?php if (!is_page()) :?>
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
	    	<?php endif; ?>
		<?php endwhile; ?>

	<?php else : ?>

		<h1>No posts found.</h1>

	<?php endif; ?>

	</section> 
  		<!--! end of section.main -->
  	</div>

<?php get_footer(); ?>
