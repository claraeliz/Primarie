<?php

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" class="post-thumbnail" aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail( 'medium_large' ); ?>
        </a>
    <?php endif; ?>

    <div class="post-card-body">
        <header class="entry-header">
            <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
            <div class="entry-meta">
                <span class="posted-on"><?php echo get_the_date(); ?></span>
            </div>
        </header>

        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>

        <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php esc_html_e( 'Citeste mai mult', 'primarie' ); ?>
            </a>
        </footer>
    </div>

</article>
