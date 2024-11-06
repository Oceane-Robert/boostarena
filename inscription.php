<?php
/* Template Name: Inscription */
get_header();

if (!is_user_logged_in()) {
    ?>
    <h1 class="hero">Inscription</h1>
    <div class="custom-registration-form">
        <form method="post" action="">
            <p>
                <label for="user_firstname">Prénom</label>
                <input type="text" name="user_firstname" id="user_firstname" required />
            </p>
            <p>
                <label for="user_lastname">Nom</label>
                <input type="text" name="user_lastname" id="user_lastname" required />
            </p>
            <p>
                <label for="user_rang">Rang</label>
                <select name="user_rang" id="user_rang" required>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                </select>
            </p>
            <p>
                <label for="user_email">Email</label>
                <input type="email" name="user_email" id="user_email" required />
            </p>
            <p>
                <label for="user_password">Mot de passe</label>
                <input type="password" name="user_password" id="user_password" required />
            </p>
            <p>
                <input type="checkbox" name="legal_info" id="legal_info" required />
                <label for="legal_info">J'accepte les <a class="check" href="/informations-legales">informations légales</a></label>
            </p>
            <p>
                <input type="submit" name="submit_registration" value="S'inscrire" />
            </p>
            </form>
    </div>
    <?php
} else {
    echo '<p>Vous êtes déjà connecté.</p>';
}

get_footer();

