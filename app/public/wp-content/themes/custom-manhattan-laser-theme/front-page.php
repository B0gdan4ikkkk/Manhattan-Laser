<?php get_header() ?>

<img src="<?php echo get_template_directory_uri() ?>/img/hero-bg.webp" alt="Hero Image" class="w-full object-cover absolute top-0 left-0 z-[-999] hero-img">
<section class="flex flex-col items-end justify-end hero-section">
    <div class="container flex flex-col items-start justify-center h-full">
        <div class="flex flex-col items-start justify-between w-full lg:items-end">
            <div class="flex flex-col items-start justify-start w-full">
                <h1 class="hero-title-animate text-[36px] md:text-[96px] text-[#F4EFE8] font-display mb-5 max-w-[333px] md:max-w-[910px] leading-[100%]">
                    Membership-Based Skin Longevity.
                </h1>
                <p class="text-[16px] text-[#F4EFE8] max-w-[350px] md:max-w-[400px] mb-8 md:mb-16">
                    MD-led collagen protocols using energy-based devices and regenerative injectables for long-term results.
                </p>
                <div class="flex flex-col w-full gap-8 md:gap-10 md:flex-row md:items-end justify-between">
                    <a href="<?php echo home_url('/membership/'); ?>" class="flex items-center gap-4 md:gap-10 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit">
                    <span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
                        Become a Member
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
                    </a>
                    <div class="hero-counters flex flex-col items-start gap-6 md:flex-row lg:items-center lg:gap-5">
                        <div class="flex flex-col items-start gap-1 md:gap-2">
                            <p class="text-[32px] md:text-[36px] text-[#F4EFE8]" data-counter="15" data-suffix="+">0+</p>
                            <p class="text-[16px] md:text-[20px] text-[#F4EFE8]">Years of Clinical Rigor.</p>
                        </div>
                        <div class="flex flex-col items-start gap-1 md:gap-2">
                            <p class="text-[32px] md:text-[36px] text-[#F4EFE8]" data-counter="94" data-suffix="%">0%</p>
                            <p class="text-[16px] md:text-[20px] text-[#F4EFE8]">Patient Loyalty Rate.</p>
                        </div>
                        <div class="flex flex-col items-start gap-1 md:gap-2">
                            <p class="text-[32px] md:text-[36px] text-[#F4EFE8]" data-counter="50" data-suffix="+">0+</p>
                            <p class="text-[16px] md:text-[20px] text-[#F4EFE8]">Bespoke Longevity Protocols.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll group relative flex h-[60px] w-full justify-center">
        <div class="hero-scroll-shadow pointer-events-none absolute inset-x-0 bottom-0 top-0 z-20" aria-hidden="true"></div>
        <a href="#collagen-method" class="hero-scroll-bounce relative z-10 inline-block">
            <svg class="hero-scroll-icon h-10 w-5 text-[#F4EFE8] transition-transform duration-200 ease-out hover:-translate-y-1" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M2 6 L10 14 L18 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 16 L10 24 L18 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
</section>

