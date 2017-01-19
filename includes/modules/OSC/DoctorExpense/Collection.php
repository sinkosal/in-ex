<?php

namespace OSC\DoctorExpense;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('doctor_expense', 'de');
		$this->idField = 'de.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByInvoiceNo( $arg ){
		$this->addWhere("de.invoice_no LIKE '%" . $arg. "%'");
	}

	public function filterByDoctorId( $arg ){
		$this->addWhere("de.doctor_id = '" . (int)$arg. "' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("de.expense_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

	public function filterById( $arg ){
		$this->addWhere("de.id = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("de.status = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('de.id', $arg);
	}
}
