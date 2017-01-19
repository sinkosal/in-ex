<?php

namespace OSC\Stock;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$stockid,
		$stocktype,
		$productname,
		$barcode,
		$supplierid,
		$qty,
		$detail,
		$createby,
		$updateby
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'stocktype',
				'productname',
				'barcode',
				'supplierid',
				'qty',
				'detail',
				'createby',
				'updateby',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				stockid,
				stocktype,
				productname,
				barcode,
				supplierid,
				qty,
				detail,
				createby,
				updateby
			FROM
				stock
			WHERE
				stockid = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Stock not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}
	public function insert(){
		$this->dbQuery("
			INSERT INTO
				stock
			(
				stocktype,
				productname,
				barcode,
				supplierid,
				qty,
				detail,
				createby,
				updateby
			)
				VALUES
			(
				'" . (int)$this->getStocktype() . "',
				'" . $this->getProductname() . "',
				'" . $this->getBarcode() . "',
				'" . $this->getSupplierid() . "',
				'" . $this->getQty() . "',
				'" . $this->getDetail() . "',
				'" . $this->getCreateby() . "',
				'" . $this->getUpdateby() . "'
			)
		");
		$this->setProductsId( $this->dbInsertId() );
	}

	public function updateStock(){
		$this->dbQuery("
			UPDATE
				stock
			SET
 				qty = qty - '" . $this->getQty() . "'
			WHERE
				stockid = '" . (int)$this->getId() . "'
		");
	}

	public function setUpdateby( $string ){
		$this->updateby = $string;
	}

	public function getUpdateby(){
		return $this->updateby;
	}

	public function setCreateby( $string ){
		$this->createby = $string;
	}

	public function getCreateby(){
		return $this->createby;
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setQty( $string ){
		$this->qty = (int)$string;
	}

	public function getQty(){
		return $this->qty;
	}

	public function setSupplierid( $string ){
		$this->supplierid = (int)$string;
	}

	public function getSupplierid(){
		return $this->supplierid;
	}

	public function setBarcode( $string ){
		$this->barcode = $string;
	}

	public function getBarcode(){
		return $this->barcode;
	}

	public function setProductname( $string ){
		$this->productname = $string;
	}

	public function getProductname(){
		return $this->productname;
	}

	public function setStocktype( $string ){
		$this->stocktype = (int)$string;
	}

	public function getStocktype(){
		return $this->stocktype;
	}

	public function setStockid( $string ){
		$this->stockid = (int)$string;
	}
	
	public function getStockid(){
		return $this->stockid;
	}
	
}
