<?php

namespace OSC\AccountTypeIncomeStatement;

use
	Aedea\Core\Database\StdCollection
;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_type', 'at');
		$this->idField = 'at.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByIncomeStatementId(){
		$this->addWhere("at.income_statement = 1 ");
	}

	public function filterByDate( $date ){
		$this->addTable('account_chart', 'ac');
		$this->addTable('account_transaction_detail', 'atd');

		$this->addWhere("at.id = ac.account_type_id");
		$this->addWhere("ac.id = atd.account_chart_id");

		$newDate = $date .' ' . '23:59:59';
		$this->addWhere("atd.create_date <= '" . $newDate . "' AND atd.status = 1 ");
	}
}
