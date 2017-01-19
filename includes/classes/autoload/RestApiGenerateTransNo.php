<?php

use
    OSC\AccountTransaction\Collection as AccountTransCol
;
class RestApiGenerateTransNo extends RestApi
{

    public function get($params)
    {
        $col = new AccountTransCol();
        $col->sortById('DESC');

        if($params['GET']['trans_date']){
            $date = $params['GET']['trans_date'];
            $transDate = date('dmY', strtotime($date));
        } else{
            $date = date('Y-m-d', strtotime(date("Y-m-d H:i:s")));
            $transDate = date('dmY', strtotime(date("Y-m-d H:i:s")));
        }

//        $date = date('Y-m-d', strtotime(date("Y-m-d H:i:s")));
        // filter by current date
        $col->filterByTransDate($date);
        $count = $col->getTotalCount();
        // count number of sale today how many transaction
        $count < 0 ? $count = 1 : $count = $count + 1;
        // set invoice number
//        $invoiceDate = date('dmY', strtotime(date("Y-m-d H:i:s")));
        $invoice = $transDate . '-00' . $count;
        return array('data' => array('trans_no' => $invoice));
    }
}