<?php

namespace OSC\BalanceSheet;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\AccountTypeBalanceSheet\Collection as AccountTypeCol
;

class Object extends DbObj {
		
	protected
		$name,
		$detail,
		$total
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new AccountTypeCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'detail',
				'total'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name
			FROM
				balance_sheet
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Balance Sheet not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$sum = $this->dbQuery("
			SELECT
				SUM(
					CASE
		            	WHEN
		            		type_of_account_report = 1 THEN debit - credit
		            	  WHEN
		            		type_of_account_report = 2 THEN credit - debit
		            	ELSE
		            		0
		          	END
	           	) as total
			FROM
				account_transaction_detail
			WHERE
				type_of_account_report = '" . (int)$this->getId() . "'
					AND
				status = 1
		");
		$total = $this->dbFetchArray($sum);
		$this->setTotal($total['total']);

		$this->detail->setFilter('balance_sheet_id', $this->getId());
		$this->detail->populate();
	}

	public function setTotal( $string ){
		$this->total = doubleval($string);
	}

	public function getTotal(){
		return $this->total;
	}

	public function setName( $string ){
		$this->name = $string;
	}

	public function getName(){
		return $this->name;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}
}
