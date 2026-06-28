<?php

function render_inner_blocks( $block ) {
	$inner_blocks = $block->parsed_block['innerBlocks'] ?? array();

	return implode( '', array_map('render_block', $inner_blocks ) );
}

function render_collection_blueprint_setting( $setting ) {

	ob_start();
	?>
	<div class="collection-blueprint-setting">
		<label for="<?php echo esc_attr( $setting['key'] ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
		<?php if ( $setting['type'] === 'text' ) : ?>
			<input type="text" name="<?php echo esc_attr( $setting['key'] ); ?>" id="<?php echo esc_attr( $setting['key'] ); ?>" value="<?php echo esc_attr( $setting['default'] ); ?>" <?php echo $setting['required'] ? 'required' : ''; ?>>
		<?php elseif ( $setting['type'] === 'select' ) : ?>
			<select name="<?php echo esc_attr( $setting['key'] ); ?>" id="<?php echo esc_attr( $setting['key'] ); ?>" <?php echo $setting['required'] ? 'required' : ''; ?>>
				<?php foreach ( $setting['options'] as $option ) : ?>
					<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $option['value'], $setting['default'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}