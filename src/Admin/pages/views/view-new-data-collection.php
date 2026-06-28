<?php

defined( 'ABSPATH' ) || exit;

if ( ! isset( $collection_blueprints ) ) {
	$collection_blueprints = array();
}
?>
<style>
	.settings-card {
		border: 1px solid #ccc;
		padding: 20px;
		margin-bottom: 20px;
		background-color: #fff;
		box-shadow: 0 1px 1px rgba(0,0,0,.04);
	}
	.settings-card__header {
		margin-bottom: 10px;
	}
	.settings-card__header h2 {
		margin: 0;
		font-size: 18px;
	}
	.settings-card__body {
		margin-top: 10px;
	}
	.settings-card__body label {
		display: block;
		margin-bottom: 10px;
	}
	.settings-card__body input[type="text"] {
		width: 100%;
		padding: 8px;
		box-sizing: border-box;
	}
	.settings-card__body fieldset {
		border: 1px solid #ccc;
		padding: 10px;
	}
	.settings-card__body legend {
		font-weight: bold;
	}
	.settings-card__body ul {
		list-style-type: disc;
		margin-left: 20px;
	}
</style>
<div class="wrap">
	<h1>New Data Collection</h1>
	<p>Choose a blueprint and create a collection that can power intelligent data experiences.
	<form method="post" action="">
		<div class="collection-details settings-card">
			<div class="settings-card__header">
				<h2>Collection Details</h2>
			</div>
			<div class="settings-card__body">
				<label>
					Collection Name
					<input name="collection_name" type="text" value="" class="regular-text" required>
				</label>
			</div>
		</div>
		<div class="collection-blueprint settings-card">
			<div class="settings-card__header">
				<h2>Choose a Collection Blueprint</h2>
			</div>
			<div class="settings-card__body">
				<p></p>
				<fieldset>
					<legend><span>Select a starting point for your data collection to pre-configure records and syncing.</span></legend>
					<?php foreach ( $collection_blueprints as $blueprint ) : ?>
						<label>
							<input type="radio" name="collection_blueprint" value="<?php echo esc_attr( $blueprint->get_name() ); ?>" required>
							<?php echo esc_html( $blueprint->get_name() ); ?>
							<?php echo esc_html( $blueprint->get_description() ); ?>
							<?php 
								$blueprint_class = $blueprint->get_blueprint_class();
								$blueprint_class::init( array() );
								if ( class_exists( $blueprint_class ) ) :
									$searchable_attributes = $blueprint_class::get_searchable_attributes();
							?>
							<ul>
								<?php foreach ( $searchable_attributes as $attribute ) : ?>
									<li><?php echo esc_html( $attribute ); ?></li>
								<?php endforeach; ?>
							</ul>
							<?php
									$blueprint_settings = $blueprint->get_blueprint_settings();
									if ( ! empty( $blueprint_settings ) ) :
										foreach ( $blueprint_settings as $setting ) : ?>
											<?php echo render_collection_blueprint_setting( $setting ); ?>
										<?php endforeach;
									endif;
								endif;
							?>
						</label><br>
					<?php endforeach; ?>
				</fieldset>
			</div>
		</div>
		<?php submit_button( 'Create Data Collection' ); ?>
	</form>
</div>