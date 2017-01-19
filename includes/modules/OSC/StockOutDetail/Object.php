<?php

namespace OSC\StockOutDetail;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$stockOutId
		, $productId
		, $productName
		, $qty
		, $unitPrice
		, $total
		, $description
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'product_name',
				'qty',
				'product_id',
				'total',
				'stock_out_id',
				'description',
				'unit_price'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				stock_out_id,
				product_id,
				product_name,
				qty,
				total,
				description,
				unit_price
			FROM
				stock_out_detail
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Stock Out Detail not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				stock_out_detail
			(
				stock_out_id,
				product_id,
				product_name,
				qty,
				total,
				description,
				unit_price
			)
				VALUES
			(
				'" . $this->getStockOutId() . "',
				'" . $this->getProductId() . "',
				'" . $this->getProductName() . "',
				'" . $this->getQty() . "',
				'" . $this->getTotal() . "',
				'" . $this->getDescription() . "',
				'" . $this->getUnitPrice() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setProductName( $string ){
		$this->productName = $string;
	}

	public function getProductName(){
		return $this->productName;
	}

	public function setDescription( $string ){
		$this->description = $string;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setTotal( $string ){
		$this->total = $string;
	}

	public function getTotal(){
		return $this->total;
	}

	public function setUnitPrice( $string ){
		$this->unitPrice = $string;
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

	public function setProductId( $string ){
		$this->productId = (int)$string;
	}

	public function getProductId(){
		return $this->productId;
	}

	public function setStockOutId( $string ){
		$this->stockOutId = (string)$string;
	}
	
	public function getStockOutId(){
		return $this->stockOutId;
	}
	
}
