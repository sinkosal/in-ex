<?php

namespace OSC\CustomerList;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\DoctorList\Collection
		as  DoctorListCol
	, OSC\CustomerType\Collection
		as  CustomerTypeCol
;

class Object extends DbObj {
		
	protected
		$customerTypeId
		, $customerid
		, $fullName
		, $detail
		, $sex
		, $dob
		, $tel
		, $email
		, $address
		, $doctorId
		, $relativeContact
		, $relativeTel
		, $doctorFields
		, $customerType
		, $barcode
		, $country
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'full_name',
				'detail',
				'customer_type_id',
				'customerid',
				'sex',
				'dob',
				'status',
				'tel',
				'email',
				'address',
				'relative_contact',
				'doctor_fields',
				'customer_type',
				'relative_tel',
				'country',
				'barcode',
			)
		);

		return parent::toArray($args);
	}

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->doctorFields = new DoctorListCol();
		$this->customerType = new CustomerTypeCol();
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				full_name,
				detail,
				customer_type_id,
				customerid,
				sex,
				doctor_id,
				dob,
				tel,
				email,
				address,
				relative_contact,
				relative_tel,
				barcode,
				country,
				status
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

		$this->customerType->setFilter('id', $this->getCustomerTypeId());
		$this->customerType->populate();

		$this->doctorFields->setFilter('id', $this->getDoctorId());
		$this->doctorFields->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				tab_customer
			SET
				full_name = '" . $this->getFullName() . "',
				customer_type_id = '" . $this->getCustomerTypeId() . "',
				dob = '" . $this->getDob() . "',
				doctor_id = '" . $this->getDoctorId() . "',
				tel = '" . $this->getTel() . "',
				address = '" . $this->getAddress() . "',
				detail = '" . $this->getDetail() . "',
				email = '" . $this->getEmail() . "',
				relative_contact = '" . $this->getRelativeContact() . "',
				relative_tel = '" . $this->getRelativeTel() . "',
				sex = '" . $this->getSex() . "',
				detail = '" . $this->getDetail() . "',
				barcode = '" . $this->getBarcode() . "',
				country = '" . $this->getCountry() . "',
				update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function updateStatus(){
		if( !$this->getId() ){
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				tab_customer
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
				tab_customer
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				tab_customer
			(
				full_name,
				detail,
				address,
				tel,
				dob,
				doctor_id,
				relative_contact,
				relative_tel,
				email,
				customer_type_id,
				sex,
				country,
				barcode,
				create_date,
				create_by
			)
				VALUES
			(
				'" . $this->getFullName() . "',
				'" . $this->getDetail() . "',
				'" . $this->getAddress() . "',
				'" . $this->getTel() . "',
				'" . $this->getDob() . "',
				'" . $this->getDoctorId() . "',
				'" . $this->getRelativeContact() . "',
				'" . $this->getRelativeTel() . "',
				'" . $this->getEmail() . "',
				'" . $this->getCustomerTypeId() . "',
				'" . $this->getSex() . "',
				'" . $this->getCountry() . "',
				'" . $this->getBarcode() . "',
 				NOW(),
 				'" . $this->getCreateBy() . "'
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

	public function setDoctorId( $string ){
		$this->doctorId = $string;
	}

	public function getDoctorId(){
		return $this->doctorId;
	}

	public function setSex( $string ){
		$this->sex = $string;
	}

	public function getSex(){
		return $this->sex;
	}

	public function setBarcode( $string ){
		$this->barcode = $string;
	}
	public function getBarcode(){
		return $this->barcode;
	}

	public function setCountry( $string ){
		$this->country = $string;
	}
	public function getCountry(){
		return $this->country;
	}

	public function setCustomerTypeId( $string ){
		$this->customerTypeId = $string;
	}
	public function getCustomerTypeId(){
		return $this->customerTypeId;
	}

	public function setEmail( $string ){
		$this->email = $string;
	}
	public function getEmail(){
		return $this->email;
	}

	public function setRelativeTel( $string ){
		$this->relativeTel = $string;
	}
	public function getRelativeTel(){
		return $this->relativeTel;
	}

	public function setRelativeContact( $string ){
		$this->relativeContact = $string;
	}
	public function getRelativeContact(){
		return $this->relativeContact;
	}

	public function setTel( $string ){
		$this->tel = $string;
	}
	public function getTel(){
		return $this->tel;
	}

	public function setAddress( $string ){
		$this->address = $string;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setDob( $date ){
		$this->dob =  date('Y-m-d', strtotime( $date ));
	}

	public function getDob(){
		return $this->dob;
	}

	public function setFullName( $string ){
		$this->fullName = $string;
	}
	
	public function getFullName(){
		return $this->fullName;
	}

	public function setCustomerType( $string ){
		$this->customerType = $string;
	}

	public function getCustomerType(){
		return $this->customerType;
	}

	public function setDoctorFields( $string ){
		$this->doctorFields = $string;
	}

	public function getDoctorFields(){
		return $this->doctorFields;
	}

	public function setCustomerid( $string ){
		$this->customerid = $string;
	}

	public function getCustomerid(){
		return $this->customerid;
	}
}
