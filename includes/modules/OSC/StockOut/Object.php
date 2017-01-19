<?php

namespace OSC\StockOut;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\StockOutDetail\Collection as StockOutDetailCol
;

class Object extends DbObj {
		
	protected
		$stockOutDate
		, $customerId
		, $requestById
		, $requestByName
		, $approveById
		, $approveByName
		, $deliveryTo
		, $grandTotal
		, $payment
		, $remain
		, $note
		, $detail
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new StockOutDetailCol();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'stock_out_date',
				'request_by_id',
				'request_by_name',
				'approve_by_id',
				'approve_by_name',
				'delivery_to',
				'grand_total',
				'payment',
				'remain',
				'note',
				'detail'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				stock_out_date,
				request_by_id,
				request_by_name,
				approve_by_id,
				approve_by_name,
				grand_total,
				payment,
				remain,
				delivery_to,
				note
			FROM
				stock_out
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Stock Out not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->detail->setFilter('stock_out_id', $this->getId());
		$this->detail->populate();
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				stock_out
			(
				stock_out_date,
				delivery_to,
				request_by_id,
				request_by_name,
				approve_by_id,
				approve_by_name,
				grand_total,
				payment,
				remain,
				note,
				create_by
			)
				VALUES
			(
				'" . $this->getStockOutDate() . "',
				'" . $this->getDeliveryTo() . "',
				'" . $this->getRequestById() . "',
				'" . $this->getRequestByName() . "',
				'" . $this->getApproveById() . "',
				'" . $this->getApproveByName() . "',
				'" . $this->getGrandTotal() . "',
				'" . $this->getPayment() . "',
				'" . $this->getRemain() . "',
				'" . $this->getNote() . "',
				'" . $this->getCreateBy() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setNote( $string ){
		$this->note = $string;
	}

	public function getNote(){
		return $this->note;
	}

	public function setRemain( $string ){
		if( $string < 0){
			$string= 0;
		}
		$this->remain = doubleval($string);
	}

	public function getRemain(){
		return $this->remain;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setPayment( $string ){
		$this->payment =  doubleval($string);
	}

	public function getPayment(){
		return $this->payment;
	}

	public function setGrandTotal( $string ){
		$this->grandTotal = doubleval($string);
	}

	public function getGrandTotal(){
		return $this->grandTotal;
	}

	public function setRequestById( $string ){
		$this->requestById = (int)$string;
	}

	public function getRequestById(){
		return $this->requestById;
	}

	public function setDeliveryTo( $string ){
		$this->deliveryTo = $string;
	}

	public function getDeliveryTo(){
		return $this->deliveryTo;
	}

	public function setStockOutDate( $date ){
		$this->stockOutDate = date('Y-m-d', strtotime( $date ));
	}
	
	public function getStockOutDate(){
		return $this->stockOutDate;
	}

	public function setRequestByName( $string ){
		$this->requestByName = $string;
	}

	public function getRequestByName(){
		return $this->requestByName;
	}

	public function setApproveById( $string ){
		$this->approveById = (int)$string;
	}

	public function getApproveById(){
		return $this->approveById;
	}

	public function setApproveByName( $string ){
		$this->approveByName = $string;
	}

	public function getApproveByName(){
		return $this->approveByName;
	}

}
