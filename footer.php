<footer class="bg-ink text-white/70 pt-14">
    <div class="site-container">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1fr] gap-10 pb-12 border-b border-white/[.08]">

            <!-- Despre -->
            <div>
                <div class="flex items-center gap-3 mb-[.85rem] text-white text-[1.05rem] font-bold">
                    <svg class="size-[42px] text-white/50 shrink-0" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                        <circle cx="30" cy="30" r="28" stroke="currentColor" stroke-width="2" opacity=".5"/>
                        <path d="M18 42V26l12-8 12 8v16" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M26 42v-8h8v8" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M14 42h32" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                </div>
                <p class="text-base leading-[1.65] m-0">
                    <?php esc_html_e( 'Administrație publică locală în slujba cetățenilor.', 'primarie' ); ?>
                </p>
            </div>

         
            <!-- Servicii -->
            <div>
                <h4 class="text-base font-bold uppercase tracking-[.1em] text-white m-0 mb-4">
                    <?php esc_html_e( 'Servicii', 'primarie' ); ?>
                </h4>
                <ul class="flex flex-col gap-2 list-none m-0 p-0">
                    <?php
                    $links = [
                        __( 'Informații publice', 'primarie' ),
                        __( 'Impozite și Taxe', 'primarie' ),
                        __( 'Urbanism', 'primarie' ),
                        __( 'Stare Civilă', 'primarie' ),
                        __( 'Asistență Socială', 'primarie' ),
                        __( 'Agricultură', 'primarie' ),
                    ];
                    foreach ( $links as $label ) :
                    ?>
                        <li>
                            <a href="#" class="text-base text-white/65 no-underline transition-all duration-[220ms] hover:text-accent hover:pl-1">
                                <?php echo esc_html( $label ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-base font-bold uppercase tracking-[.1em] text-white m-0 mb-4">
                    <?php esc_html_e( 'Contact', 'primarie' ); ?>
                </h4>
                <address class="not-italic mb-4">
                    <p class="text-base my-[.3rem] text-white/65"><?php esc_html_e( 'Strada Principală, nr. 1', 'primarie' ); ?></p>
                    <p class="text-base my-[.3rem] text-white/65">
                        <a href="tel:+40258000000" class="text-white/65 no-underline hover:text-accent transition-colors">+40 258 000 000</a>
                    </p>
                    <p class="text-base my-[.3rem] text-white/65">
                        <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"
                           class="text-white/65 no-underline hover:text-accent transition-colors">
                            <?php echo esc_html( get_option( 'admin_email' ) ); ?>
                        </a>
                    </p>
                </address>
                <div class="flex gap-2">
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                       class="flex items-center justify-center size-[34px] bg-white/[.08] text-white/70
                              transition-colors duration-[220ms] hover:bg-accent hover:text-white">
                        <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom bar -->
    <div class="py-[1.1rem] bg-black/20">
        <div class="site-container flex items-center justify-between gap-4 flex-wrap">
            <p class="text-base m-0 text-white/45">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?>
                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
                <?php esc_html_e( 'Toate drepturile rezervate.', 'primarie' ); ?>
            </p>
            <p class="text-base m-0">
                <a href="<?php echo esc_url( admin_url() ); ?>"
                   class="text-white/45 no-underline hover:text-accent transition-colors">
                    <?php esc_html_e( 'Administrare', 'primarie' ); ?>
                </a>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
