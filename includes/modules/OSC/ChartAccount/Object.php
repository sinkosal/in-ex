<?php

namespace OSC\ChartAccount;

use
	Aedea\Core\Database\StdObject as DbObj
	, OSC\AccountType\Collection as AccountTypeCollection
;

class Object extends DbObj {
		
	protected
		$name,
		$accountCode,
		$accountTypeId,
		$detail
	;

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->detail = new AccountTypeCollection();
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'name',
				'account_code',
				'status',
				'detail'
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				name,
				account_code,
				account_type_id,
				status
			FROM
				account_chart
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Chart Account not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->detail->setFilter('id', $this->getAccountTypeId());
		$this->detail->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				account_chart
			SET
				name = '" . $this->getName() . "',
				account_code = '" . $this->getAccountCode() . "',
				account_type_id = '" . $this->getAccountTypeId() . "',
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
				account_chart
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
				account_chart
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				account_chart
			(
				name,
				account_code,
				account_type_id,
				create_by,
				status,
				create_date
			)
				VALUES
			(
				'" . $this->getName() . "',
				'" . $this->getAccountCode() . "',
				'" . $this->getAccountTypeId() . "',
				'" . $this->getCreateBy() . "',
				1,
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDetail( $string ){
		$this->detail = $string;
	}

	public function getDetail(){
		return $this->detail;
	}

	public function setAccountTypeId( $string ){
		$this->accountTypeId = (int)$string;
	}

	public function getAccountTypeId(){
		return $this->accountTypeId;
	}

	public function setAccountCode( $string ){
		$this->accountCode = $string;
	}

	public function getAccountCode(){
		return $this->accountCode;
	}

	public function setName( $string ){
		$this->name = $string;
	}
	
	public function getName(){
		return $this->name;
	}

}
