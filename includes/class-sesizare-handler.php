<?php
/**
 * Sesizare_Handler
 *
 * Înregistrează CPT-ul "sesizare", handler-ul AJAX de trimitere a formularului,
 * validarea/sanitizarea datelor, upload-ul imaginii și notificarea pe email.
 *
 * Nu modifică nimic din tema activă — fișier independent, inclus din
 * sesizare-modal-loader.php.
 */

defined( 'ABSPATH' ) || exit;

class Sesizare_Handler {

    const NONCE_ACTION = 'sesizare_submit_action';
    const AJAX_ACTION   = 'submit_sesizare';

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_post_meta' ] );

        add_action( 'wp_ajax_' . self::AJAX_ACTION, [ $this, 'handle_ajax_submit' ] );
        add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, [ $this, 'handle_ajax_submit' ] );

        add_action( 'add_meta_boxes', [ $this, 'register_meta_box' ] );
    }

    /**
     * Opțiunile disponibile pentru "Tip sesizare" — sursă unică, folosită
     * atât pentru a randa <select>-ul, cât și pentru validarea server-side.
     *
     * @return array<string,string> slug => etichetă
     */
    public static function get_tip_options() {
        return [
            'gunoi'          => __( 'Gunoi / salubrizare', 'primarie' ),
            'infrastructura' => __( 'Infrastructură / drumuri', 'primarie' ),
            'iluminat'       => __( 'Iluminat public', 'primarie' ),
            'spatii-verzi'   => __( 'Spații verzi', 'primarie' ),
            'altele'         => __( 'Altele', 'primarie' ),
        ];
    }

    /**
     * Tipuri MIME de imagine acceptate — sursă unică, folosită și de JS
     * (vezi sesizare-modal-loader.php → wp_localize_script).
     *
     * @return array<string,string> extensie => mime type
     */
    public static function get_allowed_image_types() {
        return [
            'jpg|jpeg' => 'image/jpeg',
            'png'      => 'image/png',
            'gif'      => 'image/gif',
            'webp'     => 'image/webp',
        ];
    }

    public static function get_max_file_size() {
        return 5 * 1024 * 1024; // 5MB
    }

    // ─── Custom Post Type ────────────────────────────────────────────────────

    public function register_post_type() {
        register_post_type( 'sesizare', [
            'labels' => [
                'name'               => __( 'Sesizări', 'primarie' ),
                'singular_name'      => __( 'Sesizare', 'primarie' ),
                'add_new_item'       => __( 'Adaugă sesizare', 'primarie' ),
                'edit_item'          => __( 'Editează sesizarea', 'primarie' ),
                'view_item'          => __( 'Vizualizează sesizarea', 'primarie' ),
                'search_items'       => __( 'Caută sesizări', 'primarie' ),
                'not_found'          => __( 'Nu au fost găsite sesizări.', 'primarie' ),
                'all_items'          => __( 'Toate sesizările', 'primarie' ),
                'menu_name'          => __( 'Sesizări', 'primarie' ),
            ],
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_admin_bar'  => false,
            'show_in_rest'       => false,
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'supports'           => [ 'title', 'editor' ],
            'has_archive'        => false,
            'menu_icon'          => 'dashicons-flag',
            'menu_position'      => 26,
        ] );
    }

    public function register_post_meta() {
        $fields = [
            'sesizare_lat'        => 'string',
            'sesizare_lng'        => 'string',
            'sesizare_tip'        => 'string',
            'sesizare_nume'       => 'string',
            'sesizare_email'      => 'string',
            'sesizare_telefon'    => 'string',
            'sesizare_imagine_id' => 'integer',
        ];

        foreach ( $fields as $key => $type ) {
            register_post_meta( 'sesizare', $key, [
                'single'       => true,
                'type'         => $type,
                'show_in_rest' => false,
            ] );
        }
    }

    public function register_meta_box() {
        add_meta_box(
            'sesizare-detalii',
            __( 'Detalii sesizare', 'primarie' ),
            [ $this, 'render_meta_box' ],
            'sesizare',
            'side',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        $lat      = get_post_meta( $post->ID, 'sesizare_lat', true );
        $lng      = get_post_meta( $post->ID, 'sesizare_lng', true );
        $tip      = get_post_meta( $post->ID, 'sesizare_tip', true );
        $nume     = get_post_meta( $post->ID, 'sesizare_nume', true );
        $email    = get_post_meta( $post->ID, 'sesizare_email', true );
        $telefon  = get_post_meta( $post->ID, 'sesizare_telefon', true );
        $img_id   = get_post_meta( $post->ID, 'sesizare_imagine_id', true );
        $tipuri   = self::get_tip_options();
        $tip_label = $tipuri[ $tip ] ?? $tip;

        echo '<p><strong>' . esc_html__( 'Tip:', 'primarie' ) . '</strong> ' . esc_html( $tip_label ) . '</p>';
        echo '<p><strong>' . esc_html__( 'Nume:', 'primarie' ) . '</strong> ' . esc_html( $nume ) . '</p>';
        echo '<p><strong>' . esc_html__( 'Email:', 'primarie' ) . '</strong> ' . esc_html( $email ) . '</p>';
        echo '<p><strong>' . esc_html__( 'Telefon:', 'primarie' ) . '</strong> ' . esc_html( $telefon ) . '</p>';

        if ( $lat !== '' && $lng !== '' ) {
            $maps_url = 'https://www.google.com/maps?q=' . rawurlencode( $lat . ',' . $lng );
            echo '<p><strong>' . esc_html__( 'Locație:', 'primarie' ) . '</strong> <a href="' . esc_url( $maps_url ) . '" target="_blank" rel="noopener">' . esc_html( $lat . ', ' . $lng ) . '</a></p>';
        }

        if ( $img_id ) {
            echo wp_get_attachment_image( $img_id, 'medium', false, [ 'style' => 'max-width:100%;height:auto;' ] );
        }
    }

    // ─── AJAX ────────────────────────────────────────────────────────────────

    public function handle_ajax_submit() {
        if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'Sesiune expirată. Reîncarcă pagina și încearcă din nou.', 'primarie' ) ] );
        }

        $errors = [];

        // ─ Locație ─
        $lat = isset( $_POST['sesizare_lat'] ) ? (float) $_POST['sesizare_lat'] : null;
        $lng = isset( $_POST['sesizare_lng'] ) ? (float) $_POST['sesizare_lng'] : null;
        if ( $lat === null || $lng === null || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180 ) {
            $errors[] = __( 'Locația selectată pe hartă este invalidă.', 'primarie' );
        }

        // ─ Tip sesizare ─
        $tip_options = self::get_tip_options();
        $tip = isset( $_POST['sesizare_tip'] ) ? sanitize_text_field( wp_unslash( $_POST['sesizare_tip'] ) ) : '';
        if ( ! isset( $tip_options[ $tip ] ) ) {
            $errors[] = __( 'Tipul sesizării este invalid.', 'primarie' );
        }

        // ─ Descriere ─
        $descriere = isset( $_POST['sesizare_descriere'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sesizare_descriere'] ) ) : '';
        if ( mb_strlen( $descriere ) < 20 ) {
            $errors[] = __( 'Descrierea trebuie să aibă minimum 20 de caractere.', 'primarie' );
        }

        // ─ Date de contact ─
        $nume = isset( $_POST['sesizare_nume'] ) ? sanitize_text_field( wp_unslash( $_POST['sesizare_nume'] ) ) : '';
        if ( $nume === '' ) {
            $errors[] = __( 'Numele este obligatoriu.', 'primarie' );
        }

        $email = isset( $_POST['sesizare_email'] ) ? sanitize_email( wp_unslash( $_POST['sesizare_email'] ) ) : '';
        if ( ! is_email( $email ) ) {
            $errors[] = __( 'Adresa de email este invalidă.', 'primarie' );
        }

        $telefon = isset( $_POST['sesizare_telefon'] ) ? sanitize_text_field( wp_unslash( $_POST['sesizare_telefon'] ) ) : '';
        if ( ! preg_match( '/^[+]?[0-9\s\-\(\)]{7,15}$/', $telefon ) ) {
            $errors[] = __( 'Numărul de telefon este invalid.', 'primarie' );
        }

        // ─ Consimțământ ─
        $consimtamant = isset( $_POST['sesizare_consimtamant'] ) ? sanitize_text_field( wp_unslash( $_POST['sesizare_consimtamant'] ) ) : '';
        if ( $consimtamant !== '1' ) {
            $errors[] = __( 'Trebuie să fii de acord cu politica de prelucrare a datelor.', 'primarie' );
        }

        if ( $errors ) {
            wp_send_json_error( [ 'message' => implode( ' ', $errors ) ] );
        }

        // ─ Creare post ─
        $post_id = wp_insert_post( [
            'post_type'    => 'sesizare',
            'post_status'  => 'publish',
            'post_title'   => sprintf( '%s — %s', $tip_options[ $tip ], date_i18n( 'd.m.Y H:i' ) ),
            'post_content' => $descriere,
        ], true );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => __( 'A apărut o eroare la salvarea sesizării. Încearcă din nou.', 'primarie' ) ] );
        }

        update_post_meta( $post_id, 'sesizare_lat', $lat );
        update_post_meta( $post_id, 'sesizare_lng', $lng );
        update_post_meta( $post_id, 'sesizare_tip', $tip );
        update_post_meta( $post_id, 'sesizare_nume', $nume );
        update_post_meta( $post_id, 'sesizare_email', $email );
        update_post_meta( $post_id, 'sesizare_telefon', $telefon );

        // ─ Upload imagine (opțional) ─
        $attachment_id = 0;
        if ( ! empty( $_FILES['sesizare_imagine'] ) && ! empty( $_FILES['sesizare_imagine']['name'] ) ) {
            $attachment_id = $this->handle_image_upload( $post_id );
            if ( is_wp_error( $attachment_id ) ) {
                wp_send_json_error( [ 'message' => $attachment_id->get_error_message() ] );
            }
            if ( $attachment_id ) {
                update_post_meta( $post_id, 'sesizare_imagine_id', $attachment_id );
                set_post_thumbnail( $post_id, $attachment_id );
            }
        }

        $this->send_notification_email( $post_id, [
            'tip'       => $tip_options[ $tip ],
            'nume'      => $nume,
            'email'     => $email,
            'telefon'   => $telefon,
            'descriere' => $descriere,
            'lat'       => $lat,
            'lng'       => $lng,
        ] );

        wp_send_json_success( [
            'message' => __( 'Sesizarea a fost trimisă cu succes. Îți mulțumim!', 'primarie' ),
        ] );
    }

    /**
     * Validează tipul MIME server-side și încarcă imaginea prin wp_handle_upload.
     *
     * @param int $post_id Postul "sesizare" de care se va atașa imaginea.
     * @return int|WP_Error ID-ul attachment-ului sau WP_Error.
     */
    private function handle_image_upload( $post_id ) {
        $file = $_FILES['sesizare_imagine'];

        if ( ! empty( $file['error'] ) && $file['error'] !== UPLOAD_ERR_OK ) {
            return new WP_Error( 'upload_error', __( 'Eroare la încărcarea imaginii.', 'primarie' ) );
        }

        if ( $file['size'] > self::get_max_file_size() ) {
            return new WP_Error( 'file_too_large', __( 'Imaginea depășește dimensiunea maximă admisă (5MB).', 'primarie' ) );
        }

        $filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], self::get_allowed_image_types() );
        if ( empty( $filetype['ext'] ) || empty( $filetype['type'] ) ) {
            return new WP_Error( 'invalid_type', __( 'Tipul fișierului nu este permis. Sunt acceptate doar imagini (jpg, png, gif, webp).', 'primarie' ) );
        }

        // Verificare suplimentară — fișierul trebuie să fie efectiv o imagine validă.
        if ( @getimagesize( $file['tmp_name'] ) === false ) {
            return new WP_Error( 'invalid_image', __( 'Fișierul încărcat nu este o imagine validă.', 'primarie' ) );
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $upload = wp_handle_upload( $file, [ 'test_form' => false ] );

        if ( isset( $upload['error'] ) ) {
            return new WP_Error( 'upload_error', $upload['error'] );
        }

        $attachment_id = wp_insert_attachment( [
            'post_mime_type' => $upload['type'],
            'post_title'     => sanitize_file_name( $file['name'] ),
            'post_status'    => 'inherit',
        ], $upload['file'], $post_id );

        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }

        $attach_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $attach_data );

        return $attachment_id;
    }

    /**
     * Trimite o notificare pe email către adresa configurată.
     * Adresa poate fi schimbată via filter-ul `sesizare_notification_email`.
     */
    private function send_notification_email( $post_id, $data ) {
        $to = apply_filters( 'sesizare_notification_email', get_option( 'admin_email' ) );

        $subject = sprintf( __( '[Sesizare nouă] %s', 'primarie' ), $data['tip'] );

        $body  = __( 'A fost trimisă o nouă sesizare prin formularul de pe site:', 'primarie' ) . "\n\n";
        $body .= __( 'Tip:', 'primarie' ) . ' ' . $data['tip'] . "\n";
        $body .= __( 'Nume:', 'primarie' ) . ' ' . $data['nume'] . "\n";
        $body .= __( 'Email:', 'primarie' ) . ' ' . $data['email'] . "\n";
        $body .= __( 'Telefon:', 'primarie' ) . ' ' . $data['telefon'] . "\n";
        $body .= __( 'Locație:', 'primarie' ) . ' https://www.google.com/maps?q=' . $data['lat'] . ',' . $data['lng'] . "\n\n";
        $body .= __( 'Descriere:', 'primarie' ) . "\n" . $data['descriere'] . "\n\n";
        $body .= __( 'Vezi sesizarea în panoul de administrare:', 'primarie' ) . ' ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";

        // WordPress generează implicit From-ul ca "wordpress@{domeniul-site-ului}".
        // Pe instalări locale (ex. domeniu "localhost"), adresa rezultată e respinsă
        // de validarea PHPMailer ("Invalid address"), iar wp_mail() eșuează silențios.
        // Forțăm aici From-ul la admin_email (garantat valid de WordPress), doar
        // pentru acest email — nu schimbăm comportamentul global de mail al site-ului.
        add_filter( 'wp_mail_from', [ $this, 'filter_mail_from' ] );
        wp_mail( $to, $subject, $body );
        remove_filter( 'wp_mail_from', [ $this, 'filter_mail_from' ] );
    }

    public function filter_mail_from( $original_email ) {
        $admin_email = get_option( 'admin_email' );
        return is_email( $admin_email ) ? $admin_email : $original_email;
    }
}

new Sesizare_Handler();
