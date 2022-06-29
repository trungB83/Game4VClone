<?php
/**
 * Ltheme functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Lt_Theme
 * @since Ltheme 1.0
 */
/**
 * Ltheme only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}
if ( ! function_exists( 'ltheme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * Create your own ltheme_setup() function to override in a child theme.
	 *
	 * @since Ltheme 1.0
	 */
	function ltheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/ltheme
		 * If you're building a theme based on Ltheme, use a find and replace
		 * to change 'ltheme' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'ltheme' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for custom logo.
		 *
		 *  @since Ltheme 1.2
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 240,
				'width'       => 240,
				'flex-height' => true,
			)
		);
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'ltheme' ),
				'social'  => __( 'Social Links Menu', 'ltheme' ),
			)
		);
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			)
		);
		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style( array( 'css/editor-style.css', ltheme_fonts_url() ) );
		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );
		// Load default block styles.
		add_theme_support( 'wp-block-styles' );
		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );
		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif; // ltheme_setup
add_action( 'after_setup_theme', 'ltheme_setup' );
/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Ltheme 1.0
 */
function ltheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ltheme_content_width', 840 );
}
add_action( 'after_setup_theme', 'ltheme_content_width', 0 );
/**
 * Add preconnect for Google Fonts.
 *
 * @since Ltheme 1.6
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function ltheme_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'ltheme-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ltheme_resource_hints', 10, 2 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Ltheme 1.0
 */
function ltheme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Right Sidebar', 'ltheme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'ltheme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar( array(
		    'name'          => __( 'Blog Left Sidebar', 'themename' ),
		    'id'            => 'sidebar-left-blog',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'  => '</aside>',
		    'before_title'  => '<h3 class="widget-title">',
		    'after_title'   => '</h3>',
	  )
	);
	register_sidebar(
		array(
			'name'          => __( 'Woocommerce Right Sidebar', 'ltheme' ),
			'id'            => 'sidebar-right-ws',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'ltheme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar( array(
		    'name'          => __( 'Woocommerce Left Sidebar', 'themename' ),
		    'id'            => 'sidebar-left-ws',
		    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		    'after_widget'  => '</aside>',
		    'before_title'  => '<h3 class="widget-title">',
		    'after_title'   => '</h3>',
	  )
	);
}
add_action( 'widgets_init', 'ltheme_widgets_init' );

if ( ! function_exists( 'ltheme_fonts_url' ) ) :
	/**
	 * Register Google fonts for Ltheme.
	 *
	 * Create your own ltheme_fonts_url() function to override in a child theme.
	 *
	 * @since Ltheme 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function ltheme_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';
		/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'ltheme' ) ) {
			$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
		}
		/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'ltheme' ) ) {
			$fonts[] = 'Montserrat:400,700';
		}
		/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'ltheme' ) ) {
			$fonts[] = 'Inconsolata:400';
		}
		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => urlencode( implode( '|', $fonts ) ),
					'subset' => urlencode( $subsets ),
				),
				'https://fonts.googleapis.com/css'
			);
		}
		return $fonts_url;
	}
endif;
/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Ltheme 1.0
 */
function ltheme_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'ltheme_javascript_detection', 0 );
/**
 * Enqueues scripts and styles.
 *
 * @since Ltheme 1.0
 */
