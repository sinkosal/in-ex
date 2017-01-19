<?php

namespace OSC\InvoiceDetail;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('invoice_detail', 'ind');
		$this->idField = 'ind.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByName( $arg ){
		if($arg){
			$this->addWhere("ind.name LIKE '%" . $arg. "%' ");
		}
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("ind.invoice_detail_id = '" . $arg. "' ");
		}
	}

	public function sortById($arg){
		$this->addOrderBy('ind.id', $arg);
	}
}
