<?php

namespace OSC\Stock;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('stock', 's');
		$this->idField = 's.stockid';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		$this->addWhere("s.stockid = '" . $arg. "%' ");
	}

	public function filterByName( $arg ){
		$this->addWhere("s.productname LIKE '%" . $arg. "%' ");
	}
	
}
