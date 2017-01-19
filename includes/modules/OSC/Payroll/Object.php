<?php

namespace OSC\Payroll;

use
	Aedea\Core\Database\StdObject as DbObj,
	OSC\Staff\Collection as staffCol
;

class Object extends DbObj {

	public function __construct( $params = array() ){
		parent::__construct($params);
		$this->staffDetail = new staffCol();
	}

	protected
		$staffId,
		$regularRate,
		$totalRegularAmount,
		$ot,
		$otRate,
		$totalOtAmount,
		$servicePerform,
		$benefit,
		$totalServicePerform,
		$note,
		$otherCompensation,
		$spouseMinor,
		$grossSalary,
		$taxableSalary,
		$tax,
		$taxAmount,
		$netSalary,
		$totalSalary,
		$staffAdvance,
		$finalSalary,
		$accountTypeId,
		$accountTypeName,
		$staffDetail
	;

	public function setTotalSalary( $string ){
		$this->totalSalary = doubleval($string);
	}
	public function getTotalSalary(){
		return $this->totalSalary;
	}

	public function setTaxableSalary( $string ){
		$this->taxableSalary = doubleval($string);
	}
	public function getTaxableSalary(){
		return $this->taxableSalary;
	}

	public function setStaffDetail( $string ){
		$this->staffDetail = $string;
	}
	public function getStaffDetail(){
		return $this->staffDetail;
	}

	public function setAccountTypeName( $string ){
		$this->accountTypeName = $string;
	}
	public function getAccountTypeName(){
		return $this->accountTypeName;
	}

	public function setAccountTypeId( $string ){
		$this->accountTypeId = (int)$string;
	}
	public function getAccountTypeId(){
		return $this->accountTypeId;
	}

	public function setFinalSalary( $string ){
		$this->finalSalary = doubleval($string);
	}
	public function getFinalSalary(){
		return $this->finalSalary;
	}

	public function setStaffAdvance( $string ){
		$this->staffAdvance = doubleval($string);
	}
	public function getStaffAdvance(){
		return $this->staffAdvance;
	}

	public function setNetSalary( $string ){
		$this->netSalary = doubleval($string);
	}
	public function getNetSalary(){
		return $this->netSalary;
	}

	public function setTaxAmount( $string ){
		$this->taxAmount = doubleval($string);
	}
	public function getTaxAmount(){
		return $this->taxAmount;
	}

	public function setTax( $string ){
		$this->tax = $string;
	}
	public function getTax(){
		return $this->tax;
	}

	public function setGrossSalary( $string ){
		$this->grossSalary = doubleval($string);
	}
	public function getGrossSalary(){
		return $this->grossSalary;
	}

	public function setSpouseMinor( $string ){
		$this->spouseMinor = doubleval($string);
	}
	public function getSpouseMinor(){
		return $this->spouseMinor;
	}

	public function setOtherCompensation( $string ){
		$this->otherCompensation = (int)$string;
	}
	public function getOtherCompensation(){
		return $this->otherCompensation;
	}

	public function setNote( $string ){
		$this->note = $string;
	}
	public function getNote(){
		return $this->note;
	}

	public function setTotalServicePerform( $string ){
		$this->totalServicePerform = doubleval($string);
	}
	public function getTotalServicePerform(){
		return $this->totalServicePerform;
	}

	public function setBenefit( $string ){
		$this->benefit = (int)$string;
	}
	public function getBenefit(){
		return $this->benefit;
	}

	public function setServicePerform( $string ){
		$this->servicePerform = (int)$string;
	}
	public function getServicePerform(){
		return $this->servicePerform;
	}

	public function setTotalOtAmount( $string ){
		$this->totalOtAmount = doubleval($string);
	}
	public function getTotalOtAmount(){
		return $this->totalOtAmount;
	}

	public function setRegularRate( $string ){
		$this->regularRate = (int)$string;
	}
	public function getRegularRate(){
		return $this->regularRate;
	}

	public function setTotalRegularAmount( $string ){
		$this->totalRegularAmount = doubleval($string);
	}
	public function getTotalRegularAmount(){
		return $this->totalRegularAmount;
	}

	public function setOt( $string ){
		$this->ot = (int)$string;
	}
	public function getOt(){
		return $this->ot;
	}

	public function setStaffId( $string ){
		$this->staffId = (int)$string;
	}
	public function getStaffId(){
		return $this->staffId;
	}

	public function setOtRate( $string ){
		$this->otRate = (int)$string;
	}
	public function getOtRate(){
		return $this->otRate;
	}

	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'staff_id',
				'regular_rate',
				'total_regular_amount',
				'ot',
				'ot_rate',
				'total_ot_amount',
				'service_perform',
				'benefit',
				'total_service_perform',
				'note',
				'other_compensation',
				'spouse_minor',
				'gross_salary',
				'taxable_salary',
				'tax',
				'tax_amount',
				'net_salary',
				'total_salary',
				'staff_advance',
				'final_salary',
				'account_type_id',
				'account_type_name',
				'staff_detail',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				staff_id,
				regular_rate,
				total_regular_amount,
				ot,
				ot_rate,
				total_ot_amount,
				service_perform,
				benefit,
				total_service_perform,
				note,
				other_compensation,
				spouse_minor,
				gross_salary,
				taxable_salary,
				tax,
				tax_amount,
				net_salary,
				total_salary,
				staff_advance,
				final_salary,
				account_type_id,
				account_type_name
			FROM
				pay_roll
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Payroll not found",
				404
			);
		}
		$this->setProperties($this->dbFetchArray($q));

		$this->staffDetail->setFilter('id', $this->getStaffId());
		$this->staffDetail->populate();
	}

	public function update($id){
		if( !$id ) {
			throw new Exception("save method requires id to be set");
		}
		$this->dbQuery("
			UPDATE
				pay_roll
			SET
				name = '" . $this->getName() . "',
				detail = '" . $this->getDetail() . "',
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
				pay_roll
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
				pay_roll
			WHERE
				id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				pay_roll
			(
				staff_id,
				regular_rate,
				total_regular_amount,
				ot,
				ot_rate,
				total_ot_amount,
				service_perform,
				benefit,
				total_service_perform,
				note,
				other_compensation,
				spouse_minor,
				gross_salary,
				taxable_salary,
				tax,
				tax_amount,
				net_salary,
				total_salary,
				staff_advance,
				final_salary,
				account_type_id,
				account_type_name,
				create_by,
				create_date
			)
				VALUES
			(
				'" . $this->getStaffId() . "',
				'" . $this->getRegularRate() . "',
				'" . $this->getTotalRegularAmount() . "',
				'" . $this->getOt() . "',
				'" . $this->getOtRate() . "',
				'" . $this->getTotalOtAmount() . "',
				'" . $this->getServicePerform() . "',
				'" . $this->getBenefit() . "',
				'" . $this->getTotalServicePerform() . "',
				'" . $this->getNote() . "',
				'" . $this->getOtherCompensation() . "',
				'" . $this->getSpouseMinor() . "',
				'" . $this->getGrossSalary() . "',
				'" . $this->getTaxableSalary() . "',
				'" . $this->getTax() . "',
				'" . $this->getTaxAmount() . "',
				'" . $this->getNetSalary() . "',
				'" . $this->getTotalSalary() . "',
				'" . $this->getStaffAdvance() . "',
				'" . $this->getFinalSalary() . "',
				'" . $this->getAccountTypeId() . "',
				'" . $this->getAccountTypeName() . "',
				'" . $this->getCreateBy() . "',
 				NOW()
			)
		");
		$this->setId( $this->dbInsertId() );
	}


}
