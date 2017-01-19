<?php

namespace OSC\DoctorListOnly;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name
			FROM
				doctor_list
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Doctor List not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function setName( $string ){
		$this->name = $string;
	}
	
	public function getName(){
		return $this->name;
	}
}
