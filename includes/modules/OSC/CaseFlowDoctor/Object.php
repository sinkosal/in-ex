<?php

namespace OSC\CaseFlowDoctor;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$invoiceNo
		, $invoiceDate
		, $customerId
		, $customerName
		, $doctorId
		, $doctorName
		, $bank
		, $cashIn
		, $bankCharge
		, $treatment
	;

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'invoice_no',
				'invoice_date',
				'customer_id',
				'customer_name',
				'doctor_id',
				'doctor_name',
				'bank',
				'cash_in',
				'treatment',
				'bank_charge',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				invoice_no,
				invoice_date,
				doctor_id,
				doctor_name,
				customer_id,
				customer_name,
				bank,
				cash_in,
				treatment,
				bank_charge
			FROM
				case_flow_doctor
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Case Flow Doctor not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				case_flow_doctor
			(
				invoice_no,
				invoice_date,
				customer_id,
				customer_name,
				doctor_id,
				doctor_name,
				bank,
				cash_in,
				treatment,
				bank_charge,
				status,
				create_by
			)
				VALUES
			(
				'" . $this->getInvoiceNo() . "',
				'" . $this->getInvoiceDate() . "',
				'" . $this->getCustomerId() . "',
				'" . $this->getCustomerName() . "',
				'" . $this->getDoctorId() . "',
				'" . $this->getDoctorName() . "',
				'" . $this->getBank() . "',
				'" . $this->getCashIn() . "',
				'" . $this->getTreatment() . "',
				'" . $this->getBankCharge() . "',
				'" . $this->getStatus() . "',
				'" . $this->getCreateBy() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setBankCharge( $string ){
		$this->bankCharge = doubleval($string);
	}
	public function getBankCharge(){
		return $this->bankCharge;
	}

	public function setDoctorName( $string ){
		$this->doctorName = $string;
	}
	public function getDoctorName(){
		return $this->doctorName;
	}

	public function setCustomerId( $string ){
		$this->customerId = (int)$string;
	}
	public function getCustomerId(){
		return $this->customerId;
	}

	public function setCustomerName( $string ){
		$this->customerName = $string;
	}
	public function getCustomerName(){
		return $this->customerName;
	}

	public function setInvoiceDate( $string ){
		$this->invoiceDate = date('Y-m-d', strtotime( $string ));
	}
	public function getInvoiceDate(){
		return $this->invoiceDate;
	}

	public function setInvoiceNo( $string ){
		$this->invoiceNo = $string;
	}
	public function getInvoiceNo(){
		return $this->invoiceNo;
	}

	public function setTreatment( $string ){
		$this->treatment = $string;
	}
	public function getTreatment(){
		return $this->treatment;
	}


	public function setCashIn( $string ){
		$this->cashIn = doubleval($string);
	}
	public function getCashIn(){
		return $this->cashIn;
	}

	public function setDoctorId( $string ){
		$this->doctorId = (int)$string;
	}
	public function getDoctorId(){
		return $this->doctorId;
	}

	public function setBank( $string ){
		$this->bank = doubleval($string);
	}
	public function getBank(){
		return $this->bank;
	}

}
