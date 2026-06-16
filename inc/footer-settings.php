<?php
defined( 'ABSPATH' ) || exit;

// ─── Submeniu Appearance → Footer Settings ───────────────────────────────────

add_action( 'admin_menu', function () {
    add_menu_page(
        __( 'Theme Options', 'primarie' ),
        __( 'Theme Options', 'primarie' ),
        'manage_options',
        'primarie-theme-options',
        'primarie_render_footer_settings',
        'dashicons-admin-customizer',
        61
    );

    add_submenu_page(
        'primarie-theme-options',
        __( 'Footer Settings', 'primarie' ),
        __( 'Footer Settings', 'primarie' ),
        'manage_options',
        'primarie-theme-options',
        'primarie_render_footer_settings'
    );
} );

// ─── Înregistrare opțiuni ────────────────────────────────────────────────────

add_action( 'admin_init', function () {
    register_setting( 'primarie_footer_settings', 'primarie_footer_carousel', [
        'sanitize_callback' => function ( $input ) {
            if ( ! is_array( $input ) ) return [];
            $clean = [];
            foreach ( $input as $row ) {
                $img_id = absint( $row['img_id'] ?? 0 );
                $link   = esc_url_raw( $row['link'] ?? '' );
                if ( $img_id ) {
                    $clean[] = [ 'img_id' => $img_id, 'link' => $link ];
                }
            }
            return $clean;
        },
    ] );

    register_setting( 'primarie_program_lucru_settings', 'primarie_program_lucru', [
        'sanitize_callback' => function ( $input ) {
            if ( ! is_array( $input ) ) return [];
            $clean = [];
            foreach ( $input as $row ) {
                $zi  = sanitize_text_field( $row['zi']  ?? '' );
                $ore = sanitize_text_field( $row['ore'] ?? '' );
                if ( $zi !== '' ) {
                    $clean[] = [ 'zi' => $zi, 'ore' => $ore ];
                }
            }
            return $clean;
        },
    ] );
} );

// ─── Enqueue media uploader doar pe pagina noastră ───────────────────────────

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_primarie-theme-options' !== $hook ) return;
    wp_enqueue_media();
} );

// ─── Render pagina de setări ─────────────────────────────────────────────────

