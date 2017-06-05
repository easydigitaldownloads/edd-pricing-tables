<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Pricing_Tables_Admin {

	public function __construct() {
		add_filter( 'edd_metabox_fields_save', array( $this, 'save_meta' ) );
		add_action( 'edd_meta_box_price_fields', array( $this, 'settings' ), 100 );
	}

	/**
	 * Settings
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function settings( $download_id ) {

		?>
		<div id="edd-pricing-tables-options-wrap">

			<?php
			/**
			 * Enable pricing table
			 */
			$checked = get_post_meta( $download_id, '_edd_pricing_table', true );
			?>
			<p>
				<input type="checkbox" name="_edd_pricing_table" id="edd-pricing-table" value="1"<?php checked( true, $checked ); ?>/>
				<label for="edd-pricing-table">
					<?php _e( 'Create pricing table', 'edd-pricing-tables' ); ?>
				</label>
			</p>

			<?php
			/**
			 * Enable advanced options pricing table
			 */
			$advanced_options = get_post_meta( $download_id, '_edd_pricing_table_advanced_options', true );
			?>
			<p id="edd-pricing-tables-advanced-options" style="display: none;">
				<input type="checkbox" name="_edd_pricing_table_advanced_options" id="edd-pricing-table-advanced-options" value="1"<?php checked( true, $advanced_options ); ?>/>
				<label for="edd-pricing-table-advanced-options">
					<?php _e( 'Show advanced options', 'edd-pricing-tables' ); ?>
				</label>
			</p>

			<?php
			/**
			 * Variable pricing enabled
			 */
			if ( edd_has_variable_prices( $download_id ) ) : ?>

				<div id="edd-pricing-tables-variable-pricing-options" style="display: none;">
				<?php

				$variable_prices = edd_get_variable_prices( $download_id );

				if ( $variable_prices ) : ?>

					<?php foreach ( $variable_prices as $key => $price ) :

						$features = isset( $price['features'] ) ? $price['features'] : '';

						$pricing_option_name          = ! empty( $price['pricing_option_name'] ) ? $price['pricing_option_name'] : '';
						$pricing_option_description   = ! empty( $price['pricing_option_description'] ) ? $price['pricing_option_description'] : '';
						$pricing_option_period        = ! empty( $price['pricing_option_period'] ) ? $price['pricing_option_period'] : '';
						$featured_title               = ! empty( $price['featured_title'] ) ? $price['featured_title'] : '';
						$featured_text                = ! empty( $price['featured_text'] ) ? $price['featured_text'] : '';
						$button_text                  = ! empty( $price['button_text'] ) ? $price['button_text'] : '';
					?>
						<div style="border: 1px solid #e5e5e5; margin-bottom: 20px; padding: 0 20px 20px 20px;">

							<p><strong><?php echo esc_attr( $price['name'] ); ?></strong></p>

								<?php
								/**
								 * Pricing option name
								 */
								?>
								<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
									<p>
										<strong><label for="edd-pricing-table-pricing-option-name-<?php echo $key;?>"><?php _e( 'Pricing Option Name', 'edd-pricing-tables' ); ?></label></strong>
									</p>
									<input class="large-text" type="text" name="edd_variable_prices[<?php echo $key; ?>][pricing_option_name]" id="edd-pricing-table-pricing-option-name-<?php echo $key;?>" value="<?php echo esc_attr( $pricing_option_name ); ?>" />
									<p class="description"><?php _e( 'Entering a pricing option name here will override the variable pricing option name above.', 'edd-pricing-tables' );  ?></p>
								</div>

								<?php
								/**
								 * Pricing option description
								 */
								?>
								<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
									<p>
										<strong><label for="edd-pricing-table-pricing-option-description-<?php echo $key;?>"><?php _e( 'Pricing Option Description', 'edd-pricing-tables' ); ?></label></strong>
									</p>
									<input class="large-text" type="text" name="edd_variable_prices[<?php echo $key; ?>][pricing_option_description]" id="edd-pricing-table-pricing-option-description-<?php echo $key;?>" value="<?php echo esc_attr( $pricing_option_description ); ?>" />
									<p class="description"><?php _e( 'Enter a pricing option description.', 'edd-pricing-tables' );  ?></p>
								</div>

								<?php
								/**
								 * Period
								 */
								?>
								<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
									<p>
										<strong><label for="edd-pricing-table-pricing-option-name-<?php echo $key;?>"><?php _e( 'Pricing Option Period', 'edd-pricing-tables' ); ?></label></strong>
									</p>
									<input class="large-text" type="text" name="edd_variable_prices[<?php echo $key; ?>][pricing_option_period]" id="edd-pricing-table-pricing-option-period-<?php echo $key;?>" value="<?php echo esc_attr( $pricing_option_period ); ?>" />
									<p class="description"><?php _e( 'Entering a pricing option period. E.g. per year.', 'edd-pricing-tables' );  ?></p>
								</div>

								<?php
								/**
								 * Features
								 */
								?>
								<div class="edd-pricing-tables-option-wrap">
									<p>
										<strong><label for="edd-pricing-table-features-<?php echo $key;?>"><?php _e( 'Features', 'edd-pricing-tables' ); ?></label></strong>
									</p>

									<textarea style="width:100%;" rows="5" class="large-textarea" name="edd_variable_prices[<?php echo $key; ?>][features]" id="edd-pricing-table-features-<?php echo $key;?>"><?php echo esc_textarea( $features ); ?></textarea>
									<p class="description"><?php _e( 'Enter features for this pricing option, one per line.', 'easy-digital-downloads' ); ?></p>
								</div>

								<?php
								/**
								 * Featured text
								 */
								?>
								<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
									<p>
										<strong><label for="edd-pricing-table-featured-text-<?php echo $key;?>"><?php _e( 'Featured Text', 'edd-pricing-tables' ); ?></label></strong>
									</p>
									<input class="large-text" type="text" name="edd_variable_prices[<?php echo $key; ?>][featured_text]" id="edd-pricing-table-featured-text-<?php echo $key;?>" value="<?php echo esc_attr( $featured_text ); ?>" />
									<p class="description"><?php _e( 'Make this pricing option featured by entering text, E.g. Most popular.', 'edd-pricing-tables' );  ?></p>
								</div>

								<?php
								/**
								 * Button text
								 */
								?>
								<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
									<p>
										<strong><label for="edd-pricing-table-button-text-<?php echo $key;?>"><?php _e( 'Button Text', 'edd-pricing-tables' ); ?></label></strong>
									</p>
									<input class="large-text" type="text" name="edd_variable_prices[<?php echo $key; ?>][button_text]" id="edd-pricing-table-featured-text-<?php echo $key;?>" value="<?php echo esc_attr( $button_text ); ?>" />
									<p class="description"><?php _e( 'E.g. Purchase', 'edd-pricing-tables' );  ?></p>
								</div>


						</div>
					<?php endforeach; ?>

				<?php endif; ?>
				</div>


			<?php else : // single download ?>

				<?php
				$features      = get_post_meta( $download_id, '_edd_pricing_tables_features', true );
				$featured_text = get_post_meta( $download_id, '_edd_pricing_tables_featured_text', true );
				$button_text   = get_post_meta( $download_id, '_edd_pricing_tables_button_text', true );
				$option_name   = get_post_meta( $download_id, '_edd_pricing_tables_option_name', true );
				$option_desc   = get_post_meta( $download_id, '_edd_pricing_tables_option_description', true );
				$option_period = get_post_meta( $download_id, '_edd_pricing_tables_option_period', true );
				?>
				<div>

					<?php
					/**
					 * Pricing option name
					 */
					?>
					<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
						<p>
							<strong><label for="edd-pricing-table-option-name"><?php _e( 'Pricing Option Name', 'edd-pricing-tables' ); ?></label></strong>
						</p>
						<input class="large-text" type="text" name="_edd_pricing_tables_option_title" id="edd-pricing-table-option-name" value="<?php echo esc_attr( $option_name ); ?>" />
						<p class="description"><?php _e( 'Entering an option title here will replace the download title on the pricing table.', 'edd-pricing-tables' );  ?></p>
					</div>

					<?php
					/**
					 * Pricing option description
					 */
					?>
					<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
						<p>
							<strong><label for="edd-pricing-table-option-description"><?php _e( 'Pricing Option Description', 'edd-pricing-tables' ); ?></label></strong>
						</p>
						<input class="large-text" type="text" name="_edd_pricing_tables_option_description" id="edd-pricing-table-option-description" value="<?php echo esc_attr( $option_desc ); ?>" />
						<p class="description"><?php _e( 'Enter a pricing option description.', 'edd-pricing-tables' );  ?></p>
					</div>

					<?php
					/**
					 * Pricing period
					 */
					?>
					<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
						<p>
							<strong><label for="edd-pricing-table-option-period"><?php _e( 'Pricing Option Period', 'edd-pricing-tables' ); ?></label></strong>
						</p>
						<input class="large-text" type="text" name="_edd_pricing_tables_option_period" id="edd-pricing-table-option-period" value="<?php echo esc_attr( $option_period ); ?>" />
						<p class="description"><?php _e( 'Entering a pricing option period. E.g. per year.', 'edd-pricing-tables' );  ?></p>
					</div>

					<?php
					/**
					 * Features
					 */
					?>
					<textarea style="width:100%;" rows="5" class="large-textarea" name="_edd_pricing_tables_features" id="edd-pricing-table-features-field"><?php echo esc_textarea( $features ); ?></textarea>
					<p><?php _e( 'Enter one feature per line.', 'easy-digital-downloads' ); ?></p>

					<?php
					/**
					 * Featured text
					 */
					?>
					<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
						<p>
							<strong><label for="edd-pricing-table-featured-text"><?php _e( 'Featured Text', 'edd-pricing-tables' ); ?></label></strong>
						</p>
						<input class="large-text" type="text" name="_edd_pricing_tables_featured_text" id="edd-pricing-table-featured-text" value="<?php echo esc_attr( $featured_text ); ?>" />
						<p class="description"><?php _e( 'Make this pricing option featured by entering text, E.g. Most popular.', 'edd-pricing-tables' );  ?></p>
					</div>

					<?php
					/**
					 * Button text
					 */
					?>
					<div class="edd-pricing-tables-option-wrap edd-pricing-tables-option-advanced" style="display: none;">
						<p>
							<strong><label for="edd-pricing-table-button-text"><?php _e( 'Button Text', 'edd-pricing-tables' ); ?></label></strong>
						</p>
						<input class="large-text" type="text" name="_edd_pricing_tables_button_text" id="edd-pricing-table-button-text" value="<?php echo esc_attr( $button_text ); ?>" />
						<p class="description"><?php _e( 'E.g. Purchase', 'edd-pricing-tables' );  ?></p>
					</div>

				</div>

			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Save meta
	 */
	public function save_meta( $fields ) {

		$fields[] = '_edd_pricing_table';
		$fields[] = '_edd_pricing_table_advanced_options';

		// Single download
		$fields[] = '_edd_pricing_tables_features';
		$fields[] = '_edd_pricing_tables_featured_text';
		$fields[] = '_edd_pricing_tables_button_text';
		$fields[] = '_edd_pricing_tables_option_name';
		$fields[] = '_edd_pricing_tables_option_description';
		$fields[] = '_edd_pricing_tables_option_period';


		return $fields;

	}

}
new EDD_Pricing_Tables_Admin;
