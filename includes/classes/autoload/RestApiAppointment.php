<?php

use
	OSC\Appointment\Collection
		as appointmentCol
	, OSC\Appointment\Object
		as appointmentObj
	, OSC\AppointmentDetail\Object
		as appointmentDetailObj
;

class RestApiAppointment extends RestApi {

	public function get($params){
		$col = new appointmentCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['customer_id'] ? $col->filterByCustomerId($params['GET']['customer_id']) : '';
		$params['GET']['invoice_no'] ? $col->filterByInvoiceNo($params['GET']['invoice_no']) : '';
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
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}
	public function post($params){
		$obj = new appointmentObj();
		$obj->setCreateBy($_SESSION['user_name']);
		$obj->setProperties($params['POST']['appointment']);
		$obj->insert();
		$appointmentId = $obj->getId();
		// start insert data into detail
		foreach( $params['POST']['appointment_detail'] as $key => $value){
			$objDetail = new appointmentDetailObj();
			$objDetail->setAppointmentId($appointmentId);
			$objDetail->setProperties($value);
			$objDetail->insert();
			unset($value);
		}
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function put($params){
		$obj = new appointmentObj();
		$this->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setProperties($params['PUT']['appointment']);
		$obj->update($this->getId());
		$objDetail = new appointmentDetailObj();
		// remove all appointment id than insert new again to avoid mess data
		$objDetail->delete($this->getId());
		foreach( $params['PUT']['appointment_detail'] as $key => $value){
			$objDetail->setAppointmentId($this->getId());
			$objDetail->setProperties($value);
			$objDetail->insert();
			unset($value);
		}
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function patch($params){
		$obj = new appointmentObj();
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setId($this->getId());
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}


}
