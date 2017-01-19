<?php

namespace OSC\DoctorList;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('doctor_list', 'dl');
		$this->idField = 'dl.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("dl.name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterByStatus( $arg ){
		$this->addWhere("dl.status = '" . (int)$arg. "' ");
	}

	public function filterById( $arg ){
		$this->addWhere("dl.id = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('dl.id', $arg);
	}
}
