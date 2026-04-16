<?php
/**
 * 404 template.
 *
 * @package custom-manhattan-laser-theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$home_url       = home_url( '/' );
$treatments_url = post_type_exists( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : $home_url;
$blog_url       = function_exists( 'custom_manhattan_laser_get_blog_page_url' ) ? custom_manhattan_laser_get_blog_page_url() : $home_url;
?>

<main class="">
	<section class="container px-5 pb-16 pt-8 md:pb-24 md:pt-12">

		<div class="mx-auto mt-12 max-w-[760px] text-center md:mt-16">
			<p class="text-[14px] uppercase tracking-[0.14em] text-[#F4EFE8]/55 md:text-[15px]">Error</p>
			<h1 class="mt-3 font-display text-[56px] leading-[0.95] text-[#F4EFE8] md:text-[120px]">404</h1>
			<h2 class="mt-5 font-display text-[30px] leading-[1.08] text-[#F4EFE8] md:text-[48px]">
				<?php esc_html_e( 'Page not found', 'custom-manhattan-laser-theme' ); ?>
			</h2>
			<p class="mx-auto mt-5 max-w-[620px] text-[16px] leading-relaxed text-[#F4EFE8]/80 md:text-[18px]">
				<?php esc_html_e( 'The page you are looking for may have been moved, deleted, or never existed.', 'custom-manhattan-laser-theme' ); ?>
			</p>

			<div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
			<a href="<?php echo esc_url( $home_url ); ?>" class="flex items-center gap-4 md:gap-10 rounded-full bg-[#1F2A44] pl-5 pr-7 py-2.5 md:py-3 text-[16px] relative group w-fit h-fit">
                    <span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">
					Go to Home
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
                    </a>
					<a		href="<?php echo esc_url( $treatments_url ); ?>"
					class="flex items-center gap-10 rounded-full bg-[#F4EFE824] pl-5 pr-7 py-3 text-[16px] backdrop-blur-[7px] relative group"

				>
					<span class="whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1 text-[#F4EFE8]">
						View Treatments
					</span>
					<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]">
						<span class="absolute inset-0 rounded-full border border-[#F4EFE8]" aria-hidden="true"></span>
						<span class="absolute left-1/2 top-1/2 h-1 w-1 rounded-full bg-[#F4EFE8] -translate-x-1/2 -translate-y-1/2 translate-x-[15px] transition-transform duration-300 ease-out group-hover:translate-x-[-50%] group-hover:translate-y-[-50%]" aria-hidden="true"></span>
					</span>
			</a>
			</div>

			<p class="mt-8 text-[15px] text-[#F4EFE8]/70">
				<?php esc_html_e( 'You can also check the latest articles in our blog.', 'custom-manhattan-laser-theme' ); ?>
				<a href="<?php echo esc_url( $blog_url ); ?>" class="underline decoration-[#F4EFE8]/40 underline-offset-4 hover:decoration-[#F4EFE8]">
					<?php esc_html_e( 'Open Blog', 'custom-manhattan-laser-theme' ); ?>
				</a>
			</p>
		</div>
	</section>
</main>

<?php
get_footer();
