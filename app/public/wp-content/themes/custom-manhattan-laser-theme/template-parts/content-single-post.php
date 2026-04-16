<?php
/**
 * Разметка одиночной записи блога (post).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();

	$blog_url = function_exists( 'custom_manhattan_laser_get_blog_page_url' )
		? custom_manhattan_laser_get_blog_page_url()
		: home_url( '/' );

	$breadcrumb_items = array(
		array(
			'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
			'url'  => home_url( '/' ),
		),
		array(
			'name' => __( 'Blog', 'custom-manhattan-laser-theme' ),
			'url'  => $blog_url,
		),
		array(
			'name' => get_the_title() ? get_the_title() : __( 'Post', 'custom-manhattan-laser-theme' ),
			'url'  => get_permalink(),
		),
	);

	$recommended_query = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 6,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'post_status'         => 'publish',
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
		)
	);
	$recommended_slides = array();
	if ( $recommended_query->have_posts() ) {
		while ( $recommended_query->have_posts() ) {
			$recommended_query->the_post();
			$post_id = get_the_ID();

			$img_id  = get_post_thumbnail_id( $post_id );
			$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';

			$short_desc = get_post_meta( $post_id, 'short_description', true );
			if ( '' === $short_desc ) {
				$short_desc = get_the_excerpt( $post_id );
			}

			$recommended_slides[] = array(
				'image' => $img_url,
				'title' => get_the_title( $post_id ),
				'desc'  => $short_desc,
				'date'  => get_the_date( 'F j, Y', $post_id ),
				'link'  => get_permalink( $post_id ),
			);
		}
		wp_reset_postdata();
	}
	?>

<article <?php post_class( 'blog-single pb-16 text-[#F4EFE8] md:pb-24' ); ?> itemscope itemtype="https://schema.org/BlogPosting">
	<nav class="blog-single__breadcrumb border-b border-[#F4EFE80D] container !pb-5 " aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
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
	if ( function_exists( 'custom_manhattan_laser_render_single_post_schema' ) ) {
		custom_manhattan_laser_render_single_post_schema( get_the_ID() );
	}
	?>

	<div class="container px-5 pt-8 md:pt-12">
		<?php
		$plain_text   = wp_strip_all_tags( (string) get_the_content() );
		$word_count   = (int) str_word_count( $plain_text );
		$reading_time = max( 1, (int) ceil( $word_count / 200 ) );
		$single_img_url = (string) get_post_meta( get_the_ID(), 'single_post_image_url', true );
		?>
		<div class="grid items-start gap-4 lg:grid-cols-[450px_minmax(0,1fr)] lg:gap-4">
			<aside class="blog-single__meta">
				<h1 class="font-display text-[36px] md:text-[48px] leading-[1.06] text-[#F4EFE8]" itemprop="headline">
					<?php the_title(); ?>
				</h1>
				<div class="">
					<div class="flex items-center justify-between gap-4 border-b border-[#F4EFE81A] py-3">
						<span class="text-[15px] text-[#F4EFE8]/55"><?php esc_html_e( 'Reading time:', 'custom-manhattan-laser-theme' ); ?></span>
						<span class="text-[15px] uppercase tracking-[0.04em] text-[#F4EFE8]"><?php echo esc_html( (string) $reading_time ); ?> MIN</span>
					</div>
					<div class="flex items-center justify-between gap-4 border-b border-[#F4EFE81A] py-3">
						<span class="text-[15px] text-[#F4EFE8]/55"><?php esc_html_e( 'Author:', 'custom-manhattan-laser-theme' ); ?></span>
						<span class="text-[15px] text-right uppercase tracking-[0.04em] text-[#F4EFE8]"><?php echo esc_html( get_the_author() ); ?></span>
					</div>
					<div class="flex items-center justify-between gap-4 border-b border-[#F4EFE81A] py-3">
						<span class="text-[15px] text-[#F4EFE8]/55"><?php esc_html_e( 'Published by:', 'custom-manhattan-laser-theme' ); ?></span>
						<span class="text-[15px] text-right uppercase tracking-[0.04em] text-[#F4EFE8]"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					</div>
					<div class="flex items-center justify-between gap-4 py-3">
						<span class="text-[15px] text-[#F4EFE8]/55"><?php esc_html_e( 'Date of publication:', 'custom-manhattan-laser-theme' ); ?></span>
						<time class="text-[15px] text-right uppercase tracking-[0.04em] text-[#F4EFE8]" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>" itemprop="datePublished">
							<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
						</time>
					</div>
				</div>
			</aside>

			<div class="min-w-0">
				<?php if ( '' !== $single_img_url || has_post_thumbnail() ) : ?>
					<div class="blog-single__featured overflow-hidden max-h-[400px]">
						<?php if ( '' !== $single_img_url ) : ?>
							<img
								src="<?php echo esc_url( $single_img_url ); ?>"
								class="h-auto w-full object-cover"
								itemprop="image"
								alt="<?php echo esc_attr( get_the_title() ); ?>"
								loading="lazy"
							>
						<?php else : ?>
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class'    => 'h-auto w-full object-cover',
									'itemprop' => 'image',
									'alt'      => esc_attr( get_the_title() ),
								)
							);
							?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="blog-single__content mt-7 text-[16px] leading-relaxed text-[#F4EFE8]/85 md:mt-8 md:text-[17px]" itemprop="articleBody">
					<h2 class="font-display text-[44px] leading-[1.04] text-[#F4EFE8] md:text-[58px]">
						<?php the_title(); ?>
					</h2>
					<?php if ( has_excerpt() ) : ?>
						<p class="mb-4 mt-3 text-[20px] leading-[1.3] text-[#F4EFE8] md:text-[24px]" itemprop="description">
							<?php echo esc_html( get_the_excerpt() ); ?>
						</p>
					<?php endif; ?>
					<?php the_content(); ?>
				</div>
			</div>
		</div>

		<?php
		wp_link_pages(
			array(
				'before' => '<nav class="mt-10 text-[15px] text-[#F4EFE8]/70" aria-label="' . esc_attr__( 'Page', 'custom-manhattan-laser-theme' ) . '"><p class="mb-2">' . esc_html__( 'Pages:', 'custom-manhattan-laser-theme' ) . '</p><ul class="flex flex-wrap gap-2">',
				'after'  => '</ul></nav>',
			)
		);
		?>
	</div>
</article>

	<?php if ( ! empty( $recommended_slides ) ) : ?>
		<section class="treatments-section py-16 md:py-24">
			<div class="treatments-section__row flex flex-col lg:flex-row items-start lg:items-stretch">
				<div class="treatments-section__text-wrap shrink-0 pr-6 lg:pr-10 relative z-10">
					<div class="treatments-section__text max-w-[380px] flex flex-col justify-between h-full">
						<p class="treatments-section__label mb-5 md:mb-7 flex items-center gap-2 text-[15px] md:text-[20px] text-[#F4EFE8]">
							<span class="h-1 w-1 rounded-full bg-[#F4EFE8]"></span>
							You may also like
						</p>
						<div class="flex flex-col">
							<h2 class="treatments-section__title font-display text-[32px] text-[#F4EFE8] md:text-[48px] leading-[100%]">
								Recommended Articles
							</h2>
							<p class="treatments-section__sub mt-5 text-[16px] text-[#F4EFE8] max-w-[330px]">
								More expert reads to continue your skincare journey with confidence.
							</p>
						</div>
					</div>
				</div>

				<div class="treatments-section__slider relative mt-10 flex-1 min-w-0 lg:mt-0 lg:pr-0 flex flex-col px-5 md:px-0 w-full">
					<div class="swiper blog-swiper order-1 w-full lg:order-2">
						<div class="swiper-wrapper">
							<?php foreach ( $recommended_slides as $slide ) : ?>
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
										<h3 class="treatments-card__title mt-1 font-display text-[20px] md:text-[24px] text-[#F4EFE8]">
											<a href="<?php echo esc_url( $slide['link'] ); ?>"><?php echo esc_html( $slide['title'] ); ?></a>
										</h3>
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
	<?php endif; ?>

	<?php
}

get_footer();
