<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Pootlepress_Horizontal_Submenu Class
 *
 * Base class for the Pootlepress Horizontal Submenu.
 *
 * @package WordPress
 * @subpackage Pootlepress_Horizontal_Submenu
 * @category Core
 * @author Pootlepress
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * public $token
 * public $version
 * 
 * - __construct()
 * - add_theme_options()
 * - get_menu_styles()
 * - load_stylesheet()
 * - load_script()
 * - load_localisation()
 * - check_plugin()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 * - get_header()
 * - woo_nav_custom()
 */
class Pootlepress_Horizontal_Submenu {
	public $token = 'pootlepress-horizontal-submenu';
	public $version;
	private $file;

    private $enabled;
    private $bgColor;
    private $fontStyle;
    private $divider;
    private $borderTop;
    private $borderBottom;
    private $borderLeftRight;
    private $hoverColor;

	/**
	 * Constructor.
	 * @param string $file The base file of the plugin.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct ( $file ) {
		$this->file = $file;
		$this->load_plugin_textdomain();
		add_action( 'init', 'check_main_heading', 0 );
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );

		// Add the custom theme options.
		add_filter( 'option_woo_template', array( &$this, 'add_theme_options' ) );

        add_action( 'get_header', array( &$this, 'get_header' ) , 1000);

        add_action( 'wp_enqueue_scripts', array( &$this, 'load_script' ) );

        add_action('wp_head', array(&$this, 'option_css'), 100);

        $this->enabled = get_option('pootlepress-horizontal-submenu-enabled', 'true') == 'true';

        $this->bgColor = get_option('pootlepress-horizontal-submenu-bg-color', '');
        $this->fontStyle = get_option('pootlepress-horizontal-submenu-font',
            array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => '','color' => '#666666')
        );
        $this->divider = get_option('pootlepress-horizontal-submenu-divider',
            array('width' => '0','style' => 'solid','color' => '#dbdbdb')
        );
        $this->borderTop = get_option('pootlepress-horizontal-submenu-border-top',
            array('width' => '0','style' => 'solid','color' => '#dbdbdb')
        );
        $this->borderBottom = get_option('pootlepress-horizontal-submenu-border-bottom',
            array('width' => '0','style' => 'solid','color' => '#dbdbdb')
        );
        $this->borderLeftRight = get_option('pootlepress-horizontal-submenu-border-left-right',
            array('width' => '0','style' => 'solid','color' => '#dbdbdb')
        );
        $this->hoverColor = get_option('pootlepress-horizontal-submenu-hover-color', '');

	} // End __construct()

	/**
	 * Add theme options to the WooFramework.
	 * @access public
	 * @since  1.0.0
	 * @param array $o The array of options, as stored in the database.
	 */
	public function add_theme_options ( $o ) {

		$o[] = array(
				'name' => __( 'Horizontal Sub-menu', 'pp-hs' ),
				'type' => 'subheading'
		);

        $o[] = array(
            'id' => 'pootlepress-horizontal-submenu-enabled',
            'name' => 'Enable Horizontal Submenu',
            'desc' => 'Enable Horizontal Submenu',
            'type' => 'checkbox',
            'std' => 'true'
        );
        $o[] = array(
            'id' => 'pootlepress-horizontal-submenu-bg-color',
            'name' => 'Background Color',
            'desc' => 'Background Color',
            'type' => 'color',
            'std' => ''
        );
        $o[] = array(
            "id" => "pootlepress-horizontal-submenu-font",
            "name" => 'Navigation Font Style',
            "desc" => 'Select typography for navigation.',
            "std" => array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => '','color' => '#666666'),
            "type" => "typography"
        );
        $o[] = array(
            "id" => "pootlepress-horizontal-submenu-divider",
            "name" => __( 'Divider', 'pp-hs' ),
            "desc" => __( 'Specify border properties for the menu items dividers.', 'pp-hs' ),
            "std" => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
            "type" => "border"
        );
        $o[] = array(
            "id" => "pootlepress-horizontal-submenu-border-top",
            "name" => __( 'Border Top', 'pp-hs' ),
            "desc" => __( 'Specify border properties for the navigation.', 'pp-hs' ),
            "std" => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
            "type" => "border"
        );

        $o[] = array(
            "id" => "pootlepress-horizontal-submenu-border-bottom",
            "name" => __( 'Border Bottom', 'pp-hs' ),
            "desc" => __( 'Specify border properties for the navigation.', 'pp-hs' ),
            "std" => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
            "type" => "border"
        );

