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
    [ 'icon' => 'bx bx-clipboard',      'title' => __( 'Acte necesare',         'primarie' ), 'link' => '#' ],
    [ 'icon' => 'bx bx-id-card',         'title' => __( 'Evidența persoanelor',  'primarie' ), 'link' => '#' ],
    [ 'icon' => 'bx bx-coin-stack',      'title' => __( 'Taxe și impozite',      'primarie' ), 'link' => '#' ],
    [ 'icon' => 'bx bx-calendar-heart',  'title' => __( 'Stare civilă',          'primarie' ), 'link' => '#' ],
    [ 'icon' => 'bx bx-buildings',       'title' => __( 'Urbanism și cadastru',  'primarie' ), 'link' => '#' ],
    [ 'icon' => 'bx bx-cart',            'title' => __( 'Achiziții publice',     'primarie' ), 'link' => '#' ],
];

// ─── Quick links (serviciu CPT) ───────────────────────────────────────────────
$quick_links_query = new WP_Query( [
    'post_type'      => 'serviciu',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
] );

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

get_header();

// ─── Hero ACF fields ──────────────────────────────────────────────────────────
$subtitlu_hero_sus = get_field( 'subtitlu_hero_sus' );
$subtitlu_hero     = get_field( 'subtitlu_hero' );
$subtitlu_hero_jos = get_field( 'subtitlu_hero_jos' );
$buton_1           = get_field( 'buton_1' );
$buton_2           = get_field( 'buton_2' );
?>

