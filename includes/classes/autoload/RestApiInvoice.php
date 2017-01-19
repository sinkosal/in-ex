<?php

use
	OSC\Invoice\Collection as InvoiceCol
	, OSC\Invoice\Object as InvoiceObj
	, OSC\InvoiceDetail\Object as InvoiceDetailObj
	, OSC\Appointment\Object as appointmentObj
	, OSC\AppointmentDetail\Object as appointmentDetailObj
	, OSC\CaseFlowDoctor\Object as CaseFlowObj
;

class RestApiInvoice extends RestApi {

	public function get($params){
		$col = new InvoiceCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['balance'] ? $col->filterByBalance() : '';
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		$params['GET']['customer_type_id'] ? $col->filterByCustomerTypeId($params['GET']['customer_type_id']) : '';
		$params['GET']['customer_id'] ? $col->filterByCustomerId($params['GET']['customer_id']) : '';
		$params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
		$params['GET']['doctor_id'] ? $col->filterByDoctorId($params['GET']['doctor_id']) : '';
		$params['GET']['invoice_no'] ? $col->filterByInvoiceNo($params['GET']['invoice_no']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';

		if($params['GET']['paginate']){
			$showDataPerPage = 20;
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
		$obj = new InvoiceObj();
		$obj->setCreateBy($_SESSION['user_name']);
		if( $params['POST']['invoice']['deposit'] >= $params['POST']['invoice']['grand_total'] ){
			$params['POST']['invoice']['deposit'] = $params['POST']['invoice']['grand_total'];
		}
		$obj->setProperties($params['POST']['invoice']);
		$obj->setStatus(1);
		$obj->insert();
		$invoiceId = $obj->getId();
		$invoiceNo = $params['POST']['invoice']['invoice_no'];

		// start insert doctor case flow
		$objCaseFlow = new CaseFlowObj();
		$objCaseFlow->setStatus(1);
		$objCaseFlow->setProperties($params['POST']['case_flow']);
		$objCaseFlow->insert();

		// start insert data into detail
		foreach( $params['POST']['invoice_detail'] as $key => $value){
			$objDetail = new InvoiceDetailObj();
			$objDetail->setInvoiceDetailId($invoiceId);
			$objDetail->setInvoiceDetailNo($invoiceNo);
			$objDetail->setProperties($value);
			$objDetail->insert();
			unset($value);
		}
		if(sizeof($params['POST']['appointment']) > 0){
			$appointment = new appointmentObj();
			$appointment->setCreateBy($_SESSION['user_name']);
			$appointment->setInvoiceId($invoiceId);
			$appointment->setInvoiceNo($obj->getInvoiceNo());
			$appointment->setInvoiceDate($obj->getInvoiceDate());
			$appointment->setCustomerId($obj->getCustomerId());
			$appointment->setCustomerName($obj->getCustomerName());
			$appointment->setCustomerTelephone($obj->getCustomerTel());
			$appointment->insert();
			$appId = $appointment->getId();
			$objAppDetail = new appointmentDetailObj();
			foreach( $params['POST']['appointment'] as $key => $value){
				$objAppDetail->setAppointmentId($appId);
				$objAppDetail->setProperties($value);
				$objAppDetail->insert();
				unset($value);
			}
		}
		return array(
			'data' => array(
				'id' => $invoiceId,
				'success' => 'success'
			)
		);
	}

//	public function put($params){
//		$obj = new InvoiceObj();
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
	public function patch($params){
		$obj = new InvoiceObj();
		$obj->setStatus($params['PATCH']['status']);
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setInvoiceNo($params['PATCH']['invoice_no']);
		$obj->updateStatus();
	}

}
