<?php

namespace OSC\AccountTransaction;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\AccountTransactionDetail\Collection as TransactionDetailCollection
;

class Object extends DbObj {
		
	protected
		$transNo
		, $transDate
		, $typeId
		, $amount
		, $remarks
		, $detail
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new TransactionDetailCollection();
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setTransNo( $string ){
		$this->transNo = $string;
	}

	public function getTransNo(){
		return $this->transNo;
	}

	public function setTransDate( $string ){
		$this->transDate =  date('Y-m-d', strtotime( $string ));
	}

	public function getTransDate(){
		return $this->transDate;
	}

	public function setTypeId( $string ){
		$this->typeId = $string;
	}
	public function getTypeId(){
		return $this->typeId;
	}

	public function setAmount ( $string ){
		$this->amount = doubleval($string);
	}
	public function getAmount(){
		return $this->amount;
	}

	public function setRemarks( $string ){
		$this->remarks =$string;
	}
	public function getRemarks(){
		return $this->remarks;
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'trans_no',
				'trans_date',
				'type_id',
				'amount',
				'remarks',
				'status',
				'detail'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				trans_no,
				trans_date,
				type_id,
				amount,
				remarks,
				status
			FROM
				account_transaction
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Account Transaction not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->detail->setFilter('trans_no', $this->getTransNo());
		$this->detail->populate();

	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				account_transaction
			SET
				trans_no = '" . $this->getTransNo() . "',
				trans_date = '" . $this->getTransDate() . "',
				type_id = '" . $this->getTypeId() . "',
				amount = '" . $this->getAmount() . "',
				remarks = '" . $this->getRemarks() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function updateStatus($string){
		if( !$this->getId() ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				account_transaction
			SET
				status = '" . $this->getStatus() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");

		$this->dbQuery("
			UPDATE
				account_transaction_detail
			SET
				status = '" . $this->getStatus() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				trans_no = '" . $string . "'
		");

	}

	public function delete($id){
		if( !$id ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				account_transaction
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				account_transaction
			(
				trans_no,
				trans_date,
				type_id,
				amount,
				remarks,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getTransNo() . "',
				'" . $this->getTransDate() . "',
				'" . $this->getTypeId() . "',
				'" . $this->getAmount() . "',
				'" . $this->getRemarks() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

}
