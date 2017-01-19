<?php

namespace OSC\AccountTransactionDetailBalanceSheet;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$debit
		, $credit
	;

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
				'debit',
				'credit',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				debit,
				credit
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
