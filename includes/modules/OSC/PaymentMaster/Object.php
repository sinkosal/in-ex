<?php

namespace OSC\PaymentMaster;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\PaymentMasterDetail\Collection as PaymentDetailCol
	;

class Object extends DbObj {

	protected
		$vendorPaymentNo
		, $referenceNo
		, $vendorId
		, $vendorName
		, $paymentDate
		, $description
		, $totalAmount
		, $discountType
		, $discount
		, $totalDiscount
		, $paymentMethod
		, $bankCharge
		, $grandTotal
		, $totalBalance
		, $totalLastBalance
		, $detail
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new PaymentDetailCol();
	}


	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'vendor_payment_no',
				'reference_no',
				'vendor_id',
				'vendor_name',
				'payment_date',
				'description',
				'total_amount',
				'discount_type',
				'discount',
				'total_discount',
				'payment_method',
				'bank_charge',
				'grand_total',
				'total_balance',
				'total_last_balance',
				'detail'
			)
		);

		return parent::toArray($args);
	}

	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				vendor_payment_no,
				reference_no,
				vendor_id,
				vendor_name,
				payment_date,
				description,
				total_amount,
				discount_type,
				discount,
				total_discount,
				payment_method,
				bank_charge,
				grand_total,
				total_balance,
				total_last_balance
			FROM
				payment_master
			WHERE
				id = '" . (int)$this->getId() . "'	
		");

		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Payment Master not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->detail->setFilter('payment_id', $this->getId());
		$this->detail->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				products_type
			SET
				name = '" . $this->getName() . "',
				description = '" . $this->getDescription() . "'
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
				products_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				payment_master
			(
				vendor_payment_no,
				reference_no,
				vendor_id,
				vendor_name,
				payment_date,
				description,
				total_amount,
				discount_type,
				discount,
				total_discount,
				payment_method,
				bank_charge,
				grand_total,
				total_balance,
				total_last_balance,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getVendorPaymentNo() . "',
				'" . $this->getReferenceNo() . "',
				'" . $this->getVendorId() . "',
				'" . $this->getVendorName() . "',
				'" . $this->getPaymentDate() . "',
				'" . $this->getDescription() . "',
				'" . $this->getTotalAmount() . "',
				'" . $this->getDiscountType() . "',
				'" . $this->getDiscount() . "',
				'" . $this->getTotalDiscount() . "',
				'" . $this->getPaymentMethod() . "',
				'" . $this->getBankCharge() . "',
				'" . $this->getGrandTotal() . "',
				'" . $this->getTotalBalance() . "',
				'" . $this->getTotalLastBalance() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
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

	public function setDetail( $string ){
		$this->detail = $string;
	}
	public function getDetail(){
		return $this->detail;
	}

	public function setDescription( $string ){
		$this->description = $string;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setVendorId( $string ){
		$this->vendorId = $string;
	}

	public function getVendorId(){
		return $this->vendorId;
	}

	public function setVendorName( $string ){
		$this->vendorName = $string;
	}

	public function getVendorName(){
		return $this->vendorName;
	}

	public function setVendorPaymentNo( $string ){
		$this->vendorPaymentNo = $string;
	}

	public function getVendorPaymentNo(){
		return $this->vendorPaymentNo;
	}

	public function setReferenceNo( $string ){
		$this->referenceNo = $string;
	}

	public function getReferenceNo(){
		return $this->referenceNo;
	}

	public function setPaymentDate( $string ){
		$this->paymentDate = date('Y-m-d', strtotime( $string ));;
	}

	public function getPaymentDate(){
		return $this->paymentDate;
	}

	public function setTotalDiscount( $string ){
		$this->totalDiscount = doubleval($string);
	}

	public function getTotalDiscount(){
		return $this->totalDiscount;
	}

	public function setTotalAmount( $string ){
		$this->totalAmount = doubleval($string);
	}
	public function getTotalAmount(){
		return $this->totalAmount;
	}

	public function setTotalBalance( $string ){
		$this->totalBalance = doubleval($string);
	}
	public function getTotalBalance(){
		return $this->totalBalance;
	}

	public function setTotalLastBalance( $string ){
		$this->totalLastBalance = doubleval($string);
	}
	public function getTotalLastBalance(){
		return $this->totalLastBalance;
	}

	public function setGrandTotal( $string ){
		$this->grandTotal = doubleval($string);
	}
	public function getGrandTotal(){
		return $this->grandTotal;
	}

	public function setDiscountType( $string ){
		$this->discountType = $string;
	}
	public function getDiscountType(){
		return $this->discountType;
	}

	public function setDiscount( $string ){
		$this->discount = $string;
	}
	public function getDiscount(){
		return $this->discount;
	}

	public function setPaymentMethod( $string ){
		$this->paymentMethod = $string;
	}
	public function getPaymentMethod(){
		return $this->paymentMethod;
	}


}
