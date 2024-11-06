<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
      <article class="page-body">
          
          <?php the_post_thumbnail( 'large' ); ?>
          <h1 class="hero">
          <?php the_title(); ?>
          </h1>
          

      </article>
  <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
