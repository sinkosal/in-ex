<?php

namespace OSC\Service;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\CustomerType\Collection
		as  CustomerTypeCol
;

class Object extends DbObj {
		
	protected
		$serviceName
		, $type
		, $customerTypeId
		, $normal
		, $unit
		, $price
		, $detail
		, $createBy
		, $modifiesBy
		, $customerType
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->customerType = new CustomerTypeCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'service_name',
				'customer_type',
				'type',
				'normal',
				'unit',
				'price',
				'detail',
				'status'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				service_name,
				detail,
				type,
				customer_type_id,
				unit,
				price,
				normal,
				status
			FROM
				services
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Service not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->customerType->setFilter('id', $this->getCustomerTypeId());
		$this->customerType->populate();

	}

	public function updateStatus($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				services
			SET
				status = '" . $this->getStatus() . "'
			WHERE
				id = '" . (int)$id . "'
		");

	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				services
			SET
				service_name = '" . $this->getServiceName() . "',
				type = '" . $this->getType() . "',
				customer_type_id = '" . $this->getCustomerTypeId() . "',
				normal = '" . $this->getNormal() . "',
				unit = '" . $this->getUnit() . "',
				price = '" . $this->getPrice() . "',
				detail = '" . $this->getDetail() . "'
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
				services
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				services
			(
				service_name,
				detail,
				type,
				customer_type_id,
				unit,
				price,
				normal,
				status,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getServiceName() . "',
				'" . $this->getDetail() . "',
				'" . $this->getType() . "',
				'" . $this->getCustomerTypeId() . "',
				'" . $this->getUnit() . "',
				'" . $this->getPrice() . "',
				'" . $this->getNormal() . "',
				1,
				'" . $this->getCreateBy() ."',
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

	public function setServiceName( $string ){
		$this->serviceName = $string;
	}
	
	public function getServiceName(){
		return $this->serviceName;
	}


	public function setPrice( $string ){
		$this->price = doubleval($string);
	}

	public function getPrice(){
		return $this->price;
	}
	public function setUnit( $string ){
		$this->unit = $string;
	}

	public function getUnit(){
		return $this->unit;
	}
	public function setNormal( $string ){
		$this->normal = $string;
	}

	public function getNormal(){
		return $this->normal;
	}

	public function setCustomerTypeId( $string ){
		$this->customerTypeId = $string;
	}

	public function getCustomerTypeId(){
		return $this->customerTypeId;
	}

	public function setType( $string ){
		$this->type = $string;
	}

	public function getType(){
		return $this->type;
	}

	public function setCustomerType( $string ){
		$this->customerType = $string;
	}

	public function getCustomerType(){
		return $this->customerType;
	}

}
