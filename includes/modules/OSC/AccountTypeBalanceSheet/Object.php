<?php

namespace OSC\AccountTypeBalanceSheet;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\ChartAccountBalanceSheet\Collection as ChartAccountCol
;

class Object extends DbObj {
		
	protected
		$name
		,  $detail
		,  $total
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'name',
				'detail',
				'total'
			)
		);

		return parent::toArray($args);
	}

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new ChartAccountCol();
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name
			FROM
				account_type
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Account Type not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		if($this->getTypeOfAccount){

		}
		//set conditional depend on type of account has balance sheet id
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
	           	) 
				as total
			FROM
				account_transaction_detail
			WHERE
				account_type_id = '" . (int)$this->getId() . "'
					AND
				status = 1
		");
		$total = $this->dbFetchArray($sum);
		$this->setTotal($total['total']);

		$this->detail->setFilter('account_type_id', $this->getId());
		$this->detail->populate();
	}

	public function setTotal( $string ){
		$this->total = doubleval($string);
	}

	public function getTotal(){
		return $this->total;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setName( $string ){
		$this->name = $string;
	}

	public function getName(){
		return $this->name;
	}

}
