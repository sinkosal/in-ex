<?php

namespace OSC\Invoice;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('invoice', 'i');
		$this->idField = 'i.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("i.id = '" . (int)$arg. "' ");
		}
	}

	public function sortById( $arg ){
		$this->addOrderBy("i.id", $arg);
	}

	public function filterByStatus( $arg ){
		$this->addWhere("i.status = '" . (int)$arg. "' ");
	}

	public function filterByDoctorId( $arg ){
		$this->addWhere("i.doctor_id = '" . (int)$arg. "' ");
	}

	public function filterByInvoiceDate($arg){
		$this->addWhere("i.invoice_date = '" . $arg . "' ");
	}

	public function filterByCustomerId( $arg ){
		$this->addWhere("i.customer_id = '" . (int)$arg. "' ");
	}

	public function filterByCustomerTypeId( $arg ){
		$this->addWhere("i.customer_type_id = '" . (int)$arg. "' ");
	}

	public function filterByInvoiceNo( $arg ){
		$this->addWhere("i.invoice_no LIKE '%" . $arg. "%' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("i.invoice_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

	public function filterByBalance(){
		$this->addWhere("i.balance > 0 ");
	}
}
