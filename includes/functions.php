<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Determine if the pricing table has a featured pricing option
 */
function edd_pricing_tables_has_featured( $download_id = 0 ) {

	if ( ! $download_id ) {
		return false;
	}

	$has_featured = false;

	$price_options = edd_get_variable_prices( $download_id );

	if ( $price_options ) {
		foreach ( $price_options as $price_id => $price_option ) {

			if ( ! empty( $price_option['featured_text'] ) ) {
				$has_featured = true;
				break;
			}

		}
	}

	return $has_featured;

}

/**
 * Filters the price
 * Wraps currency symbol in <sup> and price amount in <span>
 *
 * @since 1.0.0
 */
function edd_pricing_tables_filter_currency( $formatted, $currency, $price ) {

	// Prevent filter when returning discount amount at checkout.
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return $formatted;
	}

	$symbol = edd_currency_symbol( $currency );

	if ( $symbol ) {
		$formatted = '<sup>' . $symbol . '</sup>' . '<span class="price-amount">' . $price . '</span>';
	}

	return $formatted;

}
