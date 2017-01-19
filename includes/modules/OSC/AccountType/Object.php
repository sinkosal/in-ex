<?php

namespace OSC\AccountType;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$name
		, $minValues
		, $maxValues
		, $drcr
		,  $balanceSheetId
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'min_values',
				'max_values',
				'balance_sheet_id',
				'drcr',
				'status',
				'create_date'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				min_values,
				max_values,
				balance_sheet_id,
				drcr,
				status,
				create_date
			FROM
				account_type
			WHERE
				id = '" . (int)$this->getId() . "'
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Account Type not found",
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
				account_type
			SET
				name = '" . $this->getName() . "',
				min_values = '" . $this->getMinValues() . "',
				max_values = '" . $this->getMaxvalues() . "',
				drcr = '" . $this->getDrcr() . "',
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
				account_type
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
				accunt_type
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				account_type
			(
				name,
				min_values,
				max_values,
				drcr,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getMinValues() . "',
				'" . $this->getMaxValues() . "',
				'" . $this->getDrcr() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}
// set and get name
	public function setName( $string ){
		$this->name = $string;
	}

	public function getName(){
		return $this->name;
	}
//set and get min values
	public function setMinValues( $string ){
		$this->minValues = $string;
	}
	
	public function getMinValues(){
		return $this->minValues;
	}
//set and get max values
	public function setMaxValues( $string ){
		$this-> maxValues = $string;
	}
	public function getMaxValues (){
		return $this->maxValues;
	}

	// set and get debit credit
	public function setDrcr ( $string ){
		$this->drcr =$string;
	}
	public function getDrcr (){
		return $this->drcr;
	}

	public function setBalanceSheetId( $string ){
		$this->balanceSheetId = $string;
	}
	public function getBalanceSheetId(){
		return $this->balanceSheetId;
	}
}
