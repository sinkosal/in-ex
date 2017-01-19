<?php

namespace OSC\AccountTransactionDetail;

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
				'create_date',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				trans_no,
				reference_id,
				account_id,
				account_name,
				supplier_id,
				supplier_name,
				payee_invoice,
				debit,
				credit,
				pay_amount,
				note,
				create_date,
				trans_date
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

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				account_transaction_detail
			SET
				trans_no = '" . $this->getTransNo() . "',
				reference_id = '" . $this->getReferenceId() . "',
				account_id = '" . $this->getAccountId() . "',
				account_name = '" . $this->getAccountName() . "',
				supplier_id = '" . $this->getSupplierId() . "',
				supplier_name = '" . $this->getSupplierName() . "',
				payee_invoice = '" . $this->getPayeeInvoice() . "',
				debit = '" . $this->getDebit() . "',
				credit = '" . $this->getCredit() . "',
				pay_amount = '" . $this->getPayAmount() . "',
				note = '" . $this->getNote() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function delete($id){
		if( !$id ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				account_transaction_detail
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				account_transaction_detail
			(
				trans_no,
				reference_id,
				account_type_id,
				account_chart_id,
				type_of_account_report,
				account_id,
				account_name,
				supplier_id,
				supplier_name,
				payee_invoice,
				debit,
				credit,
				pay_amount,
				note,
				create_by,
				create_date,
				trans_date
			)
				VALUES
			(
				'" . $this->getTransNo() . "',
				'" . $this->getReferenceId() . "',
				'" . $this->getAccountTypeId() . "',
				'" . $this->getAccountChartId() . "',
				'" . $this->getTypeOfAccountReport() . "',
				'" . $this->getAccountId() . "',
				'" . $this->getAccountName() . "',
				'" . $this->getSupplierId() . "',
				'" . $this->getSupplierName() . "',
				'" . $this->getPayeeInvoice() . "',
				'" . $this->getDebit() . "',
				'" . $this->getCredit() . "',
				'" . $this->getPayAmount() . "',
				'" . $this->getNote() . "',
				'" . $this->getCreateBy() . "',
 				NOW(),
 				'" . $this->getTransDate() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}


}