function primarie_render_footer_settings() {
    $active_tab   = isset( $_GET['tab'] ) && $_GET['tab'] === 'program' ? 'program' : 'footer';
    $carousel     = get_option( 'primarie_footer_carousel', [] );
    if ( ! is_array( $carousel ) ) $carousel = [];
    $program      = get_option( 'primarie_program_lucru', [
        [ 'zi' => 'Luni – Joi',            'ore' => '08:00 – 16:30' ],
        [ 'zi' => 'Vineri',                'ore' => '08:00 – 14:00' ],
        [ 'zi' => 'Sâmbătă – Duminică',   'ore' => 'Închis' ],
    ] );
    if ( ! is_array( $program ) ) $program = [];

    $base_url = admin_url( 'admin.php?page=primarie-theme-options' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Theme Options', 'primarie' ); ?></h1>

        <nav class="nav-tab-wrapper" style="margin-bottom:1.5rem;">
            <a href="<?php echo esc_url( $base_url . '&tab=footer' ); ?>"
               class="nav-tab <?php echo $active_tab === 'footer' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Carousel Footer', 'primarie' ); ?>
            </a>
            <a href="<?php echo esc_url( $base_url . '&tab=program' ); ?>"
               class="nav-tab <?php echo $active_tab === 'program' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e( 'Program de lucru', 'primarie' ); ?>
            </a>
        </nav>

        <?php if ( $active_tab === 'footer' ) : ?>

            <?php settings_errors( 'primarie_footer_settings' ); ?>
            <form method="post" action="options.php" id="footer-settings-form">
                <?php settings_fields( 'primarie_footer_settings' ); ?>

                <h2 class="title"><?php esc_html_e( 'Carousel Bannere', 'primarie' ); ?></h2>
                <p class="description"><?php esc_html_e( 'Adaugă imaginile și link-urile care apar în carousel-ul din footer.', 'primarie' ); ?></p>

                <table class="wp-list-table widefat fixed striped" style="max-width:820px;margin-top:1rem;">
                    <thead>
                        <tr>
                            <th style="width:160px"><?php esc_html_e( 'Imagine', 'primarie' ); ?></th>
                            <th><?php esc_html_e( 'Link (URL)', 'primarie' ); ?></th>
                            <th style="width:90px"></th>
                        </tr>
                    </thead>
                    <tbody id="carousel-tbody">
                        <?php foreach ( $carousel as $i => $row ) :
                            $thumb = wp_get_attachment_image_url( $row['img_id'], 'thumbnail' );
                        ?>
                        <tr class="carousel-row">
                            <td>
                                <input type="hidden"
                                       name="primarie_footer_carousel[<?php echo $i; ?>][img_id]"
                                       value="<?php echo esc_attr( $row['img_id'] ); ?>"
                                       class="carousel-img-id">
                                <img src="<?php echo esc_url( $thumb ?: '' ); ?>"
                                     class="carousel-img-preview"
                                     style="max-height:56px;width:auto;display:<?php echo $thumb ? 'block' : 'none'; ?>;margin-bottom:6px;">
                                <button type="button" class="button carousel-select-img">
                                    <?php esc_html_e( 'Alege imagine', 'primarie' ); ?>
                                </button>
                            </td>
                            <td>
                                <input type="url"
                                       name="primarie_footer_carousel[<?php echo $i; ?>][link]"
                                       value="<?php echo esc_url( $row['link'] ); ?>"
                                       class="regular-text"
                                       placeholder="https://">
                            </td>
                            <td>
                                <button type="button" class="button carousel-remove-row" style="color:#b32d2e">
                                    <?php esc_html_e( 'Șterge', 'primarie' ); ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p style="margin-top:1rem;">
                    <button type="button" class="button" id="carousel-add-row">
                        &#43; <?php esc_html_e( 'Adaugă banner', 'primarie' ); ?>
                    </button>
                </p>

                <?php submit_button( __( 'Salvează setările', 'primarie' ) ); ?>
            </form>

        <?php else : ?>

            <?php settings_errors( 'primarie_program_lucru_settings' ); ?>
            <form method="post" action="options.php" id="program-lucru-form">
                <?php settings_fields( 'primarie_program_lucru_settings' ); ?>

                <h2 class="title"><?php esc_html_e( 'Program de lucru', 'primarie' ); ?></h2>
                <p class="description"><?php esc_html_e( 'Intervalele afișate în caseta "Program de lucru" de pe homepage.', 'primarie' ); ?></p>

                <table class="wp-list-table widefat fixed striped" style="max-width:640px;margin-top:1rem;">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Zi / Interval zile', 'primarie' ); ?></th>
                            <th><?php esc_html_e( 'Ore', 'primarie' ); ?></th>
                            <th style="width:90px"></th>
                        </tr>
                    </thead>
                    <tbody id="program-tbody">
                        <?php foreach ( $program as $i => $row ) : ?>
                        <tr class="program-row">
                            <td>
                                <input type="text"
                                       name="primarie_program_lucru[<?php echo $i; ?>][zi]"
                                       value="<?php echo esc_attr( $row['zi'] ); ?>"
                                       class="regular-text"
                                       placeholder="<?php esc_attr_e( 'ex: Luni – Vineri', 'primarie' ); ?>">
                            </td>
                            <td>
                                <input type="text"
                                       name="primarie_program_lucru[<?php echo $i; ?>][ore]"
                                       value="<?php echo esc_attr( $row['ore'] ); ?>"
                                       class="regular-text"
                                       placeholder="<?php esc_attr_e( 'ex: 08:00 – 16:30', 'primarie' ); ?>">
                            </td>
                            <td>
                                <button type="button" class="button program-remove-row" style="color:#b32d2e">
                                    <?php esc_html_e( 'Șterge', 'primarie' ); ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p style="margin-top:1rem;">
                    <button type="button" class="button" id="program-add-row">
                        &#43; <?php esc_html_e( 'Adaugă rând', 'primarie' ); ?>
                    </button>
                </p>

                <?php submit_button( __( 'Salvează setările', 'primarie' ) ); ?>
            </form>

        <?php endif; ?>
    </div>

    <script>
    (function($) {
        // ── Carousel tab ──────────────────────────────────────────────────────
        $('#footer-settings-form').on('submit', function() {
            $('#carousel-tbody .carousel-row').each(function(i) {
                $(this).find('[name]').each(function() {
                    this.name = this.name.replace(/\[\d+\]/, '[' + i + ']');
                });
            });
        });

        $('#carousel-add-row').on('click', function() {
            var idx = $('#carousel-tbody .carousel-row').length;
            $('#carousel-tbody').append(
                '<tr class="carousel-row">' +
                    '<td>' +
                        '<input type="hidden" name="primarie_footer_carousel[' + idx + '][img_id]" value="" class="carousel-img-id">' +
                        '<img src="" class="carousel-img-preview" style="max-height:56px;width:auto;display:none;margin-bottom:6px;">' +
                        '<button type="button" class="button carousel-select-img"><?php echo esc_js( __( 'Alege imagine', 'primarie' ) ); ?></button>' +
                    '</td>' +
                    '<td><input type="url" name="primarie_footer_carousel[' + idx + '][link]" value="" class="regular-text" placeholder="https://"></td>' +
                    '<td><button type="button" class="button carousel-remove-row" style="color:#b32d2e"><?php echo esc_js( __( 'Șterge', 'primarie' ) ); ?></button></td>' +
                '</tr>'
            );
        });

        $(document).on('click', '.carousel-remove-row', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.carousel-select-img', function(e) {
            e.preventDefault();
            var $row = $(this).closest('tr');
            var frame = wp.media({
                title: '<?php echo esc_js( __( 'Alege imagine banner', 'primarie' ) ); ?>',
                button: { text: '<?php echo esc_js( __( 'Selectează', 'primarie' ) ); ?>' },
                multiple: false,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                var src = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                $row.find('.carousel-img-id').val(att.id);
                $row.find('.carousel-img-preview').attr('src', src).show();
            });
            frame.open();
        });

        // ── Program de lucru tab ───────────────────────────────────────────────
        $('#program-lucru-form').on('submit', function() {
            $('#program-tbody .program-row').each(function(i) {
                $(this).find('[name]').each(function() {
                    this.name = this.name.replace(/\[\d+\]/, '[' + i + ']');
                });
            });
        });

        $('#program-add-row').on('click', function() {
            var idx = $('#program-tbody .program-row').length;
            $('#program-tbody').append(
                '<tr class="program-row">' +
                    '<td><input type="text" name="primarie_program_lucru[' + idx + '][zi]" value="" class="regular-text" placeholder="<?php echo esc_js( __( 'ex: Luni – Vineri', 'primarie' ) ); ?>"></td>' +
                    '<td><input type="text" name="primarie_program_lucru[' + idx + '][ore]" value="" class="regular-text" placeholder="<?php echo esc_js( __( 'ex: 08:00 – 16:30', 'primarie' ) ); ?>"></td>' +
                    '<td><button type="button" class="button program-remove-row" style="color:#b32d2e"><?php echo esc_js( __( 'Șterge', 'primarie' ) ); ?></button></td>' +
                '</tr>'
            );
        });

        $(document).on('click', '.program-remove-row', function() {
            $(this).closest('tr').remove();
        });
    })(jQuery);
    </script>
    <?php
}
