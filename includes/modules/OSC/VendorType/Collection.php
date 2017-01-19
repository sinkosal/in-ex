<?php

namespace OSC\VendorType;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('supplier_type', 'st');
		$this->idField = 'st.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("st.name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		$this->addWhere("st.id = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("st.status = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('st.id', $arg);
	}

}
