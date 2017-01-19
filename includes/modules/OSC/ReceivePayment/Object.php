<?php
namespace OSC\ReceivePayment;
use
    Aedea\Core\Database\StdObject as DbObj,
    OSC\ReceivePaymentDetail\Collection as ReceivePaymentDetailCol
;

class Object extends DbObj{
     protected
        $customerName,
        $customerId,
        $receivePaymentNo,
        $receivePaymentDate,
        $note,
        $totalBalance,
        $discountType,
        $discountAmount,
        $totalDiscountAmount,
        $grandTotal,
        $paymentMethod,
        $totalPaymentAmount,
        $totalLastBalance,
        $bankCharge,
        $detail
     ;

    public function __construct( $params = array() ){
        parent::__construct($params);
        $this->detail = new ReceivePaymentDetailCol();
    }

    public function toArray($params=array()){
        $args= array(
            'include'=>array(
                'id',
                'customer_id',
                'customer_name',
                'bank_charge',
                'receive_payment_date',
                'receive_payment_no',
                'note',
                'total_balance',
                'discount_type',
                'discount_amount',
                'total_discount_amount',
                'payment_method',
                'total_payment_amount',
                'total_last_balance',
                'detail',
            )
        );
        return parent::toArray($args);
    }
    public function load( $params = array() ){
        $q = $this->dbQuery("
			SELECT
                customer_id,
                customer_name,
                receive_payment_date,
                receive_payment_no,
                note,
                total_balance,
                discount_type,
                discount_amount,
                total_discount_amount,
                payment_method,
                total_payment_amount,
                total_last_balance,
                bank_charge
			FROM
				receive_payment
			WHERE
				id = '" . (int)$this->getId() . "'
		");

        if( ! $this->dbNumRows($q) ){
            throw new \Exception(
                "404: Receive Payment not found",
                404
            );
        }
        $this->setProperties($this->dbFetchArray($q));

        $this->detail->setFilter('receive_payment_id', $this->getId());
        $this->detail->populate();
    }

    public function insert(){
        $this->dbQuery("
			INSERT INTO
				receive_payment
			(
				customer_id,
                customer_name,
                receive_payment_date,
                receive_payment_no,
                note,
                total_balance,
                discount_type,
                discount_amount,
                total_discount_amount,
                payment_method,
                total_payment_amount,
                total_last_balance,
                bank_charge,
                create_by,
                create_date
			)
				VALUES
			(
			    '" . $this->getCustomerId() . "',
				'" . $this->getCustomerName() . "',
				'" . $this->getReceivePaymentDate() . "',
				'" . $this->getReceivePaymentNo() . "',
				'" . $this->getNote() . "',
				'" . $this->getTotalBalance() . "',
				'" . $this->getDiscountType() . "',
				'" . $this->getDiscountAmount() . "',
				'" . $this->getTotalDiscountAmount() . "',
				'" . $this->getPaymentMethod() . "',
				'" . $this->getTotalPaymentAmount() . "',
				'" . $this->getTotalLastBalance() . "',
				'" . $this->getBankCharge() . "',
				'" . $this->getCreateBy() . "',
				NOW()
			)
		");
        $this->setId( $this->dbInsertId() );
    }

    public function update(){
        $this->dbQuery("
			UPDATE
				purchase_master
			SET
                remain = '" . $this->getRemain() . "',
                payment = payment + '" . $this->getPayment() . "',
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
				purchase_master
			WHERE
				id = '" . (int)$this->getId() . "'
		");
    }

    public function setBankCharge( $string ){
        $this->bankCharge = doubleval($string);
    }
    public function getBankCharge(){
        return $this->bankCharge;
    }

    public function setCustomerId($string){
        $this->customerId = (int)$string;
    }
    public function getCustomerId(){
        return $this->customerId;
    }

    public function setDetail($string){
        $this->detail = $string;
    }
    public function getDetail(){
        return $this->detail;
    }

    public function setReceivePaymentDate($date){
        $this->receivePaymentDate = date('Y-m-d', strtotime( $date ));
    }
    public function getReceivePaymentDate(){
        return $this->receivePaymentDate;
    }

    public function setCustomerName($string){
        $this->customerName = $string;
    }
    public function getCustomerName(){
        return $this->customerName;
    }

    public function setReceivePaymentNo($string){
        $this->receivePaymentNo = $string;
    }
    public function getReceivePaymentNo(){
        return $this->receivePaymentNo;
    }

    public function setNote($string){
        $this->note = $string;
    }
    public function getNote(){
        return $this->note;
    }

    public function setTotalBalance($string){
        $this->totalBalance = doubleval($string);
    }
    public function getTotalBalance(){
        return $this->totalBalance;
    }

    public function setPaymentMethod($string){
        $this->paymentMethod = $string;
    }
    public function getPaymentMethod(){
        return $this->paymentMethod;
    }

    public function setTotalLastBalance($string){
        if( $string < 0){
            $string= 0;
        }
        $this->totalLastBalance = doubleval($string);
    }
    public function getTotalLastBalance(){
        return $this->totalLastBalance;
    }

    public function setTotalPaymentAmount($string){
        $this->totalPaymentAmount = doubleval( $string );
    }
    public function getTotalPaymentAmount(){
        return $this->totalPaymentAmount;
    }

    public function setDiscountType($string){
        $this->discountType = $string;
    }
    public function getDiscountType(){
        return $this->discountType;
    }

    public function setDiscountAmount($string){
        $this->discountAmount = doubleval( $string );
    }
    public function getDiscountAmount(){
        return $this->discountAmount;
    }

    public function setTotalDiscountAmount($string){
        $this->totalDiscountAmount = doubleval( $string );
    }
    public function getTotalDiscountAmount(){
        return $this->totalDiscountAmount;
    }

    public function setGrandTotal($string){
        $this->grandTotal = doubleval( $string );
    }
    public function getGrandTotal(){
        return $this->grandTotal;
    }
}
