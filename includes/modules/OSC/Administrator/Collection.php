<?php

namespace OSC\Administrator;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('administrators', 'ad');
		$this->idField = 'ad.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByUserName($arg){
		$this->addWhere("ad.user_name = '" . $arg . "' ");
	}

	public function filterById($arg){
		$this->addWhere("ad.id = '" . (int)$arg . "' ");
	}
}
