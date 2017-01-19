<?php

namespace OSC\ProductsType;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('products_type', 'pt');
		$this->idField = 'pt.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function sortById($arg){
		$this->addOrderBy('pt.id', $arg);
	}

	public function filterById( $arg ){
		$this->addWhere("pt.id = '" . (int)$arg . "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("pt.status = '" . (int)$arg . "' ");
	}

	public function filterByName( $arg ){
		if($arg) {
			$this->addWhere("pt.name LIKE '%" . $arg . "%' ");
		}
	}
}
