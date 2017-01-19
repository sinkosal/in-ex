<?php
namespace
    OSC\PurchaseMaster;
use
    Aedea\Core\Database\StdObject as DbObj,
    OSC\PurchaseDetail\Collection as PurchaseDetailCol
;

class Object extends DbObj{
     protected
        $supplierName,
        $supplierId,
        $purchaseDate,
        $reffNo,
        $paymentNext,
        $note,
        $total,
        $payment,
        $remain,
        $detail
     ;

    public function __construct( $params = array() ){
        parent::__construct($params);
        $this->detail = new PurchaseDetailCol();
    }

    public function toArray($params=array()){
        $args= array(
            'include'=>array(
                'id',
                'supplier_id',
                'supplier_name',
                'status',
                'purchase_date',
                'reff_no',
                'note',
                'total',
                'payment',
                'payment_next',
                'detail',
                'remain'
            )
        );
        return parent::toArray($args);
    }
    public function load( $params = array() ){
        $q = $this->dbQuery("
			SELECT
                supplier_id,
                supplier_name,
                purchase_date,
                reff_no,
                note,
                total,
                payment,
                payment_next,
                status,
                remain
			FROM
				purchase_master
			WHERE
				id = '" . (int)$this->getId() . "'
		");

        if( ! $this->dbNumRows($q) ){
            throw new \Exception(
                "404: Purchase Master not found",
                404
            );
        }
        $this->setProperties($this->dbFetchArray($q));
        $this->detail->setFilter('purchase_id', $this->getId());
        $this->detail->populate();
    }

    public function insert(){
        $this->dbQuery("
			INSERT INTO
				purchase_master
			(
				supplier_id,
                supplier_name,
                note,
                create_by,
                reff_no,
                total,
                payment,
                purchase_date,
                remain,
                create_date,
                status
			)
				VALUES
			(
			    '" . $this->getSupplierId() . "',
				'" . $this->getSupplierName() . "',
				'" . $this->getNote() . "',
				'" . $this->getCreateBy() . "',
				'" . $this->getReffNo() . "',
				'" . $this->getTotal() . "',
				'" . $this->getPayment() . "',
				'" . $this->getPurchaseDate() . "',
				'" . $this->getRemain() . "',
				NOW(),
				1
			)
		");
        $this->setId( $this->dbInsertId() );
    }

    public function update(){
        // update payment if need
        //payment = payment + '" . $this->getPayment() . "',
        $this->dbQuery("
			UPDATE
				purchase_master
			SET
                remain = '" . $this->getRemain() . "',
                update_by = '" . $this->getUpdateBy() . "',
                payment_next = payment_next + '" . $this->getPayment() . "'
			WHERE
				id = '" . (int)$this->getId() . "'
		");
    }

    public function updateStatus(){
        $this->dbQuery("
			UPDATE
				purchase_master
			SET
                status = '" . (int)$this->getStatus() . "',
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

    public function setSupplierId($string){
        $this->supplierId =(int)$string;
    }
    public function getSupplierId(){
        return $this->supplierId;
    }

    public function setDetail($string){
        $this->detail =(int)$string;
    }
    public function getDetail(){
        return $this->detail;
    }

    public function setPurchaseDate($date){
        $this->purchaseDate = date('Y-m-d', strtotime( $date ));
    }
    public function getPurchaseDate(){
        return $this->purchaseDate;
    }

    public function setSupplierName($string){
        $this->supplierName = $string;
    }
    public function getSupplierName(){
        return $this->supplierName;
    }

    public function setReffNo($string){
        $this->reffNo = $string;
    }
    public function getReffNo(){
        return $this->reffNo;
    }

    public function setNote($string){
        $this->note = $string;
    }
    public function getNote(){
        return $this->note;
    }

    public function setTotal($string){
        $this->total = doubleval($string);
    }
    public function getTotal(){
        return $this->total;
    }

    public function setPayment($string){
        $this->payment = doubleval($string);
    }
    public function getPayment(){
        return $this->payment;
    }

    public function setPaymentNext($string){
        $this->paymentNext = doubleval($string);
    }
    public function getPaymentNext(){
        return $this->paymentNext;
    }

    public function setRemain($string){
        if( $string < 0){
            $string= 0;
        }
        $this->remain = doubleval( $string );
    }
    public function getRemain(){
        return $this->remain;
    }

}
