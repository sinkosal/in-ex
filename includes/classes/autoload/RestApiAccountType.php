<?php

use
	OSC\AccountType\Collection
		as AccountTypeCol
	, OSC\AccountType\Object
		as AccountTypeObj
;

class RestApiAccountType extends RestApi {

	public function get($params){
		$col = new AccountTypeCol();
		$col->orderBy('DESC');
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['name'] ? $col->filterByName($params['GET']['name']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
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
		$obj = new AccountTypeObj();
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
		$obj = new AccountTypeObj();
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
		$obj = new AccountTypeObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

	public function delete(){
		$obj = new AccuntTypeObj();
		$obj->delete($this->getId());
	}

}
