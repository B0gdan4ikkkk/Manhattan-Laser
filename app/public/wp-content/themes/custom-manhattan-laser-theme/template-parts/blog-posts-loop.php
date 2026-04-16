<?php
/**
 * Сетка записей блога + пагинация (для blog.php и AJAX).
 *
 * @package custom-manhattan-laser-theme
 *
 * @var WP_Query $blog_query Запрос записей.
 * @var string   $blog_url    URL страницы блога (для пагинации).
 * @var int      $filter_cat  ID рубрики или 0.
 * @var string   $search_q    Строка поиска.
 * @var int      $paged       Номер страницы.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $blog_query ) || ! ( $blog_query instanceof WP_Query ) ) {
	return;
}

$blog_url   = isset( $blog_url ) ? $blog_url : home_url( '/' );
$filter_cat = isset( $filter_cat ) ? (int) $filter_cat : 0;
$search_q   = isset( $search_q ) ? (string) $search_q : '';
$paged      = isset( $paged ) ? max( 1, (int) $paged ) : 1;

if ( $blog_query->have_posts() ) : ?>
	<div class="blog-page__grid grid gap-10 sm:grid-cols-2 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-12 lg:items-stretch">
		<?php
		while ( $blog_query->have_posts() ) :
			$blog_query->the_post();
			?>
			<article <?php post_class( 'blog-card flex h-full flex-col' ); ?>>
				<a href="<?php the_permalink(); ?>" class="blog-card__thumb-link block aspect-square w-full shrink-0 overflow-hidden bg-[#1a1614] md:rounded-sm">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail(
							'large',
							array(
								'class'   => 'h-full w-full object-cover transition-transform duration-500 ease-out hover:scale-[1.03]',
								'loading' => 'lazy',
								'alt'     => esc_attr( get_the_title() ),
							)
						);
					} else {
						echo '<span class="block h-full w-full bg-[#1f1b18]" aria-hidden="true"></span>';
					}
					?>
				</a>
				<time class="mt-4 block shrink-0 text-[13px] text-[#F4EFE8]/50 md:text-[14px]" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
				</time>
				<h2 class="blog-card__title mt-2 shrink-0 font-display text-[22px] font-normal leading-snug text-[#F4EFE8] md:text-[24px] lg:text-[26px]">
					<a href="<?php the_permalink(); ?>" class="transition-opacity hover:opacity-85">
						<?php the_title(); ?>
					</a>
				</h2>
				<p class="blog-card__excerpt mt-3 flex-1 text-[14px] leading-relaxed text-[#F4EFE8]/65 md:text-[15px]">
					<span class="line-clamp-4 block"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></span>
				</p>
				<a href="<?php the_permalink(); ?>" class="blog-card__more mt-4 inline-block shrink-0 text-[14px] text-[#F4EFE8] underline underline-offset-4 transition-opacity hover:opacity-80">
					<?php esc_html_e( 'learn more', 'custom-manhattan-laser-theme' ); ?>
				</a>
			</article>
		<?php endwhile; ?>
	</div>

	<?php
	$total_pages = (int) $blog_query->max_num_pages;
	if ( $total_pages > 1 ) {
		global $wp_rewrite;
		if ( $wp_rewrite->using_permalinks() ) {
			$pagination_base = user_trailingslashit( trailingslashit( $blog_url ) . 'page/%#%/' );
		} else {
			$pagination_base = esc_url( add_query_arg( 'paged', '%#%', $blog_url ) );
		}
		$add_args = array();
		if ( $filter_cat ) {
			$add_args['cat'] = $filter_cat;
		}
		if ( $search_q !== '' ) {
			$add_args['s'] = $search_q;
		}
		$pagination = paginate_links(
			array(
				'base'      => $pagination_base,
				'format'    => '',
				'current'   => $paged,
				'total'     => $total_pages,
				'type'      => 'list',
				'prev_text' => __( '←', 'custom-manhattan-laser-theme' ),
				'next_text' => __( '→', 'custom-manhattan-laser-theme' ),
				'add_args'  => $add_args,
			)
		);
		if ( $pagination ) {
			echo '<nav class="blog-page__pagination mt-14 text-[#F4EFE8]/80" aria-label="' . esc_attr__( 'Posts pagination', 'custom-manhattan-laser-theme' ) . '">' . wp_kses_post( $pagination ) . '</nav>';
		}
	}
	wp_reset_postdata();
	?>
<?php else : ?>
	<p class="blog-page__empty text-[16px] text-[#F4EFE8]/60">
		<?php esc_html_e( 'No posts found.', 'custom-manhattan-laser-theme' ); ?>
	</p>
	<?php
	wp_reset_postdata();
endif;
