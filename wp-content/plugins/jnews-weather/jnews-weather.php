<?php
/*
	Plugin Name: JNews - Weather
	Plugin URI: http://jegtheme.com/
	Description: Weather Forecast Plugin for JNews Themes
	Version: 9.0.1
	Author: Jegtheme
	Author URI: http://jegtheme.com
	License: GPL2
*/

defined( 'JNEWS_WEATHER' )          or define( 'JNEWS_WEATHER', 'jnews-weather');
defined( 'JNEWS_WEATHER_VERSION' )  or define( 'JNEWS_WEATHER_VERSION', '9.0.1' );
defined( 'JNEWS_WEATHER_URL' )      or define( 'JNEWS_WEATHER_URL', plugins_url('jnews-weather') );
defined( 'JNEWS_WEATHER_FILE' )     or define( 'JNEWS_WEATHER_FILE',  __FILE__ );
defined( 'JNEWS_WEATHER_DIR' )      or define( 'JNEWS_WEATHER_DIR', plugin_dir_path( __FILE__ ) );

global $pagenow;

/**
 * Get JNews option
 *
 * @param array $setting
 * @param mixed $default
 *
 * @return mixed
 *
 */
if ( !function_exists('jnews_get_option') )
{
    function jnews_get_option( $setting, $default = null )
    {
        $options = get_option( 'jnews_option', array() );
        $value = $default;

        if ( isset( $options[ $setting ] ) )
        {
            $value = $options[ $setting ];
        }
        return $value;
    }
}

if ( ! function_exists( 'is_elementor_editor' ) ) {
	/**
	 * Check if the current page is elementor editor
	 */
	function is_elementor_editor() {
		return is_admin() && ( isset( $_GET['action'] ) && sanitize_text_field( $_GET['action'] ) === 'elementor' );
	}
}

/**
 * Load Plugin Option
 */
add_action( 'jeg_register_customizer_option', 'jnews_weather_customizer_option' );

if ( ! function_exists( 'jnews_weather_customizer_option' ) ) {
	function jnews_weather_customizer_option() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$customizer = Jeg\Customizer\Customizer::get_instance();

			$global_weather_section = [
				'id'       => 'jnews_global_weather_section',
				'title'    => esc_html__( 'Weather Setting', 'jnews-weather' ),
				'panel'    => 'jnews_global_panel',
				'priority' => 252,
				'type'     => 'jnews-lazy-section',
			];

			$header_weather_section = [
				'id'       => 'jnews_header_weather_section',
				'title'    => esc_html__( 'Header - Weather Setting', 'jnews-weather' ),
				'panel'    => 'jnews_header',
				'priority' => 252,
				'type'     => 'jnews-lazy-section',
			];

			$customizer->add_section( $global_weather_section );
			$customizer->add_section( $header_weather_section );
		}
	}
}


add_filter('jeg_register_lazy_section', 'jnews_weather_lazy_section');

if(!function_exists('jnews_weather_lazy_section'))
{
    function jnews_weather_lazy_section($result)
    {
        $result['jnews_global_weather_section'][] = JNEWS_WEATHER_DIR . "global-weather-option.php";
        $result['jnews_header_weather_section'][] = JNEWS_WEATHER_DIR . "header-weather-option.php";
        return $result;
    }
}

/**
 * Load Weather Class
 */
add_action( 'plugins_loaded', 'jnews_weather' );

if ( ! function_exists('jnews_weather') )
{
    function jnews_weather()
    {
        require_once 'class.jnews-weather.php';
        JNews_Weather::getInstance();
    }
}

/**
 * Load Top Bar Weather
 */
if( !is_admin() ) {
    add_action( 'plugins_loaded', 'header_topbar_weather' );

    if ( ! function_exists('header_topbar_weather') )
    {
        function header_topbar_weather()
        {
            require_once 'class.jnews-weather-topbar.php';
            JNews_Weather_TopBar::getInstance();
        }
    }
}
   

/**
 * Register Weather Widget
 */
if( 'widgets.php' === $pagenow || !is_admin() || is_elementor_editor() || ( defined( 'DOING_AJAX' ) && 'DOING_AJAX' ) ) {
    add_action( 'widgets_init', 'register_weather_widget' );

    if ( ! function_exists('register_weather_widget') )
    {
        function register_weather_widget()
        {
            if ( ! defined( 'JNEWS_THEME_URL' ) ) return;

            require_once 'class.jnews-weather-widget.php';
            register_widget("JNews_Weather_Widget");
        }
    }
}

/**
 * Load Shortcode Class
 */
add_action('plugins_loaded', 'jnews_weather_shortcode');

if ( ! function_exists('jnews_weather_shortcode') )
{
    function jnews_weather_shortcode()
    {
        require_once 'class.jnews-weather-shortcode.php';
        JNews_Weather_Shortcode::getInstance();
        
    }
}


/**
 * Load Text Domain
 */
function jnews_weather_load_textdomain()
{
    load_plugin_textdomain( JNEWS_WEATHER, false, basename(__DIR__) . '/languages/' );
}

jnews_weather_load_textdomain();
