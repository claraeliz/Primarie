<?php get_header(); ?>

<div class="site-container py-12">
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'max-w-4xl' ); ?>>

            <?php $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) ); ?>
            <nav class="text-sm text-muted mb-4 flex items-center gap-2 flex-wrap">
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
            // ─── Video ───────────────────────────────────────────────────────
            $video_embed = get_field( 'video_pagina' );
            if ( $video_embed ) :
            ?>
                <div class="video-embed-wrap mb-8">
                    <?php echo $video_embed; ?>
                </div>
            <?php endif; ?>

            <?php
            // ─── Documents accordion grouped by year ─────────────────────────
            $docs = get_field( 'incarca_documente' );
            if ( $docs ) :
                if ( ! is_array( $docs ) ) {
                    $docs = [ $docs ];
                }

                // Group by year; date return format is d/m/Y → year = last 4 chars
                $docs_by_year = [];
                foreach ( $docs as $doc ) {
                    $post_id = is_object( $doc ) ? $doc->ID : (int) $doc;
                    $data    = get_field( 'data_document', $post_id );
                    $year    = ( $data && strlen( $data ) >= 4 ) ? substr( $data, -4 ) : '—';
                    $docs_by_year[ $year ][] = $doc;
                }
                krsort( $docs_by_year ); // newest year first

                $is_first = true;
                foreach ( $docs_by_year as $year => $year_docs ) :
            ?>
                <details class="doc-accordion" <?php echo $is_first ? 'open' : ''; ?>>
                    <summary class="doc-accordion__summary">
                        <span><?php echo esc_html( $year ); ?></span>
                        <svg class="doc-accordion__chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </summary>
                    <div class="doc-accordion__body">
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
                                <?php foreach ( $year_docs as $i => $doc ) :
                                    $post_id = is_object( $doc ) ? $doc->ID : (int) $doc;
                                    $numar   = get_field( 'numar_document', $post_id );
                                    $data    = get_field( 'data_document',  $post_id );
                                    $titlu   = get_field( 'titlu_document', $post_id );
                                    $fisier  = get_field( 'fisier_pdf',     $post_id );
                                    $pdf_url = is_array( $fisier ) ? ( $fisier['url'] ?? '' ) : '';
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
                </details>
            <?php
                    $is_first = false;
                endforeach;
            endif;
            ?>

        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
