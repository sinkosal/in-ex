<?php

use
	OSC\DoctorListOnly\Collection as DoctorCol
;

class RestApiDoctorListOnly extends RestApi {

	public function get($params){
		$col = new DoctorCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$showDataPerPage = 10;
		if($params['GET']['pagination']){
			$start = $params['GET']['start'];
			$this->applyLimit($col,
				array(
					'limit' => array( $start, $showDataPerPage )
				)
			);
		}
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

}
