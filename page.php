<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		

		<div class="wrapper">
		  <section class="main">
		    <div class="page">
		      <?php the_content(); ?>
		    </div>

		    <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

		  </section> 
		  <!--! end of section.main -->
		  </div>

		<?php endwhile; endif; ?>

<?php get_footer(); ?>