<section id="collagen-method" class="collagen-section my-8 md:my-24">
	<div class="md:max-w-[1400px] md:px-5 mx-auto">
		<h2 class="collagen-title text-center text-[32px] font-display uppercase text-[#F4EFE8] md:text-[64px] leading-[110%] max-w-[643px] mx-auto">COLLAGEN METHOD: 3 PILLARS</h2>

		<div class="collagen-grid mt-8 grid grid-cols-1 md:gap-6 md:mt-2 md:grid-cols-[1fr_auto_1fr] md:items-center md:gap-10 max-w-[643px] mx-auto">
			<div class="flex items-center justify-center collagen-pillar-card order-2 border-b border-b-[#FFFFFF0D] md:border-b-0 pb-3 md:py-4 md:order-none md:bg-transparent md:p-0 md:rounded-none">
				
				<p class="collagen-pillar inline text-center md:text-left  font-normal md:uppercase text-[#F4EFE8] text-[14px]  max-w-[230px] md:max-w-[145px]"><span class="collagen-pillar-num font-normal md:uppercase text-[#F4EFE8] text-[14px] md:hidden">01. </span> Preserve - collagen before visible decline.</p>
			</div>

			<div class="flex items-center justify-center collagen-image order-first w-auto md:order-none ">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/img/collagen-method.png' ); ?>" alt="Collagen Method" class="w-auto h-[88px] object-contain mb-6 md:mb-0" loading="lazy">
			</div>

			<div class="flex items-center justify-center collagen-pillar-card order-3 border-b border-b-[#FFFFFF0D] md:border-b-0 py-4 md:order-none md:bg-transparent md:p-0 md:rounded-none">
				
				<p class="collagen-pillar inline text-center  font-normal md:uppercase text-[#F4EFE8] text-[14px]  max-w-[238px] md:max-w-[150px] md:text-right"><span class="collagen-pillar-num  font-normal md:uppercase text-[#F4EFE8] text-[14px] md:hidden">02. </span> Repair - Stimulate structural renewal.</p>
			</div>

			<div class="flex items-center justify-center collagen-pillar-card order-4 pt-3 md:py-4 md:col-span-3 md:order-none md:bg-transparent md:p-0 md:rounded-none md:text-center">
				
				<p class="collagen-pillar collagen-pillar--bottom inline  font-normal md:uppercase text-[#F4EFE8] text-[14px] md:block md:w-fit md:mx-auto text-center max-w-[238px] md:max-w-none"><span class="collagen-pillar-num  font-normal uppercase text-[#F4EFE8] text-[14px] md:hidden">03. </span>Maintain - Make results predictable.</p>
			</div>
		</div>
	</div>
</section>

<section class="why-section py-8 md:py-24">
	<div class="">
		<div class="why-section__top flex flex-col md:flex-row justify-between md:items-center container">
			<div class="mb-5 md:mb-0">
				<p class="why-section__label mb-5 md:mb-7 flex items-center gap-2 text-[15px] md:text-[20px] text-[#F4EFE8]">
					<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
					Why us
				</p>
				<h2 class="why-section__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] max-w-[350px] md:max-w-[788px] leading-[100%]">
					Not a canvas for experiments, but an ecosystem that deserves strategy.
				</h2>
			</div>
			<div class="flex flex-col items-start md:items-end">
				<a href="<?php echo home_url('/about-the-doctor-medical-team/'); ?>" class="why-section__link order-2 md:order-none mb-0 md:mb-6 inline-flex items-center gap-2 text-[15px] uppercase text-[#F4EFE8] opacity-80 transition-opacity hover:opacity-100">
					MORE ABOUT US
					<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7v10"/>
					</svg>
				</a>
				<p class="why-section__intro order-1 md:order-none max-w-[422px] text-[16px] text-[#F4EFE8] mb-8 md:mb-0 md:hidden">We never begin a treatment “by eye.” Every care program is based on digital skin diagnostics and an analysis of your biomarkers. We understand what your cells need even before you notice the first changes in the mirror.</p>
				<p class="why-section__intro order-1 md:order-none max-w-[422px] text-[16px] text-[#F4EFE8] mb-8 md:mb-0 hidden md:block">
					We transform traditional skincare into personalized health management, where every protocol is based on in-depth digital diagnostics and the application of certified technologies to achieve long-term results at the cellular level — without altering your natural individuality.
				</p>
			</div>
		</div>

		<div class="why-section__cols grid grid-cols-1 gap-px md:grid-cols-2 lg:grid-cols-4 mt-8 lg:mt-[92px] bg-[#F4EFE8]/5 pt-px">
			<div class="bg-[#18100D] py-8 md:pt-12 md:pl-10 px-5 md:pr-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-right-px after:top-[-635px] lg:after:h-[635px] after:content-[''] after:z-[-20]">
				<h3 class="why-section__col-title mb-4 md:mb-9 text-[16px] font-display uppercase text-[#F4EFE8] md:text-[24px] ">DIAGNOSTICS INSTEAD OF ASSUMPTIONS</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8]">We never begin a treatment "by eye." Every care program is based on digital skin diagnostics and an analysis of your biomarkers. We understand what your cells need even before you notice the first changes in the mirror.</p>
			</div>
			<div class="bg-[#18100D] py-8 md:pt-12 px-5 md:px-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-left-px after:bottom-[-400px] lg:after:h-[400px] after:content-[''] after:z-[-20]">
				<h3 class="why-section__col-title mb-4 md:mb-9 text-[16px] font-display uppercase text-[#F4EFE8] md:text-[24px] ">FDA-CLEARED TECHNOLOGIES</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8]">We invest in equipment that represents the gold standard of global medicine. You receive predictable, safe results supported by clinical research — not marketing promises.</p>
			</div>
			<div class="bg-[#18100D] py-8 md:pt-12 px-5 md:px-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-right-px after:bottom-[-400px] lg:after:h-[400px] after:content-[''] after:z-[-20]">
				<h3 class="why-section__col-title mb-4 md:mb-9 text-[16px] font-display uppercase text-[#F4EFE8] md:text-[24px] ">AESTHETICS WITHOUT SIGNS OF INTERVENTION</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8]">Our expertise lies in intelligent rejuvenation. We preserve your natural facial expressions and unique features. The result is still you — simply in your most refined form: refreshed and radiant.</p>
			</div>
			<div class="bg-[#18100D] py-8 md:pt-12 md:pr-10 px-5 md:pl-6 md:pb-7 relative after:w-px after:bg-[#F4EFE8]/5 after:absolute after:-left-px after:top-[-635px] lg:after:h-[635px] after:content-[''] after:z-[-20]" >
				<h3 class="why-section__col-title mb-4 md:mb-9 text-[16px] font-display uppercase text-[#F4EFE8] md:text-[24px] ">365-DAY SUPPORT</h3>
				<p class="why-section__col-text text-[16px] text-[#F4EFE8]">Your skin doesn't live from appointment to appointment. We create a personalized roadmap for home care and nutritional support, ensuring your results last as long as possible.</p>
			</div>
		</div>
	</div>
