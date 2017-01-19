<?php

namespace OSC\DoctorList;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\DoctorType\Collection
		as  DoctorTypeCol
;

class Object extends DbObj {
		
	protected
		$name
		, $detail
		, $sex
		, $address
		, $phone
		, $doctorTypeId
		, $doctorType
		, $action
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'sex',
				'detail',
				'doctor_type',
				'address',
				'status',
				'phone'
			)
		);

		return parent::toArray($args);
	}

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->doctorType = new DoctorTypeCol();
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				detail,
				sex,
				address,
				phone,
				doctor_type_id,
				status
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

		$this->doctorType->setFilter('id', $this->getDoctorTypeId());
		$this->doctorType->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				doctor_list
			SET
				name = '" . $this->getName() . "',
				detail = '" . $this->getDetail() . "',
				sex = '" . $this->getSex() . "',
				phone = '" . $this->getPhone() . "',
				address = '" . $this->getAddress() . "',
				doctor_type_id = '" . $this->getDoctorTypeId() . "',
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
				doctor_list
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
				doctor_list
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				doctor_list
			(
				name,
				detail,
				sex,
				phone,
				address,
				doctor_type_id,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getDetail() . "',
				'" . $this->getSex() . "',
				'" . $this->getPhone() . "',
				'" . $this->getAddress() . "',
				'" . $this->getDoctorTypeId() . "',
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

	public function setAddress( $string ){
		$this->address = $string;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setPhone( $string ){
		$this->phone = $string;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function setSex( $string ){
		$this->sex = $string;
	}

	public function getSex(){
		return $this->sex;
	}

	public function setDoctorTypeId( $string ){
		$this->doctorTypeId = $string;
	}

	public function getDoctorTypeId(){
		return $this->doctorTypeId;
	}

	public function setDoctorType( $string ){
		$this->doctorType = $string;
	}

	public function getDoctorType(){
		return $this->doctorType;
	}
}
