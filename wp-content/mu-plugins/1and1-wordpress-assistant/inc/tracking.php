<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Assistant_Tracking {

	/**
	 * Initialize One_And_One_Assistant_Tracking class
	 */
	public function init() {
		add_filter( 'affilinet_subid_array', array( $this, 'add_sub_id_to_affilinet_performance_ads' ) );
	}

	/**
	 * Add Affilinet performance ads SubID
	 *
	 * @param array $sub_ids list of SubIDs
	 */
	public function add_sub_id_to_affilinet_performance_ads( $sub_ids ) {
		$sub_ids[] = '1und1';

		return $sub_ids;
	}
}
