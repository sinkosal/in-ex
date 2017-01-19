<?php
namespace
    OSC\PurchaseDetail;
use Aedea\Core\Database\StdCollection;


class Collection extends StdCollection{
    public function __Construct($params=array()){
        parent::__construct($params);
        $this->addTable('purchase_detail','pd');
        $this->idField='pd.id';
        $this->setDistinct(true);
        $this->objectType=__NAMESPACE__.'\Object';
    }

    public function filterById( $arg ){
        $this->addWhere("pd.id = '" . (int)$arg. "' ");
    }

    public function filterByPurchaseId( $arg ){
        $this->addWhere("pd.purchase_id = '" . (int)$arg. "' ");
    }

}
