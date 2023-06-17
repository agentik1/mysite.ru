<?php
/**
 * OnePress Proxy
 *
 * Handles request with proxy.
 *
 * @package OnePress
 * @since   2.1.9
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Onepress_Proxy' ) ) {
	/**
	 * Onepress_Proxy class.
	 */
	class Onepress_Proxy {
		/**
		 * Instance.
		 *
		 * @var object
		 */
		protected static $_instance = null;
		/**
		 * Get instance of Onepress_Proxy.
		 *
		 * @since 1.2.6
		 * @return object
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		/**
		 * Get proxy from cache or direct get and store to cache.
		 *
		 * @since 1.2.6
		 * @return void
		 */
		public function get_proxy() {
			$cache_result = get_transient( 'onepress_free_proxies' );
			if ( false === $cache_result || empty( $cache_result ) ) {
				$cache_result = $this->get_free_proxy();
				if ( is_array( $cache_result ) && ! empty( $cache_result ) ) {
					set_transient( 'onepress_free_proxies', $cache_result, 2 * HOUR_IN_SECONDS );
				}
			}
			return $cache_result;
		}
		/**
		 * Get list valid proxies.
		 *
		 * @since 1.2.6
		 * @return array
		 */
		public function get_valid_proxies() {
			$get_proxies = $this->get_proxy();
			$data = array();
			if ( is_array( $get_proxies ) ) {
				$data = $get_proxies;
			} elseif ( is_string( $get_proxies ) && ! empty( $get_proxies ) ) {
				$data        = explode( ' ', $get_proxies );
			}
			if ( is_array( $data ) && ! empty( $data ) ) {
				$data        = array_filter( array_map( 'trim', $data ) );
			}

			$proxies     = array();
			if ( is_array( $data ) && ! empty( $data ) ) {
				$proxies = array_unique( $data );
				shuffle( $proxies );
				$proxies = array_values( $proxies );
			}
			return $proxies;
		}
		/**
		 * Get list list free proxies.
		 *
		 * @since 1.2.6
		 * @return array
		 */
		public function get_free_proxy() {
			$url     = 'https://free-proxy-list.net/';
			$html    = file_get_html( $url );
			$proxies = array();
			if ( is_object( $html ) && method_exists( $html, 'find' ) ) {
				try {
					$tbody = $html->find( '#proxylisttable tbody', 0 );
					if ( is_object( $tbody ) ) {
						foreach ( $tbody->find( 'tr' ) as $tr ) {
							$proxy = '';
							if ( is_object( $tr ) && $tr->find( 'td', 0 ) && $tr->find( 'td', 0 )->plaintext ) {
								$proxy .= $tr->find( 'td', 0 )->plaintext;

								if ( $tr->find( 'td', 1 ) && $tr->find( 'td', 1 )->plaintext ) {
									$proxy .= ':' . $tr->find( 'td', 1 )->plaintext;
								}
							}
							if ( ! empty( $proxy ) ) {
								$proxies[] = $proxy;
							}
						}
					}
				} catch ( Exception $e ) {
					$proxies = array();
				}
			}
			if ( ! empty( $proxies ) ) {
				$proxies = array_unique( $proxies );
			}
			return $proxies;
		}
		/**
		 * Get proxy from cache or direct get and store to cache.
		 *
		 * @since 1.2.6
		 * @return array
		 */
		public function get_random_useragent() {
			$agent = array(
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
				'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
				'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
				'Mozilla/5.0 (Linux; Android 8.0.0;) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.96 Mobile Safari/537.36',
				'Mozilla/5.0 (iPhone; CPU iPhone OS 12_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/78.0.3904.84 Mobile/15E148 Safari/605.1',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
				'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
				'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.83 Safari/537.1',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
				'Mozilla/5.0 (Windows NT 5.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.67 Safari/537.36',
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
				'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/74.0.3729.157 Safari/537.36',
				'Mozilla/5.0 (Linux; Android 7.1.2; AFTMM Build/NS6264; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/59.0.3071.125 Mobile Safari/537.36',
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
			);
			shuffle( $agent );
			return $agent[0];
		}
		/**
		 * Get proxy from cache or direct get and store to cache.
		 *
		 * @since 1.2.6
		 * @param string $url target url.
		 * @param string $proxy a proxy string.
		 * @return string
		 */
		public function get_html_content( $url, $proxy = '' ) {
			$header     = array(
				'Accept: text/xml,application/xml,application/xhtml+xml, text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
				'Cache-Control: max-age=0',
				'Connection: keep-alive',
				'Keep-Alive: 300',
				'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
				'Accept-Language: en-us,en;q=0.5',
			);
			$user_agent = $this->get_random_useragent();
			$options    = array(
				CURLOPT_PROXY           => $proxy,
				CURLOPT_HTTPPROXYTUNNEL => 0,
				CURLOPT_REFERER         => 'https://www.google.com',
				CURLOPT_FOLLOWLOCATION  => true,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_USERAGENT       => $user_agent,
				CURLOPT_CONNECTTIMEOUT  => 20,
				CURLOPT_TIMEOUT         => 20,
				CURLOPT_MAXREDIRS       => 10,
				CURLOPT_HEADER          => true,
				CURLOPT_HTTPHEADER      => $header,
				CURLOPT_ENCODING        => 'gzip,deflate',
			);
			$ch         = curl_init( $url );
			curl_setopt_array( $ch, $options );
			$html = curl_exec( $ch );
			curl_close( $ch );
			return $html;
		}

		/**
		 * Get proxy from cache or direct get and store to cache.
		 *
		 * @since 1.2.6
		 * @param string $item_url target url.
		 * @return string
		 */
		public function get_html( $item_url = '' ) {
			if ( function_exists( 'set_time_limit' ) ) {
				set_time_limit( 0 );
			}
			$try_count  = 0;
			$count_fail = 0;
			do {
				$proxies = $this->get_valid_proxies();
				$proxy   = ( is_array( $proxies ) && ! empty( $proxies ) ) ? $proxies[0] : false;
				try {
					if ( $count_fail > 5 ) {
						break;
					}
					if ( 0 === $count_fail ) {
						$proxy = false;
					}
					$html = $this->get_html_content( $item_url, $proxy );

					if ( ! empty( $html ) ) {
						$dom = new simple_html_dom();
						$dom->load( $html, true, false );
						if ( is_object( $dom ) && method_exists( $dom, 'find' ) && $dom->find( 'title', 0 ) ) {
							return $html;
						} else {
							$count_fail++;
						}
					}
					$try_count++;
					if ( $try_count >= count( $proxies ) || ! isset( $proxies[ $try_count ] ) ) {
						break;
					}
					$proxy = $proxies[ $try_count ];
				} catch ( Exception $e ) {
					$try_count++;
					if ( $try_count >= count( $proxies ) || ! isset( $proxies[ $try_count ] ) ) {
						break;
					}
					$proxy = $proxies[ $try_count ];
				}
			} while ( true );

			return false;
		}


	}
}
if ( ! function_exists( 'onepress_proxy' ) ) {
	/**
	 * Get instagram data via proxy
	 *
	 * @since 1.2.6
	 * @return object
	 */
	function onepress_proxy() {
		return Onepress_Proxy::get_instance();
	}
}



