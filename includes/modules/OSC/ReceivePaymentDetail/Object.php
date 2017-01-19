<?php
namespace OSC\ReceivePaymentDetail;
use
    Aedea\Core\Database\StdObject as DbObj,
    OSC\ReceivePaymentDetail\Collection as ReceivePaymentDetailCol
;

class Object extends DbObj{
     protected
        $receivePaymentId,
        $invoiceNo,
        $totalAmount,
        $depositBefore,
        $lastDeposit,
        $balanceBefore,
        $lastBalance
     ;

    public function __construct( $params = array() ){
        parent::__construct($params);
        $this->detail = new ReceivePaymentDetailCol();
    }

    public function toArray($params=array()){
        $args= array(
            'include'=>array(
                'id',
                'receive_payment_id',
                'invoice_no',
                'total_amount',
                'deposit_before',
                'last_deposit',
                'balance_before',
                'last_balance',
            )
        );
        return parent::toArray($args);
    }
    public function load( $params = array() ){
        $q = $this->dbQuery("
			SELECT
                receive_payment_id,
                invoice_no,
                total_amount,
                deposit_before,
                last_deposit,
                balance_before,
                last_balance
			FROM
				receive_payment_detail
			WHERE
				id = '" . (int)$this->getId() . "'
		");

        if( ! $this->dbNumRows($q) ){
            throw new \Exception(
                "404: Receive Payment Detail not found",
                404
            );
        }
        $this->setProperties($this->dbFetchArray($q));
    }

    public function insert(){
        $this->dbQuery("
			INSERT INTO
				receive_payment_detail
			(
				receive_payment_id,
                invoice_no,
                total_amount,
                deposit_before,
                last_deposit,
                balance_before,
                last_balance,
                create_date
			)
				VALUES
			(
			    '" . $this->getReceivePaymentId() . "',
				'" . $this->getInvoiceNo() . "',
				'" . $this->getTotalAmount() . "',
				'" . $this->getDepositBefore() . "',
				'" . $this->getLastDeposit() . "',
				'" . $this->getBalanceBefore() . "',
				'" . $this->getLastBalance() . "',
				NOW()
			)
		");
        $this->setId( $this->dbInsertId() );
    }

    public function update(){
        $this->dbQuery("
			UPDATE
				receive_payment_detail
			SET
                update_by = '" . $this->getUpdateBy() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
    }

    public function delete(){
        if (!$this->getId()) {
            throw new Exception("delete method requires id to be set");
        }
        $this->dbQuery("
			DELETE FROM
				receive_payment_detail
			WHERE
				id = '" . (int)$this->getId() . "'
		");
    }

    public function setInvoiceNo($string){
        $this->invoiceNo = $string;
    }
    public function getInvoiceNo(){
        return $this->invoiceNo;
    }

    public function setTotalAmount($string){
        $this->totalAmount = $string;
    }
    public function getTotalAmount(){
        return $this->totalAmount;
    }

    public function setReceivePaymentId($string){
        $this->receivePaymentId = $string;
    }
    public function getReceivePaymentId(){
        return $this->receivePaymentId;
    }

    public function setDepositBefore($string){
        $this->depositBefore = doubleval($string);
    }
    public function getDepositBefore(){
        return $this->depositBefore;
    }

    public function setLastDeposit($string){
        $this->lastDeposit = doubleval($string);
    }
    public function getLastDeposit(){
        return $this->lastDeposit;
    }

    public function setBalanceBefore($string){
        $this->balanceBefore = doubleval($string);
    }
    public function getBalanceBefore(){
        return $this->balanceBefore;
    }

    public function setLastBalance($string){
        $this->lastBalance = doubleval( $string );
    }
    public function getLastBalance(){
        return $this->lastBalance;
    }

}
