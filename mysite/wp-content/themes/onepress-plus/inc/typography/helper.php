<?php

// Register our customizer panels, sections, settings, and controls.
$GLOBALS['wp_typography_auto_apply'] = array();

add_action( 'wp_head', 'onepress_typography_print_styles', 99 );

/**
 * Render typography styles.
 *
 * @param bool $echo
 * @param bool $for_editor
 * @return array
 */
function onepress_typography_render_style( $echo = true, $for_editor = false ) {
	global $wp_typography_auto_apply;

	$google_fonts = array();
	$font_variants = array();
	$css = array();
	$scheme = is_ssl() ? 'https' : 'http';

	if ( ! function_exists( 'onepress_typography_get_fonts' ) ) {
		include_once dirname( __FILE__ ) . '/typography.php';
	}

	$fonts = onepress_typography_get_google_fonts();

	if ( ! empty( $wp_typography_auto_apply ) ) {
		foreach ( $wp_typography_auto_apply as $k => $settings ) {

			if ( isset( $settings['data_type'] ) && 'option' == $settings['data_type'] ) {
				$data = get_option( $k, false );
			} else {
				$data = get_theme_mod( $k, false );
			}
			$data = json_decode( $data, true );
			if ( ( ! $data || empty( $data ) ) && $settings['default'] ) {
				$data = $settings['default'];
			}

			if ( ! is_array( $data ) ) {
				continue;
			}

			$data  = array_filter( $data );
			if ( empty( $data ) && is_array( $settings['default'] ) ) {
				$data = array_merge( $settings['default'], $data );
			}

			$data = wp_parse_args(
				$data,
				array(
					'font-family'     => '',
					'color'           => '',
					'font-style'      => '',
					'font-weight'     => '',
					'font-size'       => '',
					'line-height'     => '',
					'letter-spacing'  => '',
					'text-transform'  => '',
					'text-decoration' => '',
				)
			);

			$font_id = false;
			if ( isset( $data ) && is_array( $data ) ) {
				if ( isset( $data['font-family'] ) && '' != $data['font-family'] ) {
					$font_id = sanitize_title( $data['font-family'] );
				}
			}

			if ( '' != $font_id && isset( $fonts[ $font_id ] ) && 'google' == $fonts[ $font_id ]['font_type'] ) {
				$google_fonts[ $font_id ] = $fonts[ $font_id ];

				if ( ! isset( $font_variants[ $font_id ] ) || ! is_array( $font_variants[ $font_id ] ) ) {
					$font_variants[ $font_id ] = array();
				}

				$style = '';
				if ( $data['font-weight'] ) {
					$style .= $data['font-weight'];
				}

				if ( '' !== $data['font-style'] && 'normal' != $data['font-style'] ) {
					$style .= $data['font-style'];
				}

				if ( in_array( $style, $fonts[ $font_id ]['font_weights'] ) ) {
					$font_variants[ $font_id ][ $style ] = $style;
				}
			}

			if ( $for_editor ) {
				$selector = $settings['editor_selector'];
			} else {
				$selector = $settings['css_selector'];
			}
			if ( $selector ) {
				$css[] = onepress_typography_css( $data, $selector );
			}
		}
	}

	$_fonts = array();
	$_subsets = array();

	foreach ( $google_fonts as $font_id => $font ) {
		$name = str_replace( ' ', '+', $font['name'] );
		$variants = ( isset( $font_variants[ $font_id ] ) && ! empty( $font_variants[ $font_id ] ) ) ? $font_variants[ $font_id ] : array( 'regular' );

		$s = '';
		$v = array();
		if ( ! empty( $variants ) ) {
			foreach ( $variants as $_v ) {
				if ( $_v != 'regular' ) {
					switch ( $_v ) {
						case 'italic':
							$v[ $_v ] = '400i';
							break;
						default:
							$v[ $_v ] = str_replace( 'italic', 'i', $_v );
					}
				} else {
					$v[ $_v ] = '400';
				}
			}
		}

		if ( ! isset( $v['regular'] ) ) {
			$v['regular'] = '400';
		}

		if ( ! isset( $v['400'] ) ) {
			$v['400'] = '400';
		}

		$v = array_unique( $v );

		if ( ! empty( $v ) ) {
			$s .= ':' . join( ',', $v );
		}
		$_fonts[ $font_id ] = "{$name}" . $s;

		if ( isset( $font['subsets'] ) ) {
			$_subsets = array_merge( $_subsets, $font['subsets'] );
		}
	}

	$return = array(
		'url' => '',
		'code' => '',
	);

	if ( count( $_fonts ) ) {
		$url = $scheme . '://fonts.googleapis.com/css?family=' . join( $_fonts, '|' );
		if ( ! empty( $_subsets ) ) {
			$_subsets = array_unique( $_subsets );
			$url .= '&subset=' . join( ',', $_subsets );
		}
		$return['url'] = $url;
	}

	$return['code'] = join( " \n ", $css );

	if ( $echo ) {
		if ( $return['url'] ) {
			echo "<link id='wp-typo-google-font' href='" . $return['url'] . "' rel='stylesheet' type='text/css'>"; // WPCS: XSS ok.
		}

		if ( $return['code'] ) {
			echo '<style class="wp-typography-print-styles" type="text/css">' . "\n" . $return['code'] . "\n" . '</style>'; // WPCS: XSS ok.
		}
		return false;
	} else {
		return $return;
	}

}