        $o[] = array(
            "id" => "pootlepress-horizontal-submenu-border-left-right",
            "name" => __( 'Border Left/Right', 'pp-hs' ),
            "desc" => __( 'Specify border properties for the navigation.', 'pp-hs' ),
            "std" => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
            "type" => "border"
        );

        $o[] = array(
            'id' => 'pootlepress-horizontal-submenu-hover-color',
            'name' => __('Hover / Selected Text Color', 'pp-hs'),
            'desc' => __('Hover / Selected Text Color', 'pp-hs'),
            'std' => '',
            'type' => 'color'
        );

        return $o;
	} // End add_theme_options()

    public function option_css() {

        if ($this->enabled == false) {
            return;
        }

        $css = '';

        $css .= "@media only screen and (min-width: 768px) {\n";

        $css .= "#navigation ul.nav { position: static; }\n";
        $css .= "#navigation ul.nav > li { position: static; }\n";

        $css .= "#navigation ul.nav > li > .sub-menu  {\n";
        $css .= "\t" . 'width: 100%;' . "\n";
        $css .= "}\n";

        $css .= "#navigation ul.nav > li > .sub-menu > li {\n";
        $css .= "\t" . 'display: inline-block;' . "\n";
        $css .= "}\n";

        $css .= "#navigation ul.nav > li > .sub-menu > li > a:hover {\n";
        $css .= "\t" . 'text-decoration: none;' . "\n";
        $css .= "}\n";

        // bg color
        if ($this->bgColor != '') {
            $css .= "#navigation ul.nav > li > .sub-menu  {\n";
            $css .= "\t" . 'background-color: ' . $this->bgColor . " !important;\n";
            $css .= "}\n";
        }

        // font style
        $css .= "#navigation ul.nav > li > .sub-menu > li > a {\n";
        $css .= "\t" . $this->generate_font_css($this->fontStyle) . ";\n";
        $css .= "}\n";

        // divider
        $divider = 'border-right:'. $this->divider["width"].'px '.$this->divider["style"].' '.$this->divider["color"].' !important;';
        $css .= "#navigation ul.nav > li > .sub-menu > li {\n";
        $css .= "\t" . $divider . "\n";
        $css .= "}\n";

        // border-top
        $borderTop = 'border-top:'. $this->borderTop["width"].'px '.$this->borderTop["style"].' '.$this->borderTop["color"].' !important;';
        $css .= "#navigation ul.nav > li > .sub-menu {\n";
        $css .= "\t" . $borderTop . "\n";
        $css .= "}\n";

        // border-bottom
        $borderBottom = 'border-bottom:'. $this->borderBottom["width"].'px '.$this->borderBottom["style"].' '.$this->borderBottom["color"].' !important;';
        $css .= "#navigation ul.nav > li > .sub-menu {\n";
        $css .= "\t" . $borderBottom . "\n";
        $css .= "}\n";

        // border left/right
        $borderLeftRight = 'border-left:'. $this->borderLeftRight["width"].'px '.$this->borderLeftRight["style"].' '.$this->borderLeftRight["color"].' !important;';
        $borderLeftRight .= 'border-right:'. $this->borderLeftRight["width"].'px '.$this->borderLeftRight["style"].' '.$this->borderLeftRight["color"].' !important;';
        $css .= "#navigation ul.nav > li > .sub-menu {\n";
        $css .= "\t" . $borderLeftRight . "\n";
        $css .= "}\n";

        // adjust left
        $css .= "#navigation ul.nav > li > .sub-menu {\n";
        $css .= "\t" . 'left: -' . $this->borderLeftRight['width'] . 'px !important;' . "\n";
        $css .= "}\n";

        if ($this->hoverColor != '') {
            $css .= "#navigation ul.nav .sub-menu li.current-menu-item > a > span {\n";
            $css .= "\t" . 'color: ' . $this->hoverColor . " !important;\n";
            $css .= "}\n";

            $css .= "#navigation ul.nav .sub-menu li:hover > a > span {\n";
            $css .= "\t" . 'color: ' . $this->hoverColor . " !important;\n";
            $css .= "}\n";
        }


        $all_plugins = get_plugins();
        $isCenterMenuActivated = false;
        foreach ( $all_plugins as $k => $v )
            if ( substr( $k, 0, 23) == 'cx-center-menu-and-logo') {
                if ( is_plugin_active($k)) {
                    $isCenterMenuActivated = true;
                }
            }

        $centerExtension = 'pootlepress-center-menu-n-logo';
        // center menu and logo extension compability
        $centerPrimaryNavEnabled	= get_option($centerExtension."_center-navigation-option");

        if ($isCenterMenuActivated && $centerPrimaryNavEnabled == 'true') {
            $css .= "#navigation ul.nav {\n";
            $css .= "\t" . 'text-align: center; float: none;' . "\n";
            $css .= "}\n";

            $css .= "#navigation ul.nav > li {\n";
            $css .= "\t" . 'display: inline-block; float: none;' . "\n";
            $css .= "}\n";

            $css .= "#navigation ul.nav > li > .sub-menu > li {\n";
            $css .= "\t" . 'float: none;' . "\n";
            $css .= "}\n";
        }

        $css .= "}\n";//close media query


        echo "<style>".$css."</style>";
    }

    private function generate_font_css( $option, $em = '1' ) {

        // Test if font-face is a Google font
        global $google_fonts;

        if (isset($google_fonts) && is_array($google_fonts) && count($google_fonts) > 0) {
            foreach ($google_fonts as $google_font) {

                // Add single quotation marks to font name and default arial sans-serif ending
                if ($option['face'] == $google_font['name'])
                    $option['face'] = "'" . $option['face'] . "', arial, sans-serif";

            } // END foreach
        }

        if ( !@$option['style'] && !@$option['size'] && !@$option['unit'] && !@$option['color'] )
            return 'font-family: '.stripslashes($option["face"]).' !important;';
        else {
            if (!isset($option['unit'])) {
                $option['unit'] = 'px';
            }
            return 'font:' . $option['style'] . ' ' . $option['size'] . $option['unit'] . '/' . $em . 'em ' . stripslashes($option['face']) . ' !important; color:' . $option['color'] . ' !important;';
        }
    }

    public function get_header () {

        if ($this->enabled == false) {
            return;
        }

        remove_action('woo_nav_inside', 'woo_nav_primary', 10); // this is added by canvas

        add_action( 'woo_nav_inside', array(&$this, 'woo_nav_custom'), 10 );
    }

    public function load_script()
    {
        if ($this->enabled == false) {
            return;
        }

        $pluginFile = dirname(dirname(__FILE__)) . '/pootlepress-horizontal-submenu.php';
        wp_enqueue_script('pootlepress-horizontal-submenu', plugin_dir_url($pluginFile) . 'scripts/horizontal-submenu.js', array('jquery'));

        if (isset($GLOBALS['pootlepress_center_mnl'])) {
            // if center menu and logo is activated
            // check if primary nav is center
            $nameprefix = 'pootlepress-center-menu-n-logo';
            $_center_pri_nav_enabled	= get_option($nameprefix."_center-navigation-option") == 'true';
            if ($_center_pri_nav_enabled) {
                wp_localize_script('pootlepress-horizontal-submenu', 'PHS', array('isPrimaryNavCentered' => true));
            }
        }

    }

    public function woo_nav_custom() {

        ?>

        <a href="<?php echo home_url(); ?>" class="nav-home"><i class="fa fa-home"></i><span><?php _e( 'Home', 'woothemes' ); ?></span></a>

        <?php
        if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
            echo '<h3>' . woo_get_menu_name( 'primary-menu' ) . '</h3>';
            wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'depth' => 2,
                'theme_location' => 'primary-menu', 'link_before' => '<span>', 'link_after' => '</span>',
                'walker' => new Pootlepress_Horizontal_Submenu_Nav_Walker() ) );
        } else {
            ?>
            <ul id="main-nav" class="nav fl">
                <?php
                if ( get_option( 'woo_custom_nav_menu' ) == 'true' ) {
                    if ( function_exists( 'woo_custom_navigation_output' ) ) { woo_custom_navigation_output( 'name=Woo Menu 1' ); }
                } else { ?>

                    <?php if ( is_page() ) { $highlight = 'page_item'; } else { $highlight = 'page_item current_page_item'; } ?>
                    <li class="<?php echo esc_attr( $highlight ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
                    <?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
                <?php } ?>
            </ul><!-- /#nav -->
        <?php }

        //woo_nav_after();
    } // End woo_nav_custom()

	/**
	 * Load the plugin's localisation file.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( $this->token, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation()

	/**
	 * Load the plugin textdomain from the main WordPress "languages" folder.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = $this->token;
	    // The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	 
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain()

	/**
	 * Run on activation.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->token . '-version', $this->version );
		}
	} // End register_plugin_version()

} // End Class


