<?php
/**
 * Sesizare Modal — Loader
 *
 * Fișier de bootstrap independent de temă: enqueue assets (Leaflet + JS/CSS
 * proprii), include handler-ul AJAX/CPT și randează markup-ul modalului în
 * footer.
 *
 * Acest fișier NU adaugă singur butonul de declanșare — modalul se deschide
 * fie apelând `window.openSesizareModal()`, fie prin click pe orice element
 * cu `id="open-sesizare-modal"` sau atributul `data-open-sesizare-modal`.
 *
 * Trebuie inclus manual (ex. din functions.php):
 *     require_once get_template_directory() . '/sesizare-modal-loader.php';
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/includes/class-sesizare-handler.php';

/**
 * Permite restricționarea pe ce pagini se încarcă modalul (Leaflet + CSS/JS).
 * Implicit activ pe toate paginile, deoarece butonul de declanșare poate fi
 * plasat oriunde (header, footer, pagini specifice etc.).
 *
 * Exemplu de restricționare:
 *     add_filter( 'sesizare_modal_should_load', '__return_false' );
 *     // și apoi reactivare condiționată pe anumite pagini/template-uri.
 */
function sesizare_modal_should_load() {
    return apply_filters( 'sesizare_modal_should_load', true );
}

add_action( 'wp_enqueue_scripts', 'sesizare_modal_enqueue_assets' );
function sesizare_modal_enqueue_assets() {

    if ( ! sesizare_modal_should_load() ) {
        return;
    }

    // Leaflet — același handle/versiune ca harta-comuna.js, ca să nu se
    // încarce de două ori pe paginile unde ambele sunt prezente.
    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        [],
        '1.9.4'
    );

    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        [],
        '1.9.4',
        true
    );

    wp_enqueue_style(
        'sesizare-modal-css',
        get_template_directory_uri() . '/assets/css/sesizare-modal.css',
        [ 'leaflet-css' ],
        '1.0.1'
    );

    wp_enqueue_script(
        'sesizare-modal-js',
        get_template_directory_uri() . '/assets/js/sesizare-modal.js',
        [ 'leaflet-js' ],
        '1.0.1',
        true
    );

    // Conturul localității — același fișier folosit și de harta din hero
    // (assets/geojson/nimigea.json). La reutilizarea temei pentru altă
    // primărie, e suficient să se înlocuiască fișierul de aici.
    $geojson_path = get_template_directory() . '/assets/geojson/nimigea.json';
    $geojson_url  = file_exists( $geojson_path ) ? get_template_directory_uri() . '/assets/geojson/nimigea.json' : '';

    wp_localize_script( 'sesizare-modal-js', 'sesizareModalData', [
        'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
        'action'           => Sesizare_Handler::AJAX_ACTION,
        'nonce'            => wp_create_nonce( Sesizare_Handler::NONCE_ACTION ),
        'maxFileSize'      => Sesizare_Handler::get_max_file_size(),
        'allowedMimeTypes' => array_values( Sesizare_Handler::get_allowed_image_types() ),
        'geojsonUrl'       => $geojson_url,
        'i18n'             => [
            'fileTooLarge'      => __( 'Imaginea depășește dimensiunea maximă admisă (5MB).', 'primarie' ),
            'fileInvalidType'   => __( 'Tipul fișierului nu este permis. Sunt acceptate doar imagini.', 'primarie' ),
            'descTooShort'      => __( 'Descrierea trebuie să aibă minimum 20 de caractere.', 'primarie' ),
            'emailInvalid'      => __( 'Adresa de email nu este validă.', 'primarie' ),
            'phoneInvalid'      => __( 'Numărul de telefon nu este valid.', 'primarie' ),
            'genericError'      => __( 'A apărut o eroare. Încearcă din nou.', 'primarie' ),
            'confirmClose'      => __( 'Ai date necompletate în formular. Sigur vrei să închizi?', 'primarie' ),
            'outsideBoundary'   => __( 'Locația selectată trebuie să fie în interiorul localității.', 'primarie' ),
        ],
    ] );
}

