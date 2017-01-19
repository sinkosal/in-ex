<?php

use
	OSC\CustomerType\Collection
		as CustomerTypeCol
	, OSC\CustomerType\Object
		as CustomerTypeObj
;

class RestApiCustomerType extends RestApi {

	public function get($params){
		$col = new CustomerTypeCol();
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['name'] ? $col->filterByName($params['GET']['name']) : '';
		if($params['GET']['paginate']){
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
		$obj = new CustomerTypeObj();
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
		$obj = new CustomerTypeObj();var_dump($params['PUT']);
		$this->setId($this->getId());
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
		$obj = new CustomerTypeObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

	public function delete(){
		$obj = new CustomerTypeObj();
		$obj->delete($this->getId());
	}

}
