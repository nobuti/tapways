<?php get_header(); ?>
	
	<div class="wrapper">
  		<section class="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


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

		<h2>Not Found</h2>

	<?php endif; ?>

		</section> 
  		<!--! end of section.main -->
  		<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>	
  	</div>
  	

<?php get_footer(); ?>
