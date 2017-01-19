<?php

namespace OSC\CaseFlowDoctor;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('case_flow_doctor', 'cfd');
		$this->idField = 'cfd.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("cfd.id = '" . (int)$arg. "' ");
		}
	}

	public function sortById( $arg ){
		$this->addOrderBy("cfd.id", $arg);
	}

	public function sortByStatus( $arg ){
		$this->addWhere("cfd.status = '" . (int)$arg. "' ");
	}

	public function filterByDoctorId( $arg ){
		$this->addWhere("cfd.doctor_id = '" . (int)$arg. "' ");
	}

	public function filterByInvoiceDate($arg){
		$this->addWhere("cfd.invoice_date = '" . $arg . "' ");
	}

	public function filterByCustomerId( $arg ){
		$this->addWhere("cfd.customer_id = '" . (int)$arg. "' ");
	}

	public function filterByCustomerTypeId( $arg ){
		$this->addWhere("cfd.customer_type_id = '" . (int)$arg. "' ");
	}

	public function filterByInvoiceNo( $arg ){
		$this->addWhere("cfd.invoice_no LIKE '%" . $arg. "%' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("cfd.invoice_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}

}
