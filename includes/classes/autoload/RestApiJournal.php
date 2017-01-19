<?php
use
    OSC\AccountTransaction\Collection as  JournalCol,
    OSC\AccountTransaction\Object as  JournalObj,
    OSC\AccountTransactionDetail\Object as  JournalDetailObj
;

class RestApiJournal extends RestApi{

    public function get($params){
        $col=new JournalCol;
        $col->sortById('DESC');
        $params['GET']['vendor_id'] ? $col->filterByVendorId($params['GET']['vendor_id']) : '';
        $params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
        $params['GET']['balance'] ? $col->filterByBalance() : '';
        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
        $showDataPerPage = 10;
        if($params['GET']['pagination']) {
            $start = $params['GET']['start'];
            $this->applyLimit($col,
                array(
                    'limit' => array($start, $showDataPerPage)
                )
            );
        }
        return $this->getReturn($col,$params);
    }

    public function post($params){
        $obj = new JournalObj();
        $obj->setCreateBy($_SESSION['user_name']);
        $obj->setProperties($params['POST']['journal']);
        $obj->insert();
        $transNo = $obj->getTransNo();
        // start insert data into detail
        foreach( $params['POST']['journal_detail'] as $key => $value){
            $objDetail = new JournalDetailObj();
            $objDetail->setTransNo($transNo);
            $objDetail->setTransDate($params['POST']['journal']['trans_date']);
            $objDetail->setProperties($value);
            $objDetail->insert();
            unset($value);
        }
        return array(
            'data' => array(
                'id' => $obj->getId(),
                'success' => 'success'
            )
        );
    }

    public function put($params){
        $obj = new JournalObj();
        $this->setId($this->getId());
        $obj->setProperties($params['PUT']);
        $obj->update($this->getId());
        return array(
            'data' => array(
                'id' => $obj->getId(),
                'success' => 'success'
            )
        );
    }

    public function patch($params){
        $obj = new JournalObj();
        $obj->setId($this->getId());
        $obj->setUpdateBy($_SESSION['user_name']);
        $obj->setStatus($params['PATCH']['status']);
        $obj->updateStatus($params['PATCH']['trans_no']);
    }

    public function delete(){
        $obj = new PurchaseObj();
        $obj->delete($this->getId());
    }

}
