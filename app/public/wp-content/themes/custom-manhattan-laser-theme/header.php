<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( '' ); ?>>
<?php wp_body_open(); ?>

<header class="relative header z-[1000]">
	<div class="header__inner header__inner--mobile">
	<div class="container ">
		<div class="header__bar flex justify-between items-center  lg:grid lg:grid-cols-3 lg:justify-items-center py-7">
			<a href="<?php echo home_url(); ?>" class="header__logo flex items-center shrink-0 justify-self-start">
				<img src="<?php echo get_template_directory_uri() ?>/img/logo.svg" alt="Manhattan Laser - Medical Aesthetic Clinic" class="h-8 w-auto object-contain lg:h-10">
			</a>
			<nav class="hidden lg:block w-full">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'flex justify-between gap-5 items-center w-full',
							'fallback_cb'    => false,
						)
					);
				}
				?>
			</nav>

			<div class="flex items-center gap-4 justify-self-end">
				<button
					type="button"
					class="search-trigger hidden lg:flex items-center gap-10 rounded-full bg-[#F4EFE8]/10 pl-5 pr-7 py-3 text-[16px] backdrop-blur-[6px] relative group"
					aria-expanded="false"
					aria-controls="search-overlay"
				>
					<span class="inline-flex h-7 w-7 items-center justify-center absolute top-1/2 -translate-y-1/2 left-[-35px]">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/img/search.svg' ); ?>"
							alt=""
							class="h-7 w-7"
							loading="lazy"
						/>
					</span>
					<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
						View Treatments
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
				</button>
				<button
					type="button"
					class="header__hamburger lg:hidden flex flex-col justify-center items-center gap-1.5 w-8 h-8 text-[#F4EFE8] aria-label="<?php esc_attr_e( 'Open menu', 'custom-manhattan-laser-theme' ); ?>"
					aria-expanded="false"
					aria-controls="mobile-menu"
				>
					<span class="header__hamburger-line block w-6 h-0.5 bg-current rounded-full transition-transform duration-300 ease-out origin-center" aria-hidden="true"></span>
					<span class="header__hamburger-line block w-6 h-0.5 bg-current rounded-full transition-transform duration-300 ease-out origin-center" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
	</div>

	<?php
	$mobile_menu_procedures = function_exists( 'custom_manhattan_laser_get_treatment_menu_groups' )
		? custom_manhattan_laser_get_treatment_menu_groups()
		: array();
	if ( empty( $mobile_menu_procedures ) && post_type_exists( 'treatment' ) ) {
		$arch = get_post_type_archive_link( 'treatment' );
		if ( $arch ) {
			$mobile_menu_procedures = array(
				array(
					'label' => __( 'All treatments', 'custom-manhattan-laser-theme' ),
					'url'   => $arch,
					'items' => array(),
				),
			);
		}
	}
	$down_arrow_src = get_template_directory_uri() . '/img/down-arrow.svg';
	?>
	<div id="mobile-menu" class="mobile-menu fixed inset-0 z-50 lg:hidden bg-[#18100D] flex flex-col justify-between" aria-hidden="true">
		<div class="mobile-menu__header flex items-center justify-between px-5 py-6 shrink-0">
			<a href="<?php echo home_url(); ?>" class="mobile-menu__logo">
				<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Manhattan Laser - Medical Aesthetic Clinic" class="h-8 w-auto object-contain" width="200" height="32">
			</a>
			<button type="button" class="mobile-menu__close flex items-center justify-center text-[#F4EFE8] transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e( 'Close menu', 'custom-manhattan-laser-theme' ); ?>">
				<svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>
		<nav class="mobile-menu__nav flex-1 overflow-y-auto px-5 pt-10">
			<ul class="mobile-menu__list flex flex-col gap-1">
				<li class="mobile-menu__item mobile-menu__item--accordion">
					<button type="button" class="mobile-menu__trigger w-full flex items-center justify-between pb-4 text-left font-display text-[24px] uppercase text-[#F4EFE8]" aria-expanded="false" data-mobile-accordion>
						<a href="#">TREATMENTS</a>
						<img src="<?php echo esc_url( $down_arrow_src ); ?>" alt="" class="mobile-menu__caret-img h-8 w-8 shrink-0 transition-transform duration-200" aria-hidden="true">
					</button>
					<ul class="mobile-menu__sublist mobile-menu__sublist--l1 hidden pb-6">
						<?php foreach ( $mobile_menu_procedures as $cat ) : ?>
							<?php
							$cat_href = isset( $cat['url'] ) ? $cat['url'] : ( post_type_exists( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : home_url( '/' ) );
							?>
							<?php if ( empty( $cat['items'] ) ) : ?>
						<li class="mobile-menu__item">
							<a href="<?php echo esc_url( $cat_href ); ?>" class="block pb-4 text-left text-[20px] uppercase text-[#F4EFE8]"><?php echo esc_html( $cat['label'] ); ?></a>
						</li>
							<?php else : ?>
						<li class="mobile-menu__item mobile-menu__item--accordion">
							<button type="button" class="mobile-menu__trigger w-full flex items-center gap-1 pb-2 text-left text-[20px] uppercase text-[#F4EFE8]" aria-expanded="false" data-mobile-accordion>
								<a href="<?php echo esc_url( $cat_href ); ?>"><?php echo esc_html( $cat['label'] ); ?></a>
								<img src="<?php echo esc_url( $down_arrow_src ); ?>" alt="" class="mobile-menu__caret-img h-6 w-6 shrink-0 transition-transform duration-200" aria-hidden="true">
							</button>
							<ul class="mobile-menu__sublist mobile-menu__sublist--l2 hidden pb-6">
								<?php foreach ( $cat['items'] as $proc ) : ?>
								<li>
									<a href="<?php echo esc_url( $proc['url'] ); ?>" class="block pt-2 text-[16px] text-[#F4EFE8]/80 hover:text-[#F4EFE8]"><?php echo esc_html( $proc['title'] ); ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/membership/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">MEMBERSHIP</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/reviews/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">TESTIMONIALS</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/faq/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">FAQ</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/contact/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">CONTACT & LOCATION</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/about-the-doctor-medical-team/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">ABOUT US</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/before-after/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">BEFORE / AFTER</a></li>
				<li class="mobile-menu__item"><a href="<?php echo home_url('/blog/'); ?>" class="mobile-menu__link block pb-4 font-display text-[24px] uppercase text-[#F4EFE8]">BLOG</a></li>
			</ul>
		</nav>
		<div class="mobile-menu__footer shrink-0 px-5 pb-10 pt-8">
			<div class="flex items-center gap-5">
				<a href="#" class="text-[#F4EFE8] transition-opacity" aria-label="Instagram"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.785-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
				<a href="#" class="text-[#F4EFE8] transition-opacity" aria-label="Facebook"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
				<a href="#" class="text-[#F4EFE8] transition-opacity" aria-label="WhatsApp"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
			</div>
		</div>
	</div>

	<?php
	$pc_menu_groups = function_exists( 'custom_manhattan_laser_get_treatment_menu_groups' )
		? array_slice( custom_manhattan_laser_get_treatment_menu_groups(), 0, 3 )
		: array();
	?>
	<div id="pc-menu" class="pc-menu hidden lg:block bg-[#1F2A44]/30 backdrop-blur-[10px] absolute top-full left-0 w-full z-10 border-t border-t-[#F4EFE8]/10">
		<div class="container">
			<div class="grid grid-cols-1 gap-10 py-9 md:grid-cols-2 lg:grid-cols-3 lg:justify-items-start lg:items-start">
				<?php if ( ! empty( $pc_menu_groups ) ) : ?>
					<?php foreach ( $pc_menu_groups as $col ) : ?>
						<?php
						$col_items   = isset( $col['items'] ) && is_array( $col['items'] ) ? $col['items'] : array();
						$col_total   = count( $col_items );
						$col_shown   = array_slice( $col_items, 0, 5 );
						$col_more    = max( 0, $col_total - 5 );
						?>
					<div class="flex flex-col items-start justify-start">
						<h3 class="mb-5 text-[18px] font-semibold text-[#F4EFE8]">
							<p class=""><?php echo esc_html( $col['label'] ); ?></p>
						</h3>
						<?php if ( ! empty( $col_shown ) ) : ?>
						<ul class="flex flex-col items-start justify-start gap-2.5 text-[16px] text-[#F4EFE8]/85">
							<?php foreach ( $col_shown as $proc ) : ?>
							<li class="hover:text-[#F4EFE8]"><a href="<?php echo esc_url( $proc['url'] ); ?>"><?php echo esc_html( $proc['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
						<div class="mt-5 flex flex-wrap items-baseline gap-2 uppercase">
							<a href="<?php echo esc_url( $col['url'] ); ?>" class="text-[15px] font-semibold text-[#F4EFE8] underline"><?php esc_html_e( 'View all', 'custom-manhattan-laser-theme' ); ?></a>
							<?php if ( $col_more > 0 ) : ?>
							<span class="text-[15px] font-semibold text-[#F4EFE8]" aria-hidden="true"><?php echo esc_html( (string) $col_more . '+' ); ?></span>
							<span class="sr-only"><?php echo esc_html( sprintf( /* translators: %d: number of additional procedures */ _n( '%d more procedure in this category.', '%d more procedures in this category.', $col_more, 'custom-manhattan-laser-theme' ), $col_more ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				<?php elseif ( post_type_exists( 'treatment' ) && get_post_type_archive_link( 'treatment' ) ) : ?>
					<div class="flex flex-col items-start justify-start lg:col-span-3">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'treatment' ) ); ?>" class="text-[18px] font-semibold text-[#F4EFE8] underline"><?php esc_html_e( 'Browse all treatments', 'custom-manhattan-laser-theme' ); ?></a>
						<p class="mt-3 max-w-md text-[15px] text-[#F4EFE8]/70"><?php esc_html_e( 'Add categories and procedures in the WordPress admin (Treatments → Category).', 'custom-manhattan-laser-theme' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div id="search-overlay" class="search-overlay bg-[#1F2A44]/20 backdrop-blur-[10px] absolute top-full left-0 w-full z-20 border-t border-t-[#F4EFE8]/10 py-6" role="dialog" aria-label="<?php esc_attr_e( 'Поиск', 'custom-manhattan-laser-theme' ); ?>">
		<div class="search-overlay__bar container flex items-center gap-4 py-4">
			<input
				type="search"
				class="search-overlay__input flex-1 min-w-0 text-[16px] text-[#F4EFE8] placeholder:text-[#F4EFE8]/50 border-0 focus:ring-0 focus:outline-none bg-transparent"
				placeholder="<?php esc_attr_e( 'Search services, classes and articles....', 'custom-manhattan-laser-theme' ); ?>"
				autocomplete="off"
				aria-label="<?php esc_attr_e( 'Search', 'custom-manhattan-laser-theme' ); ?>"
			/>
			<button type="button" class="search-close flex h-6 w-6 shrink-0 items-center justify-center  text-[#F4EFE8] transition-colors" aria-label="<?php esc_attr_e( 'Закрыть поиск', 'custom-manhattan-laser-theme' ); ?>">
				<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>
		<div class="search-overlay__results pt-4">
			<div
				class="search-overlay__results-inner container py-3"
				data-search-results
				hidden
				aria-live="polite"
			></div>
		</div>
	</div>

</header>

<main class="content overflow-hidden">