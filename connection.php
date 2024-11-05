<?php
/* Template Name: Connexion */
get_header();

if (!is_user_logged_in()) {
    ?>
    <h1 class="hero">Connexion</h1>
    <div class="custom-login-form">
        <form method="post" action="">
            <p>
                <label for="user_login">Nom d'utilisateur ou email</label>
                <input type="text" name="user_login" id="user_login" required />
            </p>
            <p>
                <label for="user_password">Mot de passe</label>
                <input type="password" name="user_password" id="user_password" required />
            </p>
            <p>
                <input type="submit" name="submit_login" value="Se connecter" />
            </p>
        </form>
    </div>
    <?php
} else {
    echo '<p>Vous êtes déjà connecté.</p>';
}

get_footer();
