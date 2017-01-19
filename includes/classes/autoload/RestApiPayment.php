<?php
use
    OSC\PaymentMaster\Collection as  PaymentCol,
    OSC\PaymentMaster\Object as  PaymentObj,
    OSC\PaymentMasterDetail\Object as  PaymentDetailObj,
    OSC\PurchaseMaster\Object as  PurchaseObj
;

class RestApiPayment extends RestApi{

    public function get($params){
        $col=new PaymentCol;
        $col->sortById('DESC');
        $params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
        $params['GET']['vendor_id'] ? $col->filterByVendorId($params['GET']['vendor_id']) : '';
        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
//        $showDataPerPage = 20;
//        $start = $params['GET']['start'];
//        $this->applyLimit($col,
//            array(
//                'limit' => array( $start, $showDataPerPage )
//            )
//        );
        return $this->getReturn($col,$params);
    }

    public function post($params){
        $obj = new PaymentObj();
        $obj->setCreateBy($_SESSION['user_name']);
        $obj->setProperties($params['POST']['payment'][0]);
        $vendorPaymentNo = $params['POST']['payment'][0]['reference_no'];
        $obj->insert();
        $paymentId = $obj->getId();
        // start insert data into detail
        foreach( $params['POST']['payment_detail'] as $key => $value){
            $objDetail = new PaymentDetailObj();
            if($value['payment_next'] > 0) {
                $objDetail->setPaymentId($paymentId);
                $objDetail->setPaymentReferenceNo($vendorPaymentNo);
                $objDetail->setInvoiceAmount($value['total']);
                $balance = doubleval($value['remain'] - $value['payment_next']);
                $payment = doubleval($value['payment_next'] + $value['payment']);
                if( $payment >= $value['total'] ){
                    $payment = $value['total'];
                }
                $objDetail->setBalance($balance);
                $objDetail->setPayAmount($payment);
                $objDetail->insert();

                // update balance
                $objPurchase = new PurchaseObj();
                $objPurchase->setId($value['id']);
                $objPurchase->setPayment($value['payment_next']);
                $objPurchase->setRemain($balance);
                $objPurchase->setUpdateBy($_SESSION['user_name']);
                $objPurchase->update();
            }
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
        $obj = new PurchaseObj();
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

    public function delete(){
        $obj = new PurchaseObj();
        $obj->delete($this->getId());
    }

}
