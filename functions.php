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






