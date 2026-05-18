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

// ─── Înregistrare opțiune ─────────────────────────────────────────────────────

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
} );

// ─── Enqueue media uploader doar pe pagina noastră ───────────────────────────

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_primarie-theme-options' !== $hook ) return;
    wp_enqueue_media();
} );

// ─── Render pagina de setări ─────────────────────────────────────────────────

function primarie_render_footer_settings() {
    $rows = get_option( 'primarie_footer_carousel', [] );
    if ( ! is_array( $rows ) ) $rows = [];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Footer Settings', 'primarie' ); ?></h1>

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
                    <?php foreach ( $rows as $i => $row ) :
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
    </div>

    <script>
    (function($) {
        // Re-indexează rândurile înainte de submit
        $('#footer-settings-form').on('submit', function() {
            $('#carousel-tbody .carousel-row').each(function(i) {
                $(this).find('[name]').each(function() {
                    this.name = this.name.replace(/\[\d+\]/, '[' + i + ']');
                });
            });
        });

        // Adaugă rând nou
        $('#carousel-add-row').on('click', function() {
            var idx = $('#carousel-tbody .carousel-row').length;
            $('#carousel-tbody').append(
                '<tr class="carousel-row">' +
                    '<td>' +
                        '<input type="hidden" name="primarie_footer_carousel[' + idx + '][img_id]" value="" class="carousel-img-id">' +
                        '<img src="" class="carousel-img-preview" style="max-height:56px;width:auto;display:none;margin-bottom:6px;">' +
                        '<button type="button" class="button carousel-select-img"><?php esc_js( esc_html_e( 'Alege imagine', 'primarie' ) ); ?></button>' +
                    '</td>' +
                    '<td><input type="url" name="primarie_footer_carousel[' + idx + '][link]" value="" class="regular-text" placeholder="https://"></td>' +
                    '<td><button type="button" class="button carousel-remove-row" style="color:#b32d2e"><?php esc_js( esc_html_e( 'Șterge', 'primarie' ) ); ?></button></td>' +
                '</tr>'
            );
        });

        // Șterge rând
        $(document).on('click', '.carousel-remove-row', function() {
            $(this).closest('tr').remove();
        });

        // WP Media uploader
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
    })(jQuery);
    </script>
    <?php
}
