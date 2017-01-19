<?php
namespace OSC\ReceivePayment;

use Aedea\Core\Database\StdCollection;


class Collection extends StdCollection{
    public function __Construct($params=array()){
        parent::__construct($params);
        $this->addTable('receive_payment','rp');
        $this->idField='rp.id';
        $this->setDistinct(true);
        $this->objectType=__NAMESPACE__.'\Object';
    }

    public function filterByReceiveNo( $arg ){
//        $this->addWhere("rp.receive_payment_no = '" . $arg. "' ");
    }

    public function filterByCustomerId( $arg ){
        $this->addWhere("rp.customer_id = '" . (int)$arg. "' ");
    }

    public function filterByBalance(){
        $this->addWhere("rp.total_last_balance > 0 ");
    }

    public function filterByDate($from, $to){
        $this->addWhere("rp.receive_payment_date BETWEEN '" . $from . "' AND '" . $to . "' ");
    }

    public function sortById($arg){
        $this->addOrderBy('rp.id', $arg);
    }

}
