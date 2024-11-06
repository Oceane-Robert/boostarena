<?php get_header(); ?>

<h1 class="hero">
    Matchs
</h1>
<?php
// Récupérer les valeurs possibles du champ ACF "statue"
$statues = get_field_object('statue')['choices'];
?>

<form action="" method="get">
    <select name="status" id="status">
        <option value="all">Tous les matchs</option>
        <?php foreach ($statues as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($_GET['status'], $key); ?>><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrer</button>
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

if ($filtered_matches->have_posts()) :
    while ($filtered_matches->have_posts()) : $filtered_matches->the_post();
        // Afficher les matchs filtrés
        the_title('<h2>', '</h2>');
        // Ajoutez ici le code pour afficher les autres informations du match
    endwhile;
    wp_reset_postdata();
else :
    echo '<p>Aucun match trouvé.</p>';
endif;
?>
<?php get_footer(); ?>