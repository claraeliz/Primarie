<?php get_header(); ?>

<div class="site-container py-12">
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'max-w-4xl' ); ?>>

            <h1 class="text-[2rem] font-extrabold text-ink tracking-[-0.02em] mb-6">
                <?php the_title(); ?>
            </h1>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="mb-6 overflow-hidden">
                    <?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-auto' ] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( get_the_content() ) : ?>
                <div class="prose prose-slate max-w-none mb-8 text-ink leading-relaxed">
                    <?php the_content(); ?>
                    <?php wp_link_pages(); ?>
                </div>
            <?php endif; ?>

            <?php
            // ─── Documents table ─────────────────────────────────────────────
            $docs = get_field( 'incarca_documente' );
            // ACF Post Object poate returna un singur obiect sau un array
            if ( $docs ) :
                if ( ! is_array( $docs ) ) {
                    $docs = [ $docs ];
                }
            ?>
                <div class="overflow-hidden border border-stroke shadow-sm">
                    <table class="w-full" style="border-collapse:separate;border-spacing:0;">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="text-left px-5 py-[.65rem] text-base font-bold uppercase tracking-[.1em] w-[110px]">
                                    <?php esc_html_e( 'Număr', 'primarie' ); ?>
                                </th>
                                <th class="text-left px-5 py-[.65rem] text-base font-bold uppercase tracking-[.1em] w-[120px]">
                                    <?php esc_html_e( 'Data', 'primarie' ); ?>
                                </th>
                                <th class="text-left px-5 py-[.65rem] text-base font-bold uppercase tracking-[.1em]">
                                    <?php esc_html_e( 'Titlu', 'primarie' ); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $docs as $i => $doc ) :
                                $post_id   = is_object( $doc ) ? $doc->ID : (int) $doc;
                                $numar     = get_field( 'numar_document', $post_id );
                                $data      = get_field( 'data_document',  $post_id );
                                $titlu     = get_field( 'titlu_document', $post_id );
                                $fisier    = get_field( 'fisier_pdf',     $post_id );
                                $pdf_url   = is_array( $fisier ) ? ( $fisier['url'] ?? '' ) : '';
                            ?>
                                <tr class="doc-row <?php echo $i % 2 === 0 ? 'doc-row-white' : 'doc-row-gray'; ?>">
                                    <td class="doc-cell text-base text-muted">
                                        <?php echo esc_html( $numar ?: '—' ); ?>
                                    </td>
                                    <td class="doc-cell text-base text-muted whitespace-nowrap">
                                        <?php echo esc_html( $data ?: '—' ); ?>
                                    </td>
                                    <td class="doc-cell text-base">
                                        <?php if ( $pdf_url ) : ?>
                                            <a href="<?php echo esc_url( $pdf_url ); ?>"
                                               target="_blank" rel="noopener noreferrer"
                                               class="text-primary no-underline font-medium hover:text-accent hover:underline transition-colors leading-[1.4]">
                                                <?php echo esc_html( $titlu ?: get_the_title( $post_id ) ); ?>
                                            </a>
                                        <?php else : ?>
                                            <span class="text-ink font-medium">
                                                <?php echo esc_html( $titlu ?: get_the_title( $post_id ) ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
