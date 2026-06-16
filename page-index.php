<?php
/**
 * Template Name: Pagina Index
 */
get_header();
?>

<div class="site-container py-12">
    <?php while ( have_posts() ) : the_post(); ?>

        <div class="text-center mb-10">
            <?php $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) ); ?>
            <nav class="text-sm text-muted mb-4 flex items-center justify-center gap-2 flex-wrap">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                   class="hover:text-primary transition-colors no-underline">
                    <?php bloginfo( 'name' ); ?>
                </a>
                <?php foreach ( $ancestors as $ancestor_id ) : ?>
                    <span class="text-muted/40">—</span>
                    <a href="<?php echo esc_url( get_permalink( $ancestor_id ) ); ?>"
                       class="hover:text-primary transition-colors no-underline">
                        <?php echo esc_html( get_the_title( $ancestor_id ) ); ?>
                    </a>
                <?php endforeach; ?>
                <span class="text-muted/40">—</span>
                <span class="text-ink"><?php the_title(); ?></span>
            </nav>

            <h1 class="index-page__title"><?php the_title(); ?></h1>
            <div class="index-page__bar"></div>
        </div>

        <?php if ( get_the_content() ) : ?>
            <div class="prose prose-slate max-w-none mb-10 text-ink leading-relaxed text-center">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

        <?php
        $selected_cat = isset( $_GET['cat'] ) ? absint( $_GET['cat'] ) : 0;

        // Colectează categoriile din paginile copil
        $all_children = new WP_Query( [
            'post_type'      => 'page',
            'post_parent'    => get_the_ID(),
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ] );

        $child_cats = [];
        if ( $all_children->have_posts() ) {
            while ( $all_children->have_posts() ) {
                $all_children->the_post();
                foreach ( get_the_category() as $cat ) {
                    $child_cats[ $cat->term_id ] = $cat;
                }
            }
            wp_reset_postdata();
        }

        // Afișează butoanele de filtrare dacă există categorii
        if ( ! empty( $child_cats ) ) :
        ?>
        <div class="flex flex-wrap gap-2 justify-center mb-8">
            <a href="<?php echo esc_url( remove_query_arg( 'cat' ) ); ?>"
               class="cat-filter-btn <?php echo ! $selected_cat ? 'cat-filter-btn--active' : ''; ?>">
                Toate
            </a>
            <?php foreach ( $child_cats as $cat ) : ?>
                <a href="<?php echo esc_url( add_query_arg( 'cat', $cat->term_id ) ); ?>"
                   class="cat-filter-btn <?php echo $selected_cat === $cat->term_id ? 'cat-filter-btn--active' : ''; ?>">
                    <?php echo esc_html( $cat->name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php
        // Query filtrat după categorie
        $query_args = [
            'post_type'      => 'page',
            'post_parent'    => get_the_ID(),
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];
        if ( $selected_cat ) {
            $query_args['category__in'] = [ $selected_cat ];
        }
        $children = new WP_Query( $query_args );

        if ( $children->have_posts() ) :
        ?>
        <div class="index-grid">
            <?php while ( $children->have_posts() ) : $children->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="index-card">
                    <svg class="index-card__icon" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 8.5A2.5 2.5 0 014.5 6h3.086a2.5 2.5 0 011.768.732L10.5 7.88H19.5A2.5 2.5 0 0122 10.38V17.5A2.5 2.5 0 0119.5 20h-15A2.5 2.5 0 012 17.5V8.5z"/>
                        <path d="M2 10h20" opacity=".4"/>
                    </svg>
                    <span class="index-card__title"><?php the_title(); ?></span>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
