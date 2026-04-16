<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="min-h-screen bg-slate-950 text-slate-50">
	<div class="mx-auto max-w-5xl px-4 py-12">
		<header class="mb-12 border-b border-slate-800 pb-8">
			<h1 class="text-4xl font-semibold tracking-tight">
				<?php bloginfo( 'name' ); ?>
			</h1>
			<p class="mt-2 text-slate-400">
				<?php bloginfo( 'description' ); ?>
			</p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-8 md:grid-cols-2">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'rounded-2xl border border-slate-800 bg-slate-900/40 p-6 shadow-sm shadow-slate-900/40' ); ?>>
						<a href="<?php the_permalink(); ?>" class="block">
							<h2 class="text-xl font-semibold tracking-tight hover:text-sky-400">
								<?php the_title(); ?>
							</h2>
						</a>
						<div class="mt-2 text-sm text-slate-400">
							<?php the_time( get_option( 'date_format' ) ); ?>
						</div>
						<div class="mt-4 text-sm text-slate-300">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="mt-4 inline-flex items-center text-sm font-medium text-sky-400 hover:text-sky-300">
							<?php esc_html_e( 'Читать далее', 'custom-manhattan-laser-theme' ); ?>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="mt-10">
				<?php
				the_posts_pagination(
					array(
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
					)
				);
				?>
			</div>
		<?php else : ?>
			<p class="text-slate-400">
				<?php esc_html_e( 'Записей пока нет.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();

