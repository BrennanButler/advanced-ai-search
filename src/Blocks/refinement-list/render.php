<?php
$wrapper_attributes = get_block_wrapper_attributes();
$searchId = $block->context['advanced-ai-search/search-id'];
?>
<div <?php echo $wrapper_attributes; ?> data-search-id="<?php echo esc_attr( $searchId ); ?>"></div>