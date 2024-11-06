<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
      <article class="page-body">
          
          <?php the_post_thumbnail( 'large' ); ?>
          <h1 class="hero">
          <?php the_title(); ?>
          </h1>
          <p>Le <?php the_field('dateheure_du_match'); ?>H</p>

      </article>
  <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
