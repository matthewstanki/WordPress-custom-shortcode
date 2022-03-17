<?php

/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if (!function_exists('twentytwentytwo_support')) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support()
	{

		// Add support for block styles.
		add_theme_support('wp-block-styles');

		// Enqueue editor styles.
		add_editor_style('style.css');
	}

endif;

add_action('after_setup_theme', 'twentytwentytwo_support');

if (!function_exists('twentytwentytwo_styles')) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles()
	{
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get('Version');

		$version_string = is_string($theme_version) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Add styles inline.
		wp_add_inline_style('twentytwentytwo-style', twentytwentytwo_get_font_face_styles());

		// Enqueue theme stylesheet.
		wp_enqueue_style('twentytwentytwo-style');
	}

endif;

add_action('wp_enqueue_scripts', 'twentytwentytwo_styles');

if (!function_exists('twentytwentytwo_editor_styles')) :

	/**
	 * Enqueue editor styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_editor_styles()
	{

		// Add styles inline.
		wp_add_inline_style('wp-block-library', twentytwentytwo_get_font_face_styles());
	}

endif;

add_action('wp_enqueue_scripts', 'vault_files');

function vault_files()
{
	wp_enqueue_style('vault_main_style', get_stylesheet_uri());
	wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
}

add_action('admin_init', 'twentytwentytwo_editor_styles');


if (!function_exists('twentytwentytwo_get_font_face_styles')) :

	/**
	 * Get font face styles.
	 * Called by functions twentytwentytwo_styles() and twentytwentytwo_editor_styles() above.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return string
	 */
	function twentytwentytwo_get_font_face_styles()
	{

		return "
		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: normal;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri('assets/fonts/SourceSerif4Variable-Roman.ttf.woff2') . "') format('woff2');
		}

		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: italic;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri('assets/fonts/SourceSerif4Variable-Italic.ttf.woff2') . "') format('woff2');
		}
		";
	}

endif;

if (!function_exists('twentytwentytwo_preload_webfonts')) :

	/**
	 * Preloads the main web font to improve performance.
	 *
	 * Only the main web font (font-style: normal) is preloaded here since that font is always relevant (it is used
	 * on every heading, for example). The other font is only needed if there is any applicable content in italic style,
	 * and therefore preloading it would in most cases regress performance when that font would otherwise not be loaded
	 * at all.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_preload_webfonts()
	{
?>
		<link rel="preload" href="<?php echo esc_url(get_theme_file_uri('assets/fonts/SourceSerif4Variable-Roman.ttf.woff2')); ?>" as="font" type="font/woff2" crossorigin>
<?php
	}

endif;

add_action('wp_head', 'twentytwentytwo_preload_webfonts');

// Bugsy Feed Shortcode
function bugsy_feed_shortcode()
{

	$body = wp_remote_retrieve_body(wp_remote_get(
		'https://inwestowaniepro.pl/wp-json/wp/v2/posts?_embed'
	));

	$posts = json_decode($body, true);

	$posts = array_slice($posts, 0, 4);

	$html = "<div class='container bugsy-feed'>";
	$html .= "<div class='row'>";

	foreach ($posts as $post) {
		$html .= "<div class='col-sm-6 col-md-4 col-lg-3 mb-3'>";
		$html .= "<div class='card d-flex'>";
		$html .= "<figure style='background-image:url({$post["_embedded"]["wp:featuredmedia"][0]["source_url"]})'></figure>";
		$html .= "<div class='card-body d-flex flex-column'>";
		$html .= "<h6 class='text-muted small'>" . date("Y-m-d H:i:s", strtotime($post['modified_gmt'])) . "</h6>";
		$html .= "<h4 class='flex-grow-1'>" . $post['title']['rendered'] . "</h4>";
		$html .= "<a class='btn btn-primary btn-sm' href='" .  $post['link'] . "' target='_blank'>Czytaj wiecej</a>";
		$html .= "</div>";
		$html .= "</div>";
		$html .= "</div>";
	}
	$html .= "</div>";
	$html .= "</div>";

	return $html;
}

add_shortcode('bugsy-feed', 'bugsy_feed_shortcode');
