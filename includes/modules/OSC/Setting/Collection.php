<?php

namespace OSC\Setting;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('setting', 's');
		$this->idField = 's.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("s.id = '" . (int)$arg. "' ");
		}
	}

}
