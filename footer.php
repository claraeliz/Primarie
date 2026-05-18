<footer class="bg-white text-gray-600">

    <!-- Banner slider -->
    <div class="banner-slider border-b border-gray-200" id="bannerSlider" aria-label="Bannere parteneri">
        <button class="banner-slider__btn banner-slider__prev" aria-label="<?php esc_attr_e( 'Anterior', 'primarie' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        <div class="banner-slider__viewport">
            <div class="banner-slider__track">
                <?php
                $banners = get_option( 'primarie_footer_carousel', [] );
                if ( ! is_array( $banners ) ) $banners = [];

                if ( empty( $banners ) ) :
                    for ( $n = 1; $n <= 10; $n++ ) :
                ?>
                <div class="banner-slider__slide">
                    <a href="#" class="banner-slider__link" aria-label="<?php echo esc_attr( "Banner $n" ); ?>">
                        <img src="<?php echo esc_url( "https://placehold.co/200x100/e2e8f0/58595B?text=Banner+$n" ); ?>"
                             alt="<?php echo esc_attr( "Banner $n" ); ?>"
                             width="200" height="100"
                             class="banner-slider__img">
                    </a>
                </div>
                <?php
                    endfor;
                else :
                    foreach ( $banners as $banner ) :
                        $img_url = wp_get_attachment_image_url( $banner['img_id'], 'full' );
                        $img_alt = get_post_meta( $banner['img_id'], '_wp_attachment_image_alt', true );
                        $href    = $banner['link'] ?: '#';
                        if ( ! $img_url ) continue;
                ?>
                <div class="banner-slider__slide">
                    <a href="<?php echo esc_url( $href ); ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="banner-slider__link">
                        <img src="<?php echo esc_url( $img_url ); ?>"
                             alt="<?php echo esc_attr( $img_alt ); ?>"
                             class="banner-slider__img">
                    </a>
                </div>
                <?php
                    endforeach;
                endif;
                ?>
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
