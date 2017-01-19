<?php

use
	OSC\Service\Collection
		as ServiceCol
	, OSC\Service\Object
		as ServiceObj
;

class RestApiService extends RestApi {

	public function get($params){
		$col = new ServiceCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$col->filterById($params['GET']['id']);
		$params['GET']['customer_type_id'] ? $col->filterByCustomerTypeId($params['GET']['customer_type_id']) : '';
		$showDataPerPage = 10;
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

	public function post($params){
		$obj = new ServiceObj();echo $_SESSION['user_name'];
		$obj->setCreateBy($_SESSION['user_name']);
		$obj->setProperties($params['POST']);
		$obj->insert();
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function put($params){
		$obj = new ServiceObj();
		$obj->setProperties($params['PUT']);
		$obj->update($this->getId());
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function patch($params){
		$obj = new ServiceObj();
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus($this->getId());
	}

}
