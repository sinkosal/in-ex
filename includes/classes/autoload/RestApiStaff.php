<?php

use
	OSC\Staff\Collection
		as StaffCol
	, OSC\Staff\Object
		as StaffObj
;

class RestApiStaff extends RestApi {

	public function get($params){
		$col = new StaffCol();
		// start limit page
		$col->sortByName('DESC');
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$col->filterByName($params['GET']['name']);
		$col->filterByType($params['GET']['type']);
		$col->filterById($params['GET']['id']);
		$params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
		if($params['GET']['pagination']){
			$showDataPerPage = 10;
			$start = $params['GET']['start'];
			$this->applyLimit($col,
				array(
					'limit' => array( $start, $showDataPerPage )
				)
			);
		}
		return $this->getReturn($col, $params);
	}
	public function post($params){
		$obj = new StaffObj();
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
		$obj = new StaffObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setProperties($params['PUT']);
		$obj->update();
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function delete(){
		$obj = new StaffObj();
		$obj->delete($this->getId());
	}

	public function patch($params){
		$obj = new StaffObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

}
