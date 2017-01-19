<?php

namespace OSC\AccountTransaction;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_transaction', 'at');
		$this->idField = 'at.id';
		$this->setDistinct(true);
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByTransDate($arg){
		$this->addWhere("at.trans_date = '" . $arg . "' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("at.trans_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

	public function filterById( $arg ){
			$this->addWhere("at.id = '" . (int)$arg. "' ");
	}

	public function filterByVendorId( $arg ){
		$this->addWhere("at.supplire_id = '" . (int)$arg. "' ");
	}

	public function filterByTransactionNo( $arg ){
		$this->addWhere("at.trans_no = '" . $arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("at.status = '" . (int)$arg. "' ");
	}

	public function sortById( $arg ){
		$this->addOrderBy("at.id",  $arg);
	}

}
