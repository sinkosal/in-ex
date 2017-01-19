<?php
namespace
    OSC\PurchaseMaster;
use Aedea\Core\Database\StdCollection;


class Collection extends StdCollection{
    public function __Construct($params=array()){
        parent::__construct($params);
        $this->addTable('purchase_master','pm');
        $this->idField='pm.id';
        $this->setDistinct(true);
        $this->objectType=__NAMESPACE__.'\Object';
    }

    public function filterBySupplierName( $arg ){
        $this->addWhere("pm.supplier_name LIKE '%" . $arg. "%' ");
    }

    public function filterByReferenceNO( $arg ){
        $this->addWhere("pm.reff_no LIKE '%" . $arg. "%' ");
    }

    public function filterById( $arg ){
        $this->addWhere("pm.id = '" . (int)$arg. "' ");
    }

    public function filterByVendorId( $arg ){
        $this->addWhere("pm.supplier_id = '" . (int)$arg. "' ");
    }

    public function filterByBalance(){
        $this->addWhere("pm.remain > 0 ");
    }

    public function filterByStatus($arg){
        $this->addWhere("pm.status = '" . (int)$arg ."' ");
    }

    public function filterByDate($from, $to){
        $this->addWhere("pm.purchase_date BETWEEN '" . $from . "' AND '" . $to . "' ");
    }

    public function sortById($arg){
        $this->addOrderBy('pm.id', $arg);
    }

}
