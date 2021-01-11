<?php

// Check for Timber
if ( ! class_exists( 'Timber' ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
    });

    add_filter('template_include', function($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
    });

    return;
}

// Define paths to Twig templates

Timber::$dirname = array(
    'views',
    'templates'
);

// Define TimeberSite Child Class
class BootsmoothSite extends TimberSite {

    function __construct() {
        add_theme_support( 'post-formats' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
        add_filter( 'timber_context', array( $this, 'add_to_context' ) );
        add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        parent::__construct();
    }

    function register_post_types() {
        //this is where you can register custom post types
    }

    function register_taxonomies() {
        //this is where you can register custom taxonomies
    }

    // register custom context variables
    function add_to_context( $context ) {
        $context['menu'] = new TimberMenu();
        $context['site'] = $this;
        $context['header'] = Timber::get_widgets( 'header' );
        $context['copy_right_area'] = Timber::get_widgets( 'sidebar-5' );
        $context['right_area'] = Timber::get_widgets( 'sidebar-6' );
        return $context;
    }

    function add_to_twig( $twig ) {
        $twig->addExtension( new Twig_Extension_StringLoader() );
        return $twig;
    }
}
new BootsmoothSite();

add_action( 'after_setup_theme', function() {
    add_theme_support( 'woocommerce' );
} );

add_filter( 'woocommerce_cart_needs_shipping', 'filter_function_disable_shipping' );
function filter_function_disable_shipping( $needs_shipping ){
    return false;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    return $fields;
}

function timber_set_product( $post ) {
    global $product;

    if ( is_woocommerce() ) {
        $product = wc_get_product( $post->ID );
    }
}

// include shortcodes
include 'shortcodes.php';

// Add menu shortcode - [menu name="menu_name"]
function print_menu_shortcode($atts, $content = null) {
    $name = 'none';
    extract(shortcode_atts(array( 'name' => null, 'class' => null ), $atts));

    $defaults = array(
        'container'       => 'ul',
        'container_class' => '',
        'container_id'    => '',
        'menu_id'         => '',
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<nav class="uk-navbar-container" uk-navbar><div class="uk-navbar-center"><ul id="%1$s" class="%2$s"  >%3$s</ul></div></nav>',
        'depth'           => 3,
        'menu' 			  => $name,
        'menu_class' 	  => 'uk-navbar-nav uk-hidden-small uk-navbar-flip',
        'echo' 			  => false,
        'walker'          => new walkerNavMenu()
    );
    return wp_nav_menu( $defaults );
}

add_shortcode('menu', 'print_menu_shortcode');

// Add menu shortcode - [menu name="menu_name"]
function print_news_shortcode($atts, $content = null) {
    ob_start();
    get_template_part('posts');
    return ob_get_clean();
}

add_shortcode('news', 'print_news_shortcode');

function register_theme_features() {
    // Register widget areas
    register_sidebar( array(
        'name'          => __( 'HEADER', 'custom' ),
        'id'            => 'header',
        'description'   => __( 'Header 1200px', 'custom' ),
    ));

    register_sidebar( array(
        'name'          => __( 'Copyright area', 'custom' ),
        'id'            => 'sidebar-5',
        'description'   => __( 'Copyright area', 'custom' ),
    ));

    register_sidebar( array(
        'name'          => __( 'Right Canvas', 'custom' ),
        'id'            => 'sidebar-6',
        'description'   => __( 'Right Canvas', 'custom' ),
    ));

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu' ),
            'secondary' => __( 'Secondary Menu' )
        )
    );
}

$args = array(
    'default-color' => '#f8f8f8'
);

add_post_type_support( 'page', 'excerpt' );

add_theme_support( 'custom-background', $args );

add_action( 'init', 'register_theme_features' );