function ltheme_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'ltheme-fonts', ltheme_fonts_url(), array(), null );
	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );
	// Theme stylesheet.
	wp_enqueue_style( 'ltheme-style', get_stylesheet_uri() );
	// Theme block stylesheet.
	wp_enqueue_style( 'ltheme-block-style', get_template_directory_uri() . '/css/blocks.css', array( 'ltheme-style' ), '20181230' );
	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'ltheme-ie', get_template_directory_uri() . '/css/ie.css', array( 'ltheme-style' ), '20160816' );
	wp_style_add_data( 'ltheme-ie', 'conditional', 'lt IE 10' );
	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'ltheme-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'ltheme-style' ), '20160816' );
	wp_style_add_data( 'ltheme-ie8', 'conditional', 'lt IE 9' );
	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'ltheme-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'ltheme-style' ), '20160816' );
	wp_style_add_data( 'ltheme-ie7', 'conditional', 'lt IE 8' );
	// Style woocommerce stylesheet.
	wp_enqueue_style( 'ltheme-woocommerce', get_template_directory_uri() . '/css/woocommerce.css', array( 'ltheme-style' ), '20160816' );
	// Style FontAwesome stylesheet.
	wp_enqueue_style( 'ltheme-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array( 'ltheme-style' ), '20160816' );
	// Style custom stylesheet.
	wp_enqueue_style( 'ltheme-custom', get_template_directory_uri() . '/css/custom.css', array( 'ltheme-style' ), '20160816' );
	// Load the html5 shiv.
	wp_enqueue_script( 'ltheme-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'ltheme-html5', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'ltheme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'ltheme-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}
	wp_enqueue_script( 'jquery-typed', get_template_directory_uri() . '/js/typed.js', array( 'jquery' ), '20181230', true );
	wp_enqueue_script( 'jquery-min', get_template_directory_uri() . '/js/jquery-2.2.4.min.js', array( 'jquery' ), '20181230', true );
	wp_enqueue_script( 'ltheme-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20181230', true );
	wp_enqueue_script( 'ltheme-jquery', get_template_directory_uri() . '/js/jquery.min.js', array( 'jquery' ), '20181230', true  );
	wp_enqueue_script( 'ltheme-custom', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), '20181230', true );
	wp_localize_script(
		'ltheme-script',
		'screenReaderText',
		array(
			'expand'   => __( 'expand child menu', 'ltheme' ),
			'collapse' => __( 'collapse child menu', 'ltheme' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ltheme_scripts' );

/**
 * Enqueue styles for the block-based editor.
 *
 * @since Ltheme 1.6
 */
function ltheme_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'ltheme-block-editor-style', get_template_directory_uri() . '/css/editor-blocks.css', array(), '20181230' );
	// Add custom fonts.
	wp_enqueue_style( 'ltheme-fonts', ltheme_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'ltheme_block_editor_styles' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @since Ltheme 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function ltheme_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	return $classes;
}
add_filter( 'body_class', 'ltheme_body_classes' );
/**
 * Converts a HEX value to RGB.
 *
 * @since Ltheme 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Ltheme 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function ltheme_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 840 <= $width ) {
		$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
	}
	if ( 'page' === get_post_type() ) {
		if ( 840 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	} else {
		if ( 840 > $width && 600 <= $width ) {
			$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		} elseif ( 600 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	}
	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'ltheme_content_image_sizes_attr', 10, 2 );
/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Ltheme 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function ltheme_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		} else {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
		}
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ltheme_post_thumbnail_sizes_attr', 10, 3 );
/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Ltheme 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function ltheme_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'ltheme_widget_tag_cloud_args' );

/**
 * Add sidebar left 
 */
function wpb_widgets_init() {

	register_sidebar( array(

		 'name' => 'Top Bottom',

		 'id' => 'custom-header-widget',

		 'before_widget' => '<div class="right-menu">',

		 'after_widget' => '</div>',

		 'before_title' => '<h2 class="title-widget">',

		 'after_title' => '</h2>',
	 ) );
	register_sidebar( array(

		 'name' => 'Header Top Left',

		 'id' => 'header-top-left',

		 'before_widget' => '<div class="head-left-custom"">',

		 'after_widget' => '</div>',

		 'before_title' => '<h2 class="title-widget">',

		 'after_title' => '</h2>',
	 ) );
	register_sidebar( array(

		 'name' => 'Header Top Right',

		 'id' => 'header-top-right',

		 'before_widget' => '<div class="head-left-custom">',

		 'after_widget' => '</div>',

		 'before_title' => '<h2 class="title-widget">',

		 'after_title' => '</h2>',
	 ) );
	}
add_action( 'widgets_init', 'wpb_widgets_init' );
// Hook Bottom
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Bottom 1',
        'id'   => 'footer-1-widget',
        'description'   => 'Footer 1 widget position.',
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'name' => 'Bottom 2',
        'id'   => 'footer-2-widget',
        'description'   => 'Footer 2 widget position.',
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'name' => 'Bottom 3',
        'id'   => 'footer-3-widget',
        'description'   => 'Footer 3 widget position.',
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>'
    ));
    register_sidebar(array(
        'name' => 'Bottom 4',
        'id'   => 'footer-4-widget',
        'description'   => 'Footer 3 widget position.',
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>'
    ));
}
/**
 * Breadcrumb
 */
function dimox_breadcrumbs() {
    $delimiter = '»';
    $home = 'Home'; 
    $before = '<span class="current">'; 
    $after = '</span>';
    if ( !is_home() && !is_front_page() || is_paged() ) {
        echo '<div id="crumbs">';
        global $post;
        $homeLink = get_bloginfo('url');
        echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if ( is_category() ) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            echo $before . single_cat_title('', false) . $after;
        } elseif ( is_day() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $before . get_the_title() . $after;
            }
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && !$post->post_parent ) {
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_search() ) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif ( is_tag() ) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif ( is_author() ) {
            global $author;
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif ( is_404() ) {
            echo $before . 'Error 404' . $after;
        }
        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }
        echo '</div>';
    }
} // end dimox_breadcrumbs()

//Optimize source code => Remove CSS libs
function smartwp_remove_wp_block_library_css(){
 wp_dequeue_style( 'wp-block-library' );
 wp_dequeue_style( 'wp-block-library-theme' );


 wp_dequeue_style('elementor-animations');
 wp_dequeue_style('ltheme-fonts');
 wp_dequeue_style('elementor-icons-fa-solid-css');
}
//Optimize source code => Remove JS libs
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css' );
add_action( 'wp_print_scripts', 'pp_deregister_javascript', 99 );
function pp_deregister_javascript() {
	wp_deregister_script( 'pp-del-avatar-script' );
}