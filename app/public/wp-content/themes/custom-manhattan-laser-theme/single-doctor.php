<?php
/**
 * Одна карточка врача (CPT doctor).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_queried_object_id();

$doctor_role        = (string) get_post_meta( $post_id, 'doctor_role', true );
$doctor_hero_kicker = (string) get_post_meta( $post_id, 'doctor_hero_kicker', true );
$doctor_hero_bio    = (string) get_post_meta( $post_id, 'doctor_hero_bio', true );
$doctor_credentials = (string) get_post_meta( $post_id, 'doctor_credentials', true );
$doctor_hero_bg_url = (string) get_post_meta( $post_id, 'doctor_hero_bg_url', true );

$hero_kicker = '' !== $doctor_hero_kicker ? $doctor_hero_kicker : $doctor_role;

$hero_bio = $doctor_hero_bio;
if ( '' === $hero_bio && $post_id && has_excerpt( $post_id ) ) {
	$hero_bio = get_the_excerpt( $post_id );
}

$credential_lines = array_filter(
	array_map(
		'trim',
		explode( "\n", str_replace( "\r\n", "\n", $doctor_credentials ) )
	)
);

$hero_bg_src = '' !== $doctor_hero_bg_url
	? $doctor_hero_bg_url
	: get_template_directory_uri() . '/img/doctor-bg.png';

$team_url = function_exists( 'custom_manhattan_laser_get_medical_team_page_url' )
	? custom_manhattan_laser_get_medical_team_page_url()
	: home_url( '/' );

$doctor_url = get_permalink( $post_id );

$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => __( 'Medical Team', 'custom-manhattan-laser-theme' ),
		'url'  => $team_url,
	),
	array(
		'name' => get_the_title( $post_id ),
		'url'  => $doctor_url ? $doctor_url : '',
	),
);

get_header();
?>

<img src="<?php echo esc_url( $hero_bg_src ); ?>" alt="" class="hero-img-single-doctor pointer-events-none absolute left-0 top-0 z-[100] w-full object-cover" width="1920" height="1080" decoding="async">
<div class="w-full absolute top-0 left-0 z-[100] hero-img-single-doctor bg-[#000000]/30"></div>
<section class="hero-section-single-doctor relative z-[100] mx-5 flex flex-col">
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

	<div class="container flex h-full flex-col items-start justify-between !mt-10 !px-0 md:!mb-10 md:flex-row">
		<div class="order-2 flex h-full w-full flex-col items-end justify-end md:order-none md:mt-0 md:max-w-[450px] md:items-start md:justify-start mt-[140px]">
			<?php if ( '' !== $hero_kicker ) : ?>
				<p class="mb-3 max-w-[180px] font-display text-[16px] uppercase leading-relaxed text-[#F4EFE8] md:mb-5 md:max-w-[350px] md:text-[24px]">
					<?php echo esc_html( $hero_kicker ); ?>
				</p>
			<?php endif; ?>
			<h1 class="max-w-[180px] font-display text-[36px] leading-[97%] text-[#F4EFE8] md:max-w-full md:text-[96px]">
				<?php echo esc_html( get_the_title( $post_id ) ); ?>
			</h1>
		</div>
		<div class="order-1 flex max-w-[381px] flex-col items-start justify-start md:order-none md:h-full md:items-end md:justify-end">
			<?php if ( '' !== $hero_bio ) : ?>
				<p class="mb-5 text-[15px] md:text-[20px]">
					<?php echo esc_html( $hero_bio ); ?>
				</p>
			<?php endif; ?>
			<?php if ( ! empty( $credential_lines ) ) : ?>
				<ul class="ml-5 list-disc text-[14px] md:ml-6 md:text-[16px]">
					<?php foreach ( $credential_lines as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
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
</section>

<?php
$faq_items = function_exists( 'custom_manhattan_laser_get_doctor_faq_items' )
	? custom_manhattan_laser_get_doctor_faq_items( $post_id )
	: array();
$faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(),
);
foreach ( $faq_items as $faq_row ) {
	if ( '' === trim( (string) $faq_row['a'] ) ) {
		continue;
	}
	$faq_schema['mainEntity'][] = array(
		'@type'          => 'Question',
		'name'           => wp_strip_all_tags( $faq_row['q'] ),
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => wp_strip_all_tags( $faq_row['a'] ),
		),
	);
}
?>
<?php if ( ! empty( $faq_schema['mainEntity'] ) ) : ?>
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
<?php endif; ?>
<section class="faq-section relative bg-[#F5F1EB] py-14 md:py-24" id="faq" aria-labelledby="faq-heading">
<div class="before-after-section__bg-pattern pointer-events-none absolute left-[-70%] bottom-[-50%] z-0 hidden h-[500px] w-full rounded-full bg-[#201511] blur-[150px]"> </div>
	<div class="container relative z-[1]">
		<div class="flex flex-col items-start justify-between gap-5 md:flex-row lg:items-start lg:gap-16">
			<div class="max-w-xl">
				<p class="mb-5 hidden items-center gap-2 text-[15px] text-[#333333] md:mb-7 md:text-[20px]">
					<span class="h-1 w-1 shrink-0 rounded-full bg-[#333333]" aria-hidden="true"></span>
					<?php esc_html_e( 'FAQ', 'custom-manhattan-laser-theme' ); ?>
				</p>
				<h2 id="faq-heading" class="font-display text-[32px] leading-[110%] text-[#333333] md:max-w-[380px] md:text-[48px]">
					<?php esc_html_e( 'Areas of Expertise', 'custom-manhattan-laser-theme' ); ?>
				</h2>
				<p class="mt-5 max-w-[222px] font-sans text-[15px] leading-[1.5] text-[#333333] md:text-[16px]">
					<?php esc_html_e( 'Modern aesthetic medicine for natural-looking results.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="faq-section__accordion min-w-0 w-full md:max-w-[600px]">
				<?php foreach ( $faq_items as $fi => $faq_item ) : ?>
				<details
					class="faq-item group border-b border-[#333333]/15 py-5"
					name="faq-home"
					<?php echo 0 === $fi ? 'open' : ''; ?>
				>
					<summary class="faq-item__summary flex w-full cursor-pointer list-none items-start justify-between gap-6 bg-transparent p-0 text-left text-[#333333]">
						<span class="min-w-0 flex-1 font-display text-[18px] leading-snug md:text-[22px] lg:text-[24px]"><?php echo esc_html( $faq_item['q'] ); ?></span>
						<span class="faq-item__icons relative mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center text-[26px] font-light leading-none text-[#333333]" aria-hidden="true">
							<span class="faq-item__icon-plus inline-flex items-center justify-center">+</span>
						</span>
					</summary>
					<?php if ( '' !== trim( (string) $faq_item['a'] ) ) : ?>
					<div class="faq-item__answer mt-4 pr-2 font-sans text-[15px] leading-relaxed text-[#333333] md:pr-8 md:text-[16px]">
						<?php echo esc_html( $faq_item['a'] ); ?>
					</div>
					<?php endif; ?>
				</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<section class="doctor-certifications px-4 py-16 md:py-20 block hidden" aria-labelledby="doctor-certifications-heading">
	<div class="container mx-auto max-w-6xl">
		<header class="mx-auto mb-10 text-center md:mb-14">
			<h2 id="doctor-certifications-heading" class="mb-4 font-display text-[28px] font-normal leading-[1.15] text-[#F4EFE8] md:text-[40px] lg:text-[44px]">
				<?php esc_html_e( 'Professional Certifications', 'custom-manhattan-laser-theme' ); ?>
			</h2>
			<p class="font-sans text-[16px] leading-relaxed text-[#F4EFE8]/85 max-w-[451px] mx-auto">
				<?php esc_html_e( 'Advanced medical education and globally recognized credentials ensuring the highest standard of care.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		</header>
		<div class="mx-auto grid max-w-[1000px] grid-cols-1 gap-6 md:grid-cols-3 md:gap-6">
			<?php for ( $ci = 0; $ci < 3; $ci++ ) : ?>
				<div class="flex aspect-[3/4] items-center justify-center bg-[#f8f5f2]">
					<span class="sr-only"><?php echo esc_html( sprintf( /* translators: %d: placeholder card number 1–3 */ __( 'Certification image placeholder %d', 'custom-manhattan-laser-theme' ), $ci + 1 ) ); ?></span>
					<svg class="h-14 w-14 shrink-0 text-[#9a9690]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<rect x="10" y="14" width="44" height="36" rx="2" stroke="currentColor" stroke-width="2" />
						<path d="M14 46 L26 30 L34 38 L46 22 L50 46" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						<circle cx="24" cy="26" r="4" stroke="currentColor" stroke-width="2" />
					</svg>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>

