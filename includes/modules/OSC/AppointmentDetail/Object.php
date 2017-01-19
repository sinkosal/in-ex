<?php

namespace OSC\AppointmentDetail;

use
	Aedea\Core\Database\StdObject as DbObj
;

class Object extends DbObj {
		
	protected
		$appointmentId,
		$date,
		$note
	;
	
	public function toArray( $params = array() ){
		$args = array(
			'include' => array(
				'id',
				'appointment_id',
				'date',
				'note',
			)
		);

		return parent::toArray($args);
	}
	
	public function load( $params = array() ){
		$q = $this->dbQuery("
			SELECT
				appointment_id,
				date,
				note
			FROM
				appointment_detail
			WHERE
				id = '" . (int)$this->getId() . "'	
		");
		
		if( ! $this->dbNumRows($q) ){
			throw new \Exception(
				"404: Appointment Detail not found",
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
				appointment_detail
			SET
				date = '" . $this->getDate() . "',
				note = '" . $this->getNote() . "'
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
				appointment_detail
			WHERE
				appointment_id = '" . (int)$id . "'
		");
	}

	public function insert(){
		$this->dbQuery("
			INSERT INTO
				appointment_detail
			(
				appointment_id,
				date,
				note
			)
				VALUES
			(
				'" . $this->getAppointmentId() . "',
				'" . $this->getDate() . "',
				'" . $this->getNote() . "'
			)
		");
		$this->setId( $this->dbInsertId() );
	}

	public function setDate( $string ){
		if($string != '0000-00-00 00:00:00'){
			$string = date('Y-m-d', strtotime( $string ));
		}
		$this->date = $string;
	}
	public function getDate(){
		return $this->date;
	}

	public function setNote( $string ){
		$this->note = $string;
	}
	public function getNote(){
		return $this->note;
	}

	public function setAppointmentId( $string ){
		$this->appointmentId = $string;
	}
	public function getAppointmentId(){
		return $this->appointmentId;
	}

}
