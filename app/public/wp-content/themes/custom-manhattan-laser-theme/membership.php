<?php
/**
 * Template Name: Membership
 * Template Post Type: page
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$post_id = get_queried_object_id();
if ( ! $post_id && is_singular( 'page' ) ) {
	$post_id = get_the_ID();
}

$hero_img_url = $post_id ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';
if ( ! $hero_img_url ) {
	$hero_img_url = get_template_directory_uri() . '/img/hero-bg.webp';
}

$page_title = $post_id && get_the_title( $post_id ) ? get_the_title( $post_id ) : __( 'Membership & Payment Plans', 'custom-manhattan-laser-theme' );
$page_url   = $post_id ? get_permalink( $post_id ) : '';

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

$is_term = false;
$term    = null;

$cta_url   = home_url( '/contact/' );
$cta_label = __( 'Book a Consultation', 'custom-manhattan-laser-theme' );

?>


<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo $is_term ? esc_attr( $term->name ) : 'Hero Image'; ?>" class="w-full object-cover absolute top-0 left-0 z-[100] hero-img-single">
<div class="w-full absolute top-0 left-0 z-[100] hero-img-single bg-[#000000]/30"></div>
<section class="flex flex-col items-start justify-between hero-section-single relative z-[100]">
	<div class="container">
		<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
			<ul class="flex gap-1.5 items-center text-[12px] md:text-[16px]">
				<?php
				$bc_count = count( $breadcrumb_items );
				for ( $bc_i = 0; $bc_i < $bc_count; $bc_i++ ) :
					$bc_item   = $breadcrumb_items[ $bc_i ];
					$bc_name   = isset( $bc_item['name'] ) ? (string) $bc_item['name'] : '';
					$bc_url    = isset( $bc_item['url'] ) ? (string) $bc_item['url'] : '';
					$is_last   = $bc_i === $bc_count - 1;
					?>
					<?php if ( ! $is_last ) : ?>
						<li class="text-[#F4EFE8]/50 hover:text-[#F4EFE8] transition-colors duration-300 ease-out">
							<a href="<?php echo esc_url( $bc_url ); ?>"><?php echo esc_html( $bc_name ); ?></a>
						</li>
					<?php else : ?>
						<li><?php echo esc_html( $bc_name ); ?></li>
					<?php endif; ?>
					<?php if ( ! $is_last ) : ?>
						<li class="text-[#F4EFE8]/50">/</li>
					<?php endif; ?>
				<?php endfor; ?>
			</ul>
		</nav>

		<?php
		if ( function_exists( 'custom_manhattan_laser_render_breadcrumb_schema' ) ) {
			custom_manhattan_laser_render_breadcrumb_schema( $breadcrumb_items );
		}
		?>
	</div>
    <div class="container flex flex-col items-center justify-center h-full">
        <div class="flex flex-col items-center justify-center w-full lg:items-end">
            <div class="mb-8 flex w-full flex-col items-center md:mb-8">
                <h1 class="hero-title-animate mb-6 max-w-[300px] md:max-w-full text-center text-[36px] font-display leading-[100%] text-white md:mb-5 md:text-[96px]">
				Membership & Payment Plans
                </h1>
					<p class="text-[#F4EFE8] text-[16px] max-w-[397px] mx-auto text-center">Flexible plans designed to make advanced aesthetic treatments more accessible.</p>
     </div>
                
        </div>
    </div>
    <div class="mb-8  md:mx-auto w-full max-w-[1400px] px-5">
				<a		href="<?php echo esc_url( $cta_url ); ?>"
					class="flex gap-10 rounded-full bg-[#F4EFE824] pl-5 pr-7 py-4 md:py-5 text-[16px] backdrop-blur-[7px] relative group w-full justify-center items-center"

				>
					<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1 text-[#F4EFE8]">
						<?php echo esc_html( $cta_label ); ?>
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
			</a>
            </div>
</section>

<section class="collagen-decline-section relative py-8 md:pb-24 mt-0 md:mt-20">
	<div class="container relative z-10 text-center">
		<h2 class="membership-tiers__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] mx-auto mb-8 leading-[100%]">
            Membership Programs
		</h2>
	</div>
	<div class="container">
		<div class="membership-tiers__grid grid grid-cols-1 md:grid-cols-2 gap-7 mt-8 md:mt-16">
			<div class="relative z-0 border border-[#FFFFFF]">
			<div class="absolute left-[-1%] top-[-1%] z-10 h-[102%] w-[102%] backdrop-blur-[6.4px] bg-[#18100D80]"></div>
			<div class="relative z-20 grid grid-cols-1 gap-y-6 px-10 py-12 md:grid-cols-2 md:items-start md:gap-x-10 md:gap-y-8">
				<h3 class="relative mb-0 font-display text-[32px] uppercase leading-[100%] text-[#E5E1DA] md:col-start-1 md:row-start-1 md:mb-4 md:text-[#F4EFE8]">
					ESSENTIAL <br> MEMBERSHIP
				</h3>
				<ul class="flex flex-col gap-2.5 font-sans md:col-start-2 md:row-span-3 md:row-start-1 md:self-start" role="list">
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						10% off all treatments
					</li>
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						Complimentary skin analysis
					</li>
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						Priority booking
					</li>
				</ul>
				<p class="mb-0 font-sans text-[20px] text-white md:col-start-1 md:row-start-2 md:mb-12 md:text-[36px]">
					$399 <span class="text-[16px] text-[#E5E1DA]/85 md:text-[#F4EFE8]">month</span>
				</p>
				<a
					href="<?php echo esc_url( $cta_url ); ?>"
					class="flex h-fit w-fit items-center gap-7 rounded-full bg-[#1E2B43] py-2.5 pl-5 pr-7 text-[16px] relative group md:col-start-1 md:row-start-3 md:gap-20 md:bg-[#1F2A44] md:py-3"
				>
					<span class="whitespace-nowrap text-[#E5E1DA] transition-transform duration-300 ease-out group-hover:translate-x-1 md:text-[#F4EFE8]">Join Now</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#E5E1DA] md:border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#E5E1DA] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%] md:bg-[#F4EFE8]" aria-hidden="true"></span>
					</span>
				</a>
			</div>
			</div>
			<div class="relative z-0 border border-[#FFFFFF]">
			<div class="absolute left-[-1%] top-[-1%] z-10 h-[102%] w-[102%] backdrop-blur-[6.4px] bg-[#18100D80]"></div>
			<div class="relative z-20 grid grid-cols-1 gap-y-6 px-10 py-12 md:grid-cols-2 md:items-start md:gap-x-10 md:gap-y-8">
				<h3 class="relative mb-0 font-display text-[32px] uppercase leading-[100%] text-[#E5E1DA] md:col-start-1 md:row-start-1 md:mb-4 md:text-[#F4EFE8]">
					PREMIUM <br> MEMBERSHIP
				</h3>
				<ul class="flex flex-col gap-2.5 font-sans md:col-start-2 md:row-span-3 md:row-start-1 md:self-start" role="list">
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						10% off all treatments
					</li>
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						Complimentary skin analysis
					</li>
					<li class="flex items-center gap-1.5 text-[#E5E1DA] md:text-[#F4EFE8]">
						<span class="h-1 w-1 shrink-0 rounded-full bg-[#E5E1DA] md:bg-[#F4EFE8]" aria-hidden="true"></span>
						Priority booking
					</li>
				</ul>
				<p class="mb-0 font-sans text-[20px] text-white md:col-start-1 md:row-start-2 md:mb-12 md:text-[36px]">
					$599 <span class="text-[16px] text-[#E5E1DA]/85 md:text-[#F4EFE8]">month</span>
				</p>
				<a
					href="<?php echo esc_url( $cta_url ); ?>"
					class="flex h-fit w-fit items-center gap-7 rounded-full bg-[#1E2B43] py-2.5 pl-5 pr-7 text-[16px] relative group md:col-start-1 md:row-start-3 md:gap-20 md:bg-[#1F2A44] md:py-3"
				>
					<span class="whitespace-nowrap text-[#E5E1DA] transition-transform duration-300 ease-out group-hover:translate-x-1 md:text-[#F4EFE8]">Join Now</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#E5E1DA] md:border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#E5E1DA] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%] md:bg-[#F4EFE8]" aria-hidden="true"></span>
					</span>
				</a>
			</div>
			</div>
		</div>
	</div>

</section>


<section class="why-section bg-[#18100D] py-8 md:py-24" aria-labelledby="why-member-heading">
	<div class="">
		<div class="container text-center">
			<h2 id="why-member-heading" class="why-section__title font-display text-[32px] leading-[100%] text-[#F4EFE8] md:text-[48px] lg:text-[56px]">
				<?php esc_html_e( 'Why Become a Member', 'custom-manhattan-laser-theme' ); ?>
			</h2>
		</div>

		<div class="why-section__cols mt-8 grid gap-px bg-[#F4EFE8]/5 pt-px pb-px md:pb-0 grid-cols-2 lg:mt-[72px] lg:grid-cols-4">
			<div class="relative bg-[#18100D] px-5 py-8 md:pb-7 md:pl-10 md:pr-6 md:pt-12 ">
				<h3 class="why-section__col-title mb-4 font-display text-[20px] uppercase text-[#F4EFE8] md:mb-9 md:text-[32px]">
                    EXCLUSIVE <br/> DISCOUNTS
				</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8CC] md:text-[#F4EFE8] max-w-[230px]">
					<?php esc_html_e( 'Members receive special pricing on treatments.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="relative bg-[#18100D] px-5 py-8 md:px-6 md:pb-7 md:pt-12 ">
				<h3 class="why-section__col-title mb-4 font-display text-[20px] uppercase text-[#F4EFE8] md:mb-9 md:text-[32px]">
                 PRIORITY <br/> BOOKING
				</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8CC] md:text-[#F4EFE8] max-w-[184px]">
					<?php esc_html_e( 'Get preferred appointment times.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="relative bg-[#18100D] px-5 py-8 md:px-6 md:pb-7 md:pt-12 ">
				<h3 class="why-section__col-title mb-4 font-display text-[20px] uppercase text-[#F4EFE8] md:mb-9 md:text-[32px]">
                    PERSONALIZED <br/> SKIN PLANS
				</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8CC] md:text-[#F4EFE8] max-w-[210px]">
					<?php esc_html_e( 'Custom treatment plans from our experts.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="relative bg-[#18100D] px-5 py-8 md:px-6 md:pb-7 md:pl-6 md:pr-10 md:pt-12 ">
				<h3 class="why-section__col-title mb-4 font-display text-[20px] uppercase text-[#F4EFE8] md:mb-9 md:text-[32px]">
                MEMBER <br/> EVENTS
				</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8CC] md:text-[#F4EFE8] max-w-[183px]">
					<?php esc_html_e( 'Invitations to exclusive skincare events.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>


<section class="bg-[#F9F7F2] py-10 md:py-[66px]" aria-labelledby="flexible-payment-heading">
		<div class="max-w-[1400px] md:px-5 mx-auto flex flex-col items-center justify-center text-[#313131]">
			<div class="flex flex-col items-center  justify-center text-center px-5 md:px-0">
				<h2 id="flexible-payment-heading" class="why-section__title font-display text-[32px] leading-[100%] text-[#313131] md:text-[48px] mb-4">
					<?php esc_html_e( 'Flexible Payment Options', 'custom-manhattan-laser-theme' ); ?>
				</h2>
				<p class="text-[16px] max-w-[484px] hidden md:block">
					<?php esc_html_e( 'We offer convenient financing plans so you can begin your treatment today and pay over time.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>

			<div class="membership-pricing-table-wrap mt-8 md:mt-12 w-full container">
				<div class="membership-pricing-scroll">
					<table class="membership-pricing-table w-full  border-collapse text-left md:min-w-0">
						<thead>
							<tr>
								<th scope="col" class="membership-pricing-table__th"><?php esc_html_e( 'Treatment', 'custom-manhattan-laser-theme' ); ?></th>
								<th scope="col" class="membership-pricing-table__th"><?php esc_html_e( 'Total price', 'custom-manhattan-laser-theme' ); ?></th>
								<th scope="col" class="membership-pricing-table__th"><?php esc_html_e( 'Monthly payment', 'custom-manhattan-laser-theme' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="membership-pricing-table__td"><?php esc_html_e( 'Botox Package', 'custom-manhattan-laser-theme' ); ?></td>
								<td class="membership-pricing-table__td"><?php echo esc_html( '$900' ); ?></td>
								<td class="membership-pricing-table__td"><?php echo esc_html( '$75/month' ); ?></td>
							</tr>
							<tr>
								<td class="membership-pricing-table__td"><?php esc_html_e( 'Laser Treatment', 'custom-manhattan-laser-theme' ); ?></td>
								<td class="membership-pricing-table__td"><?php echo esc_html( '$1200' ); ?></td>
								<td class="membership-pricing-table__td"><?php echo esc_html( '$100/month' ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<p class="membership-pricing-hint md:hidden" role="note">
					<span class="membership-pricing-hint__icon" aria-hidden="true">&lt;&gt;</span>
					<span class="membership-pricing-hint__text text-[10px]"><?php esc_html_e( 'Swipe left or right to view the full pricing', 'custom-manhattan-laser-theme' ); ?></span>
				</p>
				<a href="#" class="flex items-center gap-7 md:gap-20 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit md:mx-auto mt-5 md:mt-8">
                    <span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1 text-[#FBF5E7]">
					Apply for Payment Plan
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
                    </a>
			</div>
		</div>
</section>

<section class="how-it-works max-md:bg-black py-10 text-[#F4EFE8] md:py-16 lg:py-20" aria-labelledby="how-it-works-heading">
	<div class="container">
		<h2 id="how-it-works-heading" class="how-it-works__title text-left font-display text-[32px] leading-[100%] text-white md:text-center md:text-[48px] md:text-[#F4EFE8]">
			<?php esc_html_e( 'How it Works', 'custom-manhattan-laser-theme' ); ?>
		</h2>
		<div class="how-it-works__rule mx-auto mt-5 hidden max-w-full border-t border-[#313131] md:mt-8 md:block" aria-hidden="true"></div>
		<ol class="how-it-works__grid m-0 mt-10 grid list-none grid-cols-1 p-0 md:mt-0 md:grid-cols-[auto_auto_auto] md:gap-0">
			<li class="how-it-works__cell flex min-h-0 items-start justify-start border-b border-[#ffffff1a] py-2 text-left text-[15px] uppercase text-[#F4EFE8]/85 md:min-h-[140px] md:items-center md:justify-center md:border-b-0 md:border-r md:border-[#313131] md:px-8 md:py-0 md:pt-8 md:pb-5 md:text-center md:text-[20px] md:text-[#F4EFE8]">
				<?php esc_html_e( '01. Choose your treatment or membership', 'custom-manhattan-laser-theme' ); ?>
			</li>
			<li class="how-it-works__cell flex min-h-0 items-start justify-start border-b border-[#ffffff1a] py-2 text-left text-[15px] uppercase text-[#F4EFE8]/85 md:min-h-[140px] md:items-center md:justify-center md:border-b-0 md:border-r md:border-[#313131] md:px-8 md:py-0 md:pt-8 md:pb-5 md:text-center md:text-[20px] md:text-[#F4EFE8]">
				<?php esc_html_e( '02. Select a payment plan', 'custom-manhattan-laser-theme' ); ?>
			</li>
			<li class="how-it-works__cell flex min-h-0 items-start justify-start py-2 text-left text-[15px] uppercase text-[#F4EFE8]/85 md:min-h-[140px] md:items-center md:justify-center md:px-8 md:py-0 md:pt-8 md:pb-5 md:text-center md:text-[20px] md:text-[#F4EFE8]">
				<?php esc_html_e( '03. Start your journey with our specialists', 'custom-manhattan-laser-theme' ); ?>
			</li>
		</ol>
	</div>
</section>

<?php
$faq_items = array(
	array(
		'q' => __( 'What does membership include?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'Membership is designed to reward consistency: preferred pricing on treatments, priority scheduling, complimentary skin analysis where applicable, and access to member-only events. Exact benefits depend on the tier you choose — we review everything with you before you enroll.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'How do flexible payment plans work?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'Payment plans let you spread the cost of a treatment or package over predictable monthly installments instead of paying in full upfront. After you apply and are approved, we align the plan with your care timeline so you can start when you are ready.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'Can I combine membership discounts with a payment plan?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'In many cases, member pricing applies to the services you select, and financing can be used for the balance according to the options available. Our team will walk through the numbers during your consultation so there are no surprises.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'How do I join or apply for financing?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'Choose the membership or treatment package that fits your goals, then complete a short application for a payment plan if you would like one. We confirm details with you in person or by phone, answer questions, and only move forward when you are comfortable.', 'custom-manhattan-laser-theme' ),
	),
);
$faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(),
);
foreach ( $faq_items as $faq_row ) {
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
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
<section class="faq-section bg-[#18100D] py-14 md:py-24 relative" id="faq" aria-labelledby="faq-heading">
<div class="before-after-section__bg-pattern absolute left-[-70%] bottom-[-50%] w-full h-[500px]  bg-[#201511] blur-[150px] z-0 hidden md:flex rounded-full"> </div>
	<div class="container">
		<div class="flex flex-col md:flex-row justify-between items-start gap-5 lg:gap-16 lg:items-start">
			<div class="max-w-xl">
				<p class="mb-5 md:mb-7 flex items-center gap-2 text-[15px] text-[#F4EFE8] md:text-[20px]">
					<span class="h-1 w-1 shrink-0 rounded-full bg-[#F4EFE8]" aria-hidden="true"></span>
					<?php esc_html_e( 'FAQ', 'custom-manhattan-laser-theme' ); ?>
				</p>
				<h2 id="faq-heading" class="font-display text-[32px] leading-[110%] text-[#F4EFE8] md:text-[48px] max-w-[350px] md:max-w-[380px]">
					<?php esc_html_e( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' ); ?>
				</h2>
				<p class="mt-5 max-w-[254px]  text-[15px] leading-[1.5] text-[#F4EFE8]/90 md:text-[16px] md:max-w-[297px]">
					<?php esc_html_e( 'Straightforward answers about membership benefits and payment options.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="faq-section__accordion min-w-0 md:max-w-[600px] w-full">
				<?php foreach ( $faq_items as $fi => $faq_item ) : ?>
				<details
					class="faq-item group border-b border-[#F4EFE80D] py-5"
					name="faq-membership"
					<?php echo 0 === $fi ? 'open' : ''; ?>
				>
					<summary class="faq-item__summary flex w-full cursor-pointer list-none items-start justify-between gap-6 bg-transparent p-0 text-left text-[#F4EFE8]">
						<span class="min-w-0 flex-1 font-display text-[18px] leading-snug md:text-[22px] lg:text-[24px]"><?php echo esc_html( $faq_item['q'] ); ?></span>
						<span class="faq-item__icons relative mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center text-[26px] font-light leading-none" aria-hidden="true">
							<span class="faq-item__icon-plus inline-flex items-center justify-center">+</span>
						</span>
					</summary>
					<div class="faq-item__answer pr-2 text-[15px] text-[#F4EFE8]/75 md:text-[16px] md:leading-relaxed md:pr-8 mt-4">
						<?php echo esc_html( $faq_item['a'] ); ?>
					</div>
				</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>



<section class="cta-section relative w-full cta-section flex items-center mt-10 md:mt-20">
	<img src="<?php echo esc_url( get_template_directory_uri() . '/img/cta-footer-bg.webp' ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
	<div class="container relative z-10 flex items-center justify-center">
		<div class="flex flex-col items-center justify-center">
			<h2 class="cta-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[110%] mb-4 max-w-[550px] text-center">
			Ready to elevate your skincare routine?
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
get_footer();
