<?php

	if ( 'equalize' === $settings->column_height ) :
		?>
		.fl-node-<?php echo $id; ?> .fl-table-column,
		.fl-node-<?php echo $id; ?> .fl-table-inner-wrap {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.fl-node-<?php echo $id; ?> .fl-table-features {
			flex: 1;
		}
		<?php
	endif;

	// Spacing
	FLBuilderCSS::rule( array(
		'selector' => ".fl-node-$id .fl-table .fl-table-wrap",
		'media'    => 'default',
		'enabled'  => ! empty( $settings->advanced_spacing_right ) || ! empty( $settings->advanced_spacing_left ),
		'props'    => array(
			'padding-top'    => '0',
			'padding-right'  => ! empty( $settings->advanced_spacing_right ) ? $settings->advanced_spacing_right . 'px' : '0',
			'padding-bottom' => '0',
			'padding-left'   => ! empty( $settings->advanced_spacing_left ) ? $settings->advanced_spacing_left . 'px' : '0',
		),
	) );

	FLBuilderCSS::rule( array(
		'selector' => ".fl-node-$id .fl-table .fl-table-wrap",
		'media'    => 'medium',
		'enabled'  => ! empty( $settings->advanced_spacing_right_medium ) || ! empty( $settings->advanced_spacing_left_medium ),
		'props'    => array(
			'padding-top'    => '0',
			'padding-right'  => ! empty( $settings->advanced_spacing_right_medium ) ? $settings->advanced_spacing_right_medium . 'px' : '0',
			'padding-bottom' => '0',
			'padding-left'   => ! empty( $settings->advanced_spacing_left_medium ) ? $settings->advanced_spacing_left_medium . 'px' : '0',
		),
	) );

	FLBuilderCSS::rule( array(
		'selector' => ".fl-node-$id .fl-table .fl-table-wrap",
		'media'    => 'responsive',
		'enabled'  => ! empty( $settings->advanced_spacing_right_responsive ) || ! empty( $settings->advanced_spacing_left_responsive ),
		'props'    => array(
			'padding-top'    => '0',
			'padding-right'  => ! empty( $settings->advanced_spacing_right_responsive ) ? $settings->advanced_spacing_right_responsive . 'px' : '0',
			'padding-bottom' => '0',
			'padding-left'   => ! empty( $settings->advanced_spacing_left_responsive ) ? $settings->advanced_spacing_left_responsive . 'px' : '0',
		),
	) );
	?>
	<?php
	// Legacy Border
	if ( empty( $settings->border_type ) || 'legacy' === $settings->border_type ) :
		?>

		/*Curvy Boxes*/
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-rounded .fl-table-column {
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-rounded .fl-table-inner-wrap {
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
		}

		/*Large*/
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-large .fl-table-inner-wrap {
			margin: 12px;
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-large.fl-table-column-height-equalize .fl-table-column {
			/* padding-bottom: 24px; */
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-large .fl-table-column .fl-table-content {
			margin: 0 -15px;
		}
		/*adjust for no spacing*/
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-large.fl-table-spacing-none .fl-table-column .fl-table-content {
			margin: 0 -14px;
		}

		/*Medium*/
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-medium .fl-table-inner-wrap {
			margin: 6px;
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-medium.fl-table-column-height-equalize .fl-table-column {
			/* padding-bottom: 12px; */
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-medium .fl-table-column .fl-table-content {
			margin: 0 -9px;
		}

		/*Small Border*/
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-small .fl-table-column {
			border: 0 !important;
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-small .fl-table-inner-wrap {
			margin: 0px;
		}
		.fl-node-<?php echo $id; ?> .fl-table.fl-table-border-small .fl-table-column .fl-table-content {
			margin: 0 -1px;
		}
	<?php elseif ( 'standard' === $settings->border_type ) : ?>

		.fl-node-<?php echo $id; ?> .fl-table .fl-table-inner-wrap {
			margin: 0;
		}
		.fl-node-<?php echo $id; ?> .fl-table-box {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		<?php
		FLBuilderCSS::border_field_rule( array(
			'settings'     => $settings,
			'setting_name' => 'standard_border',
			'selector'     => ".fl-node-$id .fl-table .fl-table-inner-wrap",
		) );

		// Border Width
		if ( ! empty( $settings->standard_border['width'] ) ) :
			$border_width          = $settings->standard_border['width'];
			$content_bar_margin_left = '';
			if ( ! empty( $border_width['left'] ) ) {
				$content_bar_margin_left = 'margin-left: -' . ( $border_width['left'] ) . 'px;';
			}

			$content_bar_margin_right = '';
			if ( ! empty( $border_width['right'] ) ) {
				$content_bar_margin_right = 'margin-right: -' . ( $border_width['right'] ) . 'px;';
			}
			?>
			.fl-node-<?php echo $id; ?> .fl-table .fl-table-column .fl-table-content {
				<?php
				echo $content_bar_margin_left;
				echo $content_bar_margin_right;
				?>
			}
			<?php
		endif;

		// Border Radius
		if ( ! empty( $settings->standard_border['radius'] ) ) :
			$standard_border_rad = $settings->standard_border['radius'];
			$border_radius       = array(
				'top_left'     => empty( $standard_border_rad['top_left'] ) ? '0' : ( 'border-top-left-radius: ' . $standard_border_rad['top_left'] . 'px;' ),
				'top_right'    => empty( $standard_border_rad['top_right'] ) ? '0' : ( 'border-top-right-radius: ' . $standard_border_rad['top_right'] . 'px;' ),
				'bottom_left'  => empty( $standard_border_rad['bottom_left'] ) ? '0' : ( 'border-bottom-left-radius: ' . $standard_border_rad['bottom_left'] . 'px;' ),
				'bottom_right' => empty( $standard_border_rad['bottom_right'] ) ? '0' : ( 'border-bottom-right-radius: ' . $standard_border_rad['bottom_right'] . 'px;' ),
			);
			?>
			.fl-node-<?php echo $id; ?> .fl-table .fl-table-column {
			<?php
			foreach ( $border_radius as $br ) {
				echo $br;
			}
			?>
			}
			<?php
		endif;

		if ( 'equalize' === $settings->column_height ) :
			?>
			.fl-node-<?php echo $id; ?> .fl-table .fl-table-features {
				padding-bottom: 30px;
			}
			<?php
		endif;

	endif;
	?>


<?php
// Loop through and style each table box
$total_table_cols = count( $settings->table_columns );
for ( $i = 0; $i < $total_table_cols; $i++ ) :

	if ( ! is_object( $settings->table_columns[ $i ] ) ) {
		continue;
	}

	// table Box Settings
	$table_column = $settings->table_columns[ $i ];

	?>

	/*table Box Style*/
	<?php
	$box_border_color = empty( $table_column->background ) ? '' : $table_column->background;
	if ( ! empty( $settings->border_type ) && 'legacy' === $settings->border_type ) :
		$box_border_color = empty( $table_column->background ) ? '' : $table_column->background;

		FLBuilderCSS::responsive_rule( array(
			'settings'     => $table_column,
			'setting_name' => 'pbox_top_margin',
			'selector'     => ".fl-node-$id .fl-table-column-$i",
			'prop'         => 'margin-top',
			'unit'         => 'px',
		) );
		?>
		.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> {
			border: 1px solid <?php echo FLBuilderColor::hex_or_rgb( FLBuilderColor::adjust_brightness( $box_border_color, 30, 'darken' ) ); ?>;
			background: <?php echo FLBuilderColor::hex_or_rgb( $box_border_color ); ?>;
		}
		.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> .fl-table-inner-wrap {
			border-width: 1px;
			border-style: solid;
			border-color: <?php echo FLBuilderColor::hex_or_rgb( FLBuilderColor::adjust_brightness( $box_border_color, 30, 'darken' ) ); ?>;
		}
	<?php endif; ?>

	.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> .fl-table-inner-wrap {
		background: <?php echo FLBuilderColor::hex_or_rgb( $table_column->foreground ); ?>;
	}

	<?php if ( ! empty( $table_column->title_color ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> h2.fl-table-title {
			color: <?php echo FLBuilderColor::hex_or_rgb( $table_column->title_color ); ?>;
		}
	<?php endif; ?>

	<?php if ( empty( $table_column->title_typography->font_size->length ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> h2 {
			font-size: <?php echo ( empty( $table_column->title_size ) ? '24' : $table_column->title_size ); ?>px;
		}
	<?php endif; ?>
	<?php if ( ! empty( $table_column->content_color ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-table .fl-table-wrap .fl-table-column-<?php echo $i; ?> .fl-table-content  {
			color: <?php echo FLBuilderColor::hex_or_rgb( $table_column->content_color ); ?>;
		}
	<?php endif; ?>
	<?php
		FLBuilderCSS::typography_field_rule( array(
			'settings'     => $table_column,
			'setting_name' => 'title_typography',
			'selector'     => ".fl-node-$id .fl-table-column-$i h2.fl-table-title",
		) );
	?>
	<?php if ( empty( $table_column->content_typography->font_size->length ) ) : ?>
		.fl-node-<?php echo $id; ?> .fl-table-column-<?php echo $i; ?> .fl-table-content {
			font-size: <?php echo ( empty( $table_column->content_size ) ? '1' : $table_column->content_size ); ?>em;
		}
	<?php endif; ?>
	<?php
		FLBuilderCSS::typography_field_rule( array(
			'settings'     => $table_column,
			'setting_name' => 'content_typography',
			'selector'     => ".fl-node-$id .fl-table-column-$i .fl-table-content",
		) );
	?>


<?php endfor; ?>
