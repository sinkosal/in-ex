<?php

namespace OSC\AccountTypeBalanceSheet;

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

	public function filterByBalanceSheetId( $arg ){
		$this->addWhere("at.balance_sheet_id = '" . (int)$arg. "' ");
	}

}
