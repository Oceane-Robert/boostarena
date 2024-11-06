<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
      <article class="page-body">
          <h1 class="hero">
          <?php the_title(); ?>
          </h1>
          <?php $image = get_field('image_de_lequipe'); ?>
          <div class="element-equipe">
            <?php
            $user = get_field("capitaine_de_lequipe");
            if( $user ): ?>
            <div class="author-box">
                <figure class="avatar"><?php echo get_avatar($user['ID'], 1000); ?></figure>            
                <p>
                    <?php echo $user['display_name']; ?>  
                </p>
            </div>
            <?php endif; ?>
            <?php
            $user = get_field("membre_1");
            if( $user ): ?>
            <div class="author-box">
                <figure class="avatar"><?php echo get_avatar($user['ID'], 1000); ?></figure>            
                <p>
                    <?php echo $user['display_name']; ?>  
                </p>
            </div>
            <?php endif; ?>
            <?php
            $user = get_field("membre_2");
            if( $user ): ?>
            <div class="author-box">
                <figure class="avatar"><?php echo get_avatar($user['ID'], 1000); ?></figure>            
                <p>
                    <?php echo $user['display_name']; ?>  
                </p>
            </div>
            <?php endif; ?>
          </div>  
      </article>
  <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
