<?php
/* Template Name: Connexion */
get_header();

if (!is_user_logged_in()) {
    ?>
    <h1 class="hero">Connexion</h1>
    <div class="custom-login-form">
        <form method="post" action="">
            <p class="champ-login">
                <label for="user_login"><h4>Pseudonyme</h4></label>
                <input type="text" name="user_login" id="user_login" value="paul" required />
            </p>
            <p class="champ-login">
                <label for="user_email"><h4>Email</h4></label>
                <input type="email" name="user_email" id="user_email" value="paul@example.com" required />
            </p>
            <p class="champ-login">
                <label for="user_password"><h4>Mot de passe</h4></label>
                <input type="password" name="user_password" id="user_password" value="password" required />
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
?>
