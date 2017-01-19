<?php

namespace OSC\ChartAccount;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('account_chart', 'ca');
		$this->idField = 'ca.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function sortById($arg){
		$this->addOrderBy('ca.id', $arg);
	}

	public function filterById( $arg ){
		$this->addWhere("ca.id = '" . (int)$arg. "' ");
	}

	public function filterByAccountTypeId( $arg ){
		$this->addWhere("ca.account_type_id = '" . (int)$arg. "' ");
	}

	public function filterByMix( $arg ){
		$this->addWhere("ca.name LIKE '%" . $arg. "%'  OR ca.account_code LIKE '%" . $arg. "%' ");
	}

	public function filterByAccountCode( $arg ){
		$this->addWhere("ca.account_code = '" . $arg. "' ");
	}

	public function filterByName( $arg ){
		$this->addWhere("ca.name LIKE '%" . $arg. "%' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("ca.status = '" . (int)$arg. "' ");
	}

}
