<?php

namespace OSC\DoctorType;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name
		, $detail
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'status',
				'detail',
				'create_date'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				detail,
				status,
				create_date
			FROM
				doctor_type
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Doctor Type not found",
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
				doctor_type
			SET
				name = '" . $this->getName() . "',
				detail = '" . $this->getDetail() . "',
				update_by = '" . $this->getUpdateBy() . "'
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
				doctor_type
			SET
				status = '" . $this->getStatus() . "',
				update_by = '" . $this->getUpdateBy() . "'
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
				doctor_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				doctor_type
			(
				name,
				detail,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getDetail() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setName( $string ){
		$this->name = $string;
	}
	
	public function getName(){
		return $this->name;
	}

}
