<?php
/* Template Name: Contact */

get_header();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_text_field($_POST["name"]);
    $email = sanitize_email($_POST["email"]);
    $message = sanitize_textarea_field($_POST["message"]);

    $to = get_option('admin_email');
    $subject = "Nouveau message de contact de " . $name;
    $headers = "From: " . $email;

    wp_mail($to, $subject, $message, $headers);

    echo '<p>Merci pour votre message. Nous vous répondrons dès que possible.</p>';
} else {
    ?>
    <h1 class="hero">Contact</h1>
    <div class="custom-login-form">
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
        <p class="champ-login">
            <label for="name"><h4>Nom</h4></label>
            <input type="text" name="name" id="name" class="input" required value="Robert" />
        </p>
        <p class="champ-login">
            <label for="name"><h4>Prénom</h4></label>
            <input type="text" name="name" id="name" class="input" required value="Paul" />
        </p>
        <p class="champ-login">
            <label for="email"><h4>Email</h4></label>
            <input type="email" name="email" id="email" required value="paul@exemple.com" />
        </p>
        <p class="champ-login">
            <label for="message"><h4>Message</h4></label>
            <textarea name="message" id="message" required class="textarea">Écrire...</textarea>
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </form>
    </div>
    <?php
}

get_footer();