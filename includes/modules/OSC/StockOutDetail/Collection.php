<?php

namespace OSC\StockOutDetail;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('stock_out_detail', 'sod');
		$this->idField = 'sod.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterById( $arg ){
		if($arg){
			$this->addWhere("sod.id = '" . (int)$arg. "' ");
		}
	}

	public function filterByStockOutId( $arg ){
		$this->addWhere("sod.stock_out_id = '" . (int)$arg. "' ");
	}

	public function sortByProductName($arg){
		$this->addOrderBy('sod.product_name', $arg);
	}

}
