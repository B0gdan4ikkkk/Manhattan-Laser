<?php
/**
 * Архив всех процедур (/treatments/).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = get_terms(
	array(
		'taxonomy'   => 'treatment_category',
		'hide_empty' => false,
		'parent'     => 0,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
if ( is_wp_error( $categories ) ) {
	$categories = array();
}
?>

<?php
get_footer();
