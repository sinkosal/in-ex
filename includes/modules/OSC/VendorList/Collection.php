<?php

namespace OSC\VendorList;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('supplier_list', 'sl');
		$this->idField = 'sl.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("sl.name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("sl.id = '" . (int)$arg. "' ");
		}
	}

	public function sortById($arg){
		$this->addOrderBy('sl.id', $arg);
	}

	public function filterByStatus( $arg ){var_dump($arg);
		$this->addWhere("sl.status = '" . (int)$arg. "' ");
	}
}
