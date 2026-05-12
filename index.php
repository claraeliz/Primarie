<?php get_header(); ?>

    <?php if ( have_posts() ) : ?>

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <div class="posts-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/content', get_post_format() ); ?>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>

    <?php else : ?>

        <article class="no-results">
            <header class="entry-header">
                <h1 class="entry-title"><?php esc_html_e( 'Niciun rezultat gasit.', 'primarie' ); ?></h1>
            </header>
            <div class="entry-content">
                <p><?php esc_html_e( 'Incearca o cautare sau verifica meniurile.', 'primarie' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        </article>

    <?php endif; ?>

<?php get_footer(); ?>
