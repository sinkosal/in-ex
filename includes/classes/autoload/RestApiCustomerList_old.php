<?php

use
	OSC\CustomerList\Collection
		as CustomerListCol
	, OSC\CustomerList\Object
		as CustomerListObj
;

class RestApiCustomerList extends RestApi {

	public function get($params){
		$col = new CustomerListCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['customer_type_id'] ? $col->filterByCustomerTypeId($params['GET']['customer_type_id']) : '';
		$col->filterByName($params['GET']['name']);
		$col->filterById($params['GET']['id']);
		if($params['GET']['search_in_invoice']){
			$showDataPerPage = 10;
		}elseif($params['GET']['search_in_report']){
			$showDataPerPage = 100;
		}
		else{
			$showDataPerPage = 20;
		}

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
		$obj = new CustomerListObj();
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

	public function patch($params){
		$obj = new CustomerListObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

	public function put($params){
		$obj = new CustomerListObj();
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

	public function delete(){
		$obj = new CustomerListObj();
		$obj->delete($this->getId());
	}

}
