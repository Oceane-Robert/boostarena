<?php get_header(); ?>
<div class="hero-image">
    <?php 
    $header_image = get_theme_mod('header_image_setting');
    if ($header_image) : ?>
        <img src="<?php echo esc_url($header_image); ?>" alt="Image d'en-tête" class="hero-img">
    <?php endif; ?>
</div>
<div class="accueil">
    <div class="content">
        <h1>BoostArena</h1>
        <h3>DU 2 AU 30 NOVEMBRE</h3>
        <p class="slogan">Rejoins vite le plus grand tournoi de Rocket League</p>
        <button class="btn Button">S'inscrire</button>
    </div>
</div>
    <p>Bienvenue  sur BoostArena ! 

Prêts à marquer l'histoire de Rocket League ? Rejoignez notre communauté de joueurs passionnés et participez à des matchs épiques en 3v3. Sur BoostArena, le respect, le plaisir et l'esprit d'équipe sont les maîtres-mots. Alors, rassemble tes coéquipiers et inscris-toi dès maintenant !

</p>
<div>
    <h2>LES PROCHAINS MATCHS</h2>
    <?php
    // Boucle pour les matchs du carrousel
    $carousel_matches = new WP_Query(array(
        'post_type' => 'match',
        'posts_per_page' => -1, // Récupérer tous les matchs pour le carrousel
    ));
    ?>
    <section class="archive-match__carousel" x-data="{ currentSlide: 0, totalSlides: <?php echo $carousel_matches->post_count; ?> }">
        <div class="carousel relative overflow-hidden">
            <div class="slides flex transition-transform duration-500" :style="{ transform: 'translateX(-' + currentSlide * 100 + '%)' }">
                <?php
                if ($carousel_matches->have_posts()) :
                    while ($carousel_matches->have_posts()) : $carousel_matches->the_post();
                        // Récupérer les équipes
                        $equipe1 = get_field('equipe_1'); 
                        $equipe2 = get_field('equipe_2');
                        
                        if ($equipe1 && is_array($equipe1) && isset($equipe1[0]) && $equipe2 && is_array($equipe2) && isset($equipe2[0])) {
                            // Récupérer les IDs des équipes (en prenant le premier élément du tableau)
                            $equipe1_id = $equipe1[0]->ID;
                            $equipe2_id = $equipe2[0]->ID;
                            
                            // Récupérer les images des équipes
                            $image_equipe1 = get_field('image_de_lequipe', $equipe1_id);
                            $image_equipe2 = get_field('image_de_lequipe', $equipe2_id);
                            
                            // Récupérer les noms des équipes
                            $nom_equipe1 = $equipe1[0]->post_title;
                            $nom_equipe2 = $equipe2[0]->post_title;
                            ?>
                            <div class="carousel__item w-full flex-shrink-0">
                                <div class="matchs-card">
                                    <div class="match-equipe">
                                        <div class="teams-container">
                                            <div class="team-logo">
                                                <?php if ($image_equipe1) : ?>
                                                    <img src="<?php echo esc_url($image_equipe1['url']); ?>" 
                                                         alt="<?php echo esc_attr($nom_equipe1); ?>" />
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="vs">VS</div>
                                            
                                            <div class="team-logo">
                                                <?php if ($image_equipe2) : ?>
                                                    <img src="<?php echo esc_url($image_equipe2['url']); ?>" 
                                                         alt="<?php echo esc_attr($nom_equipe2); ?>" />
                                                <?php endif; ?>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="match-date">
                                        <p><?php the_field('dateheure_du_match'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <button @click="currentSlide = (currentSlide > 0) ? currentSlide - 1 : totalSlides - 1" class="carousel__nav carousel__nav--prev absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-4 py-2">&lsaquo;</button>
            <button @click="currentSlide = (currentSlide < totalSlides - 1) ? currentSlide + 1 : 0" class="carousel__nav carousel__nav--next absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-4 py-2">&rsaquo;</button>
        </div>
    </section>
</div>

<?php get_footer(); ?>