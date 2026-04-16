<?php
/**
 * Одна процедура.
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id      = get_the_ID();
$current_post = $post_id ? get_post( $post_id ) : null;
$is_treatment = $current_post instanceof WP_Post && 'treatment' === $current_post->post_type;

$archive_link = post_type_exists( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : home_url( '/' );

$term = null;
$terms = $post_id ? get_the_terms( $post_id, 'treatment_category' ) : false;
if ( is_array( $terms ) && ! empty( $terms ) ) {
	$term = $terms[0];
}
$is_term = $term instanceof WP_Term;

$hero_img_url = $post_id ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';
$hero_bg_override_url = $post_id ? (string) get_post_meta( $post_id, 'treatment_hero_bg_image_url', true ) : '';
if ( '' !== $hero_bg_override_url ) {
	$hero_img_url = $hero_bg_override_url;
}

$short_desc = $post_id ? (string) get_post_meta( $post_id, 'short_description', true ) : '';

$hero_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_hero_title', true ) : '';
$hero_highlights_raw = array(
	$post_id ? (string) get_post_meta( $post_id, 'treatment_highlight_1', true ) : '',
	$post_id ? (string) get_post_meta( $post_id, 'treatment_highlight_2', true ) : '',
	$post_id ? (string) get_post_meta( $post_id, 'treatment_highlight_3', true ) : '',
	$post_id ? (string) get_post_meta( $post_id, 'treatment_highlight_4', true ) : '',
);
$hero_highlights = array_values(
	array_filter(
		array_map(
			'trim',
			$hero_highlights_raw
		),
		static function ( $val ) {
			return '' !== $val;
		}
	)
);

$best_for_label       = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_best_for', true ) : '';
$duration_label       = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_duration', true ) : '';
$downtime_label       = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_downtime', true ) : '';
$results_visible_label = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_results_visible', true ) : '';
$longevity_label      = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_longevity', true ) : '';
$safety_label         = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_safety', true ) : '';

$best_for_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_best_for_title', true ) : '';
$duration_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_duration_title', true ) : '';
$downtime_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_downtime_title', true ) : '';
$results_visible_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_results_visible_title', true ) : '';
$longevity_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_longevity_title', true ) : '';
$safety_title = $post_id ? (string) get_post_meta( $post_id, 'treatment_label_safety_title', true ) : '';

$hero_facts = array(
	array(
		'title' => '' !== trim( $best_for_title ) ? trim( $best_for_title ) : __( 'Best for:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $best_for_label ),
	),
	array(
		'title' => '' !== trim( $duration_title ) ? trim( $duration_title ) : __( 'Duration:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $duration_label ),
	),
	array(
		'title' => '' !== trim( $downtime_title ) ? trim( $downtime_title ) : __( 'Downtime:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $downtime_label ),
	),
	array(
		'title' => '' !== trim( $results_visible_title ) ? trim( $results_visible_title ) : __( 'Results visible:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $results_visible_label ),
	),
	array(
		'title' => '' !== trim( $longevity_title ) ? trim( $longevity_title ) : __( 'Longevity:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $longevity_label ),
	),
	array(
		'title' => '' !== trim( $safety_title ) ? trim( $safety_title ) : __( 'Safety:', 'custom-manhattan-laser-theme' ),
		'value' => trim( $safety_label ),
	),
);
$hero_facts = array_values(
	array_filter(
		$hero_facts,
		static function ( $row ) {
			return ! empty( $row['value'] );
		}
	)
);

$cta_label = $post_id ? (string) get_post_meta( $post_id, 'treatment_cta_label', true ) : '';
$cta_url   = $post_id ? (string) get_post_meta( $post_id, 'treatment_cta_url', true ) : '';
$cta_label = trim( $cta_label );
$cta_url   = trim( $cta_url );
$has_hero_cta = '' !== $cta_label && '' !== $cta_url;

$neuro_heading = $post_id ? (string) get_post_meta( $post_id, 'treatment_neuro_heading', true ) : '';
$neuro_intro   = $post_id ? (string) get_post_meta( $post_id, 'treatment_neuro_intro', true ) : '';
$neuro_heading = trim( $neuro_heading );
$neuro_intro   = trim( $neuro_intro );

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

$breadcrumb_items[] = array(
	'name' => get_the_title( $post_id ),
	'url'  => '',
);

$ml_treatment_gallery_image_urls = array();
if ( '' !== $hero_img_url ) {
	$ml_treatment_gallery_image_urls[] = $hero_img_url;
}

get_header();
?>

<?php if ( '' !== $hero_img_url ) : ?>
<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo $is_term ? esc_attr( $term->name ) : 'Hero Image'; ?>" class="w-full object-cover absolute top-0 left-0 z-[100] hero-img-single">
<div class="w-full absolute top-0 left-0 z-[100] hero-img-single bg-[#000000]/30"></div>
<?php endif; ?>
<section class="flex flex-col items-start justify-start hero-section-single relative z-[100]">
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
    <div class="container flex flex-col items-center justify-end h-full">
        <div class="flex flex-col items-center justify-center w-full lg:items-end">
            <div class="mb-8 flex w-full flex-col items-center md:mb-8">
				<?php if ( '' !== $hero_title ) : ?>
					<h1 class="hero-title-animate mb-6 max-w-[1250px] text-center text-[36px] font-display leading-[100%] text-white md:mb-8 md:text-[64px]">
						<?php echo esc_html( $hero_title ); ?>
					</h1>
				<?php endif; ?>
				<?php if ( ! empty( $hero_highlights ) ) : ?>
					<div class="mb-5 flex flex-wrap items-center justify-between md:justify-center gap-y-2 md:mb-5" aria-label="<?php esc_attr_e( 'Highlights', 'custom-manhattan-laser-theme' ); ?>">
						<?php foreach ( $hero_highlights as $h_i => $highlight ) : ?>
							<div class="px-3 py-1 text-center text-[16px] uppercase text-white/85 md:py-5">
								<?php echo esc_html( $highlight ); ?>
							</div>
							<?php if ( $h_i < count( $hero_highlights ) - 1 ) : ?>
								<span class="hidden h-1 w-1 rounded-full bg-[#F4EFE8D9] md:block" aria-hidden="true"></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $hero_facts ) ) : ?>
					<div class="mb-8 md:mx-auto md:mb-10">
						<div class="grid grid-cols-2 text-left md:grid-cols-3">
							<?php foreach ( $hero_facts as $fact ) : ?>
								<div class="flex h-full flex-col gap-2 border-b border-r border-[#FFFFFF0D] px-3 py-3 md:px-4 md:py-5">
									<span class="font-display text-[24px] leading-tight text-white"><?php echo esc_html( $fact['title'] ); ?></span>
									<span class="font-sans text-[13px] leading-snug text-white/90 md:text-[14px] lg:text-[16px]"><?php echo esc_html( $fact['value'] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

					<a href="<?php echo esc_url( $cta_url ); ?>" class="relative flex w-full items-center justify-center gap-10 rounded-full bg-[#F4EFE824] py-4 pl-5 pr-7 text-[16px] backdrop-blur-[7px] group md:py-5">
						<span class="whitespace-nowrap text-[#F4EFE8] transition-transform duration-300 ease-out group-hover:translate-x-1">
							Make an appointment
						</span>
						<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
							<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
							<span class="absolute left-1/2 top-1/2 h-1 w-1 -translate-x-1/2 -translate-y-1/2 translate-x-[15px] rounded-full bg-[#F4EFE8] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
						</span>
					</a>
            </div>
        </div>
    </div>
</section>

<?php
$neuromodulator_timeline = array();
for ( $ni = 1; $ni <= 4; $ni++ ) {
	$name    = $post_id ? (string) get_post_meta( $post_id, 'treatment_neuro_item_' . $ni . '_name', true ) : '';
	$text    = $post_id ? (string) get_post_meta( $post_id, 'treatment_neuro_item_' . $ni . '_text', true ) : '';
	$name    = trim( $name );
	$text    = trim( $text );
	if ( '' === $name || '' === $text ) {
		continue;
	}
	$neuromodulator_timeline[] = array(
		'num'  => sprintf( '%02d', $ni ),
		'name' => $name,
		'text' => $text,
	);
}
?>
<?php if ( '' !== $neuro_heading && '' !== $neuro_intro && ! empty( $neuromodulator_timeline ) ) : ?>
<section
	class="neuro-timeline py-14 text-[#F4EFE8] md:py-20"
	aria-labelledby="neuro-timeline-heading"
>
	<div class="md:max-w-[1400px] md:mx-auto md:px-5 items-center justify-center flex flex-col">
		<h2 id="neuro-timeline-heading" class="mx-auto mb-4 md:mb-5 text-start md:text-center font-display text-[32px] leading-[110%] md:text-[48px] px-5 md:px-0">
			<?php echo esc_html( $neuro_heading ); ?>
		</h2>
		<p class="mx-auto mb-10 max-w-[1059px] text-start md:text-center text-[16px] text-[#F4EFE8]/88 md:mb-16 md:text-[20px] px-5 md:px-0">
			<?php echo esc_html( $neuro_intro ); ?>
		</p>

		<div class="neuro-timeline__track relative hidden md:block">
			<div class="neuro-timeline__spine" aria-hidden="true"></div>
			<ul class="relative z-[1] m-0 list-none p-0">
				<?php
				foreach ( $neuromodulator_timeline as $nti => $nti_row ) :
					$row_active = ( 1 === (int) $nti );
					$active_class = $row_active ? ' is-active' : '';
					?>
					<li class="neuro-timeline__item grid grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] items-center gap-x-3 py-6 sm:gap-x-10 sm:py-5<?php echo esc_attr( $active_class ); ?>">
						<div class="min-w-0" aria-hidden="true"></div>
						<div class="neuro-timeline__node-wrapper flex shrink-0 justify-center<?php echo esc_attr( $active_class ); ?>">
							<span class="neuro-timeline__node inline-flex shrink-0 items-center justify-center rounded-full border border-[#F4EFE8]/45 text-sm text-[#F4EFE8]/90 sm:text-[32px]<?php echo esc_attr( $active_class ); ?>" data-neuro-node>
								<?php echo esc_html( $nti_row['num'] ); ?>
							</span>
						</div>
						<div class="neuro-timeline__copy min-w-0 text-left">
							<h3 class="font-display leading-none text-[#F4EFE8] text-[24px] md:text-[32px]">
								<?php echo esc_html( $nti_row['name'] ); ?>
							</h3>
							<p class="mt-2 md:mt-5 text-[#F4EFE8]/85 text-[15px] md:text-[20px] max-w-[440px]">
								<?php echo esc_html( $nti_row['text'] ); ?>
							</p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="neuro-timeline__mobile-grid md:hidden">
			<ul class="m-0 grid list-none grid-cols-2 p-0 gap-px bg-[#FFFFFF0D] pt-px pb-px">
				<?php foreach ( $neuromodulator_timeline as $nti_row ) : ?>
					<li class="neuro-timeline__mobile-item p-5 bg-[#18100D]">
						<span class="neuro-timeline__mobile-node inline-flex shrink-0 items-center justify-center rounded-full border border-[#F4EFE8] text-sm text-[#F4EFE8] text-[16px] sm:text-[32px]">
							<?php echo esc_html( $nti_row['num'] ); ?>
						</span>
						<div class="neuro-timeline__copy mt-5 min-w-0 text-left">
							<h3 class="font-display leading-none text-[#F4EFE8] text-[24px] md:text-[32px]">
								<?php echo esc_html( $nti_row['name'] ); ?>
							</h3>
							<p class="mt-2 md:mt-5 text-[#F4EFE8]/80 text-[15px] md:text-[20px] max-w-[440px]">
								<?php echo esc_html( $nti_row['text'] ); ?>
							</p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>









<?php
$treatable_area_images = array();
$treatable_areas_heading = $post_id ? (string) get_post_meta( $post_id, 'treatment_areas_heading', true ) : '';
$treatable_areas_items_raw = $post_id ? (string) get_post_meta( $post_id, 'treatment_areas_items', true ) : '';
$treatable_areas_image_left = $post_id ? (string) get_post_meta( $post_id, 'treatment_areas_image_left_url', true ) : '';
$treatable_areas_image_right = $post_id ? (string) get_post_meta( $post_id, 'treatment_areas_image_right_url', true ) : '';
$treatable_areas_image_mobile = $post_id ? (string) get_post_meta( $post_id, 'treatment_areas_image_mobile_url', true ) : '';
if ( '' !== $treatable_areas_image_left ) {
	$treatable_area_images[0] = $treatable_areas_image_left;
}
if ( '' !== $treatable_areas_image_right ) {
	$treatable_area_images[1] = $treatable_areas_image_right;
}
$treatable_areas_mobile_image = $treatable_areas_image_mobile;
$treatable_areas_items = array_filter(
	array_map(
		'trim',
		preg_split( "/\r\n|\n|\r/", $treatable_areas_items_raw )
	)
);
$treatable_areas_heading      = trim( $treatable_areas_heading );
$treatable_areas_items        = array_values( $treatable_areas_items );
$treatable_area_left_image    = isset( $treatable_area_images[0] ) ? (string) $treatable_area_images[0] : '';
$treatable_area_right_image   = isset( $treatable_area_images[1] ) ? (string) $treatable_area_images[1] : '';
$has_treatable_areas_section  = (
	'' !== $treatable_areas_heading ||
	! empty( $treatable_areas_items ) ||
	'' !== $treatable_area_left_image ||
	'' !== $treatable_area_right_image ||
	'' !== $treatable_areas_mobile_image
);

if ( '' !== $treatable_area_left_image ) {
	$ml_treatment_gallery_image_urls[] = $treatable_area_left_image;
}
if ( '' !== $treatable_area_right_image ) {
	$ml_treatment_gallery_image_urls[] = $treatable_area_right_image;
}
if ( '' !== $treatable_areas_mobile_image ) {
	$ml_treatment_gallery_image_urls[] = $treatable_areas_mobile_image;
}
?>
<?php if ( $has_treatable_areas_section ) : ?>
<section
	class="py-12 md:py-20 relative z-10" 
	role="region"
	aria-label="<?php echo esc_attr( '' !== $treatable_areas_heading ? $treatable_areas_heading : __( 'Treatable Areas', 'custom-manhattan-laser-theme' ) ); ?>"
>
<div class="before-after-section__bg-pattern absolute left-[-60%] top-[-5%] w-full h-[500px]  bg-[#201511] blur-[150px] -z-10 hidden md:flex rounded-full"> </div>
	<div class="mx-auto grid max-w-[1400px] grid-cols-1 gap-3 px-5 md:grid-cols-2 md:gap-6 after:w-px after:bg-[#F4EFE8]/5 after:absolute after:right-[33%] after:top-[-1300px] lg:after:h-[1500px] after:content-[''] after:z-[-20] before:w-px before:bg-[#F4EFE8]/5 before:absolute before:left-[33%] before:top-[-1300px] lg:before:h-[1500px] before:content-[''] before:z-[-20] z-[100]">
		
			<div class="hidden overflow-hidden md:block h-[260px] md:h-full md:min-h-[600px]">
			<?php if ( '' !== $treatable_area_left_image ) : ?>
				<img
					src="<?php echo esc_url( $treatable_area_left_image ); ?>"
					alt="<?php esc_attr_e( 'Treatment area photo', 'custom-manhattan-laser-theme' ); ?>"
					class="h-[260px] w-full object-cover md:h-full md:min-h-[600px]"
					loading="lazy"
				>
				<?php endif; ?>
			</div>
		

		<div class="grid gap-6 md:grid-rows-[240px_auto]">
			
				<div class="hidden overflow-hidden md:block h-full w-full">
				<?php if ( '' !== $treatable_area_right_image ) : ?>
					<img
						src="<?php echo esc_url( $treatable_area_right_image ); ?>"
						alt="<?php esc_attr_e( 'Treatable zones photo', 'custom-manhattan-laser-theme' ); ?>"
						class="h-full w-full object-cover"
						loading="lazy"
					>
					<?php endif; ?>
				</div>
			

			<div>
				<?php if ( '' !== $treatable_areas_heading ) : ?>
					<h2 class="text-center font-display text-[32px] leading-[110%] text-[#F4EFE8] md:text-left md:text-[48px] max-w-[300px]">
						<?php echo esc_html( $treatable_areas_heading ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( ! empty( $treatable_areas_items ) ) : ?>
					<ul class="mt-4 grid list-disc grid-cols-2 gap-x-6 gap-y-2.5 pl-5 text-[16px] font-normal leading-[100%] text-[#F4EFE8] md:flex md:flex-col">
						<?php foreach ( $treatable_areas_items as $treatable_item ) : ?>
							<li><?php echo esc_html( $treatable_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			
				<div class="overflow-hidden md:hidden h-[350px]">
				<?php if ( '' !== $treatable_areas_mobile_image ) : ?>
					<img
						src="<?php echo esc_url( $treatable_areas_mobile_image ); ?>"
						alt="<?php esc_attr_e( 'Treatment area photo', 'custom-manhattan-laser-theme' ); ?>"
						class="h-[350px] w-full object-cover"
						loading="lazy"
					>
					<?php endif; ?>
				</div>
			
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$candidate_heading = $post_id ? (string) get_post_meta( $post_id, 'treatment_candidate_heading', true ) : '';
$candidate_suitable_label = $post_id ? (string) get_post_meta( $post_id, 'treatment_candidate_suitable_label', true ) : '';
$candidate_suitable_text = $post_id ? (string) get_post_meta( $post_id, 'treatment_candidate_suitable_text', true ) : '';
$candidate_not_suitable_label = $post_id ? (string) get_post_meta( $post_id, 'treatment_candidate_not_suitable_label', true ) : '';
$candidate_not_suitable_text = $post_id ? (string) get_post_meta( $post_id, 'treatment_candidate_not_suitable_text', true ) : '';

$candidate_heading = trim( $candidate_heading );
$candidate_suitable_label = trim( $candidate_suitable_label );
$candidate_suitable_text = trim( $candidate_suitable_text );
$candidate_not_suitable_label = trim( $candidate_not_suitable_label );
$candidate_not_suitable_text = trim( $candidate_not_suitable_text );
$candidate_rows = array();
if ( '' !== $candidate_suitable_label && '' !== $candidate_suitable_text ) {
	$candidate_rows[] = array(
		'label' => $candidate_suitable_label,
		'text'  => $candidate_suitable_text,
	);
}
if ( '' !== $candidate_not_suitable_label && '' !== $candidate_not_suitable_text ) {
	$candidate_rows[] = array(
		'label' => $candidate_not_suitable_label,
		'text'  => $candidate_not_suitable_text,
	);
}
?>
<?php if ( '' !== $candidate_heading && ! empty( $candidate_rows ) ) : ?>
<section
	class="py-12 md:py-20"
	role="region"
	aria-label="<?php echo esc_attr( $candidate_heading ); ?>"
>
	<div class="mx-auto max-w-[1400px] px-5">
		<h2 class="font-display text-[32px] leading-[100%] text-[#F4EFE8] md:text-[48px]">
			<?php echo esc_html( $candidate_heading ); ?>
		</h2>

		<div class="mt-8 md:mt-5">
			<?php foreach ( $candidate_rows as $candidate_row ) : ?>
				<div class="grid grid-cols-1 gap-3 border-b border-[#F4EFE80D] pb-5 pt-6 md:grid-cols-[360px_minmax(0,1fr)] md:items-center md:gap-6 md:pb-2">
					<p class="font-display text-[24px] leading-[100%] text-[#F4EFE8] md:text-[32px]">
						<?php echo esc_html( $candidate_row['label'] ); ?>
					</p>
					<p class="text-left text-[16px] text-[#F4EFE8] md:text-right">
						<?php echo esc_html( $candidate_row['text'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>



<?php
	$before_after_query_args = array(
		'post_type'      => 'before_after',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	);
	if ( $post_id ) {
		$before_after_query_args['meta_query'] = array(
			array(
				'key'     => 'before_after_treatment_id',
				'value'   => (int) $post_id,
				'compare' => '=',
				'type'    => 'NUMERIC',
			),
		);
	}
	$before_after_query = new WP_Query( $before_after_query_args );
	$before_after_slides = array();
	if ( $before_after_query->have_posts() ) {
		while ( $before_after_query->have_posts() ) {
			$before_after_query->the_post();
			$before_id = (int) get_post_meta( get_the_ID(), 'before_after_before_photo', true );
			$after_id  = (int) get_post_meta( get_the_ID(), 'before_after_after_photo', true );
			$before_url = $before_id ? wp_get_attachment_image_url( $before_id, 'large' ) : '';
			$after_url  = $after_id ? wp_get_attachment_image_url( $after_id, 'large' ) : '';
			if ( $before_url && $after_url ) {
				if ( '' !== $before_url ) {
					$ml_treatment_gallery_image_urls[] = $before_url;
				}
				if ( '' !== $after_url ) {
					$ml_treatment_gallery_image_urls[] = $after_url;
				}
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
?>
<?php if ( ! empty( $before_after_slides ) ) : ?>
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
<?php endif; ?>

<?php
$treatment_pricing_table = function_exists( 'custom_manhattan_laser_get_treatment_pricing_table_for_template' )
	? custom_manhattan_laser_get_treatment_pricing_table_for_template( $post_id )
	: array( 'headers' => array(), 'rows' => array() );
$treatment_pricing_heading_meta = $post_id ? (string) get_post_meta( $post_id, 'treatment_pricing_heading', true ) : '';
$treatment_pricing_heading     = trim( $treatment_pricing_heading_meta );
$pricing_headers              = isset( $treatment_pricing_table['headers'] ) && is_array( $treatment_pricing_table['headers'] ) ? array_values( array_filter( array_map( 'trim', $treatment_pricing_table['headers'] ) ) ) : array();
$pricing_rows                 = array();
if ( isset( $treatment_pricing_table['rows'] ) && is_array( $treatment_pricing_table['rows'] ) ) {
	foreach ( $treatment_pricing_table['rows'] as $pricing_row ) {
		if ( ! is_array( $pricing_row ) ) {
			continue;
		}
		$clean_row = array_values( array_map( 'trim', $pricing_row ) );
		$has_data  = false;
		foreach ( $clean_row as $cell_val ) {
			if ( '' !== $cell_val ) {
				$has_data = true;
				break;
			}
		}
		if ( $has_data ) {
			$pricing_rows[] = $clean_row;
		}
	}
}
$pricing_col_count = count( $pricing_headers );
$pricing_min_w     = max( 480, min( 1400, $pricing_col_count * 150 ) );
?>
<?php if ( '' !== $treatment_pricing_heading && ! empty( $pricing_headers ) && ! empty( $pricing_rows ) ) : ?>
<section class="bg-[#18100D] py-14 md:py-24 md:pb-[120px]" role="region" aria-label="<?php echo esc_attr( $treatment_pricing_heading ); ?>">
	<div class="mx-auto max-w-[1400px] pl-5 md:px-5" data-pricing-scroll-root>
		<h2 class="mb-8 text-center font-display text-[32px] leading-[110%] text-[#F4EFE8] md:mb-10 md:text-[48px] pr-5 md:pr-0">
			<?php echo esc_html( $treatment_pricing_heading ); ?>
		</h2>

		<div
			class="pricing-cost-table-scroll overflow-x-auto pr-5 md:pr-0"
			data-pricing-table-scroll
			style="<?php echo esc_attr( '--pricing-table-min-w: ' . (int) $pricing_min_w . 'px' ); ?>"
		>
			<table class="pricing-cost-dynamic-table w-full border-collapse text-left text-[#F4EFE8] md:min-w-0">
				<thead>
					<tr>
						<?php foreach ( $pricing_headers as $th_text ) : ?>
							<th class="border border-[#F4EFE80D] px-4 py-3 text-[20px] font-display font-normal uppercase leading-none md:px-6 md:py-5 md:text-[24px]">
								<?php echo esc_html( $th_text ); ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $pricing_rows as $pricing_row ) : ?>
						<tr>
							<?php foreach ( $pricing_row as $cell ) : ?>
								<td class="border border-[#F4EFE80D] text-[#F4EFE8D9]/85 px-4 py-3 text-[16px] md:px-6 md:py-5 md:text-[20px]">
									<?php echo esc_html( $cell ); ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="pricing-cost-scrollbar-wrap mt-4 mb-3 mr-5 md:mt-0 md:mb-0 md:mr-0 md:hidden" aria-hidden="true">
			<div class="pricing-cost-scrollbar-track" data-pricing-scrollbar-track tabindex="-1">
				<div class="pricing-cost-scrollbar-thumb" data-pricing-scrollbar-thumb></div>
			</div>
		</div>

		<div class="pricing-cost-swipe-hint mr-5 flex items-center gap-2.5 rounded-full bg-[#1E130C] px-4 py-2.5 text-[13px] leading-snug text-[#F4EFE8] md:hidden">
			<span class="pricing-cost-swipe-hint__icon shrink-0 font-sans text-[12px] tracking-tight text-[#F4EFE8]/90" aria-hidden="true">&lt; &gt;</span>
			<span><?php esc_html_e( 'Swipe left or right to view the full pricing', 'custom-manhattan-laser-theme' ); ?></span>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$procedure_heading_meta = $post_id ? (string) get_post_meta( $post_id, 'treatment_procedure_heading', true ) : '';
$procedure_heading = trim( $procedure_heading_meta );

$procedure_steps = array();
for ( $procedure_i = 1; $procedure_i <= 4; $procedure_i++ ) {
	$label_meta        = $post_id ? (string) get_post_meta( $post_id, 'treatment_procedure_step_' . $procedure_i . '_label', true ) : '';
	$title_meta        = $post_id ? (string) get_post_meta( $post_id, 'treatment_procedure_step_' . $procedure_i . '_title', true ) : '';
	$label_meta        = trim( $label_meta );
	$title_meta        = trim( $title_meta );
	if ( '' === $label_meta || '' === $title_meta ) {
		continue;
	}
	$procedure_steps[] = array(
		'num'   => sprintf( '%02d', $procedure_i ),
		'label' => $label_meta,
		'title' => $title_meta,
	);
}
$procedure_steps_json = wp_json_encode( $procedure_steps, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
?>
<?php if ( '' !== $procedure_heading && ! empty( $procedure_steps ) ) : ?>
<section
	id="procedure-section"
	class="procedure-section procedure-section--desktop bg-[#F4EFE8] text-[#313131]"
	role="region"
	aria-label="<?php echo esc_attr( $procedure_heading ); ?>"
	data-procedure-steps="<?php echo esc_attr( $procedure_steps_json ); ?>"
>
	<div class="procedure-pin relative overflow-hidden max-w-[1400px] mx-auto">
		<div class="absolute left-0 top-0 z-[10] w-full pt-20 sm:pt-12 flex justify-center sm:justify-start">
			<h2 class="font-display text-[32px] lg:text-[64px] leading-[110%] text-[#313131] max-w-[250px] md:max-w-[445px] px-5 text-center sm:text-left">
				<span class="block"><?php echo esc_html( $procedure_heading ); ?></span>
			</h2>
		</div>

		<div class="relative z-[2] flex h-full items-end justify-center pt-32 sm:pt-24 px-0 sm:px-5">
			<div class="procedure-dial-wrap relative w-full md:p-10 aspect-[1/1]">
				<div class="procedure-rotor relative aspect-[1/1] w-[200vw] sm:w-full">
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/img/Ellipse.svg' ); ?>"
						alt=""
						class="procedure-ellipse w-full h-auto select-none pointer-events-none"
						loading="lazy"
					>

					<div class="procedure-markers pointer-events-none absolute inset-0 z-[2]">
						<?php foreach ( $procedure_steps as $step_i => $step ) : ?>
							<div class="procedure-marker" data-marker-index="<?php echo (int) $step_i; ?>" aria-hidden="true">
								<span class="procedure-marker-badge">
									<span class="procedure-marker-badge-inner">
										<?php echo esc_html( $step['num'] ); ?>
									</span>
								</span>
								<span class="procedure-marker-content">
									<span class="procedure-marker-line-wrap">
										<span class="procedure-marker-dot"></span>
										<span class="procedure-marker-line"></span>
									</span>
									<span class="procedure-marker-step">
										<span class="procedure-marker-step-label"><?php echo esc_html( $step['label'] ); ?></span>
										<span class="procedure-marker-step-title"><?php echo esc_html( $step['title'] ); ?></span>
									</span>
								</span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$results_longevity = function_exists( 'custom_manhattan_laser_get_treatment_results_longevity_for_template' )
	? custom_manhattan_laser_get_treatment_results_longevity_for_template( $post_id )
	: array( 'heading' => '', 'rows' => array() );
$results_longevity_heading = isset( $results_longevity['heading'] ) ? trim( (string) $results_longevity['heading'] ) : '';
$results_longevity_rows_raw = isset( $results_longevity['rows'] ) && is_array( $results_longevity['rows'] ) ? $results_longevity['rows'] : array();
$results_longevity_rows = array();
foreach ( $results_longevity_rows_raw as $results_row ) {
	$rl = isset( $results_row['label'] ) ? trim( (string) $results_row['label'] ) : '';
	$rv = isset( $results_row['value'] ) ? trim( (string) $results_row['value'] ) : '';
	if ( '' === $rl || '' === $rv ) {
		continue;
	}
	$results_longevity_rows[] = array(
		'label' => $rl,
		'value' => $rv,
	);
}
?>
<?php if ( '' !== $results_longevity_heading && ! empty( $results_longevity_rows ) ) : ?>
<section
	class="bg-[#18100D] py-12 md:py-[120px]"
	role="region"
	aria-label="<?php echo esc_attr( $results_longevity_heading ); ?>"
>
	<div class="mx-auto max-w-[1400px] px-5">
		<h2 class="font-display text-[32px] leading-[100%] text-[#F4EFE8] md:text-[48px]">
			<?php echo esc_html( $results_longevity_heading ); ?>
		</h2>

		<div class="mt-8 md:mt-5">
			<?php foreach ( $results_longevity_rows as $results_row ) : ?>
				<div class="grid grid-cols-1 gap-3 border-b border-[#F4EFE80D] pb-5 md:pb-2 pt-6 md:grid-cols-[360px_minmax(0,1fr)] md:items-center md:gap-6">
					<p class="font-sans text-[24px] font-medium uppercase leading-[130%] tracking-[0.08em] text-[#F4EFE8] md:text-[32px]">
						<?php echo esc_html( $results_row['label'] ); ?>
					</p>
					<p class="text-left font-sans text-[16px] leading-snug text-[#F4EFE8] md:text-right md:text-[16px]">
						<?php echo esc_html( $results_row['value'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$treatment_comparison_table = function_exists( 'custom_manhattan_laser_get_treatment_comparison_table_for_template' )
	? custom_manhattan_laser_get_treatment_comparison_table_for_template( $post_id )
	: array( 'headers' => array(), 'rows' => array() );
$treatment_comparison_heading_meta = $post_id ? (string) get_post_meta( $post_id, 'treatment_comparison_heading', true ) : '';
$treatment_comparison_heading = trim( $treatment_comparison_heading_meta );
$comparison_headers          = isset( $treatment_comparison_table['headers'] ) && is_array( $treatment_comparison_table['headers'] ) ? array_values( array_filter( array_map( 'trim', $treatment_comparison_table['headers'] ) ) ) : array();
$comparison_rows             = array();
if ( isset( $treatment_comparison_table['rows'] ) && is_array( $treatment_comparison_table['rows'] ) ) {
	foreach ( $treatment_comparison_table['rows'] as $comparison_row ) {
		if ( ! is_array( $comparison_row ) ) {
			continue;
		}
		$clean_row = array_values( array_map( 'trim', $comparison_row ) );
		$has_data  = false;
		foreach ( $clean_row as $cell_val ) {
			if ( '' !== $cell_val ) {
				$has_data = true;
				break;
			}
		}
		if ( $has_data ) {
			$comparison_rows[] = $clean_row;
		}
	}
}
$comparison_col_count = count( $comparison_headers );
$comparison_min_w       = max( 480, min( 1400, $comparison_col_count * 150 ) );
?>
<?php if ( '' !== $treatment_comparison_heading && ! empty( $comparison_headers ) && ! empty( $comparison_rows ) ) : ?>
<section class="bg-[#F4EFE8] py-8 md:py-12" role="region" aria-label="<?php echo esc_attr( $treatment_comparison_heading ); ?>">
	<div class="mx-auto max-w-[1400px] pl-5 md:px-5" data-comparison-scroll-root>
		<h2 class="mb-8 text-center font-display text-[32px] leading-[110%] text-[#313131] md:mb-10 md:text-[48px] pr-5 md:pr-0">
			<?php echo esc_html( $treatment_comparison_heading ); ?>
		</h2>

		<div
			class="comparison-table-scroll overflow-x-auto pr-5 md:pr-0"
			data-comparison-table-scroll
			style="<?php echo esc_attr( '--comparison-table-min-w: ' . (int) $comparison_min_w . 'px' ); ?>"
		>
			<table class="comparison-dynamic-table w-full border-collapse text-left text-[#313131] md:min-w-0">
				<thead>
					<tr>
						<?php foreach ( $comparison_headers as $th_text ) : ?>
							<th class="border border-[#3131311A] px-4 py-3 text-[20px] font-display font-normal uppercase leading-none md:px-6 md:py-5 md:text-[24px]">
								<?php echo esc_html( $th_text ); ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $comparison_rows as $comparison_row ) : ?>
						<tr>
							<?php foreach ( $comparison_row as $c_cell ) : ?>
								<td class="border border-[#3131311A] px-4 py-3 text-left text-[16px] font-sans leading-snug text-[#313131]/90 md:px-6 md:py-5 md:text-[20px]">
									<?php echo esc_html( $c_cell ); ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="comparison-scrollbar-wrap mt-4 mb-3 mr-5 md:mt-0 md:mb-0 md:mr-0 md:hidden" aria-hidden="true">
			<div class="comparison-scrollbar-track" data-comparison-scrollbar-track tabindex="-1">
				<div class="comparison-scrollbar-thumb" data-comparison-scrollbar-thumb></div>
			</div>
		</div>

		<div class="comparison-swipe-hint mr-5 flex items-center gap-2.5 rounded-full bg-[#31313124] px-4 py-2.5 text-[13px] leading-snug text-[#313131] backdrop-blur-[6px] md:hidden">
			<span class="comparison-swipe-hint__icon shrink-0 font-sans text-[12px] tracking-tight text-[#313131]" aria-hidden="true">&lt; &gt;</span>
			<span><?php esc_html_e( 'Swipe left or right to view the full pricing', 'custom-manhattan-laser-theme' ); ?></span>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
$faq_category_term_id = $is_term ? (int) $term->term_id : 0;
$faq_bundle           = function_exists( 'custom_manhattan_laser_get_treatment_faq_for_display' )
	? custom_manhattan_laser_get_treatment_faq_for_display( $post_id, $faq_category_term_id )
	: array( 'heading' => '', 'intro' => '', 'items' => array() );
$faq_heading = isset( $faq_bundle['heading'] ) ? trim( (string) $faq_bundle['heading'] ) : '';
$faq_intro   = isset( $faq_bundle['intro'] ) ? trim( (string) $faq_bundle['intro'] ) : '';
$faq_items   = isset( $faq_bundle['items'] ) && is_array( $faq_bundle['items'] ) ? $faq_bundle['items'] : array();

$faq_schema_main = array();
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
	$faq_schema_main[] = array(
		'@type'          => 'Question',
		'name'           => $fq,
		'acceptedAnswer' => array(
			'@type' => 'Answer',
			'text'  => $fa,
		),
	);
}
$faq_post_slug    = $post_id ? (string) get_post_field( 'post_name', $post_id ) : '';
$faq_details_name = $faq_post_slug ? 'faq-treatment-' . sanitize_title( $faq_post_slug ) : 'faq-treatment';
?>
<?php if ( ! empty( $faq_schema_main ) ) : ?>
<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_schema_main ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
<?php endif; ?>
	<?php
	if ( function_exists( 'custom_manhattan_laser_render_treatment_medicalbusiness_physician_schema' ) ) {
		// $term can be null; gallery urls may be empty.
		custom_manhattan_laser_render_treatment_medicalbusiness_physician_schema( $post_id, $term, $ml_treatment_gallery_image_urls );
	}
	?>
<?php if ( ! empty( $faq_visible_items ) ) : ?>
<section class="faq-section bg-[#18100D] py-14 md:py-24 relative" id="faq" aria-labelledby="faq-heading">
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
					<p class="mt-5 max-w-[254px]  text-[15px] leading-[1.5] text-[#F4EFE8]/90 md:max-w-[297px] md:text-[16px]">
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

<?php
$ml_treatment_author = function_exists( 'custom_manhattan_laser_get_treatment_author_doctor_for_display' )
	? custom_manhattan_laser_get_treatment_author_doctor_for_display( $post_id )
	: null;
?>
<?php if ( is_array( $ml_treatment_author ) && ! empty( $ml_treatment_author['name'] ) && ! empty( $ml_treatment_author['url'] ) ) : ?>
<section
	class="treatment-author-byline border-t border-[#F4EFE814] bg-[#18100D] py-10 md:py-12"
	aria-labelledby="treatment-author-heading"
	itemscope
	itemtype="https://schema.org/Person"
>
	<div class="container mx-auto max-w-[1400px] px-5">
		<p id="treatment-author-heading" class="mb-4 font-sans text-[12px] font-medium uppercase tracking-[0.12em] text-[#F4EFE8]/55">
			<?php esc_html_e( 'Medical review / author', 'custom-manhattan-laser-theme' ); ?>
		</p>
		<div class="flex max-w-xl flex-row items-center gap-4">
			<?php if ( '' !== (string) $ml_treatment_author['avatar_url'] ) : ?>
				<img
					src="<?php echo esc_url( $ml_treatment_author['avatar_url'] ); ?>"
					alt="<?php echo esc_attr( sprintf( /* translators: %s: doctor name */ __( 'Photo of %s', 'custom-manhattan-laser-theme' ), $ml_treatment_author['name'] ) ); ?>"
					class="h-14 w-14 shrink-0 rounded-full object-cover ring-1 ring-[#F4EFE8]/20"
					width="56"
					height="56"
					decoding="async"
					itemprop="image"
				>
			<?php else : ?>
				<span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#F4EFE8]/10 font-display text-[18px] uppercase text-[#F4EFE8]" aria-hidden="true">
					<?php echo esc_html( function_exists( 'mb_substr' ) ? mb_substr( (string) $ml_treatment_author['name'], 0, 1 ) : substr( (string) $ml_treatment_author['name'], 0, 1 ) ); ?>
				</span>
			<?php endif; ?>
			<div class="min-w-0">
				<a
					href="<?php echo esc_url( $ml_treatment_author['url'] ); ?>"
					class="font-display text-[20px] leading-tight text-[#F4EFE8] underline-offset-4 hover:underline"
					rel="author"
					itemprop="url"
				>
					<span itemprop="name"><?php echo esc_html( $ml_treatment_author['name'] ); ?></span>
				</a>
				<?php if ( '' !== (string) $ml_treatment_author['role'] ) : ?>
					<p class="mt-1 font-sans text-[14px] leading-snug text-[#F4EFE8]/70" itemprop="jobTitle">
						<?php echo esc_html( $ml_treatment_author['role'] ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

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
$booking_cities       = function_exists( 'custom_manhattan_laser_get_booking_city_options' ) ? custom_manhattan_laser_get_booking_city_options() : array();
$booking_doctors      = function_exists( 'custom_manhattan_laser_get_booking_doctor_options' ) ? custom_manhattan_laser_get_booking_doctor_options() : array();
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

<?php
get_footer();
