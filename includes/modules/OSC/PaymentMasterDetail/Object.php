<?php

namespace OSC\PaymentMasterDetail;

use
	Aedea\Core\Database\StdObject as DbObj
	;

class Object extends DbObj {

	protected
		$paymentId
		, $paymentReferenceNo
		, $invoiceAmount
		, $balance
		, $payAmount
	;

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'payment_id',
				'payment_reference_no',
				'invoice_amount',
				'balance',
				'pay_amount'
			)
		);

		return parent::toArray($args);
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				payment_id,
				payment_reference_no,
				invoice_amount,
				balance,
				pay_amount
			FROM
				payment_master_detail
			WHERE
				id = '" . (int)$this->getId() . "'	
		");

		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Payment Master Detail not found",
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
				products_type
			SET
				name = '" . $this->getName() . "',
				description = '" . $this->getDescription() . "'
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
				products_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				payment_master_detail
			(
				payment_id,
				payment_reference_no,
				invoice_amount,
				balance,
				pay_amount,
				create_date
			)
				VALUES
			(
				'" . $this->getPaymentId() . "',
				'" . $this->getPaymentReferenceNo() . "',
				'" . $this->getInvoiceAmount() . "',
				'" . $this->getBalance() . "',
				'" . $this->getPayAmount() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}


	public function setPayAmount( $string ){
		$this->payAmount = $string;
	}
	public function getPayAmount(){
		return $this->payAmount;
	}

	public function setPaymentId( $string ){
		$this->paymentId = $string;
	}
	public function getPaymentId(){
		return $this->paymentId;
	}

	public function setPaymentReferenceNo( $string ){
		$this->paymentReferenceNo = $string;
	}
	public function getPaymentReferenceNo(){
		return $this->paymentReferenceNo;
	}

	public function setInvoiceAmount( $string ){
		$this->invoiceAmount = $string;
	}
	public function getInvoiceAmount(){
		return $this->invoiceAmount;
	}

	public function setBalance( $string ){
		if($string < 0){
			$string = 0;
		}
		$this->balance = $string;
	}
	public function getBalance(){
		return $this->balance;
	}

}
