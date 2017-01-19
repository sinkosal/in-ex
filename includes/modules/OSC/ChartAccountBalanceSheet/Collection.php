<?php

namespace OSC\ChartAccountBalanceSheet;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_chart', 'ca');
		$this->idField = 'ca.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByAccountTypeId( $arg ){
		$this->addWhere("ca.account_type_id = '" . (int)$arg. "' ");
	}

}
