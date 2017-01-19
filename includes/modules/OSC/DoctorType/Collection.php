<?php

namespace OSC\DoctorType;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('doctor_type', 'dt');
		$this->idField = 'dt.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("dt.name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("dt.id = '" . (int)$arg. "' ");
		}
	}

	public function filterByStatus( $arg ){
		$this->addWhere("dt.status = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('dt.id', $arg);
	}
}
