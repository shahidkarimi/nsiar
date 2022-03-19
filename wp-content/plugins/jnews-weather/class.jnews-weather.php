<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'JNews_Weather' ) )
{
    class JNews_Weather
    {
        /**
         * @var JNews_Weather
         */
        private static $instance;

        /**
         * @var string
         */
        private $cache_key = "jnews_weather_cache";

        /**
         * @var string
         */
        private $location_cookie = "weather_location";

        /**
         * @var string
         */
        public $temp_unit;

        /**
         * @var string
         */
        public $ip_location;

        /**
         * @return JNews_Weather
         */
        public static function getInstance()
        {
            if (null === static::$instance)
            {
                static::$instance = new static();
            }
            return static::$instance;
        }

        /**
         * JNews_Weather constructor
         */
        private function __construct()
        {
            $this->setup_hook();

            $this->temp_unit = jnews_get_option('weather_default_temperature', 'c');
        }

        protected function setup_hook()
        {
            add_action( 'init',            array( $this, 'setup_cookie' ) );
            add_action( 'wp_print_styles', array( $this, 'load_assets' ) );
        }

        public function setup_cookie()
        {
			if ( jnews_get_option( 'top_bar_weather_location_auto', false ) ) {
				if ( ! isset( $_COOKIE[ $this->location_cookie ] ) && 'unknown' !== $_COOKIE[ $this->location_cookie ]  )
				{
					$expired = get_option( 'cookie_timeout', time() + 24 * 60 * 60 * 30 );
					setcookie( $this->location_cookie, $this->get_ip_address_location(), $expired, '/');
				}
			}
        }

        /**
         * Load plugin assest
         */
        public function load_assets()
        {
            wp_enqueue_style( 'jnews-weather-style',    JNEWS_WEATHER_URL . '/assets/css/plugin.css', null, JNEWS_WEATHER_VERSION );
            if ( is_user_logged_in() ) {
                wp_enqueue_style( 'tiny-slider' );
            }
            if ( is_user_logged_in() ) {
                wp_enqueue_script( 'tiny-slider-noconflict' );
            }
            wp_enqueue_script( 'jnews-weather',         JNEWS_WEATHER_URL . '/assets/js/plugin.js', null, JNEWS_WEATHER_VERSION, true );
        }

        /**
         * Convert temperature into default
         *
         * @param  int $temp
         *
         * @return int
         *
         */
        public function convert_temperature( $temp )
        {
            if ( $this->temp_unit == 'c' )
            {
                $temp = $this->convert_to_celcius( $temp );
            }

            return $temp;
        }

        /**
         * Convert temperature into Fahrenheit
         *
         * @param  int $temp
         *
         * @return int
         *
         */
        public function convert_to_fahrenheit( $temp )
        {
            return floor( ( ( $temp * 9 ) / 5 ) + 32 );
        }

        /**
         * Convert temperature into Celsius
         *
         * @param  int $temp
         *
         * @return int
         *
         */
        public function convert_to_celcius( $temp )
        {
            return floor( ( ( $temp - 32 ) * 5 ) / 9 );
        }

        /**
         * Get mean of min and max temperature
         *
         * @param  int $min
         * @param  int $max
         *
         * @return int
         *
         */
        public function temperature_mean( $min, $max )
        {
            return floor( ( $min + $max ) / 2 );
        }

        /**
         * Get IP Address
         *
         * @return string
         *
         */
        public function get_ip_address()
        {
            if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
                $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
                $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
                $ip = getenv('HTTP_FORWARDED');
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }

        /**
         * Get location cookie
         *
         * @return false | string
         *
         */
        public function get_location()
        {
            if ( isset( $_COOKIE[$this->location_cookie] ) && $_COOKIE[$this->location_cookie] !== 'unknown' )
            {
                return $_COOKIE[$this->location_cookie];
            } else {
                return false;
            }
        }

        /**
         * Get location of certain ip address
         *
         * @return false | string
         *
         */
	    protected function get_ip_address_location() {
		    $location = 'unknown';

		    $data    = $this->make_request_ip_location();
		    $city    = ! empty( $data['city'] ) ? $data['city'] : '';
		    $country = ! empty( $data['country'] ) ? $data['country'] : '';
		    $country = ! empty( $data['country_code'] ) ? $data['country_code'] : $country;

		    if ( ! empty( $city ) && ! empty( $country ) ) {
			    $location = $city . ', ' . $country;
		    }

		    return $location;
	    }

	    /**
	     * @return array
	     */
	    protected function make_request_ip_location() {
		    $providers = [ 'api.ipdata.co', 'ipinfo.io' ];
		    $ip        = $this->get_ip_address();
		    foreach ( $providers as $provider ) {
			    switch ( $provider ) {
				    case 'ipinfo.io':
				    	$api = '';
				    	if ( jnews_get_option('top_bar_weather_ipinfo_api', '') ) {
				    		$api = "?token=" . jnews_get_option('top_bar_weather_ipinfo_api', '');
					    }
					    $data = $this->make_request( "http://ipinfo.io/{$ip}/json" . $api );
					    if ( $data !== false ) {
						    return $data;
					    }
					    break;
				    case 'api.ipdata.co':
					    $api = 'test';
					    if ( jnews_get_option('top_bar_weather_ipdata_api', '') ) {
						    $api = jnews_get_option('top_bar_weather_ipdata_api', '');
					    }

					    $data = $this->make_request( "https://api.ipdata.co/{$ip}?api-key=" . $api );
					    if ( $data !== false ) {
						    return $data;
					    }
					    break;
			    }
		    }
	    }

        /**
         * Check data cache
         *
         * @param  string $source
         * @param  string $location
         *
         * @return array
         *
         */
        public function check_cache( $source, $location )
        {
            $now = current_time('timestamp');
            $data_scrap   = '';
            $temp_cache   = array();
            $add_cache    = true;
	        $update_cache = false;

            if ( empty( $location ) )
            {
                return false;
            }

            $expired    = $this->get_expired();
            $data_cache = get_option( $this->cache_key, array() );

            if ( !empty( $data_cache ) && is_array( $data_cache ) )
            {
                foreach ( $data_cache as $data )
                {
                    if ( $data['source'] == $source && $data['location'] == $location )
                    {
                        $add_cache = false;

                        if ( $data['expired'] < ( $now - $expired ) )
                        {
	                        $data_update = $this->fetch_data( $source, $location );

	                        if ( ! empty( $data_update ) )
	                        {
		                        $data['expired'] = current_time('timestamp');
		                        $data['data'] = $data_update;
		                        $update_cache = true;
	                        }
                        }

                        $data_scrap = $data['data'];
                    }
                }
            }

            if ( $add_cache )
            {
                $data_scrap = $this->fetch_data( $source, $location );

                if ( ! empty( $data_scrap ) )
                {
                    $data_cache[] = array(
                        'source'   => $source,
                        'location' => $location,
                        'expired'  => current_time('timestamp'),
                        'data'     => $data_scrap,
                    );
                } else {
                    $add_cache = false;
                }
            }

            if ( $add_cache || $update_cache ) update_option( $this->cache_key, $data_cache );

            return $data_scrap;
        }

        /**
         * Fetch data
         *
         * @param  string $source
         * @param  string $location
         *
         * @return array
         *
         */
        protected function fetch_data( $source, $location )
        {
            $result = array();

            switch ( $source )
            {
	            case 'yahoo':

		            $app_id          = jnews_get_option( 'weather_yahoo_app_id' );
		            $consumer_key    = jnews_get_option( 'weather_yahoo_consumer_key' );
		            $consumer_secret = jnews_get_option( 'weather_yahoo_consumer_secret' );

		            if ( ! empty( $app_id ) && ! empty( $consumer_key ) && ! empty( $consumer_secret ) ) {
			            $result = $this->yahoo_make_request( $app_id, $consumer_key, $consumer_secret, urldecode( $location ) );

			            if ( ! empty( $result ) ) {
				            $result = $this->yahoo_handler_data( $result );
			            }
		            }
		            break;

                case 'darksky':

                    $api_key    = jnews_get_option('weather_darksky_api_key', '');
                    $coordinate = $this->get_location_coordinate( $location );

                    if ( !empty( $api_key ) && !empty( $coordinate ) )
                    {
                        $url = 'https://api.darksky.net/forecast/' . $api_key . '/' . $coordinate['location']['coordinate'] . '?exclude=hourly,flags,alerts,minutely';

                        $result = $this->make_request( $url );

                        if ( !empty( $result ) )
                        {
                            $result = $this->darksky_handler_data( $result, $location );
                        }
                    }
                    break;

                case 'openweathermap':

	                $api_key    = jnews_get_option( 'weather_openweathermap_api_key', '' );
	                $location_coordinate = $this->get_location_coordinate( $location );

	                if ( ! empty( $api_key ) && ! empty( $location_coordinate ) )
                    {
	                    $coordinate = explode( ",", $location_coordinate['location']['coordinate'] );
	                    $url        = 'https://api.openweathermap.org/data/2.5/onecall?lat=' . $coordinate[0] . '&lon=' . $coordinate[1] . '&units=imperial&exclude=minutely,hourly,alert,current&appid=' . $api_key;

                        $result = $this->make_request( $url );

                        if ( !empty( $result ) )
                        {
                            if ( empty( $result['city'] ) ) {
                                $result['city']['name'] = $location_coordinate['location']['address']['locality'] ?: $location_coordinate['location']['address']['administrative_area_level_1'];
                                $result['city']['country'] = $location_coordinate['location']['address']['country'];
                                $result['city']['coord']['lat'] = $coordinate[0];
                                $result['city']['coord']['lon'] = $coordinate[1];
                            }

                            $result = $this->openweathermap_handler_data( $result );
                        }
                    }
                    break;

                case 'aerisweather':

                    $id     = jnews_get_option('weather_aerisweather_id', '');
                    $secret = jnews_get_option('weather_aerisweather_secret', '');

                    if ( !empty( $id ) && !empty( $secret ) )
                    {
                        $url = 'http://api.aerisapi.com/forecasts?p=' . urldecode( $location ) . '&client_id=' . $id . '&client_secret=' . $secret;

                        $result = $this->make_request( $url );

                        if ( !empty( $result ) )
                        {
                            $result = $this->aerisweather_handler_data( $result, $location );
                        }
                    }
                    break;
            }

            return $result;
        }

        /**
         * Get expired of data cache
         *
         * @return int
         *
         */
        public function get_expired()
        {
            $expired = jnews_get_option('weather_cache_expired', '1');

            if ( $expired != 'no' )
            {
                $expired = $expired * 60 * 60;
            } else {
                $expired = 0;
            }

            return $expired;
        }

        /**
         * Make remote request
         *
         * @param  string $url
         *
         * @return array
         *
         */
        public function make_request( $url )
        {
            $response = wp_remote_get( $url, array(
                'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
                'headers'    => array(
                    'referer' => home_url(),
                ),
            ) );

            if ( !is_wp_error( $response ) && $response['response']['code'] == '200' )
            {
                $result = json_decode( $response['body'], true );

                return $result;
            }

            return false;
        }

        /**
         * Get coordinate of location
         *
         * @param  string $location
         *
         * @return string
         *
         */
        public function get_location_coordinate( $location )
        {
            $coordinate = array();
            $api_key = jnews_get_option('top_bar_weather_location_api', '');

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $location );

            if ( $api_key ) {
                $url = $url . '&key=' . $api_key;
            }

            $data = $this->make_request( $url );

            if ( $data['status'] == 'OK' )
            {
                $data = $data['results'][0];
                $coordinate['location']['coordinate'] = $data['geometry']['location']['lat'] . ',' . $data['geometry']['location']['lng'];

                foreach ($data['address_components'] as $key => $value) {
                    $types = $data['address_components'][$key]['types'][0];
                    $coordinate['location']['address'][$types] = $data['address_components'][$key]['long_name'];
                }
            }

            return $coordinate;
        }

        /**
         * Data handler for Aeris Weather
         *
         * @param  array  $result
         * @param  string $loc
         *
         * @return array
         *
         */
        public function aerisweather_handler_data( $result, $loc )
        {
            if ( !$result['success'] )
            {
                return false;
            }

            $loc = explode(',', $loc);

            $result = $result['response'][0];

            $location = array(
                'city'      => isset( $loc[0] ) ? $loc[0] : '' ,
                'country'   => isset( $loc[1] ) ? $loc[1] : '' ,
                'latitude'  => $result['loc']['lat'],
                'longitude' => $result['loc']['long'],
            );

            $data = array(
                'location' => $location,
            );

            for ( $a = 0; $a < 7; $a++ )
            {
            	$code = explode(':', $result['periods'][$a]['weatherPrimaryCoded']);
                $item = array(
                    'condition' => $this->aerisweather_handler_condition( $result['periods'][$a]['weatherPrimaryCoded'] ),
                    'code'      => end($code),
                    'desc'      => $result['periods'][$a]['weatherPrimary'],
                    'temp'      => floor( $result['periods'][$a]['avgTempF'] ),
                    'temp_min'  => floor( $result['periods'][$a]['minTempF'] ),
                    'temp_max'  => floor( $result['periods'][$a]['maxTempF'] ),
                    'humidity'  => $result['periods'][$a]['humidity'],
                    'speed'     => $result['periods'][$a]['windSpeedMPH'],
                    'clouds'    => $this->aerisweather_handler_cloud( $result['periods'][$a]['cloudsCoded'] ),
                    'time'      => $result['periods'][$a]['timestamp'],
                );

                if ( $a == 0 )
                {
                    $data['now'] = $item;
                } else {
                    $data['next'][$a] = $item;
                }
            }

            return $data;
        }

        /**
         * Weather condition handler for Aeris Weather
         *
         * @param  array $data
         *
         * @return string
         *
         */
        public function aerisweather_handler_condition( $data )
        {
        	$data = explode(':', $data);
            switch ( end($data) )
            {
                case 'BD':
                case 'BN':
                case 'BR':
                    $condition = 'windy';
                    break;

                case 'T':
                case 'WP':
                    $condition = 'thunderstorm';
                    break;

                case 'FR':
                case 'L':
                case 'R':
                case 'RW':
                case 'RS':
                case 'WM':
                case 'UP':
                case 'ZL':
                case 'ZR':
                case 'ZY':
                    $condition = 'rainy';
                    break;

                case 'A':
                case 'BS':
                case 'BY':
                case 'IC':
                case 'IF':
                case 'IP':
                case 'SI':
                case 'S':
                case 'SW':
                    $condition = 'snowy';
                    break;

                case 'F':
                case 'H':
                case 'K':
                case 'VA':
                case 'ZF':
                    $condition = 'foggy';
                    break;

                case 'SC':
                    $condition = 'cloudy partly';
                    break;

                case 'OV':
                case 'BK':
                    $condition = 'cloudy';
                    break;

                case 'FW':
                case 'CL':
                    $condition = 'sunny';
                    break;

                default:
                    $condition = '';
                    break;
            }

            return $condition;
        }

        /**
         * Cloud condition handler for Aeris Weather
         *
         * @param  array $data
         *
         * @return string
         *
         */
        public function aerisweather_handler_cloud( $data )
        {
            switch ( $data )
            {
                case 'CL':
                    $cloud = '0-7%';
                    break;

                case 'FW':
                    $cloud = '7-32%';
                    break;

                case 'SC':
                    $cloud = '32-70%';
                    break;

                case 'BK':
                    $cloud = '70-95%';
                    break;

                case 'OV':
                    $cloud = '95-100%';
                    break;

                default:
                    $cloud = '';
                    break;
            }

            return $cloud;
        }

        /**
         * Data handler for Dark Sky
         *
         * @param  array  $result
         * @param  string $loc
         *
         * @return array
         *
         */
        public function darksky_handler_data( $result, $loc )
        {
            if ( empty( $result ) || !is_array( $result ) )
            {
                return false;
            }

            $loc = explode(',', $loc);

            $location = array(
                'city'      => isset( $loc[0] ) ? $loc[0] : '' ,
                'country'   => isset( $loc[1] ) ? $loc[1] : '' ,
                'latitude'  => $result['latitude'],
                'longitude' => $result['longitude'],
            );

            $now = array(
                'condition' => $this->darksky_handler_condition( $result['currently']['icon'] ),
                'code'      => $result['currently']['icon'],
                'desc'      => $result['currently']['summary'],
                'temp'      => floor( $result['currently']['temperature'] ),
                'temp_min'  => floor( $result['daily']['data'][0]['temperatureMin'] ),
                'temp_max'  => floor( $result['daily']['data'][0]['temperatureMax'] ),
                'humidity'  => $result['currently']['humidity'],
                'speed'     => $result['currently']['windSpeed'],
                'clouds'    => $result['currently']['cloudCover'] * 100,
                'time'      => $result['currently']['time'],
            );

            $data = array(
                'location' => $location,
                'now'      => $now,
            );

            for ( $a = 1; $a < 7; $a++ )
            {
                $data['next'][$a] = array(
                    'condition' => $this->darksky_handler_condition( $result['daily']['data'][$a]['icon'] ),
                    'desc'      => $result['daily']['data'][$a]['icon'],
                    'temp'      => $this->temperature_mean( $result['daily']['data'][$a]['temperatureMin'], $result['daily']['data'][$a]['temperatureMax'] ),
                    'temp_min'  => floor( $result['daily']['data'][$a]['temperatureMin'] ),
                    'temp_max'  => floor( $result['daily']['data'][$a]['temperatureMax'] ),
                    'time'      => $result['daily']['data'][$a]['time'],
                );
            }

            return $data;
        }

        /**
         * Weather condition handler for Dark Sky
         *
         * @param  array $data
         *
         * @return string
         *
         */
        public function darksky_handler_condition( $data )
        {
            switch ( $data )
            {
                case 'wind':
                case 'tornado':
                    $condition = 'windy';
                    break;

                case 'thunderstorm':
                    $condition = 'thunderstorm';
                    break;

                case 'rain':
                case 'sleet':
                    $condition = 'rainy';
                    break;

                case 'hail':
                case 'snow':
                    $condition = 'snowy';
                    break;

                case 'fog':
                    $condition = 'foggy';
                    break;

                case 'cloudy':
                    $condition = 'cloudy';
                    break;

                case 'partly-cloudy-night':
                    $condition = 'cloudy night partly';
                    break;

                case 'partly-cloudy-day':
                    $condition = 'cloudy day partly';
                    break;

                case 'clear-night':
                    $condition = 'sunny night';
                    break;

                case 'clear-day':
                    $condition = 'sunny day';
                    break;

                default:
                    $condition = '';
                    break;
            }

            return $condition;
        }

        /**
         * Data handler for Yahoo
         *
         * @param  object  $result
         *
         * @return array
         *
         */
	    public function yahoo_handler_data( $result ) {
		    $location = array(
			    'city'      => $result->location->city,
			    'country'   => $result->location->country,
			    'latitude'  => $result->location->lat,
			    'longitude' => $result->location->long,
		    );

		    $now = array(
			    'condition' => $this->yahoo_handler_condition( $result->current_observation->condition->code ),
			    'code'      => $result->current_observation->condition->code,
			    'desc'      => $result->current_observation->condition->text,
			    'temp'      => floor( $result->current_observation->condition->temperature ),
			    'temp_min'  => floor( $result->forecasts[1]->low ),
			    'temp_max'  => floor( $result->forecasts[1]->high ),
			    'humidity'  => $result->current_observation->atmosphere->humidity,
			    'speed'     => $result->current_observation->wind->speed,
			    'clouds'    => '-',
			    'time'      => $result->current_observation->pubDate,
		    );

		    $data = array(
			    'location' => $location,
			    'now'      => $now,
		    );

		    for ( $a = 1; $a <= 7; $a ++ ) {
			    $data['next'][ $a ] = array(
				    'condition' => $this->yahoo_handler_condition( $result->forecasts[ $a ]->code ),
				    'desc'      => $result->forecasts[ $a ]->text,
				    'temp'      => $this->temperature_mean( $result->forecasts[ $a ]->low, $result->forecasts[ $a ]->high ),
				    'temp_min'  => floor( $result->forecasts[ $a ]->low ),
				    'temp_max'  => floor( $result->forecasts[ $a ]->high ),
				    'time'      => $result->forecasts[ $a ]->date,
			    );
		    }

		    return $data;
	    }

        /**
         * Weather condition handler for Yahoo
         *
         * @param  array  $data
         *
         * @return string
         *
         */
        public function yahoo_handler_condition( $data )
        {
            switch ( $data )
            {
                case 19:
                case 23:
                case 24:
                case 47:
                    $condition = 'windy';
                    break;

                case  0:
                case  1:
                case  2:
                case  3:
                case  4:
                case 37:
                case 38:
                case 39:
                case 45:
                    $condition = 'thunderstorm';
                    break;

                case  5:
                case  6:
                case  8:
                case  9:
                case 10:
                case 11:
                case 12:
                case 35:
                case 40:
                    $condition = 'rainy';
                    break;

                case  7:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                case 41:
                case 42:
                case 43:
                case 46:
                    $condition = 'snowy';
                    break;

                case 20:
                case 21:
                case 22:
                    $condition = 'foggy';
                    break;

                case 25:
                case 26:
                    $condition = 'cloudy';
                    break;

                case 27:
                    $condition = 'cloudy day';
                    break;

                case 28:
                    $condition = 'cloudy night';
                    break;

                case 29:
                    $condition = 'cloudy night partly';
                    break;

                case 30:
                    $condition = 'cloudy day partly';
                    break;

                case 44:
                    $condition = 'cloudy partly';
                    break;

                case 31:
                case 33:
                    $condition = 'sunny night';
                    break;

                case 32:
                case 36:
                    $condition = 'sunny';
                    break;

                case 34:
                    $condition = 'sunny day';
                    break;

                default:
                    $condition = '';
                    break;
            }

            return $condition;
        }

        /**
         * Data handler for Open Weather Map
         *
         * @param  array  $result
         *
         * @return array
         *
         */
        public function openweathermap_handler_data( $result )
        {
            $location = array(
                'city'      => $result['city']['name'],
                'country'   => $result['city']['country'],
                'latitude'  => $result['city']['coord']['lat'],
                'longitude' => $result['city']['coord']['lon'],
            );

            $data = array(
                'location' => $location,
            );

            for ( $a = 0; $a < 7; $a++ )
            {
                $item = array(
				    'condition' => $this->openweathermap_handler_condition( $result['daily'][ $a ]['weather'][0]['icon'] ),
				    'code'      => $result['daily'][ $a ]['weather'][0]['id'],
				    'desc'      => $result['daily'][ $a ]['weather'][0]['description'],
				    'temp'      => $this->temperature_mean( $result['daily'][ $a ]['temp']['min'], $result['daily'][ $a ]['temp']['max'] ),
				    'temp_min'  => floor( $result['daily'][ $a ]['temp']['min'] ),
				    'temp_max'  => floor( $result['daily'][ $a ]['temp']['max'] ),
				    'humidity'  => $result['daily'][ $a ]['humidity'],
				    'speed'     => $result['daily'][ $a ]['wind_speed'],
				    'clouds'    => $result['daily'][ $a ]['clouds'],
				    'time'      => $result['daily'][ $a ]['dt'],
                );

                if ( $a == 0 )
                {
                    $data['now'] = $item;
                } else {
                    $data['next'][$a] = $item;
                }
            }

            return $data;
        }

        /**
         * Weather condition handler for Open Weather Map
         *
         * @param  array  $data
         *
         * @return string
         *
         */
        public function openweathermap_handler_condition( $data )
        {
            switch ( $data )
            {
                case '11d':
                    $condition = 'thunderstorm day';
                    break;

                case '11n':
                    $condition = 'thunderstorm night';
                    break;

                case '10d':
                    $condition = 'rainy day';
                    break;

                case '10n':
                    $condition = 'rainy night';
                    break;

                case '09d':
                    $condition = 'rainy day partly';
                    break;

                case '09n':
                    $condition = 'rainy night partly';
                    break;

                case '13d':
                    $condition = 'snowy day';
                    break;

                case '13n':
                    $condition = 'snowy night';
                    break;

                case '50d':
                    $condition = 'foggy day';
                    break;

                case '50n':
                    $condition = 'foggy night';
                    break;

                case '04d':
                    $condition = 'cloudy day';
                    break;

                case '04n':
                    $condition = 'cloudy night';
                    break;

                case '02n':
                case '03n':
                    $condition = 'cloudy night partly';
                    break;

                case '02d':
                case '03d':
                    $condition = 'cloudy day partly';
                    break;

                case '01d':
                    $condition = 'sunny day';
                    break;

                case '01n':
                    $condition = 'sunny night';
                    break;

                default:
                    $condition = '';
                    break;
            }

            return $condition;
        }

        /**
         * Get icon condition handler
         *
         * @param  string $condition
         *
         * @return array
         *
         */
        public function get_icon_condition_handler( $condition )
        {
            $icon_sm = $icon_lg = '';

            switch ( $condition )
            {
                case 'thunderstorm day':
                    $icon_sm = 'jegicon-thunderstorm-day-sm';
                    $icon_lg = 'jegicon-thunderstorm-day-lg';
                    break;

                case 'thunderstorm night':
                    $icon_sm = 'jegicon-thunderstorm-night-sm';
                    $icon_lg = 'jegicon-thunderstorm-night-lg';
                    break;

                case 'thunderstorm':
                    $icon_sm = 'jegicon-thunderstorm-sm';
                    $icon_lg = 'jegicon-thunderstorm-lg';
                    break;

                case 'rainy day':
                case 'rainy day partly':
                    $icon_sm = 'jegicon-rainy-day-sm';
                    $icon_lg = 'jegicon-rainy-day-lg';
                    break;

                case 'rainy night':
                case 'rainy night partly':
                    $icon_sm = 'jegicon-rainy-night-sm';
                    $icon_lg = 'jegicon-rainy-night-lg';
                    break;

                case 'rainy':
                    $icon_sm = 'jegicon-rainy-sm';
                    $icon_lg = 'jegicon-rainy-lg';
                    break;

                case 'sunny day':
                case 'sunny day partly':
                    $icon_sm = 'jegicon-sunny-day-sm';
                    $icon_lg = 'jegicon-sunny-day-lg';
                    break;

                case 'sunny night':
                case 'sunny night partly':
                    $icon_sm = 'jegicon-sunny-night-sm';
                    $icon_lg = 'jegicon-sunny-night-lg';
                    break;

                case 'sunny':
                    $icon_sm = 'jegicon-sunny-sm';
                    $icon_lg = 'jegicon-sunny-lg';
                    break;

                case 'cloudy day':
	            case 'cloudy partly':
                case 'cloudy day partly':
                    $icon_sm = 'jegicon-cloudy-day-sm';
                    $icon_lg = 'jegicon-cloudy-day-lg';
                    break;

                case 'cloudy night':
                case 'cloudy night partly':
                    $icon_sm = 'jegicon-cloudy-night-sm';
                    $icon_lg = 'jegicon-cloudy-night-lg';
                    break;

                case 'cloudy':
                    $icon_sm = 'jegicon-cloudy-sm';
                    $icon_lg = 'jegicon-cloudy-lg';
                    break;

                case 'foggy day':
                case 'foggy day partly':
                    $icon_sm = 'jegicon-foggy-day-sm';
                    $icon_lg = 'jegicon-foggy-day-lg';
                    break;

                case 'foggy night':
                case 'foggy night partly':
                    $icon_sm = 'jegicon-foggy-night-sm';
                    $icon_lg = 'jegicon-foggy-night-lg';
                    break;

                case 'foggy':
                    $icon_sm = 'jegicon-foggy-sm';
                    $icon_lg = 'jegicon-foggy-lg';
                    break;

                case 'snowy day':
                case 'snowy day partly':
                    $icon_sm = 'jegicon-snowy-day-sm';
                    $icon_lg = 'jegicon-snowy-day-lg';
                    break;

                case 'snowy night':
                case 'snowy night partly':
                    $icon_sm = 'jegicon-snowy-night-sm';
                    $icon_lg = 'jegicon-snowy-night-lg';
                    break;

                case 'snowy':
                    $icon_sm = 'jegicon-snowy-sm';
                    $icon_lg = 'jegicon-snowy-lg';
                    break;

                case 'windy':
                    $icon_sm = 'jegicon-windy-sm';
                    $icon_lg = 'jegicon-windy-lg';
                    break;
            }

            $data = array(
                'icon_sm' => $icon_sm,
                'icon_lg' => $icon_lg
            );

            return $data;
        }

	    public function yahoo_buildBaseString( $baseURI, $method, $params ) {
		    $r = array();
		    ksort( $params );
		    foreach ( $params as $key => $value ) {
			    $r[] = "$key=" . rawurlencode( $value );
		    }

		    return $method . "&" . rawurlencode( $baseURI ) . '&' . rawurlencode( implode( '&', $r ) );
	    }

	    public function yahoo_buildAuthorizationHeader( $oauth ) {
		    $r      = 'Authorization: OAuth ';
		    $values = array();
		    foreach ( $oauth as $key => $value ) {
			    $values[] = "$key=\"" . rawurlencode( $value ) . "\"";
		    }
		    $r .= implode( ', ', $values );

		    return $r;
	    }

	    public function yahoo_make_request( $app_id, $consumer_key, $consumer_secret, $location ) {
		    $url                      = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
		    $query                    = array(
			    'location' => $location,
			    'format'   => 'json',
		    );
		    $oauth                    = array(
			    'oauth_consumer_key'     => $consumer_key,
			    'oauth_nonce'            => uniqid( mt_rand( 1, 1000 ) ),
			    'oauth_signature_method' => 'HMAC-SHA1',
			    'oauth_timestamp'        => time(),
			    'oauth_version'          => '1.0'
		    );
		    $base_info                = $this->yahoo_buildBaseString( $url, 'GET', array_merge( $query, $oauth ) );
		    $composite_key            = rawurlencode( $consumer_secret ) . '&';
		    $oauth_signature          = base64_encode( hash_hmac( 'sha1', $base_info, $composite_key, true ) );
		    $oauth['oauth_signature'] = $oauth_signature;
		    $header                   = array(
			    $this->yahoo_buildAuthorizationHeader( $oauth ),
			    'Yahoo-App-Id: ' . $app_id
		    );
		    $options                  = array(
			    CURLOPT_HTTPHEADER     => $header,
			    CURLOPT_HEADER         => false,
			    CURLOPT_URL            => $url . '?' . http_build_query( $query ),
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_SSL_VERIFYPEER => false
		    );
		    $ch                       = curl_init();
		    curl_setopt_array( $ch, $options );
		    $response = curl_exec( $ch );
		    curl_close( $ch );
		    return json_decode( $response );
	    }
    }
}

