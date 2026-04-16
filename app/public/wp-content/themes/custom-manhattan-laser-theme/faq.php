<?php
/**
 * Template Name: FAQ
 * Страница FAQ: категории (якоря), аккордеон, schema.org FAQPage.
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$page_url = get_permalink();
$page_title = get_the_title() ? get_the_title() : __( 'FAQ', 'custom-manhattan-laser-theme' );
$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => $page_title,
		'url'  => $page_url ? $page_url : '',
	),
);

$faq_terms = get_terms(
	array(
		'taxonomy'   => 'faq_category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $faq_terms ) ) {
	$faq_terms = array();
}

$faq_query_base = array(
	'post_type'      => 'faq_item',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
	),
);

$uncategorized_posts = get_posts(
	array_merge(
		$faq_query_base,
		array(
			'tax_query' => array(
				array(
					'taxonomy' => 'faq_category',
					'operator' => 'NOT EXISTS',
				),
			),
		)
	)
);

$category_sections = array();
foreach ( $faq_terms as $term ) {
	$posts = get_posts(
		array_merge(
			$faq_query_base,
			array(
				'tax_query' => array(
					array(
						'taxonomy' => 'faq_category',
						'field'    => 'term_id',
						'terms'    => (int) $term->term_id,
					),
				),
			)
		)
	);
	if ( ! empty( $posts ) ) {
		$category_sections[] = array(
			'term'  => $term,
			'posts' => $posts,
		);
	}
}

$schema_entities = array();
$schema_seen_ids   = array();
foreach ( $category_sections as $section ) {
	foreach ( $section['posts'] as $post_obj ) {
		if ( isset( $schema_seen_ids[ $post_obj->ID ] ) ) {
			continue;
		}
		$schema_seen_ids[ $post_obj->ID ] = true;
		$q = get_the_title( $post_obj );
		$a = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_obj ) );
		$a = preg_replace( '/\s+/u', ' ', $a );
		$a = trim( $a );
		if ( '' !== $q && '' !== $a ) {
			$schema_entities[] = array(
				'q' => $q,
				'a' => $a,
			);
		}
	}
}
foreach ( $uncategorized_posts as $post_obj ) {
	if ( isset( $schema_seen_ids[ $post_obj->ID ] ) ) {
		continue;
	}
	$schema_seen_ids[ $post_obj->ID ] = true;
	$q = get_the_title( $post_obj );
	$a = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_obj ) );
	$a = preg_replace( '/\s+/u', ' ', $a );
	$a = trim( $a );
	if ( '' !== $q && '' !== $a ) {
		$schema_entities[] = array(
			'q' => $q,
			'a' => $a,
		);
	}
}

$faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(),
);
foreach ( $schema_entities as $row ) {
	$faq_schema['mainEntity'][] = array(
		'@type'          => 'Question',
		'name'           => wp_strip_all_tags( $row['q'] ),
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $row['a'],
		),
	);
}

$has_content = ! empty( $category_sections ) || ! empty( $uncategorized_posts );
$jump_links  = array();
?>

<section
	class="faq-page bg-[#18100D] pb-16 md:pb-24"
	aria-label="<?php echo esc_attr( $page_title ); ?>"
>
	<nav
		class="!pb-5 text-[#F4EFE8] border-b border-[#F4EFE80D] container"
		aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>"
	>
		<?php
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumbs_html' ) ) {
			custom_manhattan_laser_render_breadcrumbs_html( $breadcrumb_items );
		}
		?>
	</nav>
	<?php
	if ( function_exists( 'custom_manhattan_laser_render_breadcrumb_schema' ) ) {
		custom_manhattan_laser_render_breadcrumb_schema( $breadcrumb_items );
	}
	?>

	<div class="container !pt-5">
		<h1 class="font-display text-left text-[48px] leading-[100%] text-[#F4EFE8] md:text-[96px]">
			<?php echo esc_html( __( 'FAQ', 'custom-manhattan-laser-theme' ) ); ?>
		</h1>

		<?php if ( ! $has_content ) : ?>
			<p class="mt-10 max-w-xl text-[16px] text-[#F4EFE8]/80">
				<?php esc_html_e( 'No questions have been added yet. Add FAQ entries and categories in the WordPress admin.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php else : ?>
			<?php if ( ! empty( $faq_schema['mainEntity'] ) ) : ?>
				<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
			<?php endif; ?>

			<?php
			$jump_links = array();
			foreach ( $category_sections as $section ) {
				$term = $section['term'];
				$jump_links[] = array(
					'id'    => 'faq-category-' . sanitize_title( $term->slug ),
					'label' => $term->name,
				);
			}
			if ( ! empty( $uncategorized_posts ) ) {
				$jump_links[] = array(
					'id'    => 'faq-category-other',
					'label' => __( 'Other', 'custom-manhattan-laser-theme' ),
				);
			}

			$global_fi = 0;
			?>

			<div class="relative mt-5 grid grid-cols-1 items-start gap-8 md:grid-cols-2 md:gap-10 lg:mt-5 lg:gap-16">
				<aside
					class="faq-page__jump md:sticky z-[5] max-h-[min(100vh-6rem,28rem)] w-full shrink-0 overflow-y-auto overscroll-contain rounded-lg bg-[#18100D]/95 py-3 pl-0 pr-1 backdrop-blur-sm md:top-28 md:max-h-[min(100vh-7rem,32rem)] lg:top-32 lg:max-h-[calc(100vh-9rem)] lg:bg-transparent lg:py-0 lg:backdrop-blur-none"
					aria-label="<?php esc_attr_e( 'FAQ categories', 'custom-manhattan-laser-theme' ); ?>"
				>
					<p class="text-[16px] uppercase text-[#F4EFE8BF]">
						<?php esc_html_e( 'Jump to', 'custom-manhattan-laser-theme' ); ?>
					</p>
					<ul class="faq-jump-nav mt-5 space-y-2.5 text-[16px] text-[#F4EFE8]" role="list">
						<?php foreach ( $jump_links as $jl ) : ?>
							<li>
								<a
									class="faq-jump-nav__link block border-b border-transparent transition-colors hover:text-[#F4EFE8] aria-[current=true]:underline aria-[current=true]:decoration-[#F4EFE8] aria-[current=true]:underline-offset-4 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F4EFE8]/40"
									href="#<?php echo esc_attr( $jl['id'] ); ?>"
								>
									<?php echo esc_html( $jl['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</aside>

				<div class="min-w-0 space-y-12">
					<?php foreach ( $category_sections as $section ) : ?>
						<?php
						$term        = $section['term'];
						$cat_posts   = $section['posts'];
						$section_id  = 'faq-category-' . sanitize_title( $term->slug );
						?>
						<section
							id="<?php echo esc_attr( $section_id ); ?>"
							class="faq-page__category"
							aria-labelledby="<?php echo esc_attr( $section_id ); ?>-heading"
						>
							<h2 id="<?php echo esc_attr( $section_id ); ?>-heading" class="font-display text-[32px] leading-[110%] text-[#F4EFE8] md:text-[48px]">
								<?php echo esc_html( $term->name ); ?>
							</h2>
							<div class="faq-section__accordion mt-6 min-w-0 w-full md:max-w-[720px]">
								<?php
								foreach ( $cat_posts as $post_obj ) :
									setup_postdata( $post_obj );
									?>
									<details
										class="faq-item group border-b border-[#F4EFE80D] py-5"
										name="faq-page"
										<?php echo 0 === $global_fi ? 'open' : ''; ?>
									>
										<summary class="faq-item__summary flex w-full cursor-pointer list-none items-start justify-between gap-6 bg-transparent p-0 text-left text-[#F4EFE8]">
											<span class="min-w-0 flex-1 font-display text-[18px] leading-snug md:text-[22px] lg:text-[24px]"><?php echo esc_html( get_the_title( $post_obj ) ); ?></span>
											<span class="faq-item__icons relative mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center text-[26px] font-light leading-none" aria-hidden="true">
												<span class="faq-item__icon-plus inline-flex items-center justify-center">+</span>
											</span>
										</summary>
										<div class="faq-item__answer pr-2 text-[15px] text-[#F4EFE8]/75 md:text-[16px] md:leading-relaxed md:pr-8 mt-4 entry-content-faq">
											<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $post_obj ) ); ?>
										</div>
									</details>
									<?php
									++$global_fi;
								endforeach;
								?>
							</div>
						</section>
					<?php endforeach; ?>

					<?php if ( ! empty( $uncategorized_posts ) ) : ?>
						<section
							id="faq-category-other"
							class="faq-page__category scroll-mt-28"
							aria-labelledby="faq-category-other-heading"
						>
							<h2 id="faq-category-other-heading" class="font-display text-[28px] leading-[110%] text-[#F4EFE8] md:text-[36px] lg:text-[40px]">
								<?php esc_html_e( 'Other', 'custom-manhattan-laser-theme' ); ?>
							</h2>
							<div class="faq-section__accordion mt-6 min-w-0 w-full md:max-w-[720px]">
								<?php
								foreach ( $uncategorized_posts as $post_obj ) :
									?>
									<details
										class="faq-item group border-b border-[#F4EFE80D] py-5"
										name="faq-page"
										<?php echo 0 === $global_fi ? 'open' : ''; ?>
									>
										<summary class="faq-item__summary flex w-full cursor-pointer list-none items-start justify-between gap-6 bg-transparent p-0 text-left text-[#F4EFE8]">
											<span class="min-w-0 flex-1 font-display text-[18px] leading-snug md:text-[22px] lg:text-[24px]"><?php echo esc_html( get_the_title( $post_obj ) ); ?></span>
											<span class="faq-item__icons relative mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center text-[26px] font-light leading-none" aria-hidden="true">
												<span class="faq-item__icon-plus inline-flex items-center justify-center">+</span>
											</span>
										</summary>
										<div class="faq-item__answer pr-2 text-[15px] text-[#F4EFE8]/75 md:text-[16px] md:leading-relaxed md:pr-8 mt-4 entry-content-faq">
											<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $post_obj ) ); ?>
										</div>
									</details>
									<?php
									++$global_fi;
								endforeach;
								?>
							</div>
						</section>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php if ( $has_content && ! empty( $jump_links ) ) : ?>
<script>
(function () {
	var sections = document.querySelectorAll('.faq-page__category');
	var links = document.querySelectorAll('.faq-jump-nav__link');
	if (!sections.length || !links.length) return;

	function getScrollOffset() {
		var header = document.querySelector('.header');
		var extra = 28;
		if (header) {
			return Math.round(header.getBoundingClientRect().height) + extra;
		}
		return 120;
	}

	function idFromHref(a) {
		return (a.getAttribute('href') || '').replace(/^#/, '');
	}

	function setActiveById(activeId) {
		if (!activeId) return;
		links.forEach(function (a) {
			a.setAttribute('aria-current', idFromHref(a) === activeId ? 'true' : 'false');
		});
	}

	function updateFromScroll() {
		var offset = getScrollOffset();
		var scrollY = window.scrollY || document.documentElement.scrollTop;
		var trigger = scrollY + offset;
		var activeId = sections[0] ? sections[0].id : '';
		var i;
		for (i = 0; i < sections.length; i++) {
			var sec = sections[i];
			var top = sec.getBoundingClientRect().top + window.scrollY;
			if (trigger >= top - 2) {
				activeId = sec.id;
			}
		}
		setActiveById(activeId);
	}

	var ticking = false;
	function onScroll() {
		if (ticking) return;
		ticking = true;
		requestAnimationFrame(function () {
			ticking = false;
			updateFromScroll();
		});
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', function () {
		updateFromScroll();
	});
	window.addEventListener('hashchange', function () {
		var h = (window.location.hash || '').replace(/^#/, '');
		if (h) {
			setActiveById(h);
		} else {
			updateFromScroll();
		}
	});

	links.forEach(function (a) {
		a.addEventListener('click', function () {
			var id = idFromHref(a);
			window.requestAnimationFrame(function () {
				window.setTimeout(function () {
					setActiveById(id);
				}, 80);
			});
		});
	});

	function init() {
		if (window.location.hash) {
			setActiveById(window.location.hash.replace(/^#/, ''));
		}
		updateFromScroll();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
	window.addEventListener('load', updateFromScroll);
})();
</script>
<?php endif; ?>

<?php
get_footer();
