<?php

namespace OSC\VendorType;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name
		, $description
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'name',
				'description',
				'id',
				'status'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				description,
				status
			FROM
				supplier_type
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Supplier Type not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				supplier_type
			SET
				name = '" . $this->getName() . "',
				description = '" . $this->getDescription() . "'
			WHERE
				id = '" . (int)$id . "'
		");

	}

	public function updateStatus(){
		if( !$this->getId() ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				supplier_type
			SET
				status = '" . $this->getStatus() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
	}
	public function delete($id){
		if( !$id ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				supplier_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				supplier_type
			(
				name,
				description,
				create_date
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getDescription() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setName( $string ){
		$this->name = (string)$string;
	}

	public function getName(){
		return $this->name;
	}

	public function setDescription( $string ){
		$this->description = (string)$string;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
}
