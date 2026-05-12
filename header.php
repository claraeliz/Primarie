<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="sticky top-0 z-[200] bg-white border-b border-stroke transition-shadow" id="site-header">
    <div class="site-container">

        <!-- ── Rând 1: Logo | Search | Contact | Burger ── -->
        <div class="flex items-center justify-between gap-6 py-[.65rem] border-b border-stroke">

            <!-- Logo -->
            <div class="flex items-center shrink-0">
                <?php if ( has_custom_logo() ) : ?>
                    <a class="flex items-center shrink-0 site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, [ 'class' => 'custom-logo' ] ); ?>
                        <span class="logo-title">Comuna<br>Nimigea<br>Județul Bistrița-Năsăud</span>
                    </a>
                    
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 no-underline" rel="home">
                        <div class="size-12 text-primary shrink-0">
                            <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="2"/>
                                <path d="M18 42V26l12-8 12 8v16" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M26 42v-8h8v8" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M14 42h32" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="flex flex-col leading-[1.15]">
                            <span class="text-base font-semibold uppercase tracking-[.12em] text-muted">Primăria</span>
                            <span class="text-[1.1rem] font-extrabold text-primary tracking-[-0.01em]"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Search -->
            <form class="flex-1 max-w-[520px] flex items-center border border-stroke bg-[#f8f9fa] overflow-hidden
                         transition-all duration-[220ms]
                         focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(0,73,144,.08)] focus-within:bg-white"
                  role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <button type="submit"
                        class="flex items-center justify-center bg-transparent border-none px-3 text-muted cursor-pointer shrink-0
                               transition-colors duration-[220ms] hover:text-primary"
                        aria-label="<?php esc_attr_e( 'Caută', 'primarie' ); ?>">
                    <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>
                <input type="search" name="s"
                       class="flex-1 border-none bg-transparent py-[.55rem] pr-3 text-base text-ink outline-none min-w-0 placeholder:text-muted"
                       placeholder="<?php esc_attr_e( 'Caută ce ai nevoie', 'primarie' ); ?>"
                       value="<?php echo esc_attr( get_search_query() ); ?>">
            </form>

            <!-- Contact -->
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
               class="inline-flex items-center px-[1.35rem] py-[.55rem] bg-primary text-white text-base font-medium
                      whitespace-nowrap shrink-0 no-underline
                      transition-colors duration-[220ms] hover:bg-accent">
                <?php esc_html_e( 'Contact', 'primarie' ); ?>
            </a>

            <!-- Burger (mobil) -->
            <button class="flex md:hidden flex-col gap-[5px] bg-transparent border-none cursor-pointer p-[.4rem] ml-auto"
                    id="nav-toggle" aria-controls="site-nav" aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Deschide meniu', 'primarie' ); ?>">
                <span class="block w-[22px] h-[2px] bg-primary transition-transform"></span>
                <span class="block w-[22px] h-[2px] bg-primary transition-transform"></span>
                <span class="block w-[22px] h-[2px] bg-primary transition-transform"></span>
            </button>

        </div>

        <!-- ── Rând 2: Navigare ── -->
        <nav class="hidden md:block w-full" id="site-nav"
             aria-label="<?php esc_attr_e( 'Navigare principală', 'primarie' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'site-menu flex items-center justify-between w-full list-none m-0 p-0',
                'fallback_cb'    => function () {
                    echo '<ul class="site-menu flex items-center justify-between w-full list-none m-0 p-0">'
                       . '<li><a href="' . esc_url( home_url( '/' ) ) . '">Acasă</a></li></ul>';
                },
            ] );
            ?>
        </nav>

    </div>
</header>

<script>
( function () {
    var toggle = document.getElementById( 'nav-toggle' );
    var nav    = document.getElementById( 'site-nav' );
    var header = document.getElementById( 'site-header' );

    if ( toggle && nav ) {
        toggle.addEventListener( 'click', function () {
            var isOpen = nav.classList.contains( 'nav-open' );
            if ( isOpen ) {
                nav.classList.remove( 'nav-open' );
                nav.classList.add( 'hidden' );
                this.setAttribute( 'aria-expanded', 'false' );
            } else {
                nav.classList.remove( 'hidden' );
                nav.classList.add( 'nav-open' );
                this.setAttribute( 'aria-expanded', 'true' );
            }
        } );
    }

    window.addEventListener( 'scroll', function () {
        if ( header ) header.classList.toggle( 'header--scrolled', window.scrollY > 40 );
    }, { passive: true } );
} )();
</script>
