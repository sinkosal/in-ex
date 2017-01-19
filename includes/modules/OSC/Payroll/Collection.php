<?php

namespace OSC\Payroll;

use Aedea\Core\Database\StdCollection;

class Collection extends StdCollection {
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		
		$this->addTable('pay_roll', 'pr');
		$this->idField = 'pr.id';
		$this->setDistinct(true);
		
		$this->objectType = __NAMESPACE__ . '\Object';		
	}

	public function filterByStaffName( $arg ){
		$this->addWhere("pr.staff_name LIKE '%" . $arg. "%' ");
	}

	public function filterByStaffId( $arg ){
		$this->addWhere("pr.staff_id = '" . (int)$arg. "' ");
	}

	public function filterByInThisMonth($params){
		if($params){
			$this->addWhere(" date_format(pr.create_date, '%m%y') = date_format('" . $params . "', '%m%y') ");
		}else{
			$this->addWhere(" date_format(pr.create_date, '%m%y') = date_format(current_date, '%m%y') ");
		}
	}

	public function filterById( $arg ){
		$this->addWhere("pr.id = '" . (int)$arg. "' ");
	}

	public function filterByStatus( $arg ){
		$this->addWhere("pr.status = '" . (int)$arg. "' ");
	}

	public function sortById($arg){
		$this->addOrderBy('pr.id', $arg);
	}
}
