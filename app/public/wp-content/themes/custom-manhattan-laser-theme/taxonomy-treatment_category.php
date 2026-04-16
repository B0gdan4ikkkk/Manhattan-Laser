<?php
/**
 * Архив категории процедур (treatment_category).
 * URL: /treatments/category/{slug}/ (после сброса постоянных ссылок).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term         = get_queried_object();
$archive_link = post_type_exists( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : home_url( '/' );
$is_term      = $term instanceof WP_Term;

$hero_image_id = $is_term ? (int) get_term_meta( $term->term_id, 'treatment_category_hero_image_id', true ) : 0;
$hero_img_url   = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'large' ) : get_template_directory_uri() . '/img/hero-bg.webp';

$short_desc = $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_short_description', true ) : '';
if ( '' === $short_desc && $is_term ) {
	$short_desc = $term->description;
}

$category_png_id = $is_term ? (int) get_term_meta( $term->term_id, 'treatment_category_category_png_image_id', true ) : 0;
$category_png_url = $category_png_id ? wp_get_attachment_image_url( $category_png_id, 'large' ) : get_template_directory_uri() . '/img/category.png';

$science_title = $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_science_title', true ) : '';
$science_intro = $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_science_intro', true ) : '';

$cols = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$cols[ $i ] = array(
		'title' => $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_science_col_' . $i . '_title', true ) : '',
		'desc'  => $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_science_col_' . $i . '_desc', true ) : '',
	);
}

// Фолбек-тексты "как на фото".
$default_science_title = 'The Science of Non-Invasive Excellence';
$default_science_intro = 'Your skin doesn\'t live from appointment to appointment. We create a personalized roadmap for home care and nutritional support, ensuring your results last as long as possible.';
$default_cols = array(
	1 => array(
		'title' => 'PRECISION TECHNOLOGY:',
		'desc'  => 'Targeted energy that protects surrounding tissue.',
	),
	2 => array(
		'title' => 'PROVEN SAFETY:',
		'desc'  => 'All devices are FDA-cleared and operated by medical experts.',
	),
	3 => array(
		'title' => 'NO SURGERY REQUIRED:',
		'desc'  => 'Achieve surgical-grade lifting and contouring without incisions.',
	),
	4 => array(
		'title' => 'PROVEN SAFETY:',
		'desc'  => 'All devices are FDA-cleared and operated by medical experts.',
	),
);

$science_title_final = $science_title ? $science_title : $default_science_title;
$science_intro_final = $science_intro ? $science_intro : $default_science_intro;
$breadcrumb_items = array();
$breadcrumb_items[] = array(
	'name' => 'Home',
	'url'  => home_url( '/' ),
);
$breadcrumb_items[] = array(
	'name' => 'Treatments',
	'url'  => $archive_link,
);

if ( $is_term ) {
	$ancestor_ids = get_ancestors( $term->term_id, 'treatment_category' );
	$ancestor_ids = array_reverse( is_array( $ancestor_ids ) ? $ancestor_ids : array() );

	foreach ( $ancestor_ids as $ancestor_id ) {
		$ancestor = get_term( $ancestor_id, 'treatment_category' );
		if ( ! $ancestor || is_wp_error( $ancestor ) ) {
			continue;
		}
		$alink = get_term_link( $ancestor );
		if ( is_wp_error( $alink ) ) {
			continue;
		}
		$breadcrumb_items[] = array(
			'name' => (string) $ancestor->name,
			'url'  => (string) $alink,
		);
	}

	$current_link = get_term_link( $term );
	if ( ! is_wp_error( $current_link ) ) {
		$breadcrumb_items[] = array(
			'name' => (string) $term->name,
			'url'  => (string) $current_link,
		);
	}
}
?>

<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo $is_term ? esc_attr( $term->name ) : 'Hero Image'; ?>" class="w-full object-cover absolute top-0 left-0 z-[-999] hero-img-category">
<div class="w-full absolute top-0 left-0 z-[-10] hero-img-category bg-[#000000]/50"></div>
<section class="flex flex-col items-start justify-start hero-section-category">
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
            <div class="flex flex-col items-center justify-center w-full text-center">
                <h1 class="hero-title-animate text-[36px] md:text-[96px] text-[#F4EFE8] font-display mb-5 leading-[100%]">
					<?php echo $is_term ? esc_html( $term->name ) : esc_html__( 'Treatments', 'custom-manhattan-laser-theme' ); ?>
                </h1>
                <p class="text-[16px] text-[#F4EFE8] md:max-w-[578px] mb-8 md:mb-16">
					<?php echo $short_desc ? esc_html( $short_desc ) : '&nbsp;'; ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="why-section pt-8 pb-20 md:py-24">
	<div class="">
		<div class="why-section__top flex flex-col md:flex-row justify-between md:items-center container">
			<div class="mb-5 md:mb-0">
				<h2 class="why-section__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] max-w-[350px] md:max-w-[788px] leading-[100%]">
					<?php echo esc_html( $science_title_final ); ?>
				</h2>
			</div>

			<div class="flex flex-col items-start md:items-end">
				<p class="why-section__intro order-1 md:order-none max-w-[422px] text-[16px] text-[#F4EFE8]/80 md:text-[#F4EFE8] mb-8 md:mb-0 md:hidden">
					<?php echo esc_html( $science_intro_final ); ?>
				</p>
				<p class="why-section__intro order-1 md:order-none max-w-[422px] text-[16px] text-[#F4EFE8]/80 md:text-[#F4EFE8] mb-8 md:mb-0 hidden md:block">
					<?php echo esc_html( $science_intro_final ); ?>
				</p>
			</div>
		</div>

		<div class="why-section__cols grid grid-cols-1 gap-px md:grid-cols-2 lg:grid-cols-4 mt-8 lg:mt-[92px] bg-[#F4EFE8]/5 pt-px pb-px md:pb-0">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<?php
				$col_title = ! empty( $cols[ $i ]['title'] ) ? $cols[ $i ]['title'] : $default_cols[ $i ]['title'];
				$col_desc  = ! empty( $cols[ $i ]['desc'] ) ? $cols[ $i ]['desc'] : $default_cols[ $i ]['desc'];

				$col_class = '';
				if ( 1 === $i ) {
					$col_class = "bg-[#18100D] py-8 md:pt-12 md:pl-10 px-5 md:pr-6 md:pb-10 relative";
				} elseif ( 2 === $i ) {
					$col_class = "bg-[#18100D] py-8 md:pt-12 px-5 md:px-6 md:pb-10 relative ";
				} elseif ( 3 === $i ) {
					$col_class = "bg-[#18100D] py-8 md:pt-12 px-5 md:px-6 md:pb-10 relative ";
				} else {
					$col_class = "bg-[#18100D] py-8 md:pt-12 md:pr-10 px-5 md:pl-6 md:pb-10 relative";
				}
				?>
				<div class="<?php echo esc_attr( $col_class ); ?>">
					<h3 class="why-section__col-title mb-4 md:mb-9 text-[16px] font-display uppercase text-[#F4EFE8] md:text-[24px]">
						<?php echo esc_html( $col_title ); ?>
					</h3>
					<p class="why-section__col-text text-[16px] text-[#F4EFE8]">
						<?php echo esc_html( $col_desc ); ?>
					</p>
				</div>
			<?php endfor; ?>
		</div>
	</div>

	<div class="px-5 md:px-10 mt-8 md:mt-[90px] max-w-[1920px] mx-auto">
		<?php if ( $category_png_url ) : ?>
			<img src="<?php echo esc_url( $category_png_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" class="w-full h-auto" loading="lazy">
		<?php endif; ?>
	</div>
</section>

<?php
$treatments_block_title = $is_term ? (string) get_term_meta( $term->term_id, 'treatment_category_treatments_block_title', true ) : '';
if ( '' === $treatments_block_title ) {
	$treatments_block_title = 'Advanced Body Sculpting';
}
$treatments_posts = get_posts(
	array(
		'post_type'      => 'treatment',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
		'tax_query'      => array(
			array(
				'taxonomy' => 'treatment_category',
				'field'    => 'term_id',
				'terms'    => $is_term ? $term->term_id : 0,
			),
		),
	)
);
?>
<section class="mb-12 md:mb-[140px]">
	<div class="max-w-[1920px] mx-auto flex flex-col items-center justify-center">
		<div class="container flex flex-col items-center justify-center">
			<h2 class="font-display text-[32px] text-[#F4EFE8] md:text-[64px] md:mb-20"><?php echo esc_html( $treatments_block_title ); ?></h2>
		</div>

		<?php if ( ! empty( $treatments_posts ) ) : ?>
			<!-- Desktop (>=1200px): list rows with hover-preview -->
			<div class="tcat-treatments-desktop flex flex-col items-center justify-center relative group w-full">
				<div class="pointer-events-none absolute inset-0 bg-[#18100D]/60 backdrop-blur-[2px] z-[10] opacity-0 transition-opacity duration-300 ease-out group-hover:opacity-100"></div>
				<?php
				$index = 0;
				foreach ( $treatments_posts as $treatment_post ) :
					setup_postdata( $treatment_post );
					$post_id     = $treatment_post->ID;
					$num         = str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT );
					$card_desc   = get_post_meta( $post_id, 'treatment_short_desc', true );
					if ( '' === (string) $card_desc ) {
						$card_desc = get_the_excerpt( $post_id );
					}
					$thumb_url    = get_the_post_thumbnail_url( $post_id, 'medium_large' );
					if ( ! $thumb_url ) {
						$thumb_url = $hero_img_url;
					}
					$preview_side = ( 0 === $index % 2 ) ? 'right' : 'left';
					$rot_class    = ( 'right' === $preview_side ) ? 'rotate-[10deg]' : 'rotate-[-10deg]';
					?>
					<div class="relative z-0 flex items-center justify-between py-[35px] border-b border-b-[#FFFFFF]/10 w-full transition-[transform,opacity] duration-300 ease-out hover:z-[20] hover:-translate-y-1 px-5 md:px-10">
						<div class="gap-16 flex items-center">
							<p class="font-display text-[24px] text-[#F4EFE8]"><?php echo esc_html( $num ); ?></p>
							<h4 class="font-display text-[48px] text-[#F4EFE8] max-w-[550px]"><?php echo esc_html( get_the_title( $post_id ) ); ?></h4>
						</div>
						<div class="gap-8 flex items-center">
							<div class="tcat-hover-preview tcat-hover-preview--<?php echo esc_attr( $preview_side ); ?> pointer-events-none absolute left-[45%] top-1/2 z-[15] w-[240px] -translate-x-1/2 -translate-y-1/2 opacity-0">
								<img
									src="<?php echo esc_url( $thumb_url ); ?>"
									alt=""
									class="h-[240px] w-[240px] object-cover <?php echo esc_attr( $rot_class ); ?>"
									loading="lazy"
								/>
							</div>
							<p class="max-w-[374px] text-[16px] text-[#F4EFE8]"><?php echo esc_html( (string) $card_desc ); ?></p>
							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"
								class="flex items-center gap-10 rounded-full bg-[#F4EFE824] pl-5 pr-7 py-3 text-[16px] backdrop-blur-[7px] relative btn-treatment-category">
								<span class="whitespace-nowrap transition-transform duration-300 ease-out btn-treatment-category-text text-[#F4EFE8]">Learn more</span>
								<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out btn-treatment-category-els">
									<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
									<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out btn-treatment-category-els-inner" aria-hidden="true"></span>
								</span>
							</a>
						</div>
					</div>
					<?php
					$index++;
				endforeach;
				wp_reset_postdata();
				?>
			</div>

			<!-- Mobile / Tablet (<1200px): cards grid (4 cols -> 1 col) -->
			<div class="tcat-treatments-mobile w-full px-5 md:px-10">
				<div class="mx-auto max-w-[1400px] bg-[#FFFFFF0D] md:bg-[#18100D] gap-px grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-8">
					<?php
					$index = 0;
					foreach ( $treatments_posts as $treatment_post ) :
						$post_id   = $treatment_post->ID;
						$card_desc = get_post_meta( $post_id, 'treatment_short_desc', true );
						if ( '' === (string) $card_desc ) {
							$card_desc = get_the_excerpt( $post_id );
						}
						$thumb_url  = get_the_post_thumbnail_url( $post_id, 'large' );
						if ( ! $thumb_url ) {
							$thumb_url = $hero_img_url;
						}
						?>
						<article class="flex flex-col py-8 md:py-0 bg-[#18100D]">
							<div class="mb-5">
								<p class="font-display text-[20px] text-[#F4EFE8] mb-3"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></p>
								<h3 class="font-display text-[24px] text-[#F4EFE8]"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
							</div>

							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="block w-full overflow-hidden">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" class="w-full aspect-[4/3] object-cover" loading="lazy">
							</a>

							<p class="mt-4 text-[14px] text-[#F4EFE8]/80"><?php echo esc_html( (string) $card_desc ); ?></p>

							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"
								class="mt-5 flex items-center gap-10 rounded-full bg-[#F4EFE824] pl-5 pr-7 py-3 text-[16px] backdrop-blur-[7px] relative btn-treatment-category w-fit">
								<span class="whitespace-nowrap transition-transform duration-300 ease-out btn-treatment-category-text text-[#F4EFE8]">Learn more</span>
								<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out btn-treatment-category-els">
									<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
									<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out btn-treatment-category-els-inner" aria-hidden="true"></span>
								</span>
							</a>
						</article>
						<?php
						$index++;
					endforeach;
					?>
				</div>
			</div>
		<?php endif; ?>

		<a href="#" class="mt-8 md:mt-12 items-center gap-7 md:gap-20 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit hidden xl:flex">
			<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
				Book Consultation
			</span>
			<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
				<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
				<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
			</span>
		</a>
	</div>
</section>

<?php
	$before_after_query = new WP_Query(
		array(
			'post_type'      => 'before_after',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => 'before_after_treatment_category_id',
					'value'   => $is_term ? (int) $term->term_id : 0,
					'compare' => '=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
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
$faq_term_id = $is_term ? (int) $term->term_id : 0;
$faq_bundle  = function_exists( 'custom_manhattan_laser_get_treatment_category_faq_for_display' )
	? custom_manhattan_laser_get_treatment_category_faq_for_display( $faq_term_id )
	: array(
		'heading' => __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' ),
		'intro'   => __( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' ),
		'items'   => array(),
	);
$faq_heading = isset( $faq_bundle['heading'] ) ? trim( (string) $faq_bundle['heading'] ) : '';
$faq_intro   = isset( $faq_bundle['intro'] ) ? trim( (string) $faq_bundle['intro'] ) : '';
$faq_items   = isset( $faq_bundle['items'] ) && is_array( $faq_bundle['items'] ) ? $faq_bundle['items'] : array();

$faq_visible_items = array();
foreach ( $faq_items as $faq_row ) {
	if ( ! is_array( $faq_row ) ) {
		continue;
	}
	$fq = isset( $faq_row['q'] ) ? trim( wp_strip_all_tags( (string) $faq_row['q'] ) ) : '';
	$fa = isset( $faq_row['a'] ) ? trim( wp_strip_all_tags( (string) $faq_row['a'] ) ) : '';
	if ( '' === $fq || '' === $fa ) {
		continue;
	}
	$faq_visible_items[] = array(
		'q' => $fq,
		'a' => $fa,
	);
}

$faq_schema_main = array();
foreach ( $faq_visible_items as $fv ) {
	$faq_schema_main[] = array(
		'@type'          => 'Question',
		'name'           => $fv['q'],
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $fv['a'],
		),
	);
}
$faq_details_name = $is_term && isset( $term->slug )
	? 'faq-cat-' . sanitize_title( $term->slug )
	: 'faq-treatment-category';
?>
<?php if ( ! empty( $faq_visible_items ) ) : ?>
<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_schema_main ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
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
					<?php echo esc_html( '' !== $faq_heading ? $faq_heading : __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' ) ); ?>
				</h2>
				<?php if ( '' !== $faq_intro ) : ?>
					<p class="mt-5 max-w-[254px]  text-[15px] leading-[1.5] text-[#F4EFE8]/90 md:text-[16px] md:max-w-[297px]">
						<?php echo esc_html( $faq_intro ); ?>
					</p>
				<?php endif; ?>
			</div>
			<div class="faq-section__accordion min-w-0 md:max-w-[600px] w-full">
				<?php
				$faq_visible_i = 0;
				foreach ( $faq_visible_items as $faq_item ) :
					$fq = $faq_item['q'];
					$fa = $faq_item['a'];
					?>
				<details
					class="faq-item group border-b border-[#F4EFE80D] py-5"
					name="<?php echo esc_attr( $faq_details_name ); ?>"
					<?php echo 0 === $faq_visible_i ? 'open' : ''; ?>
				>
					<summary class="faq-item__summary flex w-full cursor-pointer list-none items-start justify-between gap-6 bg-transparent p-0 text-left text-[#F4EFE8]">
						<span class="min-w-0 flex-1 font-display text-[18px] leading-snug md:text-[22px] lg:text-[24px]"><?php echo esc_html( $fq ); ?></span>
						<span class="faq-item__icons relative mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center text-[26px] font-light leading-none" aria-hidden="true">
							<span class="faq-item__icon-plus inline-flex items-center justify-center">+</span>
						</span>
					</summary>
					<div class="faq-item__answer pr-2 text-[15px] text-[#F4EFE8]/75 md:text-[16px] md:leading-relaxed md:pr-8 mt-4">
						<?php echo esc_html( $fa ); ?>
					</div>
				</details>
					<?php
					++$faq_visible_i;
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>



<section class="cta-section relative w-full cta-section flex items-center mt-10 md:mt-20">
	<img src="<?php echo esc_url( get_template_directory_uri() . '/img/cta-footer-bg-category.webp' ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
	<div class="container relative z-10 flex items-center justify-center">
		<div class="flex flex-col items-center justify-center">
			<h2 class="cta-section__title font-display text-[32px] md:text-[48px] text-[#F4EFE8] leading-[110%] mb-4 max-w-[433px] text-center">
			Ready to start your longevity journey?
			</h2>
			<p class="cta-section__text text-[16px] text-[#F4EFE8] mb-8 max-w-[275px] md:max-w-[405px] text-center">
			Schedule a personalized consultation at one of our NYC locations to find the perfect technology for your goals.
			</p>
			<a href="#" class="flex items-center gap-7 md:gap-20 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit">
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

<?php
get_footer();
