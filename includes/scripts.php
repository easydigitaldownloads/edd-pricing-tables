<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Print scripts
 *
 * @since 1.0
*/
function edd_pricing_tables_scripts() {
	wp_register_style( 'edd-pricing-tables', EDD_PT_PLUGIN_URL . 'assets/css/edd-pricing-tables.css', array(), EDD_PT_VERSION, 'screen' );
	wp_enqueue_style( 'edd-pricing-tables' );
}
add_action( 'wp_enqueue_scripts', 'edd_pricing_tables_scripts', 100 );

/**
 * Show or hide the table row based on the checkbox option
 *
 * @since 1.0.0
 */
function edd_pricing_tables_admin_scripts() {

	$screen = get_current_screen();

	if ( $screen->id !== 'download' ) {
		return;
	}

	?>
	<script>

		jQuery(document).ready(function($) {

			// All options are hidden by default with display:none on the div
			// When "Create pricing table" is checked it will show these options
			var enablePricingTable = $('#edd-pricing-table');

			// The "Show advanced options" checkbox
			var enableAdvancedOptions = $('#edd-pricing-table-advanced-options');

			// The pricing options
			var pricingTableOptions = $('#edd-pricing-tables-variable-pricing-options');

			// Store all the advanced options
			var advancedOptions = $('.edd-pricing-tables-option-advanced');

			// Advanced options wrap
			var optionAdvancedOptions = $('#edd-pricing-tables-advanced-options');

			// Show or hide the pricing table options
			enablePricingTable.click( function() {

				if ( this.checked ) {
					$( pricingTableOptions ).show();
					$( optionAdvancedOptions ).show();
				} else {
					$( pricingTableOptions ).hide();
					$( optionAdvancedOptions ).hide();
				}

			});

			// Show or hide the pricing table options on page load
			if ( enablePricingTable.is(':checked') ) {
				$( pricingTableOptions ).show();
				$( optionAdvancedOptions ).show();
			} else {
				$( pricingTableOptions ).hide();
				$( optionAdvancedOptions ).hide();
			}

			// Show or hide the advanced options
			enableAdvancedOptions.click( function() {

				if ( this.checked ) {
					$( advancedOptions ).show();
				} else {
					$( advancedOptions ).hide();
				}

			});

			// Show or hide the advanced options on page load
			if ( enableAdvancedOptions.is(':checked') ) {
				$( advancedOptions ).show();
			} else {
				$( advancedOptions ).hide();
			}

		});
	</script>

	<?php
}
add_action( 'in_admin_footer', 'edd_pricing_tables_admin_scripts' );
