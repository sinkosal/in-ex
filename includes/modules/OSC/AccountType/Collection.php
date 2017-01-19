<?php

namespace OSC\AccountType;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_type', 'at');
		$this->idField = 'at.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		$this->addWhere("at.id = '" . (int)$arg. "' ");
	}

	public function filterByBalanceSheetId( $arg ){
		$this->addWhere("at.balance_sheet_id = '" . (int)$arg. "' ");
	}

	public function filterByName( $arg ){
		$this->addWhere("at.name = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("at.status = '" . (int)$arg. "' ");
	}


	public function orderBy( $arg ){
		$this->addOrderBy("at.id",  $arg);
	}

}
