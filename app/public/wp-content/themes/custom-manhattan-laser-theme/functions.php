<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function custom_manhattan_laser_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Главное меню', 'custom-manhattan-laser-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'custom_manhattan_laser_theme_setup' );

/**
 * FAQ: класс body — сброс overflow у main через inline CSS (см. custom_manhattan_laser_faq_sticky_fix).
 *
 * @param string[] $classes Классы body.
 * @return string[]
 */
function custom_manhattan_laser_faq_body_class( $classes ) {
	if ( is_page_template( 'faq.php' ) ) {
		$classes[] = 'faq-layout-sticky';
	}
	return $classes;
}
add_filter( 'body_class', 'custom_manhattan_laser_faq_body_class' );

/**
 * FAQ: overflow-hidden на main в header ломает position:sticky у сайдбара.
 */
function custom_manhattan_laser_faq_sticky_fix() {
	if ( ! is_page_template( 'faq.php' ) ) {
		return;
	}
	$css = 'body.faq-layout-sticky main.content,body.faq-layout-sticky main.content.overflow-hidden{overflow:visible!important;}';
	wp_add_inline_style( 'custom-manhattan-laser-theme-tailwind', $css );
}
add_action( 'wp_enqueue_scripts', 'custom_manhattan_laser_faq_sticky_fix', 30 );

function custom_manhattan_laser_theme_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'custom-manhattan-laser-theme-tailwind',
		get_template_directory_uri() . '/assets/css/tailwind.css',
		array(),
		$theme_version
	);

	wp_enqueue_style(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11'
	);

	wp_enqueue_script(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11',
		true
	);

	wp_enqueue_script(
		'lenis',
		'https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js',
		array(),
		'1.1.18',
		true
	);

	wp_enqueue_script(
		'gsap',
		'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
		array(),
		'3.12.5',
		true
	);

	wp_enqueue_script(
		'gsap-scrolltrigger',
		'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
		array( 'gsap' ),
		'3.12.5',
		true
	);

	wp_enqueue_script(
		'custom-manhattan-laser-theme-main',
		get_template_directory_uri() . '/js/main.js',
		array( 'swiper', 'lenis', 'gsap-scrolltrigger' ),
		$theme_version,
		true
	);
	wp_localize_script(
		'custom-manhattan-laser-theme-main',
		'mlHeaderSearch',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'ml_header_treatments_search' ),
		)
	);

	wp_enqueue_script(
		'custom-manhattan-laser-theme-procedure-animation',
		get_template_directory_uri() . '/js/procedure-animation.js',
		array( 'custom-manhattan-laser-theme-main' ),
		$theme_version,
		true
	);

	$ml_blog_page_id = 0;
	if ( is_page_template( 'blog.php' ) ) {
		$ml_blog_page_id = get_queried_object_id();
	} elseif ( is_home() && ! is_front_page() ) {
		$ml_blog_page_id = (int) get_option( 'page_for_posts' );
	}
	if ( $ml_blog_page_id > 0 ) {
		wp_enqueue_script(
			'ml-blog-archive',
			get_template_directory_uri() . '/js/blog-archive.js',
			array(),
			$theme_version,
			true
		);
		wp_localize_script(
			'ml-blog-archive',
			'mlBlogArchive',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ml_blog_filter' ),
				'pageId'  => $ml_blog_page_id,
				'blogUrl' => get_permalink( $ml_blog_page_id ),
			)
		);
	}

	if ( is_page_template( 'contact.php' ) ) {
		if ( ! defined( 'CUSTOM_MANHATTAN_GOOGLE_MAPS_API_KEY' ) ) {
			define( 'CUSTOM_MANHATTAN_GOOGLE_MAPS_API_KEY', 'AIzaSyCyXRLpq4lCG1qDQvnOnb1yHCaSKnvO5Js' );
		}

		wp_enqueue_script(
			'ml-contact-google-map',
			get_template_directory_uri() . '/js/contact-google-map.js',
			array(),
			$theme_version,
			true
		);

		wp_localize_script(
			'ml-contact-google-map',
			'mlContactMapConfig',
			array(
				'address' => '808 Lexington Ave, Fl 2, New York, NY 10065',
				'zoom'    => 16,
			)
		);

		$gmaps_key = CUSTOM_MANHATTAN_GOOGLE_MAPS_API_KEY;
		if ( $gmaps_key ) {
			$gmaps_url = add_query_arg(
				array(
					'key'         => $gmaps_key,
					'callback'    => 'mlInitContactGoogleMap',
					'loading'     => 'async',
				),
				'https://maps.googleapis.com/maps/api/js'
			);

			wp_enqueue_script(
				'ml-google-maps-api',
				esc_url_raw( $gmaps_url ),
				array( 'ml-contact-google-map' ),
				null,
				true
			);
		}
	}

	if ( is_page_template( 'page-testimonials.php' ) ) {
		wp_enqueue_script(
			'ml-testimonials-page',
			get_template_directory_uri() . '/js/testimonials-page.js',
			array(),
			$theme_version,
			true
		);
	}

	if ( is_singular( 'treatment' ) ) {
		wp_enqueue_script(
			'custom-manhattan-neuro-timeline',
			get_template_directory_uri() . '/js/neuromodulator-timeline.js',
			array( 'custom-manhattan-laser-theme-main' ),
			$theme_version,
			true
		);
		wp_localize_script(
			'custom-manhattan-laser-theme-main',
			'mlBookingAjax',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'i18n' => array(
					'sending'        => __( 'Sending…', 'custom-manhattan-laser-theme' ),
					'networkError'   => __( 'Network error. Please try again.', 'custom-manhattan-laser-theme' ),
					'formInvalid'    => __( 'Please fill in all highlighted fields and accept the Privacy Policy.', 'custom-manhattan-laser-theme' ),
					'privacyRequired' => __( 'Please accept the Privacy Policy to continue.', 'custom-manhattan-laser-theme' ),
				),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'custom_manhattan_laser_theme_scripts' );

/**
 * Поиск на сайте: только обычные записи (post), в одном стиле с блогом.
 *
 * @param WP_Query $query Главный запрос.
 */
function custom_manhattan_laser_search_only_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$query->set( 'post_type', 'post' );
}
add_action( 'pre_get_posts', 'custom_manhattan_laser_search_only_posts' );

/**
 * AJAX: лента блога (фильтр рубрики + поиск + страница).
 */
function custom_manhattan_laser_ajax_blog_posts() {
	check_ajax_referer( 'ml_blog_filter', 'nonce' );

	$page_id = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
	$paged   = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
	$cat     = isset( $_POST['cat'] ) ? absint( $_POST['cat'] ) : 0;
	$s       = isset( $_POST['s'] ) ? sanitize_text_field( wp_unslash( $_POST['s'] ) ) : '';

	$posts_page = (int) get_option( 'page_for_posts' );
	$valid      = false;
	if ( $posts_page && $page_id === $posts_page ) {
		$valid = true;
	} elseif ( $page_id > 0 ) {
		$tpl = get_page_template_slug( $page_id );
		if ( is_string( $tpl ) && 'blog.php' === $tpl ) {
			$valid = true;
		}
	}

	if ( ! $valid ) {
		wp_send_json_error( array( 'message' => 'Invalid page' ), 403 );
	}

	$blog_url = get_permalink( $page_id );

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 9,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
	);
	if ( $cat ) {
		$args['cat'] = $cat;
	}
	if ( $s !== '' ) {
		$args['s'] = $s;
	}

	$blog_query = new WP_Query( $args );

	$filter_cat = $cat;
	$search_q   = $s;

	ob_start();
	include locate_template( 'template-parts/blog-posts-loop.php' );
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_ml_blog_posts', 'custom_manhattan_laser_ajax_blog_posts' );
add_action( 'wp_ajax_nopriv_ml_blog_posts', 'custom_manhattan_laser_ajax_blog_posts' );

/**
 * AJAX: поиск treatments для хедера (live при вводе).
 */
function custom_manhattan_laser_ajax_header_treatments_search() {
	check_ajax_referer( 'ml_header_treatments_search', 'nonce' );

	$search_raw = isset( $_POST['s'] ) ? wp_unslash( $_POST['s'] ) : '';
	$search     = sanitize_text_field( (string) $search_raw );

	if ( mb_strlen( $search ) < 2 ) {
		wp_send_json_success(
			array(
				'items' => array(),
			)
		);
	}

	$query = new WP_Query(
		array(
			'post_type'           => 'treatment',
			'post_status'         => 'publish',
			's'                   => $search,
			'posts_per_page'      => 8,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$items = array();
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$items[] = array(
				'title' => get_the_title( $post ),
				'url'   => get_permalink( $post ),
			);
		}
	}
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'items' => $items,
		)
	);
}
add_action( 'wp_ajax_ml_header_treatments_search', 'custom_manhattan_laser_ajax_header_treatments_search' );
add_action( 'wp_ajax_nopriv_ml_header_treatments_search', 'custom_manhattan_laser_ajax_header_treatments_search' );

/**
 * Post type: Treatments
 */
