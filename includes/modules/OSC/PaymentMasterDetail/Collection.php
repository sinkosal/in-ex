<?php

namespace OSC\PaymentMasterDetail;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('payment_master_detail', 'pmt');
		$this->idField = 'pmt.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function sortById($arg){
		$this->addOrderBy('pmt.id', $arg);
	}

	public function filterById( $arg ){
		$this->addWhere("pmt.id = '" . (int)$arg . "' ");
	}

	public function filterByPaymentId( $arg ){
		$this->addWhere("pmt.payment_id = '" . (int)$arg . "' ");
	}
}
