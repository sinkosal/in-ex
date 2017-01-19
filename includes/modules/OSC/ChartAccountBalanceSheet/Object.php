<?php

namespace OSC\ChartAccountBalanceSheet;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\AccountTransactionDetailBalanceSheet\Collection as AccountTransCollection
;

class Object extends DbObj {
		
	protected
		$name,
		$accountTypeId,
		$total
	;

	public function __construct( $params = array() ){
//		parent::__construct($params);
//		$this->detail = new AccountTransCollection();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'name',
				'total'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				account_type_id
			FROM
				account_chart
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Chart Account not found",
				404
			);
		}

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
				account_chart_id = '" . (int)$this->getId() . "'
					AND
				status = 1
		");
		$total = $this->dbFetchArray($sum);
		$this->setTotal($total['total']);

		$this->setProperties($this->dbFetchArray($q));
//		$this->detail->setFilter('account_chart_id', $this->getId());
//		$this->detail->populate();
	}

	public function setTotal( $string ){
		$this->total = doubleval($string);
	}

	public function getTotal(){
		return $this->total;
	}

	public function setAccountTypeId( $string ){
		$this->accountTypeId = (int)$string;
	}

	public function getAccountTypeId(){
		return $this->accountTypeId;
	}

	public function setName( $string ){
		$this->name = $string;
	}
	
	public function getName(){
		return $this->name;
	}

}
