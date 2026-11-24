<?php
/**
 * Title: Presentación del blog
 * Slug: capitulo-abierto-blog/hero
 * Categories: capitulo
 * Inserter: true
 */
$hero = esc_url(get_theme_file_uri('assets/images/hero.jpg'));
?>
<!-- wp:columns {"className":"ca-wrap ca-hero","verticalAlignment":"center"} --><div class="wp-block-columns are-vertically-aligned-center ca-wrap ca-hero"><!-- wp:column {"width":"53%"} --><div class="wp-block-column" style="flex-basis:53%"><!-- wp:paragraph {"className":"ca-eyebrow"} --><p class="ca-eyebrow">EL BLOG DE CAPÍTULO ABIERTO</p><!-- /wp:paragraph --><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Lecturas, ideas<br>y conversaciones</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"ca-subtitle"} --><p class="ca-subtitle">Un espacio para descubrir libros, compartir reflexiones y seguir leyendo juntos.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"47%","className":"ca-hero-image"} --><div class="wp-block-column ca-hero-image" style="flex-basis:47%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="<?php echo $hero; ?>" alt="Chica escribiendo en un ordenador portátil"/></figure><!-- /wp:image --></div><!-- /wp:column --></div><!-- /wp:columns -->