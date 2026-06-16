<?php
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
    register_taxonomy_for_object_type( 'category', 'page' );
} );
