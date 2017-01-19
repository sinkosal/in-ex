<?php
namespace OSC\ReceivePaymentDetail;

use Aedea\Core\Database\StdCollection;


class Collection extends StdCollection{
    public function __Construct($params=array()){
        parent::__construct($params);
        $this->addTable('receive_payment_detail','rpd');
        $this->idField='rpd.id';
        $this->setDistinct(true);
        $this->objectType=__NAMESPACE__.'\Object';
    }

    public function filterByReceivePaymentId( $arg ){
        $this->addWhere("rpd.receive_payment_id = '" . $arg. "' ");
    }

    public function sortById($arg){
        $this->addOrderBy('rpd.id', $arg);
    }

}
