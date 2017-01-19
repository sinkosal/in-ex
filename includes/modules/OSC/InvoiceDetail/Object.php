<?php

namespace OSC\InvoiceDetail;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$invoiceDetailId
		, $invoiceDetailNo
		, $serviceId
		, $serviceName
		, $dentOrder
		, $color
		, $unitPrice
		, $qty
		, $total
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'invoice_detail_id',
				'invoice_detail_no',
				'service_id',
				'service_name',
				'dent_order',
				'color',
				'unit_price',
				'qty',
				'total',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				invoice_detail_id,
				invoice_detail_no,
				service_id,
				service_name,
				dent_order,
				color,
				unit_price,
				qty,
				total
			FROM
				invoice_detail
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Invoice Detail not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				doctor_type
			SET
				name = '" . $this->getName() . "',
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
				doctor_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				invoice_detail
			(
				invoice_detail_id,
				invoice_detail_no,
				service_id,
				service_name,
				dent_order,
				color,
				unit_price,
				qty,
				total
			)
				VALUES
			(
				'" . $this->getInvoiceDetailId() . "',
				'" . $this->getInvoiceDetailNo() . "',
				'" . $this->getServiceId() . "',
				'" . $this->getServiceName() . "',
				'" . $this->getDentOrder() . "',
				'" . $this->getColor() . "',
				'" . $this->getUnitPrice() . "',
				'" . $this->getQty() . "',
				'" . $this->getTotal() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setInvoiceDetailId( $string ){
		$this->invoiceDetailId = (int)$string;
	}

	public function getInvoiceDetailId(){
		return $this->invoiceDetailId;
	}

	public function setInvoiceDetailNo( $string ){
		$this->invoiceDetailNo = $string;
	}

	public function getInvoiceDetailNo(){
		return $this->invoiceDetailNo;
	}

	public function setServiceName( $string ){
		$this->serviceName = $string;
	}

	public function getServiceName(){
		return $this->serviceName;
	}

	public function setServiceId( $string ){
		$this->serviceId = (int)$string;
	}

	public function getServiceId(){
		return $this->serviceId;
	}

	public function setDentOrder( $string ){
		$this->dentOrder = $string;
	}

	public function getDentOrder(){
		return $this->dentOrder;
	}

	public function setColor( $string ){
		$this->color = $string;
	}

	public function getColor(){
		return $this->color;
	}

	public function setUnitPrice( $string ){
		$this->unitPrice = doubleval($string);
	}

	public function getUnitPrice(){
		return $this->unitPrice;
	}

	public function setQty( $string ){
		$this->qty = (int)$string;
	}

	public function getQty(){
		return $this->qty;
	}

	public function setTotal( $string ){
		$this->total = doubleval($string);
	}

	public function getTotal(){
		return $this->total;
	}

}