if ( ! class_exists( 'Onepress_Plus_Instagram' ) ) {
	class Onepress_Plus_Instagram {
		protected static $_instance = null;
		public $instagram_url       = 'https://www.instagram.com';
		public $api_url             = 'https://graph.instagram.com';

		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'get_instagram_token' ), 1 );
			add_action( 'muplugins_loaded', array( $this, 'get_instagram_token' ), 1 );

			add_action( 'init', array( $this, 'remove_connected_user' ), PHP_INT_MAX );

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ), 99 );
		}

		public function enqueue() {
			wp_enqueue_style( 'onepress-plus-customizer',  ONEPRESS_PLUS_URL . 'assets/css/customizer.css' );
		}

		function get_setting_number_items() {
			$number_item = absint( get_theme_mod( 'onepress_g_number', 10 ) );
			if ( ! $number_item ) {
				$number_item = 10;
			}
			return $number_item;
		}

		function remove_connected_user() {
			if ( isset( $_GET['pm_action'] ) && 'remove_inst_account' == $_GET['pm_action'] ) {
				$wp_nonce = ( isset( $_GET['_wp_nonce'] ) && ! empty( $_GET['_wp_nonce'] ) ) ? $_GET['_wp_nonce'] : '';
				if ( wp_verify_nonce( $wp_nonce, 'remove_inst_account' ) ) {
					$current_user_profile = get_option( 'pm_instagram_current_user_profile', array() );
					if ( is_array( $current_user_profile ) && isset( $current_user_profile['username'] ) ) {
						$username = $current_user_profile['username'];
						$username  = strtolower( $username );
						$username  = str_replace( '@', '', $username );
						$cache_key = 'onepress_plus_itg_' . $username;

						set_theme_mod( 'onepress_gallery_source_instagram', '' );
						$number_item = $this->get_setting_number_items();
						$gallery_data_cache_key = 'onepress_gallery_instagram_' . $username . $number_item;

						delete_transient( $cache_key );
						delete_transient( $gallery_data_cache_key );
					}
					delete_option( 'pm_instagram_current_user_profile' );
					delete_option( 'pm_instagram_access_token' );
					$redirect_to_url = admin_url( '/customize.php?autofocus[panel]=onepress_gallery&autofocus[section]=onepress_gallery_content' );
					wp_redirect( $redirect_to_url );
					exit;
				}
			}
		}

		function is_insta_gallery_activated() {
			$active_plugins = get_option( 'active_plugins' );
			if ( in_array( 'insta-gallery/insta-gallery.php', $active_plugins ) ) {
				return true;
			}
			return false;
		}

		function get_access_token() {
			if ( ! $this->is_insta_gallery_activated() ) {
				return get_option( 'pm_instagram_access_token', '' );
			} else {
				return get_option( 'insta_gallery_token', '' );
			}
		}

		function get_instagram_token() {
			if ( ! $this->is_insta_gallery_activated() && isset( $_GET['page'] ) && 'qligg_account' == $_GET['page'] ) {
				$new_url = admin_url( 'admin.php' );
				$args = array(
					'pm_action' => 'pm_instagram_token',
					'access_token' => '',
					'token_expires_in' => '',
				);
				if ( isset( $_GET['accounts'] ) && is_array( $_GET['accounts'] ) && ! empty( $_GET['accounts'] ) ) {
					$accounts = wp_unslash( $_GET['accounts'] );
					foreach ( $accounts as $account ) {
						if ( isset( $account['access_token'] ) && ! empty( $account['access_token'] ) ) {
							$args['access_token'] = $account['access_token'];
						}
						if ( isset( $account['expires_in'] ) && ! empty( $account['expires_in'] ) ) {
							$args['token_expires_in'] = $account['expires_in'];
						}
					}
				}
				$new_url = add_query_arg( $args, $new_url );
				if ( ! empty( $new_url ) ) {
					wp_redirect( $new_url );
					exit;
				}
			}

			if ( ! $this->is_insta_gallery_activated() && isset( $_GET['pm_action'] ) ) {
				$pm_action    = ! empty( $_GET['pm_action'] ) ? sanitize_text_field( wp_unslash( $_GET['pm_action'] ) ) : '';
				$access_token = ! empty( $_GET['access_token'] ) ? sanitize_text_field( wp_unslash( $_GET['access_token'] ) ) : '';
				if ( 'pm_instagram_token' == $pm_action && ! empty( $access_token ) ) {
					update_option( 'pm_instagram_access_token', $access_token );
					$current_user_profile = $this->get_current_user_profile();
					if ( is_array( $current_user_profile ) && isset( $current_user_profile['username'] ) ) {
						set_theme_mod( 'onepress_gallery_source', 'instagram' );
						set_theme_mod( 'onepress_gallery_source_instagram', $current_user_profile['username'] );
					}
					update_option( 'pm_instagram_current_user_profile', $current_user_profile );
					$redirect_to_url = admin_url( '/customize.php?autofocus[panel]=onepress_gallery&autofocus[section]=onepress_gallery_content' );
					wp_redirect( $redirect_to_url );
					exit;
				}
			}
		}

		public function get_user_profile( $access_token ) {
			$args     = array(
				'timeout'     => 180,
				'httpversion' => '1.1',
			);
			$url      = "{$this->api_url}/me";

			$request_args = array(
				'fields'       => 'id,media_count,username,account_type',
				'access_token' => $access_token,
			);
			$url = add_query_arg( $request_args, trailingslashit( $url ) );
			$response = wp_remote_get( $url, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$body = ( isset( $response['body'] ) && ! empty( $response['body'] ) ) ? json_decode( $response['body'], true ) : array();
				if ( ! empty( $body ) ) {
					return $body;
				}
			}
			return false;
		}

		public function get_current_user_profile() {
			$access_token = $this->get_access_token();
			$user_data    = array();
			if ( ! empty( $access_token ) ) {
				$user_profile = $this->get_user_profile( $access_token );
				if ( ! empty( $user_profile ) ) {
					$user_data = $user_profile;
				}
			}
			return $user_data;
		}

		public function get_user_items( $access_token, $limit = 100 ) {
			$args     = array(
				'timeout'     => 180,
				'httpversion' => '1.1',
			);

			$request_args = array(
				'limit'        => $limit,
				'fields'       => 'media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}',
				'access_token' => $access_token,
			);

			$url = "{$this->api_url}/me/media";
			$url = add_query_arg( $request_args, trailingslashit( $url ) );
			$response = wp_remote_get( $url, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$body = ( isset( $response['body'] ) && ! empty( $response['body'] ) ) ? json_decode( $response['body'], true ) : array();
				if ( ! empty( $body ) && isset( $body['data'] ) && ! empty( $body['data'] ) ) {
					return $body['data'];
				}
			}
			return false;
		}

		public function get_current_user_items( $limit = 100 ) {
			$access_token = $this->get_access_token();
			$user_items    = array();
			if ( ! empty( $access_token ) ) {
				$get_user_items = $this->get_user_items( $access_token, $limit );
				if ( ! empty( $get_user_items ) ) {
					$user_items = $get_user_items;
				}
			}
			return $user_items;
		}

		public function get_current_user_items_formatted( $limit = 100 ) {
			$instagram = array();
			$user_items = $this->get_current_user_items( $limit );
			if ( is_array( $user_items ) && ! empty( $user_items ) ) {
				foreach ( $user_items as $item ) {
					if ( isset( $item['media_type'] ) && 'IMAGE' == $item['media_type'] && isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) {
						$instagram[] = array(
							'title'     => ( isset( $item['caption'] ) && ! empty( $item['caption'] ) ) ? trim( $item['caption'] ) : '',
							'link'      => ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) ? $item['permalink'] : '',
							'thumbnail' => ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) ? trailingslashit( $item['permalink'] ) . 'media?size=m' : '',
							'small'     => ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) ? trailingslashit( $item['permalink'] ) . 'media?size=t' : '',
							'large'     => ( isset( $item['permalink'] ) && ! empty( $item['permalink'] ) ) ? trailingslashit( $item['permalink'] ) . 'media?size=l' : '',
							'full'      => ( isset( $item['media_url'] ) && ! empty( $item['media_url'] ) ) ? trim( $item['media_url'] ) : '',
							'type'      => $item['media_type'],
						);
					}
				}
			}
			return $instagram;
		}


	}
}

if ( ! function_exists( 'onepress_plus_instagram' ) ) {
	function onepress_plus_instagram() {
		return Onepress_Plus_Instagram::get_instance();
	}
}
onepress_plus_instagram();


