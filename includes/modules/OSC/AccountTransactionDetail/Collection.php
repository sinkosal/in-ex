<?php

namespace OSC\AccountTransactionDetail;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_transaction_detail', 'atd');
		$this->idField = 'atd.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
			$this->addWhere("atd.id = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("atd.status = '" . (int)$arg. "' ");
	}

	public function filterByTransNo( $arg ){
		$this->addWhere("atd.trans_no = '" . $arg. "' ");
	}

	public function filterByDate($from, $to){
		$new = $to .' ' . '23:59:59';
		$this->addWhere("atd.trans_date BETWEEN '" . $from . "' AND '" . $new . "' ");
	}

	public function filterByVendorId( $arg ){
		$this->addWhere("atd.supplier_id = '" . (int)$arg. "' ");
	}

	public function orderBy( $arg ){
		$this->addOrderBy("atd.trans_date",  $arg);
	}

}
