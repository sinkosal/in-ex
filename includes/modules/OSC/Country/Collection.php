<?php

namespace OSC\Country;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('countries', 'co');
		$this->idField = 'co.countries_id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		$this->addWhere("co.countries_id = '" . (int)$arg. "' ");
	}


}
