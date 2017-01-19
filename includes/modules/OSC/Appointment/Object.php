<?php

namespace OSC\Appointment;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\AppointmentDetail\Collection as AppointmentDetailCol,
	OSC\CustomerList\Collection as CustomerCol
;

class Object extends DbObj {
		
	protected
		$invoiceId,
		$invoiceNo,
		$customerId,
		$customerName,
		$invoiceDate,
		$customerTelephone,
		$detail,
		$customerAddress,
		$customerCountry,
		$customerGender,
		$customerDob,
		$customerDetail
	;

	public function __construct( $params = array() )
	{
		parent::__construct($params);
		$this->detail = new AppointmentDetailCol();
		$this->customerDetail = new CustomerCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'invoice_id',
				'invoice_no',
				'invoice_date',
				'customer_id',
				'customer_detail',
				'detail',
				'status'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				invoice_id,
				invoice_no,
				invoice_date,
				customer_id,
				status
			FROM
				appointment
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Appointment not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->customerDetail->setFilter('id', $this->getCustomerId());
		$this->customerDetail->populate();

		$this->detail->setFilter('appointment_id', $this->getId());
		$this->detail->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				appointment
			SET
				update_by = '" . $this->getUpdateBy() . "',
				customer_id = '" . $this->getCustomerId() . "'
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
				appointment
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
				appointment
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				appointment
			(
				invoice_id,
				invoice_no,
				invoice_date,
				customer_id,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getInvoiceId() . "',
				'" . $this->getInvoiceNo() . "',
				'" . $this->getInvoiceDate() . "',
				'" . $this->getCustomerId() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setInvoiceDate( $string ){
		if($string != '0000-00-00 00:00:00'){
			$string = date('Y-m-d', strtotime( $string ));
		}
		$this->invoiceDate = $string;
	}
	public function getInvoiceDate(){
		return $this->invoiceDate;
	}

	public function setInvoiceNo( $string ){
		$this->invoiceNo = $string;
	}
	public function getInvoiceNo(){
		return $this->invoiceNo;
	}

	public function setInvoiceId( $string ){
		$this->invoiceId = $string;
	}
	public function getInvoiceId(){
		return $this->invoiceId;
	}
	public function setCustomerDetail( $string ){
		$this->customerDetail = $string;
	}
	public function getCustomerDetail(){
		return $this->customerDetail;
	}

	public function setCustomerName( $string ){
		$this->customerName = $string;
	}
	public function getCustomerName(){
		return $this->customerName;
	}

	public function setCustomerTelephone( $string ){
		$this->customerTelephone = $string;
	}
	public function getCustomerTelephone(){
		return $this->customerTelephone;
	}

	public function setCustomerId( $string ){
		$this->customerId = $string;
	}
	public function getCustomerId(){
		return $this->customerId;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}
	public function getDetail(){
		return $this->detail;
	}


	public function setCustomerAddress( $string ){
		$this->customerAddress = $string;
	}
	public function getCustomerAddress(){
		return $this->customerAddress;
	}

	public function setCustomerDob( $string ){
		$this->customerDob = $string;
	}
	public function getCustomerDob(){
		return $this->customerDob;
	}

	public function setCustomerGender( $string ){
		$this->customerGender = $string;
	}
	public function getCustomerGender(){
		return $this->customerGender;
	}

	public function setCustomerCountry( $string ){
		$this->customerCountry = $string;
	}
	public function getCustomerCountry(){
		return $this->customerCountry;
	}
}
