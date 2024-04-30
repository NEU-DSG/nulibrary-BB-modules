<?php

$table_box_start = '';
$table_box_end   = '';
if ( 'standard' === $settings->border_type ) {
	$table_box_start = '<div class="fl-table-box">';
	$table_box_end   = '</div>';
}
?>
<div class="fl-table fl-table-border-type-standard fl-table-column-height-auto fl-table-straight">
	<?php

	$columns = count( $settings->table_columns );

	for ( $i = 0; $i < $columns; $i++ ) :

		if ( ! is_object( $settings->table_columns[ $i ] ) ) {
			continue;
		}

		?>

		<div class="fl-table-col-<?php echo $columns; ?> fl-table-wrap">
			<div class="fl-table-column fl-table-column-<?php echo $i; ?>">
				<div class="fl-table-inner-wrap">
					<?php
						echo $table_box_start;
						$module->render_title( $i );
						$module->render_content( $i );
						echo $table_box_end;
					?>
				</div>
			</div>
		</div>
		<?php
	endfor;
	?>
</div>
