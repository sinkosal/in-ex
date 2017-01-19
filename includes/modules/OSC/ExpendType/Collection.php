<?php

namespace OSC\CustomerType;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('customer_type', 'ct');
		$this->idField = 'ct.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
			$this->addWhere("ct.id = '" . (int)$arg. "' ");
	}

	public function filterByName( $arg ){
		$this->addWhere("ct.name = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("ct.status = '" . (int)$arg. "' ");
	}

}
