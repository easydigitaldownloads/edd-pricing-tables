<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Pricing_Tables_Shortcodes {

	public function __construct() {
        add_shortcode( 'edd_pricing_table', array( $this, 'edd_pricing_table_shortcode' ) );
	}

    /**
    * [edd_pricing_table] shortcode
    *
    * @since  1.0
    */
	public function edd_pricing_table_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( array(
			'id'  => '',
			'ids' => '',
		), $atts, 'edd_pricing_table' );

		$edd_pricing_tables = new EDD_Pricing_Tables_Frontend;
		$content            = $edd_pricing_tables->edd_pricing_table( $atts );

		return do_shortcode( $content );

	}

}
new EDD_Pricing_Tables_Shortcodes;
