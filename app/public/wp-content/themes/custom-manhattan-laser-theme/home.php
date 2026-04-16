<?php
/**
 * Лента записей, если в «Настройки → Чтение» выбрана отдельная страница для записей.
 * Вёрстка совпадает с шаблоном страницы Blog (blog.php).
 *
 * @package custom-manhattan-laser-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ML_BLOG_ARCHIVE_HOME', true );
require get_template_directory() . '/blog.php';
