<?php
/**
 * Template Name: Contact
 * Шаблон страницы Contact
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$contact_page_url = get_permalink();
$breadcrumb_items = array(
	array(
		'name' => __( 'Home', 'custom-manhattan-laser-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'name' => get_the_title() ? get_the_title() : __( 'Contact', 'custom-manhattan-laser-theme' ),
		'url'  => $contact_page_url ? $contact_page_url : '',
	),
);
?>

<section
	class="contact-locations pb-16 md:pb-24"
	aria-label="<?php esc_attr_e( 'Contact', 'custom-manhattan-laser-theme' ); ?>"
>

<nav class="!pb-5 text-[#F4EFE8] border-b border-[#F4EFE80D] container" aria-label="<?php esc_attr_e( 'Breadcrumb', 'custom-manhattan-laser-theme' ); ?>">
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
	<div class="container !max-w-[910px]">


		<h1 class="font-display text-center text-[36px] md:text-[96px] font-normal text-[#F4EFE8]">
			<?php esc_html_e( 'Contact Us', 'custom-manhattan-laser-theme' ); ?>
		</h1>

		<p class="mx-auto mt-6 max-w-[530px] text-center text-[16px] text-[#F4EFE8]">
			<?php esc_html_e( 'Visit us on the Upper East Side for professional aesthetic care in a modern, welcoming environment.', 'custom-manhattan-laser-theme' ); ?>
		</p>

		<div class="mt-10 border-t border-[#F4EFE81A] pt-10 md:mt-16 md:border-0 md:pt-0">
			<div class="mb-10 flex justify-between gap-4 md:mb-16 md:grid md:grid-cols-[2fr_3fr] md:gap-x-8 md:items-start">
				<div class="shrink-0 text-[16px] text-[#F4EFE8B2]">
					<?php esc_html_e( 'Address', 'custom-manhattan-laser-theme' ); ?>
				</div>
				<div class="min-w-0 text-right md:text-left">
					<p class="text-[16px] font-semibold text-[#FFFFFF] md:text-[#F4EFE8B2]">
						<?php esc_html_e( 'Upper East Side', 'custom-manhattan-laser-theme' ); ?>
					</p>
					<p class="mt-1 whitespace-pre-line text-[16px] text-[#FFFFFF]">
						<?php echo esc_html( "808 Lexington Ave, Fl 2,\nNew York, NY 10065" ); ?>
					</p>
				</div>
			</div>

			<div class="border-t border-b border-[#F4EFE81A] py-0 md:border-t-0 md:border-b md:pb-0">
				<div
					id="contact-google-map"
					class="contact-google-map -mx-5 h-[min(420px,55vh)] min-h-[280px] w-full overflow-hidden sm:mx-0 md:rounded-sm"
					role="region"
					aria-label="<?php esc_attr_e( 'Map: clinic location', 'custom-manhattan-laser-theme' ); ?>"
				></div>
			</div>

			<div class="flex justify-between gap-4 border-b border-[#F4EFE81A] py-10 md:grid md:grid-cols-[2fr_3fr] md:gap-x-8 md:items-start md:py-12">
				<div class="max-w-[48%] shrink-0 text-[16px] font-semibold leading-snug text-[#F4EFE8B2] md:max-w-none">
					<?php esc_html_e( 'Statutory company name', 'custom-manhattan-laser-theme' ); ?>
				</div>
				<div class="min-w-0 text-right text-[16px] text-[#FFFFFF] md:text-left">
					<?php esc_html_e( 'Manhattan Laser', 'custom-manhattan-laser-theme' ); ?>
				</div>
			</div>

			<div class="flex justify-between gap-4 border-b border-[#F4EFE81A] py-10 md:grid md:grid-cols-[2fr_3fr] md:gap-x-8 md:items-start md:py-12">
				<div class="shrink-0 text-[16px] font-semibold text-[#F4EFE8B2]">
					<?php esc_html_e( 'Opening Hours', 'custom-manhattan-laser-theme' ); ?>
				</div>
				<div class="min-w-0 whitespace-pre-line text-right text-[16px] text-[#FFFFFF] md:text-left">
					<?php echo esc_html( "Monday – Friday,\n9:00 AM – 6:00 PM" ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
