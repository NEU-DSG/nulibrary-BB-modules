<?php

define( 'FL_TABLES_URL', FLBuilder::plugin_url() . 'modules/tables/' );

/**
 * @class FLTablesModule
 */
class FLTablesModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Table', 'fl-builder' ),
			'description'     => __( 'A simple table generator.', 'fl-builder' ),
			'category'        => __( 'Layout', 'fl-builder' ),
			'partial_refresh' => true,
			'icon'            => 'editor-table.svg',
		));

		// Register custom fields.
		add_filter( 'fl_builder_custom_fields', function( $fields ) {
			$fields['fl-tables'] = __DIR__ . '/fields/fl-tables.php';
			return $fields;
		} );

	}

	/**
	 * since TBD
	 * @method register_features_field
	 */

	/**
	 * since TBD
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
			wp_enqueue_style( 'fl-tables-field', FL_TABLES_URL . 'css/tables.css', array(), '' );
		}
	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *
	 * @since 2.2
	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		// For table Modules made before version 2.5, set the Border Type = 'legacy'.
		if ( empty( $settings->border_type ) ) {
			$settings->border_type = 'legacy';
		}

		// For PT Modules made before version 2.5, set the Column Height = 'auto'.
		if ( empty( $settings->column_height ) ) {
			$settings->column_height = 'auto';
		}

		// Convert 'Spacing' (a select field) to 'Advanced Spacing' (a dimension field).
		if ( ! empty( $settings->spacing ) && ! isset( $settings->advanced_spacing_left ) && ! isset( $settings->advanced_spacing_right ) ) {
			if ( 'large' === $settings->spacing ) {
				$space = '12';
			} elseif ( 'medium' === $settings->spacing ) {
				$space = '6';
			} else {
				$space = '0';
			}
			$settings->advanced_spacing_left  = $space;
			$settings->advanced_spacing_right = $space;
		}
		// Uncomment below to remove the 'spacing' field. For now, just keep it.
		// unset( $settings->spacing );

		// Handle table column settings.
		$col_count = count( $settings->table_columns );
		for ( $i = 0; $i < $col_count; $i++ ) {

			if ( ! is_object( $settings->table_columns[ $i ] ) ) {
				continue;
			}

			$table_column = $settings->table_columns[ $i ];

			// Rename column field 'margin' to 'pbox_top_margin'
			if ( isset( $settings->table_columns[ $i ]->margin ) ) {
				$settings->table_columns[ $i ]->pbox_top_margin = $settings->table_columns[ $i ]->margin;
				unset( $settings->table_columns[ $i ]->margin );
			}


			// Convert table Size to table Typography.
			if ( ! empty( $table_column->table_size ) && empty( $table_column->table_typography->font_size->length ) ) {
				if ( ! empty( $table_column->table_typography ) && is_object( $settings->table_columns[ $i ]->table_typography ) ) {
					$settings->table_columns[ $i ]->table_typography->font_size->length = empty( $table_column->table_size ) ? '31' : $table_column->table_size;
					$settings->table_columns[ $i ]->table_typography->font_size->unit   = empty( $table_column->table_size_unit ) ? 'px' : $table_column->table_size_unit;
				}
			}


			// Convert 'features' field to 'extended_features'.
			$features_empty          = $this->is_features_empty( $table_column, 'features' );
			$extended_features_empty = $this->is_features_empty( $table_column, 'extended_features' );

			if ( ! $features_empty && $extended_features_empty ) {

				$extended_features = array();

				foreach ( $table_column->features as $feature ) {
					$feature_obj              = new stdClass;
					$feature_obj->description = $feature;
					$feature_obj->icon        = '';
					$feature_obj->tooltip     = '';

					$extended_features[] = $feature_obj;
				}

				$settings->table_columns[ $i ]->extended_features = $extended_features;

			}
		}

		return $settings;
	}

	/**
	 * Check if the table Column's 'features' or 'extended_features' is empty.
	 * This field was available prior to version 2.5 and was replaced by 'extended_features'.
	 *
	 * @since 2.5
	 * @method update
	 * @param object $table_column
	 * @method string is_features_empty
	 */
	private function is_features_empty( $table_column, $key = 'features' ) {
		$is_empty = true;

		if ( ! empty( $table_column->{ $key } ) && 'array' === gettype( $table_column->{ $key } ) ) {
			$is_empty = ( 1 === count( $table_column->{ $key } ) && empty( $table_column->{ $key }[0] ) );
		} else {
			$is_empty = empty( $table_column->{ $key } );
		}

		return $is_empty;
	}

	/**
	 * Returns an array of settings used to render a button module.
	 *
	 * @since 2.2
	 * @param object $table_column
	 * @return array
	 */
	public function get_button_settings( $table_column ) {

	}


	/**
	 * Returns the main container class.
	 *
	 * @since 2.5
	 * @method get_TABLES_class
	 * @param object $settings
	 * @return string
	 */
	public function get_TABLES_class() {
		$settings = $this->settings;

		$TABLES_class   = array();
		$TABLES_class[] = 'fl-table';

		if ( 'legacy' === $settings->border_type ) {
			$TABLES_class[] = 'fl-table-border-' . $settings->border_size;
			$TABLES_class[] = 'fl-table-border-type-legacy';
		} elseif ( 'standard' === $settings->border_type ) {
			$TABLES_class[] = 'fl-table-border-type-standard';
		}

		$TABLES_class[] = 'fl-table-column-height-' . $settings->column_height;
		$TABLES_class[] = 'fl-table-' . $settings->border_radius;

		return implode( ' ', $TABLES_class );
	}
	/**
	 * Render the title.
	 *
	 * @since 2.5
	 * @method render_title
	 * @param int $col_index
	 */
	public function render_title( $col_index ) {
		$settings       = $this->settings;
		$table_column = $settings->table_columns[ $col_index ];
		echo '<h2 class="fl-table-title">' . $table_column->title . '</h2>';
	}

		/**
		 * Render the title.
		 *
		 * @since 2.5
		 * @method render_content
		 * @param int $col_index
		 */
		public function render_content( $col_index ) {
			$settings       = $this->settings;
			$table_column = $settings->table_columns[ $col_index ];
			echo '<p class="fl-table-content">' . $table_column->table_content . '</p>';
		}
	/**
	 * Render the table.
	 *
	 * @since 2.5
	 * @method render_table
	 * @param int $col_index
	 */
	public function render_table( $col_index ) {
		$settings       = $this->settings;
		$table_column = $settings->table_columns[ $col_index ];

		$html = '<div class="fl-table-table">';
		if ( 'no' === $settings->dual_billing ) {
			$html .= ' ' . $table_column->table . ' ';
			$html .= '<span class="fl-table-duration">' . $table_column->duration . '</span>';
		} elseif ( 'yes' === $settings->dual_billing ) {
			$html .= '<span class="first_option-table">' . $table_column->table . '</span>';
			$html .= '<span class="second_option-table">' . $table_column->table_option_2 . '</span>';
		}
		$html .= '</div>';

		echo $html;
	}


}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('FLTablesModule', array(
	'columns' => array(
		'title'    => __( 'Table Boxes', 'fl-builder' ),
		'sections' => array(
			'table_options_section' => array(
				'title'  => __( 'Table Options', 'fl-builder' ),
				'fields' => array(
					'table_columns' => array(
						'type'         => 'form',
						'label'        => __( 'Table Box', 'fl-builder' ),
						'form'         => 'table_column_form',
						'preview_text' => 'title',
						'multiple'     => true,
					),
				),
			),

		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'fl-builder' ),
		'sections' => array(
			'general'              => array(
				'title'  => 'General Style',
				'fields' => array(
					'column_height'    => array(
						'type'    => 'select',
						'label'   => __( 'Column Height', 'fl-builder' ),
						'default' => '',    // See filter_settings() method.
						'options' => array(
							'equalize' => __( 'Equalize', 'fl-builder' ),
							'auto'     => __( 'Auto', 'fl-builder' ),
						),
						'toggle'  => array(
							'auto' => array(
								'fields' => array(
									'min_height',
								),
							),
						),
						'help'    => __( '"Equalize" sets the columns to have the same height as the largest column.', 'fl-builder' ),
					),
					'min_height'       => array(
						'type'    => 'unit',
						'label'   => __( 'Features Min Height', 'fl-builder' ),
						'default' => '0',
						'units'   => array( 'px' ),
						'slider'  => array(
							'max'  => 1000,
							'step' => 10,
						),
						'preview' => array(
							'type'      => 'css',
							'selector'  => '.fl-table-features',
							'property'  => 'min-height',
							'unit'      => 'px',
							'important' => true,
						),
						'help'    => __( 'Use this to normalize the height of your boxes when they have different numbers of features.', 'fl-builder' ),
					),
					'advanced_spacing' => array(
						'type'       => 'dimension',
						'label'      => __( 'Advanced Spacing', 'fl-builder' ),
						'default'    => '12',
						'units'      => array( 'px' ),
						'slider'     => true,
						'responsive' => true,
						'preview'    => array(
							'type' => 'refresh',
						),
					),
				),
			),

		),
	),
));

