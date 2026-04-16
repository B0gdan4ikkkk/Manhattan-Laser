<?php
/**
 * Template Name: Our medical team
 * Template Post Type: page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$post_id = get_queried_object_id();
$hero_img_url = get_the_post_thumbnail_url( $post_id, 'large' );
if ( ! $hero_img_url ) {
	$hero_img_url = get_template_directory_uri() . '/img/hero-bg.webp';
}
$page_title = get_the_title( $post_id ) ? get_the_title( $post_id ) : __( 'Our medical team', 'custom-manhattan-laser-theme' );
$short_desc = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : '';
$page_url   = get_permalink( $post_id );

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
?>

<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo esc_attr( $page_title ); ?>" class="w-full object-cover absolute top-0 left-0 z-[-999] hero-img-about hidden md:flex">
<div class="w-full absolute top-0 left-0 z-[-10] hero-img-about bg-[#000000]/30 hidden md:flex"></div>
<section class="flex flex-col items-start justify-start hero-section-about ">
	<div class="container">
		<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
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
    <div class="container flex flex-col items-center justify-center h-full">
        <div class="flex flex-col items-center justify-center w-full lg:items-end pt-12 md:pt-0 pb-20 md:pb-0">
            <div class="flex flex-col items-center justify-center w-full text-center">
                <h1 class="hero-title-animate text-[36px] md:text-[96px] text-[#F4EFE8] font-display mb-5  leading-[100%] text-center">
                    Meet Our Medical Team.
                </h1>
                <p class="text-[16px] text-[#F4EFE8] md:max-w-[542px] text-center max-w-[330px]">
                Our team of board-certified medical experts combines clinical rigor with a refined aesthetic eye to deliver transformative results that honor your natural beauty and promote long-term skin longevity.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="medical-team-legacy bg-[#F5F0E8] text-[#313131] py-12 md:py-20" aria-label="<?php esc_attr_e( 'Legacy and trust', 'custom-manhattan-laser-theme' ); ?>">
	<div class="container">
		<div class="grid grid-cols-1 gap-10 md:grid-cols-2 md:items-start md:gap-x-8 lg:gap-x-14">
			<div class="min-w-0">
				<h2 class="max-w-none font-display text-[30px] font-normal leading-[1.08] tracking-tight text-[#1a1a1a] md:max-w-[350px] md:text-[48px] md:leading-[105%]">
					<?php esc_html_e( 'A Legacy of Proven Trust', 'custom-manhattan-laser-theme' ); ?>
				</h2>
			</div>
			<div class="medical-team-legacy-counters grid w-full grid-cols-2 items-start gap-x-4 min-[480px]:gap-x-6 md:flex md:items-center md:justify-between md:gap-x-0">
				<div class="min-w-0">
					<p
						class="font-display text-[48px] font-normal leading-none text-[#1a1a1a] tabular-nums min-[400px]:text-[55px] md:text-[96px]"
						data-counter="99"
						data-suffix="%"
					><?php echo esc_html( '0%' ); ?></p>
					<p class="mt-2 text-[14px] leading-snug text-[#313131]/80 md:mt-0.5 md:text-[16px] md:text-[#313131]">
						<?php esc_html_e( 'Patient satisfaction rate', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>
				<div class="min-w-0 md:pr-20">
					<p
						class="font-display text-[48px] font-normal leading-none text-[#1a1a1a] tabular-nums min-[400px]:text-[55px] md:text-[96px]"
						data-counter="50"
						data-suffix="k+"
					><?php echo esc_html( '0k+' ); ?></p>
					<p class="mt-2 text-[14px] leading-snug text-[#313131]/80 md:mt-0.5 md:text-[16px] md:text-[#313131]">
						<?php esc_html_e( 'Successful aesthetic procedures', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>
			</div>
		</div>

		<div class="mt-8 md:mt-10">
			<div
				class="flex min-h-[300px] w-full items-center justify-center bg-[#b5b2ad] md:min-h-[400px] lg:min-h-[500px]"
				role="img"
				aria-label="<?php esc_attr_e( 'Team image placeholder', 'custom-manhattan-laser-theme' ); ?>"
			>
				<svg class="h-14 w-14 text-[#8f8c87] md:h-16 md:w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3A1.5 1.5 0 0 0 1.5 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008H12V8.25Z" />
				</svg>
			</div>
		</div>

		<div class="mt-6 grid grid-cols-1 text-[15px] leading-snug text-[#313131]/90 md:mt-4 md:grid-cols-2 md:text-[16px] md:leading-relaxed md:text-[#313131]">
			<div class="hidden md:block" aria-hidden="true"></div>
			<div class="grid grid-cols-2 gap-x-4 items-start text-left min-[480px]:gap-x-6 md:flex md:max-w-none md:justify-between md:gap-x-8">
				<div class="min-w-0 md:max-w-[48%]">
					<?php esc_html_e( 'Beyond specialists: masters of clinical precision committed to the highest standards of medical integrity.', 'custom-manhattan-laser-theme' ); ?>
				</div>
				<div class="min-w-0 md:max-w-[48%]">
					<?php esc_html_e( 'A lifelong commitment to safety, continuous education, and uncompromising clinical excellence.', 'custom-manhattan-laser-theme' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
	$doctor_slides = array();
	if ( post_type_exists( 'doctor' ) ) {
		$doctors_query = new WP_Query(
			array(
				'post_type'      => 'doctor',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			)
		);
		if ( $doctors_query->have_posts() ) {
			while ( $doctors_query->have_posts() ) {
				$doctors_query->the_post();
				$img_id  = get_post_thumbnail_id();
				$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';
				$doctor_slides[] = array(
					'image' => $img_url,
					'title' => get_the_title(),
					'role'  => (string) get_post_meta( get_the_ID(), 'doctor_role', true ),
					'link'  => get_permalink(),
				);
			}
			wp_reset_postdata();
		}
	}
?>
<?php if ( ! empty( $doctor_slides ) ) : ?>
<section class="treatments-section bg-[#F4EFE8] py-16 md:py-24">
	<div class="treatments-section__row flex flex-col lg:flex-row lg:items-start">
		<div class="treatments-section__text-wrap shrink-0 pr-6 lg:pr-10">
			<div class="treatments-section__text max-w-[380px]">
				<h2 class="treatments-section__title font-display text-[32px] leading-[100%] text-[#1a1a1a] md:text-[48px]">
					<?php esc_html_e( 'Medical Team', 'custom-manhattan-laser-theme' ); ?>
				</h2>
				<p class="treatments-section__sub mt-5 text-[16px] leading-snug text-[#313131]/80">
					<?php esc_html_e( 'A team of elite specialists united by a singular vision: achieving flawless results through clinical precision, continuous innovation, and bespoke care for every patient.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
		</div>

		<div class="treatments-section__slider relative mt-10 flex min-w-0 flex-1 flex-col px-5 md:px-0 lg:mt-0 lg:pr-0">
			<div class="swiper treatments-swiper order-1 w-full lg:order-2">
				<div class="swiper-wrapper">
					<?php foreach ( $doctor_slides as $slide ) : ?>
					<div class="swiper-slide">
						<div class="treatments-card flex flex-col">
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__image relative w-full overflow-hidden bg-[#e8e4df]">
								<?php if ( $slide['image'] ) : ?>
									<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" class="h-[350px] w-full object-cover grayscale lg:h-[385px]" loading="lazy">
								<?php else : ?>
									<div class="flex h-[350px] w-full items-center justify-center text-sm text-[#313131]/35 lg:h-[385px]"><?php esc_html_e( 'Photo', 'custom-manhattan-laser-theme' ); ?></div>
								<?php endif; ?>
							</a>
							<h3 class="treatments-card__title mt-3 font-display text-[20px] text-[#313131] md:mt-5 md:text-[24px]"><a href="<?php echo esc_url( $slide['link'] ); ?>" class="transition-opacity hover:opacity-80"><?php echo esc_html( $slide['title'] ); ?></a></h3>
							<?php if ( $slide['role'] !== '' ) : ?>
							<p class="treatments-card__desc mt-4 text-[16px] leading-snug text-[#313131]/80 line-clamp-3 lg:line-clamp-none"><?php echo esc_html( $slide['role'] ); ?></p>
							<?php endif; ?>
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__link relative mt-4 inline-block w-fit text-[16px] lowercase text-[#313131]/85 transition-opacity after:absolute after:left-[-3%] after:top-[95%] after:h-px after:w-[106%] after:bg-[#313131]/50 after:content-[''] hover:text-[#313131] hover:after:bg-[#313131]"><?php esc_html_e( 'learn more', 'custom-manhattan-laser-theme' ); ?></a>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="treatments-section__nav relative order-2 mt-8 flex justify-end gap-6 md:order-first md:mb-10 md:mt-0 md:mr-12">
				<button type="button" class="treatments-swiper-prev group flex items-center justify-center opacity-70 transition-opacity hover:opacity-100" aria-label="<?php esc_attr_e( 'Previous', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="treatments-swiper-arrow h-2 w-14 brightness-0 md:h-2.5 md:w-16" width="28" height="8">
				</button>
				<button type="button" class="treatments-swiper-next group flex items-center justify-center opacity-70 transition-opacity hover:opacity-100" aria-label="<?php esc_attr_e( 'Next', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="treatments-swiper-arrow h-2 w-14 brightness-0 md:h-2.5 md:w-16" width="28" height="8">
				</button>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="why-section bg-[#18100D] px-0 py-16 md:py-24" aria-label="<?php esc_attr_e( 'Why choose us', 'custom-manhattan-laser-theme' ); ?>">
	<div class="">
		<div class="why-section__top text-left  md:max-w-[1400px] px-5 md:mx-auto">
			<h2 class="why-section__title mb-8 max-w-none font-display text-[26px] font-normal leading-[1.12] text-[#F4EFE8] min-[400px]:text-[28px] md:mb-10 md:max-w-[1159px] md:text-[48px] lg:text-[64px] md:leading-[100%]">
				<?php esc_html_e( 'Botox NYC – Safe & Effective Injections in Midtown, Upper East Side & Brooklyn', 'custom-manhattan-laser-theme' ); ?>
			</h2>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4 pb-8 min-[480px]:gap-x-6 md:gap-x-10 md:pb-10">
				<p class="hidden md:block" aria-hidden="true"></p>
				<div class="flex items-center justify-between gap-8">
					<p class="why-section__intro min-w-0 text-[#F4EFE8] md:text-[#F4EFE8]/80 text-[16px] md:text-[20px] max-w-[328px]">
						<?php esc_html_e( 'We combine FDA-cleared technologies with clinical expertise for maximum safety and precision.', 'custom-manhattan-laser-theme' ); ?>
					</p>
					<p class="why-section__intro min-w-0 text-[#F4EFE8]/80 text-[14px] md:text-[16px] md:text-[#F4EFE8]/80">
						<?php esc_html_e( 'Our treatments deliver natural-looking results rooted in evidence-based medicine and skin longevity.', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>
			</div>
		</div>

		<div class="why-section__cols mt-8 grid grid-cols-2 gap-px bg-[#F4EFE8]/10 py-px md:mt-10 lg:mt-[92px] lg:grid-cols-4">
			<div class="bg-[#18100D] p-5 min-[480px]:p-6 md:p-8 md:pt-12 md:pl-10 md:pr-6 md:pb-7 lg:pl-10 relative after:absolute after:w-px after:bg-[#F4EFE8]/5 after:-right-px after:top-[-635px] lg:after:h-[635px] after:content-[''] after:z-0">
				<h3 class="why-section__col-title mb-3 font-display text-[15px] font-normal leading-snug text-[#F4EFE8] min-[400px]:text-[16px] md:mb-9 md:text-[22px] lg:text-[24px]">
					<?php esc_html_e( 'Uncompromising Medical Standards.', 'custom-manhattan-laser-theme' ); ?>
				</h3>
				<p class="why-section__col-text font-sans text-[14px] leading-snug text-[#F4EFE8]/85 min-[400px]:text-[15px] md:text-[16px] md:leading-relaxed md:text-[#F4EFE8]">
					<?php esc_html_e( 'We utilize only FDA-cleared technologies and MD-led protocols to ensure every procedure is as safe as it is effective.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="bg-[#18100D] p-5 min-[480px]:p-6 md:p-8 md:pt-12 md:px-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-left-px after:bottom-[-400px] lg:after:h-[400px] after:content-[''] after:z-0 before:w-px before:bg-[#F4EFE8]/5 before:absolute before:-right-px before:bottom-[-400px] lg:before:h-[400px] before:content-[''] before:z-0">
				<h3 class="why-section__col-title mb-3 font-display text-[15px] font-normal leading-snug text-[#F4EFE8] min-[400px]:text-[16px] md:mb-9 md:text-[22px] lg:text-[24px]">
					<?php esc_html_e( 'The Art of Subtle Enhancement.', 'custom-manhattan-laser-theme' ); ?>
				</h3>
				<p class="why-section__col-text font-sans text-[14px] leading-snug text-[#F4EFE8]/85 min-[400px]:text-[15px] md:text-[16px] md:leading-relaxed md:text-[#F4EFE8]">
					<?php esc_html_e( 'Our philosophy is to honor your unique anatomy, delivering results that refresh and rejuvenate without ever looking “done”.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="bg-[#18100D] p-5 min-[480px]:p-6 md:p-8 md:pt-12 md:px-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-right-px after:bottom-[-400px] lg:after:h-[400px] after:content-[''] before:w-px before:bg-[#F4EFE8]/5 after:z-0 before:absolute before:-left-px before:top-[-635px] lg:before:h-[635px] before:content-[''] before:z-0">
				<h3 class="why-section__col-title mb-3 font-display text-[15px] font-normal leading-snug text-[#F4EFE8] min-[400px]:text-[16px] md:mb-9 md:text-[22px] lg:text-[24px]">
					<?php esc_html_e( 'Scientifically-Proven Excellence.', 'custom-manhattan-laser-theme' ); ?>
				</h3>
				<p class="why-section__col-text font-sans text-[14px] leading-snug text-[#F4EFE8]/85 min-[400px]:text-[15px] md:text-[16px] md:leading-relaxed md:text-[#F4EFE8]">
					<?php esc_html_e( 'Every treatment we offer is rooted in clinical rigor and high-fidelity medical research for guaranteed, predictable outcomes.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="bg-[#18100D] p-5 min-[480px]:p-6 md:p-8 md:pt-12 md:pr-10 md:pl-6 md:pb-7 relative after:absolute after:-left-px after:top-[-635px] lg:after:h-[635px] after:content-[''] after:z-0 after:w-px after:bg-[#F4EFE8]/5">
				<h3 class="why-section__col-title mb-3 font-display text-[15px] font-normal leading-snug text-[#F4EFE8] min-[400px]:text-[16px] md:mb-9 md:text-[22px] lg:text-[24px]">
					<?php esc_html_e( 'A Commitment to Skin Longevity.', 'custom-manhattan-laser-theme' ); ?>
				</h3>
				<p class="why-section__col-text font-sans text-[14px] leading-snug text-[#F4EFE8]/85 min-[400px]:text-[15px] md:text-[16px] md:leading-relaxed md:text-[#F4EFE8]">
					<?php esc_html_e( 'Beyond immediate aesthetics, we focus on cellular health and long-term regeneration to sustain your radiance for years to come.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<section class="collagen-decline-section relative py-20 md:py-[100px] mt-10 md:mt-16">
    <div class="bg-[#201511] blur-[40px] absolute top-0 left-0 w-full h-full"></div>
	<div class="container relative z-10 text-center">
		<h2 class="collagen-decline__title font-display text-[16px] text-[#F4EFE8] md:text-[48px] max-w-[926px] mx-auto leading-[100%] ">
         <span class="block md:hidden uppercase">Every procedure is medically reviewed and performed by board-certified providers.</span>
         <span class="hidden md:block">Every procedure is medically reviewed and performed by board-certified providers.</span>
		</h2>
	</div>
</section>

<?php
$medical_marquee_icon = get_template_directory_uri() . '/img/medical-icon.png';
$medical_marquee_count = 14;
?>
<section class="medical-marquee-section" aria-hidden="true">
	<div class="medical-marquee">
		<div class="medical-marquee__track">
			<div class="medical-marquee__group">
				<?php
				for ( $mi = 0; $mi < $medical_marquee_count; $mi++ ) :
					?>
				<div class="medical-marquee__cell">
					<div class="medical-marquee__circle">
						<img src="<?php echo esc_url( $medical_marquee_icon ); ?>" alt="" loading="lazy" decoding="async" width="80" height="80">
					</div>
				</div>
					<?php
				endfor;
				?>
			</div>
			<div class="medical-marquee__group">
				<?php
				for ( $mi = 0; $mi < $medical_marquee_count; $mi++ ) :
					?>
				<div class="medical-marquee__cell">
					<div class="medical-marquee__circle">
						<img src="<?php echo esc_url( $medical_marquee_icon ); ?>" alt="" loading="lazy" decoding="async" width="80" height="80">
					</div>
				</div>
					<?php
				endfor;
				?>
			</div>
			<div class="medical-marquee__group">
				<?php
				for ( $mi = 0; $mi < $medical_marquee_count; $mi++ ) :
					?>
				<div class="medical-marquee__cell">
					<div class="medical-marquee__circle">
						<img src="<?php echo esc_url( $medical_marquee_icon ); ?>" alt="" loading="lazy" decoding="async" width="80" height="80">
					</div>
				</div>
					<?php
				endfor;
				?>
			</div>
			<div class="medical-marquee__group">
				<?php
				for ( $mi = 0; $mi < $medical_marquee_count; $mi++ ) :
					?>
				<div class="medical-marquee__cell">
					<div class="medical-marquee__circle">
						<img src="<?php echo esc_url( $medical_marquee_icon ); ?>" alt="" loading="lazy" decoding="async" width="80" height="80">
					</div>
				</div>
					<?php
				endfor;
				?>
			</div>
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
									<option value="<?php echo esc_attr( $val ); ?>" <?php selected( '', $val ); ?>><?php echo esc_html( $lab ); ?></option>
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

<?php get_footer(); ?>