<!-- ═══════════════════════════════ HERO ════════════════════════════════════ -->
<section class="relative overflow-hidden min-h-[580px] bg-gradient-to-br from-primary to-accent" aria-label="Banner principal">

    <div id="harta-comuna" class="absolute inset-0 z-[1]"></div>

    <div class="absolute inset-0 z-[2] pointer-events-none" style="background:linear-gradient(to right,rgba(255,255,255,.82) 0%,rgba(255,255,255,.55) 38%,rgba(255,255,255,.10) 60%,transparent 75%);"></div>

    <div class="absolute inset-0 z-[3] flex items-center pointer-events-none">
        <div class="site-container w-full py-16">
            <div class="max-w-[620px] pointer-events-auto">

                <?php if ( $subtitlu_hero_sus ) : ?>
                    <p class="text-base font-semibold uppercase tracking-[.12em] text-black m-0 mb-3">
                        <?php echo esc_html( $subtitlu_hero_sus ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $subtitlu_hero ) : ?>
                    <h1 class="text-[2.6rem] md:text-[3.5rem] font-extrabold text-black leading-[1.1] m-0 mb-4 tracking-[-0.02em]">
                        <?php echo esc_html( $subtitlu_hero ); ?>
                    </h1>
                <?php endif; ?>

                <?php if ( $subtitlu_hero_jos ) : ?>
                    <p class="text-[1.05rem] text-black m-0 mb-8">
                        <?php echo esc_html( $subtitlu_hero_jos ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $buton_1 || $buton_2 ) : ?>
                    <div class="flex flex-wrap gap-3">
                        <?php if ( is_array( $buton_1 ) ) : ?>
                            <a href="<?php echo esc_url( $buton_1['url'] ); ?>"
                               <?php if ( ! empty( $buton_1['target'] ) ) : ?>target="<?php echo esc_attr( $buton_1['target'] ); ?>"<?php endif; ?>
                               class="inline-flex items-center px-6 py-3 bg-primary text-white text-base font-bold no-underline
                                      transition-all duration-[220ms] hover:bg-accent hover:text-white hover:-translate-y-[2px] hover:shadow-[0_6px_24px_rgba(0,0,0,.25)]">
                                <?php echo esc_html( $buton_1['title'] ); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ( is_array( $buton_2 ) ) : ?>
                            <a href="<?php echo esc_url( $buton_2['url'] ); ?>"
                               <?php if ( ! empty( $buton_2['target'] ) ) : ?>target="<?php echo esc_attr( $buton_2['target'] ); ?>"<?php endif; ?>
                               class="inline-flex items-center px-6 py-3 bg-transparent text-black text-base font-bold no-underline
                                      border border-black/40 transition-all duration-[220ms] hover:bg-black/10 hover:border-black/70">
                                <?php echo esc_html( $buton_2['title'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <button type="button"
                            data-open-sesizare-modal
                            class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white text-base font-bold
                                   transition-all duration-[220ms] hover:bg-primary hover:-translate-y-[2px] hover:shadow-[0_6px_24px_rgba(0,0,0,.25)]">
                        <i class="bx bx-flag-alt text-lg"></i>
                        <?php esc_html_e( 'Trimite o sesizare', 'primarie' ); ?>
                    </button>
                </div>

            </div>
        </div>
    </div>

</section>


<!-- ═══════════════════════════════ SERVICES ════════════════════════════════ -->
<section class="py-16 bg-white" id="servicii" aria-labelledby="services-heading">
    <div class="site-container">
        <div class="text-center mb-10">
            <h2 id="services-heading" class="text-[1.8rem] font-extrabold text-ink m-0 tracking-[-0.02em]">
                <?php esc_html_e( 'Cele mai accesate secțiuni', 'primarie' ); ?>
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ( $services as $svc ) : ?>
                <a href="<?php echo esc_url( $svc['link'] ); ?>"
                   class="relative flex flex-col items-center gap-4 p-8 bg-white border border-stroke no-underline overflow-hidden group
                          transition-all duration-[220ms] hover:-translate-y-1 hover:shadow-[0_8px_40px_rgba(0,0,0,.10)] hover:border-primary/30">
                    <span class="absolute inset-x-0 top-0 h-[3px] bg-primary scale-x-0 group-hover:scale-x-100 transition-transform duration-[220ms] origin-left"></span>
                    <i class="<?php echo esc_attr( $svc['icon'] ); ?> text-[2.8rem] text-primary" aria-hidden="true"></i>
                    <h3 class="text-[1rem] font-semibold text-ink m-0 text-center leading-[1.3]">
                        <?php echo esc_html( $svc['title'] ); ?>
                    </h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════ DESPRE COMUNĂ ═══════════════════════════ -->
<?php
$left_image  = get_field( 'left_image' );
$slider_text = get_field( 'slider_text' );
$descriere   = get_field( 'descriere' ) ?: '';

$rotator_words = [];
if ( is_array( $slider_text ) ) {
    $rotator_words = array_values( array_filter( [
        $slider_text['text_1'] ?? '',
        $slider_text['text_2'] ?? '',
        $slider_text['text_3'] ?? '',
    ] ) );
}
?>
<section class="py-20 bg-white" id="despre" aria-labelledby="despre-heading">
    <div class="site-container">
        <div class="grid grid-cols-1 lg:grid-cols-[5fr_7fr] gap-14 items-start">

            <!-- LEFT: imagine -->
            <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                <div class="relative w-full mx-auto lg:mx-0 mb-6">
                    <?php if ( $left_image ) : ?>
                        <img src="<?php echo esc_url( $left_image['url'] ); ?>"
                             alt="<?php echo esc_attr( $left_image['alt'] ); ?>"
                             class="w-full object-cover block">
                    <?php else : ?>
                        <div class="w-full aspect-[3/4] bg-faint flex items-center justify-center">
                            <i class="bx bx-image text-[4rem] text-muted/30" aria-hidden="true"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: rotator + descriere -->
            <div class="flex flex-col gap-7">

                <!-- WORD ROTATOR -->
                <?php if ( ! empty( $rotator_words ) ) : ?>
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
                <?php endif; ?>

                <?php if ( $descriere ) : ?>
                    <div class="text-[.97rem] text-muted leading-[1.85] [&_p]:m-0 [&_p+p]:mt-4">
                        <?php echo wp_kses_post( $descriere ); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ CUVÂNTUL PRIMARULUI ══════════════════════════ -->
<?php
$primar                 = get_field( 'primar' )                 ?: '';
$viceprimar             = get_field( 'viceprimar' )             ?: '';
$secretar_general       = get_field( 'secretar_general' )       ?: '';
$contabil               = get_field( 'contabil' )               ?: '';
$cuvantul_primarului    = get_field( 'cuvantul_primarului' )    ?: '';
$poza_primar            = get_field( 'poza_primar' );
$titlu_primar_peste_poza = get_field( 'titlu_primar_peste_poza' ) ?: '';

$echipa = [
    [ 'rol' => __( 'Primar',           'primarie' ), 'nume' => $primar ],
    [ 'rol' => __( 'Viceprimar',       'primarie' ), 'nume' => $viceprimar ],
    [ 'rol' => __( 'Secretar General', 'primarie' ), 'nume' => $secretar_general ],
    [ 'rol' => __( 'Contabil',         'primarie' ), 'nume' => $contabil ],
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
                                <?php echo $membru['nume'] ? esc_html( $membru['nume'] ) : '—'; ?>
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
                <?php if ( $cuvantul_primarului ) : ?>
                    <div class="text-[.97rem] text-muted leading-[1.9] [&_p]:m-0 [&_p+p]:mt-4">
                        <?php echo wp_kses_post( $cuvantul_primarului ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( $primar ) : ?>
                    <p class="text-[1.1rem] italic text-muted/60 m-0 mt-2" style="font-family: Georgia, serif;">
                        <?php echo esc_html( $primar ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- COL 3: Fotografie primar -->
            <div class="relative w-full overflow-hidden">
                <?php if ( $poza_primar ) : ?>
                    <img src="<?php echo esc_url( $poza_primar['url'] ); ?>"
                         alt="<?php echo esc_attr( $poza_primar['alt'] ?: $primar ); ?>"
                         class="w-full object-cover object-top block">
                <?php else : ?>
                    <div class="w-full aspect-[3/4] bg-stroke/40 flex items-center justify-center">
                        <i class="bx bx-user text-[4rem] text-muted/20" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary/90 to-transparent pt-12 pb-5 px-5">
                    <?php if ( $primar ) : ?>
                        <p class="text-[1.2rem] font-extrabold text-white m-0 mb-[2px] tracking-[-0.01em]">
                            <?php echo esc_html( $primar ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $titlu_primar_peste_poza ) : ?>
                        <p class="text-[.68rem] font-bold uppercase text-white/65 tracking-[.16em] m-0">
                            <?php echo esc_html( $titlu_primar_peste_poza ); ?>
                        </p>
                    <?php endif; ?>
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
                                                <div class="flex flex-row items-start justify-start text-white w-[198px] min-h-[64px] shrink-0 text-center py-2 ">
                                                                                                      
                                                    <div class="flex flex-row items-center justify-center gap-[4px] flex-wrap bg-primary text-white rounded-[10px] px-[5px] py-[10px]">
                                                        <span class="text-[1rem] leading-none"><?php echo date_i18n( 'd', $ts ); ?></span>
                                                        <span class="text-[1rem] leading-none tracking-wide"><?php echo date_i18n( 'M', $ts ); ?></span>
                                                        <span class="text-[1rem] leading-none w-full relative top-[0px]"><?php echo date_i18n( 'Y', $ts ); ?></span>
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
                    <?php if ( $quick_links_query->have_posts() ) : ?>
                        <?php while ( $quick_links_query->have_posts() ) : $quick_links_query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"
                                   class="flex items-center gap-2 px-4 py-[.65rem] bg-white border border-stroke no-underline
                                          text-base font-medium text-ink
                                          transition-all duration-[220ms] hover:border-primary/30 hover:bg-primary/[.04] hover:text-primary hover:pl-5">
                                    <svg class="size-4 text-primary shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php the_title(); ?>
                                </a>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php else : ?>
                        <li class="px-4 py-3 text-base text-muted">
                            <?php esc_html_e( 'Niciun serviciu adăugat momentan.', 'primarie' ); ?>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Schedule card -->
                <?php
                $program_lucru = get_option( 'primarie_program_lucru', [
                    [ 'zi' => __( 'Luni – Joi',           'primarie' ), 'ore' => '08:00 – 16:30' ],
                    [ 'zi' => __( 'Vineri',               'primarie' ), 'ore' => '08:00 – 14:00' ],
                    [ 'zi' => __( 'Sâmbătă – Duminică',  'primarie' ), 'ore' => __( 'Închis', 'primarie' ) ],
                ] );
                if ( ! is_array( $program_lucru ) ) $program_lucru = [];
                ?>
                <div class="schedule-card">
                    <h3><?php esc_html_e( 'Program de lucru', 'primarie' ); ?></h3>
                    <ul>
                        <?php foreach ( $program_lucru as $rand ) : ?>
                            <li>
                                <span><?php echo esc_html( $rand['zi'] ?? '' ); ?></span>
                                <strong><?php echo esc_html( $rand['ore'] ?? '' ); ?></strong>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════ INFO STRIP ══════════════════════════════ -->


<?php get_footer(); ?>