<section class="doctor-certifications px-4 py-16 md:py-20" aria-labelledby="doctor-certifications-heading">
	<div class="container mx-auto max-w-6xl">
		<div class="mx-auto mb-10 text-center md:mb-14">
			<h2 id="doctor-certifications-heading" class="mb-4 font-display text-[28px] font-normal leading-[1.15] text-[#F4EFE8] md:text-[40px] lg:text-[44px]">
				<?php esc_html_e( 'Professional Certifications', 'custom-manhattan-laser-theme' ); ?>
			</h2>
			<p class="font-sans text-[16px] leading-relaxed text-[#F4EFE8]/85 max-w-[451px] mx-auto">
				<?php esc_html_e( 'Advanced medical education and globally recognized credentials ensuring the highest standard of care.', 'custom-manhattan-laser-theme' ); ?>
			</p>
		</div>

		<?php
		$doctor_cert_slides = 3;
		$doctor_cert_card   = function ( $index ) {
			?>
			<div class="doctor-certifications-card box-border aspect-[3/4] w-full border border-[#e8dcc8]/85 bg-[#110F0E] p-1">
				<div class="flex h-full min-h-0 w-full items-center justify-center border border-[#e8dcc8]/75 bg-[#f8f5f2]">
					<span class="sr-only"><?php echo esc_html( sprintf( /* translators: %d: placeholder card number */ __( 'Certification image placeholder %d', 'custom-manhattan-laser-theme' ), $index ) ); ?></span>
					<svg class="h-14 w-14 shrink-0 text-[#9a9690]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<rect x="10" y="14" width="44" height="36" rx="2" stroke="currentColor" stroke-width="2" />
						<path d="M14 46 L26 30 L34 38 L46 22 L50 46" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						<circle cx="24" cy="26" r="4" stroke="currentColor" stroke-width="2" />
					</svg>
				</div>
			</div>
			<?php
		};
		?>

		<div class="doctor-certifications__mobile md:hidden">
			<div class="swiper doctor-certifications-swiper !overflow-visible">
				<div class="swiper-wrapper">
					<?php
					for ( $ci = 0; $ci < $doctor_cert_slides; $ci++ ) :
						?>
					<div class="swiper-slide !h-auto">
						<?php $doctor_cert_card( $ci + 1 ); ?>
					</div>
						<?php
					endfor;
					?>
				</div>
			</div>
			<div class="mt-8 flex items-center justify-center gap-14">
				<button type="button" class="doctor-certifications-swiper-prev flex h-10 w-10 items-center justify-center font-sans text-[22px] font-light leading-none text-[#F5F2ED] transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Previous slide', 'custom-manhattan-laser-theme' ); ?>">
					<span aria-hidden="true">←</span>
				</button>
				<button type="button" class="doctor-certifications-swiper-next flex h-10 w-10 items-center justify-center font-sans text-[22px] font-light leading-none text-[#F5F2ED] transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Next slide', 'custom-manhattan-laser-theme' ); ?>">
					<span aria-hidden="true">→</span>
				</button>
			</div>
		</div>

		<div class="mx-auto hidden max-w-6xl grid-cols-1 gap-6 md:grid md:grid-cols-3">
			<?php
			for ( $ci = 0; $ci < $doctor_cert_slides; $ci++ ) {
				$doctor_cert_card( $ci + 1 );
			}
			?>
		</div>
	</div>
