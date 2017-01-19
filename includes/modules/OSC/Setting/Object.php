<?php

namespace OSC\Setting;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$companyName
		, $description
	;

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'company_name',
				'description',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				company_name,
				description
			FROM
				setting
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Setting not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

	}

	public function update(){
		if( !$this->getId() ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				setting
			SET
				company_name = '" . $this->getCompanyName() . "',
				description = '" . $this->getDescription() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");

	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				setting
			(
				company_name,
				description,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getCompanyName() . "',
				'" . $this->getDescription() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDescription( $string ){
		$this->description = $string;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setCompanyName( $string ){
		$this->companyName = $string;
	}
	
	public function getCompanyName(){
		return $this->companyName;
	}

}
