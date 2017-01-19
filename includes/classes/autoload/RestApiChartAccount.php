<?php

use
	OSC\ChartAccount\Collection
		as ChartAccountCol
	, OSC\ChartAccount\Object
		as ChartAccountObj
;

class RestApiChartAccount extends RestApi {

	public function get($params){
		$col = new ChartAccountCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['name'] ? $col->filterByName($params['GET']['name']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['account_type_id'] ? $col->filterByAccountTypeId($params['GET']['account_type_id']) : '';
		$params['GET']['account_code'] ? $col->filterByAccountCode($params['GET']['account_code']) : '';
		$params['GET']['mix'] ? $col->filterByMix($params['GET']['mix']) : '';
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
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
		$obj = new ChartAccountObj();
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
		$obj = new ChartAccountObj();
		$this->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
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
		$obj = new ChartAccountObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

	public function delete(){
		$obj = new ChartAccountObj();
		$obj->delete($this->getId());
	}

}
