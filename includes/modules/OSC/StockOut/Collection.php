<?php

namespace OSC\StockOut;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('stock_out', 'so');
		$this->idField = 'so.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}


	public function filterByDate($from, $to){
		$this->addWhere("so.stock_out_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

	public function filterById( $arg ){
		$this->addWhere("so.id = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('so.id', $arg);
	}
	
}
