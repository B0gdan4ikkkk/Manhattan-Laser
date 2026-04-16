<?php
/**
 * Одиночная запись (post) и прочие singular без своего шаблона.
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_singular( 'post' ) ) {
	get_template_part( 'template-parts/content', 'single-post' );
	return;
}

get_header();
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<main class="bg-[#110D0C] px-5 py-12 text-[#F4EFE8] md:py-16">
			<div class="container max-w-3xl">
				<h1 class="font-display text-2xl text-[#F4EFE8]"><?php the_title(); ?></h1>
				<div class="blog-single__content mt-6 text-[#F4EFE8]/80">
					<?php the_content(); ?>
				</div>
			</div>
		</main>
		<?php
	}
}
get_footer();
