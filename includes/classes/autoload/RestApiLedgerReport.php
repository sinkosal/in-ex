<?php
use
    OSC\AccountTransactionLedger\Collection as  ledgerCol
;

class RestApiLedgerReport extends RestApi{

    public function get($params){
        $col = new ledgerCol;
        $col->orderBy('DESC');
        $col->filterByStatus(1);
        $params['GET']['account_id'] ? $col->filterByAccountId($params['GET']['account_id']) : '';
        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
        $params['GET']['vendor_id'] ? $col->filterByVendorId($params['GET']['vendor_id']) : '';
        return $this->getReturn($col,$params);
    }

}