</section>

<?php
$booking_notice = '';
if ( isset( $_GET['booking'] ) ) {
	$b = sanitize_text_field( wp_unslash( $_GET['booking'] ) );
	if ( 'sent' === $b ) {
		$booking_notice = 'sent';
	} elseif ( 'privacy' === $b ) {
		$booking_notice = 'privacy';
	} elseif ( 'error' === $b ) {
		$booking_notice = 'error';
	}
}
$booking_privacy_url = home_url( '/privacy-policy' );
$booking_cities       = function_exists( 'custom_manhattan_laser_get_booking_city_options' ) ? custom_manhattan_laser_get_booking_city_options() : array( '' => __( 'Choose your city', 'custom-manhattan-laser-theme' ) );
$booking_doctors      = function_exists( 'custom_manhattan_laser_get_booking_doctor_options' ) ? custom_manhattan_laser_get_booking_doctor_options() : array( '' => __( 'Choose a doctor', 'custom-manhattan-laser-theme' ) );
$treatments_for_booking = get_posts(
	array(
		'post_type'      => 'treatment',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
$booking_time_slots = array();
for ( $bh = 9; $bh <= 18; $bh++ ) {
	foreach ( array( 0, 30 ) as $bm ) {
		if ( 18 === $bh && $bm > 0 ) {
			break;
		}
		$booking_time_slots[] = sprintf( '%02d:%02d', $bh, $bm );
	}
}
?>
<section class="ml-booking-section py-14 text-[#F4EFE8] md:py-24 relative" aria-labelledby="ml-booking-heading" id="book-appointment">
<div class="before-after-section__bg-pattern absolute right-[30%] bottom-[0%] md:w-full w-[1000px] h-[800px] md:h-[600px]  bg-[#55392C] blur-[300px] z-0 flex rounded-full"> </div>
	<div class="container !max-w-[768px] relative z-10">
		<?php if ( 'sent' === $booking_notice ) : ?>
			<p class="mb-8 rounded-md border border-[#F4EFE8]/20 bg-[#1a1411] px-4 py-3 text-[15px] text-[#F4EFE8]" role="status"><?php esc_html_e( 'Thank you. Your appointment request has been received.', 'custom-manhattan-laser-theme' ); ?></p>
		<?php elseif ( 'privacy' === $booking_notice ) : ?>
			<p class="mb-8 rounded-md border border-amber-500/40 bg-[#1a1411] px-4 py-3 text-[15px] text-[#F4EFE8]" role="alert"><?php esc_html_e( 'Please agree to the Privacy Policy to continue.', 'custom-manhattan-laser-theme' ); ?></p>
		<?php elseif ( 'error' === $booking_notice ) : ?>
			<p class="mb-8 rounded-md border border-red-500/40 bg-[#1a1411] px-4 py-3 text-[15px] text-[#F4EFE8]" role="alert"><?php esc_html_e( 'Something went wrong. Please check the form and try again.', 'custom-manhattan-laser-theme' ); ?></p>
		<?php endif; ?>

		<h2 id="ml-booking-heading" class="mb-8 font-display text-[32px] leading-[110%] text-[#F4EFE8] md:mb-14 md:text-[48px] px-4 md:px-0">
			<?php esc_html_e( 'Book your Botox appointment today at Manhattan Laser.', 'custom-manhattan-laser-theme' ); ?>
		</h2>

		<div id="ml-booking-feedback" class="ml-booking-feedback mb-8 w-full max-w-full rounded-md border px-4 py-3 text-[15px] font-sans text-[#F4EFE8]" role="status" aria-live="polite" hidden></div>

		<form class="ml-booking-form flex w-full max-w-full flex-col items-center !bg-[#18100D] md:!bg-transparent px-4 md:px-0 py-8 md:py-0 " id="ml-booking-form" method="post" action="#" novalidate>
			<?php wp_nonce_field( 'ml_booking_form', 'ml_booking_nonce' ); ?>
			<input type="hidden" name="ml_booking_treatment_id" value="<?php echo esc_attr( (string) (int) $post_id ); ?>">
			<p class="ml-booking-honeypot absolute -left-[9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
				<label for="ml-booking-website"><?php esc_html_e( 'Website', 'custom-manhattan-laser-theme' ); ?></label>
				<input type="text" name="ml_booking_website" id="ml-booking-website" value="" tabindex="-1" autocomplete="off">
			</p>

			<div class="grid grid-cols-1 gap-12 md:grid-cols-2 md:gap-16 lg:gap-16 w-full">
				<div>
					<p class="mb-6 font-display text-[20px] md:text-[24px] uppercase text-[#F4EFE8]"><?php esc_html_e( 'Contact information', 'custom-manhattan-laser-theme' ); ?></p>
					<div class="flex flex-col gap-8 md:gap-10">
						<div class="ml-booking-field">
							<label class="sr-only" for="ml-booking-first-name"><?php esc_html_e( 'Name', 'custom-manhattan-laser-theme' ); ?></label>
							<input class="ml-booking-input font-sans" id="ml-booking-first-name" type="text" name="ml_booking_first_name" autocomplete="given-name" placeholder="<?php echo esc_attr( __( 'Name', 'custom-manhattan-laser-theme' ) ); ?>" required>
						</div>
						<div class="ml-booking-field">
							<label class="sr-only" for="ml-booking-last-name"><?php esc_html_e( 'Surname', 'custom-manhattan-laser-theme' ); ?></label>
							<input class="ml-booking-input font-sans" id="ml-booking-last-name" type="text" name="ml_booking_last_name" autocomplete="family-name" placeholder="<?php echo esc_attr( __( 'Surname', 'custom-manhattan-laser-theme' ) ); ?>" required>
						</div>
						<div class="ml-booking-field">
							<label class="sr-only" for="ml-booking-phone"><?php esc_html_e( 'Phone number', 'custom-manhattan-laser-theme' ); ?></label>
							<input class="ml-booking-input font-sans" id="ml-booking-phone" type="tel" name="ml_booking_phone" autocomplete="tel" placeholder="<?php echo esc_attr( __( 'Phone number', 'custom-manhattan-laser-theme' ) ); ?>">
						</div>
						<div class="ml-booking-field">
							<label class="sr-only" for="ml-booking-email"><?php esc_html_e( 'Email', 'custom-manhattan-laser-theme' ); ?></label>
							<input class="ml-booking-input font-sans" id="ml-booking-email" type="email" name="ml_booking_email" autocomplete="email" placeholder="<?php echo esc_attr( __( 'Email', 'custom-manhattan-laser-theme' ) ); ?>" required>
						</div>
					</div>
				</div>
				<div>
					<p class="mb-6 font-display text-[20px] md:text-[24px] uppercase text-[#F4EFE8]"><?php esc_html_e( 'Appointment details', 'custom-manhattan-laser-theme' ); ?></p>
					<div class="flex flex-col gap-8 md:gap-10">
						<div class="ml-booking-field ml-booking-cs" id="ml-booking-cs-city">
							<label class="sr-only" id="ml-booking-lbl-city" for="ml-booking-sel-city"><?php esc_html_e( 'City', 'custom-manhattan-laser-theme' ); ?></label>
							<select class="ml-booking-cs__native font-sans" id="ml-booking-sel-city" name="ml_booking_city" required tabindex="-1">
								<?php foreach ( $booking_cities as $val => $lab ) : ?>
									<option value="<?php echo esc_attr( $val ); ?>" <?php selected( '', $val ); ?>><?php echo esc_html( $lab ); ?></option>
								<?php endforeach; ?>
							</select>
							<button type="button" class="ml-booking-cs__trigger font-sans" aria-haspopup="listbox" aria-expanded="false" aria-controls="ml-booking-cs-city-list" aria-labelledby="ml-booking-lbl-city">
								<span class="ml-booking-cs__value"></span>
								<span class="ml-booking-cs__chev" aria-hidden="true"></span>
							</button>
							<ul class="ml-booking-cs__list font-sans" id="ml-booking-cs-city-list" role="listbox" hidden></ul>
						</div>
						<div class="ml-booking-field ml-booking-cs" id="ml-booking-cs-doctor">
							<label class="sr-only" id="ml-booking-lbl-doctor" for="ml-booking-sel-doctor"><?php esc_html_e( 'Doctor', 'custom-manhattan-laser-theme' ); ?></label>
							<select class="ml-booking-cs__native font-sans" id="ml-booking-sel-doctor" name="ml_booking_doctor" required tabindex="-1">
								<?php foreach ( $booking_doctors as $val => $lab ) : ?>
									<option value="<?php echo esc_attr( $val ); ?>" <?php selected( (string) (int) $post_id, $val ); ?>><?php echo esc_html( $lab ); ?></option>
								<?php endforeach; ?>
							</select>
							<button type="button" class="ml-booking-cs__trigger font-sans" aria-haspopup="listbox" aria-expanded="false" aria-controls="ml-booking-cs-doctor-list" aria-labelledby="ml-booking-lbl-doctor">
								<span class="ml-booking-cs__value"></span>
								<span class="ml-booking-cs__chev" aria-hidden="true"></span>
							</button>
							<ul class="ml-booking-cs__list font-sans" id="ml-booking-cs-doctor-list" role="listbox" hidden></ul>
						</div>
						<div class="ml-booking-field ml-booking-cs" id="ml-booking-cs-service">
							<label class="sr-only" id="ml-booking-lbl-service" for="ml-booking-sel-service"><?php esc_html_e( 'Service', 'custom-manhattan-laser-theme' ); ?></label>
							<select class="ml-booking-cs__native font-sans" id="ml-booking-sel-service" name="ml_booking_service" required tabindex="-1">
								<option value=""><?php esc_html_e( 'Choose a service', 'custom-manhattan-laser-theme' ); ?></option>
								<?php foreach ( $treatments_for_booking as $tpost ) : ?>
									<option value="<?php echo esc_attr( (string) $tpost->ID ); ?>" <?php selected( (int) $post_id, (int) $tpost->ID ); ?>><?php echo esc_html( get_the_title( $tpost ) ); ?></option>
								<?php endforeach; ?>
							</select>
							<button type="button" class="ml-booking-cs__trigger font-sans" aria-haspopup="listbox" aria-expanded="false" aria-controls="ml-booking-cs-service-list" aria-labelledby="ml-booking-lbl-service">
								<span class="ml-booking-cs__value"></span>
								<span class="ml-booking-cs__chev" aria-hidden="true"></span>
							</button>
							<ul class="ml-booking-cs__list font-sans" id="ml-booking-cs-service-list" role="listbox" hidden></ul>
						</div>
						<div class="ml-booking-field">
							<label class="sr-only" for="ml-booking-date"><?php esc_html_e( 'Date', 'custom-manhattan-laser-theme' ); ?></label>
							<input class="ml-booking-input ml-booking-input--date font-sans" id="ml-booking-date" type="date" name="ml_booking_date" required min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>" title="<?php echo esc_attr( __( 'Choose a date', 'custom-manhattan-laser-theme' ) ); ?>">
						</div>
						<div class="ml-booking-field ml-booking-cs" id="ml-booking-cs-time">
							<label class="sr-only" id="ml-booking-lbl-time" for="ml-booking-sel-time"><?php esc_html_e( 'Time', 'custom-manhattan-laser-theme' ); ?></label>
							<select class="ml-booking-cs__native font-sans" id="ml-booking-sel-time" name="ml_booking_time" required tabindex="-1">
								<option value=""><?php esc_html_e( 'Choose time', 'custom-manhattan-laser-theme' ); ?></option>
								<?php foreach ( $booking_time_slots as $slot ) : ?>
									<option value="<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $slot ); ?></option>
								<?php endforeach; ?>
							</select>
							<button type="button" class="ml-booking-cs__trigger font-sans" aria-haspopup="listbox" aria-expanded="false" aria-controls="ml-booking-cs-time-list" aria-labelledby="ml-booking-lbl-time">
								<span class="ml-booking-cs__value"></span>
								<span class="ml-booking-cs__chev" aria-hidden="true"></span>
							</button>
							<ul class="ml-booking-cs__list font-sans" id="ml-booking-cs-time-list" role="listbox" hidden></ul>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-8 w-full md:mt-10 flex flex-col items-center">
				<label class="ml-booking-privacy flex cursor-pointer items-center gap-[10px] font-sans text-[14px] leading-snug text-[#F4EFE8] md:gap-3 md:text-[15px]">
					<input class="ml-booking-checkbox" id="ml-booking-privacy" type="checkbox" name="ml_booking_privacy" value="1" required aria-required="true">
					<span class="ml-booking-privacy__text">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s: privacy policy URL */
								__( 'I have read and agree to the <a class="ml-booking-privacy__link" href="%s">Privacy policy</a>', 'custom-manhattan-laser-theme' ),
								esc_url( $booking_privacy_url )
							)
						);
						?>
					</span>
				</label>
			</div>

			<div class="mt-5 w-full">
				<button type="submit" class="ml-booking-submit group relative flex items-center justify-between sm:justify-center h-fit w-full  rounded-full bg-[#1F2A44] py-2.5 pl-5 pr-7 text-[16px] text-[#F4EFE8] transition-opacity disabled:opacity-70 gap-6 md:py-3">
					<span class="ml-booking-submit__label whitespace-nowrap text-left transition-transform duration-300 ease-out group-hover:translate-x-1">
						<?php esc_html_e( 'Make an appointment', 'custom-manhattan-laser-theme' ); ?>
					</span>
					<span class="ml-booking-submit__right flex min-h-4 min-w-4 shrink-0 items-center justify-end gap-7 md:gap-20">
						<span class="ml-booking-submit__spinner" aria-hidden="true"></span>
						<span class="ml-booking-submit__tail relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
							<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
							<span class="absolute left-1/2 top-1/2 h-1 w-1 -translate-x-1/2 -translate-y-1/2 translate-x-[15px] rounded-full bg-[#F4EFE8] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
						</span>
					</span>
				</button>
			</div>
		</form>
	</div>
</section>

<?php
get_footer();
