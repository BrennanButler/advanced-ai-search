<?php
/**
 * Reading time data source.
 *
 * @package WooSearch\RecordModel\DataSource
 */

namespace WooSearch\RecordModel\DataSource;

use WooSearch\Integrations\Record_Service_Integrations_Registry;
use WooSearch\RecordModel\DataSource\Data_Source_Interface;
/**
 * Reading_Time_Data_Source class.
 */
class Reading_Time_Data_Source implements Data_Source_Interface {

	/**
	 * The wordcount
	 *
	 * @var int
	 */
	protected int $word_count;

	/**
	 * Constructor.
	 *
	 * @param integer $word_count The word count.
	 */
	public function __construct( int $word_count ) {
		$this->word_count = $word_count;
	}

	/**
	 * Get the raw data for the operator.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$avg_words_per_minute = 238;

		$reading_time   = $this->word_count / $avg_words_per_minute;
		$read_time_text = $reading_time < 1 ? '1 Minute or less' : floor( $reading_time ) . ' Minutes or less';

		$reading_time_data = array(
			'value' => $reading_time,
			'text'  => $read_time_text,
		);

		return $reading_time_data;
	}
}

add_action(
	'woo_search_register_record_service_integrations',
	function ( Record_Service_Integrations_Registry $record_service_integrations_registry ) {

		$record_service_integrations_registry->register(
			array(
				'slug'                => 'reading-time-service',
				'name'                => 'Reading time Service',
				'description'         => 'Reading time Service',
				'service'             => Reading_Time_Data_Source::class,
				'index_type_supports' => array(
					'posttype-index' => array(),
					'woo-index' => array(),
				),
			)
		);
	}
);
