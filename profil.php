<?php
/**
 * Template Name: Profil
 */

get_header();

// Vérifier si l'utilisateur est connecté
if (!is_user_logged_in()) {
    ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p>Veuillez vous connecter pour accéder à cette page.</p>
        </div>
    </div>
    <?php
    get_footer();
    return;
}

// Récupérer l'équipe de l'utilisateur
$user_id = get_current_user_id();
$args = array(
    'post_type' => 'equipe',
    'posts_per_page' => 1,
    'meta_query' => array(
    'relation' => 'OR',
        array(
            'key' => 'capitaine_de_lequipe',
            'value' => $user_id,
            'compare' => '='
        ),
        array(
            'key' => 'equipier_1',
            'value' => $user_id,
            'compare' => '='
        ),
        array(
            'key' => 'equipier_2',
            'value' => $user_id,
            'compare' => '='
        )
    )
);

$team_query = new WP_Query($args);

// Récupérer les statistiques
function fetch_user_match_stats($team_id) {
    $matches = get_posts(array(
        'post_type' => 'match',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'equipe_1',
                'value' => $team_id
            ),
            array(
                'key' => 'equipe_2',
                'value' => $team_id
            )
        )
    ));

    $stats = array(
        'total' => 0,
        'gagne' => 0,
        'perdu' => 0,
    );

    foreach ($matches as $match) {
        $statue = get_field('statue', $match->ID); // Correction de 'statue' à 'statut'
        $equipe_1 = get_field('equipe_1', $match->ID);
        $decision = get_field('decision', $match->ID);

        // Instructions de débogage
        error_log("Match ID: " . $match->ID);
        error_log("Statue: " . $statue);
        error_log("Equipe 1: " . $equipe_1);
        error_log("Decision: " . $decision);

        switch ($statue) { // Correction de 'statue' à 'statut'
            case 'a_venir':
                $stats['a_venir']++;
                break;
            case 'en_cours':
                $stats['en_cours']++;
                break;
            case 'termine': // Correction de 'termines' à 'termine'
                $stats['total']++;
                if (($equipe_1 == $team_id && $decision == 'gagne') || 
                    ($equipe_1 != $team_id && $decision == 'perdu')) {
                    $stats['gagne']++;
                } else {
                    $stats['perdu']++;
                }
                break;
        }
    }

    return $stats;
}

?>

<div class="container mx-auto px-4 py-8">
    <?php if ($team_query->have_posts()) : while ($team_query->have_posts()) : $team_query->the_post(); 
        $team_id = get_the_ID();
        $stats = fetch_user_match_stats($team_id); // Correction de 'get_user_match_stats' à 'fetch_user_match_stats'
        $is_captain = get_field('capitaine') == $user_id;
    ?>
        
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- En-tête de l'équipe -->
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex items-center gap-4">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="w-24 h-24 rounded-full overflow-hidden">
                            <?php the_post_thumbnail('thumbnail', ['class' => 'w-full h-full object-cover']); ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h1 class="text-2xl font-bold"><?php the_title(); ?></h1>
                        <?php if ($is_captain) : ?>
                            <span class="inline-block bg-blue-500 text-white text-sm px-2 py-1 rounded">Capitaine</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <h2 class="font-bold text-lg mb-2">Matchs terminés</h2>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center">
                            <span class="block text-2xl font-bold"><?php echo $stats['total']; ?></span>
                            <span class="text-sm text-gray-600">Total</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-2xl font-bold text-green-500"><?php echo $stats['gagne']; ?></span>
                            <span class="text-sm text-gray-600">Gagnés</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-2xl font-bold text-red-500"><?php echo $stats['perdu']; ?></span>
                            <span class="text-sm text-gray-600">Perdus</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h2 class="font-bold text-lg mb-2">Matchs à venir</h2>
                    <span class="block text-2xl font-bold"><?php echo $stats['a_venir']; ?></span>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h2 class="font-bold text-lg mb-2">Matchs en cours</h2>
                    <span class="block text-2xl font-bold"><?php echo $stats['en_cours']; ?></span>
                </div>
            </div>

            <!-- Informations de l'équipe -->
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Membres de l'équipe</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php
                    $capitaine = get_field('capitaine');
                    $coequipier1 = get_field('coequipier_1');
                    $coequipier2 = get_field('coequipier_2');
                    ?>
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="font-bold">Capitaine</h3>
                        <p><?php echo $capitaine['display_name']; ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="font-bold">Coéquipier 1</h3>
                        <p><?php echo $coequipier1 ? $coequipier1['display_name'] : 'Non assigné'; ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="font-bold">Coéquipier 2</h3>
                        <p><?php echo $coequipier2 ? $coequipier2['display_name'] : 'Non assigné'; ?></p>
                    </div>
                </div>
            </div>

            <?php if ($is_captain) : ?>
                <!-- Formulaire de modification (uniquement pour le capitaine) -->
                <div class="p-6 border-t">
                    <h2 class="text-xl font-bold mb-4">Modifier l'équipe</h2>
                    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="update_team_info">
                        <input type="hidden" name="team_id" value="<?php echo get_the_ID(); ?>">
                        <?php wp_nonce_field('update_team_info', 'team_update_nonce'); ?>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Coéquipier 1</label>
                            <select name="coequipier_1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Sélectionner un joueur</option>
                                <?php
                                $users = get_users();
                                foreach ($users as $user) {
                                    $selected = $coequipier1 && $coequipier1['ID'] == $user->ID ? 'selected' : '';
                                    echo '<option value="' . $user->ID . '" ' . $selected . '>' . $user->display_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Coéquipier 2</label>
                            <select name="coequipier_2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Sélectionner un joueur</option>
                                <?php
                                foreach ($users as $user) {
                                    $selected = $coequipier2 && $coequipier2['ID'] == $user->ID ? 'selected' : '';
                                    echo '<option value="' . $user->ID . '" ' . $selected . '>' . $user->display_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image de l'équipe</label>
                            <input type="file" name="team_image" class="mt-1 block w-full">
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour l'équipe
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>

    <?php endwhile; else: ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p>Vous n'êtes pas encore membre d'une équipe.</p>
        </div>
    <?php endif; wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>