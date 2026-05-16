<?php
/*
 * Template Name: Homepage
 * Template Post Type: page
 *
 * Setează această pagină ca "Pagină de start statică" în
 * Setări → Citire pentru a o folosi ca homepage.
 */

defined( 'ABSPATH' ) || exit;

// ─── Services data ────────────────────────────────────────────────────────────
$services = [
    [
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>',
        'title' => __( 'Centrul de Informații', 'primarie' ),
        'desc'  => __( 'Informații pentru cetățeni conform Legii 544/2001', 'primarie' ),
        'link'  => '#',
    ],
    [
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>',
        'title' => __( 'Impozite și Taxe', 'primarie' ),
        'desc'  => __( 'Plăți online, declarații fiscale și certificate fiscale', 'primarie' ),
        'link'  => '#',
    ],
    [
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>',
        'title' => __( 'Urbanism și Autorizații', 'primarie' ),
        'desc'  => __( 'Autorizații de construire, planuri urbanistice, avize', 'primarie' ),
        'link'  => '#',
    ],
    [
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
        'title' => __( 'Registru Agricol', 'primarie' ),
        'desc'  => __( 'Înregistrare terenuri, adeverințe agricole, arendă', 'primarie' ),
        'link'  => '#',
    ],
];

// ─── Quick links ──────────────────────────────────────────────────────────────
$quick_links = [
    [ 'label' => __( 'Cerere informații publice', 'primarie' ),        'link' => '#' ],
    [ 'label' => __( 'Certificat fiscal', 'primarie' ),                'link' => '#' ],
    [ 'label' => __( 'Autorizație de construire', 'primarie' ),        'link' => '#' ],
    [ 'label' => __( 'Înregistrare în Registrul Agricol', 'primarie' ), 'link' => '#' ],
    [ 'label' => __( 'Stare civilă – acte necesare', 'primarie' ),     'link' => '#' ],
    [ 'label' => __( 'Ajutor social', 'primarie' ),                    'link' => '#' ],
    [ 'label' => __( 'Alocații de stat', 'primarie' ),                 'link' => '#' ],
    [ 'label' => __( 'Petiții și sesizări online', 'primarie' ),       'link' => '#' ],
    [ 'label' => __( 'Declarație impozit clădiri', 'primarie' ),       'link' => '#' ],
    [ 'label' => __( 'Program audiențe', 'primarie' ),                 'link' => '#' ],
];

// ─── Announcements (document_public CPT, document_category: anunturi) ───────
$announcements = new WP_Query( [
    'post_type'      => 'document_public',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'document_category',
            'field'    => 'slug',
            'terms'    => 'anunturi',
        ],
    ],
] );

// ─── Hero background ──────────────────────────────────────────────────────────
$hero_style = '';
$hero_img   = get_field( 'hero_image' );
if ( $hero_img ) {
    $hero_style = ' style="background-image: url(' . esc_url( $hero_img['url'] ) . ');"';
}

get_header();
?>

