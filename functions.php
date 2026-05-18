<?php

defined( 'ABSPATH' ) || exit;

// ─── Theme Setup ────────────────────────────────────────────────────────────

add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'primarie', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    register_nav_menus( [
        'primary' => __( 'Meniu Principal', 'primarie' ),
        'footer'  => __( 'Meniu Footer', 'primarie' ),
    ] );
} );

// ─── Enqueue Scripts & Styles ────────────────────────────────────────────────

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'primarie-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'primarie-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        [ 'primarie-style' ],
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'boxicons',
        'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css',
        [],
        '2.1.4'
    );

    wp_enqueue_script(
        'primarie-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        wp_get_theme()->get( 'Version' ),
        true
    );
} );

// ─── ACF Local JSON ──────────────────────────────────────────────────────────

add_filter( 'acf/settings/save_json', function () {
    return get_template_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );

// ─── Theme Settings ──────────────────────────────────────────────────────────

$_inc = get_template_directory() . '/inc/footer-settings.php';
if ( file_exists( $_inc ) ) require_once $_inc;

// ─── Disable Gutenberg ───────────────────────────────────────────────────────

add_filter( 'use_block_editor_for_post', '__return_false', 10 );
add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

// ─── Widgets ─────────────────────────────────────────────────────────────────

add_action( 'widgets_init', function () {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'primarie' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Adauga widget-uri aici.', 'primarie' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );
} );


/**
 * Enqueue Leaflet + custom map script DOAR pe template-ul Hartă Comună
 */
add_action( 'wp_enqueue_scripts', 'claraeliz_enqueue_harta_comuna_assets' );
function claraeliz_enqueue_harta_comuna_assets() {

    if ( ! is_page_template( 'page-homepage.php' ) ) {
        return;
    }

    // Leaflet CSS
    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        array(),
        '1.9.4'
    );

    // Leaflet JS
    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        array(),
        '1.9.4',
        true
    );

    // Script-ul propriu
    wp_enqueue_script(
        'harta-comuna-js',
        get_stylesheet_directory_uri() . '/assets/js/harta-comuna.js',
        array( 'leaflet-js' ),
        '1.0.0',
        true
    );

    // Date din PHP în JS (sigur, fără hardcoding în JS)
    wp_localize_script( 'harta-comuna-js', 'hartaComunaData', array(
        'geojsonUrl'    => get_stylesheet_directory_uri() . '/assets/geojson/nimigea.json',
        'centerLat'     => 47.2333,
        'centerLng'     => 24.3667,
        'zoom'          => 8,
        'primarieLat'   => 47.2350,
        'primarieLng'   => 24.3680,
        'primarieLabel' => __( 'Primăria Nimigea', 'claraeliz' ),
    ) );
}
