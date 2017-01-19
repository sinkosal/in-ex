<?php

namespace OSC\CustomerListOnly;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$fullName
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'full_name',
			)
		);

		return parent::toArray($args);
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				full_name
			FROM
				tab_customer
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Customer List not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function setFullName( $string ){
		$this->fullName = $string;
	}
	
	public function getFullName(){
		return $this->fullName;
	}

}
