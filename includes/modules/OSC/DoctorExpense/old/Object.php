<?php

namespace OSC\DoctorExpense;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\DoctorListOnly\Collection as doctorCol,
	OSC\CustomerListOnly\Collection as customerCol
;

class Object extends DbObj {
		
	protected
		$doctorId
		, $customerId
		, $expenseDate
		, $invoiceNo
		, $description
		, $qty
		, $price
		, $amount
		, $doctorDetail
		, $customerDetail
	;
	
	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->doctorDetail = new doctorCol();
		$this->customerDetail = new customerCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'doctor_detail',
				'customer_detail',
				'status',
				'description',
				'expense_date',
				'invoice_no',
				'qty',
				'price',
				'amount'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				doctor_id,	
				customer_id,
				status,
				description,
				expense_date,
				invoice_no,
				qty,
				price,
				amount
			FROM
				doctor_expense
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Doctor Expense not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->customerDetail->setFilter('id', $this->getCustomerId());
		$this->customerDetail->populate();

		$this->doctorDetail->setFilter('id', $this->getDoctorId());
		$this->doctorDetail->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				doctor_expense
			SET
				expense_date = '" . $this->getExpenseDate() . "',
				invoice_no = '" . $this->getInvoiceNo() . "',
				doctor_id = '" . $this->getDoctorId() . "',
				customer_id = '" . $this->getCustomerId() . "',
				description = '" . $this->getDescription() . "',
				qty = '" . $this->getQty() . "',
				price = '" . $this->getPrice() . "',
				amount = '" . $this->getAmount() . "',
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
				doctor_expense
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
				doctor_expense
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				doctor_expense
			(
				doctor_id,
				customer_id,
				status,
				description,
				expense_date,
				invoice_no,
				qty,
				price,
				amount,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getDoctorId() . "',
				'" . $this->getCustomerId() . "',
				1,
				'" . $this->getDescription() . "',
				'" . $this->getExpenseDate() . "',
				'" . $this->getInvoiceNo() . "',
				'" . $this->getQty() . "',
				'" . $this->getPrice() . "',
				'" . $this->getAmount() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDoctorId( $string ){
		$this->doctorId = (int)$string;
	}

	public function getDoctorId(){
		return $this->doctorId;
	}

	public function setDescription( $string ){
		$this->description = $string;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setInvoiceNo( $string ){
		$this->invoiceNo = $string;
	}

	public function getInvoiceNo(){
		return $this->invoiceNo;
	}

	public function setAmount( $string ){
		$this->amount = doubleval($string);
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setPrice( $string ){
		$this->price = doubleval($string);
	}

	public function getPrice(){
		return $this->price;
	}

	public function setQty( $string ){
		$this->qty = (int)$string;
	}

	public function getQty(){
		return $this->qty;
	}

	public function setExpenseDate( $string ){
		$this->expenseDate = date('Y-m-d', strtotime( $string ));
	}

	public function getExpenseDate(){
		return $this->expenseDate;
	}

	public function setCustomerId( $string ){
		$this->customerId = (int)$string;
	}

	public function getCustomerId(){
		return $this->customerId;
	}

	public function setDoctorDetail( $string ){
		$this->doctorDetail = $string;
	}
	
	public function getDoctorDetail(){
		return $this->doctorDetail;
	}

	public function setCustomerDetail( $string ){
		$this->customerDetail = $string;
	}
	
	public function getCustomerDetail(){
		return $this->customerDetail;
	}

}
