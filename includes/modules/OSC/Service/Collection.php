<?php

namespace OSC\Service;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('services', 's');
		$this->idField = 's.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("s.service_name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("s.id = '" . (int)$arg. "' ");
		}
	}

	public function filterByCustomerTypeId( $arg ){
		$this->addWhere("s.customer_type_id = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('s.id', $arg);
	}
}
