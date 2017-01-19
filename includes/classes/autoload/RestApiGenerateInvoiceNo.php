<?php

use
    OSC\Invoice\Collection as InvoiceCol
;
class RestApiGenerateInvoiceNo extends RestApi
{

    public function get($params)
    {
        $col = new InvoiceCol();
        // start limit page
        $col->sortById('DESC');
        if($params['GET']['invoice_date']){
            $date = $params['GET']['invoice_date'];
            $invoiceDate = date('dmY', strtotime($date));
        } else{
            $date = date('Y-m-d', strtotime(date("Y-m-d H:i:s")));
            $invoiceDate = date('dmY', strtotime(date("Y-m-d H:i:s")));
        }

        // filter by current date
        $col->filterByInvoiceDate($date);
        $count = $col->getTotalCount();
        // count number of sale today how many transaction
        $count < 0 ? $count = 1 : $count = $count + 1;
        // set invoice number

        $invoice = 'CT-' . $invoiceDate . '-00' . $count;
        return array('data' => array('invoice_no' => $invoice));
    }
}