<!-- ═══════════════════════════════ HERO ════════════════════════════════════ -->
<section class="relative bg-cover bg-center pt-[140px] pb-[110px] overflow-hidden bg-gradient-to-br from-primary to-accent"<?php echo $hero_style; // phpcs:ignore ?> aria-label="Banner principal">
    <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(7,9,10,.45)_0%,rgba(0,73,144,.4)_100%)]"></div>
    <div class="site-container relative z-10">
        <div class="max-w-[620px]">
            <p class="text-base font-semibold uppercase tracking-[.12em] text-white/70 m-0 mb-3">
                <?php esc_html_e( 'Portal oficial al', 'primarie' ); ?>
            </p>
            <h1 class="text-[2.6rem] md:text-[3.5rem] font-extrabold text-white leading-[1.1] m-0 mb-4 tracking-[-0.02em]">
                <?php
                $hero_title = get_field( 'hero_title' );
                if ( ! empty( $hero_title ) ) {
                    echo esc_html( $hero_title );
                }
                ?>
            </h1>
            <p class="text-[1.05rem] text-white/75 m-0 mb-8">
                <?php esc_html_e( 'Portal online – rapid, simplu, eficient', 'primarie' ); ?>
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="#servicii"
                   class="inline-flex items-center px-6 py-3 bg-white text-primary text-base font-bold no-underline
                          transition-all duration-[220ms] hover:bg-accent hover:text-white hover:-translate-y-[2px] hover:shadow-[0_6px_24px_rgba(0,0,0,.25)]">
                    <?php esc_html_e( 'Servicii cetățeni', 'primarie' ); ?>
                </a>
                <a href="#noutati"
                   class="inline-flex items-center px-6 py-3 bg-transparent text-white text-base font-bold no-underline
                          border border-white/40 transition-all duration-[220ms] hover:bg-white/10 hover:border-white/70">
                    <?php esc_html_e( 'Noutăți', 'primarie' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ SERVICES ════════════════════════════════ -->
<section class="py-16 bg-white" id="servicii" aria-labelledby="services-heading">
    <div class="site-container">
        <div class="text-center mb-10">
            <h2 id="services-heading" class="text-[1.8rem] font-extrabold text-ink m-0 mb-2 tracking-[-0.02em]">
                <?php esc_html_e( 'Servicii pentru Cetățeni', 'primarie' ); ?>
            </h2>
            <p class="text-muted text-[1rem] m-0">
                <?php esc_html_e( 'Accesează rapid cele mai importante servicii ale primăriei', 'primarie' ); ?>
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php foreach ( $services as $svc ) : ?>
                <a href="<?php echo esc_url( $svc['link'] ); ?>"
                   class="relative flex flex-col gap-3 p-7 bg-white border border-stroke no-underline overflow-hidden group
                          transition-all duration-[220ms] hover:-translate-y-1 hover:shadow-[0_8px_40px_rgba(0,0,0,.12)] hover:border-primary/20">
                    <span class="absolute inset-x-0 top-0 h-[3px] bg-primary scale-x-0 group-hover:scale-x-100 transition-transform duration-[220ms] origin-left"></span>
                    <div class="size-11 text-primary [&_svg]:size-full" aria-hidden="true">
                        <?php echo $svc['icon']; // phpcs:ignore — SVG, not user input ?>
                    </div>
                    <h3 class="text-base font-bold text-ink m-0 leading-[1.3]">
                        <?php echo esc_html( $svc['title'] ); ?>
                    </h3>
                    <p class="text-base text-muted m-0 leading-[1.6] flex-1">
                        <?php echo esc_html( $svc['desc'] ); ?>
                    </p>
                    <span class="text-primary text-base font-semibold mt-1 transition-colors group-hover:text-accent" aria-hidden="true">
                        <?php esc_html_e( 'Accesează', 'primarie' ); ?> →
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════ DESPRE COMUNĂ ═══════════════════════════ -->
<?php
$despre_imagine   = get_field( 'despre_imagine' );
$despre_titlu     = get_field( 'despre_titlu' )     ?: get_bloginfo( 'name' );
$despre_subtitlu  = get_field( 'despre_subtitlu' )  ?: '';
$despre_citat     = get_field( 'despre_citat' )     ?: '';
$despre_descriere = get_field( 'despre_descriere' ) ?: '';
$despre_galerie   = get_field( 'despre_galerie' )   ?: [];
$despre_link      = get_field( 'despre_link' )      ?: '';
?>
<section class="py-20 bg-white" id="despre" aria-labelledby="despre-heading">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-[5fr_7fr] gap-14 items-start">

            <!-- LEFT: imagine cu titlu/subtitlu absolut + citat -->
            <div class="flex flex-col items-center text-center lg:items-start lg:text-left">

                <div class="relative w-full mx-auto lg:mx-0 mb-6">
                    <?php if ( $despre_imagine ) : ?>
                        <img src="<?php echo esc_url( $despre_imagine['url'] ); ?>"
                             alt="<?php echo esc_attr( $despre_imagine['alt'] ?: $despre_titlu ); ?>"
                             class="w-full object-cover grayscale contrast-110 block">
                    <?php else : ?>
                        <div class="w-full aspect-[3/4] bg-faint flex items-center justify-center">
                            <svg class="size-20 text-muted/30" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 21h18M6.75 3h10.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V3.75A.75.75 0 016.75 3z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <!-- gradient + text absolut peste imagine -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent pt-16 pb-5 px-5 text-left">
                        <h2 id="despre-heading" class="text-[1.55rem] font-extrabold text-white m-0 mb-[3px] tracking-[-0.01em]">
                            <?php echo esc_html( $despre_titlu ); ?>
                        </h2>
                        <?php $subtitlu_display = $despre_subtitlu ?: __( 'Comună în județul Bistrița-Năsăud', 'primarie' ); ?>
                        <p class="text-[.7rem] font-bold uppercase tracking-[.18em] text-white/65 m-0">
                            <?php echo esc_html( $subtitlu_display ); ?>
                        </p>
                    </div>
                </div>

               

            </div>

            <!-- RIGHT: descriere + galerie + link -->
            <div class="flex flex-col gap-7">

                <!-- WORD ROTATOR -->
                <?php
                $rotator_words = apply_filters( 'despre_rotator_words', [
                    __( 'lorem ipsum 1', 'primarie' ),
                    __( 'lorem ipsum 2', 'primarie' ),
                    __( 'lorem ipsum 3', 'primarie' ),
                ] );
                ?>
                <div class="flex items-center gap-3">
                    
                    <span class="w-px h-12 bg-stroke shrink-0"></span>
                    <span id="despre-rotator-word"
                          class="text-[2rem] font-extrabold text-primary transition-opacity duration-500 leading-none tracking-[-0.02em]"
                          style="min-width:10ch;">
                        <?php echo esc_html( $rotator_words[0] ); ?>
                    </span>
                </div>
                <script>
                (function () {
                    var words = <?php echo wp_json_encode( $rotator_words ); ?>;
                    var el    = document.getElementById( 'despre-rotator-word' );
                    var i     = 0;
                    if ( ! el || words.length < 2 ) return;
                    setInterval( function () {
                        el.style.opacity = '0';
                        setTimeout( function () {
                            i = ( i + 1 ) % words.length;
                            el.textContent  = words[ i ];
                            el.style.opacity = '1';
                        }, 500 );
                    }, 2500 );
                })();
                </script>

                <?php if ( $despre_descriere ) : ?>
                    <div class="text-[.97rem] text-muted leading-[1.85] [&_p]:m-0 [&_p+p]:mt-4">
                        <?php echo wp_kses_post( $despre_descriere ); ?>
                    </div>
                <?php else : ?>
                    <div class="text-[1.08rem] text-muted leading-[1.85] flex flex-col gap-4">
                        <p><?php esc_html_e( 'Comuna noastră este o așezare cu o istorie bogată, situată în inima județului, înconjurată de dealuri line și păduri seculare. Comunitatea locală se mândrește cu tradițiile sale, cu oamenii harnici și cu un patrimoniu cultural de o valoare inestimabilă, transmis cu grijă de-a lungul generațiilor.', 'primarie' ); ?></p>
                        <p><?php esc_html_e( 'De-a lungul timpului, localitatea a cunoscut momente importante de dezvoltare, de la construirea primelor instituții publice până la proiectele moderne de infrastructură care au transformat viața cetățenilor. Fiecare etapă a adus cu sine noi oportunități și a consolidat spiritul comunitar al locuitorilor.', 'primarie' ); ?></p>
                        <p><?php esc_html_e( 'Astăzi, primăria lucrează neobosit pentru a îmbunătăți calitatea vieții prin investiții în educație, sănătate, cultură și infrastructură. Suntem mândri de ceea ce am realizat împreună și privim cu optimism spre un viitor prosper pentru toți cetățenii comunei.', 'primarie' ); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $despre_galerie ) ) : ?>
                    <div class="grid grid-cols-3 gap-3">
                        <?php foreach ( array_slice( $despre_galerie, 0, 3 ) as $gimg ) : ?>
                            <div class="overflow-hidden aspect-[4/3]">
                                <img src="<?php echo esc_url( $gimg['sizes']['medium'] ?? $gimg['url'] ); ?>"
                                     alt="<?php echo esc_attr( $gimg['alt'] ?? '' ); ?>"
                                     class="w-full h-full object-cover grayscale hover:grayscale-0 transition-[filter] duration-500">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

               
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ CUVÂNTUL PRIMARULUI ══════════════════════════ -->
<?php
$primar_nume       = get_field( 'primar_nume' )       ?: 'Ioan Popescu';
$primar_titlu      = get_field( 'primar_titlu' )      ?: get_bloginfo( 'name' );
$primar_cuvant     = get_field( 'primar_cuvant' )     ?: '';
$primar_imagine    = get_field( 'primar_imagine' );
$primar_semnatura  = get_field( 'primar_semnatura' );
$echipa            = get_field( 'echipa_administrativa' ) ?: [
    [ 'rol' => __( 'Primar',           'primarie' ), 'nume' => $primar_nume ],
    [ 'rol' => __( 'Viceprimar',       'primarie' ), 'nume' => '—' ],
    [ 'rol' => __( 'Secretar General', 'primarie' ), 'nume' => '—' ],
    [ 'rol' => __( 'Contabil',         'primarie' ), 'nume' => '—' ],
];
?>
<section class="py-20 bg-white" id="primar" aria-labelledby="primar-heading">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr_340px] gap-10 items-start">

            <!-- COL 1: Echipa administrativă -->
            <div class="bg-white border border-stroke p-7 rounded-sm">
                <p class="text-[.68rem] font-bold uppercase tracking-[.16em] text-primary m-0 mb-1">
                    <?php esc_html_e( 'Primăria', 'primarie' ); ?>
                </p>
                <h2 class="text-[1.35rem] font-extrabold text-ink m-0 mb-7 tracking-[-0.01em]">
                    <?php esc_html_e( 'Echipa administrativă', 'primarie' ); ?>
                </h2>
                <ul class="list-none m-0 p-0 flex flex-col divide-y divide-stroke">
                    <?php foreach ( $echipa as $membru ) : ?>
                        <li class="py-4 first:pt-0 last:pb-0">
                            <span class="block text-[.72rem] font-semibold text-primary mb-[3px]">
                                <?php echo esc_html( $membru['rol'] ); ?>
                            </span>
                            <span class="block text-[1rem] font-bold text-ink">
                                <?php echo esc_html( $membru['nume'] ); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- COL 2: Cuvântul primarului -->
            <div class="flex flex-col gap-5">
                <h2 id="primar-heading" class="text-[1.65rem] font-extrabold text-ink m-0 tracking-[-0.02em]">
                    <?php esc_html_e( 'Cuvântul primarului', 'primarie' ); ?>
                </h2>
                <div class="text-[.97rem] text-muted leading-[1.9] [&_p]:m-0 [&_p+p]:mt-4">
                    <?php if ( $primar_cuvant ) : ?>
                        <?php echo wp_kses_post( $primar_cuvant ); ?>
                    <?php else : ?>
                        <p><?php esc_html_e( 'Prin acest site dorim să asigurăm transparența și deschiderea totală în activitatea administrației publice locale. Aici veți găsi informații actualizate despre proiectele aflate în derulare, inițiativele comunitare, evenimentele locale și toate aspectele care influențează viața de zi cu zi a locuitorilor comunei.', 'primarie' ); ?></p>
                        <p><?php esc_html_e( 'Vă asigur că fiecare decizie adoptată la nivel local are ca scop principal binele și prosperitatea fiecărui cetățean. Împreună, prin implicare și respect reciproc, putem construi o comunitate unită, modernă și orientată spre viitor.', 'primarie' ); ?></p>
                        <p><?php esc_html_e( 'Vă mulțumim pentru vizita pe site și vă invităm să descoperiți frumusețea și spiritul cald al comunei noastre și în realitate.', 'primarie' ); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ( $primar_semnatura ) : ?>
                    <img src="<?php echo esc_url( $primar_semnatura['url'] ); ?>"
                         alt="<?php echo esc_attr( $primar_semnatura['alt'] ?: __( 'Semnătură', 'primarie' ) ); ?>"
                         class="h-14 w-auto object-contain opacity-70 mt-2 self-start">
                <?php else : ?>
                    <p class="text-[1.1rem] italic text-muted/60 m-0 mt-2" style="font-family: Georgia, serif;">
                        <?php echo esc_html( $primar_nume ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- COL 3: Fotografie primar -->
            <div class="relative w-full overflow-hidden">
                <?php if ( $primar_imagine ) : ?>
                    <img src="<?php echo esc_url( $primar_imagine['url'] ); ?>"
                         alt="<?php echo esc_attr( $primar_imagine['alt'] ?: $primar_nume ); ?>"
                         class="w-full object-cover object-top block">
                <?php else : ?>
                    <div class="w-full aspect-[3/4] bg-stroke/40 flex items-center justify-center">
                        <svg class="size-20 text-muted/20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary/90 to-transparent pt-12 pb-5 px-5">
                    <p class="text-[1.2rem] font-extrabold text-white m-0 mb-[2px] tracking-[-0.01em]">
                        <?php echo esc_html( $primar_nume ); ?>
                    </p>
                    <p class="text-[.68rem] font-bold uppercase tracking-[.16em] text-white/65 m-0">
                        <?php echo esc_html( sprintf( __( 'Primarul comunei %s', 'primarie' ), $primar_titlu ) ); ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════ NEWS + LINKS ════════════════════════════ -->
<section class="py-16 bg-white" id="noutati" aria-label="Conținut principal">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8">

            <!-- ANNOUNCEMENTS COLUMN -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[1.4rem] font-extrabold text-ink m-0 tracking-[-0.01em]">
                        <?php esc_html_e( 'Noutăți', 'primarie' ); ?>
                    </h2>
                </div>

                <div class="overflow-hidden border border-stroke shadow-sm">
                    <table class="w-full" style="border-collapse:separate;border-spacing:0;">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-left px-5 py-[.65rem] text-base font-bold uppercase tracking-[.1em]">
                                    <?php esc_html_e( 'Ultimele Anunțuri', 'primarie' ); ?>
                                </th>
                                <th class="text-right px-5 py-[.65rem] text-base font-bold uppercase tracking-[.1em] w-[140px]">
                                    <?php esc_html_e( 'Document', 'primarie' ); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( $announcements->have_posts() ) : ?>
                                <?php $row = 0; while ( $announcements->have_posts() ) : $announcements->the_post(); $row++;
                                    $data_doc  = get_field( 'data_document' );
                                    $titlu_doc = get_field( 'titlu_document' );
                                    $numar_doc = get_field( 'numar_document' );
                                    $fisier    = get_field( 'fisier_pdf' );
                                ?>
                                    <tr class="doc-row <?php echo $row % 2 === 0 ? 'doc-row-gray' : 'doc-row-white'; ?>">
                                        <td class="doc-cell align-middle">
                                            <div class="flex items-center gap-4">
                                                <?php
                                                    $ts = false;
                                                    if ( $data_doc ) {
                                                        $dt = DateTime::createFromFormat( 'd/m/Y', $data_doc )
                                                           ?: DateTime::createFromFormat( 'Y-m-d', $data_doc )
                                                           ?: DateTime::createFromFormat( 'd.m.Y', $data_doc );
                                                        if ( $dt ) {
                                                            $ts = $dt->getTimestamp();
                                                        }
                                                    }
                                                    if ( ! $ts ) {
                                                        $ts = get_the_time( 'U' );
                                                    }
                                                ?>
                                                <div class="flex flex-row items-center justify-center text-black w-[198px] min-h-[64px] shrink-0 text-center py-2 ">
                                                    <i class="bx bxs-calendar-alt text-[1.4rem] leading-none mb-[3px] opacity-80"></i>
                                                   
                                                    <div class="flex flex-row items-center justify-center gap-[4px] flex-wrap"> 
                                                        <span class="text-[1rem] leading-none"><?php echo date_i18n( 'd', $ts ); ?></span>
                                                        <span class="text-[1rem] leading-none tracking-wide"><?php echo date_i18n( 'M', $ts ); ?></span>
                                                        <span class="text-[1rem] leading-none relative top-[0px]"><?php echo date_i18n( 'Y', $ts ); ?></span>
                                                    </div>
                                                    
                                                </div>
                                                <span class="text-base text-ink font-medium leading-[1.4]">
                                                    <?php echo esc_html( $titlu_doc ?: get_the_title() ); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="doc-cell-right">
                                            <?php if ( $fisier ) : ?>
                                                <a href="<?php echo esc_url( $fisier['url'] ); ?>"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="doc-download-link group">
                                                    <svg class="size-7 shrink-0 text-ink transition-colors duration-200 group-hover:text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm5.845 17.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V12a.75.75 0 00-1.5 0v4.19l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd"/>
                                                        <path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z"/>
                                                    </svg>
                                                   
                                                </a>
                                            <?php else : ?>
                                                <span class="text-base text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            <?php else : ?>
                                <tr class="bg-white">
                                    <td colspan="2" class="px-5 py-6 text-center text-base text-muted">
                                        <?php esc_html_e( 'Nu există anunțuri publicate momentan.', 'primarie' ); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- QUICK LINKS COLUMN -->
            <div>
                <div class="mb-6">
                    <h2 class="text-[1.4rem] font-extrabold text-ink m-0 tracking-[-0.01em]">
                        <?php esc_html_e( 'Servicii Frecvente', 'primarie' ); ?>
                    </h2>
                </div>
                <ul class="flex flex-col gap-[.35rem] list-none m-0 p-0 mb-6" role="list">
                    <?php foreach ( $quick_links as $ql ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $ql['link'] ); ?>"
                               class="flex items-center gap-2 px-4 py-[.65rem] bg-white border border-stroke no-underline
                                      text-base font-medium text-ink
                                      transition-all duration-[220ms] hover:border-primary/30 hover:bg-primary/[.04] hover:text-primary hover:pl-5">
                                <svg class="size-4 text-primary shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo esc_html( $ql['label'] ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Schedule card -->
                <div class="bg-primary p-6 text-white">
                    <h3 class="text-[1rem] font-bold m-0 mb-4"><?php esc_html_e( 'Program de lucru', 'primarie' ); ?></h3>
                    <ul class="flex flex-col list-none m-0 p-0">
                        <li class="flex justify-between items-center text-base py-[.45rem] border-b border-white/[.12]">
                            <span class="text-white/75"><?php esc_html_e( 'Luni – Joi', 'primarie' ); ?></span>
                            <strong class="font-bold">08:00 – 16:30</strong>
                        </li>
                        <li class="flex justify-between items-center text-base py-[.45rem] border-b border-white/[.12]">
                            <span class="text-white/75"><?php esc_html_e( 'Vineri', 'primarie' ); ?></span>
                            <strong class="font-bold">08:00 – 14:00</strong>
                        </li>
                        <li class="flex justify-between items-center text-base pt-[.45rem]">
                            <span class="text-white/75"><?php esc_html_e( 'Sâmbătă – Duminică', 'primarie' ); ?></span>
                            <strong class="font-bold"><?php esc_html_e( 'Închis', 'primarie' ); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════ INFO STRIP ══════════════════════════════ -->


<?php get_footer(); ?>
