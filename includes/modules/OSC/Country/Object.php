<?php

namespace OSC\Country;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$countriesName
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'countries_name',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				countries_name
			FROM
				countries
			WHERE
				countries_id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Countries not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function setCountriesName( $string ){
		$this->countriesName = (string)$string;
	}

	public function getCountriesName(){
		return $this->countriesName;
	}

	
}
