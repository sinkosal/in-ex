<?php

use
	OSC\ReceivePayment\Collection as ReceivePaymentCol
	, OSC\ReceivePayment\Object as ReceivePaymentObj
	, OSC\ReceivePaymentDetail\Object as ReceivePaymentDetailObj
	, OSC\Invoice\Object as InvoiceObj
	, OSC\CaseFlowDoctor\Object as CaseFlowObj
;

class RestApiReceivePayment extends RestApi {

	public function get($params){
		$col = new ReceivePaymentCol();
		// start limit page
		$col->sortById('DESC');
		$params['GET']['balance'] ? $col->filterByBalance() : '';
		$params['GET']['customer_id'] ? $col->filterByCustomerId($params['GET']['customer_id']) : '';
		$params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';

//		$showDataPerPage = 10;
//		$start = $params['GET']['start'];
//		$this->applyLimit($col,
//			array(
//				'limit' => array( $start, $showDataPerPage )
//			)
//		);
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

	public function post($params){
		$obj = new ReceivePaymentObj();
		$obj->setCreateBy($_SESSION['user_name']);
		$obj->setProperties($params['POST']['receive_payment'][0]);
		$obj->insert();
		$totalDiscountAmount = $params['POST']['receive_payment'][0]['total_discount_amount'];
		$receivePaymentId = $obj->getId();

		// start insert doctor case flow
		$objCaseFlow = new CaseFlowObj();
		$objCaseFlow->setStatus(1);
		$objCaseFlow->setProperties($params['POST']['receive_case_flow_doctor'][0]);
		$objCaseFlow->insert();

		// start insert data into detail
		foreach( $params['POST']['receive_payment_detail'] as $key => $value){
			$objDetail = new ReceivePaymentDetailObj();
			$objDetail->setReceivePaymentId($receivePaymentId);
			$objDetail->setInvoiceNo($value['invoice_no']);
			$objDetail->setTotalAmount($value['sub_total']);
			$objDetail->setDepositBefore($value['deposit']);
			$objDetail->setBalanceBefore($value['balance']);
			$lastBalance = doubleval($value['balance'] - $value['payment_next'] - $totalDiscountAmount);
			if($lastBalance <= 0){
				$lastBalance = 0;
			}
			$payment = doubleval( $value['payment_next'] + $totalDiscountAmount );
			if( $payment >= doubleval($value['balance'] ) ){
				$value['payment_next'] = $value['balance'];
			}
			$objDetail->setLastDeposit( $value['payment_next'] );
			$objDetail->setLastBalance($lastBalance);
			if(doubleval( $value['payment_next'] ) > 0) {
				$objDetail->insert();
			}

			// update balance in invoice
			$objInvoice = new InvoiceObj();
			$objInvoice->setId($value['id']);
			$objInvoice->setDeposit($payment);
			$objInvoice->setBalance($lastBalance);
			$objInvoice->setUpdateBy($_SESSION['user_name']);
			$objInvoice->update();
			unset($value);
		}
		return array(
			'data' => array(
				'id' => $receivePaymentId,
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
//	public function patch($params){
//		$obj = new InvoiceObj();
//		$obj->setStatus($params['PATCH']['status']);
//		$obj->updateStatus($this->getId());
//	}

}
