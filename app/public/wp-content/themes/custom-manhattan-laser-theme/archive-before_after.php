<?php
/**
 * Архив кейсов Before / After (ЧПУ: обычно /before-after/).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_type_obj = get_post_type_object( 'before_after' );
$archive_title = $post_type_obj && isset( $post_type_obj->labels->name )
	? $post_type_obj->labels->name
	: __( 'Before/After', 'custom-manhattan-laser-theme' );

$archive_url = function_exists( 'custom_manhattan_laser_get_before_after_archive_url' )
	? custom_manhattan_laser_get_before_after_archive_url()
	: get_post_type_archive_link( 'before_after' );
if ( ! is_string( $archive_url ) || '' === $archive_url ) {
	$archive_url = home_url( '/' );
}

$hero_bg_src = get_template_directory_uri() . '/img/doctor-bg.png';

$hero_kicker = __( 'Before/After', 'custom-manhattan-laser-theme' );

$hero_bio = __( 'See how the right care strategy transforms your appearance while preserving your unique individuality.', 'custom-manhattan-laser-theme' );

$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => $archive_title,
		'url'  => $archive_url,
	),
);

get_header();
?>

<img src="<?php echo esc_url( $hero_bg_src ); ?>" alt="" class="hero-img-single-doctor pointer-events-none absolute left-0 top-0 z-[100] w-full object-cover" width="1920" height="1080" decoding="async">
<section class="hero-section-single-doctor relative z-[100] mx-5 flex flex-col" id="main-content">
	<div class="container !px-0">
		<nav class="text-[#F4EFE8]" aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
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
	</div>

	<div class="container flex h-full flex-col items-start justify-start !mt-10 !px-0 md:!mb-10">
		<div class="flex w-full flex-col items-start justify-start md:mt-10 md:max-w-[950px] md:items-start md:justify-start ">
			<h1 class="max-w-[330px] font-display text-[32px] leading-[100%] text-[#F4EFE8] md:max-w-[950px] md:text-[96px]">
				<?php echo esc_html( __( 'Transformations That Speak for Themselves', 'custom-manhattan-laser-theme' ) ); ?>
			</h1>
		</div>
		<div class="flex items-end w-full flex-col justify-center md:order-none md:h-full md:items-end md:justify-end mt-5">
			<div class="w-full flex items-center justify-start">
				<p class="text-start w-full md:max-w-[540px] text-[16px] text-[#F4EFE8]">
				Our Before & After gallery showcases real results achieved by our patients. Each transformation reflects a personalized treatment plan designed to enhance natural beauty and address individual concerns.
				</p>
			</div>
			<div class="w-full items-center justify-start md:justify-end hidden md:flex">
			<p class="text-[16px] text-[#F4EFE8] md:max-w-[478px]">
					 Please note that results may vary from person to person depending on skin type, anatomy, and treatment goals. All procedures are performed by licensed and certified medical professionals using advanced techniques and clinically proven treatments.
				</p>
			</div>
		</div>
	</div>
	<div class="flex flex-col ">
	<p class="text-[16px] text-[#F4EFE8] md:max-w-[478px] block md:hidden mb-5">
					 Please note that results may vary from person to person depending on skin type, anatomy, and treatment goals. All procedures are performed by licensed and certified medical professionals using advanced techniques and clinically proven treatments.
				</p>
	<a
		href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
		class="relative mx-auto mb-6 mt-auto flex w-full max-w-[1400px] items-center justify-between rounded-full bg-[#F4EFE824] py-4 pl-5 pr-7 text-[16px] backdrop-blur-[7px] md:mb-8 md:justify-center md:gap-10 md:py-5 group"
	>
		<span class="whitespace-nowrap text-[#F4EFE8]">
			<?php esc_html_e( 'Book a Consultation', 'custom-manhattan-laser-theme' ); ?>
		</span>
		<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
			<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
			<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
		</span>
	</a>
	</div>
</section>

<?php
	$ml_ba_filter_treatment = isset( $_GET['treatment'] ) ? absint( wp_unslash( $_GET['treatment'] ) ) : 0;
	if ( $ml_ba_filter_treatment > 0 && ! get_post( $ml_ba_filter_treatment ) ) {
		$ml_ba_filter_treatment = 0;
	}
	if ( $ml_ba_filter_treatment > 0 && 'treatment' !== get_post_type( $ml_ba_filter_treatment ) ) {
		$ml_ba_filter_treatment = 0;
	}
	$before_after_slides = function_exists( 'custom_manhattan_laser_get_before_after_archive_slide_rows' )
		? custom_manhattan_laser_get_before_after_archive_slide_rows( $ml_ba_filter_treatment )
		: array();
	$treatments_for_filter = array();
	if ( post_type_exists( 'treatment' ) ) {
		$treatments_for_filter = get_posts(
			array(
				'post_type'      => 'treatment',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
	}
	$ml_ba_filter_label = __( 'Filter', 'custom-manhattan-laser-theme' );
	if ( $ml_ba_filter_treatment > 0 ) {
		$ml_ba_filter_label = get_the_title( $ml_ba_filter_treatment );
		if ( '' === $ml_ba_filter_label ) {
			$ml_ba_filter_label = __( 'Filter', 'custom-manhattan-laser-theme' );
		}
	}
?>
<section class="before-after-section overflow-visible py-16 md:py-24 relative">
	<div class="container overflow-visible" style="padding-left: 0;">
		<div class="before-after-section__row flex flex-col overflow-visible lg:flex-row lg:items-center gap-12 lg:gap-16 pl-5 md:pl-0 ">
			<div class="before-after-section__slider-wrap flex-1 min-w-0 w-full overflow-visible md:order-none">
				<?php if ( ! empty( $treatments_for_filter ) ) : ?>
				<style>
					.ml-ba-filter-details > summary { list-style: none; }
					.ml-ba-filter-details > summary::-webkit-details-marker { display: none; }
					.ml-ba-filter-details[open] .ml-ba-filter__chev { transform: rotate(180deg); }
				</style>
				<details class="ml-ba-filter-details group relative z-[60] mb-10 max-w-[min(100%,22rem)] overflow-visible" data-ml-ba-filter id="ml-ba-filter-details">
					<summary
						class="ml-ba-filter__toggle flex w-full cursor-pointer list-none items-center justify-between gap-3 rounded-full border border-[#F4EFE8]/15 bg-[#F4EFE824] px-5 py-3 text-left font-sans text-[15px] text-[#F4EFE8] shadow-sm backdrop-blur-[12px] transition-opacity hover:opacity-95 md:text-[16px]"
						id="ml-ba-filter-btn"
					>
						<span class="ml-ba-filter__label truncate"><?php echo esc_html( $ml_ba_filter_label ); ?></span>
						<span class="ml-ba-filter__chev inline-flex shrink-0 text-[#F4EFE8] transition-transform duration-200" aria-hidden="true">
							<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
					</summary>
					<div
						class="ml-ba-filter__panel absolute left-0 right-0 top-full z-[70] mt-2 max-h-[min(70vh,420px)] overflow-y-auto rounded-[24px] border border-[#F4EFE8]/12 bg-[#F4EFE824] py-2 shadow-lg backdrop-blur-[12px]"
						id="ml-ba-filter-listbox"
						role="listbox"
						aria-labelledby="ml-ba-filter-btn"
					>
						<ul class="list-none px-2 py-1 font-sans text-[15px] text-[#F4EFE8] md:text-[16px]">
							<li role="none">
								<button
									type="button"
									class="ml-ba-filter__opt flex w-full rounded-xl px-3 py-2.5 text-left transition-colors hover:bg-[#F4EFE8]/10 <?php echo 0 === (int) $ml_ba_filter_treatment ? 'underline decoration-[#F4EFE8] underline-offset-4' : ''; ?>"
									role="option"
									data-treatment-id="0"
									<?php echo 0 === (int) $ml_ba_filter_treatment ? 'aria-selected="true"' : ''; ?>
								>
									<?php echo esc_html( __( 'All treatments', 'custom-manhattan-laser-theme' ) ); ?>
								</button>
							</li>
							<?php foreach ( $treatments_for_filter as $treat_post ) : ?>
								<li role="none">
									<button
										type="button"
										class="ml-ba-filter__opt flex w-full rounded-xl px-3 py-2.5 text-left transition-colors hover:bg-[#F4EFE8]/10 <?php echo (int) $ml_ba_filter_treatment === (int) $treat_post->ID ? 'underline decoration-[#F4EFE8] underline-offset-4' : ''; ?>"
										role="option"
										data-treatment-id="<?php echo esc_attr( (string) (int) $treat_post->ID ); ?>"
										<?php echo (int) $ml_ba_filter_treatment === (int) $treat_post->ID ? 'aria-selected="true"' : ''; ?>
									>
										<?php echo esc_html( get_the_title( $treat_post ) ); ?>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</details>
				<?php endif; ?>
				<div
					class="relative min-h-[120px]"
					data-ml-before-after-archive="1"
					id="ml-archive-ba-slider-root"
					aria-busy="false"
				>
					<div
						id="ml-ba-archive-loading"
						class="absolute inset-0 z-[100] hidden flex-col items-center justify-center gap-4 rounded-[inherit] bg-[#18100D]/65 backdrop-blur-[4px] transition-opacity duration-200"
						aria-hidden="true"
						role="status"
					>
						<span
							class="inline-block h-11 w-11 shrink-0 animate-spin rounded-full border-2 border-[#F4EFE8]/20 border-t-[#F4EFE8]"
							aria-hidden="true"
						></span>
						<span class="ml-ba-archive-loading__text font-sans text-[14px] font-medium tracking-wide text-[#F4EFE8] md:text-[15px]">
							<?php echo esc_html( __( 'Loading…', 'custom-manhattan-laser-theme' ) ); ?>
						</span>
					</div>
					<div id="ml-archive-ba-slider-mount" class="relative transition-opacity duration-200" aria-live="polite" aria-busy="false">
						<?php
						if ( function_exists( 'custom_manhattan_laser_render_before_after_archive_slider_inner' ) ) {
							echo custom_manhattan_laser_render_before_after_archive_slider_inner( $before_after_slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
$testimonials_items = array(
	array(
		'name' => 'Olena K.',
		'text' => 'I truly appreciated the professional and structured approach. Instead of offering quick fixes, the specialist explained a long-term strategy tailored to my skin.',
		'date' => '22.01.2026',
	),
	array(
		'name' => 'Marcus T.',
		'text' => 'Clear communication from day one. Worth the visit.',
		'date' => '18.01.2026',
	),
	array(
		'name' => 'Sofia R.',
		'text' => 'After years of trial and error with different clinics, I finally feel my concerns are heard. The plan is realistic, step-by-step, and I already see measurable improvement in texture and tone. The team never rushes appointments.',
		'date' => '12.01.2026',
	),
	array(
		'name' => 'James L.',
		'text' => 'Discreet, elegant space and science-backed recommendations — no upselling.',
		'date' => '09.01.2026',
	),
	array(
		'name' => 'Elena V.',
		'text' => 'I came in with sensitive, reactive skin and was afraid of aggressive treatments. Here they built a gentle protocol first, then gradually introduced stronger options only when my barrier was ready. That patience made all the difference.',
		'date' => '04.01.2026',
	),
	array(
		'name' => 'David K.',
		'text' => 'Five stars. Professional.',
		'date' => '28.12.2025',
	),
	array(
		'name' => 'Anna M.',
		'text' => 'The follow-up care instructions were detailed and easy to follow at home. I appreciated that they checked in after my first session and adjusted the routine based on how my skin responded. That level of attention is rare.',
		'date' => '15.12.2025',
	),
);
?>
<section class="testimonials-section bg-[#18100D] py-14 md:py-24 relative" aria-labelledby="testimonials-heading">
<div class="before-after-section__bg-pattern absolute right-[-70%] top-[-5%] w-full h-[500px]  bg-[#201511] blur-[150px] z-0 hidden md:flex rounded-full"> </div>
	<div class="container relative">
		<p class="testimonials-section__label mb-5 md:mb-7 flex items-center gap-2 text-[15px] text-[#F4EFE8] md:text-[20px]">
			<span class="h-1 w-1 shrink-0 rounded-full bg-[#F4EFE8]" aria-hidden="true"></span>
			<?php esc_html_e( 'Testimonials', 'custom-manhattan-laser-theme' ); ?>
		</p>
		<h2 id="testimonials-heading" class="mb-5 max-w-[920px] font-display text-[32px] leading-[110%] text-[#F4EFE8] md:mb-5 md:text-[48px]">
			<?php esc_html_e( 'Structured Care. Measured Results.', 'custom-manhattan-laser-theme' ); ?>
		</h2>
		<div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between md:mb-12 lg:mb-14">
			<p class="max-w-[300px] text-[15px] text-[#F4EFE8]/90 md:text-[16px]">
				<?php esc_html_e( 'Our clients\' experiences reflect a strategic, professional approach to skin health.', 'custom-manhattan-laser-theme' ); ?>
			</p>
			<div class="testimonials-section__nav shrink-0 items-center justify-start gap-10 sm:justify-end hidden md:flex">
				<button type="button" class="testimonials-swiper-prev flex items-center justify-center text-[#F4EFE8] transition-opacity hover:opacity-70" aria-label="<?php esc_attr_e( 'Previous testimonials', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
				<button type="button" class="testimonials-swiper-next flex items-center justify-center text-[#F4EFE8] transition-opacity hover:opacity-70" aria-label="<?php esc_attr_e( 'Next testimonials', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
			</div>
		</div>
		<div class="testimonials-swiper-outer -mx-1 overflow-hidden px-1 md:-mx-2 md:px-2">
			<div class="swiper testimonials-swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $testimonials_items as $t ) : ?>
					<div class="swiper-slide testimonials-swiper-slide">
						<article class="testimonials-card flex h-full  flex-col rounded-none border border-[#F4EFE8]/[0.11] bg-[#1a1411] p-5 md:min-h-[292px] md:p-6" aria-label="<?php echo esc_attr( sprintf( __( '5 out of 5 stars, %s', 'custom-manhattan-laser-theme' ), $t['name'] ) ); ?>">
							<p class="text-[14px] font-semibold text-[#F4EFE8] md:text-[16px]"><?php echo esc_html( $t['name'] ); ?></p>
							<div class="testimonials-card__stars mb-5 md:mb-8 mt-1.5 flex gap-[3px] text-[10px] leading-none tracking-tight text-[#F4EFE8] md:text-[11px]" aria-hidden="true">
								<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
							</div>
							<p class="testimonials-card__text mb-4 flex-1 text-[11px] text-[#F4EFE8] md:text-[16px]">
								<?php echo esc_html( $t['text'] ); ?>
							</p>
							<p class="mt-auto text-right text-[12px] tabular-nums text-[#F4EFE8]/60 md:text-[14px]"><?php echo esc_html( $t['date'] ); ?></p>
						</article>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="testimonials-section__nav shrink-0 items-center justify-center gap-10 sm:justify-end md:hidden flex mt-8">
				<button type="button" class="testimonials-swiper-prev flex items-center justify-center text-[#F4EFE8] transition-opacity hover:opacity-70" aria-label="<?php esc_attr_e( 'Previous testimonials', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
				<button type="button" class="testimonials-swiper-next flex items-center justify-center text-[#F4EFE8] transition-opacity hover:opacity-70" aria-label="<?php esc_attr_e( 'Next testimonials', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
			</div>
		</div>
	</div>
</section>

<section class="cta-section relative w-full cta-section flex items-center mt-10 md:mt-20">
	<img src="<?php echo esc_url( get_template_directory_uri() . '/img/cta-footer-bg.webp' ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
	<div class="container relative z-10 flex items-center justify-center">
		<div class="flex flex-col items-center justify-center">
			<h2 class="cta-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[110%] mb-4 max-w-[763px] text-center">
			See the same results for yourself — book your consultation today
			</h2>
			<p class="cta-section__text text-[16px] text-[#F4EFE8] mb-8 max-w-[275px] text-center">
			We focus on sustainable results, not temporary fixes.
			</p>
			<a href="#" class="flex items-center gap-7 md:gap-20 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit">
                    <span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
					Book My Consultation
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
                    </a>
		</div>
	</div>
</section>

<?php
$ml_ba_theme_version = wp_get_theme()->get( 'Version' );
wp_enqueue_script(
	'ml-before-after-archive',
	get_template_directory_uri() . '/js/before-after-archive.js',
	array( 'custom-manhattan-laser-theme-main' ),
	$ml_ba_theme_version,
	true
);
$ml_ba_page_url_enqueue = function_exists( 'custom_manhattan_laser_get_before_after_archive_url' )
	? custom_manhattan_laser_get_before_after_archive_url()
	: home_url( '/' );
wp_localize_script(
	'ml-before-after-archive',
	'mlBeforeAfterArchive',
	array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ml_before_after_archive' ),
		'action'  => 'ml_before_after_archive_slider',
		'pageUrl' => esc_url( $ml_ba_page_url_enqueue ),
		'i18n'    => array(
			'filter'  => __( 'Filter', 'custom-manhattan-laser-theme' ),
			'all'     => __( 'All treatments', 'custom-manhattan-laser-theme' ),
			'loading' => __( 'Loading…', 'custom-manhattan-laser-theme' ),
		),
	)
);
get_footer();
