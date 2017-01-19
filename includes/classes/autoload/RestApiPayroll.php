<?php

use
	OSC\Payroll\Collection
		as payrollCol
	, OSC\Payroll\Object
		as payrollObj
;

class RestApiPayroll extends RestApi {

	public function get($params){
		$col = new payrollCol();
		$col->sortById('DESC');
		$params['GET']['date'] ? $col->filterByInThisMonth($params['GET']['date']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['staff_id'] ? $col->filterByStaffId($params['GET']['staff_id']) : '';
//		$params['GET']['staff_name'] ? $col->filterByStaffName($params['GET']['staff_name']) : '';
		if($params['GET']['pagination']){
			$showDataPerPage = 10;
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

	public function post($params){
		$col = new payrollCol();
		$col->filterByInThisMonth('');
		$col->filterByStaffId($params['POST']['staff_id']);
		if($col->getTotalCount() == 0){
			$obj = new payrollObj();
			$obj->setCreateBy($_SESSION['user_name']);
			$obj->setProperties($params['POST']);
			$obj->insert();
			return array(
				'data' => array(
					'id' => $obj->getId(),
					'success' => 'success'
				)
			);
		}else{
			return array( data => array( data => 'existing') );
		}
	}
//
//	public function put($params){
//		$obj = new payrollObj();
//		$this->setId($this->getId());
//		$obj->setUpdateBy($_SESSION['user_name']);
//		$obj->setProperties($params['PUT']);
//		$obj->update($this->getId());
//		return array(
//			'data' => array(
//				'id' => $obj->getId(),
//				'success' => 'success'
//			)
//		);
//	}
//
//	public function patch($params){
//		$obj = new payrollObj();
//		$obj->setUpdateBy($_SESSION['user_name']);
//		$obj->setId($this->getId());
//		$obj->setStatus($params['PATCH']['status']);
//		$obj->updateStatus();
//	}


}
