<?php get_header(); ?>

<section class="hero">
    <h1>
        equipe
    </h1>
    <div class="hero-rectangle"></div> <!-- Ajouter ce div pour le rectangle -->
</section>
<a class="button-creer" href="http://localhost:8888/boostarena.oceanerobert.fr/wp-admin/post-new.php?post_type=equipe">Créer une équipe</a>
<?php
// Récupérer les valeurs possibles du champ ACF "rang_global"
$rangs = get_field_object('rang_global')['choices'];
?>

<form action="" method="get" class="button-select">
    <select name="rang" id="rang" class="select">
        <option value="all">Tous les rangs</option>
        <?php foreach ($rangs as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($_GET['rang'], $key); ?>><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="filtre">Filtrer</button>
</form>

<section class="card-equipe">
<?php
// Filtrer les équipes en fonction de la sélection de l'utilisateur
$rang_filter = isset($_GET['rang']) ? $_GET['rang'] : 'all';

$args = array(
    'post_type' => 'equipe',
    'posts_per_page' => -1,
);

if ($rang_filter !== 'all') {
    $args['meta_query'] = array(
        array(
            'key' => 'rang_global',
            'value' => $rang_filter,
            'compare' => '=',
        ),
    );
};

$filtered_teams = new WP_Query($args);
?>

<section class="card-equipe">
    <div class="team-grid">
        <?php
        $filtered_teams = new WP_Query($args);

        if ($filtered_teams->have_posts()) :
            while ($filtered_teams->have_posts()) : $filtered_teams->the_post();
                echo '<div class="team-card">'; // Ajouter une div avec une classe

                echo '<div class="team-card-content">'; // Ajouter un conteneur supplémentaire

                // Récupérer l'image de l'équipe
                $image = get_field('image_de_lequipe');
                if ($image) {
                    echo '<img class="team-image" src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '" />';
                }

                the_title('<h2 class="team-title">', '</h2>');

                echo '</div>'; // Fermer le conteneur supplémentaire
                echo '</div>'; // Fermer la div
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Aucune équipe trouvée.</p>';
        endif;
        ?>
    </div>
</section>
<?php get_footer(); ?>
<?php get_footer(); ?>