</section>

<section class="collagen-decline-section relative py-8 md:pb-24 md:pt-12 md:mt-20">
    <div class="bg-[#201511] blur-[40px] absolute top-0 left-0 w-full h-full"></div>
	<div class="container relative z-10 md:text-center">
		<p class="collagen-decline__label flex items-center justify-start md:justify-center mb-5 md:mb-7 gap-2 text-[15px] md:text-[20px] text-[#F4EFE8] md:text-center">
			<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
			Longevity Science
		</p>
		<h2 class="collagen-decline__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] max-w-[705px] mx-auto leading-[100%]">
			Address the Early Signs of Collagen Decline
		</h2>
		<div class="collagen-decline__points mt-5 flex flex-col md:flex-row flex-wrap items-start md:items-center justify-start md:justify-center gap-y-4 md:gap-y-0 md:gap-x-9 text-[16px] text-[#F4EFE8]/90">
			<span class="flex items-center leading-[100%] gap-1"><span class="bg-[#F4EFE8E5]/90 w-1 h-1 mr-1 block md:hidden rounded-full"></span>Establish your collagen baseline</span>
			<span class="flex items-center leading-[100%] gap-1"><span class="bg-[#F4EFE8E5]/90 w-1 h-1 mr-1 block md:hidden rounded-full"></span>Stimulate structural renewal</span>
			<span class="flex items-center leading-[100%] gap-1"><span class="bg-[#F4EFE8E5]/90 w-1 h-1 mr-1 block md:hidden rounded-full"></span>Maintain long-term skin integrity</span>
		</div>
	</div>

	<?php
		$marquee_row_1 = array( 'Acne scarring', 'Enlarged pores', 'Hyperpigmentation', 'Sun damage', 'Melasma', 'Rosacea appearance', 'Uneven tone', 'Texture irregularity', 'Dullness' );
		$marquee_row_2 = array( 'Fine lines', 'Wrinkles', 'Skin laxity', 'Loss of firmness', 'Crepey skin', 'Under-eye texture', 'Neck aging', 'Scars', 'Stretch marks', 'Volume loss' );
		?>
	<div class="collagen-decline__marquees mt-14 md:mt-20">
		<div class="marquee-row overflow-hidden py-2 md:py-3">
			<div class="marquee-track flex w-max">
				<?php for ( $m = 0; $m < 4; $m++ ) : ?>
				<div class="marquee-track__copy flex items-center gap-x-2.5 md:gap-x-5 shrink-0 ">
					<?php foreach ( $marquee_row_1 as $idx => $word ) : ?>
						<span class="marquee-word whitespace-nowrap text-[16px] text-[#F4EFE8] md:text-[20px]"><?php echo esc_html( $word ); ?></span>
						<?php if ( $idx < count( $marquee_row_1 ) - 1 ) : ?><span class="marquee-dot h-1 w-1 shrink-0 rounded-full bg-[#F4EFE8]/50" aria-hidden="true"></span><?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="marquee-row overflow-hidden py-2 md:py-3">
			<div class="marquee-track marquee-track--reverse flex w-max">
				<?php for ( $m = 0; $m < 4; $m++ ) : ?>
				<div class="marquee-track__copy flex items-center gap-x-2.5 md:gap-x-5 shrink-0">
					<?php foreach ( $marquee_row_2 as $idx => $word ) : ?>
						<span class="marquee-word whitespace-nowrap text-[16px] text-[#F4EFE8] md:text-[20px]"><?php echo esc_html( $word ); ?></span>
						<?php if ( $idx < count( $marquee_row_2 ) - 1 ) : ?><span class="marquee-dot h-1 w-1 shrink-0 rounded-full bg-[#F4EFE8]/50" aria-hidden="true"></span><?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</section>