FLBuilder::register_settings_form('table_column_form', array(
	'title' => __( 'Add table Box', 'fl-builder' ),
	'tabs'  => array(
		'general' => array(
			'title'    => __( 'General', 'fl-builder' ),
			'sections' => array(
				'title'     => array(
					'title'  => __( 'Title', 'fl-builder' ),
					'fields' => array(
						'title' => array(
							'type'  => 'text',
							'label' => __( 'Title', 'fl-builder' ),
						),
						'table_content' => array(
							'type'        => 'editor',
							'label'       => '',
							'rows'        => 13,
							'wpautop'     => false,
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.fl-rich-text',
															'label' => __( 'Content', 'fl-builder' ),
							),
							'connections' => array( 'string' ),
						),
					),
				),


			),
		),
		'style'   => array(
			'title'    => __( 'Style', 'fl-builder' ),
			'sections' => array(
				'general_style_section' => array(
					'title'  => __( 'General Style', 'fl-builder' ),
					'fields' => array(
						'background'        => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Box Border', 'fl-builder' ),
							'default'     => 'F2F2F2',
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'foreground'        => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Box Color', 'fl-builder' ),
							'default'     => 'ffffff',
							'show_reset'  => true,
							'show_alpha'  => true,
						),

						'pbox_top_margin'   => array(
							'type'       => 'unit',
							'label'      => __( 'Box Top Margin', 'fl-builder' ),
							'default'    => '0',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
						),
					),
				),
				'title_style_section'   => array(
					'title'     => __( 'Title Style', 'fl-builder' ),
					'collapsed' => true,
					'fields'    => array(
						'title_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Title Color', 'fl-builder' ),
							'default'    => '333333',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => 'Title Typography',
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
					),
				),
				'content_style_section'   => array(
					'title'     => __( 'Content Style', 'fl-builder' ),
					'collapsed' => true,
					'fields'    => array(
						'content_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Content Color', 'fl-builder' ),
							'default'    => '333333',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'content_typography' => array(
							'type'       => 'typography',
							'label'      => 'Content Typography',
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
	),
));
