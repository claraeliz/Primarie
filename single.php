<?php get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                <div class="entry-meta">
                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                    <span class="byline"><?php the_author(); ?></span>
                </div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </div>

            <footer class="entry-footer">
                <?php the_tags( '<div class="tags-links">', ', ', '</div>' ); ?>
            </footer>
        </article>

        <?php the_post_navigation(); ?>

        <?php if ( comments_open() || get_comments_number() ) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>

    <?php endwhile; ?>

<?php get_footer(); ?>
