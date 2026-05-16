<footer class="bg-white text-gray-600">

    <!-- Banner slider -->
    <div class="banner-slider border-b border-gray-200" id="bannerSlider" aria-label="Bannere parteneri">
        <button class="banner-slider__btn banner-slider__prev" aria-label="<?php esc_attr_e( 'Anterior', 'primarie' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        <div class="banner-slider__viewport">
            <div class="banner-slider__track">
                <?php for ( $i = 1; $i <= 10; $i++ ) : ?>
                <div class="banner-slider__slide">
                    <div class="banner-slider__placeholder"><?php echo esc_html( "Banner $i" ); ?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <button class="banner-slider__btn banner-slider__next" aria-label="<?php esc_attr_e( 'Următor', 'primarie' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    </div>

    <!-- Main footer -->
    <div class="py-10 border-t border-gray-200">
        <div class="site-container flex flex-col items-center gap-6">

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <?php if ( has_custom_logo() ) : ?>
                    <a class="flex items-center shrink-0 site-logo footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, [ 'class' => 'custom-logo' ] ); ?>
                        <span class="logo-title">Comuna<br>Nimigea<br>Județul Bistrița-Năsăud</span>
                    </a>
                <?php else : ?>
                    <svg class="size-10 text-ink/40 shrink-0" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                        <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="2" opacity=".5"/>
                        <path d="M18 42V26l12-8 12 8v16" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M26 42v-8h8v8" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M14 42h32" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <div class="leading-tight">
                        <p class="text-[.65rem] font-semibold uppercase tracking-widest text-gray-400 m-0"><?php esc_html_e( 'Comuna', 'primarie' ); ?></p>
                        <p class="text-xl font-bold text-ink m-0"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer nav -->
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => 'nav',
                'container_attr' => [ 'aria-label' => __( 'Footer navigation', 'primarie' ) ],
                'menu_class'     => 'flex flex-wrap justify-center items-center list-none m-0 p-0
                                     [&>li+li]:before:content-[\'|\'] [&>li+li]:before:px-3 [&>li+li]:before:text-gray-300 [&>li+li]:before:select-none',
                'link_class'     => 'text-sm text-gray-500 no-underline hover:text-accent transition-colors',
                'fallback_cb'    => '__return_false',
            ] );
            ?>

        </div>
    </div>

    <!-- Bottom bar -->
    <div class="py-4 border-t border-gray-200">
        <div class="site-container flex items-center justify-between gap-4 flex-wrap">
            <p class="text-sm m-0 text-gray-400">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?>
                <?php
                printf(
                    /* translators: %s: site name */
                    esc_html__( 'Website primăria %s.', 'primarie' ),
                    esc_html( get_bloginfo( 'name' ) )
                );
                ?>
                <?php esc_html_e( 'Toate drepturile rezervate.', 'primarie' ); ?>
            </p>
            <nav class="flex flex-wrap gap-x-6 gap-y-1" aria-label="<?php esc_attr_e( 'Quick links', 'primarie' ); ?>">
                <a href="#" class="text-sm text-gray-500 no-underline hover:text-accent transition-colors"><?php esc_html_e( 'Date de contact', 'primarie' ); ?></a>
                <a href="#" class="text-sm text-gray-500 no-underline hover:text-accent transition-colors"><?php esc_html_e( 'Evenimente Recente', 'primarie' ); ?></a>
                <a href="#" class="text-sm text-gray-500 no-underline hover:text-accent transition-colors"><?php esc_html_e( 'Anunțuri Publice', 'primarie' ); ?></a>
            </nav>
        </div>
    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
