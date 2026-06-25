<?php
/**
 * A minimum implmentation of the OperatorInterace.
 *
 * @package WooSearch\Engine\Operators
 */

namespace WooSearch\Engine\Operators;

use WooSearch\Engine\Operators\Operator_Interface;
use WooSearch\Indicies\Record_Index_Interface;

/**
 * AbstractOperator implements the minimum viable implementation of the OperatorInterface.
 */
abstract class Abstract_Operator implements Operator_Interface {

	/**
	 * Initialize a Record Index.
	 *
	 * @param Record_Index_Interface $index The index to initialize.
	 * @return void
	 */
	public function init_index( Record_Index_Interface $index ) {

		/**
		 * Perform validation of the index before changes are commited to the search operator.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woo_search_operator_pre_flight_validation', $index );
	}

	/**
	 * Validate a Record Index.
	 *
	 * @param Record_Index_Interface $index The Index to validate.
	 * @return void
	 */
	public static function validate_index( Record_Index_Interface $index ) {
		$index->validate_index();
	}
}

add_action( 'woo_search_operator_pre_flight_validation', array( Abstract_Operator::class, 'validate_index' ) );
