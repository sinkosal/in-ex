<?php

namespace OSC\PaymentMaster;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('payment_master', 'pm');
		$this->idField = 'pm.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function sortById($arg){
		$this->addOrderBy('pm.id', $arg);
	}

	public function filterById( $arg ){
		$this->addWhere("pm.id = '" . (int)$arg . "' ");
	}

	public function filterByName( $arg ){
		$this->addWhere("pm.name LIKE '%" . $arg . "%' ");
	}

	public function filterByVendorId( $arg ){
		$this->addWhere("pm.vendor_id = '" . (int)$arg. "' ");
	}

	public function filterByDate($from, $to){
		$this->addWhere("pm.payment_date BETWEEN '" . $from . "' AND '" . $to . "' ");
	}
}
