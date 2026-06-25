<?php

function render_inner_blocks( $block ) {
	$inner_blocks = $block->parsed_block['innerBlocks'] ?? array();

	return implode( '', array_map('render_block', $inner_blocks ) );
}