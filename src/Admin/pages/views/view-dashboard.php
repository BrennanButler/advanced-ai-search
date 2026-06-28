<?php

defined( 'ABSPATH' ) || exit;

if ( ! isset( $data_collections ) ) {
	$data_collections = array();
}

?>
<table>
	<tr>
		<th>Name</th>
		<th>Collection blueprint</th>
	</tr>
	<?php foreach ( $data_collections as $collection ) : ?>
		<tr>
			<td><?php echo esc_html( $collection->get_name() ); ?></td>
			<td><?php echo esc_html( $collection->get_collection_blueprint()->get_name() ); ?></td>
		</tr>
	<?php endforeach; ?>
	<?php if ( empty( $data_collections ) ) : ?>
		<tr>
			<td colspan="2">No data collections found.</td>
		</tr>
	<?php endif; ?>
</table>