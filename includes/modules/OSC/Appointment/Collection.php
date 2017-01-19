<?php

namespace OSC\Appointment;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->addTable('appointment_detail', 'ap');
		$this->addTable('appointment', 'a');
		$this->idField = 'a.id';
		$this->setDistinct(true);
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByCustomerId( $arg ){
		$this->addWhere("a.customer_id = '" . $arg. "' ");
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("a.id = '" . (int)$arg. "' ");
		}
	}

	public function filterByStatus( $arg ){
		$this->addWhere("a.status = '" . (int)$arg. "' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("ap.appointment_id = a.id");
		$this->addWhere("ap.date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

	public function filterByInvoiceNo( $arg ){
		$this->addWhere("a.invoice_no LIKE '%" . $arg. "%' ");
	}

	public function sortById($arg){
//		$this->addWhere("ap.appointment_id = a.id");
		$this->addOrderBy('a.id', $arg);
	}
}