/**
 * Automatic add Style to <head>.
 *
 * @since  1.0.0
 * @since 2.1.3
 *
 * @param boolean $echo
 * @param boolean $for_editor
 * @return bool|string
 */
function onepress_typography_print_styles() {
	onepress_typography_render_style( true, false );
}

/**
 * Create CSS code
 *
 * @param string $css
 * @param array  $selector
 * @return bool|string
 */
function onepress_typography_css( $css, $selector = array() ) {
	if ( ! is_array( $css ) || ! $selector ) {
		return false;
	}

	if ( isset( $css['font-family'] ) && '' != $css['font-family'] ) {
		$css['font-family'] = '"' . $css['font-family'] . '"';
	}

	$base_px = apply_filters( 'root_typography_css_base_px', 16 ); // 16px;

	$code = '';
	if ( is_array( $selector ) ) {
		$selector = array_unique( $selector );
		$code .= join( "\n", $selector );
	} else {
		$code .= $selector;
	}

	$code .= " { \n";

	foreach ( $css as $k => $v ) {
		if ( $v && ! is_array( $v ) ) {
			$code .= "\t{$k}: {$v};\n";
		}
	}

	if ( isset( $css['font-size'] ) && '' != $css['font-size'] ) {
		$rem = intval( $css['font-size'] ) / $base_px;
		$code .= "\tfont-size: {$rem}rem;\n";
	}

	$code .= ' }';
	return $code;
}

/**
 * Register settings for auto apply css to <head>
 *
 * @param $setting_key
 * @param string $css_selector
 * @param string $data_type
 */
/**
 * @param string $setting_key
 * @param string $css_selector
 * @param array  $default array(
 *   'font-family'     => '',
 *   'color'           => '',
 *   'font-style'      => '',
 *   'font-weight'     => '',
 *   'font-size'       => '',
 *   'line-height'     => '',
 *   'letter-spacing'  => '',
 *   'text-transform'  => '',
 *   'text-decoration' => '',
 * ).
 * @param string $data_type
 * @param string $editor_selector
 */
function onepress_typography_helper_auto_apply( $setting_key, $css_selector = '', $default = null, $data_type = 'theme_mod', $editor_selector = '' ) {
	global $wp_typography_auto_apply;
	$wp_typography_auto_apply[ $setting_key ] = array(
		'key'             => $setting_key,
		'css_selector'    => $css_selector,
		'editor_selector' => $editor_selector,
		'data_type'       => ( $data_type ) ? $data_type : 'theme_mod',
		'default'         => $default,
	);
}


