<?php

use
	OSC\CustomerListOnly\Collection as CustomerListCol
;

class RestApiCustomerListOnly extends RestApi {

	public function get($params){
		$col = new CustomerListCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['name'] ? $col->filterByName($params['GET']['name']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$showDataPerPage = 50;
		$start = $params['GET']['start'];
		$this->applyLimit($col,
			array(
				'limit' => array( $start, $showDataPerPage )
			)
		);
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

}
