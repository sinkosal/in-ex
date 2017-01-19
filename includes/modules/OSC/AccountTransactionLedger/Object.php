<?php

namespace OSC\AccountTransactionLedger;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$transNo
		, $transDate
		, $typeId
		, $referenceId
		, $accountId
		, $accountName
		, $supplierId
		, $supplierName
		, $payeeInvoice
		, $debit
		, $credit
		, $payAmount
		, $note
		, $reverse
		, $reversetId
		, $accountTypeId
		, $accountChartId
		, $typeOfAccountReport
	;

	public function setTypeOfAccountReport( $string ){
		$this->typeOfAccountReport = $string;
	}
	public function getTypeOfAccountReport(){
		return $this->typeOfAccountReport;
	}

	public function setAccountChartId( $string ){
		$this->accountChartId = $string;
	}
	public function getAccountChartId(){
		return $this->accountChartId;
	}

	public function setAccountTypeId( $string ){
		$this->accountTypeId = $string;
	}
	public function getAccountTypeId(){
		return $this->accountTypeId;
	}

	public function setNote( $string ){
		$this->note =$string;
	}
	public function getNote(){
		return $this->note;
	}

	public function setTransNo( $string ){
		$this->transNo = $string;
	}

	public function getTransNo(){
		return $this->transNo;
	}

	public function setTransDate( $string ){
		$this->transDate = $string;
	}

	public function getTransDate(){
		return $this->transDate;
	}

	public function setReferenceId( $string ){
		$this->referenceId = $string;
	}
	public function getReferenceId (){
		return $this->referenceId;
	}

	public function setAccountId( $string ){
		$this->accountId = (int)$string;
	}
	public function getAccountId(){
		return $this->accountId;
	}

	public function setAccountName( $string ){
		$this->accountName =$string;
	}
	public function getAccountName(){
		return $this->accountName;
	}

	public function setSupplierId( $string ){
		$this->supplierId = (int)$string;
	}
	public function getSupplierId(){
		return $this->supplierId;
	}

	public function setSupplierName( $string ){
		$this->supplierName = $string;
	}
	public function getSupplierName(){
		return $this->supplierName;
	}

	public function setPayAmount( $string ){
		$this->payAmount = doubleval($string);
	}
	public function getPayAmount(){
		return $this->payAmount;
	}

	public function setPayeeInvoice( $string ){
		$this->payeeInvoice = $string;
	}
	public function getPayeeInvoice(){
		return $this->payeeInvoice;
	}

	public function setDebit( $string ){
		$this->debit = doubleval($string);
	}
	public function getDebit(){
		return $this->debit;
	}

	public function setCredit( $string ){
		$this->credit = doubleval($string);
	}
	public function getCredit(){
		return $this->credit;
	}
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'trans_no',
				'trans_date',
				'reference_id',
				'account_id',
				'account_name',
				'supplier_id',
				'supplier_name',
				'payee_invoice',
				'debit',
				'credit',
				'pay_amount',
				'note',
				'type_of_account_report',
				'create_date',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				trans_no,
				trans_date,
				reference_id,
				account_id,
				account_name,
				supplier_id,
				supplier_name,
				payee_invoice,
				type_of_account_report,
				debit,
				credit,
				pay_amount,
				note,
				create_date
			FROM
				account_transaction_detail
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Account Transaction Detail not found",
				404
			);
		}

		$this->setProperties($this->dbFetchArray($q));
	}

}
