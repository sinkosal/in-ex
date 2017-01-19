<?php

namespace OSC\Products;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('products', 'p');
		$this->idField = 'p.id';
		$this->setDistinct(true);		
		$this->objectType = __NAMESPACE__ . '\Object';	
	}
	
	public function filterByStatus( $arg ){
		$this->addWhere("p.status = '" . $arg . "'");
	}

	public function filterByType( $arg ){
		$this->addWhere("p.products_type_id = '" . $arg. "' ");
	}

	public function filterByName( $arg ){
		if($arg) {
			$this->addWhere("p.products_name LIKE '%" . $arg . "%' OR p.barcode LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("p.id = '" . (int)$arg. "' ");
		}
	}

	public function sortByDate($arg){
		$this->addOrderBy('p.create_date', $arg);
	}
}
