<?php

namespace OSC\Invoice;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\InvoiceDetail\Collection
		as  InvoiceDetailCol
;

class Object extends DbObj {
		
	protected
		$invoiceNo
		, $referenceNo
		, $invoiceDate
		, $customerTel
		, $customerId
		, $customerName
		, $customerTypeId
		, $customerTypeName
		, $doctorId
		, $doctorName
		, $total
		, $noted
		, $payType
		, $bank
		, $subTotal
		, $discount
		, $discountType
		, $totalDiscount
		, $grandTotal
		, $deposit
		, $balance
		, $detail
		, $bankCharge
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new InvoiceDetailCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'invoice_no',
				'reference_no',
				'invoice_date',
				'customer_type_id',
				'customer_type_name',
				'customer_id',
				'customer_name',
				'customer_tel',
				'doctor_id',
				'doctor_name',
				'noted',
				'pay_type',
				'bank',
				'sub_total',
				'discount',
				'discount_type',
				'total_discount',
				'grand_total',
				'deposit',
				'balance',
				'detail',
				'status',
				'bank_charge',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				invoice_no,
				customer_tel,
				customer_type_id,
				customer_type_name,
				reference_no,
				doctor_id,
				noted,
				pay_type,
				bank,
				sub_total,
				discount,
				discount_type,
				total_discount,
				grand_total,
				deposit,
				invoice_date,
				balance,
				customer_id,
				customer_name,
				doctor_name,
				status,
				bank_charge
			FROM
				invoice
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Invoice not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
		$this->detail->setFilter('id', $this->getId());
		$this->detail->populate();
	}

	public function update(){
		$this->dbQuery("
			UPDATE
				invoice
			SET
                balance = '" . $this->getBalance() . "',
                update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
	}
//	public function update(){
//		$this->dbQuery("
//			UPDATE
//				invoice
//			SET
//                balance = '" . $this->getBalance() . "',
//                deposit = deposit + '" . $this->getDeposit() . "',
//                update_by = '" . $this->getUpdateBy() . "'
//			WHERE
//				id = '" . (int)$this->getId() . "'
//		");
//	}

	public function updateStatus(){
		$this->dbQuery("
			UPDATE
				invoice
			SET
                status = '" . $this->getStatus() . "',
                update_by = '" . $this->getUpdateBy() . "'
			WHERE
				invoice_no = '" . $this->getInvoiceNo() . "'
		");

		// update case flow doctor
		$this->dbQuery("
			UPDATE
				case_flow_doctor
			SET
                status = '" . $this->getStatus() . "',
                update_by = '" . $this->getUpdateBy() . "'
			WHERE
				invoice_no = '" . $this->getInvoiceNo() . "'
		");

	}


	public function delete($id){
		if( !$id ) {
			throw new Exception("delete method requires id to be set");
		}
		$this->dbQuery("
			DELETE FROM
				customer_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				invoice
			(
				invoice_no,
				reference_no,
				invoice_date,
				customer_id,
				customer_name,
				customer_type_id,
				customer_type_name,
				doctor_id,
				doctor_name,
				noted,
				pay_type,
				bank,
				sub_total,
				discount,
				discount_type,
				total_discount,
				grand_total,
				deposit,
				balance,
				create_by,
				customer_tel,
				bank_charge,
				create_date,
				status
			)
				VALUES
			(
				'" . $this->getInvoiceNo() . "',
				'" . $this->getReferenceNo() . "',
				'" . $this->getInvoiceDate() . "',
				'" . $this->getCustomerId() . "',
				'" . $this->getCustomerName() . "',
				'" . $this->getCustomerTypeId() . "',
				'" . $this->getCustomerTypeName() . "',
				'" . $this->getDoctorId() . "',
				'" . $this->getDoctorName() . "',
				'" . $this->getNoted() . "',
				'" . $this->getPayType() . "',
				'" . $this->getBank() . "',
				'" . $this->getSubTotal() . "',
				'" . $this->getDiscount() . "',
				'" . $this->getDiscountType() . "',
				'" . $this->getTotalDiscount() . "',
				'" . $this->getGrandTotal() . "',
				'" . $this->getDeposit() . "',
				'" . $this->getBalance() . "',
				'" . $this->getCreateBy() . "',
				'" . $this->getCustomerTel() . "',
				'" . $this->getBankCharge() . "',
 				NOW(),
 				'" . $this->getStatus() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setBankCharge( $string ){
		$this->bankCharge = doubleval($string);
	}
	public function getBankCharge(){
		return $this->bankCharge;
	}

	public function setCustomerTel( $string ){
		$this->customerTel = $string;
	}
	public function getCustomerTel(){
		return $this->customerTel;
	}

	public function setDoctorName( $string ){
		$this->doctorName = $string;
	}
	public function getDoctorName(){
		return $this->doctorName;
	}

	public function setCustomerId( $string ){
		$this->customerId = (int)$string;
	}
	public function getCustomerId(){
		return $this->customerId;
	}

	public function setCustomerName( $string ){
		$this->customerName = $string;
	}
	public function getCustomerName(){
		return $this->customerName;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}
	public function getDetail(){
		return $this->detail;
	}

	public function setInvoiceDate( $string ){
		$this->invoiceDate = date('Y-m-d', strtotime( $string ));
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

	public function setReferenceNo( $string ){
		$this->referenceNo = $string;
	}
	public function getReferenceNo(){
		return $this->referenceNo;
	}

	public function setNoted( $string ){
		$this->noted = $string;
	}
	public function getNoted(){
		return $this->noted;
	}

	public function setTotal( $string ){
		$this->total = $string;
	}
	public function getTotal(){
		return $this->total;
	}

	public function setGrandTotal( $string ){
		$this->grandTotal = doubleval($string);
	}
	public function getGrandTotal(){
		return $this->grandTotal;
	}

	public function setSubTotal( $string ){
		$this->subTotal = doubleval($string);
	}
	public function getSubTotal(){
		return $this->subTotal;
	}

	public function setPayType( $string ){
		$this->payType = $string;
	}
	public function getPayType(){
		return $this->payType;
	}

	public function setDiscount( $string ){
		$this->discount = doubleval($string);
	}
	public function getDiscount(){
		return $this->discount;
	}

	public function setDiscountType( $string ){
		$this->discountType = $string;
	}
	public function getDiscountType(){
		return $this->discountType;
	}

	public function setTotalDiscount( $string ){
		$this->totalDiscount = doubleval($string);
	}
	public function getTotalDiscount(){
		return $this->totalDiscount;
	}

	public function setDeposit( $string ){
		$this->deposit = doubleval($string);
	}
	public function getDeposit(){
		return $this->deposit;
	}

	public function setBalance( $decimal ){
		if( $decimal < 0){
			$decimal = 0;
		}
		$this->balance = doubleval($decimal);
	}
	public function getBalance(){
		return $this->balance;
	}

	public function setDoctorId( $string ){
		$this->doctorId = (int)$string;
	}
	public function getDoctorId(){
		return $this->doctorId;
	}

	public function setCustomerTypeId( $string ){
		$this->customerTypeId = (int)$string;
	}
	public function getCustomerTypeId(){
		return $this->customerTypeId;
	}

	public function setCustomerTypeName( $string ){
		$this->customerTypeName = $string;
	}
	public function getCustomerTypeName(){
		return $this->customerTypeName;
	}

	public function setBank( $string ){
		$this->bank = $string;
	}
	public function getBank(){
		return $this->bank;
	}

}
