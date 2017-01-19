<?php

namespace OSC\VendorList;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\VendorType\Collection
		as  VendorCol
;

class Object extends DbObj {
		
	protected
		$supplierTypeId
		, $name
		, $companyName
		, $tel
		, $barcode
		, $contactName
		, $email
		, $country
		, $address
		, $note
		, $vendorType
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->vendorType = new VendorCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'name',
				'id',
				'company_name',
				'tel',
				'contact_name',
				'email',
				'country',
				'address',
				'note',
				'supplier_type_id',
				'vendor_type',
				'status'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				supplier_type_id,
				name,
				company_name,
				tel,
				address,
				contact_name,
				email,
				country,
				note,
				status
			FROM
				supplier_list
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Vendor List not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->vendorType->setFilter('id', $this->getSupplierTypeId());
		$this->vendorType->populate();

	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				supplier_list
			SET
				supplier_type_id = '" . $this->getSupplierTypeId() . "',
				name = '" . $this->getName() . "',
				company_name = '" . $this->getCompanyName() . "',
				tel = '" . $this->getTel() . "',
				address = '" . $this->getAddress() . "',
				contact_name = '" . $this->getContactName() . "',
				email = '" . $this->getEmail() . "',
				country = '" . $this->getCountry() . "',
				note = '" . $this->getNote() . "',
				update_by = '" . $this->getCreateBy() . "'
			WHERE
				id = '" . (int)$id . "'
		");

	}

	public function delete($id){
		if( !$id ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				supplier_list
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
				supplier_list
			SET
				status = '" . $this->getStatus() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				supplier_list
			(
				supplier_type_id,
				name,
				company_name,
				tel,
				address,
				contact_name,
				email,
				country,
				note,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getSupplierTypeId() . "',
				'" . $this->getName() . "',
				'" . $this->getCompanyName() . "',
				'" . $this->getTel() . "',
				'" . $this->getAddress() . "',
				'" . $this->getContactName() . "',
				'" . $this->getEmail() . "',
				'" . $this->getCountry() . "',
				'" . $this->getNote() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setEmail( $string ){
		$this->email = (string)$string;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setContactName( $string ){
		$this->contactName = (string)$string;
	}

	public function getContactName(){
		return $this->contactName;
	}

	public function setCountry( $string ){
		$this->country = (string)$string;
	}

	public function getCountry(){
		return $this->country;
	}

	public function setNote( $string ){
		$this->note = (string)$string;
	}

	public function getNote(){
		return $this->note;
	}

	public function setTel( $string ){
		$this->tel = (string)$string;
	}

	public function getTel(){
		return $this->tel;
	}

	public function setCompanyName( $string ){
		$this->companyName = (string)$string;
	}

	public function getCompanyName(){
		return $this->companyName;
	}

	public function setAddress( $string ){
		$this->address = (string)$string;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setSupplierTypeId( $string ){
		$this->supplierTypeId = (int)$string;
	}

	public function getSupplierTypeId(){
		return $this->supplierTypeId;
	}

	public function setVendorType( $string ){
		$this->vendorType = (string)$string;
	}

	public function getVendorType(){
		return $this->vendorType;
	}

	public function setName( $string ){
		$this->name = (string)$string;
	}
	
	public function getName(){
		return $this->name;
	}
	
}
