</main>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home          = home_url( '/' );
$testimonials  = trailingslashit( $home ) . '#testimonials-heading';
$treatments    = post_type_exists( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : $home;
$before_after  = trailingslashit( $home ) . '#before-after';
$blog_page_id  = (int) get_option( 'page_for_posts' );
$blog_url      = $blog_page_id ? get_permalink( $blog_page_id ) : $home;
$faq_url       = trailingslashit( $home ) . '#faq';
$year          = gmdate( 'Y' );
?>
<footer class="site-footer bg-black text-white" role="contentinfo">
	<!-- Мобилка: как на макете (двухколоночный блок ссылок + локации, юр. ссылки столбиком) -->
	<div class="md:hidden ">
		<div class="container px-5 pt-12 pb-10">
			<a href="<?php echo esc_url( $home ); ?>" class="inline-block w-fit shrink-0 mt-20">
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/img/logo.svg' ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					class="h-9 w-auto object-contain object-left"
					loading="lazy"
				/>
			</a>
			<p class="mt-4 max-w-[167px] text-[16px] leading-snug text-white">
				<?php esc_html_e( 'Where science meets refined aesthetics.', 'custom-manhattan-laser-theme' ); ?>
			</p>

			<div class="mt-10 grid gap-y-10 grid-cols-2 gap-x-6 text-left">

					<div>
						<h2 class="font-display text-[20px] mb-4 font-normal uppercase text-white">
							<?php esc_html_e( 'Clinic', 'custom-manhattan-laser-theme' ); ?>
						</h2>
						<nav class="mt-4 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'Clinic menu', 'custom-manhattan-laser-theme' ); ?>">
							<a href="<?php echo home_url( '/reviews' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Reviews', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="<?php echo home_url( '/treatments' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Treatments', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="<?php echo home_url( '/before-after' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Before / After', 'custom-manhattan-laser-theme' ); ?></a>
						</nav>
					</div>
					<div>
						<h3 class="font-display text-[20px] mb-4 font-normal text-white">
							<?php esc_html_e( 'Midtown', 'custom-manhattan-laser-theme' ); ?>
						</h3>
						<p class="mt-3 text-[16px] leading-relaxed text-white/85">
							<?php esc_html_e( '808 Lexington Ave, Fl 2, New York, NY 10065', 'custom-manhattan-laser-theme' ); ?>
						</p>
					</div>
					<div>
						<h2 class="font-display text-[20px] mb-4 font-normal uppercase text-white">
							<?php esc_html_e( 'Social', 'custom-manhattan-laser-theme' ); ?>
						</h2>
						<nav class="mt-4 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'Social links', 'custom-manhattan-laser-theme' ); ?>">
							<a href="#" class="transition-opacity hover:opacity-80" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="#" class="transition-opacity hover:opacity-80" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="#" class="transition-opacity hover:opacity-80" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'WhatsApp', 'custom-manhattan-laser-theme' ); ?></a>
						</nav>
					</div>
					
					<div>
						<h2 class="font-display text-[20px] mb-4 font-normal uppercase text-white">
							<?php esc_html_e( 'About', 'custom-manhattan-laser-theme' ); ?>
						</h2>
						<nav class="mt-4 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'About menu', 'custom-manhattan-laser-theme' ); ?>">
							<a href="<?php echo home_url( '/about-the-doctor-medical-team' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'About us', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="<?php echo home_url( '/faq' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'FAQ', 'custom-manhattan-laser-theme' ); ?></a>
							<a href="<?php echo home_url( '/contact' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Contact', 'custom-manhattan-laser-theme' ); ?></a>
						</nav>
					</div>
					
					
					
			</div>

			<div class="mt-12 border-t border-white/15 pt-8">
				<div class="flex flex-col gap-2.5 text-[16px] text-white">
					<a href="<?php echo home_url( '/privacy-policy' ); ?>" class="w-fit transition-opacity hover:opacity-80"><?php esc_html_e( 'Privacy Policy', 'custom-manhattan-laser-theme' ); ?></a>
					<a href="<?php echo home_url( '/manage-cookies' ); ?>" class="w-fit transition-opacity hover:opacity-80"><?php esc_html_e( 'Manage cookies', 'custom-manhattan-laser-theme' ); ?></a>
					<a href="<?php echo home_url( '/terms-and-agreements' ); ?>" class="w-fit transition-opacity hover:opacity-80"><?php esc_html_e( 'Terms and agreements', 'custom-manhattan-laser-theme' ); ?></a>
				</div>
				<p class="py-8 text-[16px]">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: year */
							__( '© %s Manhattan Laser', 'custom-manhattan-laser-theme' ),
							$year
						)
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<!-- Десктоп (md+): без изменений -->
	<div class="hidden md:block">
		<div class=" pt-14 pb-6 md:pt-10 md:pb-6">
			<div class="grid grid-cols-1 gap-12 sm:grid-cols-2 md:grid-cols-[3fr_2fr_2fr_2fr] lg:gap-0 container">
				<!-- Колонка 1: логотип + слоган -->
				<div class="flex flex-col ">
					<a href="<?php echo esc_url( $home ); ?>" class="inline-block w-fit shrink-0">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/img/logo.svg' ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="h-9 w-auto object-contain md:h-[53px]"
							loading="lazy"
						/>
					</a>
					<p class="mt-10 max-w-[325px] text-[17px] text-white md:text-[24px]">
						<?php esc_html_e( 'Where science meets refined aesthetics.', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>

				<!-- Колонка 2: CLINIC -->
				<div class="flex flex-col">
					<h2 class="font-display text-[20px] mb-3 mt-[5] font-normal uppercase text-white md:text-[24px]">
						<?php esc_html_e( 'Clinic', 'custom-manhattan-laser-theme' ); ?>
					</h2>
					<nav class="mb-8 md:mb-10 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'Clinic menu', 'custom-manhattan-laser-theme' ); ?>">
						<a href="<?php echo home_url( '/reviews' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Testimonials', 'custom-manhattan-laser-theme' ); ?></a>
						<a href="<?php echo home_url( '/treatments' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Treatments', 'custom-manhattan-laser-theme' ); ?></a>
						<a href="<?php echo home_url( '/before-after' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Before / After', 'custom-manhattan-laser-theme' ); ?></a>
						<a href="<?php echo home_url( '/blog' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Blog', 'custom-manhattan-laser-theme' ); ?></a>
					</nav>
					<h3 class="font-display text-[20px] mb-3 mt-[5] font-normal uppercase text-white md:text-[24px]">
						<?php esc_html_e( 'Midtown', 'custom-manhattan-laser-theme' ); ?>
					</h3>
					<p class="mt-3 max-w-[165px] text-[16px] text-white">
						<?php esc_html_e( '56 E 34th St, 2nd Floor, New York, NY 10016', 'custom-manhattan-laser-theme' ); ?>
					</p>
				</div>

				<!-- Колонка 3: ABOUT -->
				<div class="flex flex-col">
					<h2 class="font-display text-[20px] mb-3 mt-[5] font-normal uppercase text-white md:text-[24px]">
						<?php esc_html_e( 'About', 'custom-manhattan-laser-theme' ); ?>
					</h2>
					<nav class="mb-8 md:mb-10 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'About menu', 'custom-manhattan-laser-theme' ); ?>">
						<a href="<?php echo home_url( '/about-the-doctor-medical-team' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'About us', 'custom-manhattan-laser-theme' ); ?></a>
						<a href="<?php echo home_url( '/faq' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'FAQ', 'custom-manhattan-laser-theme' ); ?></a>
						<a href="<?php echo home_url( '/contact' ); ?>" class="transition-opacity hover:opacity-80"><?php esc_html_e( 'Contact', 'custom-manhattan-laser-theme' ); ?></a>
						<p class="transition-opacity hover:opacity-80 invisible"><?php esc_html_e( 'Location', 'custom-manhattan-laser-theme' ); ?></p>
					</nav>
				</div>

				<!-- Колонка 4: SOCIAL -->
				<div class="flex flex-col  lg:ml-20">
					<h2 class="font-display text-[20px] mb-3 mt-[5] font-normal uppercase text-white md:text-[24px]">
						<?php esc_html_e( 'Social', 'custom-manhattan-laser-theme' ); ?>
					</h2>
					<nav class="mb-8 md:mb-10 flex flex-col gap-2.5 text-[16px] leading-snug text-white" aria-label="<?php esc_attr_e( 'Social links', 'custom-manhattan-laser-theme' ); ?>">
						<a href="https://www.instagram.com/manhattan_laser?igsh=aHhjZ29sdGkxbXNk&utm_source=qr" class="transition-opacity hover:opacity-80" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'custom-manhattan-laser-theme' ); ?></a>
						<p class="transition-opacity hover:opacity-80 invisible"><?php esc_html_e( 'Follow us', 'custom-manhattan-laser-theme' ); ?></p>
					</nav>
				</div>
			</div>

			<div class="mt-14 border-t border-white/15 pt-8 md:mt-20 md:pt-6">
				<div class="grid grid-cols-1 text-[11px] leading-tight text-white/55 sm:grid-cols-2 md:grid-cols-[3fr_2fr_2fr_2fr] md:items-center md:text-xs container">
					<div>
						<a href="<?php echo home_url( '/privacy-policy' ); ?>" class="transition-opacity text-[16px] text-white md:text-left w-fit"><?php esc_html_e( 'Privacy Policy', 'custom-manhattan-laser-theme' ); ?></a>
					</div>
					<div>
						<a href="<?php echo home_url( '/manage-cookies' ); ?>" class="transition-opacity text-[16px] text-white md:text-center w-fit"><?php esc_html_e( 'Manage cookies', 'custom-manhattan-laser-theme' ); ?></a>
					</div>
					<div>
						<a href="<?php echo home_url( '/terms-and-agreements' ); ?>" class="transition-opacity text-[16px] text-white md:text-center w-fit"><?php esc_html_e( 'Terms and agreements', 'custom-manhattan-laser-theme' ); ?></a>
					</div>
					<div class="lg:ml-20">
						<p class="md:text-right w-fit text-[16px] text-white">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %s: year */
									__( '© %s Manhattan Laser', 'custom-manhattan-laser-theme' ),
									$year
								)
							);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
