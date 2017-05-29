<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Pricing_Tables_Frontend {

	public function __construct() {
		add_action( 'edd_pricing_tables_footer_end', array( $this, 'purchase_button' ) );
	}

	/**
	 * Build the pricing table
	 */
	public function edd_pricing_table( $args = array() ) {

		// Single download, perhaps variable priced
		$download_id = isset( $args['id'] ) ? $args['id'] : '';

		// Multiple downloads
		$download_ids = isset( $args['ids'] ) ? $args['ids'] : '';

		// Filters the currency to wrap the currency symbol in a <sup> tag and the price in a <span> tag.
		add_filter( 'edd_' . strtolower( edd_get_currency() ) . '_currency_filter_before', 'edd_pricing_tables_filter_currency', 10, 3 );

		if ( $download_id ) {

			// one download to build a table with, could have variable priced options
			$this->pricing_table_single( $download_id );

		} elseif ( $download_ids ) {

			// to do: multiple download support

		}

		$html = ob_get_clean();
		return $html;
	}

	/**
	 * Pricing table for a single download with variable pricing options
	 */
	public function pricing_table_single( $download_id ) {

		$pricing_table_enabled = get_post_meta( $download_id, '_edd_pricing_table', true );

		if ( ! $pricing_table_enabled )	{
			return;
		}

		// variable priced download
		$price_options = edd_get_variable_prices( $download_id );

		$classes = array( 'edd-pricing-table' );
		$count   = count( $price_options );
		$has_featured = edd_pricing_tables_has_featured( $download_id );

		$classes[] = 'edd-pricing-table-columns-' . $count;
		$classes[] = $count % 2 == 0 ? 'even' : 'odd';
		$classes[] = $has_featured ? 'has-featured' : '';
		?>

		<div class="<?php echo implode( ' ', array_filter( $classes ) ); ?>">

		<?php if ( $price_options ) : ?>

			<?php foreach ( $price_options as $price_id => $price_option ) :

				// Period.
				$period = isset( $price_option['pricing_option_period'] ) ? $price_option['pricing_option_period'] : '';

				// Price.
				$price = edd_currency_filter( edd_format_amount( $price_option['amount'], false ) );

				// Features.
				$features = isset( $price_option['features'] ) ? $price_option['features'] : '';
				$features = explode( PHP_EOL, $price_option['features'] );
				$features = array_filter( $features );

				// Featured text.
				$featured_text = ! empty( $price_option['featured_text'] ) ? $price_option['featured_text'] : '';

				// Price description.
				$pricing_description = ! empty( $price_option['pricing_option_description'] ) ? $price_option['pricing_option_description'] : '';

				// Is this pricing option featured?
				$is_featured = $featured_text ? true : false;

				// New list item for each feature?
				$list_item_mode = true;

				// Price title.
				$pricing_option_name = ! empty( $price_option['pricing_option_name'] ) ? $price_option['pricing_option_name'] : '';
				$title               = $pricing_option_name ? $pricing_option_name : $price_option['name'];

				// Button text.
				$button_text = ! empty( $price_option['button_text'] ) ? $price_option['button_text'] : __( 'Purchase', 'edd-pricing-tables' );

				$args = array(
					'price_id'     => $price_id,
					'download_id'  => $download_id,
					'button_text'  => $button_text
				);

				// Set up classes.
				$classes = array( 'edd-price-option', 'edd-price-option-' . $price_id );
				$classes[] = $is_featured ? 'featured' : '';
				$classes[] = edd_item_in_cart( $download_id, array( 'price_id' => $price_id ) ) ? 'in-cart' : '';
				?>

				 <div class="<?php echo implode( ' ', array_filter( $classes ) ); ?>">
					 <div>
						<?php if ( $is_featured ) : ?>
							<span class="featured-text"><?php echo esc_attr( $featured_text ); ?></span>
						<?php endif; ?>

						<span class="edd-pt-title"><?php echo $title; ?></span>

						<?php do_action( 'edd_pricing_tables_after_title', $args ); ?>

						<?php if ( $pricing_description ) : ?>
							<span class="edd-pt-description"><?php echo $pricing_description; ?></span>
						<?php endif; ?>

						<ul>

							<li class="pricing">
								<span class="price">
									<?php echo $price; ?>
								</span>

								<?php if ( $period ) : ?>
								<span class="period"><?php echo $period; ?></span>
								<?php endif; ?>


								<?php do_action( 'edd_pricing_tables_pricing_end', $args ); ?>
							</li>

							<?php do_action( 'edd_pricing_tables_features_start', $price_id ); ?>

							<?php if ( $list_item_mode ) : $i = 0; ?>

								<?php foreach ( $features as $feature ) : $i++; ?>
								<li class="edd-pt-feature<?php echo ' edd-pt-feature-' . $i; ?>"><?php echo $feature; ?></li>
								<?php do_action( 'edd_pricing_tables_after_feature_' . $i, $price_id ); ?>

								<?php endforeach; ?>

							<?php else : ?>

								<li class="features">
									<?php echo implode( '<br>', $features ); ?>
								</li>

							<?php endif; ?>

							<?php do_action( 'edd_pricing_tables_features_end', $price_id ); ?>

						</ul>

						<div class="footer">
							<?php do_action( 'edd_pricing_tables_footer_end', $args ); ?>
						</div>

					 </div>

				 </div>

			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Purchase button.
	 */
	public function purchase_button( $args = array() ) {

		$defaults = array();

		$args = wp_parse_args( $args, $defaults );

		$download_id = isset( $args['download_id'] ) ? $args['download_id'] : '';

		$checkout_url = function_exists( 'edd_get_checkout_uri' ) ? edd_get_checkout_uri() : '';
		$download_url = add_query_arg( array( 'edd_action' => 'add_to_cart', 'download_id' => $download_id ), $checkout_url );

		$price_id = isset( $args['price_id'] ) ? $args['price_id'] : '';

		if ( $price_id ) {
			$download_url .= '&amp;edd_options[price_id]=' . $price_id;
		}

		$in_cart = edd_item_in_cart( $download_id, array( 'price_id' => $price_id ) );

		$button_text = $in_cart ? apply_filters( 'edd_pricing_tables_complete_purchase_text', __( 'Checkout', 'edd-pricing-tables' ) ) : $args['button_text'];

		$classes = array( 'edd-pt-button' );
		$classes[] = 'button';

		$classes = $classes ? ' class="' . implode( ' ', $classes ) . '"' : '';
		?>

		<a<?php echo $classes; ?> href="<?php echo $download_url; ?>"><?php echo $button_text; ?></a>
		<?php
	}

}