<?php
	$treatments_query = new WP_Query( array(
		'post_type'      => 'treatment',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	$treatments_slides = array();
	if ( $treatments_query->have_posts() ) {
		while ( $treatments_query->have_posts() ) {
			$treatments_query->the_post();
			$img_id = get_post_thumbnail_id();
			$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';
			$treatments_slides[] = array(
				'image'       => $img_url,
				'title'       => get_the_title(),
				'desc'        => get_post_meta( get_the_ID(), 'treatment_short_desc', true ),
				'link'        => get_permalink(),
				'best_for'    => get_post_meta( get_the_ID(), 'treatment_best_for', true ),
				'duration'    => get_post_meta( get_the_ID(), 'treatment_duration', true ),
				'downtime'    => get_post_meta( get_the_ID(), 'treatment_downtime', true ),
				'results_visible' => get_post_meta( get_the_ID(), 'treatment_results_visible', true ),
				'longevity'   => get_post_meta( get_the_ID(), 'treatment_longevity', true ),
				'safety'      => get_post_meta( get_the_ID(), 'treatment_safety', true ),
				'category'    => get_the_terms( get_the_ID(), 'treatment_category' ),
			);
		}
		wp_reset_postdata();
	}
?>
<section class="treatments-section py-16 md:py-24">
	<div class="treatments-section__row flex flex-col lg:flex-row lg:items-start">
		<div class="treatments-section__text-wrap shrink-0 pr-6 lg:pr-10">
			<div class="treatments-section__text max-w-[380px]">
				<p class="treatments-section__label mb-5 md:mb-7 flex items-center gap-2 text-[15px] md:text-[20px] text-[#F4EFE8]">
					<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
					Treatments
				</p>
				<h2 class="treatments-section__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] leading-[100%]">
					Your Collagen Protocol.
				</h2>
				<p class="treatments-section__sub mt-5 text-[16px] text-[#F4EFE8]">
					Subheadline: From digital diagnostics to advanced facial architecture — exclusively evidence-based methods.
				</p>
			</div>
		</div>

		<div class="treatments-section__slider relative mt-10 flex-1 min-w-0 lg:mt-0 lg:pr-0 flex flex-col px-5 md:px-0">
			<div class="swiper treatments-swiper order-1 w-full lg:order-2">
				<div class="swiper-wrapper">
					<?php foreach ( $treatments_slides as $slide ) : ?>
					<div class="swiper-slide h-auto">
						<div class="treatments-card flex h-full flex-col">
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__image w-full overflow-hidden bg-[#2a221d] relative h-[350px] lg:h-[385px]">
								<?php if ( $slide['image'] ) : ?>
									<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" class="w-full object-cover h-[350px] lg:h-[385px]" loading="lazy">
								<?php else : ?>
									<div class="flex h-full w-full items-center justify-center text-[#F4EFE8]/40 text-sm">Image</div>
								<?php endif; ?>
								<div class="bg-black/50 absolute inset-0" aria-hidden="true"></div>
							</a>
							<h3 class="treatments-card__title mt-3 md:mt-5 font-display text-[20px] md:text-[24px] text-[#F4EFE8]"><a href="<?php echo esc_url( $slide['link'] ); ?>"><?php echo esc_html( $slide['title'] ); ?></a></h3>
							<p class="treatments-card__desc mt-4 text-[16px] text-[#F4EFE8] line-clamp-2"><?php echo esc_html( wp_trim_words( (string) $slide['desc'], 20, '...' ) ); ?></p>
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__link mt-auto pt-4 inline-block relative w-fit text-[16px] text-[#F4EFE8]/75 transition-opacity hover:text-[#F4EFE8] after:content-[''] after:absolute after:top-[95%] after:left-[-3%] after:w-[106%] after:h-px after:bg-[#F4EFE8]/75 hover:after:bg-[#F4EFE8]">learn more</a>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="treatments-section__nav order-2 flex justify-end gap-6 mt-8 md:mt-0 md:mb-10 md:mr-12 md:order-first relative">
				<button type="button" class="treatments-swiper-prev group flex items-center justify-center transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Previous', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="treatments-swiper-arrow h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
				<button type="button" class="treatments-swiper-next group flex items-center justify-center transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Next', 'custom-manhattan-laser-theme' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="treatments-swiper-arrow h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
				</button>
			</div>
		</div>
	</div>
</section>

<section class="collagen-decline-section relative py-8 md:pb-24 mt-0 md:mt-20">
	<div class="container relative z-10 text-center">
		<p class="membership-tiers__label flex items-center justify-center mb-5 md:mb-7 gap-2 text-[15px] md:text-[20px] text-[#F4EFE8] md:text-center ">
			<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
			Membership Tiers
		</p>
		<h2 class="membership-tiers__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] max-w-[270px] md:max-w-[705px] mx-auto leading-[100%]">
			Choose Your Collagen Plan
		</h2>
	</div>
	<div class="container">
		<div class="membership-tiers__grid grid grid-cols-1 md:grid-cols-2 gap-7 mt-8 md:mt-16">
			<div class="relative z-0 border border-[#FFFFFF]">
			<div class="w-[102%] h-[102%] absolute top-[-1%] left-[-1%] bg-[#18100D80] backdrop-blur-[6.4px] z-10"></div>
			<div class="px-6 py-8  flex flex-col justify-between h-auto relative z-20">
			
				<h3 class="text-[36px] font-display text-[#FFFFF3] leading-[100%] mb-16 relative after:conter-[''] after:absolute after:top-[-6px] after:left-[-18px] after:w-8 after:h-8 after:bg-[#FFFFFF12] after:backdrop-blur-[1.5px] z-0 after:z-10 after:rounded-full">Tier 1:</h3>
				<p class="text-[20px] md:text-[24px] font-display mb-8">Collagen <br> Performance</p>
				<ul class="flex flex-col gap-1">
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Quarterly RF microneedling</li>
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Biostimulator credits</li>
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Advanced protocol access</li>
				</ul>
			</div>
			</div>
			<div class="relative z-0 border border-[#FFFFFF]">
			<div class="w-[102%] h-[102%] absolute top-[-1%] left-[-1%] bg-[#18100D80] backdrop-blur-[6.4px] z-10"></div>
			<div class="px-6 py-8  flex flex-col justify-between h-auto relative z-20">
			
				<h3 class="text-[36px] font-display text-[#FFFFF3] leading-[100%] mb-16 relative after:conter-[''] after:absolute after:top-[-6px] after:left-[-18px] after:w-8 after:h-8 after:bg-[#FFFFFF12] after:backdrop-blur-[1.5px] z-0 after:z-10 after:rounded-full">Tier 1:</h3>
				<p class="text-[20px] md:text-[24px] font-display mb-8">Collagen <br> Performance</p>
				<ul class="flex flex-col gap-1">
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Quarterly RF microneedling</li>
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Biostimulator credits</li>
					<li class="flex items-center gap-1 text-[#FFFFF3B2]"><span class="h-1 w-1 rounded-full bg-[#FFFFF3B2] mr-1" #FFFFF3=""></span>Advanced protocol access</li>
				</ul>
			</div>
			</div>
		</div>
		<div class="flex items-center justify-center mt-12">
			<a		href="<?php echo home_url('/membership/'); ?>"
					class="flex items-center gap-10 rounded-full bg-[#F4EFE824] pl-5 pr-7 py-3 text-[16px] backdrop-blur-[7px] relative group"

				>
					<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1 text-[#F4EFE8]">
						Select Your Plan
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
			</a>
		</div>
	</div>

</section>



<section class="cta-section relative w-full cta-section flex items-center my-10">
	<div class="bg-[#00000080] absolute w-full h-full z-[5] md:hidden block top-0 left-0"></div>
	<img src="<?php echo esc_url( get_template_directory_uri() . '/img/cta-bg.webp' ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
	<div class="container relative z-10 flex items-center justify-start">
		<div class="mt-[140px]">
			<h2 class="cta-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[100%] mb-1 max-w-[509px]">
				Intelligent Management of Skin Health.
			</h2>
			<p class="cta-section__text text-[16px] text-[#F4EFE8] mb-10 max-w-[446px]">
				We combine biotechnology and aesthetic medicine to help you look impeccable — while remaining entirely yourself.
			</p>
			<a		href="<?php echo home_url('/membership/'); ?>"
					class="flex items-center gap-16 md:gap-12 rounded-full pl-5 pr-7 py-3 text-[16px] relative group border border-[#F4EFE8] w-fit"

				>
					<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1 text-[#F4EFE8]">
						Book now
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
	$before_after_query = new WP_Query( array(
		'post_type'      => 'before_after',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	$before_after_slides = array();
	if ( $before_after_query->have_posts() ) {
		while ( $before_after_query->have_posts() ) {
			$before_after_query->the_post();
			$before_id = (int) get_post_meta( get_the_ID(), 'before_after_before_photo', true );
			$after_id  = (int) get_post_meta( get_the_ID(), 'before_after_after_photo', true );
			$before_url = $before_id ? wp_get_attachment_image_url( $before_id, 'large' ) : '';
			$after_url  = $after_id ? wp_get_attachment_image_url( $after_id, 'large' ) : '';
			if ( $before_url && $after_url ) {
				$before_after_slides[] = array(
					'title'       => get_the_title(),
					'desc' => get_post_meta( get_the_ID(), 'before_after_short_desc', true ),
					'before_url'  => $before_url,
					'after_url'   => $after_url,
				);
			}
		}
		wp_reset_postdata();
	}
	if ( empty( $before_after_slides ) ) {
		$before_after_slides = array();
	}
?>
<section class="before-after-section pb-16 md:py-24 bg-[#18100D] relative">
	
	<div class="container" style="padding-left: 0;">
		<div class="before-after-section__row flex flex-col lg:flex-row lg:items-center gap-12 lg:gap-16 pl-5 md:pl-0 ">
			<div class="before-after-section__slider-wrap flex-1 min-w-0 md:w-[670px] order-2 md:order-none">
				<div class="swiper before-after-swiper !overflow-visible md:!overflow-hidden">
					<div class="swiper-wrapper">
						<?php foreach ( $before_after_slides as $slide ) : ?>
						<div class="swiper-slide !overflow-visible md:px-5 relative">
							<div class="absolute top-0 left-0 w-full h-full z-[5] bg-[#1E130C33]"></div>
							<div class="before-after-compare relative w-full h-[250px] md:h-[428px] bg-[#2a221d] rounded-sm overflow-visible" style="--split: 50;">
								<img src="<?php echo esc_url( $slide['after_url'] ); ?>" alt="" class="absolute inset-0 z-0 h-full w-full object-cover object-center" loading="lazy">
								<div class="before-after-compare__before-layer absolute left-0 top-0 z-[1] h-full max-w-full overflow-hidden pointer-events-none" style="width: calc(var(--split, 50) * 1%);">
									<img src="<?php echo esc_url( $slide['before_url'] ); ?>" alt="" class="before-after-compare__before-img absolute left-0 top-0 h-full max-w-none object-cover object-left" style="width: calc(10000% / clamp(1, var(--split, 50), 100));" loading="lazy">
								</div>
								<div class="before-after-vline pointer-events-none absolute top-0 bottom-0 z-[5] w-[2px] -translate-x-1/2 bg-[#F4EFE8]/90" style="left: calc(var(--split, 50) * 1%);" aria-hidden="true"></div>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/img/resize-input.svg' ); ?>" alt="" class="before-after-knob pointer-events-none absolute top-1/2 z-[6] h-[33px] w-[33px] -translate-x-1/2 -translate-y-1/2 select-none" style="left: calc(var(--split, 50) * 1%);" width="33" height="33" draggable="false">
								<div class="before-after-compare__hit absolute inset-0 z-10 touch-none select-none outline-none" role="slider" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" aria-label="<?php esc_attr_e( 'Before and after comparison', 'custom-manhattan-laser-theme' ); ?>" tabindex="0"></div>
								<div class="before-after-compare__label absolute bottom-7 left-8 z-[15]  pointer-events-none">
									<div class="flex items-center gap-2 relative">
										<span class="w-[2px] bg-[#F4EFE8] shrink-0 absolute left-[-8px] top-[1.5%] h-[103%]"></span>
										<div>
											<p class="text-[14px] md:text-[15px] text-[#F4EFE8] max-w-[300px]">
												<?php echo esc_html( $slide['desc'] ); ?>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php if ( count( $before_after_slides ) > 1 ) : ?>
				<div class="before-after-section__nav gap-6 relative mt-8 justify-center md:hidden flex">
					<button type="button" class="before-after-prev flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="<?php esc_attr_e( 'Previous', 'custom-manhattan-laser-theme' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
					</button>
					<button type="button" class="before-after-next flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="<?php esc_attr_e( 'Next', 'custom-manhattan-laser-theme' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
					</button>
				</div>
				<?php endif; ?>
			</div>
			<div class="before-after-section__content   flex flex-col justify-between  md:max-w-[345px] xl:max-w-[545px] order-1 md:order-none mx-auto relative z-20">
				<div class="flex flex-col items-center">
					<p class="before-after-section__label mb-4 md:mb-7 flex items-center gap-2 text-[15px] md:text-[20px] text-[#F4EFE8] text-center ">
						<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
						<?php esc_html_e( 'Before / After', 'custom-manhattan-laser-theme' ); ?>
					</p>
					<h2 class="before-after-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[110%] mb-4 text-center max-w-[300px]">
						<?php esc_html_e( 'Measured Improvements', 'custom-manhattan-laser-theme' ); ?>
					</h2>
					<p class="before-after-section__text text-[16px] text-[#F4EFE8] text-center max-w-[350px] md:max-w-[446px]">
						<?php esc_html_e( 'See how the right care strategy transforms your appearance while preserving your unique individuality.', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>
				<?php if ( count( $before_after_slides ) > 1 ) : ?>
				<div class="before-after-section__nav gap-6 relative md:top-[97px] xl:top-[137px] justify-center hidden md:flex">
					<button type="button" class="before-after-prev flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="<?php esc_attr_e( 'Previous', 'custom-manhattan-laser-theme' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
					</button>
					<button type="button" class="before-after-next flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="<?php esc_attr_e( 'Next', 'custom-manhattan-laser-theme' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ); ?>" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">
					</button>
				</div>
				<?php endif; ?>
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

<?php
$faq_items = array(
	array(
		'q' => __( 'When will I see results?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'Some treatments provide visible improvements within days, while others work progressively over several weeks. We prioritize sustainable, long-term skin health rather than instant but temporary effects.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'How do I know which treatment is right for me?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'During your consultation we assess your skin goals, history, and lifestyle. We then recommend a personalized plan — often combining modalities — so you understand why each step matters.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'Are the treatments safe?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'All protocols are selected and supervised with clinical rigor. We explain expected sensations, contraindications, and aftercare so you can make an informed decision.', 'custom-manhattan-laser-theme' ),
	),
	array(
		'q' => __( 'Do I need a recovery period?', 'custom-manhattan-laser-theme' ),
		'a' => __( 'It depends on the procedure. Many options allow you to return to routine quickly; others may involve mild redness or peeling for a short time. We always outline downtime before you commit.', 'custom-manhattan-laser-theme' ),
	)
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
					<?php esc_html_e( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' ); ?>
				</p>
			</div>
			<div class="faq-section__accordion min-w-0 md:max-w-[600px] w-full">
				<?php foreach ( $faq_items as $fi => $faq_item ) : ?>
				<details
					class="faq-item group border-b border-[#F4EFE80D] py-5"
					name="faq-home"
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




<?php
	$blog_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
		)
	);
	$treatments_slides = array();
	if ( $blog_query->have_posts() ) {
		while ( $blog_query->have_posts() ) {
			$blog_query->the_post();

			$post_id = get_the_ID();

			$img_id = get_post_thumbnail_id( $post_id );
			$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';

			$short_desc = get_post_meta( $post_id, 'short_description', true );
			if ( '' === $short_desc ) {
				$short_desc = get_the_excerpt( $post_id );
			}

			// Например: "October 1-31, 2025" (как на макете).
			$month = get_the_date( 'F', $post_id );
			$year = get_the_date( 'Y', $post_id );
			$end_day = (int) get_the_date( 't', $post_id );
			$slide_date = sprintf( '%1$s %2$d-%3$d, %4$s', $month, 1, $end_day, $year );

			$treatments_slides[] = array(
				'image' => $img_url,
				'title' => get_the_title( $post_id ),
				'desc'  => $short_desc,
				'date'  => $slide_date,
				'link'  => get_permalink( $post_id ),
			);
		}
		wp_reset_postdata();
	}
?>
<section class="treatments-section py-16 md:py-24">
	<div class="treatments-section__row flex flex-col lg:flex-row items-start lg:items-stretch">
		<div class="treatments-section__text-wrap shrink-0 pr-6 lg:pr-10 relative z-10">
			<div class="treatments-section__text max-w-[380px] flex flex-col justify-between h-full">
				<p class="treatments-section__label mb-5 md:mb-7 flex items-center gap-2 text-[15px] md:text-[20px] text-[#F4EFE8]">
					<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
					Blog
				</p>
				<div class="flex flex-col">
					<h2 class="treatments-section__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] leading-[100%]">
						Knowledge.  Clarity. Results.
					<p class="treatments-section__sub mt-5 text-[16px] text-[#F4EFE8] max-w-[330px]">
						Professional guidance to help you understand your skin and make informed decisions.
					</p>
				</div>
			</div>
		</div>

		<div class="treatments-section__slider relative mt-10 flex-1 min-w-0 lg:mt-0 lg:pr-0 flex flex-col px-5 md:px-0 w-full">
			<div class="swiper blog-swiper order-1 w-full lg:order-2">
				<div class="swiper-wrapper">
					<?php foreach ( $treatments_slides as $slide ) : ?>
					<div class="swiper-slide">
						<div class="treatments-card flex flex-col ">
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__image w-full overflow-hidden bg-[#2a221d] relative">
								<?php if ( $slide['image'] ) : ?>
									<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="" class="w-full object-cover h-[350px] lg:h-[325px]" loading="lazy">
								<?php else : ?>
									<div class="flex h-full w-full items-center justify-center text-[#F4EFE8]/40 text-sm">Image</div>
								<?php endif; ?>
								<div class="bg-black/50 absolute inset-0" aria-hidden="true"></div>
							</a>
							<p class="text-[12px] tabular-nums text-[#F4EFE8]/60 md:text-[14px] mt-2"><?php echo esc_html( $slide['date'] ); ?></p>
							<h3 class="treatments-card__title mt-1 font-display text-[20px] md:text-[24px] text-[#F4EFE8]"><a href="<?php echo esc_url( $slide['link'] ); ?>"><?php echo esc_html( $slide['title'] ); ?></a></h3>
							<p class="treatments-card__desc mt-4 text-[16px] text-[#F4EFE8] line-clamp-2 lg:line-clamp-none"><?php echo esc_html( $slide['desc'] ); ?></p>
							<a href="<?php echo esc_url( $slide['link'] ); ?>" class="treatments-card__link mt-4 inline-block relative w-fit text-[16px] text-[#F4EFE8]/75 transition-opacity hover:text-[#F4EFE8] after:content-[''] after:absolute after:top-[95%] after:left-[-3%] after:w-[106%] after:h-px after:bg-[#F4EFE8]/75 hover:after:bg-[#F4EFE8]">learn more</a>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="cta-section relative w-full cta-section flex items-center mt-10 md:mt-20">
	<img src="<?php echo esc_url( get_template_directory_uri() . '/img/cta-footer-bg.webp' ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
	<div class="container relative z-10 flex items-center justify-center">
		<div class="flex flex-col items-center justify-center">
			<h2 class="cta-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[110%] mb-4 max-w-[439px] text-center">
			Invest in Long-Term Skin Health
			</h2>
			<p class="cta-section__text text-[16px] text-[#F4EFE8] mb-8 max-w-[275px] text-center">
			We focus on sustainable results, not temporary fixes.
			</p>
			<a href="<?php echo home_url('/membership/'); ?>" class="flex items-center gap-7 md:gap-20 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit">
                    <span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
						Book now
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
                    </a>
		</div>
	</div>
</section>




<?php get_footer() ?>

