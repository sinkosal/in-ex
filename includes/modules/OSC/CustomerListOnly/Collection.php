<?php

namespace OSC\CustomerListOnly;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('tab_customer', 'c');
		$this->idField = 'c.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}
	
	public function filterByName( $arg ){
		$this->addWhere("c.full_name LIKE '%" . $arg. "%' OR c.tel LIKE '%" . $arg. "%' ");
	}


	public function filterById( $arg ){
		if($arg){
			$this->addWhere("c.id = '" . (int)$arg. "' ");
		}
	}

	public function filterByCustomerTypeId( $arg ){
		$this->addWhere("c.customer_type_id = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("c.status = '" . $arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('c.id', $arg);
	}

}
