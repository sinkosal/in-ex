<?php

namespace OSC\AppointmentDetail;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->addTable('appointment_detail', 'ad');
		$this->idField = 'ad.id';
		$this->setDistinct(true);
		$this->objectType = __NAMESPACE__ . '\Object';
	}

	public function filterByAppointmentId( $arg ){
		if($arg){
			$this->addWhere("ad.appointment_id = '" . (int)$arg. "' ");
		}
	}

	public function sortById($arg){
		$this->addOrderBy('ad.id', $arg);
	}

}
