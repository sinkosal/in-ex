<?php

namespace OSC\ChartAccountIncomeStatement;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name,
		$accountTypeId,
		$total
	;

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
		//set conditional depend on type of account has income statement
		// 3 for revenues and 4 for expense 5 for cost of sales
		$sum = $this->dbQuery("
			SELECT
				SUM(
					CASE
		            	WHEN
		            		type_of_account_report = 4 THEN debit - credit
					  	WHEN
		            		type_of_account_report = 3 THEN credit - debit
						WHEN
		            		type_of_account_report = 5 THEN debit - credit
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
