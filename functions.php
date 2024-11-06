<?php
// vignettes
add_theme_support( 'post-thumbnails' );
//ajouter une nouvelle zone de menu à mon thème
function register_my_menu(){
     register_nav_menus( array(
         'header-menu' => __( 'Menu De Tete'),
         'footer-menu'  => __( 'Menu De Pied'),
     ) );
 }
 add_action( 'init', 'register_my_menu', 0 );

function custom_registration_process() {
    if (isset($_POST['submit_registration'])) {
        $username = sanitize_user($_POST['user_login']);
        $email = sanitize_email($_POST['user_email']);
        $password = esc_attr($_POST['user_password']);
        $firstname = sanitize_text_field($_POST['user_firstname']);
        $lastname = sanitize_text_field($_POST['user_lastname']);
        
        $errors = new WP_Error();

        // Vérification des champs
        if (username_exists($username) || !validate_username($username)) {
            $errors->add('username_error', 'Ce nom d\'utilisateur est déjà pris ou invalide.');
        }

        if (!is_email($email) || email_exists($email)) {
            $errors->add('email_error', 'Cet email est déjà utilisé ou invalide.');
        }

        if (empty($password)) {
            $errors->add('password_error', 'Vous devez entrer un mot de passe.');
        }

        // Si aucune erreur, création de l'utilisateur
        if (empty($errors->errors)) {
            $user_id = wp_create_user($username, $password, $email);

            // Si la création a réussi, attribuer le rôle de contributeur
            if (!is_wp_error($user_id)) {
                $user = new WP_User($user_id);
                $user->set_role('contributor');

                // Connexion automatique après inscription
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url()); // Redirige vers la page d'accueil ou une autre page
                exit;
            }
        } else {
            foreach ($errors->get_error_messages() as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
        }
    }
}
add_action('template_redirect', 'custom_registration_process');

function custom_login_process() {
    if (isset($_POST['submit_login'])) {
        $creds = array();
        $creds['user_login'] = sanitize_user($_POST['user_login']);
        $creds['user_password'] = esc_attr($_POST['user_password']);
        $creds['remember'] = true; // Option pour "se souvenir de moi"

        $user = wp_signon($creds, false); // Connexion

        if (is_wp_error($user)) {
            // Si une erreur survient, afficher un message d'erreur
            echo '<p class="error">Nom d\'utilisateur ou mot de passe incorrect.</p>';
        } else {
            // Redirection après la connexion réussie
            wp_redirect(home_url()); // Rediriger vers la page d'accueil ou une autre page
            exit;
        }
    }
}
add_action('template_redirect', 'custom_login_process');

function display_login_logout_link() {
    if (is_user_logged_in()) {
        // Si l'utilisateur est connecté, afficher un lien de déconnexion
        $logout_url = wp_logout_url(home_url()); // Redirige vers la page d'accueil après déconnexion
        echo '<a href="' . esc_url($logout_url) . '">Déconnexion</a>';
    } else {
        // Sinon, afficher le lien de connexion
        $registration_url = home_url('/inscription');
        $login_url = home_url('/connexion'); 

        // Afficher les liens de Connexion, Inscription et Mot de passe oublié
        echo '<a href="' . esc_url($login_url) . '">Connexion</a>  ';
        echo '<a href="' . esc_url($registration_url) . '">Inscription</a>  ';
    }
}
function projet_mmi_customizer_register($wp_customize) {
    // Ajouter une section "Image d'en-tête"
    $wp_customize->add_section('header_image_section', array(
        'title' => __('Image d\'en-tête', 'projet-mmi'),
        'description' => __('Choisissez une image pour l\'en-tête de votre site', 'projet-mmi'),
        'priority' => 30,
    ));

    // Ajouter un réglage pour l'image d'en-tête
    $wp_customize->add_setting('header_image_setting', array(
        'default' => get_template_directory_uri() . '/assets/images/header.',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Ajouter un contrôleur pour l'image d'en-tête
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_image_control', array(
        'label' => __('Image d\'en-tête', 'projet-mmi'),
        'section' => 'header_image_section',
        'settings' => 'header_image_setting',
        'button_labels' => array(
            'select' => __('Sélectionner une image', 'projet-mmi'),
            'remove' => __('Supprimer l\'image', 'projet-mmi'),
            'change' => __('Changer l\'image', 'projet-mmi'),
        ),
    )));
}
add_action('customize_register', 'projet_mmi_customizer_register');


function projet_mmi_enqueue_archive_assets() {
    if (is_post_type_archive('match')) {
        wp_enqueue_script('projet-carousel', get_template_directory_uri() . '/js/carousel.js', array(), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'projet_mmi_enqueue_archive_assets');

function theme_enqueue_scripts() {
    wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.5.2/dist/cdn.min.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');




function update_team_info() {
    if (!isset($_POST['team_update_nonce']) || 
        !wp_verify_nonce($_POST['team_update_nonce'], 'update_team_info')) {
        wp_die('Action non autorisée');
    }

    $team_id = $_POST['team_id'];
    
    // Vérifier que l'utilisateur connecté est bien le capitaine
    $capitaine = get_field('capitaine', $team_id);
    if ($capitaine['ID'] != get_current_user_id()) {
        wp_die('Vous n\'êtes pas autorisé à modifier cette équipe');
    }

    // Mise à jour des coéquipiers
    update_field('coequipier_1', $_POST['coequipier_1'], $team_id);
    update_field('coequipier_2', $_POST['coequipier_2'], $team_id);

    // Gestion de l'upload de l'image
    if (!empty($_FILES['team_image'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('team_image', $team_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($team_id, $attachment_id);
        }
    }

    wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
    exit;
}
add_action('admin_post_update_team_info', 'update_team_info');

function get_user_match_stats($team_id) {
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
        $statut = get_field('statue', $match->ID);
        $equipe_1 = get_field('equipe_1', $match->ID);
        $decision = get_field('decision', $match->ID);

        switch ($statue) {
            case 'a_venir':
                $stats['a_venir']++;
                break;
            case 'en_cours':
                $stats['en_cours']++;
                break;
            case 'termines':
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