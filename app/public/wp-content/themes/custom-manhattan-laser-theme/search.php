<?php
/**
 * Результаты поиска по сайту.
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$blog_url = function_exists( 'custom_manhattan_laser_get_blog_page_url' )
	? custom_manhattan_laser_get_blog_page_url()
	: home_url( '/' );

$search_results_url = add_query_arg( 's', get_search_query(), home_url( '/' ) );
$breadcrumb_items   = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => __( 'Search', 'custom-manhattan-laser-theme' ),
		'url'  => $search_results_url,
	),
);
?>

<section class="blog-page bg-[#110D0C] pb-16 text-[#F4EFE8] md:pb-24" aria-label="<?php esc_attr_e( 'Search results', 'custom-manhattan-laser-theme' ); ?>">
	<nav class="blog-page__breadcrumb border-b border-[#F4EFE80D] container !pb-5 !pt-6 md:!pt-8" aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
		<?php
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumbs_html' ) ) {
			custom_manhattan_laser_render_breadcrumbs_html( $breadcrumb_items );
		}
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumb_schema' ) ) {
			custom_manhattan_laser_render_breadcrumb_schema( $breadcrumb_items );
		}
		?>
	</nav>

	<div class="container !max-w-[1280px] px-5">
		<header class="pt-8 md:pt-12">
			<h1 class="font-display text-[32px] font-normal leading-tight text-[#F4EFE8] md:text-[48px]">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search results for "%s"', 'custom-manhattan-laser-theme' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
		</header>

		<div class="mt-10">
			<?php if ( have_posts() ) : ?>
				<div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-12">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'blog-card flex flex-col' ); ?>>
							<a href="<?php the_permalink(); ?>" class="blog-card__thumb-link block aspect-square w-full overflow-hidden bg-[#1a1614] md:rounded-sm">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(
										'large',
										array(
											'class'   => 'h-full w-full object-cover transition-transform duration-500 ease-out hover:scale-[1.03]',
											'loading' => 'lazy',
											'alt'     => esc_attr( get_the_title() ),
										)
									);
								} else {
									echo '<span class="block h-full w-full bg-[#1f1b18]" aria-hidden="true"></span>';
								}
								?>
							</a>
							<time class="mt-4 block text-[13px] text-[#F4EFE8]/50 md:text-[14px]" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
								<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
							</time>
							<h2 class="mt-2 font-display text-[22px] font-normal leading-snug text-[#F4EFE8] md:text-[24px]">
								<a href="<?php the_permalink(); ?>" class="transition-opacity hover:opacity-85"><?php the_title(); ?></a>
							</h2>
							<p class="blog-card__excerpt mt-3 line-clamp-4 text-[14px] leading-relaxed text-[#F4EFE8]/65 md:text-[15px]">
								<?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?>
							</p>
							<a href="<?php the_permalink(); ?>" class="mt-4 inline-block text-[14px] text-[#F4EFE8] underline underline-offset-4">
								<?php esc_html_e( 'learn more', 'custom-manhattan-laser-theme' ); ?>
							</a>
						</article>
					<?php endwhile; ?>
				</div>
				<div class="mt-14">
					<?php the_posts_pagination( array( 'prev_text' => '←', 'next_text' => '→' ) ); ?>
				</div>
			<?php else : ?>
				<p class="mt-6 text-[16px] text-[#F4EFE8]/60">
					<?php esc_html_e( 'Nothing matched your search.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			<?php endif; ?>
		</div>

		<p class="mt-10 pb-8">
			<a href="<?php echo esc_url( $blog_url ); ?>" class="text-[15px] text-[#F4EFE8] underline underline-offset-4"><?php esc_html_e( 'View blog', 'custom-manhattan-laser-theme' ); ?></a>
		</p>
	</div>
</section>

<?php
get_footer();