add_action( 'wp_footer', 'sesizare_modal_render_markup' );
function sesizare_modal_render_markup() {

    if ( ! sesizare_modal_should_load() ) {
        return;
    }

    $tip_options = Sesizare_Handler::get_tip_options();
    ?>
    <div id="sesizare-modal-overlay" class="sesizare-overlay hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="sesizare-modal-title">
        <div id="sesizare-modal" class="sesizare-modal w-full max-w-2xl max-h-[90vh] bg-[#1a2456] text-white rounded-lg shadow-2xl flex flex-col font-sans">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 flex-shrink-0">
                <h2 id="sesizare-modal-title" class="text-lg font-semibold">
                    <?php esc_html_e( 'Formular Sesizare', 'primarie' ); ?>
                </h2>
                <button type="button" id="sesizare-close" aria-label="<?php esc_attr_e( 'Închide', 'primarie' ); ?>" class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/10 transition-colors text-xl">
                    <i class="bx bx-x"></i>
                </button>
            </div>

            <!-- Body (scrollabil) -->
            <div id="sesizare-step-content" class="flex-1 overflow-y-auto px-6 py-5">

                <!-- Pas 1 — Alege pe hartă -->
                <div data-step-panel="1">
                    <h3 class="text-base font-semibold mb-3"><?php esc_html_e( 'Alege pe hartă:', 'primarie' ); ?></h3>
                    <p class="text-sm text-white/70 mb-1"><?php esc_html_e( 'Dați clic pe locația dorită pe hartă pentru a o selecta.', 'primarie' ); ?></p>
                    <p class="text-sm text-white/70 mb-4"><?php esc_html_e( 'După ce ați ales locația, un marcaj albastru va apărea pentru a indica poziția selectată.', 'primarie' ); ?></p>
                    <div id="sesizare-map" class="w-full h-[320px] sm:h-[380px] rounded overflow-hidden"></div>
                    <p id="sesizare-map-error" class="text-xs text-red-300 mt-2 hidden"></p>
                    <input type="hidden" id="sesizare-lat">
                    <input type="hidden" id="sesizare-lng">
                </div>

                <!-- Pas 2 — Despre sesizare -->
                <div data-step-panel="2" class="hidden space-y-5">
                    <div>
                        <h3 class="text-base font-semibold mb-2"><?php esc_html_e( 'Informații despre sesizare:', 'primarie' ); ?></h3>
                        <p class="text-sm text-white/70"><?php esc_html_e( 'Aceste informații sunt esențiale pentru procesarea rapidă și eficientă a cererii, facilitând astfel intervențiile autorităților competente.', 'primarie' ); ?></p>
                    </div>

                    <div>
                        <label for="sesizare-tip" class="block text-sm mb-2"><?php esc_html_e( 'Tip sesizare:', 'primarie' ); ?></label>
                        <select id="sesizare-tip" class="w-full bg-[#10183f] text-white rounded px-3 py-2.5 outline-none border-0 focus:ring-2 focus:ring-[#7cb342]">
                            <option value=""><?php esc_html_e( 'Alege o opțiune', 'primarie' ); ?></option>
                            <?php foreach ( $tip_options as $slug => $label ) : ?>
                                <option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="sesizare-imagine" class="block text-sm mb-2"><?php esc_html_e( 'Încarcă imagine:', 'primarie' ); ?></label>
                        <input
                            type="file"
                            id="sesizare-imagine"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            class="w-full text-sm text-white/80 bg-[#10183f] rounded px-3 py-2.5 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-white file:text-[#1a2456] file:text-sm file:font-medium file:cursor-pointer cursor-pointer"
                        >
                        <p id="sesizare-imagine-error" class="text-xs text-red-300 mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="sesizare-descriere" class="block text-sm mb-2"><?php esc_html_e( 'Descriere:', 'primarie' ); ?></label>
                        <textarea
                            id="sesizare-descriere"
                            rows="4"
                            placeholder="<?php esc_attr_e( 'Descrie cât mai detaliat sesizarea ta', 'primarie' ); ?>"
                            class="w-full bg-[#10183f] text-white placeholder-white/40 rounded px-3 py-2.5 outline-none resize-none focus:ring-2 focus:ring-[#7cb342]"
                        ></textarea>
                        <p id="sesizare-descriere-error" class="text-xs text-red-300 mt-1 hidden"></p>
                    </div>
                </div>

                <!-- Pas 3 — Trimite -->
                <div data-step-panel="3" class="hidden space-y-4">
                    <div>
                        <input type="text" id="sesizare-nume" placeholder="<?php esc_attr_e( 'Nume', 'primarie' ); ?>" class="w-full bg-[#10183f] text-white placeholder-white/50 rounded px-3 py-2.5 outline-none focus:ring-2 focus:ring-[#7cb342]">
                    </div>
                    <div>
                        <input type="email" id="sesizare-email" placeholder="<?php esc_attr_e( 'Email', 'primarie' ); ?>" class="w-full bg-[#10183f] text-white placeholder-white/50 rounded px-3 py-2.5 outline-none focus:ring-2 focus:ring-[#7cb342]">
                    </div>
                    <div>
                        <input type="tel" id="sesizare-telefon" placeholder="<?php esc_attr_e( 'Telefon', 'primarie' ); ?>" class="w-full bg-[#10183f] text-white placeholder-white/50 rounded px-3 py-2.5 outline-none focus:ring-2 focus:ring-[#7cb342]">
                    </div>

                    <p class="text-xs text-white/60 leading-relaxed">
                        <?php
                        printf(
                            /* translators: 1: link policy, 2: link terms */
                            esc_html__( 'Acest site este protejat de reCAPTCHA și se aplică %1$s și %2$s Google.', 'primarie' ),
                            '<a href="#" class="underline hover:text-white">' . esc_html__( 'Politica de confidențialitate', 'primarie' ) . '</a>',
                            '<a href="#" class="underline hover:text-white">' . esc_html__( 'Termenii și condițiile', 'primarie' ) . '</a>'
                        );
                        ?>
                    </p>

                    <label class="flex items-start gap-2 text-sm cursor-pointer">
                        <input type="checkbox" id="sesizare-consimtamant" class="mt-0.5 w-4 h-4 accent-[#7cb342] cursor-pointer">
                        <span><?php esc_html_e( 'Sunt de acord cu politica de prelucrare a datelor cu caracter personal.', 'primarie' ); ?></span>
                    </label>

                    <button type="button" id="sesizare-submit" disabled class="w-full inline-flex items-center justify-center gap-2 bg-[#7cb342] text-white font-semibold py-3 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#6ba038] transition-colors">
                        <?php esc_html_e( 'Trimite sesizarea', 'primarie' ); ?>
                        <i class="bx bx-shield-quarter text-lg"></i>
                    </button>

                    <p id="sesizare-form-message" class="text-sm hidden" role="alert"></p>
                </div>

            </div>

            <!-- Footer fix: stepper + navigare -->
            <div class="flex-shrink-0 border-t border-white/10">

                <div class="relative px-6 pt-5 pb-1">
                    <div class="absolute left-[42px] right-[42px] top-[23px] h-[2px] bg-white/15"></div>
                    <div id="sesizare-stepper-fill" class="absolute left-[42px] top-[23px] h-[2px] bg-[#7cb342] transition-[width] duration-300" style="width:0%"></div>

                    <div class="relative flex justify-between">
                        <div class="flex flex-col items-center gap-2 w-20" data-stepper-item="1">
                            <div data-stepper-circle class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors">1</div>
                            <span class="text-[11px] text-white/80 text-center leading-tight"><?php esc_html_e( 'Alege pe hartă', 'primarie' ); ?></span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-20" data-stepper-item="2">
                            <div data-stepper-circle class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors">2</div>
                            <span class="text-[11px] text-white/80 text-center leading-tight"><?php esc_html_e( 'Despre sesizare', 'primarie' ); ?></span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-20" data-stepper-item="3">
                            <div data-stepper-circle class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-colors">3</div>
                            <span class="text-[11px] text-white/80 text-center leading-tight"><?php esc_html_e( 'Trimite', 'primarie' ); ?></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between px-6 py-4">
                    <button type="button" id="sesizare-btn-prev" disabled class="inline-flex items-center gap-1.5 text-sm font-medium text-white/70 disabled:opacity-40 disabled:cursor-not-allowed hover:text-white transition-colors">
                        <i class="bx bx-arrow-back"></i>
                        <?php esc_html_e( 'Anterior', 'primarie' ); ?>
                    </button>
                    <button type="button" id="sesizare-btn-next" disabled class="inline-flex items-center gap-1.5 text-sm font-semibold bg-white text-[#1a2456] px-4 py-2 rounded transition-colors disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-white/20 disabled:text-white/60 hover:bg-white/90">
                        <?php esc_html_e( 'Următor', 'primarie' ); ?>
                        <i class="bx bx-arrow-back rotate-180"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>
    <?php
}
