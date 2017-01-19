<?php
namespace
    OSC\PurchaseDetail;
use
    Aedea\Core\Database\StdObject as DbObj;

class Object extends DbObj{
     protected
        $purchaseId,
        $productId,
        $productName,
        $qty,
        $priceIn,
        $unitPrice,
        $priceOut,
        $description,
        $total,
        $barcode
     ;
    public function toArray($params=array()){
        $args= array(
            'include'=>array(
                'id',
                'product_id',
                'product_name',
                'barcode',
                'description',
                'qty',
                'unit_price',
                'total'
            )
        );
        return parent::toArray($args);
    }
    public function load( $params = array() ){
        $q = $this->dbQuery("
			SELECT
				product_id,
                purchase_id,
                barcode,
                product_name,
                description,
                qty,
                unit_price,
                total
			FROM
				purchase_detail
			WHERE
				id = '" . (int)$this->getId() . "'
		");

        if( ! $this->dbNumRows($q) ){
            throw new \Exception(
                "404: Purchase Detail not found",
                404
            );
        }
        $this->setProperties($this->dbFetchArray($q));
    }

    public function insert(){
        $this->dbQuery("
			INSERT INTO
				purchase_detail
			(
                product_id,
                product_name,
                description,
                barcode,
                qty,
                unit_price,
                total,
                purchase_id
			)
				VALUES
			(
				'" . $this->getProductId() . "',
				'" . $this->getProductName() . "',
				'" . $this->getDescription() . "',
				'" . $this->getBarcode() . "',
				'" . $this->getQty() . "',
				'" . $this->getUnitPrice() . "',
				'" . $this->getTotal() . "',
				'" . $this->getPurchaseId() . "'
			)
		");
        $this->setId( $this->dbInsertId() );
    }

    public function update(){
        $this->dbQuery("
			UPDATE
				purchase_detail
			SET
                product_id = '" . $this->getProductId() . "',
                product_name = '" . $this->getProductName() . "',
                qty = '" . $this->getQty() . "',
                price_in = '" . $this->getPriceIn() . "',
                total = '" . $this->getTotal() . "',
                price_out = '" . $this->getPriceOut() . "',
			WHERE
				pd_id = '" . (int)$this->getId() . "'
		");
    }

    public function delete()
    {
        if (!$this->getPurchaseId()) {
            throw new Exception("delete method requires id to be set");
        }
        $this->dbQuery("
			DELETE FROM
				purchase_detail
			WHERE
				purchase_id = '" . (int)$this->getPurchaseId() . "'
		");
    }

    public function setBarcode($string){
        $this->barcode = $string;
    }
    public function getBarcode(){
        return $this->barcode;
    }

    public function setProductId($string){
        $this->productId = (int)$string;
    }
    public function getProductId(){
        return $this->productId;
    }

    public function setPurchaseId($string){
        $this->purchaseId = (int)$string;
    }
    public function getPurchaseId(){
        return $this->purchaseId;
    }

    public function setProductName($string){
        $this->productName = $string;
    }
    public function getProductName(){
        return $this->productName;
    }

    public function setPriceIn($string){
        $this->priceIn = $string;
    }
    public function getPriceIn(){
        return $this->priceIn;
    }

    public function setUnitPrice($string){
        $this->unitPrice = $string;
    }
    public function getUnitPrice(){
        return $this->unitPrice;
    }

    public function setDescription($string){
        $this->description = $string;
    }
    public function getDescription(){
        return $this->description;
    }

    public function setQty($string){
        $this->qty = $string;
    }
    public function getQty(){
        return $this->qty;
    }

    public function setPriceOut($string){
        $this->priceOut = $string;
    }
    public function getPriceOut(){
        return $this->priceOut;
    }

    public function setTotal($string){
        $this->total = $string;
    }
    public function getTotal(){
        return $this->total;
    }


}
