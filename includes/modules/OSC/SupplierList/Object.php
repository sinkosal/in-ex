<?php

namespace OSC\SupplierList;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$supplierTypeId
		, $supplierTypeName
		, $companyName
		, $tel
		, $barcode
		, $contactName
		, $email
		, $country
		, $address
		, $note
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'supplier_type_id',
				'supplier_type_name',
				'company_name',
				'tel',
				'barcode',
				'contact_name',
				'email',
				'country',
				'address',
				'note'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				supplier_type_id,
				supplier_type_name,
				company_name,
				tel,
				address,
				barcode,
				contact_name,
				email,
				country,
				note
			FROM
				staff
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Staff not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function setEndContract( $string ){
		$this->endContract = (string)$string;
	}

	public function getEndContract(){
		return $this->endContract;
	}

	public function setStartContract( $string ){
		$this->startContract = (string)$string;
	}

	public function getStartContract(){
		return $this->startContract;
	}

	public function setContractDate( $string ){
		$this->contractDate = (string)$string;
	}

	public function getContractDate(){
		return $this->contractDate;
	}

	public function setNote( $string ){
		$this->note = (string)$string;
	}

	public function getNote(){
		return $this->note;
	}

	public function setMinor( $string ){
		$this->minor = (string)$string;
	}

	public function getMinor(){
		return $this->minor;
	}

	public function setSpouse( $string ){
		$this->spouse = (string)$string;
	}

	public function getSpouse(){
		return $this->spouse;
	}

	public function setPosition( $string ){
		$this->position = (string)$string;
	}

	public function getPosition(){
		return $this->position;
	}

	public function setDob( $string ){
		$this->dob = (string)$string;
	}

	public function getDob(){
		return $this->dob;
	}

	public function setAddress( $string ){
		$this->address = (string)$string;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setSex( $string ){
		$this->sex = (string)$string;
	}

	public function getSex(){
		return $this->sex;
	}

	public function setType( $string ){
		$this->type = (string)$string;
	}

	public function getType(){
		return $this->type;
	}

	public function setPhone( $string ){
		$this->phone = (string)$string;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function setName( $string ){
		$this->name = (string)$string;
	}
	
	public function getName(){
		return $this->name;
	}
	
}