function custom_manhattan_laser_register_treatment_post_type() {
	$labels = array(
		'name'               => _x( 'Treatments', 'post type general name', 'custom-manhattan-laser-theme' ),
		'singular_name'      => _x( 'Treatment', 'post type singular name', 'custom-manhattan-laser-theme' ),
		'menu_name'          => _x( 'Treatments', 'admin menu', 'custom-manhattan-laser-theme' ),
		'add_new'            => _x( 'Add New', 'treatment', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add New Treatment', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit Treatment', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New Treatment', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View Treatment', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search Treatments', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No treatments found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No treatments found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'treatments' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-clipboard',
		'supports'           => array( 'title', 'editor', 'thumbnail'),
	);
	register_post_type( 'treatment', $args );
}
add_action( 'init', 'custom_manhattan_laser_register_treatment_post_type' );

/**
 * Post type: Doctors (команда, карточки на странице Our medical team).
 */
function custom_manhattan_laser_register_doctor_post_type() {
	$labels = array(
		'name'               => _x( 'Doctors', 'post type general name', 'custom-manhattan-laser-theme' ),
		'singular_name'      => _x( 'Doctor', 'post type singular name', 'custom-manhattan-laser-theme' ),
		'menu_name'          => _x( 'Doctors', 'admin menu', 'custom-manhattan-laser-theme' ),
		'add_new'            => _x( 'Add New', 'doctor', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add New Doctor', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit Doctor', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New Doctor', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View Doctor', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search Doctors', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No doctors found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No doctors found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => 'doctors',
			'with_front' => false,
		),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
	);
	register_post_type( 'doctor', $args );
}
add_action( 'init', 'custom_manhattan_laser_register_doctor_post_type' );

/**
 * URL страницы с шаблоном «Our medical team» (первая опубликованная).
 *
 * @return string
 */
function custom_manhattan_laser_get_medical_team_page_url() {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}
	$cached = home_url( '/' );
	$ids    = get_posts(
		array(
			'post_type'              => 'page',
			'posts_per_page'         => 1,
			'post_status'            => 'publish',
			'meta_key'               => '_wp_page_template',
			'meta_value'             => 'our-medical-team.php',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	if ( ! empty( $ids ) ) {
		$cached = get_permalink( (int) $ids[0] );
	}
	return $cached;
}

/**
 * Meta: роль / специализация врача (строка для карточки, например с «|»).
 */
function custom_manhattan_laser_register_doctor_meta() {
	register_post_meta(
		'doctor',
		'doctor_role',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
	foreach (
		array(
			'doctor_hero_kicker',
			'doctor_hero_bio',
			'doctor_credentials',
			'doctor_hero_bg_url',
			'doctor_faq_json',
			'doctor_same_as',
		) as $key
	) {
		register_post_meta(
			'doctor',
			$key,
			array(
				'type'          => 'string',
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'custom_manhattan_laser_register_doctor_meta' );

/**
 * Дефолтные пункты аккордеона «Areas of Expertise» (если мета ещё не сохраняли).
 *
 * @return array<int, array{q: string, a: string}>
 */
function custom_manhattan_laser_doctor_faq_default_items() {
	return array(
		array(
			'q' => __( 'Injectable Treatments (Botox & Dermal Fillers)', 'custom-manhattan-laser-theme' ),
			'a' => __( 'Non-surgical treatments designed to soften wrinkles, restore lost volume, and enhance facial contours while preserving natural expression.', 'custom-manhattan-laser-theme' ),
		),
		array(
			'q' => __( 'Advanced Skin Rejuvenation', 'custom-manhattan-laser-theme' ),
			'a' => '',
		),
		array(
			'q' => __( 'Non-Surgical Facial Contouring', 'custom-manhattan-laser-theme' ),
			'a' => '',
		),
		array(
			'q' => __( 'Laser & Energy-Based Therapies', 'custom-manhattan-laser-theme' ),
			'a' => '',
		),
		array(
			'q' => __( 'Personalized Anti-Aging Programs', 'custom-manhattan-laser-theme' ),
			'a' => '',
		),
	);
}

/**
 * Пункты FAQ/аккордеона для страницы врача.
 *
 * @param int $post_id ID записи doctor.
 * @return array<int, array{q: string, a: string}>
 */
function custom_manhattan_laser_get_doctor_faq_items( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return custom_manhattan_laser_doctor_faq_default_items();
	}
	if ( ! metadata_exists( 'post', $post_id, 'doctor_faq_json' ) ) {
		return custom_manhattan_laser_doctor_faq_default_items();
	}
	$raw = get_post_meta( $post_id, 'doctor_faq_json', true );
	if ( ! is_string( $raw ) || '' === $raw ) {
		return array();
	}
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) ) {
		return array();
	}
	$out = array();
	foreach ( $decoded as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$q = isset( $row['q'] ) ? trim( (string) $row['q'] ) : '';
		if ( '' === $q ) {
			continue;
		}
		$a = isset( $row['a'] ) ? trim( (string) $row['a'] ) : '';
		$out[] = array(
			'q' => $q,
			'a' => $a,
		);
	}
	return $out;
}

/**
 * Meta box: роль врача.
 */
function custom_manhattan_laser_doctor_role_meta_box() {
	add_meta_box(
		'doctor_role',
		__( 'Role / specialty (card)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_doctor_role_meta_box_cb',
		'doctor',
		'normal',
		'high'
	);
}

/**
 * @param WP_Post $post Post.
 */
function custom_manhattan_laser_doctor_role_meta_box_cb( $post ) {
	wp_nonce_field( 'doctor_role_nonce', 'doctor_role_nonce' );
	$value = get_post_meta( $post->ID, 'doctor_role', true );
	echo '<p><label for="doctor_role">' . esc_html__( 'Example: Aesthetic Nurse Practitioner | Energy-Based Specialist', 'custom-manhattan-laser-theme' ) . '</label></p>';
	echo '<p><textarea id="doctor_role" name="doctor_role" class="widefat" rows="2">' . esc_textarea( (string) $value ) . '</textarea></p>';
	echo '<p class="description">' . esc_html__( 'If «Hero kicker» below is empty, this text is also shown above the name on the profile page.', 'custom-manhattan-laser-theme' ) . '</p>';
}

/**
 * Meta box: контент героя страницы врача.
 */
function custom_manhattan_laser_doctor_hero_meta_box() {
	add_meta_box(
		'doctor_hero',
		__( 'Profile hero (single page)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_doctor_hero_meta_box_cb',
		'doctor',
		'normal',
		'default'
	);
}

/**
 * @param WP_Post $post Post.
 */
function custom_manhattan_laser_doctor_hero_meta_box_cb( $post ) {
	wp_nonce_field( 'doctor_hero_nonce', 'doctor_hero_nonce' );
	$kicker   = (string) get_post_meta( $post->ID, 'doctor_hero_kicker', true );
	$bio      = (string) get_post_meta( $post->ID, 'doctor_hero_bio', true );
	$creds  = (string) get_post_meta( $post->ID, 'doctor_credentials', true );
	$bg_url = (string) get_post_meta( $post->ID, 'doctor_hero_bg_url', true );

	echo '<p><label for="doctor_hero_kicker"><strong>' . esc_html__( 'Kicker (above name, caps)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="text" id="doctor_hero_kicker" name="doctor_hero_kicker" class="widefat" value="' . esc_attr( $kicker ) . '" placeholder="' . esc_attr__( 'MD, FOUNDER & MEDICAL DIRECTOR', 'custom-manhattan-laser-theme' ) . '"></p>';
	echo '<p><label for="doctor_hero_bio"><strong>' . esc_html__( 'Intro paragraph (right column)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><textarea id="doctor_hero_bio" name="doctor_hero_bio" class="widefat" rows="4">' . esc_textarea( $bio ) . '</textarea></p>';
	echo '<p class="description">' . esc_html__( 'If empty, the post excerpt is used when set.', 'custom-manhattan-laser-theme' ) . '</p>';
	echo '<p><label for="doctor_credentials"><strong>' . esc_html__( 'Credentials (one bullet per line)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><textarea id="doctor_credentials" name="doctor_credentials" class="widefat" rows="5" placeholder="' . esc_attr__( 'Graduate of…', 'custom-manhattan-laser-theme' ) . '">' . esc_textarea( $creds ) . '</textarea></p>';
	echo '<p><label for="doctor_hero_bg_url"><strong>' . esc_html__( 'Hero background image URL (optional)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="url" id="doctor_hero_bg_url" name="doctor_hero_bg_url" class="widefat" value="' . esc_attr( $bg_url ) . '"></p>';
	echo '<p class="description">' . esc_html__( 'If empty, the default theme background is used.', 'custom-manhattan-laser-theme' ) . '</p>';

	$same_as = (string) get_post_meta( $post->ID, 'doctor_same_as', true );
	echo '<p><label for="doctor_same_as"><strong>' . esc_html__( 'Schema: profile links (sameAs)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><textarea id="doctor_same_as" name="doctor_same_as" class="widefat" rows="3" placeholder="https://">' . esc_textarea( $same_as ) . '</textarea></p>';
	echo '<p class="description">' . esc_html__( 'One URL per line (e.g. professional profile). Used in structured data when this doctor is the procedure author.', 'custom-manhattan-laser-theme' ) . '</p>';
}

/**
 * Meta box: аккордеон «Areas of Expertise» (заголовок секции в шаблоне не меняется).
 */
function custom_manhattan_laser_doctor_faq_meta_box() {
	add_meta_box(
		'doctor_faq',
		__( 'Profile: accordion items (Areas of Expertise)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_doctor_faq_meta_box_cb',
		'doctor',
		'normal',
		'default'
	);
}

/**
 * @param WP_Post $post Post.
 */
function custom_manhattan_laser_doctor_faq_meta_box_cb( $post ) {
	wp_nonce_field( 'doctor_faq_nonce', 'doctor_faq_nonce' );
	$rows = array();
	if ( metadata_exists( 'post', $post->ID, 'doctor_faq_json' ) ) {
		$raw = get_post_meta( $post->ID, 'doctor_faq_json', true );
		if ( is_string( $raw ) && '' !== $raw ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				$rows = $decoded;
			}
		}
	} else {
		$rows = custom_manhattan_laser_doctor_faq_default_items();
	}
	while ( count( $rows ) < 10 ) {
		$rows[] = array( 'q' => '', 'a' => '' );
	}
	$rows = array_slice( $rows, 0, 10 );

	echo '<p class="description">' . esc_html__( 'Title = accordion row; answer is optional (empty = title only). Up to 10 items.', 'custom-manhattan-laser-theme' ) . '</p>';

	for ( $i = 0; $i < 10; $i++ ) {
		$q = isset( $rows[ $i ]['q'] ) ? (string) $rows[ $i ]['q'] : '';
		$a = isset( $rows[ $i ]['a'] ) ? (string) $rows[ $i ]['a'] : '';
		$n = $i + 1;
		echo '<fieldset style="margin:1em 0;padding:12px;border:1px solid #c3c4c7;"><legend>' . esc_html( sprintf( /* translators: %d: item number 1–10 */ __( 'Item %d', 'custom-manhattan-laser-theme' ), $n ) ) . '</legend>';
		echo '<p><label for="doctor_faq_q_' . esc_attr( (string) $i ) . '"><strong>' . esc_html__( 'Title', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
		echo '<p><input type="text" id="doctor_faq_q_' . esc_attr( (string) $i ) . '" name="doctor_faq_q_' . esc_attr( (string) $i ) . '" class="widefat" value="' . esc_attr( $q ) . '"></p>';
		echo '<p><label for="doctor_faq_a_' . esc_attr( (string) $i ) . '"><strong>' . esc_html__( 'Answer (optional)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
		echo '<p><textarea id="doctor_faq_a_' . esc_attr( (string) $i ) . '" name="doctor_faq_a_' . esc_attr( (string) $i ) . '" class="widefat" rows="3">' . esc_textarea( $a ) . '</textarea></p>';
		echo '</fieldset>';
	}
}

/**
 * @param int $post_id Post ID.
 */
function custom_manhattan_laser_save_doctor_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['doctor_role_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['doctor_role_nonce'] ), 'doctor_role_nonce' ) && isset( $_POST['doctor_role'] ) ) {
		update_post_meta( $post_id, 'doctor_role', sanitize_textarea_field( wp_unslash( $_POST['doctor_role'] ) ) );
	}
	if ( isset( $_POST['doctor_hero_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['doctor_hero_nonce'] ), 'doctor_hero_nonce' ) ) {
		if ( isset( $_POST['doctor_hero_kicker'] ) ) {
			update_post_meta( $post_id, 'doctor_hero_kicker', sanitize_text_field( wp_unslash( $_POST['doctor_hero_kicker'] ) ) );
		}
		if ( isset( $_POST['doctor_hero_bio'] ) ) {
			update_post_meta( $post_id, 'doctor_hero_bio', sanitize_textarea_field( wp_unslash( $_POST['doctor_hero_bio'] ) ) );
		}
		if ( isset( $_POST['doctor_credentials'] ) ) {
			update_post_meta( $post_id, 'doctor_credentials', sanitize_textarea_field( wp_unslash( $_POST['doctor_credentials'] ) ) );
		}
		if ( isset( $_POST['doctor_hero_bg_url'] ) ) {
			update_post_meta( $post_id, 'doctor_hero_bg_url', esc_url_raw( wp_unslash( $_POST['doctor_hero_bg_url'] ) ) );
		}
		if ( isset( $_POST['doctor_same_as'] ) ) {
			update_post_meta( $post_id, 'doctor_same_as', sanitize_textarea_field( wp_unslash( $_POST['doctor_same_as'] ) ) );
		}
	}
	if ( isset( $_POST['doctor_faq_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['doctor_faq_nonce'] ), 'doctor_faq_nonce' ) ) {
		$items = array();
		for ( $i = 0; $i < 10; $i++ ) {
			$q_key = 'doctor_faq_q_' . $i;
			$a_key = 'doctor_faq_a_' . $i;
			$q     = isset( $_POST[ $q_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $q_key ] ) ) : '';
			$a     = isset( $_POST[ $a_key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $a_key ] ) ) : '';
			if ( '' !== $q || '' !== $a ) {
				$items[] = array(
					'q' => $q,
					'a' => $a,
				);
			}
		}
		update_post_meta( $post_id, 'doctor_faq_json', wp_json_encode( $items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
	}
}
add_action( 'add_meta_boxes', 'custom_manhattan_laser_doctor_role_meta_box' );
add_action( 'add_meta_boxes', 'custom_manhattan_laser_doctor_hero_meta_box' );
add_action( 'add_meta_boxes', 'custom_manhattan_laser_doctor_faq_meta_box' );
add_action( 'save_post_doctor', 'custom_manhattan_laser_save_doctor_meta' );

/**
 * Шаблон одиночной записи врача: явный путь (дочерняя тема → родительская).
 * Нужен, если WP по какой-то причине не берёт single-doctor.php из иерархии.
 *
 * @param string $template Полный путь к шаблону.
 * @return string
 */
function custom_manhattan_laser_single_doctor_template( $template ) {
	if ( ! is_singular( 'doctor' ) ) {
		return $template;
	}
	$candidates = array(
		get_stylesheet_directory() . '/single-doctor.php',
		get_template_directory() . '/single-doctor.php',
	);
	foreach ( $candidates as $path ) {
		if ( is_readable( $path ) ) {
			return $path;
		}
	}
	return $template;
}
add_filter( 'single_template', 'custom_manhattan_laser_single_doctor_template', 99 );

/**
 * Явно регистрируем шаблон страницы «Our medical team» в выпадающем списке.
 *
 * @param string[] $post_templates Список шаблонов.
 * @return string[]
 */
function custom_manhattan_laser_register_medical_team_page_template( $post_templates ) {
	$post_templates['our-medical-team.php'] = __( 'Our medical team', 'custom-manhattan-laser-theme' );
	return $post_templates;
}
add_filter( 'theme_page_templates', 'custom_manhattan_laser_register_medical_team_page_template' );

/**
 * Шаблон страницы отзывов.
 *
 * @param string[] $post_templates Список шаблонов.
 * @return string[]
 */
function custom_manhattan_laser_register_testimonials_page_template( $post_templates ) {
	$post_templates['page-testimonials.php'] = __( 'Patient testimonials', 'custom-manhattan-laser-theme' );
	return $post_templates;
}
add_filter( 'theme_page_templates', 'custom_manhattan_laser_register_testimonials_page_template' );

/**
 * Шаблон страницы FAQ.
 *
 * @param string[] $post_templates Список шаблонов.
 * @return string[]
 */
function custom_manhattan_laser_register_faq_page_template( $post_templates ) {
	$post_templates['faq.php'] = __( 'FAQ', 'custom-manhattan-laser-theme' );
	return $post_templates;
}
add_filter( 'theme_page_templates', 'custom_manhattan_laser_register_faq_page_template' );

/**
 * Шаблон страницы Membership.
 *
 * @param string[] $post_templates Список шаблонов.
 * @return string[]
 */
function custom_manhattan_laser_register_membership_page_template( $post_templates ) {
	$post_templates['membership.php'] = __( 'Membership', 'custom-manhattan-laser-theme' );
	return $post_templates;
}
add_filter( 'theme_page_templates', 'custom_manhattan_laser_register_membership_page_template' );

/**
 * CPT: вопросы/ответы для страницы FAQ (категории — таксономия faq_category).
 */
function custom_manhattan_laser_register_faq_item_post_type() {
	$labels = array(
		'name'               => _x( 'FAQ entries', 'post type general name', 'custom-manhattan-laser-theme' ),
		'singular_name'      => _x( 'FAQ entry', 'post type singular name', 'custom-manhattan-laser-theme' ),
		'menu_name'          => _x( 'FAQ', 'admin menu', 'custom-manhattan-laser-theme' ),
		'add_new'            => _x( 'Add New', 'faq_item', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add FAQ entry', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit FAQ entry', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New FAQ entry', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View FAQ entry', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search FAQ', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No FAQ entries found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No FAQ entries found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	register_post_type(
		'faq_item',
		array(
			'labels'              => $labels,
			'description'         => __( 'Questions and answers displayed on the FAQ page.', 'custom-manhattan-laser-theme' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-editor-help',
			'menu_position'       => 26,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor', 'page-attributes' ),
			'has_archive'         => false,
			'rewrite'               => false,
			'exclude_from_search' => true,
			'query_var'           => false,
		)
	);
}

/**
 * Категории FAQ (якоря в боковой навигации).
 */
function custom_manhattan_laser_register_faq_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'FAQ categories', 'taxonomy general name', 'custom-manhattan-laser-theme' ),
		'singular_name'     => _x( 'FAQ category', 'taxonomy singular name', 'custom-manhattan-laser-theme' ),
		'search_items'      => __( 'Search categories', 'custom-manhattan-laser-theme' ),
		'all_items'         => __( 'All categories', 'custom-manhattan-laser-theme' ),
		'parent_item'       => __( 'Parent category', 'custom-manhattan-laser-theme' ),
		'parent_item_colon' => __( 'Parent category:', 'custom-manhattan-laser-theme' ),
		'edit_item'         => __( 'Edit category', 'custom-manhattan-laser-theme' ),
		'update_item'       => __( 'Update category', 'custom-manhattan-laser-theme' ),
		'add_new_item'      => __( 'Add New category', 'custom-manhattan-laser-theme' ),
		'new_item_name'     => __( 'New category name', 'custom-manhattan-laser-theme' ),
	);
	register_taxonomy(
		'faq_category',
		array( 'faq_item' ),
		array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_faq_item_post_type' );
add_action( 'init', 'custom_manhattan_laser_register_faq_category_taxonomy', 1 );

/**
 * Одиночные URL у FAQ-записей отключены.
 */
function custom_manhattan_laser_block_faq_item_front() {
	if ( is_admin() ) {
		return;
	}
	if ( is_singular( 'faq_item' ) || is_post_type_archive( 'faq_item' ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'custom_manhattan_laser_block_faq_item_front', 0 );

/**
 * @param string  $post_link URL.
 * @param WP_Post $post      Post.
 * @return string
 */
function custom_manhattan_laser_faq_item_post_type_link( $post_link, $post ) {
	if ( $post instanceof WP_Post && 'faq_item' === $post->post_type ) {
		return '';
	}
	return $post_link;
}
add_filter( 'post_type_link', 'custom_manhattan_laser_faq_item_post_type_link', 10, 2 );

/**
 * CPT: отзывы пациентов (публикация на странице «Patient testimonials»).
 */
function custom_manhattan_laser_register_patient_review_post_type() {
	$labels = array(
		'name'               => _x( 'Patient reviews', 'post type general name', 'custom-manhattan-laser-theme' ),
		'singular_name'      => _x( 'Patient review', 'post type singular name', 'custom-manhattan-laser-theme' ),
		'menu_name'          => _x( 'Reviews', 'admin menu', 'custom-manhattan-laser-theme' ),
		'add_new'            => _x( 'Add New', 'patient review', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add New Review', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit Review', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New Review', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View Review', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search Reviews', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No reviews found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No reviews found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	register_post_type(
		'patient_review',
		array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-star-filled',
			'menu_position'       => 7,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title', 'editor' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_patient_review_post_type' );

/**
 * Отзывы хранятся в БД, но не имеют публичных URL: любой запрос одиночной страницы — 404.
 */
function custom_manhattan_laser_block_patient_review_front() {
	if ( is_admin() ) {
		return;
	}
	if ( is_singular( 'patient_review' ) || is_post_type_archive( 'patient_review' ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'custom_manhattan_laser_block_patient_review_front', 0 );

/**
 * Не формировать «красивые» permalink для отзывов (без отдельных синглов в ссылках).
 *
 * @param string  $post_link URL.
 * @param WP_Post $post      Post.
 * @return string
 */
function custom_manhattan_laser_patient_review_post_type_link( $post_link, $post ) {
	if ( $post instanceof WP_Post && 'patient_review' === $post->post_type ) {
		return '';
	}
	return $post_link;
}
add_filter( 'post_type_link', 'custom_manhattan_laser_patient_review_post_type_link', 10, 2 );

/**
 * Meta для отзыва: рейтинг, врач, услуга, email заявителя.
 */
function custom_manhattan_laser_register_patient_review_meta() {
	$keys = array(
		'review_rating',
		'review_doctor_id',
		'review_treatment_id',
		'review_reviewer_email',
		'review_reviewer_phone',
		'review_privacy_agreed',
	);
	foreach ( $keys as $key ) {
		register_post_meta(
			'patient_review',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'custom_manhattan_laser_register_patient_review_meta' );

/**
 * Вывод звёзд рейтинга (заполненные золотом, остальные приглушённые).
 *
 * @param int         $rating 1–5.
 * @param string|null $extra_class Доп. классы на обёртку.
 */
function custom_manhattan_laser_render_review_stars_markup( $rating, $extra_class = '' ) {
	$rating = (int) min( 5, max( 1, $rating ) );
	$class  = trim( 'ml-review-stars flex gap-px text-[15px] leading-none md:text-[18px] ' . (string) $extra_class );
	echo '<span class="' . esc_attr( $class ) . '" aria-hidden="true">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$on = $i <= $rating;
		echo '<span class="' . esc_attr( $on ? 'text-[#FFB800]' : 'text-[#f5f5f0]/25' ) . '">★</span>';
	}
	echo '</span>';
}

/**
 * Карточка отзыва на странице Patient testimonials (шаблон + AJAX).
 *
 * @param WP_Post $review_post Post.
 */
function custom_manhattan_laser_render_patient_review_card( $review_post ) {
	if ( ! $review_post instanceof WP_Post ) {
		return;
	}
	$rid          = $review_post->ID;
	$rating       = (int) get_post_meta( $rid, 'review_rating', true );
	if ( $rating < 1 || $rating > 5 ) {
		$rating = 5;
	}
	$doctor_id    = (int) get_post_meta( $rid, 'review_doctor_id', true );
	$treatment_id = (int) get_post_meta( $rid, 'review_treatment_id', true );
	$doctor_name  = $doctor_id && get_post( $doctor_id ) ? get_the_title( $doctor_id ) : '';
	$service_name = $treatment_id && get_post( $treatment_id ) ? get_the_title( $treatment_id ) : '';
	?>
	<article class="testimonials-page-card flex flex-col rounded-[24px] bg-[#2D2926] p-5 md:rounded-[40px] md:bg-[#F4EFE824] md:p-8 md:px-8 md:pt-8 md:pb-12 md:backdrop-blur-[6px]" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: reviewer name */ __( 'Review by %s', 'custom-manhattan-laser-theme' ), get_the_title( $rid ) ) ); ?>">
		<div class="flex items-start justify-between gap-3 md:items-center md:gap-4">
			<h2 class="min-w-0 flex-1 font-display text-[18px] font-normal leading-tight text-[#F4EFE8] md:text-[32px]">
				<?php echo esc_html( get_the_title( $rid ) ); ?>
			</h2>
			<div class="shrink-0 pt-0.5 md:pt-0">
				<?php
				if ( function_exists( 'custom_manhattan_laser_render_review_stars_markup' ) ) {
					custom_manhattan_laser_render_review_stars_markup( $rating, 'testimonials-page-card__stars' );
				}
				?>
			</div>
		</div>
		<?php if ( $doctor_name !== '' || $service_name !== '' ) : ?>
			<?php
			$meta_bits = array();
			if ( $doctor_name !== '' ) {
				$meta_bits[] =
					'<span class="text-[#9E9A96]">' . esc_html__( 'Doctor:', 'custom-manhattan-laser-theme' ) . '</span> '
					. '<span class="text-[#F4EFE8]/90">' . esc_html( $doctor_name ) . '</span>';
			}
			if ( $service_name !== '' ) {
				$meta_bits[] =
					'<span class="text-[#9E9A96]">' . esc_html__( 'Service:', 'custom-manhattan-laser-theme' ) . '</span> '
					. '<span class="text-[#F4EFE8]/90">' . esc_html( $service_name ) . '</span>';
			}
			?>
			<p class="mt-3 font-sans text-[12px] leading-relaxed md:mt-3 md:text-[16px]">
				<?php echo wp_kses_post( implode( ' · ', $meta_bits ) ); ?>
			</p>
		<?php endif; ?>
		<div class="testimonials-page-card__body mt-4 font-sans text-[14px] leading-[1.5] text-[#F4EFE8]/75 md:mt-5 md:text-[16px] md:text-[#f5f5f0]/85">
			<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $rid ) ) ); ?>
		</div>
	</article>
	<?php
}

/**
 * HTML одной карточки отзыва (для AJAX).
 *
 * @param WP_Post $review_post Post.
 * @return string
 */
function custom_manhattan_laser_get_patient_review_card_html( $review_post ) {
	ob_start();
	custom_manhattan_laser_render_patient_review_card( $review_post );
	return (string) ob_get_clean();
}

/**
 * AJAX: подгрузка следующей порции отзывов на странице testimonials.
 */
function custom_manhattan_laser_ajax_load_more_reviews() {
	check_ajax_referer( 'ml_testimonials_load_more', 'nonce' );

	$page           = isset( $_POST['page'] ) ? max( 1, absint( wp_unslash( $_POST['page'] ) ) ) : 1;
	$filter_doctor  = isset( $_POST['filter_doctor'] ) ? absint( wp_unslash( $_POST['filter_doctor'] ) ) : 0;
	$filter_service = isset( $_POST['filter_service'] ) ? absint( wp_unslash( $_POST['filter_service'] ) ) : 0;

	$meta_query = array();
	if ( $filter_doctor > 0 ) {
		$meta_query[] = array(
			'key'     => 'review_doctor_id',
			'value'   => (string) $filter_doctor,
			'compare' => '=',
		);
	}
	if ( $filter_service > 0 ) {
		$meta_query[] = array(
			'key'     => 'review_treatment_id',
			'value'   => (string) $filter_service,
			'compare' => '=',
		);
	}

	$args = array(
		'post_type'      => 'patient_review',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'paged'          => $page,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => false,
	);
	if ( count( $meta_query ) > 0 ) {
		if ( count( $meta_query ) > 1 ) {
			$meta_query['relation'] = 'AND';
		}
		$args['meta_query'] = $meta_query;
	}

	$q = new WP_Query( $args );

	$html = '';
	foreach ( $q->posts as $post_obj ) {
		if ( $post_obj instanceof WP_Post ) {
			$html .= custom_manhattan_laser_get_patient_review_card_html( $post_obj );
		}
	}

	wp_send_json(
		array(
			'success'  => true,
			'html'     => $html,
			'has_more' => (int) $page < (int) $q->max_num_pages,
		)
	);
}
add_action( 'wp_ajax_ml_load_more_reviews', 'custom_manhattan_laser_ajax_load_more_reviews' );
add_action( 'wp_ajax_nopriv_ml_load_more_reviews', 'custom_manhattan_laser_ajax_load_more_reviews' );

/**
 * HTML колонки со списком отзывов + кнопка «Read more» (первая страница выдачи).
 *
 * @param int $filter_doctor  ID врача или 0.
 * @param int $filter_service ID услуги или 0.
 * @return array HTML и признак наличия следующей страницы.
 */
function custom_manhattan_laser_get_testimonials_reviews_column_html( $filter_doctor, $filter_service ) {
	$filter_doctor  = absint( $filter_doctor );
	$filter_service = absint( $filter_service );

	$meta_query = array();
	if ( $filter_doctor > 0 ) {
		$meta_query[] = array(
			'key'     => 'review_doctor_id',
			'value'   => (string) $filter_doctor,
			'compare' => '=',
		);
	}
	if ( $filter_service > 0 ) {
		$meta_query[] = array(
			'key'     => 'review_treatment_id',
			'value'   => (string) $filter_service,
			'compare' => '=',
		);
	}

	$reviews_args = array(
		'post_type'      => 'patient_review',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'paged'          => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => false,
	);
	if ( count( $meta_query ) > 0 ) {
		if ( count( $meta_query ) > 1 ) {
			$meta_query['relation'] = 'AND';
		}
		$reviews_args['meta_query'] = $meta_query;
	}

	$reviews_query = new WP_Query( $reviews_args );
	$review_posts  = $reviews_query->posts;

	ob_start();
	if ( empty( $review_posts ) ) {
		echo '<p class="font-sans text-[15px] text-[#f5f5f0]/60">';
		esc_html_e( 'No reviews match your filters yet.', 'custom-manhattan-laser-theme' );
		echo '</p>';
	} else {
		echo '<div id="testimonials-reviews-list" class="flex flex-col gap-4 md:gap-8">';
		foreach ( $review_posts as $rp ) {
			if ( function_exists( 'custom_manhattan_laser_render_patient_review_card' ) ) {
				custom_manhattan_laser_render_patient_review_card( $rp );
			}
		}
		echo '</div>';
		if ( (int) $reviews_query->max_num_pages > 1 ) {
			echo '<button type="button" class="group relative mt-6 flex w-full items-center justify-center gap-7 rounded-full bg-[#1B263B] py-2.5 pl-5 pr-7 font-sans text-[16px] text-[#FBF5E7] transition-opacity hover:opacity-95 md:gap-20 md:py-3" data-testimonials-read-more aria-busy="false">';
			echo '<span class="testimonials-read-more__label whitespace-nowrap transition-transform duration-300 ease-out group-hover:translate-x-1">';
			esc_html_e( 'Read more', 'custom-manhattan-laser-theme' );
			echo '</span>';
			echo '<span class="relative inline-flex h-4 w-4 shrink-0 items-center justify-center transition-transform duration-300 ease-out group-hover:translate-x-[7px]" aria-hidden="true">';
			echo '<span class="absolute inset-0 rounded-full border border-[#F4EFE8]"></span>';
			echo '<span class="absolute left-1/2 top-1/2 h-1 w-1 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#F4EFE8]"></span>';
			echo '</span></button>';
		}
	}
	$html = (string) ob_get_clean();

	return array(
		'html'     => $html,
		'has_more' => (int) $reviews_query->max_num_pages > 1,
	);
}

/**
 * AJAX: смена фильтров на странице testimonials без перезагрузки.
 */
function custom_manhattan_laser_ajax_filter_testimonials() {
	check_ajax_referer( 'ml_testimonials_load_more', 'nonce' );

	$filter_doctor  = isset( $_POST['filter_doctor'] ) ? absint( wp_unslash( $_POST['filter_doctor'] ) ) : 0;
	$filter_service = isset( $_POST['filter_service'] ) ? absint( wp_unslash( $_POST['filter_service'] ) ) : 0;

	$result = custom_manhattan_laser_get_testimonials_reviews_column_html( $filter_doctor, $filter_service );

	wp_send_json(
		array(
			'success'        => true,
			'content_html'   => $result['html'],
			'has_more'       => $result['has_more'],
			'filter_doctor'  => $filter_doctor,
			'filter_service' => $filter_service,
		)
	);
}
add_action( 'wp_ajax_ml_filter_testimonials', 'custom_manhattan_laser_ajax_filter_testimonials' );
add_action( 'wp_ajax_nopriv_ml_filter_testimonials', 'custom_manhattan_laser_ajax_filter_testimonials' );

/**
 * Локализация скрипта страницы testimonials (nonce + AJAX).
 *
 * @param int    $filter_doctor  Filter.
 * @param int    $filter_service Filter.
 * @param int    $max_pages      Max query pages.
 * @param string $page_url       Permalink страницы testimonials (для history.replaceState).
 */
function custom_manhattan_laser_localize_testimonials_script( $filter_doctor, $filter_service, $max_pages, $page_url = '' ) {
	if ( ! is_string( $page_url ) ) {
		$page_url = '';
	}
	$page_url = esc_url( $page_url );
	wp_localize_script(
		'ml-testimonials-page',
		'mlTestimonials',
		array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'action'        => 'ml_load_more_reviews',
			'filterAction'  => 'ml_filter_testimonials',
			'nonce'         => wp_create_nonce( 'ml_testimonials_load_more' ),
			'nextPage'      => 2,
			'hasMore'       => $max_pages > 1,
			'filterDoctor'  => $filter_doctor,
			'filterService' => $filter_service,
			'pageUrl'       => $page_url,
			'i18n'               => array(
				'readMore'     => __( 'Read more', 'custom-manhattan-laser-theme' ),
				'loading'      => __( 'Loading…', 'custom-manhattan-laser-theme' ),
				'sending'      => __( 'Sending…', 'custom-manhattan-laser-theme' ),
				'networkError' => __( 'Network error. Please try again.', 'custom-manhattan-laser-theme' ),
			),
			'reviewSubmitAction' => 'ml_submit_patient_review',
		)
	);
}

/**
 * Metabox: врач и услуга (для модерации и ручного ввода).
 */
function custom_manhattan_laser_patient_review_details_meta_box() {
	add_meta_box(
		'patient_review_details',
		__( 'Review details', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_patient_review_details_meta_box_cb',
		'patient_review',
		'normal',
		'high'
	);
}

/**
 * @param WP_Post $post Post.
 */
function custom_manhattan_laser_patient_review_details_meta_box_cb( $post ) {
	wp_nonce_field( 'patient_review_details_nonce', 'patient_review_details_nonce' );
	$rating      = (int) get_post_meta( $post->ID, 'review_rating', true );
	$doctor_id   = (int) get_post_meta( $post->ID, 'review_doctor_id', true );
	$treatment_id = (int) get_post_meta( $post->ID, 'review_treatment_id', true );
	$email       = (string) get_post_meta( $post->ID, 'review_reviewer_email', true );
	$phone       = (string) get_post_meta( $post->ID, 'review_reviewer_phone', true );

	echo '<p><label for="review_rating"><strong>' . esc_html__( 'Rating (1–5)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="number" id="review_rating" name="review_rating" min="1" max="5" step="1" value="' . esc_attr( $rating ? (string) $rating : '5' ) . '" class="small-text"></p>';

	echo '<p><label for="review_doctor_id"><strong>' . esc_html__( 'Doctor', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<select id="review_doctor_id" name="review_doctor_id" class="widefat">';
	echo '<option value="0">' . esc_html__( '— None —', 'custom-manhattan-laser-theme' ) . '</option>';
	if ( post_type_exists( 'doctor' ) ) {
		$doctors = get_posts(
			array(
				'post_type'      => 'doctor',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $doctors as $did ) {
			echo '<option value="' . esc_attr( (string) $did ) . '"' . selected( $doctor_id, $did, false ) . '>' . esc_html( get_the_title( $did ) ) . '</option>';
		}
	}
	echo '</select>';

	echo '<p><label for="review_treatment_id"><strong>' . esc_html__( 'Service (treatment)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<select id="review_treatment_id" name="review_treatment_id" class="widefat">';
	echo '<option value="0">' . esc_html__( '— None —', 'custom-manhattan-laser-theme' ) . '</option>';
	if ( post_type_exists( 'treatment' ) ) {
		$treatments = get_posts(
			array(
				'post_type'      => 'treatment',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $treatments as $tid ) {
			echo '<option value="' . esc_attr( (string) $tid ) . '"' . selected( $treatment_id, $tid, false ) . '>' . esc_html( get_the_title( $tid ) ) . '</option>';
		}
	}
	echo '</select>';

	echo '<p><label for="review_reviewer_email"><strong>' . esc_html__( 'Reviewer email (optional)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="email" id="review_reviewer_email" name="review_reviewer_email" class="widefat" value="' . esc_attr( $email ) . '"></p>';

	echo '<p><label for="review_reviewer_phone"><strong>' . esc_html__( 'Reviewer phone (optional)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="text" id="review_reviewer_phone" name="review_reviewer_phone" class="widefat" value="' . esc_attr( $phone ) . '"></p>';
}

/**
 * @param int $post_id Post ID.
 */
function custom_manhattan_laser_save_patient_review_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['patient_review_details_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['patient_review_details_nonce'] ), 'patient_review_details_nonce' ) ) {
		return;
	}
	if ( get_post_type( $post_id ) !== 'patient_review' ) {
		return;
	}
	if ( isset( $_POST['review_rating'] ) ) {
		$r = (int) $_POST['review_rating'];
		$r = min( 5, max( 1, $r ) );
		update_post_meta( $post_id, 'review_rating', (string) $r );
	}
	if ( isset( $_POST['review_doctor_id'] ) ) {
		update_post_meta( $post_id, 'review_doctor_id', (string) absint( wp_unslash( $_POST['review_doctor_id'] ) ) );
	}
	if ( isset( $_POST['review_treatment_id'] ) ) {
		update_post_meta( $post_id, 'review_treatment_id', (string) absint( wp_unslash( $_POST['review_treatment_id'] ) ) );
	}
	if ( isset( $_POST['review_reviewer_email'] ) ) {
		update_post_meta( $post_id, 'review_reviewer_email', sanitize_email( wp_unslash( $_POST['review_reviewer_email'] ) ) );
	}
	if ( isset( $_POST['review_reviewer_phone'] ) ) {
		update_post_meta( $post_id, 'review_reviewer_phone', sanitize_text_field( wp_unslash( $_POST['review_reviewer_phone'] ) ) );
	}
}
add_action( 'add_meta_boxes', 'custom_manhattan_laser_patient_review_details_meta_box' );
add_action( 'save_post_patient_review', 'custom_manhattan_laser_save_patient_review_meta' );

/**
 * Обработка данных формы отзыва (POST). Используется admin-post и AJAX.
 *
 * @return array{ok:bool, code?:string, post_id?:int}
 */
function custom_manhattan_laser_process_patient_review_from_post() {
	if ( ! isset( $_POST['ml_review_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ml_review_nonce'] ), 'ml_review_submit' ) ) {
		return array( 'ok' => false, 'code' => 'nonce' );
	}
	if ( ! empty( $_POST['ml_review_hp'] ) ) {
		return array( 'ok' => false, 'code' => 'spam' );
	}
	if ( empty( $_POST['ml_review_privacy'] ) ) {
		return array( 'ok' => false, 'code' => 'privacy' );
	}

	$name   = isset( $_POST['ml_review_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_review_name'] ) ) : '';
	$email  = isset( $_POST['ml_review_email'] ) ? sanitize_email( wp_unslash( $_POST['ml_review_email'] ) ) : '';
	$phone  = isset( $_POST['ml_review_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_review_phone'] ) ) : '';
	$text   = isset( $_POST['ml_review_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ml_review_text'] ) ) : '';
	$rating = isset( $_POST['ml_review_rating'] ) ? (int) wp_unslash( $_POST['ml_review_rating'] ) : 0;
	$doc_id = isset( $_POST['ml_review_doctor'] ) ? absint( wp_unslash( $_POST['ml_review_doctor'] ) ) : 0;
	$tr_id  = isset( $_POST['ml_review_treatment'] ) ? absint( wp_unslash( $_POST['ml_review_treatment'] ) ) : 0;

	if ( strlen( $name ) < 2 || strlen( $text ) < 10 || $rating < 1 || $rating > 5 || $doc_id <= 0 || $tr_id <= 0 ) {
		return array( 'ok' => false, 'code' => 'invalid' );
	}
	if ( $doc_id && post_type_exists( 'doctor' ) && get_post_type( $doc_id ) !== 'doctor' ) {
		$doc_id = 0;
	}
	if ( $tr_id && post_type_exists( 'treatment' ) && get_post_type( $tr_id ) !== 'treatment' ) {
		$tr_id = 0;
	}
	if ( $doc_id <= 0 || $tr_id <= 0 ) {
		return array( 'ok' => false, 'code' => 'invalid' );
	}

	$ml_guest_review_cap_cb = function ( $allcaps ) {
		$allcaps['edit_posts'] = true;
		return $allcaps;
	};
	add_filter( 'user_has_cap', $ml_guest_review_cap_cb, 999 );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'patient_review',
			'post_title'   => $name,
			'post_content' => $text,
			'post_status'  => 'pending',
		),
		true
	);

	remove_filter( 'user_has_cap', $ml_guest_review_cap_cb, 999 );

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return array( 'ok' => false, 'code' => 'error' );
	}

	update_post_meta( $post_id, 'review_rating', (string) $rating );
	update_post_meta( $post_id, 'review_doctor_id', (string) $doc_id );
	update_post_meta( $post_id, 'review_treatment_id', (string) $tr_id );
	update_post_meta( $post_id, 'review_privacy_agreed', '1' );
	if ( $email ) {
		update_post_meta( $post_id, 'review_reviewer_email', $email );
	}
	if ( $phone !== '' ) {
		update_post_meta( $post_id, 'review_reviewer_phone', $phone );
	}

	return array( 'ok' => true, 'post_id' => (int) $post_id );
}

/**
 * Тексты ответов формы отзыва (для AJAX JSON).
 *
 * @return array<string, string>
 */
function custom_manhattan_laser_get_patient_review_form_messages() {
	return array(
		'sent'    => __( 'Thank you. Your review has been submitted and will appear after moderation.', 'custom-manhattan-laser-theme' ),
		'invalid' => __( 'Please fill in all required fields: name, service, doctor, rating, comment (at least 10 characters), and try again.', 'custom-manhattan-laser-theme' ),
		'privacy' => __( 'Please accept the Privacy Policy to submit your review.', 'custom-manhattan-laser-theme' ),
		'error'   => __( 'Something went wrong. Please try again later.', 'custom-manhattan-laser-theme' ),
		'nonce'   => __( 'Something went wrong. Please try again later.', 'custom-manhattan-laser-theme' ),
	);
}

/**
 * AJAX: отправка формы отзыва с страницы testimonials.
 */
function custom_manhattan_laser_ajax_submit_patient_review() {
	$result = custom_manhattan_laser_process_patient_review_from_post();
	$msgs   = custom_manhattan_laser_get_patient_review_form_messages();

	if ( ! empty( $result['ok'] ) ) {
		wp_send_json(
			array(
				'success'      => true,
				'message'      => $msgs['sent'],
				'message_type' => 'success',
			)
		);
	}

	$code = isset( $result['code'] ) ? (string) $result['code'] : 'error';
	if ( 'spam' === $code ) {
		wp_send_json(
			array(
				'success' => false,
				'silent'  => true,
			)
		);
	}

	$key = 'error';
	if ( isset( $msgs[ $code ] ) ) {
		$key = $code;
	}
	$type = 'error';
	if ( 'privacy' === $code ) {
		$type = 'warning';
	}

	wp_send_json(
		array(
			'success'      => false,
			'code'         => $code,
			'message'      => $msgs[ $key ],
			'message_type' => $type,
		)
	);
}
add_action( 'wp_ajax_ml_submit_patient_review', 'custom_manhattan_laser_ajax_submit_patient_review' );
add_action( 'wp_ajax_nopriv_ml_submit_patient_review', 'custom_manhattan_laser_ajax_submit_patient_review' );

/**
 * Обработка формы «Оставить отзыв» (гость или авторизованный) — редирект без AJAX.
 */
function custom_manhattan_laser_handle_patient_review_form_submit() {
	$result   = custom_manhattan_laser_process_patient_review_from_post();
	$redirect = isset( $_POST['ml_review_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['ml_review_redirect'] ) ) : home_url( '/' );
	if ( '' === $redirect ) {
		$redirect = home_url( '/' );
	}

	if ( ! empty( $result['ok'] ) ) {
		wp_safe_redirect( add_query_arg( 'review', 'sent', $redirect ) );
		exit;
	}

	$code = isset( $result['code'] ) ? (string) $result['code'] : 'error';
	if ( 'spam' === $code ) {
		wp_safe_redirect( $redirect );
		exit;
	}

	$map = array(
		'nonce'   => 'error',
		'privacy' => 'privacy',
		'invalid' => 'invalid',
		'error'   => 'error',
	);
	$q = isset( $map[ $code ] ) ? $map[ $code ] : 'error';
	wp_safe_redirect( add_query_arg( 'review', $q, $redirect ) );
	exit;
}
add_action( 'admin_post_nopriv_submit_patient_review', 'custom_manhattan_laser_handle_patient_review_form_submit' );
add_action( 'admin_post_submit_patient_review', 'custom_manhattan_laser_handle_patient_review_form_submit' );

/**
 * CPT: заявки с формы записи на процедуру (сохраняются в админке).
 */
function custom_manhattan_laser_register_ml_booking_post_type() {
	$labels = array(
		'name'               => __( 'Appointment requests', 'custom-manhattan-laser-theme' ),
		'singular_name'      => __( 'Appointment request', 'custom-manhattan-laser-theme' ),
		'menu_name'          => __( 'Appointments', 'custom-manhattan-laser-theme' ),
		'add_new'            => __( 'Add New', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add request', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit request', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New request', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View request', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search requests', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No requests found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No requests found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	register_post_type(
		'ml_booking',
		array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'menu_icon'           => 'dashicons-calendar-alt',
			'menu_position'       => 26,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_ml_booking_post_type' );

/**
 * Meta полей заявки.
 */
function custom_manhattan_laser_register_ml_booking_meta() {
	$keys = array(
		'first_name',
		'last_name',
		'phone',
		'email',
		'city',
		'doctor',
		'service',
		'date',
		'time',
		'treatment_id',
		'privacy_agreed',
	);
	foreach ( $keys as $k ) {
		register_post_meta(
			'ml_booking',
			'ml_booking_' . $k,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => false,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'custom_manhattan_laser_register_ml_booking_meta' );

/**
 * Города для формы записи (value => label).
 *
 * @return array<string, string>
 */
function custom_manhattan_laser_get_booking_city_options() {
	return apply_filters(
		'custom_manhattan_laser_booking_city_options',
		array(
			''          => __( 'Choose your city', 'custom-manhattan-laser-theme' ),
			'new-york'  => __( 'New York, NY', 'custom-manhattan-laser-theme' ),
			'manhattan' => __( 'Manhattan', 'custom-manhattan-laser-theme' ),
		)
	);
}

/**
 * Врачи для формы записи (value => label). Ключ — ID записи CPT `doctor`, пустая строка — плейсхолдер.
 *
 * @return array<string, string>
 */
function custom_manhattan_laser_get_booking_doctor_options() {
	$options = array(
		'' => __( 'Choose a doctor', 'custom-manhattan-laser-theme' ),
	);
	if ( post_type_exists( 'doctor' ) ) {
		$doctors = get_posts(
			array(
				'post_type'      => 'doctor',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		foreach ( $doctors as $doc ) {
			$options[ (string) (int) $doc->ID ] = get_the_title( $doc );
		}
	}
	return apply_filters( 'custom_manhattan_laser_booking_doctor_options', $options );
}

/**
 * Обрабатывает заявку из $_POST (без редиректа).
 *
 * @return array{ ok: bool, code?: string, message?: string, post_id?: int }
 */
function custom_manhattan_laser_process_ml_booking_request() {
	if ( ! isset( $_POST['ml_booking_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ml_booking_nonce'] ) ), 'ml_booking_form' ) ) {
		return array(
			'ok'      => false,
			'code'    => 'nonce',
			'message' => __( 'Security check failed. Please refresh the page and try again.', 'custom-manhattan-laser-theme' ),
		);
	}

	// Honeypot — «успех» без сохранения.
	if ( ! empty( $_POST['ml_booking_website'] ) ) {
		return array( 'ok' => true, 'honeypot' => true );
	}

	if ( empty( $_POST['ml_booking_privacy'] ) ) {
		return array(
			'ok'      => false,
			'code'    => 'privacy',
			'message' => __( 'Please agree to the Privacy Policy to continue.', 'custom-manhattan-laser-theme' ),
		);
	}

	$first        = isset( $_POST['ml_booking_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_first_name'] ) ) : '';
	$last         = isset( $_POST['ml_booking_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_last_name'] ) ) : '';
	$phone        = isset( $_POST['ml_booking_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_phone'] ) ) : '';
	$email        = isset( $_POST['ml_booking_email'] ) ? sanitize_email( wp_unslash( $_POST['ml_booking_email'] ) ) : '';
	$city         = isset( $_POST['ml_booking_city'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_city'] ) ) : '';
	$doctor       = isset( $_POST['ml_booking_doctor'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_doctor'] ) ) : '';
	$service      = isset( $_POST['ml_booking_service'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_service'] ) ) : '';
	$date         = isset( $_POST['ml_booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_date'] ) ) : '';
	$time         = isset( $_POST['ml_booking_time'] ) ? sanitize_text_field( wp_unslash( $_POST['ml_booking_time'] ) ) : '';
	$treatment_id = isset( $_POST['ml_booking_treatment_id'] ) ? absint( $_POST['ml_booking_treatment_id'] ) : 0;

	$city_opts   = custom_manhattan_laser_get_booking_city_options();
	$doctor_opts = custom_manhattan_laser_get_booking_doctor_options();

	$generic_error = __( 'Something went wrong. Please check the form and try again.', 'custom-manhattan-laser-theme' );

	if ( '' === $first || '' === $last || '' === $email || ! is_email( $email ) ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}
	if ( '' === $city || ! isset( $city_opts[ $city ] ) ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}
	if ( '' === $doctor || ! isset( $doctor_opts[ $doctor ] ) ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}
	if ( '' === $service ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}
	if ( '' === $date || '' === $time ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}

	$service_post = get_post( absint( $service ) );
	if ( ! $service_post || 'treatment' !== $service_post->post_type ) {
		return array( 'ok' => false, 'code' => 'validation', 'message' => $generic_error );
	}

	$title = trim( $first . ' ' . $last );
	if ( strlen( $title ) > 180 ) {
		$title = substr( $title, 0, 177 ) . '...';
	}

	$new_id = wp_insert_post(
		array(
			'post_type'   => 'ml_booking',
			'post_status' => 'publish',
			'post_title'  => $title ? $title : __( 'Appointment request', 'custom-manhattan-laser-theme' ),
		),
		true
	);

	if ( is_wp_error( $new_id ) || ! $new_id ) {
		return array( 'ok' => false, 'code' => 'db', 'message' => $generic_error );
	}

	update_post_meta( $new_id, 'ml_booking_first_name', $first );
	update_post_meta( $new_id, 'ml_booking_last_name', $last );
	update_post_meta( $new_id, 'ml_booking_phone', $phone );
	update_post_meta( $new_id, 'ml_booking_email', $email );
	update_post_meta( $new_id, 'ml_booking_city', $city );
	update_post_meta( $new_id, 'ml_booking_doctor', $doctor );
	update_post_meta( $new_id, 'ml_booking_service', (string) $service_post->ID );
	update_post_meta( $new_id, 'ml_booking_date', $date );
	update_post_meta( $new_id, 'ml_booking_time', $time );
	update_post_meta( $new_id, 'ml_booking_treatment_id', $treatment_id ? (string) $treatment_id : '' );
	update_post_meta( $new_id, 'ml_booking_privacy_agreed', '1' );

	return array( 'ok' => true, 'post_id' => (int) $new_id );
}

/**
 * Обработка POST формы записи (редирект, без JS).
 */
function custom_manhattan_laser_handle_ml_booking_form() {
	$redirect = isset( $_POST['ml_booking_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['ml_booking_redirect'] ) ) : home_url( '/' );
	if ( '' === $redirect ) {
		$redirect = home_url( '/' );
	}

	$result = custom_manhattan_laser_process_ml_booking_request();
	if ( ! empty( $result['ok'] ) ) {
		wp_safe_redirect( add_query_arg( 'booking', 'sent', $redirect ) );
		exit;
	}

	$code = isset( $result['code'] ) ? $result['code'] : 'error';
	if ( 'privacy' === $code ) {
		wp_safe_redirect( add_query_arg( 'booking', 'privacy', $redirect ) );
	} else {
		wp_safe_redirect( add_query_arg( 'booking', 'error', $redirect ) );
	}
	exit;
}
add_action( 'admin_post_ml_booking_submit', 'custom_manhattan_laser_handle_ml_booking_form' );
add_action( 'admin_post_nopriv_ml_booking_submit', 'custom_manhattan_laser_handle_ml_booking_form' );

/**
 * AJAX: отправка формы записи.
 */
function custom_manhattan_laser_ajax_ml_booking() {
	$result = custom_manhattan_laser_process_ml_booking_request();

	if ( ! empty( $result['ok'] ) ) {
		wp_send_json_success(
			array(
				'message' => __( 'Thank you. Your appointment request has been received.', 'custom-manhattan-laser-theme' ),
			)
		);
	}

	$message = isset( $result['message'] ) ? $result['message'] : __( 'Something went wrong. Please check the form and try again.', 'custom-manhattan-laser-theme' );
	$code    = isset( $result['code'] ) ? (string) $result['code'] : 'error';
	wp_send_json_error(
		array(
			'message' => $message,
			'code'    => $code,
		)
	);
}
add_action( 'wp_ajax_ml_booking', 'custom_manhattan_laser_ajax_ml_booking' );
add_action( 'wp_ajax_nopriv_ml_booking', 'custom_manhattan_laser_ajax_ml_booking' );

/**
 * Колонки списка заявок в админке.
 *
 * @param array<string, string> $columns
 * @return array<string, string>
 */
function custom_manhattan_laser_ml_booking_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['ml_booking_email'] = __( 'Email', 'custom-manhattan-laser-theme' );
			$new['ml_booking_phone'] = __( 'Phone', 'custom-manhattan-laser-theme' );
			$new['ml_booking_when']  = __( 'Date / time', 'custom-manhattan-laser-theme' );
		}
	}
	return $new;
}
add_filter( 'manage_ml_booking_posts_columns', 'custom_manhattan_laser_ml_booking_columns' );

/**
 * @param string $column
 * @param int    $post_id
 */
function custom_manhattan_laser_ml_booking_column_content( $column, $post_id ) {
	if ( 'ml_booking_email' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, 'ml_booking_email', true ) );
		return;
	}
	if ( 'ml_booking_phone' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, 'ml_booking_phone', true ) );
		return;
	}
	if ( 'ml_booking_when' === $column ) {
		$d = (string) get_post_meta( $post_id, 'ml_booking_date', true );
		$t = (string) get_post_meta( $post_id, 'ml_booking_time', true );
		echo esc_html( trim( $d . ' ' . $t ) );
	}
}
add_action( 'manage_ml_booking_posts_custom_column', 'custom_manhattan_laser_ml_booking_column_content', 10, 2 );

/**
 * Метабокс: все поля заявки (только чтение).
 */
function custom_manhattan_laser_ml_booking_details_meta_box_cb( WP_Post $post ) {
	if ( 'ml_booking' !== $post->post_type ) {
		return;
	}
	$city_key   = (string) get_post_meta( $post->ID, 'ml_booking_city', true );
	$doctor_key = (string) get_post_meta( $post->ID, 'ml_booking_doctor', true );
	$svc_id     = (int) get_post_meta( $post->ID, 'ml_booking_service', true );
	$city_opts   = custom_manhattan_laser_get_booking_city_options();
	$doctor_opts = custom_manhattan_laser_get_booking_doctor_options();
	$city_label   = isset( $city_opts[ $city_key ] ) ? $city_opts[ $city_key ] : $city_key;
	if ( isset( $doctor_opts[ $doctor_key ] ) ) {
		$doctor_label = $doctor_opts[ $doctor_key ];
	} elseif ( $doctor_key !== '' && ctype_digit( $doctor_key ) ) {
		$dp = get_post( (int) $doctor_key );
		$doctor_label = $dp && 'doctor' === $dp->post_type ? get_the_title( $dp ) : $doctor_key;
	} else {
		$doctor_label = $doctor_key;
	}
	$service_title = $svc_id ? get_the_title( $svc_id ) : '';
	$rows = array(
		__( 'First name', 'custom-manhattan-laser-theme' )   => (string) get_post_meta( $post->ID, 'ml_booking_first_name', true ),
		__( 'Last name', 'custom-manhattan-laser-theme' )    => (string) get_post_meta( $post->ID, 'ml_booking_last_name', true ),
		__( 'Phone', 'custom-manhattan-laser-theme' )        => (string) get_post_meta( $post->ID, 'ml_booking_phone', true ),
		__( 'Email', 'custom-manhattan-laser-theme' )        => (string) get_post_meta( $post->ID, 'ml_booking_email', true ),
		__( 'City', 'custom-manhattan-laser-theme' )         => $city_label,
		__( 'Doctor', 'custom-manhattan-laser-theme' )       => $doctor_label,
		__( 'Service', 'custom-manhattan-laser-theme' )      => $service_title ? $service_title . ' (ID ' . $svc_id . ')' : (string) get_post_meta( $post->ID, 'ml_booking_service', true ),
		__( 'Preferred date', 'custom-manhattan-laser-theme' ) => (string) get_post_meta( $post->ID, 'ml_booking_date', true ),
		__( 'Preferred time', 'custom-manhattan-laser-theme' ) => (string) get_post_meta( $post->ID, 'ml_booking_time', true ),
		__( 'Page treatment ID', 'custom-manhattan-laser-theme' ) => (string) get_post_meta( $post->ID, 'ml_booking_treatment_id', true ),
		__( 'Privacy agreed', 'custom-manhattan-laser-theme' ) => (string) get_post_meta( $post->ID, 'ml_booking_privacy_agreed', true ),
	);
	echo '<table class="widefat striped"><tbody>';
	foreach ( $rows as $lab => $val ) {
		echo '<tr><th style="width:200px;text-align:left;vertical-align:top;">' . esc_html( $lab ) . '</th><td>' . esc_html( $val ) . '</td></tr>';
	}
	echo '</tbody></table>';
}

function custom_manhattan_laser_ml_booking_add_meta_boxes() {
	add_meta_box(
		'ml_booking_details',
		__( 'Request details', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_ml_booking_details_meta_box_cb',
		'ml_booking',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'custom_manhattan_laser_ml_booking_add_meta_boxes' );

/**
 * Отключить блок-редактор для заявок.
 *
 * @param bool   $use
 * @param string $post_type
 * @return bool
 */
function custom_manhattan_laser_ml_booking_disable_block_editor( $use, $post_type ) {
	return 'ml_booking' === $post_type ? false : $use;
}
add_filter( 'use_block_editor_for_post_type', 'custom_manhattan_laser_ml_booking_disable_block_editor', 10, 2 );

/**
 * Taxonomy: Treatment Category
 */
function custom_manhattan_laser_register_treatment_category() {
	$labels = array(
		'name'              => _x( 'Treatment Categories', 'taxonomy general name', 'custom-manhattan-laser-theme' ),
		'singular_name'     => _x( 'Treatment Category', 'taxonomy singular name', 'custom-manhattan-laser-theme' ),
		'search_items'      => __( 'Search Categories', 'custom-manhattan-laser-theme' ),
		'all_items'         => __( 'All Categories', 'custom-manhattan-laser-theme' ),
		'parent_item'       => __( 'Parent Category', 'custom-manhattan-laser-theme' ),
		'parent_item_colon' => __( 'Parent Category:', 'custom-manhattan-laser-theme' ),
		'edit_item'         => __( 'Edit Category', 'custom-manhattan-laser-theme' ),
		'update_item'       => __( 'Update Category', 'custom-manhattan-laser-theme' ),
		'add_new_item'      => __( 'Add New Category', 'custom-manhattan-laser-theme' ),
		'new_item_name'     => __( 'New Category Name', 'custom-manhattan-laser-theme' ),
		'menu_name'         => __( 'Category', 'custom-manhattan-laser-theme' ),
	);
	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'treatment-category' ),
	);
	register_taxonomy( 'treatment_category', array( 'treatment' ), $args );
}
add_action( 'init', 'custom_manhattan_laser_register_treatment_category' );

/**
 * ЧПУ категорий процедур: /treatments/category/{slug}/
 * После активации темы зайдите в Настройки → Постоянные ссылки и нажмите «Сохранить».
 */
function custom_manhattan_laser_treatment_category_rewrite() {
	add_rewrite_rule(
		'^treatments/category/([^/]+)/?$',
		'index.php?treatment_category=$matches[1]',
		'top'
	);
}
add_action( 'init', 'custom_manhattan_laser_treatment_category_rewrite', 20 );

/**
 * @param string $url
 * @param WP_Term|int $term
 * @param string $taxonomy
 * @return string
 */
function custom_manhattan_laser_treatment_category_term_link( $url, $term, $taxonomy ) {
	if ( 'treatment_category' !== $taxonomy ) {
		return $url;
	}
	$t = is_object( $term ) ? $term : get_term( $term, $taxonomy );
	if ( ! $t || is_wp_error( $t ) ) {
		return $url;
	}
	return trailingslashit( home_url( 'treatments/category/' . $t->slug ) );
}
add_filter( 'term_link', 'custom_manhattan_laser_treatment_category_term_link', 10, 3 );

/**
 * Группы для меню процедур: категории + процедуры внутри.
 *
 * @return array<int, array{label: string, url: string, items: array<int, array{title: string, url: string}>}>
 */
function custom_manhattan_laser_get_treatment_menu_groups() {
	if ( ! taxonomy_exists( 'treatment_category' ) || ! post_type_exists( 'treatment' ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'treatment_category',
			'hide_empty' => false,
			'parent'     => 0,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}
	$groups = array();
	foreach ( $terms as $term ) {
		$tlink = get_term_link( $term );
		if ( is_wp_error( $tlink ) ) {
			continue;
		}
		$posts = get_posts(
			array(
				'post_type'      => 'treatment',
				'posts_per_page' => 50,
				'tax_query'      => array(
					array(
						'taxonomy'         => 'treatment_category',
						'field'            => 'term_id',
						'terms'            => $term->term_id,
						'include_children' => true,
					),
				),
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
		$items = array();
		foreach ( $posts as $p ) {
			$items[] = array(
				'title' => get_the_title( $p ),
				'url'   => get_permalink( $p ),
			);
		}
		$groups[] = array(
			'label' => $term->name,
			'url'   => $tlink,
			'items' => $items,
		);
	}
	return $groups;
}

add_action(
	'after_switch_theme',
	function () {
		custom_manhattan_laser_treatment_category_rewrite();
		flush_rewrite_rules( false );
	}
);

/**
 * Treatment Category meta:
 * - hero image
 * - short description
 */
function custom_manhattan_laser_register_treatment_category_meta() {
	register_term_meta(
		'treatment_category',
		'treatment_category_hero_image_id',
		array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_short_description',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_category_png_image_id',
		array(
			'type'         => 'integer',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_treatments_block_title',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_science_title',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_science_intro',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		register_term_meta(
			'treatment_category',
			'treatment_category_science_col_' . $i . '_title',
			array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_term_meta(
			'treatment_category',
			'treatment_category_science_col_' . $i . '_desc',
			array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => true,
			)
		);
	}

	register_term_meta(
		'treatment_category',
		'treatment_category_faq_heading',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_faq_intro',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);

	register_term_meta(
		'treatment_category',
		'treatment_category_faq_items_json',
		array(
			'type'         => 'string',
			'single'       => true,
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_treatment_category_meta' );

/**
 * Дефолтные пункты FAQ для страницы категории процедур.
 *
 * @return array<int, array{q: string, a: string}>
 */
function custom_manhattan_laser_get_default_treatment_category_faq_items() {
	return array(
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
		),
	);
}

/**
 * Санитизация JSON FAQ категории (вопрос + ответ).
 *
 * @param mixed $raw
 * @return string|null
 */
function custom_manhattan_laser_sanitize_treatment_category_faq_items_json( $raw ) {
	$str     = is_string( $raw ) ? wp_unslash( $raw ) : '';
	$decoded = json_decode( $str, true );
	if ( ! is_array( $decoded ) || ! isset( $decoded['items'] ) || ! is_array( $decoded['items'] ) ) {
		return null;
	}
	$items = array();
	$max   = 50;
	$count = 0;
	foreach ( $decoded['items'] as $row ) {
		if ( $count >= $max ) {
			break;
		}
		if ( ! is_array( $row ) ) {
			continue;
		}
		$q = isset( $row['q'] ) ? $row['q'] : '';
		$a = isset( $row['a'] ) ? $row['a'] : '';
		$items[] = array(
			'q' => sanitize_text_field( is_string( $q ) ? $q : (string) $q ),
			'a' => sanitize_textarea_field( is_string( $a ) ? $a : (string) $a ),
		);
		++$count;
	}
	return wp_json_encode( array( 'items' => $items ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
}

/**
 * FAQ блока категории для шаблона и schema.org.
 *
 * @param int $term_id
 * @return array{heading: string, intro: string, items: array<int, array{q: string, a: string}>}
 */
function custom_manhattan_laser_get_treatment_category_faq_for_display( $term_id ) {
	$default_items = custom_manhattan_laser_get_default_treatment_category_faq_items();
	$heading       = __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' );
	$intro         = __( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' );
	$items         = $default_items;

	if ( $term_id ) {
		$h_meta = (string) get_term_meta( $term_id, 'treatment_category_faq_heading', true );
		if ( '' !== trim( $h_meta ) ) {
			$heading = $h_meta;
		}
		$i_meta = (string) get_term_meta( $term_id, 'treatment_category_faq_intro', true );
		if ( '' !== trim( $i_meta ) ) {
			$intro = $i_meta;
		}

		$raw = get_term_meta( $term_id, 'treatment_category_faq_items_json', true );
		if ( is_string( $raw ) && '' !== trim( $raw ) ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) && isset( $decoded['items'] ) && is_array( $decoded['items'] ) ) {
				$non_empty = array();
				foreach ( $decoded['items'] as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					$q = isset( $row['q'] ) ? trim( (string) $row['q'] ) : '';
					$a = isset( $row['a'] ) ? trim( (string) $row['a'] ) : '';
					if ( '' !== $q && '' !== $a ) {
						$non_empty[] = array(
							'q' => $q,
							'a' => $a,
						);
					}
				}
				if ( ! empty( $non_empty ) ) {
					$items = $non_empty;
				} else {
					// В админке сохранён пустой список FAQ — не подставлять дефолты сайта.
					return array(
						'heading' => '',
						'intro'   => '',
						'items'   => array(),
					);
				}
			}
		}
	}

	return array(
		'heading' => $heading,
		'intro'   => $intro,
		'items'   => $items,
	);
}

/**
 * FAQ для single treatment: свои Q&A в посте; иначе — как у категории; иначе дефолт.
 *
 * @param int $post_id
 * @param int $category_term_id ID термина treatment_category (первый термин процедуры).
 * @return array{heading: string, intro: string, items: array<int, array{q: string, a: string}>}
 */
function custom_manhattan_laser_get_treatment_faq_for_display( $post_id, $category_term_id = 0 ) {
	$default_heading = __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' );
	$default_intro   = __( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' );
	$default_items   = custom_manhattan_laser_get_default_treatment_category_faq_items();

	if ( $post_id ) {
		$raw = get_post_meta( $post_id, 'treatment_faq_items_json', true );
		if ( is_string( $raw ) && '' !== trim( $raw ) ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) && isset( $decoded['items'] ) && is_array( $decoded['items'] ) ) {
				$non_empty = array();
				foreach ( $decoded['items'] as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					$q = isset( $row['q'] ) ? trim( (string) $row['q'] ) : '';
					$a = isset( $row['a'] ) ? trim( (string) $row['a'] ) : '';
					if ( '' !== $q && '' !== $a ) {
						$non_empty[] = array(
							'q' => $q,
							'a' => $a,
						);
					}
				}
				if ( ! empty( $non_empty ) ) {
					$heading = $default_heading;
					$intro   = $default_intro;
					$h_meta  = (string) get_post_meta( $post_id, 'treatment_faq_heading', true );
					if ( '' !== trim( $h_meta ) ) {
						$heading = $h_meta;
					}
					$i_meta = (string) get_post_meta( $post_id, 'treatment_faq_intro', true );
					if ( '' !== trim( $i_meta ) ) {
						$intro = $i_meta;
					}
					return array(
						'heading' => $heading,
						'intro'   => $intro,
						'items'   => $non_empty,
					);
				}

				// Сохранён JSON FAQ у процедуры, но без ни одной полной пары Q+A — блок на странице не показываем (без отката к категории).
				return array(
					'heading' => '',
					'intro'   => '',
					'items'   => array(),
				);
			}
		}
	}

	if ( $category_term_id ) {
		return custom_manhattan_laser_get_treatment_category_faq_for_display( $category_term_id );
	}

	return array(
		'heading' => $default_heading,
		'intro'   => $default_intro,
		'items'   => $default_items,
	);
}

function custom_manhattan_laser_treatment_category_meta_fields( $term ) {
	$term_id = is_object( $term ) && isset( $term->term_id ) ? (int) $term->term_id : 0;

	$hero_id       = $term_id ? (int) get_term_meta( $term_id, 'treatment_category_hero_image_id', true ) : 0;
	$hero_img_url  = $hero_id ? wp_get_attachment_image_url( $hero_id, 'large' ) : '';
	$short_desc    = $term_id ? get_term_meta( $term_id, 'treatment_category_short_description', true ) : '';
	$category_png_id      = $term_id ? (int) get_term_meta( $term_id, 'treatment_category_category_png_image_id', true ) : 0;
	$category_png_img_url = $category_png_id ? wp_get_attachment_image_url( $category_png_id, 'large' ) : '';
	$science_title = $term_id ? get_term_meta( $term_id, 'treatment_category_science_title', true ) : '';
	$science_intro = $term_id ? get_term_meta( $term_id, 'treatment_category_science_intro', true ) : '';
	$cols          = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$cols[ $i ] = array(
			'title' => $term_id ? get_term_meta( $term_id, 'treatment_category_science_col_' . $i . '_title', true ) : '',
			'desc'  => $term_id ? get_term_meta( $term_id, 'treatment_category_science_col_' . $i . '_desc', true ) : '',
		);
	}
	$nonce_action  = 'treatment_category_meta_nonce_action';
	$nonce          = wp_create_nonce( $nonce_action );

	?>
	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_hero_image_id"><?php esc_html_e( 'Category hero image', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<input type="hidden" id="treatment_category_hero_image_id" name="treatment_category_hero_image_id" value="<?php echo esc_attr( $hero_id ); ?>">
			<input type="hidden" name="treatment_category_meta_nonce" value="<?php echo esc_attr( $nonce ); ?>">

			<div class="flex flex-col gap-3">
				<div class="flex gap-3 items-center">
					<button type="button" class="button term-hero-image__select" data-target="treatment_category_hero_image_id" data-preview="treatment_category_hero_image_preview">
						<?php esc_html_e( 'Select image', 'custom-manhattan-laser-theme' ); ?>
					</button>
					<button type="button" class="button term-hero-image__clear" data-target="treatment_category_hero_image_id" data-preview="treatment_category_hero_image_preview">
						<?php esc_html_e( 'Remove', 'custom-manhattan-laser-theme' ); ?>
					</button>
				</div>
				<div id="treatment_category_hero_image_preview">
					<?php if ( $hero_img_url ) : ?>
						<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="" style="max-width:260px;height:auto;display:block;">
					<?php endif; ?>
				</div>
			</div>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_short_description"><?php esc_html_e( 'Short description', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<textarea id="treatment_category_short_description" name="treatment_category_short_description" rows="4" class="widefat"><?php echo esc_textarea( (string) $short_desc ); ?></textarea>
		</td>
	</tr>

	<?php
	$treatments_block_title = $term_id ? get_term_meta( $term_id, 'treatment_category_treatments_block_title', true ) : '';
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_treatments_block_title"><?php esc_html_e( 'Treatments block title', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<input type="text" id="treatment_category_treatments_block_title" name="treatment_category_treatments_block_title" value="<?php echo esc_attr( (string) $treatments_block_title ); ?>" class="widefat">
			<p class="description"><?php esc_html_e( 'Heading above the list of procedures (e.g. Advanced Body Sculpting).', 'custom-manhattan-laser-theme' ); ?></p>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_category_png_image_id"><?php esc_html_e( 'category.png (block image)', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<input type="hidden" id="treatment_category_category_png_image_id" name="treatment_category_category_png_image_id" value="<?php echo esc_attr( $category_png_id ); ?>">
			<input type="hidden" name="treatment_category_meta_nonce" value="<?php echo esc_attr( $nonce ); ?>">

			<div class="flex flex-col gap-3">
				<div class="flex gap-3 items-center">
					<button type="button" class="button term-hero-image__select" data-target="treatment_category_category_png_image_id" data-preview="treatment_category_category_png_image_preview">
						<?php esc_html_e( 'Select image', 'custom-manhattan-laser-theme' ); ?>
					</button>
					<button type="button" class="button term-hero-image__clear" data-target="treatment_category_category_png_image_id" data-preview="treatment_category_category_png_image_preview">
						<?php esc_html_e( 'Remove', 'custom-manhattan-laser-theme' ); ?>
					</button>
				</div>
				<div id="treatment_category_category_png_image_preview">
					<?php if ( $category_png_img_url ) : ?>
						<img src="<?php echo esc_url( $category_png_img_url ); ?>" alt="" style="max-width:260px;height:auto;display:block;">
					<?php endif; ?>
				</div>
			</div>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_science_title"><?php esc_html_e( 'Science section title', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<input type="text" id="treatment_category_science_title" name="treatment_category_science_title" value="<?php echo esc_attr( (string) $science_title ); ?>" class="widefat">
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_science_intro"><?php esc_html_e( 'Science section intro (right text)', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<textarea id="treatment_category_science_intro" name="treatment_category_science_intro" rows="4" class="widefat"><?php echo esc_textarea( (string) $science_intro ); ?></textarea>
		</td>
	</tr>

	<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_science_col_<?php echo (int) $i; ?>_title"><?php echo esc_html( sprintf( 'Column %d title', $i ) ); ?></label>
		</th>
		<td>
			<input type="text" id="treatment_category_science_col_<?php echo (int) $i; ?>_title" name="treatment_category_science_col_<?php echo (int) $i; ?>_title" value="<?php echo esc_attr( (string) $cols[ $i ]['title'] ); ?>" class="widefat">
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_science_col_<?php echo (int) $i; ?>_desc"><?php echo esc_html( sprintf( 'Column %d description', $i ) ); ?></label>
		</th>
		<td>
			<textarea id="treatment_category_science_col_<?php echo (int) $i; ?>_desc" name="treatment_category_science_col_<?php echo (int) $i; ?>_desc" rows="3" class="widefat"><?php echo esc_textarea( (string) $cols[ $i ]['desc'] ); ?></textarea>
		</td>
	</tr>
	<?php endfor; ?>
	<?php
	$faq_heading_meta = $term_id ? (string) get_term_meta( $term_id, 'treatment_category_faq_heading', true ) : '';
	$faq_intro_meta   = $term_id ? (string) get_term_meta( $term_id, 'treatment_category_faq_intro', true ) : '';
	$faq_json_admin   = $term_id ? get_term_meta( $term_id, 'treatment_category_faq_items_json', true ) : '';
	if ( ! is_string( $faq_json_admin ) || '' === trim( $faq_json_admin ) ) {
		$faq_json_admin = wp_json_encode(
			array( 'items' => custom_manhattan_laser_get_default_treatment_category_faq_items() ),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}
	$faq_default_js = wp_json_encode(
		array( 'items' => custom_manhattan_laser_get_default_treatment_category_faq_items() ),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_faq_heading"><?php esc_html_e( 'FAQ section heading', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<input type="text" id="treatment_category_faq_heading" name="treatment_category_faq_heading" value="<?php echo esc_attr( $faq_heading_meta ); ?>" class="widefat" placeholder="<?php echo esc_attr( __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' ) ); ?>">
			<p class="description"><?php esc_html_e( 'Optional. Empty = default heading on the site.', 'custom-manhattan-laser-theme' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row">
			<label for="treatment_category_faq_intro"><?php esc_html_e( 'FAQ intro text (under heading)', 'custom-manhattan-laser-theme' ); ?></label>
		</th>
		<td>
			<textarea id="treatment_category_faq_intro" name="treatment_category_faq_intro" rows="3" class="widefat" placeholder="<?php echo esc_attr( __( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' ) ); ?>"><?php echo esc_textarea( $faq_intro_meta ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Optional. Empty = default intro.', 'custom-manhattan-laser-theme' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<?php esc_html_e( 'FAQ questions & answers', 'custom-manhattan-laser-theme' ); ?>
		</th>
		<td>
			<p class="description"><?php esc_html_e( 'Shown in the FAQ section on this category archive. JSON-LD FAQPage uses the same text for Google. Remove all rows and save to hide the FAQ block for this category.', 'custom-manhattan-laser-theme' ); ?></p>
			<p style="margin:10px 0;">
				<button type="button" class="button" id="treatment-category-faq-add-row"><?php esc_html_e( 'Add FAQ item', 'custom-manhattan-laser-theme' ); ?></button>
				<button type="button" class="button" id="treatment-category-faq-remove-row"><?php esc_html_e( 'Remove last item', 'custom-manhattan-laser-theme' ); ?></button>
			</p>
			<div style="overflow-x:auto;margin-bottom:10px;">
				<table class="widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Question', 'custom-manhattan-laser-theme' ); ?></th>
							<th><?php esc_html_e( 'Answer', 'custom-manhattan-laser-theme' ); ?></th>
						</tr>
					</thead>
					<tbody id="treatment-category-faq-tbody"></tbody>
				</table>
			</div>
			<textarea name="treatment_category_faq_items_json" id="treatment_category_faq_items_json" style="display:none;width:100%;" rows="4"><?php echo esc_textarea( $faq_json_admin ); ?></textarea>
			<script>
			(function(){
				var defaultData = <?php echo $faq_default_js; ?>;
				var input = document.getElementById('treatment_category_faq_items_json');
				if (!input) return;
				var state = { items: [] };
				function loadState() {
					try {
						var v = input.value.trim();
						if (v) {
							var o = JSON.parse(v);
							if (o && Array.isArray(o.items)) {
								state.items = o.items.map(function(it) {
									return {
										q: it && it.q != null ? String(it.q) : '',
										a: it && it.a != null ? String(it.a) : ''
									};
								});
								return;
							}
						}
					} catch (e) {}
					state = JSON.parse(JSON.stringify(defaultData));
					if (!state.items || !state.items.length) state.items = [{ q: '', a: '' }];
				}
				function save() {
					input.value = JSON.stringify({ items: state.items });
				}
				function render() {
					var tbody = document.getElementById('treatment-category-faq-tbody');
					if (!tbody) return;
					tbody.innerHTML = '';
					state.items.forEach(function(row, ri) {
						var tr = document.createElement('tr');
						['q','a'].forEach(function(field) {
							var td = document.createElement('td');
							var el = document.createElement(field === 'a' ? 'textarea' : 'input');
							if (field === 'q') { el.type = 'text'; el.className = 'widefat'; }
							else { el.className = 'widefat'; el.rows = 3; }
							el.value = row[field] != null ? row[field] : '';
							(function(r, f) {
								el.addEventListener('input', function() {
									state.items[r][f] = el.value;
									save();
								});
							})(ri, field);
							td.appendChild(el);
							tr.appendChild(td);
						});
						tbody.appendChild(tr);
					});
				}
				document.getElementById('treatment-category-faq-add-row') && document.getElementById('treatment-category-faq-add-row').addEventListener('click', function() {
					state.items.push({ q: '', a: '' });
					save();
					render();
				});
				document.getElementById('treatment-category-faq-remove-row') && document.getElementById('treatment-category-faq-remove-row').addEventListener('click', function() {
					if (state.items.length <= 0) return;
					state.items.pop();
					save();
					render();
				});
				loadState();
				save();
				render();
			})();
			</script>
		</td>
	</tr>
	<?php
}

add_action(
	'treatment_category_edit_form_fields',
	function ( $term ) {
		custom_manhattan_laser_treatment_category_meta_fields( $term );
	}
);
add_action(
	'treatment_category_add_form_fields',
	function () {
		// Для добавления: показываем пустые значения.
		custom_manhattan_laser_treatment_category_meta_fields( (object) array( 'term_id' => 0 ) );
	}
);

function custom_manhattan_laser_treatment_category_admin_scripts() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || empty( $screen->taxonomy ) || 'treatment_category' !== $screen->taxonomy ) {
		return;
	}

	wp_enqueue_media();

	wp_add_inline_script(
		'jquery',
		"
		jQuery(function($){
			var frame;
			$('body').on('click', '.term-hero-image__select', function(e){
				e.preventDefault();
				var \$btn = $(this);
				var targetId = \$btn.data('target');
				var previewId = \$btn.data('preview');
				if (frame) { frame.close(); }
				frame = wp.media({ title: 'Select image', button: { text: 'Use image' }, multiple: false });
				frame.on('select', function(){
					var attachment = frame.state().get('selection').first().toJSON();
					if (attachment && attachment.id) {
						$('#' + targetId).val(attachment.id);
					}
					if (attachment && attachment.url) {
						$('#' + previewId).html('<img src=\"' + attachment.url + '\" alt=\"\" style=\"max-width:260px;height:auto;display:block;\">');
					}
				});
				frame.open();
			});

			$('body').on('click', '.term-hero-image__clear', function(e){
				e.preventDefault();
				var \$btn = $(this);
				var targetId = \$btn.data('target');
				var previewId = \$btn.data('preview');
				$('#' + targetId).val('');
				$('#' + previewId).html('');
			});
		});
		"
	);
}
add_action( 'admin_enqueue_scripts', 'custom_manhattan_laser_treatment_category_admin_scripts' );

function custom_manhattan_laser_save_treatment_category_meta( $term_id ) {
	if ( ! isset( $_POST['treatment_category_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['treatment_category_meta_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'treatment_category_meta_nonce_action' ) ) {
		return;
	}

	if ( isset( $_POST['treatment_category_hero_image_id'] ) ) {
		$hero_id = (int) $_POST['treatment_category_hero_image_id'];
		update_term_meta( $term_id, 'treatment_category_hero_image_id', $hero_id ? $hero_id : '' );
	}

	if ( isset( $_POST['treatment_category_short_description'] ) ) {
		$desc = sanitize_textarea_field( wp_unslash( $_POST['treatment_category_short_description'] ) );
		update_term_meta( $term_id, 'treatment_category_short_description', $desc );
	}

	if ( isset( $_POST['treatment_category_treatments_block_title'] ) ) {
		$title = sanitize_text_field( wp_unslash( $_POST['treatment_category_treatments_block_title'] ) );
		update_term_meta( $term_id, 'treatment_category_treatments_block_title', $title );
	}

	if ( isset( $_POST['treatment_category_category_png_image_id'] ) ) {
		$png_id = (int) $_POST['treatment_category_category_png_image_id'];
		update_term_meta( $term_id, 'treatment_category_category_png_image_id', $png_id ? $png_id : '' );
	}

	if ( isset( $_POST['treatment_category_science_title'] ) ) {
		update_term_meta(
			$term_id,
			'treatment_category_science_title',
			sanitize_text_field( wp_unslash( $_POST['treatment_category_science_title'] ) )
		);
	}

	if ( isset( $_POST['treatment_category_science_intro'] ) ) {
		update_term_meta(
			$term_id,
			'treatment_category_science_intro',
			sanitize_textarea_field( wp_unslash( $_POST['treatment_category_science_intro'] ) )
		);
	}

	for ( $i = 1; $i <= 4; $i++ ) {
		$key_title = 'treatment_category_science_col_' . $i . '_title';
		$key_desc  = 'treatment_category_science_col_' . $i . '_desc';

		if ( isset( $_POST[ $key_title ] ) ) {
			update_term_meta(
				$term_id,
				$key_title,
				sanitize_text_field( wp_unslash( $_POST[ $key_title ] ) )
			);
		}
		if ( isset( $_POST[ $key_desc ] ) ) {
			update_term_meta(
				$term_id,
				$key_desc,
				sanitize_textarea_field( wp_unslash( $_POST[ $key_desc ] ) )
			);
		}
	}

	if ( isset( $_POST['treatment_category_faq_heading'] ) ) {
		update_term_meta(
			$term_id,
			'treatment_category_faq_heading',
			sanitize_text_field( wp_unslash( $_POST['treatment_category_faq_heading'] ) )
		);
	}

	if ( isset( $_POST['treatment_category_faq_intro'] ) ) {
		update_term_meta(
			$term_id,
			'treatment_category_faq_intro',
			sanitize_textarea_field( wp_unslash( $_POST['treatment_category_faq_intro'] ) )
		);
	}

	if ( isset( $_POST['treatment_category_faq_items_json'] ) ) {
		$san_faq = custom_manhattan_laser_sanitize_treatment_category_faq_items_json( wp_unslash( $_POST['treatment_category_faq_items_json'] ) );
		if ( null !== $san_faq ) {
			update_term_meta( $term_id, 'treatment_category_faq_items_json', $san_faq );
		}
	}
}
add_action( 'edited_treatment_category', 'custom_manhattan_laser_save_treatment_category_meta' );
add_action( 'create_treatment_category', 'custom_manhattan_laser_save_treatment_category_meta' );

/**
 * Breadcrumbs (SEO): schema.org BreadcrumbList
 *
 * Reusable: other templates can prepare $items = [['name'=>..., 'url'=>...], ...]
 * and call custom_manhattan_laser_render_breadcrumb_schema( $items ).
 *
 * @param array<int, array{name: string, url: string}> $items
 * @return void
 */
function custom_manhattan_laser_render_breadcrumb_schema( array $items ) {
	$items = array_values(
		array_filter(
			$items,
			function ( $it ) {
				return ! empty( $it['name'] ) && ! empty( $it['url'] );
			}
		)
	);
	if ( empty( $items ) ) {
		return;
	}

	$list_items = array();
	$pos        = 1;
	foreach ( $items as $it ) {
		$list_items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => (string) $it['name'],
			'item'     => (string) $it['url'],
		);
		$pos ++;
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $list_items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}

/**
 * Breadcrumbs HTML (visual) renderer.
 * Reusable: pass items = [['name' => 'Home', 'url' => 'https://...'], ...]
 *
 * Notes:
 * - For the last item it renders <li>Text</li> (no link), matching existing markup.
 *
 * @param array<int, array{name: string, url: string}> $items
 * @return void
 */
function custom_manhattan_laser_render_breadcrumbs_html( array $items ) {
	$items = array_values(
		array_filter(
			$items,
			function ( $it ) {
				return ! empty( $it['name'] );
			}
		)
	);

	if ( empty( $items ) ) {
		return;
	}

	echo '<ul class="flex gap-1.5 items-center text-[12px] md:text-[16px]">';
	$count = count( $items );

	for ( $i = 0; $i < $count; $i++ ) {
		$it    = $items[ $i ];
		$name  = isset( $it['name'] ) ? (string) $it['name'] : '';
		$url   = isset( $it['url'] ) ? (string) $it['url'] : '';
		$is_last = $i === $count - 1;

		if ( ! $is_last ) {
			echo '<li class="text-[#F4EFE8]/50 hover:text-[#F4EFE8] transition-colors duration-300 ease-out"><a href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a></li>';
		} else {
			echo '<li>' . esc_html( $name ) . '</li>';
		}

		if ( ! $is_last ) {
			echo '<li class="text-[#F4EFE8]/50">/</li>';
		}
	}

	echo '</ul>';
}

/**
 * Базовый publisher для schema.org графов темы.
 *
 * @return array<string, mixed>
 */
function custom_manhattan_laser_get_schema_publisher() {
	$publisher = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	$logo_url = '';
	$site_icon_id = (int) get_option( 'site_icon' );
	if ( $site_icon_id > 0 ) {
		$logo_url = (string) wp_get_attachment_image_url( $site_icon_id, 'full' );
	}
	if ( '' === $logo_url && function_exists( 'get_custom_logo' ) ) {
		$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id > 0 ) {
			$logo_url = (string) wp_get_attachment_image_url( $custom_logo_id, 'full' );
		}
	}
	if ( '' !== $logo_url ) {
		$publisher['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		);
	}

	return $publisher;
}

/**
 * Единая печать JSON-LD для шаблонов темы.
 *
 * @param array<string, mixed> $schema Graph root.
 * @return void
 */
function custom_manhattan_laser_render_json_ld( array $schema ) {
	if ( empty( $schema ) ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}

/**
 * URLs из поля doctor_same_as (по одной в строке) для schema.org sameAs.
 *
 * @param string $raw Raw textarea.
 * @return string[]
 */
function custom_manhattan_laser_parse_doctor_same_as_urls( $raw ) {
	$raw = is_string( $raw ) ? $raw : '';
	$lines = preg_split( "/\r\n|\n|\r/", $raw );
	if ( ! is_array( $lines ) ) {
		return array();
	}
	$out = array();
	foreach ( $lines as $line ) {
		$u = esc_url_raw( trim( (string) $line ) );
		if ( '' !== $u ) {
			$out[] = $u;
		}
	}
	return array_values( array_unique( $out ) );
}

/**
 * Данные выбранного врача-автора процедуры для шаблона (только опубликованные записи doctor).
 *
 * @param int $treatment_post_id ID записи treatment.
 * @return array{id: int, name: string, url: string, role: string, avatar_url: string}|null
 */
function custom_manhattan_laser_get_treatment_author_doctor_for_display( $treatment_post_id ) {
	$treatment_post_id = (int) $treatment_post_id;
	if ( $treatment_post_id < 1 ) {
		return null;
	}
	$doctor_id = (int) get_post_meta( $treatment_post_id, 'treatment_author_doctor_id', true );
	if ( $doctor_id < 1 ) {
		return null;
	}
	$doc = get_post( $doctor_id );
	if ( ! $doc instanceof WP_Post || 'doctor' !== $doc->post_type || 'publish' !== $doc->post_status ) {
		return null;
	}
	$role = (string) get_post_meta( $doctor_id, 'doctor_role', true );
	if ( '' === trim( $role ) ) {
		$role = (string) get_post_meta( $doctor_id, 'doctor_hero_kicker', true );
	}
	$avatar = (string) get_the_post_thumbnail_url( $doctor_id, 'medium' );
	if ( '' === $avatar ) {
		$avatar = (string) get_the_post_thumbnail_url( $doctor_id, 'thumbnail' );
	}
	return array(
		'id'         => $doctor_id,
		'name'       => get_the_title( $doctor_id ),
		'url'        => (string) get_permalink( $doctor_id ),
		'role'       => trim( $role ),
		'avatar_url' => $avatar,
	);
}

/**
 * Schema.org: treatment single page business/person schemas.
 *
 * @param int        $post_id Treatment post ID.
 * @param WP_Term|null $term Treatment category term (optional, for specialty).
 * @param string[]   $gallery_image_urls Optional gallery images.
 * @return void
 */
function custom_manhattan_laser_render_treatment_medicalbusiness_physician_schema( $post_id, $term = null, array $gallery_image_urls = array() ) {
	$post_id = (int) $post_id;
	if ( $post_id < 1 ) {
		return;
	}

	$business_name = (string) get_bloginfo( 'name' );
	$business_url  = (string) home_url( '/' );

	$medical_specialty = '';
	if ( $term instanceof WP_Term ) {
		$medical_specialty = (string) $term->name;
	}

	$gallery_image_urls = array_values(
		array_unique(
			array_values(
				array_filter(
					array_map(
						static function ( $u ) {
							$u = is_string( $u ) ? trim( $u ) : '';
							return '' !== $u ? $u : '';
						},
						$gallery_image_urls
					)
				)
			)
		)
	);

	$image_objects = array();
	foreach ( array_slice( $gallery_image_urls, 0, 12 ) as $img_url ) {
		if ( '' === $img_url ) {
			continue;
		}
		$image_objects[] = array(
			'@type' => 'ImageObject',
			'url'   => (string) $img_url,
		);
	}

	$medical_business = array(
		'@context' => 'https://schema.org',
		'@type'    => 'MedicalBusiness',
		'name'     => $business_name,
		'url'      => $business_url,
	);
	if ( '' !== $medical_specialty ) {
		$medical_business['medicalSpecialty'] = $medical_specialty;
	}
	if ( ! empty( $image_objects ) ) {
		$medical_business['image'] = $image_objects;
	}

	$doctor_author_id = (int) get_post_meta( $post_id, 'treatment_author_doctor_id', true );
	$doctor_post      = ( $doctor_author_id > 0 ) ? get_post( $doctor_author_id ) : null;
	$use_doctor       = $doctor_post instanceof WP_Post && 'doctor' === $doctor_post->post_type && 'publish' === $doctor_post->post_status;

	if ( $use_doctor ) {
		$physician_name = (string) get_the_title( $doctor_author_id );
		$physician_url  = (string) get_permalink( $doctor_author_id );
		$job_title      = trim( (string) get_post_meta( $doctor_author_id, 'doctor_role', true ) );
		if ( '' === $job_title ) {
			$job_title = trim( (string) get_post_meta( $doctor_author_id, 'doctor_hero_kicker', true ) );
		}
		$physician_img = (string) get_the_post_thumbnail_url( $doctor_author_id, 'large' );
		if ( '' === $physician_img ) {
			$physician_img = (string) get_the_post_thumbnail_url( $doctor_author_id, 'medium' );
		}
		$same_as = custom_manhattan_laser_parse_doctor_same_as_urls( (string) get_post_meta( $doctor_author_id, 'doctor_same_as', true ) );
	} else {
		$author_id      = (int) get_post_field( 'post_author', $post_id );
		$physician_name = $author_id > 0 ? (string) get_the_author_meta( 'display_name', $author_id ) : '';
		if ( '' === $physician_name ) {
			$physician_name = $business_name;
		}
		$physician_url = $business_url;
		$job_title     = '';
		$physician_img = '';
		$same_as       = array();
	}

	$physician = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Physician',
		'name'     => $physician_name,
		'url'      => $physician_url,
		'worksFor' => array(
			'@type' => 'MedicalBusiness',
			'name'  => $business_name,
			'url'   => $business_url,
		),
	);
	if ( '' !== $medical_specialty ) {
		$physician['medicalSpecialty'] = $medical_specialty;
	}
	if ( '' !== $job_title ) {
		$physician['jobTitle'] = $job_title;
	}
	if ( '' !== $physician_img ) {
		$physician['image'] = array(
			'@type' => 'ImageObject',
			'url'   => $physician_img,
		);
	}
	if ( ! empty( $same_as ) ) {
		$physician['sameAs'] = $same_as;
	}

	$schemas = array(
		$medical_business,
		$physician,
	);

	$physician_author_node = $physician;
	unset( $physician_author_node['@context'] );

	$procedure_desc = trim( (string) get_post_meta( $post_id, 'short_description', true ) );
	if ( '' === $procedure_desc ) {
		$procedure_desc = trim( (string) get_post_meta( $post_id, 'treatment_short_desc', true ) );
	}
	if ( '' === $procedure_desc ) {
		$procedure_desc = wp_strip_all_tags( (string) get_post_field( 'post_excerpt', $post_id ) );
	}
	if ( '' === $procedure_desc ) {
		$procedure_desc = wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) ), 40, '...' );
	}

	$procedure_schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'MedicalProcedure',
		'name'     => (string) get_the_title( $post_id ),
		'url'      => (string) get_permalink( $post_id ),
		'author'   => $physician_author_node,
	);
	if ( '' !== $procedure_desc ) {
		$procedure_schema['description'] = $procedure_desc;
	}
	if ( ! empty( $image_objects ) ) {
		$procedure_schema['image'] = $image_objects[0];
	}
	$schemas[] = $procedure_schema;

	if ( ! empty( $image_objects ) ) {
		$schemas[] = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'ImageGallery',
			'name'             => (string) get_the_title( $post_id ),
			'associatedMedia' => $image_objects,
		);
	}

	custom_manhattan_laser_render_json_ld( $schemas );
}

/**
 * Schema.org: single blog post (BlogPosting).
 *
 * @param int $post_id Post ID.
 * @return void
 */
function custom_manhattan_laser_render_single_post_schema( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id < 1 ) {
		return;
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post || 'post' !== $post->post_type || 'publish' !== $post->post_status ) {
		return;
	}

	$single_img_url = (string) get_post_meta( $post_id, 'single_post_image_url', true );
	$image_url      = '' !== $single_img_url ? $single_img_url : (string) get_the_post_thumbnail_url( $post_id, 'large' );
	$description    = (string) get_the_excerpt( $post_id );
	if ( '' === $description ) {
		$description = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
		$description = wp_trim_words( $description, 35, '...' );
	}

	$author_id   = (int) $post->post_author;
	$author_name = $author_id > 0 ? get_the_author_meta( 'display_name', $author_id ) : '';
	if ( '' === $author_name ) {
		$author_name = get_bloginfo( 'name' );
	}

	$schema = array(
		'@context'          => 'https://schema.org',
		'@type'             => 'BlogPosting',
		'mainEntityOfPage'  => get_permalink( $post_id ),
		'url'               => get_permalink( $post_id ),
		'headline'          => get_the_title( $post_id ),
		'description'       => $description,
		'datePublished'     => get_the_date( DATE_W3C, $post_id ),
		'dateModified'      => get_the_modified_date( DATE_W3C, $post_id ),
		'author'            => array(
			'@type' => 'Person',
			'name'  => $author_name,
			'url'   => home_url( '/' ),
		),
		'publisher'         => custom_manhattan_laser_get_schema_publisher(),
	);

	if ( '' !== $image_url ) {
		$schema['image'] = array(
			'@type' => 'ImageObject',
			'url'   => $image_url,
		);
	}

	custom_manhattan_laser_render_json_ld( $schema );
}

/**
 * Schema.org: Blog archive/list page.
 *
 * @param int      $page_id   Blog page ID.
 * @param WP_Query $blog_query Query with visible posts.
 * @return void
 */
function custom_manhattan_laser_render_blog_archive_schema( $page_id, $blog_query ) {
	$page_id = (int) $page_id;
	$blog_url = $page_id > 0 ? get_permalink( $page_id ) : custom_manhattan_laser_get_blog_page_url();
	if ( '' === $blog_url ) {
		$blog_url = home_url( '/' );
	}

	$page_title = $page_id > 0 ? (string) get_the_title( $page_id ) : (string) __( 'Blog', 'custom-manhattan-laser-theme' );
	if ( '' === $page_title ) {
		$page_title = (string) __( 'Blog', 'custom-manhattan-laser-theme' );
	}
	$page_desc = $page_id > 0 ? (string) get_post_field( 'post_excerpt', $page_id ) : '';
	if ( '' === $page_desc ) {
		$page_desc = (string) __( 'Articles and insights from Manhattan Laser experts.', 'custom-manhattan-laser-theme' );
	}

	$item_list_elements = array();
	if ( $blog_query instanceof WP_Query && ! empty( $blog_query->posts ) ) {
		$position = 1;
		foreach ( $blog_query->posts as $post_obj ) {
			if ( ! $post_obj instanceof WP_Post ) {
				continue;
			}
			$post_img_url = (string) get_post_meta( $post_obj->ID, 'single_post_image_url', true );
			if ( '' === $post_img_url ) {
				$post_img_url = (string) get_the_post_thumbnail_url( $post_obj->ID, 'large' );
			}
			$post_desc = (string) get_the_excerpt( $post_obj->ID );
			if ( '' === $post_desc ) {
				$post_desc = wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $post_obj->ID ) ), 24, '...' );
			}

			$item = array(
				'@type'         => 'BlogPosting',
				'url'           => get_permalink( $post_obj->ID ),
				'headline'      => get_the_title( $post_obj->ID ),
				'datePublished' => get_the_date( DATE_W3C, $post_obj->ID ),
				'dateModified'  => get_the_modified_date( DATE_W3C, $post_obj->ID ),
				'description'   => $post_desc,
			);
			if ( '' !== $post_img_url ) {
				$item['image'] = array(
					'@type' => 'ImageObject',
					'url'   => $post_img_url,
				);
			}

			$item_list_elements[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'url'      => get_permalink( $post_obj->ID ),
				'item'     => $item,
			);
			$position ++;
		}
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'Blog',
		'url'        => $blog_url,
		'name'       => $page_title,
		'description'=> $page_desc,
		'publisher'  => custom_manhattan_laser_get_schema_publisher(),
	);

	if ( ! empty( $item_list_elements ) ) {
		$schema['blogPost'] = array_map(
			function( $list_item ) {
				return isset( $list_item['item'] ) ? $list_item['item'] : null;
			},
			$item_list_elements
		);
		$schema['mainEntity'] = array(
			'@type'           => 'ItemList',
			'itemListElement' => $item_list_elements,
		);
	}

	custom_manhattan_laser_render_json_ld( $schema );
}

/**
 * Нужен ли приоритет схемы темы над Yoast JSON-LD.
 *
 * @return bool
 */
function custom_manhattan_laser_should_override_yoast_schema() {
	if ( is_admin() ) {
		return false;
	}
	$enabled = (bool) apply_filters( 'custom_manhattan_laser_override_yoast_schema', true );
	if ( ! $enabled ) {
		return false;
	}
	if ( is_singular( 'post' ) ) {
		return true;
	}
	if ( is_page_template( 'blog.php' ) || is_home() ) {
		return true;
	}
	return false;
}

/**
 * Приоритет схемы темы: отключаем JSON-LD Yoast для блога и single post.
 *
 * @param array<int, mixed>|false $data Existing Yoast schema data.
 * @return array<int, mixed>|false
 */
function custom_manhattan_laser_disable_yoast_schema_on_blog( $data ) {
	if ( custom_manhattan_laser_should_override_yoast_schema() ) {
		return false;
	}
	return $data;
}
add_filter( 'wpseo_json_ld_output', 'custom_manhattan_laser_disable_yoast_schema_on_blog', 99 );

/**
 * URL страницы с шаблоном Blog (blog.php), для хлебных крошек и ссылок.
 *
 * @return string
 */
function custom_manhattan_laser_get_blog_page_url() {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}
	$cached = home_url( '/' );
	$page_for_posts = (int) get_option( 'page_for_posts' );
	if ( $page_for_posts ) {
		$cached = get_permalink( $page_for_posts );
		return $cached;
	}
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'blog.php',
			'fields'         => 'ids',
		)
	);
	if ( ! empty( $pages ) ) {
		$cached = get_permalink( (int) $pages[0] );
	}
	return $cached;
}

/**
 * Treatment meta: short description + characteristics
 */
function custom_manhattan_laser_register_treatment_meta() {
	register_post_meta( 'treatment', 'treatment_short_desc', array(
		'type'          => 'string',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );

	$single_content_fields = array(
		'treatment_hero_bg_image_url',
		'treatment_hero_title',
		'treatment_highlight_1',
		'treatment_highlight_2',
		'treatment_highlight_3',
		'treatment_highlight_4',
		'treatment_label_best_for',
		'treatment_label_best_for_title',
		'treatment_label_duration',
		'treatment_label_duration_title',
		'treatment_label_downtime',
		'treatment_label_downtime_title',
		'treatment_label_results_visible',
		'treatment_label_results_visible_title',
		'treatment_label_longevity',
		'treatment_label_longevity_title',
		'treatment_label_safety',
		'treatment_label_safety_title',
		'treatment_cta_label',
		'treatment_cta_url',
		'treatment_neuro_heading',
		'treatment_neuro_intro',
		'treatment_neuro_item_1_name',
		'treatment_neuro_item_1_text',
		'treatment_neuro_item_2_name',
		'treatment_neuro_item_2_text',
		'treatment_neuro_item_3_name',
		'treatment_neuro_item_3_text',
		'treatment_neuro_item_4_name',
		'treatment_neuro_item_4_text',
		'treatment_areas_heading',
		'treatment_areas_items',
		'treatment_areas_image_left_url',
		'treatment_areas_image_right_url',
		'treatment_areas_image_mobile_url',
		'treatment_candidate_heading',
		'treatment_candidate_suitable_label',
		'treatment_candidate_suitable_text',
		'treatment_candidate_not_suitable_label',
		'treatment_candidate_not_suitable_text',
		'treatment_pricing_heading',
		'treatment_pricing_table_json',
		'treatment_comparison_heading',
		'treatment_comparison_table_json',
		'treatment_procedure_heading',
		'treatment_procedure_step_1_label',
		'treatment_procedure_step_1_title',
		'treatment_procedure_step_2_label',
		'treatment_procedure_step_2_title',
		'treatment_procedure_step_3_label',
		'treatment_procedure_step_3_title',
		'treatment_procedure_step_4_label',
		'treatment_procedure_step_4_title',
		'treatment_results_longevity_heading',
		'treatment_results_longevity_rows_json',
		'treatment_faq_heading',
		'treatment_faq_intro',
		'treatment_faq_items_json',
	);
	foreach ( $single_content_fields as $meta_key ) {
		register_post_meta(
			'treatment',
			$meta_key,
			array(
				'type'          => 'string',
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
			)
		);
	}

	register_post_meta(
		'treatment',
		'treatment_author_doctor_id',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_treatment_meta' );

/**
 * Дефолтная таблица цен (single treatment).
 *
 * @return array{headers: string[], rows: string[][]}
 */
function custom_manhattan_laser_get_default_treatment_pricing_table() {
	return array(
		'headers' => array(
			__( 'Package', 'custom-manhattan-laser-theme' ),
			__( 'Units', 'custom-manhattan-laser-theme' ),
			__( 'Price', 'custom-manhattan-laser-theme' ),
			__( 'Promo deal', 'custom-manhattan-laser-theme' ),
		),
		'rows'    => array(
			array( __( 'Small Area', 'custom-manhattan-laser-theme' ), '20', '$240', '-' ),
			array( __( "Forehead + 11's", 'custom-manhattan-laser-theme' ), '40', '$480', __( 'Buy 40, Get +10 Free', 'custom-manhattan-laser-theme' ) ),
			array( __( 'Full Face', 'custom-manhattan-laser-theme' ), '60', '$720', __( 'Discounted package', 'custom-manhattan-laser-theme' ) ),
			array(
				__( 'Full Face Unlimited', 'custom-manhattan-laser-theme' ),
				__( 'Unlimited Units', 'custom-manhattan-laser-theme' ),
				'$395',
				__( 'Special Promotion', 'custom-manhattan-laser-theme' ),
			),
		),
	);
}

/**
 * Санитизация JSON таблицы цен из админки.
 *
 * @param mixed $raw
 * @return string|null
 */
function custom_manhattan_laser_sanitize_treatment_pricing_table_json( $raw ) {
	$str = is_string( $raw ) ? wp_unslash( $raw ) : '';
	$decoded = json_decode( $str, true );
	if ( ! is_array( $decoded ) ) {
		return null;
	}
	$headers = array();
	if ( ! empty( $decoded['headers'] ) && is_array( $decoded['headers'] ) ) {
		foreach ( $decoded['headers'] as $h ) {
			$headers[] = sanitize_text_field( is_string( $h ) ? $h : (string) $h );
		}
	}
	$col_count = count( $headers );
	$rows = array();
	if ( ! empty( $decoded['rows'] ) && is_array( $decoded['rows'] ) ) {
		foreach ( $decoded['rows'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$cells = array();
			for ( $i = 0; $i < $col_count; $i++ ) {
				$val   = isset( $row[ $i ] ) ? $row[ $i ] : '';
				$cells[] = sanitize_text_field( is_string( $val ) ? $val : (string) $val );
			}
			$rows[] = $cells;
		}
	}
	return wp_json_encode(
		array(
			'headers' => $headers,
			'rows'    => $rows,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);
}

/**
 * Таблица цен для шаблона.
 *
 * @param int $post_id
 * @return array{headers: string[], rows: string[][]}
 */
function custom_manhattan_laser_get_treatment_pricing_table_for_template( $post_id ) {
	if ( ! $post_id ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$raw = get_post_meta( $post_id, 'treatment_pricing_table_json', true );
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) || ! isset( $decoded['headers'] ) || ! is_array( $decoded['headers'] ) ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$headers   = array_map( 'strval', $decoded['headers'] );
	$col_count = count( $headers );
	$rows_in   = isset( $decoded['rows'] ) && is_array( $decoded['rows'] ) ? $decoded['rows'] : array();
	$out_rows  = array();
	foreach ( $rows_in as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$cells = array();
		for ( $i = 0; $i < $col_count; $i++ ) {
			$cells[] = isset( $row[ $i ] ) ? (string) $row[ $i ] : '';
		}
		$out_rows[] = $cells;
	}
	return array(
		'headers' => $headers,
		'rows'    => $out_rows,
	);
}

/**
 * Дефолтная таблица сравнения (светлая секция внизу, Botox vs …).
 *
 * @return array{headers: string[], rows: string[][]}
 */
function custom_manhattan_laser_get_default_treatment_comparison_table() {
	return array(
		'headers' => array(
			__( 'CRITERIA', 'custom-manhattan-laser-theme' ),
			__( 'BOTOX', 'custom-manhattan-laser-theme' ),
			__( 'DYSPORT', 'custom-manhattan-laser-theme' ),
			__( 'XEOMIN', 'custom-manhattan-laser-theme' ),
			__( 'JEUVEAU', 'custom-manhattan-laser-theme' ),
		),
		'rows'    => array(
			array(
				__( 'FDA Approved', 'custom-manhattan-laser-theme' ),
				'2002',
				'2009',
				'2010',
				'2019',
			),
			array(
				__( 'Average Cost', 'custom-manhattan-laser-theme' ),
				__( '$550 / session', 'custom-manhattan-laser-theme' ),
				__( '$450 / session', 'custom-manhattan-laser-theme' ),
				__( '$500 / session', 'custom-manhattan-laser-theme' ),
				__( '$450–$500 / session', 'custom-manhattan-laser-theme' ),
			),
			array(
				__( 'Onset', 'custom-manhattan-laser-theme' ),
				__( '5–7 days', 'custom-manhattan-laser-theme' ),
				__( '2–5 days', 'custom-manhattan-laser-theme' ),
				__( '3–5 days', 'custom-manhattan-laser-theme' ),
				__( '3–4 days', 'custom-manhattan-laser-theme' ),
			),
			array(
				__( 'Longevity', 'custom-manhattan-laser-theme' ),
				__( '3–6 months', 'custom-manhattan-laser-theme' ),
				__( '3–5 months', 'custom-manhattan-laser-theme' ),
				__( '3–6 months', 'custom-manhattan-laser-theme' ),
				__( '3–6 months', 'custom-manhattan-laser-theme' ),
			),
		),
	);
}

/**
 * Таблица сравнения для шаблона (отдельно от цен).
 *
 * @param int $post_id
 * @return array{headers: string[], rows: string[][]}
 */
function custom_manhattan_laser_get_treatment_comparison_table_for_template( $post_id ) {
	if ( ! $post_id ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$raw = get_post_meta( $post_id, 'treatment_comparison_table_json', true );
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) || ! isset( $decoded['headers'] ) || ! is_array( $decoded['headers'] ) ) {
		return array(
			'headers' => array(),
			'rows'    => array(),
		);
	}
	$headers   = array_map( 'strval', $decoded['headers'] );
	$col_count = count( $headers );
	$rows_in   = isset( $decoded['rows'] ) && is_array( $decoded['rows'] ) ? $decoded['rows'] : array();
	$out_rows  = array();
	foreach ( $rows_in as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$cells = array();
		for ( $i = 0; $i < $col_count; $i++ ) {
			$cells[] = isset( $row[ $i ] ) ? (string) $row[ $i ] : '';
		}
		$out_rows[] = $cells;
	}
	return array(
		'headers' => $headers,
		'rows'    => $out_rows,
	);
}

/**
 * Дефолтные строки блока Results & Longevity (single treatment).
 *
 * @return array{rows: array<int, array{label: string, value: string}>}
 */
function custom_manhattan_laser_get_default_treatment_results_longevity_rows() {
	return array(
		'rows' => array(
			array(
				'label' => __( '', 'custom-manhattan-laser-theme' ),
				'value' => __( '', 'custom-manhattan-laser-theme' ),
			),
			array(
				'label' => __( '', 'custom-manhattan-laser-theme' ),
				'value' => __( '', 'custom-manhattan-laser-theme' ),
			),
		),
	);
}

/**
 * Санитизация JSON строк Results & Longevity.
 *
 * @param mixed $raw
 * @return string|null
 */
function custom_manhattan_laser_sanitize_treatment_results_longevity_rows_json( $raw ) {
	$str     = is_string( $raw ) ? wp_unslash( $raw ) : '';
	$decoded = json_decode( $str, true );
	if ( ! is_array( $decoded ) ) {
		return null;
	}
	$rows_in = isset( $decoded['rows'] ) && is_array( $decoded['rows'] ) ? $decoded['rows'] : array();
	$rows    = array();
	$max     = 30;
	$count   = 0;
	foreach ( $rows_in as $row ) {
		if ( $count >= $max ) {
			break;
		}
		if ( ! is_array( $row ) ) {
			continue;
		}
		$label = isset( $row['label'] ) ? $row['label'] : '';
		$value = isset( $row['value'] ) ? $row['value'] : '';
		$rows[] = array(
			'label' => sanitize_text_field( is_string( $label ) ? $label : (string) $label ),
			'value' => sanitize_textarea_field( is_string( $value ) ? $value : (string) $value ),
		);
		++$count;
	}
	return wp_json_encode( array( 'rows' => $rows ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
}

/**
 * Данные блока Results & Longevity для шаблона.
 *
 * @param int $post_id
 * @return array{heading: string, rows: array<int, array{label: string, value: string}>}
 */
function custom_manhattan_laser_get_treatment_results_longevity_for_template( $post_id ) {
	$default_rows = custom_manhattan_laser_get_default_treatment_results_longevity_rows();
	$heading_meta   = $post_id ? (string) get_post_meta( $post_id, 'treatment_results_longevity_heading', true ) : '';
	$heading        = '' !== trim( $heading_meta )
		? $heading_meta
		: __( 'Results & Longevity', 'custom-manhattan-laser-theme' );

	$rows = $default_rows['rows'];
	if ( $post_id ) {
		$raw = get_post_meta( $post_id, 'treatment_results_longevity_rows_json', true );
		if ( is_string( $raw ) && '' !== trim( $raw ) ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) && ! empty( $decoded['rows'] ) && is_array( $decoded['rows'] ) ) {
				$parsed = array();
				foreach ( $decoded['rows'] as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					$label = isset( $row['label'] ) ? (string) $row['label'] : '';
					$value = isset( $row['value'] ) ? (string) $row['value'] : '';
					$parsed[] = array(
						'label' => $label,
						'value' => $value,
					);
				}
				if ( ! empty( $parsed ) ) {
					$rows = $parsed;
				}
			}
		}
	}

	return array(
		'heading' => $heading,
		'rows'    => $rows,
	);
}

/**
 * Meta box: Short description (for card on front)
 */
function custom_manhattan_laser_treatment_short_desc_meta_box() {
	add_meta_box(
		'treatment_short_desc',
		__( 'Short description (for card)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_treatment_short_desc_meta_box_cb',
		'treatment',
		'normal',
		'high'
	);
}

function custom_manhattan_laser_treatment_short_desc_meta_box_cb( $post ) {
	wp_nonce_field( 'treatment_short_desc_nonce', 'treatment_short_desc_nonce' );
	$value = get_post_meta( $post->ID, 'treatment_short_desc', true );
	echo '<p><label for="treatment_short_desc" class="screen-reader-text">' . esc_html__( 'Short description', 'custom-manhattan-laser-theme' ) . '</label>';
	echo '<textarea id="treatment_short_desc" name="treatment_short_desc" class="widefat" rows="3">' . esc_textarea( $value ) . '</textarea></p>';
}

/**
 * Meta box: врач как автор процедуры (схема + блок на сайте).
 */
function custom_manhattan_laser_treatment_author_doctor_meta_box() {
	add_meta_box(
		'treatment_author_doctor',
		__( 'Procedure author (doctor)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_treatment_author_doctor_meta_box_cb',
		'treatment',
		'side',
		'high'
	);
}

/**
 * @param WP_Post $post Post.
 */
function custom_manhattan_laser_treatment_author_doctor_meta_box_cb( $post ) {
	wp_nonce_field( 'treatment_author_doctor_nonce', 'treatment_author_doctor_nonce' );
	$selected = (int) get_post_meta( $post->ID, 'treatment_author_doctor_id', true );
	$doctors  = get_posts(
		array(
			'post_type'              => 'doctor',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		)
	);
	echo '<p><label for="treatment_author_doctor_id"><strong>' . esc_html__( 'Author physician', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<select id="treatment_author_doctor_id" name="treatment_author_doctor_id" class="widefat">';
	echo '<option value="0">' . esc_html__( '— WordPress post author (default) —', 'custom-manhattan-laser-theme' ) . '</option>';
	foreach ( $doctors as $doc_post ) {
		echo '<option value="' . esc_attr( (string) (int) $doc_post->ID ) . '"' . selected( $selected, (int) $doc_post->ID, false ) . '>' . esc_html( get_the_title( $doc_post ) ) . '</option>';
	}
	echo '</select>';
	echo '<p class="description">' . esc_html__( 'Displayed on the treatment page and used in JSON-LD as the procedure author. Set photo in the doctor’s featured image; optional profile URLs under the doctor’s hero fields.', 'custom-manhattan-laser-theme' ) . '</p>';
}

/**
 * Meta box for treatment characteristics
 */
function custom_manhattan_laser_treatment_meta_box() {
	add_meta_box(
		'treatment_characteristics',
		__( 'Characteristics', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_treatment_meta_box_cb',
		'treatment',
		'normal'
	);
}

/**
 * Meta box for single treatment content blocks (hero + neuro section).
 */
function custom_manhattan_laser_treatment_single_content_meta_box() {
	add_meta_box(
		'treatment_single_content',
		__( 'Single Treatment Content', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_treatment_single_content_meta_box_cb',
		'treatment',
		'normal'
	);
}

function custom_manhattan_laser_treatment_meta_box_cb( $post ) {
	wp_nonce_field( 'treatment_characteristics_nonce', 'treatment_characteristics_nonce' );
}

function custom_manhattan_laser_treatment_single_content_meta_box_cb( $post ) {
	wp_nonce_field( 'treatment_single_content_nonce', 'treatment_single_content_nonce' );

	$fields = array(
		'treatment_hero_bg_image_url'      => array( 'label' => __( 'Hero background image URL (optional override featured image)', 'custom-manhattan-laser-theme' ), 'type' => 'url' ),
		'treatment_hero_title'             => array( 'label' => __( 'Hero title', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_highlight_1'            => array( 'label' => __( 'Highlight 1', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_highlight_2'            => array( 'label' => __( 'Highlight 2', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_highlight_3'            => array( 'label' => __( 'Highlight 3', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_highlight_4'            => array( 'label' => __( 'Highlight 4', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_best_for'         => array( 'label' => __( 'Label: Best for', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_best_for_title'  => array( 'label' => __( 'Label title: Best for', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_duration'         => array( 'label' => __( 'Label: Duration', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_duration_title'  => array( 'label' => __( 'Label title: Duration', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_downtime'         => array( 'label' => __( 'Label: Downtime', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_downtime_title'  => array( 'label' => __( 'Label title: Downtime', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_results_visible'  => array( 'label' => __( 'Label: Results visible', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_results_visible_title' => array( 'label' => __( 'Label title: Results visible', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_longevity'        => array( 'label' => __( 'Label: Longevity', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_longevity_title' => array( 'label' => __( 'Label title: Longevity', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_safety'           => array( 'label' => __( 'Label: Safety', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_label_safety_title'   => array( 'label' => __( 'Label title: Safety', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_heading'          => array( 'label' => __( 'How it works heading', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_intro'            => array( 'label' => __( 'How it works intro', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_neuro_item_1_name'      => array( 'label' => __( 'How it works 1 title', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_item_1_text'      => array( 'label' => __( 'How it works 1 text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_neuro_item_2_name'      => array( 'label' => __( 'How it works 2 title', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_item_2_text'      => array( 'label' => __( 'How it works 2 text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_neuro_item_3_name'      => array( 'label' => __( 'How it works 3 title', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_item_3_text'      => array( 'label' => __( 'How it works 3 text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_neuro_item_4_name'      => array( 'label' => __( 'How it works 4 title', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_neuro_item_4_text'      => array( 'label' => __( 'How it works 4 text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_areas_heading'          => array( 'label' => __( 'Treatable Areas heading', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_areas_items'            => array( 'label' => __( 'Treatable Areas list (one item per line)', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_areas_image_left_url'   => array( 'label' => __( 'Treatable Areas left image URL (desktop)', 'custom-manhattan-laser-theme' ), 'type' => 'url' ),
		'treatment_areas_image_right_url'  => array( 'label' => __( 'Treatable Areas right top image URL (desktop)', 'custom-manhattan-laser-theme' ), 'type' => 'url' ),
		'treatment_areas_image_mobile_url' => array( 'label' => __( 'Treatable Areas mobile image URL', 'custom-manhattan-laser-theme' ), 'type' => 'url' ),
		'treatment_candidate_heading'      => array( 'label' => __( 'Candidate section heading', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_candidate_suitable_label' => array( 'label' => __( 'Candidate: suitable label', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_candidate_suitable_text' => array( 'label' => __( 'Candidate: suitable text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_candidate_not_suitable_label' => array( 'label' => __( 'Candidate: not suitable label', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_candidate_not_suitable_text' => array( 'label' => __( 'Candidate: not suitable text', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_pricing_heading'          => array( 'label' => __( 'Pricing section heading', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
	);

	foreach ( $fields as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		echo '<p><label for="' . esc_attr( $key ) . '" style="display:block;margin-bottom:4px;"><strong>' . esc_html( $field['label'] ) . '</strong></label>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" class="widefat" rows="3">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
		}
		echo '</p>';
	}

	$pricing_json_for_admin = get_post_meta( $post->ID, 'treatment_pricing_table_json', true );
	if ( ! is_string( $pricing_json_for_admin ) || '' === trim( $pricing_json_for_admin ) ) {
		$pricing_json_for_admin = wp_json_encode(
			custom_manhattan_laser_get_default_treatment_pricing_table(),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}
	$pricing_default_js = wp_json_encode(
		custom_manhattan_laser_get_default_treatment_pricing_table(),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	echo '<hr style="margin:20px 0;">';
	echo '<p><strong>' . esc_html__( 'Pricing table (columns & rows)', 'custom-manhattan-laser-theme' ) . '</strong></p>';
	echo '<p class="description">' . esc_html__( 'Add or remove columns and rows. Values are saved as JSON; the front-end table updates automatically.', 'custom-manhattan-laser-theme' ) . '</p>';
	echo '<p style="margin:10px 0;">';
	echo '<button type="button" class="button" id="treatment-pricing-add-col">' . esc_html__( 'Add column', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-pricing-remove-col">' . esc_html__( 'Remove last column', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-pricing-add-row">' . esc_html__( 'Add row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-pricing-remove-row">' . esc_html__( 'Remove last row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-pricing-clear-table">' . esc_html__( 'Clear table', 'custom-manhattan-laser-theme' ) . '</button>';
	echo '</p>';
	echo '<div style="overflow-x:auto;margin-bottom:10px;"><table class="widefat striped" id="treatment-pricing-editor-table"><thead id="treatment-pricing-thead"></thead><tbody id="treatment-pricing-tbody"></tbody></table></div>';
	echo '<textarea name="treatment_pricing_table_json" id="treatment_pricing_table_json" style="display:none;width:100%;" rows="4">' . esc_textarea( $pricing_json_for_admin ) . '</textarea>';

	echo '<script>
(function(){
	var defaultData = ' . $pricing_default_js . ';
	var input = document.getElementById("treatment_pricing_table_json");
	if (!input) return;
	var state = { headers: [], rows: [] };

	function loadState() {
		try {
			var v = input.value.trim();
			if (v) {
				var o = JSON.parse(v);
				if (o && Array.isArray(o.headers) && Array.isArray(o.rows)) {
					state.headers = o.headers.map(function(x){ return String(x); });
					state.rows = o.rows.map(function(r){
						return Array.isArray(r) ? r.map(function(c){ return String(c); }) : [];
					});
					normalize();
					return;
				}
			}
		} catch (e) {}
		state = JSON.parse(JSON.stringify(defaultData));
		normalize();
	}

	function normalize() {
		var n = state.headers.length;
		state.rows.forEach(function(row) {
			while (row.length < n) row.push("");
			if (row.length > n) row.length = n;
		});
	}

	function save() {
		normalize();
		input.value = JSON.stringify({ headers: state.headers, rows: state.rows });
	}

	function render() {
		normalize();
		var thead = document.getElementById("treatment-pricing-thead");
		var tbody = document.getElementById("treatment-pricing-tbody");
		if (!thead || !tbody) return;
		thead.innerHTML = "";
		tbody.innerHTML = "";
		var trh = document.createElement("tr");
		state.headers.forEach(function(h, ci) {
			var th = document.createElement("th");
			var inp = document.createElement("input");
			inp.type = "text";
			inp.className = "widefat";
			inp.value = h;
			inp.addEventListener("input", function() {
				state.headers[ci] = inp.value;
				save();
			});
			th.appendChild(inp);
			trh.appendChild(th);
		});
		thead.appendChild(trh);
		state.rows.forEach(function(row, ri) {
			var tr = document.createElement("tr");
			for (var ci = 0; ci < state.headers.length; ci++) {
				var td = document.createElement("td");
				var inp = document.createElement("input");
				inp.type = "text";
				inp.className = "widefat";
				inp.value = row[ci] != null ? row[ci] : "";
				(function(r, c) {
					inp.addEventListener("input", function() {
						state.rows[r][c] = this.value;
						save();
					});
				})(ri, ci);
				td.appendChild(inp);
				tr.appendChild(td);
			}
			tbody.appendChild(tr);
		});
	}

	document.getElementById("treatment-pricing-add-col") && document.getElementById("treatment-pricing-add-col").addEventListener("click", function() {
		state.headers.push("");
		state.rows.forEach(function(row) { row.push(""); });
		save();
		render();
	});
	document.getElementById("treatment-pricing-remove-col") && document.getElementById("treatment-pricing-remove-col").addEventListener("click", function() {
		if (state.headers.length <= 0) return;
		state.headers.pop();
		state.rows.forEach(function(row) { row.pop(); });
		save();
		render();
	});
	document.getElementById("treatment-pricing-add-row") && document.getElementById("treatment-pricing-add-row").addEventListener("click", function() {
		state.rows.push(new Array(state.headers.length).fill(""));
		save();
		render();
	});
	document.getElementById("treatment-pricing-remove-row") && document.getElementById("treatment-pricing-remove-row").addEventListener("click", function() {
		if (!state.rows.length) return;
		state.rows.pop();
		save();
		render();
	});
	document.getElementById("treatment-pricing-clear-table") && document.getElementById("treatment-pricing-clear-table").addEventListener("click", function() {
		state.headers = [];
		state.rows = [];
		save();
		render();
	});

	loadState();
	save();
	render();
})();
</script>';

	$comparison_json_for_admin = get_post_meta( $post->ID, 'treatment_comparison_table_json', true );
	if ( ! is_string( $comparison_json_for_admin ) || '' === trim( $comparison_json_for_admin ) ) {
		$comparison_json_for_admin = wp_json_encode(
			custom_manhattan_laser_get_default_treatment_comparison_table(),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}

	echo '<hr style="margin:20px 0;">';
	echo '<p><strong>' . esc_html__( 'Comparison table (light section, bottom)', 'custom-manhattan-laser-theme' ) . '</strong></p>';
	$comparison_heading_val = (string) get_post_meta( $post->ID, 'treatment_comparison_heading', true );
	echo '<p><label for="treatment_comparison_heading" style="display:block;margin-bottom:4px;"><strong>' . esc_html__( 'Comparison section heading', 'custom-manhattan-laser-theme' ) . '</strong></label>';
	echo '<input type="text" id="treatment_comparison_heading" name="treatment_comparison_heading" value="' . esc_attr( $comparison_heading_val ) . '" class="widefat" /></p>';
	echo '<p class="description">' . esc_html__( 'Separate from pricing. Same editor: columns (headers) and rows.', 'custom-manhattan-laser-theme' ) . '</p>';
	echo '<p style="margin:10px 0;">';
	echo '<button type="button" class="button" id="treatment-comparison-add-col">' . esc_html__( 'Add column', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-comparison-remove-col">' . esc_html__( 'Remove last column', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-comparison-add-row">' . esc_html__( 'Add row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-comparison-remove-row">' . esc_html__( 'Remove last row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-comparison-clear-table">' . esc_html__( 'Clear table', 'custom-manhattan-laser-theme' ) . '</button>';
	echo '</p>';
	echo '<div style="overflow-x:auto;margin-bottom:10px;"><table class="widefat striped" id="treatment-comparison-editor-table"><thead id="treatment-comparison-thead"></thead><tbody id="treatment-comparison-tbody"></tbody></table></div>';
	echo '<textarea name="treatment_comparison_table_json" id="treatment_comparison_table_json" style="display:none;width:100%;" rows="4">' . esc_textarea( $comparison_json_for_admin ) . '</textarea>';

	$comparison_default_js = wp_json_encode(
		custom_manhattan_laser_get_default_treatment_comparison_table(),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	echo '<script>
(function(){
	var defaultData = ' . $comparison_default_js . ';
	var input = document.getElementById("treatment_comparison_table_json");
	if (!input) return;
	var state = { headers: [], rows: [] };

	function loadState() {
		try {
			var v = input.value.trim();
			if (v) {
				var o = JSON.parse(v);
				if (o && Array.isArray(o.headers) && Array.isArray(o.rows)) {
					state.headers = o.headers.map(function(x){ return String(x); });
					state.rows = o.rows.map(function(r){
						return Array.isArray(r) ? r.map(function(c){ return String(c); }) : [];
					});
					normalize();
					return;
				}
			}
		} catch (e) {}
		state = JSON.parse(JSON.stringify(defaultData));
		normalize();
	}

	function normalize() {
		var n = state.headers.length;
		state.rows.forEach(function(row) {
			while (row.length < n) row.push("");
			if (row.length > n) row.length = n;
		});
	}

	function save() {
		normalize();
		input.value = JSON.stringify({ headers: state.headers, rows: state.rows });
	}

	function render() {
		normalize();
		var thead = document.getElementById("treatment-comparison-thead");
		var tbody = document.getElementById("treatment-comparison-tbody");
		if (!thead || !tbody) return;
		thead.innerHTML = "";
		tbody.innerHTML = "";
		var trh = document.createElement("tr");
		state.headers.forEach(function(h, ci) {
			var th = document.createElement("th");
			var inp = document.createElement("input");
			inp.type = "text";
			inp.className = "widefat";
			inp.value = h;
			inp.addEventListener("input", function() {
				state.headers[ci] = inp.value;
				save();
			});
			th.appendChild(inp);
			trh.appendChild(th);
		});
		thead.appendChild(trh);
		state.rows.forEach(function(row, ri) {
			var tr = document.createElement("tr");
			for (var ci = 0; ci < state.headers.length; ci++) {
				var td = document.createElement("td");
				var inp = document.createElement("input");
				inp.type = "text";
				inp.className = "widefat";
				inp.value = row[ci] != null ? row[ci] : "";
				(function(r, c) {
					inp.addEventListener("input", function() {
						state.rows[r][c] = this.value;
						save();
					});
				})(ri, ci);
				td.appendChild(inp);
				tr.appendChild(td);
			}
			tbody.appendChild(tr);
		});
	}

	document.getElementById("treatment-comparison-add-col") && document.getElementById("treatment-comparison-add-col").addEventListener("click", function() {
		state.headers.push("");
		state.rows.forEach(function(row) { row.push(""); });
		save();
		render();
	});
	document.getElementById("treatment-comparison-remove-col") && document.getElementById("treatment-comparison-remove-col").addEventListener("click", function() {
		if (state.headers.length <= 0) return;
		state.headers.pop();
		state.rows.forEach(function(row) { row.pop(); });
		save();
		render();
	});
	document.getElementById("treatment-comparison-add-row") && document.getElementById("treatment-comparison-add-row").addEventListener("click", function() {
		state.rows.push(new Array(state.headers.length).fill(""));
		save();
		render();
	});
	document.getElementById("treatment-comparison-remove-row") && document.getElementById("treatment-comparison-remove-row").addEventListener("click", function() {
		if (!state.rows.length) return;
		state.rows.pop();
		save();
		render();
	});
	document.getElementById("treatment-comparison-clear-table") && document.getElementById("treatment-comparison-clear-table").addEventListener("click", function() {
		state.headers = [];
		state.rows = [];
		save();
		render();
	});

	loadState();
	save();
	render();
})();
</script>';

	$procedure_admin_fields = array(
		'treatment_procedure_heading'        => array( 'label' => __( 'Procedure section heading (Step-by-Step)', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_procedure_step_1_label' => array( 'label' => __( 'Procedure step 1 label (e.g. STEP 1)', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_procedure_step_1_title' => array( 'label' => __( 'Procedure step 1 title', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_procedure_step_2_label' => array( 'label' => __( 'Procedure step 2 label', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_procedure_step_2_title' => array( 'label' => __( 'Procedure step 2 title', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_procedure_step_3_label' => array( 'label' => __( 'Procedure step 3 label', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_procedure_step_3_title' => array( 'label' => __( 'Procedure step 3 title', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
		'treatment_procedure_step_4_label' => array( 'label' => __( 'Procedure step 4 label', 'custom-manhattan-laser-theme' ), 'type' => 'text' ),
		'treatment_procedure_step_4_title' => array( 'label' => __( 'Procedure step 4 title', 'custom-manhattan-laser-theme' ), 'type' => 'textarea' ),
	);

	echo '<hr style="margin:20px 0;">';
	echo '<p><strong>' . esc_html__( 'Step-by-step procedure (dial section)', 'custom-manhattan-laser-theme' ) . '</strong></p>';
	foreach ( $procedure_admin_fields as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		echo '<p><label for="' . esc_attr( $key ) . '" style="display:block;margin-bottom:4px;"><strong>' . esc_html( $field['label'] ) . '</strong></label>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" class="widefat" rows="3">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
		}
		echo '</p>';
	}

	$results_json_for_admin = get_post_meta( $post->ID, 'treatment_results_longevity_rows_json', true );
	if ( ! is_string( $results_json_for_admin ) || '' === trim( $results_json_for_admin ) ) {
		$results_json_for_admin = wp_json_encode(
			custom_manhattan_laser_get_default_treatment_results_longevity_rows(),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}
	$results_default_js = wp_json_encode(
		custom_manhattan_laser_get_default_treatment_results_longevity_rows(),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	echo '<hr style="margin:20px 0;">';
	echo '<p><strong>' . esc_html__( 'Results & Longevity (bottom section)', 'custom-manhattan-laser-theme' ) . '</strong></p>';
	$results_heading_val = (string) get_post_meta( $post->ID, 'treatment_results_longevity_heading', true );
	echo '<p><label for="treatment_results_longevity_heading" style="display:block;margin-bottom:4px;"><strong>' . esc_html__( 'Section heading', 'custom-manhattan-laser-theme' ) . '</strong></label>';
	echo '<input type="text" id="treatment_results_longevity_heading" name="treatment_results_longevity_heading" value="' . esc_attr( $results_heading_val ) . '" class="widefat" /></p>';
	echo '<p class="description">' . esc_html__( 'Rows: label (left, shown uppercase on the site) and value (right).', 'custom-manhattan-laser-theme' ) . '</p>';
	echo '<p style="margin:10px 0;">';
	echo '<button type="button" class="button" id="treatment-results-longevity-add-row">' . esc_html__( 'Add row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-results-longevity-remove-row">' . esc_html__( 'Remove last row', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-results-longevity-clear-table">' . esc_html__( 'Clear table', 'custom-manhattan-laser-theme' ) . '</button>';
	echo '</p>';
	echo '<div style="overflow-x:auto;margin-bottom:10px;"><table class="widefat striped"><thead><tr><th>' . esc_html__( 'Label', 'custom-manhattan-laser-theme' ) . '</th><th>' . esc_html__( 'Value', 'custom-manhattan-laser-theme' ) . '</th></tr></thead><tbody id="treatment-results-longevity-tbody"></tbody></table></div>';
	echo '<textarea name="treatment_results_longevity_rows_json" id="treatment_results_longevity_rows_json" style="display:none;width:100%;" rows="4">' . esc_textarea( $results_json_for_admin ) . '</textarea>';

	echo '<script>
(function(){
	var defaultData = ' . $results_default_js . ';
	var input = document.getElementById("treatment_results_longevity_rows_json");
	if (!input) return;
	var state = { rows: [] };

	function loadState() {
		try {
			var v = input.value.trim();
			if (v) {
				var o = JSON.parse(v);
				if (o && Array.isArray(o.rows)) {
					state.rows = o.rows.map(function(r) {
						return {
							label: r && r.label != null ? String(r.label) : "",
							value: r && r.value != null ? String(r.value) : ""
						};
					});
					return;
				}
			}
		} catch (e) {}
		state = JSON.parse(JSON.stringify(defaultData));
		if (!state.rows || !Array.isArray(state.rows)) state.rows = [];
	}

	function save() {
		input.value = JSON.stringify({ rows: state.rows });
	}

	function render() {
		var tbody = document.getElementById("treatment-results-longevity-tbody");
		if (!tbody) return;
		tbody.innerHTML = "";
		state.rows.forEach(function(row, ri) {
			var tr = document.createElement("tr");
			["label", "value"].forEach(function(field) {
				var td = document.createElement("td");
				var inp = document.createElement("input");
				inp.type = "text";
				inp.className = "widefat";
				inp.value = row[field] != null ? row[field] : "";
				(function(r, f) {
					inp.addEventListener("input", function() {
						state.rows[r][f] = inp.value;
						save();
					});
				})(ri, field);
				td.appendChild(inp);
				tr.appendChild(td);
			});
			tbody.appendChild(tr);
		});
	}

	document.getElementById("treatment-results-longevity-add-row") && document.getElementById("treatment-results-longevity-add-row").addEventListener("click", function() {
		state.rows.push({ label: "", value: "" });
		save();
		render();
	});
	document.getElementById("treatment-results-longevity-remove-row") && document.getElementById("treatment-results-longevity-remove-row").addEventListener("click", function() {
		if (state.rows.length <= 0) return;
		state.rows.pop();
		save();
		render();
	});
	document.getElementById("treatment-results-longevity-clear-table") && document.getElementById("treatment-results-longevity-clear-table").addEventListener("click", function() {
		state.rows = [];
		save();
		render();
	});

	loadState();
	save();
	render();
})();
</script>';

	$faq_json_for_admin = get_post_meta( $post->ID, 'treatment_faq_items_json', true );
	if ( ! is_string( $faq_json_for_admin ) || '' === trim( $faq_json_for_admin ) ) {
		$faq_json_for_admin = wp_json_encode(
			array( 'items' => custom_manhattan_laser_get_default_treatment_category_faq_items() ),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}
	$faq_default_js = wp_json_encode(
		array( 'items' => custom_manhattan_laser_get_default_treatment_category_faq_items() ),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	echo '<hr style="margin:20px 0;">';
	echo '<p><strong>' . esc_html__( 'FAQ section (single treatment)', 'custom-manhattan-laser-theme' ) . '</strong></p>';
	echo '<p class="description">' . esc_html__( 'If you add at least one question with a full answer, this treatment uses this FAQ. If the list is empty (remove all rows and save), the FAQ block is hidden on this page. If you leave this as the default sample text and never save, the category FAQ (or site default) is used.', 'custom-manhattan-laser-theme' ) . '</p>';
	$faq_heading_val = (string) get_post_meta( $post->ID, 'treatment_faq_heading', true );
	echo '<p><label for="treatment_faq_heading" style="display:block;margin-bottom:4px;"><strong>' . esc_html__( 'FAQ section heading', 'custom-manhattan-laser-theme' ) . '</strong></label>';
	echo '<input type="text" id="treatment_faq_heading" name="treatment_faq_heading" value="' . esc_attr( $faq_heading_val ) . '" class="widefat" placeholder="' . esc_attr( __( 'Frequently Asked Questions', 'custom-manhattan-laser-theme' ) ) . '" /></p>';
	$faq_intro_val = (string) get_post_meta( $post->ID, 'treatment_faq_intro', true );
	echo '<p><label for="treatment_faq_intro" style="display:block;margin-bottom:4px;"><strong>' . esc_html__( 'FAQ intro (under heading)', 'custom-manhattan-laser-theme' ) . '</strong></label>';
	echo '<textarea id="treatment_faq_intro" name="treatment_faq_intro" rows="3" class="widefat" placeholder="' . esc_attr( __( 'Clear answers to help you feel confident before your visit.', 'custom-manhattan-laser-theme' ) ) . '">' . esc_textarea( $faq_intro_val ) . '</textarea></p>';
	echo '<p style="margin:10px 0;">';
	echo '<button type="button" class="button" id="treatment-faq-add-row">' . esc_html__( 'Add FAQ item', 'custom-manhattan-laser-theme' ) . '</button> ';
	echo '<button type="button" class="button" id="treatment-faq-remove-row">' . esc_html__( 'Remove last item', 'custom-manhattan-laser-theme' ) . '</button>';
	echo '</p>';
	echo '<div style="overflow-x:auto;margin-bottom:10px;"><table class="widefat striped"><thead><tr><th>' . esc_html__( 'Question', 'custom-manhattan-laser-theme' ) . '</th><th>' . esc_html__( 'Answer', 'custom-manhattan-laser-theme' ) . '</th></tr></thead><tbody id="treatment-faq-tbody"></tbody></table></div>';
	echo '<textarea name="treatment_faq_items_json" id="treatment_faq_items_json" style="display:none;width:100%;" rows="4">' . esc_textarea( $faq_json_for_admin ) . '</textarea>';

	echo '<script>
(function(){
	var defaultData = ' . $faq_default_js . ';
	var input = document.getElementById("treatment_faq_items_json");
	if (!input) return;
	var state = { items: [] };
	function loadState() {
		try {
			var v = input.value.trim();
			if (v) {
				var o = JSON.parse(v);
				if (o && Array.isArray(o.items)) {
					state.items = o.items.map(function(it) {
						return {
							q: it && it.q != null ? String(it.q) : "",
							a: it && it.a != null ? String(it.a) : ""
						};
					});
					return;
				}
			}
		} catch (e) {}
		state = JSON.parse(JSON.stringify(defaultData));
		if (!state.items || !state.items.length) state.items = [{ q: "", a: "" }];
	}
	function save() {
		input.value = JSON.stringify({ items: state.items });
	}
	function render() {
		var tbody = document.getElementById("treatment-faq-tbody");
		if (!tbody) return;
		tbody.innerHTML = "";
		state.items.forEach(function(row, ri) {
			var tr = document.createElement("tr");
			["q","a"].forEach(function(field) {
				var td = document.createElement("td");
				var el = document.createElement(field === "a" ? "textarea" : "input");
				if (field === "q") { el.type = "text"; el.className = "widefat"; }
				else { el.className = "widefat"; el.rows = 3; }
				el.value = row[field] != null ? row[field] : "";
				(function(r, f) {
					el.addEventListener("input", function() {
						state.items[r][f] = el.value;
						save();
					});
				})(ri, field);
				td.appendChild(el);
				tr.appendChild(td);
			});
			tbody.appendChild(tr);
		});
	}
	document.getElementById("treatment-faq-add-row") && document.getElementById("treatment-faq-add-row").addEventListener("click", function() {
		state.items.push({ q: "", a: "" });
		save();
		render();
	});
	document.getElementById("treatment-faq-remove-row") && document.getElementById("treatment-faq-remove-row").addEventListener("click", function() {
		if (state.items.length <= 0) return;
		state.items.pop();
		save();
		render();
	});
	loadState();
	save();
	render();
})();
</script>';
}

function custom_manhattan_laser_save_treatment_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['treatment_author_doctor_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['treatment_author_doctor_nonce'] ), 'treatment_author_doctor_nonce' ) ) {
		$doc_id = isset( $_POST['treatment_author_doctor_id'] ) ? absint( wp_unslash( $_POST['treatment_author_doctor_id'] ) ) : 0;
		if ( $doc_id > 0 && 'doctor' === get_post_type( $doc_id ) ) {
			update_post_meta( $post_id, 'treatment_author_doctor_id', $doc_id );
		} else {
			delete_post_meta( $post_id, 'treatment_author_doctor_id' );
		}
	}
	if ( isset( $_POST['treatment_short_desc_nonce'] ) && wp_verify_nonce( $_POST['treatment_short_desc_nonce'], 'treatment_short_desc_nonce' ) && isset( $_POST['treatment_short_desc'] ) ) {
		update_post_meta( $post_id, 'treatment_short_desc', sanitize_textarea_field( wp_unslash( $_POST['treatment_short_desc'] ) ) );
	}
	if ( isset( $_POST['treatment_characteristics_nonce'] ) && wp_verify_nonce( $_POST['treatment_characteristics_nonce'], 'treatment_characteristics_nonce' ) ) {
		$keys = array( 'treatment_best_for', 'treatment_duration', 'treatment_downtime', 'treatment_results_visible', 'treatment_longevity', 'treatment_safety' );
		foreach ( $keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}
	if ( isset( $_POST['treatment_single_content_nonce'] ) && wp_verify_nonce( $_POST['treatment_single_content_nonce'], 'treatment_single_content_nonce' ) ) {
		$textarea_keys = array(
			'treatment_neuro_intro',
			'treatment_neuro_item_1_text',
			'treatment_neuro_item_2_text',
			'treatment_neuro_item_3_text',
			'treatment_neuro_item_4_text',
			'treatment_areas_items',
			'treatment_candidate_suitable_text',
			'treatment_candidate_not_suitable_text',
			'treatment_procedure_step_1_title',
			'treatment_procedure_step_2_title',
			'treatment_procedure_step_3_title',
			'treatment_procedure_step_4_title',
			'treatment_faq_intro',
		);
		$url_keys = array(
			'treatment_hero_bg_image_url',
			'treatment_cta_url',
			'treatment_areas_image_left_url',
			'treatment_areas_image_right_url',
			'treatment_areas_image_mobile_url',
		);
		$keys = array(
			'treatment_hero_bg_image_url',
			'treatment_hero_title',
			'treatment_highlight_1',
			'treatment_highlight_2',
			'treatment_highlight_3',
			'treatment_highlight_4',
			'treatment_label_best_for',
			'treatment_label_best_for_title',
			'treatment_label_duration',
			'treatment_label_duration_title',
			'treatment_label_downtime',
			'treatment_label_downtime_title',
			'treatment_label_results_visible',
			'treatment_label_results_visible_title',
			'treatment_label_longevity',
			'treatment_label_longevity_title',
			'treatment_label_safety',
			'treatment_label_safety_title',
			'treatment_cta_label',
			'treatment_cta_url',
			'treatment_neuro_heading',
			'treatment_neuro_intro',
			'treatment_neuro_item_1_name',
			'treatment_neuro_item_1_text',
			'treatment_neuro_item_2_name',
			'treatment_neuro_item_2_text',
			'treatment_neuro_item_3_name',
			'treatment_neuro_item_3_text',
			'treatment_neuro_item_4_name',
			'treatment_neuro_item_4_text',
			'treatment_areas_heading',
			'treatment_areas_items',
			'treatment_areas_image_left_url',
			'treatment_areas_image_right_url',
			'treatment_areas_image_mobile_url',
			'treatment_candidate_heading',
			'treatment_candidate_suitable_label',
			'treatment_candidate_suitable_text',
			'treatment_candidate_not_suitable_label',
			'treatment_candidate_not_suitable_text',
			'treatment_pricing_heading',
			'treatment_comparison_heading',
			'treatment_procedure_heading',
			'treatment_procedure_step_1_label',
			'treatment_procedure_step_1_title',
			'treatment_procedure_step_2_label',
			'treatment_procedure_step_2_title',
			'treatment_procedure_step_3_label',
			'treatment_procedure_step_3_title',
			'treatment_procedure_step_4_label',
			'treatment_procedure_step_4_title',
			'treatment_results_longevity_heading',
			'treatment_faq_heading',
		);
		foreach ( $keys as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}
			$raw_value = wp_unslash( $_POST[ $key ] );
			if ( in_array( $key, $url_keys, true ) ) {
				update_post_meta( $post_id, $key, esc_url_raw( $raw_value ) );
			} elseif ( in_array( $key, $textarea_keys, true ) ) {
				update_post_meta( $post_id, $key, sanitize_textarea_field( $raw_value ) );
			} else {
				update_post_meta( $post_id, $key, sanitize_text_field( $raw_value ) );
			}
		}

		if ( isset( $_POST['treatment_pricing_table_json'] ) ) {
			$san_pricing = custom_manhattan_laser_sanitize_treatment_pricing_table_json( $_POST['treatment_pricing_table_json'] );
			if ( null !== $san_pricing ) {
				update_post_meta( $post_id, 'treatment_pricing_table_json', $san_pricing );
			}
		}

		if ( isset( $_POST['treatment_comparison_table_json'] ) ) {
			$san_cmp = custom_manhattan_laser_sanitize_treatment_pricing_table_json( $_POST['treatment_comparison_table_json'] );
			if ( null !== $san_cmp ) {
				update_post_meta( $post_id, 'treatment_comparison_table_json', $san_cmp );
			}
		}

		if ( isset( $_POST['treatment_results_longevity_rows_json'] ) ) {
			$san_results = custom_manhattan_laser_sanitize_treatment_results_longevity_rows_json( wp_unslash( $_POST['treatment_results_longevity_rows_json'] ) );
			if ( null !== $san_results ) {
				update_post_meta( $post_id, 'treatment_results_longevity_rows_json', $san_results );
			}
		}

		if ( isset( $_POST['treatment_faq_items_json'] ) ) {
			$san_faq = custom_manhattan_laser_sanitize_treatment_category_faq_items_json( wp_unslash( $_POST['treatment_faq_items_json'] ) );
			if ( null !== $san_faq ) {
				update_post_meta( $post_id, 'treatment_faq_items_json', $san_faq );
			}
		}
	}
}
add_action( 'add_meta_boxes', 'custom_manhattan_laser_treatment_short_desc_meta_box' );
add_action( 'add_meta_boxes', 'custom_manhattan_laser_treatment_author_doctor_meta_box' );
add_action( 'add_meta_boxes', 'custom_manhattan_laser_treatment_meta_box' );
add_action( 'add_meta_boxes', 'custom_manhattan_laser_treatment_single_content_meta_box' );
add_action( 'save_post_treatment', 'custom_manhattan_laser_save_treatment_meta' );

/**
 * Post meta: Short description (for card on front)
 */
function custom_manhattan_laser_register_blog_post_short_description_meta() {
	register_post_meta(
		'post',
		'short_description',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
		)
	);
	register_post_meta(
		'post',
		'single_post_image_url',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
		)
	);
}
add_action( 'init', 'custom_manhattan_laser_register_blog_post_short_description_meta' );

/**
 * Meta box: Blog post — short description (for card on front)
 */
function custom_manhattan_laser_blog_post_short_description_meta_box() {
	add_meta_box(
		'blog_post_short_description',
		__( 'Short description (for card)', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_blog_post_short_description_meta_box_cb',
		'post',
		'normal',
		'high'
	);
}

function custom_manhattan_laser_blog_post_short_description_meta_box_cb( $post ) {
	wp_nonce_field( 'blog_post_short_description_nonce', 'blog_post_short_description_nonce' );
	$value            = get_post_meta( $post->ID, 'short_description', true );
	$single_image_url = (string) get_post_meta( $post->ID, 'single_post_image_url', true );
	echo '<p><label for="short_description" class="screen-reader-text">' . esc_html__( 'Short description', 'custom-manhattan-laser-theme' ) . '</label>';
	echo '<textarea id="short_description" name="short_description" class="widefat" rows="3">' . esc_textarea( $value ) . '</textarea></p>';
	echo '<p><label for="single_post_image_url"><strong>' . esc_html__( 'Single post image URL (optional)', 'custom-manhattan-laser-theme' ) . '</strong></label></p>';
	echo '<p><input type="url" id="single_post_image_url" name="single_post_image_url" class="widefat" value="' . esc_attr( $single_image_url ) . '" placeholder="https://"></p>';
	echo '<p class="description">' . esc_html__( 'Used only on the single post page. If empty, the Featured image is used.', 'custom-manhattan-laser-theme' ) . '</p>';
}

function custom_manhattan_laser_save_blog_post_short_description_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['blog_post_short_description_nonce'] ) || ! wp_verify_nonce( $_POST['blog_post_short_description_nonce'], 'blog_post_short_description_nonce' ) ) {
		return;
	}
	if ( isset( $_POST['short_description'] ) ) {
		update_post_meta(
			$post_id,
			'short_description',
			sanitize_textarea_field( wp_unslash( $_POST['short_description'] ) )
		);
	}
	if ( isset( $_POST['single_post_image_url'] ) ) {
		update_post_meta(
			$post_id,
			'single_post_image_url',
			esc_url_raw( wp_unslash( $_POST['single_post_image_url'] ) )
		);
	}
}

add_action( 'add_meta_boxes', 'custom_manhattan_laser_blog_post_short_description_meta_box' );
add_action( 'save_post_post', 'custom_manhattan_laser_save_blog_post_short_description_meta' );

/**
 * Post type: Before / After
 * Публичный архив (/before-after/), одиночные URL отдают 404 (не индексируются).
 */
function custom_manhattan_laser_register_before_after_post_type() {
	$labels = array(
		'name'               => _x( 'Before / After', 'post type general name', 'custom-manhattan-laser-theme' ),
		'singular_name'      => _x( 'Before / After', 'post type singular name', 'custom-manhattan-laser-theme' ),
		'menu_name'          => _x( 'Before / After', 'admin menu', 'custom-manhattan-laser-theme' ),
		'add_new'            => _x( 'Add New', 'before/after', 'custom-manhattan-laser-theme' ),
		'add_new_item'       => __( 'Add New Case', 'custom-manhattan-laser-theme' ),
		'edit_item'          => __( 'Edit Case', 'custom-manhattan-laser-theme' ),
		'new_item'           => __( 'New Case', 'custom-manhattan-laser-theme' ),
		'view_item'          => __( 'View Case', 'custom-manhattan-laser-theme' ),
		'search_items'       => __( 'Search Before / After', 'custom-manhattan-laser-theme' ),
		'not_found'          => __( 'No cases found.', 'custom-manhattan-laser-theme' ),
		'not_found_in_trash' => __( 'No cases found in Trash.', 'custom-manhattan-laser-theme' ),
	);
	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'exclude_from_search' => true,
		'query_var'           => true,
		'rewrite'             => array(
			'slug'       => 'before-after',
			'with_front' => false,
		),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-images-alt2',
		'supports'            => array( 'title', 'page-attributes' ),
	);
	register_post_type( 'before_after', $args );
}
add_action( 'init', 'custom_manhattan_laser_register_before_after_post_type' );

/**
 * Taxonomy: Before / After Category
 */
function custom_manhattan_laser_register_before_after_category() {
	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'custom-manhattan-laser-theme' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'custom-manhattan-laser-theme' ),
		'search_items'      => __( 'Search Categories', 'custom-manhattan-laser-theme' ),
		'all_items'         => __( 'All Categories', 'custom-manhattan-laser-theme' ),
		'edit_item'         => __( 'Edit Category', 'custom-manhattan-laser-theme' ),
		'update_item'       => __( 'Update Category', 'custom-manhattan-laser-theme' ),
		'add_new_item'      => __( 'Add New Category', 'custom-manhattan-laser-theme' ),
		'new_item_name'     => __( 'New Category Name', 'custom-manhattan-laser-theme' ),
		'menu_name'         => __( 'Category', 'custom-manhattan-laser-theme' ),
	);
	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'rewrite'           => false,
	);
	register_taxonomy( 'before_after_category', array( 'before_after' ), $args );
}
add_action( 'init', 'custom_manhattan_laser_register_before_after_category' );

/**
 * Before/After: одиночные записи не отображаются (404), не индексируются.
 */
function custom_manhattan_laser_block_before_after_singular() {
	if ( ! is_singular( 'before_after' ) ) {
		return;
	}
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}
add_action( 'template_redirect', 'custom_manhattan_laser_block_before_after_singular', 0 );

/**
 * Один раз сбросить правила ЧПУ после включения архива CPT (обновить при смене slug).
 */
function custom_manhattan_laser_flush_before_after_rewrite_once() {
	if ( '1' === get_option( 'ml_before_after_rewrite_v1', '' ) ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'ml_before_after_rewrite_v1', '1', true );
}
add_action( 'init', 'custom_manhattan_laser_flush_before_after_rewrite_once', 99 );

/**
 * Архив Before/After: порядок и размер страницы.
 *
 * @param WP_Query $query Query.
 */
function custom_manhattan_laser_before_after_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'before_after' ) ) {
		$query->set( 'posts_per_page', 12 );
		$query->set( 'orderby', 'menu_order date' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'custom_manhattan_laser_before_after_archive_query' );

/**
 * URL архива кейсов Before/After (для меню и ссылок).
 *
 * @return string
 */
function custom_manhattan_laser_get_before_after_archive_url() {
	$url = get_post_type_archive_link( 'before_after' );
	return is_string( $url ) && '' !== $url ? $url : home_url( '/' );
}

/**
 * Слайды для слайдера архива Before/After (фильтр по CPT treatment через meta before_after_treatment_id).
 *
 * @param int $treatment_id 0 — все кейсы.
 * @return array<int, array{desc:string,before_url:string,after_url:string}>
 */
function custom_manhattan_laser_get_before_after_archive_slide_rows( $treatment_id ) {
	$treatment_id = absint( $treatment_id );
	$args         = array(
		'post_type'      => 'before_after',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	);
	if ( $treatment_id > 0 ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'before_after_treatment_id',
				'value'   => (string) $treatment_id,
				'compare' => '=',
			),
		);
	}
	$q = new WP_Query( $args );
	$slides = array();
	foreach ( $q->posts as $post_obj ) {
		if ( ! $post_obj instanceof WP_Post ) {
			continue;
		}
		$pid       = (int) $post_obj->ID;
		$before_id = (int) get_post_meta( $pid, 'before_after_before_photo', true );
		$after_id  = (int) get_post_meta( $pid, 'before_after_after_photo', true );
		$before_url = $before_id ? wp_get_attachment_image_url( $before_id, 'large' ) : '';
		$after_url  = $after_id ? wp_get_attachment_image_url( $after_id, 'large' ) : '';
		if ( ! $before_url || ! $after_url ) {
			continue;
		}
		$slides[] = array(
			'desc'       => (string) get_post_meta( $pid, 'before_after_short_desc', true ),
			'before_url' => $before_url,
			'after_url'  => $after_url,
		);
	}
	return $slides;
}

/**
 * HTML слайдера архива (swiper + стрелки).
 *
 * @param array $slides Результат custom_manhattan_laser_get_before_after_archive_slide_rows().
 * @return string
 */
function custom_manhattan_laser_render_before_after_archive_slider_inner( $slides ) {
	if ( ! is_array( $slides ) ) {
		$slides = array();
	}
	$img_resize = get_template_directory_uri() . '/img/resize-input.svg';
	$count      = count( $slides );
	ob_start();
	if ( $count < 1 ) {
		echo '<p class="font-sans text-[15px] text-[#f5f5f0]/60 px-2">' . esc_html__( 'No cases for this filter yet.', 'custom-manhattan-laser-theme' ) . '</p>';
	} else {
		echo '<div class="swiper before-after-swiper before-after-swiper--archive !overflow-visible md:!overflow-hidden">';
		echo '<div class="swiper-wrapper">';
		foreach ( $slides as $slide ) {
			if ( ! is_array( $slide ) ) {
				continue;
			}
			$before_url = isset( $slide['before_url'] ) ? $slide['before_url'] : '';
			$after_url  = isset( $slide['after_url'] ) ? $slide['after_url'] : '';
			$desc       = isset( $slide['desc'] ) ? $slide['desc'] : '';
			if ( ! $before_url || ! $after_url ) {
				continue;
			}
			echo '<div class="swiper-slide !overflow-visible px-2 md:px-3 relative">';
			echo '<div class="absolute top-0 left-0 w-full h-full z-[5] bg-[#1E130C33]"></div>';
			echo '<div class="before-after-compare relative w-full h-[250px] md:h-[428px] bg-[#2a221d] rounded-sm overflow-visible" style="--split: 50;">';
			echo '<img src="' . esc_url( $after_url ) . '" alt="" class="absolute inset-0 z-0 h-full w-full object-cover object-center" loading="lazy">';
			echo '<div class="before-after-compare__before-layer absolute left-0 top-0 z-[1] h-full max-w-full overflow-hidden pointer-events-none" style="width: calc(var(--split, 50) * 1%);">';
			echo '<img src="' . esc_url( $before_url ) . '" alt="" class="before-after-compare__before-img absolute left-0 top-0 h-full max-w-none object-cover object-left" style="width: calc(10000% / clamp(1, var(--split, 50), 100));" loading="lazy">';
			echo '</div>';
			echo '<div class="before-after-vline pointer-events-none absolute top-0 bottom-0 z-[5] w-[2px] -translate-x-1/2 bg-[#F4EFE8]/90" style="left: calc(var(--split, 50) * 1%);" aria-hidden="true"></div>';
			echo '<img src="' . esc_url( $img_resize ) . '" alt="" class="before-after-knob pointer-events-none absolute top-1/2 z-[6] h-[33px] w-[33px] -translate-x-1/2 -translate-y-1/2 select-none" style="left: calc(var(--split, 50) * 1%);" width="33" height="33" draggable="false">';
			echo '<div class="before-after-compare__hit absolute inset-0 z-10 touch-none select-none outline-none" role="slider" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" aria-label="' . esc_attr__( 'Before and after comparison', 'custom-manhattan-laser-theme' ) . '" tabindex="0"></div>';
			echo '<div class="before-after-compare__label absolute bottom-7 left-8 z-[15] pointer-events-none">';
			echo '<div class="flex items-center gap-2 relative">';
			echo '<span class="w-[2px] bg-[#F4EFE8] shrink-0 absolute left-[-8px] top-[1.5%] h-[103%]"></span>';
			echo '<div><p class="text-[14px] md:text-[15px] text-[#F4EFE8] max-w-[300px]">' . esc_html( $desc ) . '</p></div>';
			echo '</div></div></div></div>';
		}
		echo '</div></div>';
		if ( $count > 1 ) {
			echo '<div class="before-after-section__nav gap-6 relative mt-11 justify-center flex">';
			echo '<button type="button" class="before-after-prev flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="' . esc_attr__( 'Previous', 'custom-manhattan-laser-theme' ) . '">';
			echo '<img src="' . esc_url( get_template_directory_uri() . '/img/arrow-left.svg' ) . '" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">';
			echo '</button><button type="button" class="before-after-next flex items-center justify-center text-[#F4EFE8] hover:opacity-80 transition-opacity" aria-label="' . esc_attr__( 'Next', 'custom-manhattan-laser-theme' ) . '">';
			echo '<img src="' . esc_url( get_template_directory_uri() . '/img/arrow-right.svg' ) . '" alt="" class="h-2 w-14 md:h-2.5 md:w-16" width="28" height="8">';
			echo '</button></div>';
		}
	}
	return (string) ob_get_clean();
}

/**
 * AJAX: слайдер архива Before/After по treatment_id.
 */
function custom_manhattan_laser_ajax_before_after_archive_slider() {
	check_ajax_referer( 'ml_before_after_archive', 'nonce' );
	$treatment_id = isset( $_POST['treatment_id'] ) ? absint( wp_unslash( $_POST['treatment_id'] ) ) : 0;
	$slides       = custom_manhattan_laser_get_before_after_archive_slide_rows( $treatment_id );
	$html         = custom_manhattan_laser_render_before_after_archive_slider_inner( $slides );
	wp_send_json_success(
		array(
			'html'         => $html,
			'slide_count'  => count( $slides ),
			'treatment_id' => $treatment_id,
		)
	);
}
add_action( 'wp_ajax_ml_before_after_archive_slider', 'custom_manhattan_laser_ajax_before_after_archive_slider' );
add_action( 'wp_ajax_nopriv_ml_before_after_archive_slider', 'custom_manhattan_laser_ajax_before_after_archive_slider' );

/**
 * Before/After meta: before_photo, after_photo (attachment ID), short_desc, case_number, subtitle
 */
function custom_manhattan_laser_register_before_after_meta() {
	$fields = array(
		'before_after_before_photo' => 'integer',
		'before_after_after_photo'  => 'integer',
		'before_after_short_desc'   => 'string',
		'before_after_case_number' => 'string',
		'before_after_subtitle'    => 'string',
		'before_after_treatment_category_id' => 'integer',
		'before_after_treatment_id' => 'integer',
	);
	foreach ( $fields as $key => $type ) {
		register_post_meta( 'before_after', $key, array(
			'type'          => $type,
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
		) );
	}
}
add_action( 'init', 'custom_manhattan_laser_register_before_after_meta' );

/**
 * Meta box: Before/After — фото до, фото после, короткое описание, номер кейса, подзаголовок
 */
function custom_manhattan_laser_before_after_meta_box() {
	add_meta_box(
		'before_after_fields',
		__( 'Before / After data', 'custom-manhattan-laser-theme' ),
		'custom_manhattan_laser_before_after_meta_box_cb',
		'before_after',
		'normal',
		'high'
	);
}

function custom_manhattan_laser_before_after_meta_box_cb( $post ) {
	wp_nonce_field( 'before_after_fields_nonce', 'before_after_fields_nonce' );
	$before = (int) get_post_meta( $post->ID, 'before_after_before_photo', true );
	$after  = (int) get_post_meta( $post->ID, 'before_after_after_photo', true );
	$desc   = get_post_meta( $post->ID, 'before_after_short_desc', true );
	$treatment_category_id = (int) get_post_meta( $post->ID, 'before_after_treatment_category_id', true );
	$treatment_id = (int) get_post_meta( $post->ID, 'before_after_treatment_id', true );
	$before_url = $before ? wp_get_attachment_image_url( $before, 'medium' ) : '';
	$after_url  = $after ? wp_get_attachment_image_url( $after, 'medium' ) : '';
	$treatments = get_posts(
		array(
			'post_type'      => 'treatment',
			'posts_per_page' => 500,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<p>
		<strong><?php esc_html_e( 'Treatment (for filtering on single treatment page)', 'custom-manhattan-laser-theme' ); ?></strong><br>
		<select name="before_after_treatment_id" id="before_after_treatment_id" class="widefat">
			<option value="0"><?php esc_html_e( '— Select treatment —', 'custom-manhattan-laser-theme' ); ?></option>
			<?php foreach ( $treatments as $treatment_post ) : ?>
				<option value="<?php echo (int) $treatment_post->ID; ?>"<?php selected( $treatment_id, (int) $treatment_post->ID ); ?>>
					<?php echo esc_html( get_the_title( $treatment_post ) ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<strong><?php esc_html_e( 'Treatment category (for filtering on category page)', 'custom-manhattan-laser-theme' ); ?></strong><br>
		<?php
		wp_dropdown_categories(
			array(
				'taxonomy'         => 'treatment_category',
				'hide_empty'       => 0,
				'name'             => 'before_after_treatment_category_id',
				'id'               => 'before_after_treatment_category_id',
				'selected'         => $treatment_category_id,
				'show_option_none' => __( '— Select category —', 'custom-manhattan-laser-theme' ),
				'option_none_value' => 0,
				'hierarchical'     => 1,
			)
		);
		?>
	</p>
	<p>
		<strong><?php esc_html_e( 'Before photo', 'custom-manhattan-laser-theme' ); ?></strong><br>
		<input type="hidden" id="before_after_before_photo" name="before_after_before_photo" value="<?php echo (int) $before; ?>">
		<button type="button" class="button before-after-upload" data-target="before_after_before_photo" data-preview="before_after_before_preview"><?php esc_html_e( 'Select image', 'custom-manhattan-laser-theme' ); ?></button>
		<span id="before_after_before_preview"><?php if ( $before_url ) { echo '<img src="' . esc_url( $before_url ) . '" style="max-width:200px;height:auto;display:block;margin-top:8px;">'; } ?></span>
	</p>
	<p>
		<strong><?php esc_html_e( 'After photo', 'custom-manhattan-laser-theme' ); ?></strong><br>
		<input type="hidden" id="before_after_after_photo" name="before_after_after_photo" value="<?php echo (int) $after; ?>">
		<button type="button" class="button before-after-upload" data-target="before_after_after_photo" data-preview="before_after_after_preview"><?php esc_html_e( 'Select image', 'custom-manhattan-laser-theme' ); ?></button>
		<span id="before_after_after_preview"><?php if ( $after_url ) { echo '<img src="' . esc_url( $after_url ) . '" style="max-width:200px;height:auto;display:block;margin-top:8px;">'; } ?></span>
	</p>
	<p>
		<label class="screen-reader-text" for="before_after_short_desc"><?php esc_html_e( 'Short description', 'custom-manhattan-laser-theme' ); ?></label>
		<strong><?php esc_html_e( 'Short description', 'custom-manhattan-laser-theme' ); ?></strong><br>
		<textarea id="before_after_short_desc" name="before_after_short_desc" class="widefat" rows="3"><?php echo esc_textarea( $desc ); ?></textarea>
	</p>
	<?php
}

function custom_manhattan_laser_save_before_after_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['before_after_fields_nonce'] ) || ! wp_verify_nonce( $_POST['before_after_fields_nonce'], 'before_after_fields_nonce' ) ) {
		return;
	}
	$keys = array( 'before_after_before_photo', 'before_after_after_photo', 'before_after_short_desc', 'before_after_case_number', 'before_after_subtitle', 'before_after_treatment_category_id', 'before_after_treatment_id' );
	foreach ( $keys as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			if ( in_array( $key, array( 'before_after_before_photo', 'before_after_after_photo' ), true ) ) {
				update_post_meta( $post_id, $key, (int) $_POST[ $key ] );
			} elseif ( 'before_after_treatment_category_id' === $key || 'before_after_treatment_id' === $key ) {
				update_post_meta( $post_id, $key, (int) $_POST[ $key ] );
			} elseif ( $key === 'before_after_short_desc' ) {
				update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
			} else {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}
}
add_action( 'add_meta_boxes', 'custom_manhattan_laser_before_after_meta_box' );
add_action( 'save_post_before_after', 'custom_manhattan_laser_save_before_after_meta' );

/**
 * Admin: enqueue media for Before/After image upload
 */
function custom_manhattan_laser_before_after_admin_scripts( $hook ) {
	global $post_type;
	if ( $hook !== 'post.php' && $hook !== 'post-new.php' || $post_type !== 'before_after' ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_script( 'jquery', "
		jQuery(function($){
			$('.before-after-upload').on('click', function(){
				var target = $(this).data('target');
				var preview = $(this).data('preview');
				var frame = wp.media({ library: { type: 'image' }, multiple: false });
				frame.on('select', function(){
					var att = frame.state().get('selection').first().toJSON();
					$('#' + target).val(att.id);
					$('#' + preview).html('<img src=\"' + (att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url) + '\" style=\"max-width:200px;height:auto;display:block;margin-top:8px;\">');
				});
				frame.open();
			});
		});
	" );
}
add_action( 'admin_enqueue_scripts', 'custom_manhattan_laser_before_after_admin_scripts' );

