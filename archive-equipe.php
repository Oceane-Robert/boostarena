<?php get_header(); ?>

<h1 class="hero">
    Les équipes de projet
</h1>
<div class="cards">
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<article class="card">
<div class="image-card">
<?php the_post_thumbnail( 'large' ); ?>
</div>
<h2 class="title-card">
<a href="<?php the_permalink(); ?>">
<?php the_title(); ?>
</a>
</h2>
</article>
<?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_footer(); ?>