show_admin_bar(false);
function custom_setup(){
    load_theme_textdomain( 'custom', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'custom_setup' );

function custom_javascript_detection() {
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'custom_javascript_detection', 0 );
add_action( 'wp_footer', 'custom_javascript_detection', 0 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

require get_template_directory() . '/inc/template-tags.php';

require get_template_directory() . '/inc/customizer.php';

require get_template_directory() . '/inc/walker-nav-menu.php';


// Load the theme stylesheets
function theme_styles(){
    wp_register_style( 'uikit', get_template_directory_uri() . '/dist/css/uikit.min.css');
    wp_enqueue_style('uikit');
}

function theme_script() {
    wp_register_script( 'jquery-2.1.4', get_template_directory_uri() . '/dist/js/jquery-2.1.4.min.js' );
    wp_register_script( 'uikit', get_template_directory_uri() . '/dist/js/uikit.min.js' );
    wp_register_script( 'uikit-icon', get_template_directory_uri() . '/dist/js/uikit-icons.min.js' );
    wp_enqueue_script('jquery-2.1.4');
    wp_enqueue_script('uikit');

    wp_enqueue_script('imagesloaded', get_template_directory_uri() . '/dist/js/imagesloaded.pkgd.min.js', array(),1, true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/dist/js/custom.js', array(),1.2, true);
    wp_enqueue_script('uikit-icon');
}

add_action('wp_enqueue_scripts', 'theme_styles');
add_action('wp_enqueue_scripts', 'theme_script');

function theme_customize_register( $wp_customize ) {
    // Text color
    $wp_customize->add_setting( 'text_color', array(
        'default'   => '',
        'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Text color', 'theme' ),
    ) ) );

    // Link color
    $wp_customize->add_setting( 'link_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Link color', 'theme' ),
    ) ) );

    // Accent color
    $wp_customize->add_setting( 'accent_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Accent color', 'theme' ),
    ) ) );

    // Border color
    $wp_customize->add_setting( 'border_color', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'border_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Border color', 'theme' ),
    ) ) );

    // Header background
    $wp_customize->add_setting( 'sidebar_background', array(
        'default'   => '#ffffff',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_background', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Header background', 'theme' ),
    ) ) );

    // Footer background
    $wp_customize->add_setting( 'footer_background', array(
        'default'   => '#ffffff',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Footer background', 'theme' ),
    ) ) );

    // Menu text color
    $wp_customize->add_setting( 'menu_text_color', array(
        'default'   => '#313940',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_text_color', array(
        'section' => 'colors',
        'label'   => esc_html__( 'Menu text color', 'theme' ),
    ) ) );
}

add_action( 'customize_register', 'theme_customize_register' );

function theme_get_customizer_css() {
    ob_start();
    if (get_header_image()){
        ?>
        header{
        background-image:url(<?php header_image(); ?>)
        }
        <?php
    }

    $text_color = get_theme_mod( 'text_color', '' );
    if ( ! empty( $text_color ) ) {
        ?>
        body *:not(a *) {
        color: <?php echo $text_color; ?>;
        }
        h1 *, h2 *, h3 *, h4 *, h5 *, h6 *{
        color: <?php echo $text_color; ?>;
        }

        .contact-article *, uk-logo>a{
        color: <?php echo $text_color; ?>;
        }
        <?php
    }

    $link_color = get_theme_mod( 'link_color', '' );
    if ( ! empty( $link_color ) ) {
        ?>
        #setsticky .uk-button-text::before{
        border-bottom-color: <?php echo $link_color; ?>!important;
        }
        #setsticky .current-menu-item a{
        border-bottom-color: <?php echo $link_color; ?>!important;
        }
        <?php
    }


    $border_color = get_theme_mod( 'border_color', '' );
    if ( ! empty( $border_color ) ) {
        ?>
        input,
        textarea {
        border-color: <?php echo $border_color; ?>;
        }
        <?php
    }
    ?>
    <?php if ( !get_background_image() ) : ?>
        main{
        background-color: #f8f8f8;
        }
    <?php else: ?>
        #wrapper{
        background-image: url(<?php background_image() ?>);
        }
    <?php endif; ?>

    <?php
    $accent_color = get_theme_mod( 'accent_color', '' );
    if ( ! empty( $accent_color ) ) { ?>

        <?php if ( !get_background_image() ) : ?>
            main{
            background-color: #ffffff;
            }
        <?php else: ?>
            main{
            background-color: red;
            }
        <?php endif; ?>
        .date, .accent{
        background-color: <?php echo $accent_color; ?>;
        }
        <?php
    }

    $footer_background = get_theme_mod( 'footer_background', '#313940' );
    if ( ! empty( $footer_background ) ) {
        ?>
        footer {
        background-color: <?php echo $footer_background; ?>;
        }
        <?php
    }

    $menu_text_color = get_theme_mod( 'menu_text_color', '#3b3386' );
    if ( ! empty( $menu_text_color ) ) { ?>
        .uk-light .uk-navbar-nav>li>a, .uk-navbar-nav>li>a, .uk-nav-default>li>a, .uk-navbar-nav li a *, .uk-nav-default>li>a:after, .uk-nav-sub a  {
        color: <?php echo $menu_text_color; ?>;
        }
    <?php }

    $css = ob_get_clean();
    return $css;
}

// Modify our styles registration like so:

function theme_enqueue_styles() {
    wp_enqueue_style( 'theme-styles', get_stylesheet_uri(), array(), '1.0.1' );
    $custom_css = theme_get_customizer_css();
    wp_add_inline_style( 'theme-styles', $custom_css );
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
