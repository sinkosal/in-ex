<?php

namespace OSC\CustomerType;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name
		, $description
		, $amount
		, $rates
		, $total

	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'description',
				'status',
				'amount',
				'rates',
				'total'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				description,
				status,
				amount,
				rates
			FROM
				customer_type
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Customer Type not found",
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
				customer_type
			SET
				name = '" . $this->getName() . "',
				description = '" . $this->getDescription() . "',
				update_by = '" . $this->getUpdateBy() . "',
				amount = '" . $this->getAmount(). "',
				rates = '" . $this->getRates(). "',
				total = '" . $this->getTotal() ."'		
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
				customer_type
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
				customer_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				customer_type
			(
				name,
				description,
				create_by,
				create_date,
				amount,
				rates,
				total
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getDescription() . "',
				'" . $this->getCreateBy() . "',
 				NOW(),
 				'" . $this->getAmount() ."',
 				'" . $this->getRates() . "',
 				'" . $this->getTotal() . "'
 			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setRates($rate){
		$this->rates = $rate;
	}
	public function getRates(){
		return $this->rates;
	}
	public function setAmount($amount){
		$this->amount = $amount;
	}
	public function getAmount(){
		return $this->amount;
	}
	public function setDescription( $string ){
		$this->description = $string;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setName( $string ){
		$this->name = $string;
	}
	
	public function getName(){
		return $this->name;
	}
	public function getTotal(){
		return $this->getAmount() * $this->getRates();
	}
}