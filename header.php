<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>" type="text/css">
    <?php wp_head(); ?>
</head>
<body>
<header class="header">
    <div x-data="{ open: false }" class="menu-container relative">
        <!-- Bouton Burger pour Mobile -->
        <button @click="open = !open" class="burger-btn lg:hidden">
            ☰
        </button>

        <!-- Menu pour Mobile et Desktop -->
        <nav x-show="open || window.innerWidth >= 1024" @click.outside="open = false" 
             class="menu-items absolute lg:relative top-12 lg:top-auto left-0 lg:left-auto w-full lg:w-auto bg-white lg:bg-transparent shadow-md lg:shadow-none"
             :class="{'hidden lg:flex': !open && window.innerWidth < 1024}">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'menu_class' => 'flex flex-col lg:flex-row items-start lg:items-center mobile-menu',
            ]);
            ?>
        </nav>
    </div>
</header>
<?php wp_footer(); ?>
</body>
</html>