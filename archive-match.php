<?php get_header(); ?>

<h1 class="hero">
    Matchs
</h1>
<?php
// Récupérer les valeurs possibles du champ ACF "statue"
$statues = get_field_object('statue')['choices'];
?>

<form action="" method="get" class="button-select">
    <select name="status" id="status" class="select">
        <option value="all">Tous les matchs</option>
        <?php foreach ($statues as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($_GET['status'], $key); ?>><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="filtre">Filtrer</button>
</form>

<?php
// Filtrer les matchs en fonction de la sélection de l'utilisateur
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$args = array(
    'post_type' => 'match',
    'posts_per_page' => -1,
);

if ($status_filter !== 'all') {
    $args['meta_query'] = array(
        array(
            'key' => 'statue',
            'value' => $status_filter,
            'compare' => '=',
        ),
    );
}

$filtered_matches = new WP_Query($args);
?>

<section class="match-list">
    <?php
    // Récupérer les matchs
    $matches = new WP_Query(array(
        'post_type' => 'match',
        'posts_per_page' => -1,
    ));

    if ($matches->have_posts()) :
        while ($matches->have_posts()) : $matches->the_post();
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
                                    <div class="card-date">
                                        <h3>Le <?php the_field('dateheure_du_match'); ?>H</h3>
                                    </div>
                                    <div class="match-equipe">
                                        <div class="teams-container">
                                            <div class="team-logo">
                                                <?php if ($image_equipe1) : ?>
                                                    <img src="<?php echo esc_url($image_equipe1['url']); ?>" 
                                                         alt="<?php echo esc_attr($nom_equipe1); ?>" />
                                                <?php endif; ?>
                                                <p class="typo-card"><?php echo $nom_equipe1; ?></>
                                            </div>
                                            
                                            <p class="vs">VS</p>
                                            
                                            <div class="team-logo">
                                                <?php if ($image_equipe2) : ?>
                                                    <img src="<?php echo esc_url($image_equipe2['url']); ?>" 
                                                         alt="<?php echo esc_attr($nom_equipe2); ?>" />
                                                <?php endif; ?>
                                                <p class="typo-card"><?php echo $nom_equipe2; ?></p>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
            <?php
        }
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Aucun match trouvé.</p>';
    endif;
    ?>
</section>

<?php get_footer(); ?>
