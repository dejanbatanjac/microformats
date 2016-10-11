<?php
/**
 * microformats functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package microformats
 *
 *
 *
 * */

if ( ! function_exists( 'microformats_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
    function microformats_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on microformats, use a find and replace
     * to change 'microformats' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'microformats', get_template_directory() . '/languages' );

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
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'microformats' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'microformats_custom_background_args', array(
        'default-color' => 'fff',
        // no default image need to be set
        'default-image' => '',
    ) ) );

    add_theme_support( 'heading-textcolor', apply_filters( 'microformats_heading_style', array(
        'default-color' => '444',
    ) ) );


}
endif;
add_action( 'after_setup_theme', 'microformats_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function microformats_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'microformats_content_width', 640 );
}
add_action( 'after_setup_theme', 'microformats_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function microformats_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Widgets', 'microformats' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'microformats' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'microformats_widgets_init' );



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * Enqueue scripts and styles.
 */
function microformats_scripts() {
    // required style.css
    wp_enqueue_style( 'microformats-style', get_stylesheet_uri() );

    wp_enqueue_style( 'microformats-fonts', microformats_fonts_url(), array(), null );

    wp_enqueue_script( 'microformats-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20160426', true );

    // _s fix
    wp_enqueue_script( 'microformats-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160426', true );

    // responsive jQuery
    wp_enqueue_script( 'microformats-responsive', get_template_directory_uri() . '/js/responsive.js', array('jquery'), '20160427', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // allways need this because of responsible script I use
    wp_enqueue_script( 'jquery'); 

}

add_action( 'wp_enqueue_scripts', 'microformats_scripts' );



/**
 * [microformats_add_fonts_css description]
 * @return [type] [used for adding dynamic styles]
 */
function microformats_add_dynamic_css() {

    wp_enqueue_style( 'microformats-style', get_stylesheet_uri() );

    $hfont = get_theme_mod( 'header_font_type' ); 
    $bfont = get_theme_mod( 'body_font_type' );
    $hcolor = get_theme_mod( 'headers_textcolor' );

    $custom_css = "h1,h2,h3,h4,h5,h6 { font-family: '{$hfont}', sans-serif !important; }";
    $custom_css .= "body, button, input, select, textarea { font-family: '{$bfont}', serif !important;}";
    $custom_css .= "h1, h2, h3, h4, h5, h6, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a{ color:{$hcolor} !important}";

    wp_add_inline_style( 'microformats-style', $custom_css );

}
add_action( 'wp_enqueue_scripts', 'microformats_add_dynamic_css' );


if ( ! function_exists( 'microformats_fonts_url' ) ) :
/**
 * Register Google fonts
 */
function microformats_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    
    $hfont = get_theme_mod( 'header_font_type' );
    $bfont = get_theme_mod( 'body_font_type' );

    if ( 'off' !== _x( 'on', $hfont .'font: on or off', 'microformats' ) ) {
        $fonts[] = $hfont .':400italic,700italic,400,700';
    }
    
    if ( 'off' !== _x( 'on', $bfont .'font: on or off', 'microformats' ) ) {
        $fonts[] = $bfont .':400italic,700italic,400,700';
    }

    /*
     * Translators: To add an additional character subset specific to your language,
     * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
     */
    $subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'microformats' );

    if ( 'cyrillic' == $subset ) {
        $subsets .= ',cyrillic,cyrillic-ext';
    } elseif ( 'greek' == $subset ) {
        $subsets .= ',greek,greek-ext';
    } elseif ( 'devanagari' == $subset ) {
        $subsets .= ',devanagari';
    } elseif ( 'vietnamese' == $subset ) {
        $subsets .= ',vietnamese';
    }

    if ( $fonts ) {
        $fonts_url = add_query_arg( array(
            'family' => urlencode( implode( '|', $fonts ) ),
            'subset' => urlencode( $subsets ),
        ), 'https://fonts.googleapis.com/css' );
    }

    return $fonts_url;
}
endif;


