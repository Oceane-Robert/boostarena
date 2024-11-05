<div class="loop">
<?php if (have_posts()) : query_posts('posts_per_page=2'); ?>
    <p class="title">
    Hey ! Il y a des Posts !
    </p>
<?php while (have_posts()) : the_post(); ?>

    <div class="post">
        <?php if (has_post_thumbnail()) : ?>
    <div>
        <?php the_post_thumbnail(); ?>
    </div>
<?php endif; ?>
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="post-info">
        Posté le <?php the_date(); ?> dans <?php the_category(', '); ?> <?php the_tags(', '); ?> par <?php the_author(); ?>.
        </p>

        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </div>
<?php endwhile; ?>
<?php else : ?>
<p class="nothing">
Il n'y a pas de Post à afficher !
</p>
<?php endif; ?>
</div>