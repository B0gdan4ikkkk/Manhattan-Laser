<?php
/**
 * Template Name: Patient testimonials
 * Template Post Type: page
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$page_id    = get_queried_object_id();
$page_title = get_the_title( $page_id ) ? get_the_title( $page_id ) : __( 'Patient Testimonials', 'custom-manhattan-laser-theme' );
$page_url   = get_permalink( $page_id );
if ( ! is_string( $page_url ) || '' === $page_url ) {
	$page_url = home_url( '/' );
}
$review_privacy_url = home_url( '/privacy-policy' );

$filter_doctor  = isset( $_GET['filter_doctor'] ) ? absint( wp_unslash( $_GET['filter_doctor'] ) ) : 0;
$filter_service = isset( $_GET['filter_service'] ) ? absint( wp_unslash( $_GET['filter_service'] ) ) : 0;

$meta_query = array();
if ( $filter_doctor > 0 ) {
	$meta_query[] = array(
		'key'   => 'review_doctor_id',
		'value' => (string) $filter_doctor,
		'compare' => '=',
	);
}
if ( $filter_service > 0 ) {
	$meta_query[] = array(
		'key'   => 'review_treatment_id',
		'value' => (string) $filter_service,
		'compare' => '=',
	);
}

$reviews_args = array(
	'post_type'      => 'patient_review',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'paged'          => 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => false,
);
if ( count( $meta_query ) > 0 ) {
	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'AND';
	}
	$reviews_args['meta_query'] = $meta_query;
}

$reviews_query = new WP_Query( $reviews_args );
$review_posts  = $reviews_query->posts;

$doctors_for_select = post_type_exists( 'doctor' )
	? get_posts(
		array(
			'post_type'      => 'doctor',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	)
	: array();

$treatments_for_select = post_type_exists( 'treatment' )
	? get_posts(
		array(
			'post_type'      => 'treatment',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	)
	: array();

$filter_service_btn_label = __( 'Service', 'custom-manhattan-laser-theme' );
if ( $filter_service > 0 && get_post( $filter_service ) ) {
	$filter_service_btn_label = get_the_title( $filter_service );
}
$filter_doctor_btn_label = __( 'Doctor', 'custom-manhattan-laser-theme' );
if ( $filter_doctor > 0 && get_post( $filter_doctor ) ) {
	$filter_doctor_btn_label = get_the_title( $filter_doctor );
}

$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => $page_title,
		'url'  => $page_url,
	),
);

?>

<div class="testimonials-page min-h-screen bg-[#12100E] pb-16 md:bg-transparent md:pb-24" id="main-content">
	<div class="container">
		<nav class="!pb-5 mb-4 text-[#F4EFE8] border-b border-[#F4EFE80D]" aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
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

		<?php if ( isset( $_GET['review'] ) && 'sent' === $_GET['review'] ) : ?>
			<p class="mb-6 rounded-[20px] border border-[#f5f5f0]/20 bg-[#2e2621] px-4 py-3 text-center font-sans text-[14px] text-[#f5f5f0]" role="status">
				<?php esc_html_e( 'Thank you. Your review has been submitted and will appear after moderation.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php elseif ( isset( $_GET['review'] ) && 'invalid' === $_GET['review'] ) : ?>
			<p class="mb-6 rounded-[20px] border border-red-500/40 bg-[#2e2621] px-4 py-3 text-center font-sans text-[14px] text-[#fecaca]" role="alert">
				<?php esc_html_e( 'Please fill in all required fields: name, service, doctor, rating, comment (at least 10 characters), and try again.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php elseif ( isset( $_GET['review'] ) && 'privacy' === $_GET['review'] ) : ?>
			<p class="mb-6 rounded-[20px] border border-amber-500/40 bg-[#2e2621] px-4 py-3 text-center font-sans text-[14px] text-[#fde68a]" role="alert">
				<?php esc_html_e( 'Please accept the Privacy Policy to submit your review.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php elseif ( isset( $_GET['review'] ) && 'error' === $_GET['review'] ) : ?>
			<p class="mb-6 rounded-[20px] border border-red-500/40 bg-[#2e2621] px-4 py-3 text-center font-sans text-[14px] text-[#fecaca]" role="alert">
				<?php esc_html_e( 'Something went wrong. Please try again later.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		<?php endif; ?>

		<h1 class="font-display text-[36px] font-normal leading-[1.1] text-[#F4EFE8] md:text-[96px] md:text-[#f5f5f0]">
			<?php esc_html_e( 'Patient Testimonials', 'custom-manhattan-laser-theme' ); ?>
		</h1>

		<form class="testimonials-page-filters mt-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-stretch md:mt-8 md:gap-5" method="get" action="<?php echo esc_url( $page_url ); ?>">
			<input type="hidden" name="filter_service" id="filter_service_input" value="<?php echo esc_attr( (string) $filter_service ); ?>">
			<input type="hidden" name="filter_doctor" id="filter_doctor_input" value="<?php echo esc_attr( (string) $filter_doctor ); ?>">

			<div class="testimonials-filter-dd min-w-0 flex-1 sm:max-w-[min(100%,22rem)]" data-testimonials-filter-dd data-filter-input="filter_service">
				<button
					type="button"
					class="testimonials-filter-dd__btn font-sans md:font-display"
					id="filter_service_btn"
					aria-haspopup="listbox"
					aria-expanded="false"
					aria-controls="filter_service_listbox"
				>
					<span class="testimonials-filter-dd__text"><?php echo esc_html( $filter_service_btn_label ); ?></span>
					<span class="testimonials-filter-dd__chev" aria-hidden="true"></span>
				</button>
				<div class="testimonials-filter-dd__panel" id="filter_service_listbox" role="listbox" aria-labelledby="filter_service_btn" hidden>
					<button type="button" class="testimonials-filter-dd__option font-sans" role="option" data-value="0" <?php echo 0 === (int) $filter_service ? 'aria-selected="true"' : ''; ?>>
						<?php echo esc_html( __( 'Service', 'custom-manhattan-laser-theme' ) ); ?>
					</button>
					<?php foreach ( $treatments_for_select as $tr ) : ?>
						<button type="button" class="testimonials-filter-dd__option font-sans" role="option" data-value="<?php echo esc_attr( (string) $tr->ID ); ?>" <?php echo (int) $filter_service === (int) $tr->ID ? 'aria-selected="true"' : ''; ?>>
							<?php echo esc_html( get_the_title( $tr ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="testimonials-filter-dd min-w-0 flex-1 sm:max-w-[min(100%,22rem)]" data-testimonials-filter-dd data-filter-input="filter_doctor">
				<button
					type="button"
					class="testimonials-filter-dd__btn font-sans md:font-display"
					id="filter_doctor_btn"
					aria-haspopup="listbox"
					aria-expanded="false"
					aria-controls="filter_doctor_listbox"
				>
					<span class="testimonials-filter-dd__text"><?php echo esc_html( $filter_doctor_btn_label ); ?></span>
					<span class="testimonials-filter-dd__chev" aria-hidden="true"></span>
				</button>
				<div class="testimonials-filter-dd__panel" id="filter_doctor_listbox" role="listbox" aria-labelledby="filter_doctor_btn" hidden>
					<button type="button" class="testimonials-filter-dd__option font-sans" role="option" data-value="0" <?php echo 0 === (int) $filter_doctor ? 'aria-selected="true"' : ''; ?>>
 						<?php echo esc_html( __( 'Doctor', 'custom-manhattan-laser-theme' ) ); ?>
					</button>
					<?php foreach ( $doctors_for_select as $doc ) : ?>
						<button type="button" class="testimonials-filter-dd__option font-sans" role="option" data-value="<?php echo esc_attr( (string) $doc->ID ); ?>" <?php echo (int) $filter_doctor === (int) $doc->ID ? 'aria-selected="true"' : ''; ?>>
							<?php echo esc_html( get_the_title( $doc ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</form>

		<div class="mt-10 grid grid-cols-1 gap-8 lg:mt-10 lg:grid-cols-12 lg:gap-5">
			<div class="min-w-0 lg:col-span-8">
				<div id="testimonials-reviews-column" class="relative min-h-[140px]" aria-live="polite">
				<?php if ( empty( $review_posts ) ) : ?>
					<p class="font-sans text-[15px] text-[#f5f5f0]/60">
						<?php esc_html_e( 'No reviews match your filters yet.', 'custom-manhattan-laser-theme' ); ?>
					</p>
				<?php else : ?>

					<div id="testimonials-reviews-list" class="flex flex-col gap-4 md:gap-8">
						<?php foreach ( $review_posts as $rp ) : ?>
							<?php
							if ( function_exists( 'custom_manhattan_laser_render_patient_review_card' ) ) {
								custom_manhattan_laser_render_patient_review_card( $rp );
							}
							?>
						<?php endforeach; ?>
					</div>

					<?php if ( $reviews_query->max_num_pages > 1 ) : ?>
						<button
							type="button"
							class="group relative mt-6 flex w-full items-center justify-center gap-7 rounded-full bg-[#1B263B] py-2.5 pl-5 pr-7 font-sans text-[16px] text-[#FBF5E7] transition-opacity hover:opacity-95 md:gap-20 md:py-3"
							data-testimonials-read-more
							aria-busy="false"
						>
							<span class="testimonials-read-more__label whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
								<?php esc_html_e( 'Read more', 'custom-manhattan-laser-theme' ); ?>
							</span>
							<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]" aria-hidden="true">
								<span class="absolute inset-0 rounded-full border border-[#F4EFE8]"></span>
								<span class="absolute left-1/2 top-1/2 h-1 w-1 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#F4EFE8]"></span>
							</span>
						</button>
					<?php endif; ?>

				<?php endif; ?>
				</div>
			</div>

			<aside class="lg:col-span-4 lg:self-start">
				<div class="lg:sticky lg:top-28">
				<button
					type="button"
					class="testimonials-review-cta__card w-full cursor-pointer rounded-[24px] bg-[#F4EFE8] p-6 text-left text-[#313131] md:rounded-[40px] md:p-10"
					data-open-review-modal
					aria-haspopup="dialog"
					aria-controls="testimonials-review-modal"
				>
					<span class="block font-display text-[24px] font-normal text-[#313131] md:text-[32px]">
						<?php esc_html_e( 'We’re always happy to hear from you!', 'custom-manhattan-laser-theme' ); ?>
					</span>
					<span class="mt-4 block text-[14px] leading-relaxed text-[#313131B2] md:text-[16px]">
						<?php esc_html_e( 'Leave a review about our clinic or your doctor. Your feedback helps us get better!', 'custom-manhattan-laser-theme' ); ?>
					</span>
					<span class="testimonials-review-cta__stars mt-10 flex gap-1 text-[28px] leading-none text-[#3E3E3E33] md:mt-16" aria-hidden="true">
						<?php for ( $si = 0; $si < 5; $si++ ) : ?>
							<span>★</span>
						<?php endfor; ?>
					</span>
					<div class="group relative mt-7 flex h-fit w-full items-center justify-center gap-7 rounded-full bg-[#1B263B] py-2.5 pl-5 pr-7 font-sans text-[16px] text-[#FBF5E7] md:gap-20 md:py-3">
						<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
							<?php esc_html_e( 'Leave a review', 'custom-manhattan-laser-theme' ); ?>
						</span>
						<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
					</div>
				</button>
				</div>
			</aside>
		</div>
	</div>
</div>

<div id="testimonials-review-modal" class="testimonials-review-modal" aria-hidden="true">
	<div class="testimonials-review-modal__backdrop" data-close-review-modal tabindex="-1"></div>
	<div
		class="testimonials-review-modal__panel mx-auto w-fit text-[#313131]"
		role="dialog"
		aria-modal="true"
		aria-labelledby="testimonials-review-modal-title"
		tabindex="-1"
	>
		<button type="button" class="testimonials-review-modal__close rounded-full font-sans text-[32px] leading-none text-[#313131] transition-opacity hover:opacity-80" data-close-review-modal aria-label="<?php esc_attr_e( 'Close', 'custom-manhattan-laser-theme' ); ?>">×</button>

		<h2 id="testimonials-review-modal-title" class="font-display text-[24px] font-normal leading-[1.12] tracking-tight text-[#2a2826] md:text-[32px] max-w-[220px] md:max-w-[293px]">
			<?php esc_html_e( 'We’re always happy to hear from you!', 'custom-manhattan-laser-theme' ); ?>
		</h2>
		<p class="mt-4 max-w-none text-[14px] leading-relaxed text-[#6b6b6b] md:max-w-[315px] md:text-[16px]">
			<?php esc_html_e( 'Leave a review about our clinic or your doctor. Your feedback helps us get better!', 'custom-manhattan-laser-theme' ); ?>
		</p>

		<div id="testimonials-review-modal-notice" class="mt-6 hidden rounded-[16px] px-4 py-3 text-center font-sans text-[14px] leading-snug" role="alert" aria-live="assertive"></div>

		<form id="testimonials-review-form" class="mt-6 md:mt-10" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
			<input type="hidden" name="action" value="ml_submit_patient_review">
			<?php wp_nonce_field( 'ml_review_submit', 'ml_review_nonce' ); ?>
			<input type="hidden" name="ml_review_redirect" value="<?php echo esc_url( $page_url ); ?>">
			<input type="text" name="ml_review_hp" value="" tabindex="-1" autocomplete="off" class="absolute -left-[9999px] h-0 w-0 opacity-0" aria-hidden="true">

			<?php
			$ml_modal_field = 'testimonials-review-modal__field w-full max-w-full border-0 border-b border-[#313131]/35 bg-transparent pb-1.5 font-sans text-[16px] text-[#313131] placeholder:text-[#313131]/55 focus:border-[#313131]/70 focus:outline-none focus:ring-0';
			$ml_modal_textarea = 'w-full max-w-full resize-none rounded-lg border border-[#31313199] bg-transparent px-3 py-3 font-sans text-[16px] leading-relaxed text-[#313131] placeholder:text-[#313131]/55 focus:border-[#313131] focus:outline-none focus:ring-0 ';
			?>
			<div class="grid grid-cols-1 gap-x-10 gap-y-5 sm:grid-cols-2">
				<div class="w-full sm:col-span-1 md:w-[310px]">
					<label class="sr-only" for="ml_review_modal_name"><?php esc_html_e( 'Name', 'custom-manhattan-laser-theme' ); ?></label>
					<input type="text" name="ml_review_name" id="ml_review_modal_name" required maxlength="120" placeholder="<?php esc_attr_e( 'Name', 'custom-manhattan-laser-theme' ); ?>" class="<?php echo esc_attr( $ml_modal_field ); ?>">
				</div>
				<div class="testimonials-review-modal-dd relative w-full sm:col-span-1 md:w-[310px]" data-review-modal-dd data-placeholder="<?php echo esc_attr( __( 'Choose a service', 'custom-manhattan-laser-theme' ) ); ?>">
					<label class="sr-only" for="ml_review_modal_treatment_btn"><?php esc_html_e( 'Service', 'custom-manhattan-laser-theme' ); ?></label>
					<input type="hidden" name="ml_review_treatment" id="ml_review_treatment_val" value="" required>
					<button
						type="button"
						class="testimonials-review-modal-dd__btn"
						id="ml_review_modal_treatment_btn"
						aria-haspopup="listbox"
						aria-expanded="false"
						aria-controls="ml_review_treatment_listbox"
					>
						<span class="testimonials-review-modal-dd__btn-text"><?php echo esc_html( __( 'Choose a service', 'custom-manhattan-laser-theme' ) ); ?></span>
						<span class="testimonials-review-modal-dd__chev" aria-hidden="true"></span>
					</button>
					<div class="testimonials-review-modal-dd__panel" id="ml_review_treatment_listbox" role="listbox" aria-labelledby="ml_review_modal_treatment_btn" hidden>
						<?php foreach ( $treatments_for_select as $tr ) : ?>
							<button type="button" class="testimonials-review-modal-dd__option" role="option" data-value="<?php echo esc_attr( (string) $tr->ID ); ?>">
								<?php echo esc_html( get_the_title( $tr ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="w-full sm:col-span-1 md:w-[310px]">
					<label class="sr-only" for="ml_review_modal_phone"><?php esc_html_e( 'Phone number', 'custom-manhattan-laser-theme' ); ?></label>
					<input type="tel" name="ml_review_phone" id="ml_review_modal_phone" maxlength="40" placeholder="<?php esc_attr_e( 'Phone number', 'custom-manhattan-laser-theme' ); ?>" class="<?php echo esc_attr( $ml_modal_field ); ?>">
				</div>
				<div class="testimonials-review-modal-dd relative w-full sm:col-span-1 md:w-[310px]" data-review-modal-dd data-placeholder="<?php echo esc_attr( __( 'Doctor’s name', 'custom-manhattan-laser-theme' ) ); ?>">
					<label class="sr-only" for="ml_review_modal_doctor_btn"><?php esc_html_e( 'Doctor', 'custom-manhattan-laser-theme' ); ?></label>
					<input type="hidden" name="ml_review_doctor" id="ml_review_doctor_val" value="" required>
					<button
						type="button"
						class="testimonials-review-modal-dd__btn"
						id="ml_review_modal_doctor_btn"
						aria-haspopup="listbox"
						aria-expanded="false"
						aria-controls="ml_review_doctor_listbox"
					>
						<span class="testimonials-review-modal-dd__btn-text"><?php echo esc_html( __( 'Doctor’s name', 'custom-manhattan-laser-theme' ) ); ?></span>
						<span class="testimonials-review-modal-dd__chev" aria-hidden="true"></span>
					</button>
					<div class="testimonials-review-modal-dd__panel" id="ml_review_doctor_listbox" role="listbox" aria-labelledby="ml_review_modal_doctor_btn" hidden>
						<?php foreach ( $doctors_for_select as $doc ) : ?>
							<button type="button" class="testimonials-review-modal-dd__option" role="option" data-value="<?php echo esc_attr( (string) $doc->ID ); ?>">
								<?php echo esc_html( get_the_title( $doc ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="mt-8 w-full">
				<label class="sr-only" for="ml_review_modal_text"><?php esc_html_e( 'Write a comment', 'custom-manhattan-laser-theme' ); ?></label>
				<textarea name="ml_review_text" id="ml_review_modal_text" required rows="4" minlength="10" placeholder="<?php esc_attr_e( 'Write a comment', 'custom-manhattan-laser-theme' ); ?>" class="<?php echo esc_attr( $ml_modal_textarea ); ?> !w-full !max-w-auto"></textarea>
			</div>

			<div class="mt-6">
				<span id="ml-review-modal-stars-label" class="sr-only"><?php esc_html_e( 'Your rating', 'custom-manhattan-laser-theme' ); ?></span>
				<div class="flex items-center gap-1" data-ml-review-stars role="group" aria-labelledby="ml-review-modal-stars-label">
					<input type="hidden" name="ml_review_rating" value="" id="ml_review_rating_val" autocomplete="off">
					<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
						<button type="button" class="ml-review-star text-[28px] leading-none transition-opacity hover:opacity-90" data-star="<?php echo esc_attr( (string) $s ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: star number 1-5 */ __( '%d stars', 'custom-manhattan-laser-theme' ), $s ) ); ?>">★</button>
					<?php endfor; ?>
				</div>
			</div>

			<div class="mt-6 w-full rounded-[12px] border border-[#313131]/12 bg-white/45 px-4 py-3.5 md:px-5 md:py-4">
				<label class="flex cursor-pointer items-center gap-3 font-sans text-[14px] leading-[1.45] text-[#666666] md:gap-4 md:text-[16px]">
					<input
						type="checkbox"
						name="ml_review_privacy"
						value="1"
						required
						class="testimonials-review-modal__privacy-checkbox h-[18px] w-[18px] shrink-0 cursor-pointer rounded-sm border border-[#666666] bg-white text-[#666666] accent-[#666666] focus:outline-none focus:ring-2 focus:ring-[#666666]/25 focus:ring-offset-2 focus:ring-offset-white md:h-5 md:w-5"
					>
					<span class="text-left">
						<?php
						echo wp_kses(
							sprintf(
								/* translators: %s: privacy policy URL */
								__( 'I have read and agree to the <a class="text-[#666666] underline decoration-[#666666] decoration-1 underline-offset-[3px]" href="%s">Privacy policy</a>', 'custom-manhattan-laser-theme' ),
								esc_url( $review_privacy_url )
							),
							array(
								'a' => array(
									'href'   => array(),
									'class'  => array(),
									'target' => array(),
								),
							)
						);
						?>
					</span>
				</label>
			</div>

			<button type="submit" class="testimonials-review-form__submit mt-8 flex w-full items-center justify-center gap-3 rounded-full bg-[#1F2A44] py-3.5 pl-7 pr-6 font-sans text-[15px] font-normal text-white transition-opacity hover:opacity-95 enabled:hover:opacity-95 disabled:cursor-not-allowed disabled:opacity-60 md:py-4 md:text-[16px] group">
				<span class="testimonials-review-form__submit-label whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1"><?php esc_html_e( 'Send', 'custom-manhattan-laser-theme' ); ?></span>
				<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
			</button>
		</form>
	</div>
</div>

<?php
if ( function_exists( 'custom_manhattan_laser_localize_testimonials_script' ) ) {
	custom_manhattan_laser_localize_testimonials_script(
		$filter_doctor,
		$filter_service,
		(int) $reviews_query->max_num_pages,
		$page_url
	);
}
get_footer();
