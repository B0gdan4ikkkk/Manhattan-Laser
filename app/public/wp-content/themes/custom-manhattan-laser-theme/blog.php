<?php
/**
 * Template Name: Blog
 * Страница блога: записи, фильтр по рубрикам, поиск.
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id = 0;
if ( defined( 'ML_BLOG_ARCHIVE_HOME' ) && ML_BLOG_ARCHIVE_HOME ) {
	$page_id = (int) get_option( 'page_for_posts' );
} else {
	$page_id = (int) get_queried_object_id();
}

if ( $page_id < 1 ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

get_header();

$blog_url = get_permalink( $page_id );
$page_h1  = get_the_title( $page_id ) ? get_the_title( $page_id ) : __( 'Aesthetic Blog & Insights.', 'custom-manhattan-laser-theme' );

if ( get_query_var( 'paged' ) ) {
	$paged = (int) get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$paged = (int) get_query_var( 'page' );
} else {
	$paged = 1;
}

$filter_cat = isset( $_GET['cat'] ) ? absint( $_GET['cat'] ) : 0;
$search_q   = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => $page_h1,
		'url'  => $blog_url,
	),
);

$query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 9,
	'paged'               => $paged,
	'ignore_sticky_posts' => true,
);
if ( $filter_cat ) {
	$query_args['cat'] = $filter_cat;
}
if ( $search_q !== '' ) {
	$query_args['s'] = $search_q;
}

$blog_query = new WP_Query( $query_args );

$categories = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<section
	class="blog-page pb-16 pt-0 text-[#F4EFE8] md:pb-24"
	id="blog-page-root"
	data-blog-page-id="<?php echo esc_attr( (string) $page_id ); ?>"
	data-blog-url="<?php echo esc_url( $blog_url ); ?>"
	aria-label="<?php esc_attr_e( 'Blog', 'custom-manhattan-laser-theme' ); ?>"
>
	<nav class="blog-page__breadcrumb border-b border-[#F4EFE80D] container !pb-5" aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
		<?php
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumbs_html' ) ) {
			custom_manhattan_laser_render_breadcrumbs_html( $breadcrumb_items );
		}
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumb_schema' ) ) {
			custom_manhattan_laser_render_breadcrumb_schema( $breadcrumb_items );
		}
		?>
	</nav>
	<?php
	if ( function_exists( 'custom_manhattan_laser_render_blog_archive_schema' ) ) {
		custom_manhattan_laser_render_blog_archive_schema( $page_id, $blog_query );
	}
	?>

	<div class="container">
		<div class="blog-page__header pt-5 md:pt-2">
			<h1 class="font-display text-[36px] text-[#F4EFE8] md:text-[96px] max-w-[630px]">
				Aesthetic Blog & Insights.
			</h1>

			<form
				id="blog-page-search-form"
				class="blog-page__search js-blog-search-form mt-5 flex max-w-[300px] items-center justify-between gap-3 rounded-full px-4 py-2 bg-[#F4EFE824] backdrop-blur-[6px]"
				role="search"
				method="get"
				action="<?php echo esc_url( $blog_url ); ?>"
			>
				<label class="sr-only" for="blog-search-field"><?php esc_html_e( 'Search', 'custom-manhattan-laser-theme' ); ?></label>
				<input
					id="blog-search-field"
					class="min-w-0 text-[15px] text-[#F4EFE8] placeholder:text-[#F4EFE8B2] md:text-[16px]  bg-transparent focus:border-none outline-none"
					type="search"
					name="s"
					value="<?php echo esc_attr( $search_q ); ?>"
					placeholder="<?php esc_attr_e( 'Search', 'custom-manhattan-laser-theme' ); ?>"
					autocomplete="off"
				/>
				<button type="submit" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[#F4EFE8] transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Submit search', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/search.svg' ); ?>" alt="" class="h-5 w-5 opacity-90" width="20" height="20" loading="lazy" />
				</button>
			</form>
		</div>

		<div class="blog-page__layout mt-10 grid gap-10 pb-4 lg:mt-14 lg:grid-cols-[200px_1fr] lg:gap-16 xl:grid-cols-[220px_1fr]">
			<aside class="blog-page__sidebar lg:pt-2" aria-label="<?php esc_attr_e( 'Filter by category', 'custom-manhattan-laser-theme' ); ?>">
				<p class="text-[11px] font-medium uppercase tracking-[0.14em] text-[#F4EFE8]/55">
					<?php esc_html_e( 'Filter by:', 'custom-manhattan-laser-theme' ); ?>
				</p>
				<ul class="mt-4 flex flex-wrap gap-x-4 gap-y-2 lg:flex-col lg:gap-y-1" role="list">
					<li>
						<a
							href="<?php echo esc_url( $search_q !== '' ? add_query_arg( 's', $search_q, $blog_url ) : $blog_url ); ?>"
							class="blog-page__filter-link js-blog-filter-link inline-block text-[15px] text-[#F4EFE8]/70 transition-colors hover:text-[#F4EFE8] md:text-[16px] <?php echo ! $filter_cat ? 'blog-page__filter-link--active' : ''; ?>"
							data-cat="0"
						>
							<?php esc_html_e( 'All', 'custom-manhattan-laser-theme' ); ?>
						</a>
					</li>
					<?php foreach ( $categories as $cat ) : ?>
						<?php
						$cat_args = array( 'cat' => (int) $cat->term_id );
						if ( $search_q !== '' ) {
							$cat_args['s'] = $search_q;
						}
						?>
						<li>
							<a
								href="<?php echo esc_url( add_query_arg( $cat_args, $blog_url ) ); ?>"
								class="blog-page__filter-link js-blog-filter-link inline-block text-[15px] text-[#F4EFE8]/70 transition-colors hover:text-[#F4EFE8] md:text-[16px] <?php echo $filter_cat === (int) $cat->term_id ? 'blog-page__filter-link--active' : ''; ?>"
								data-cat="<?php echo esc_attr( (string) (int) $cat->term_id ); ?>"
							>
								<?php echo esc_html( $cat->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</aside>

			<div class="blog-page__main min-w-0" id="blog-page-main">
				<?php
				$blog_loop_vars = array(
					'blog_query' => $blog_query,
					'blog_url'   => $blog_url,
					'filter_cat' => $filter_cat,
					'search_q'   => $search_q,
					'paged'      => $paged,
				);
				extract( $blog_loop_vars ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				include locate_template( 'template-parts/blog-posts-loop.php' );
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
