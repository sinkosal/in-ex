<?php

use
	OSC\DoctorType\Collection
		as DoctorCol
	, OSC\DoctorType\Object
		as DoctorObj
;

class RestApiDoctorType extends RestApi {

	public function get($params){
		$col = new DoctorCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$col->filterById($params['GET']['id']);
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$showDataPerPage = 30;
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
		$obj = new DoctorObj();
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
		$obj = new DoctorObj();
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
		$obj = new DoctorObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

	public function delete(){
		$obj = new DoctorObj();
		$obj->delete($this->getId());
	}

}
