<?php

use
	OSC\DoctorExpense\Collection
		as DoctorExpCol
	, OSC\DoctorExpense\Object
		as DoctorExpObj
;

class RestApiDoctorExpense extends RestApi {

	public function get($params){
		$col = new DoctorExpCol();
		$col->sortById('DESC');
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['invoice_no'] ? $col->filterByInvoiceNo($params['GET']['invoice_no']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['doctor_id'] ? $col->filterByDoctorId($params['GET']['doctor_id']) : '';
		$params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
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
		$obj = new DoctorExpObj();
		$obj->setCreateBy();
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
		$obj = new DoctorExpObj();
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
		$obj = new DoctorExpObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

}
