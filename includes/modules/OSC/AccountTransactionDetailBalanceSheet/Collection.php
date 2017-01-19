<?php

namespace OSC\AccountTransactionDetailBalanceSheet;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_transaction_detail', 'atd');
		$this->idField = 'atd.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByAccountChartId( $arg ){
		$this->addWhere("atd.account_chart_id = '" . (int)$arg. "' ");
	}

}
