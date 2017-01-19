<?php
//use
//    OSC\BalanceSheet\Collection as  BalanceSheetCol
//;

class RestApiBalanceSheet extends RestApi{

    public function get($params){

        $balanceSheetQuery = tep_db_query("
            select id, name from balance_sheet
        ");
        $array = array();
        $arrayDetail = array();
        $arrayDetailSub = array();

        $sum = array();
        $sumSub = array();
        $sumSubDetail = array();
        $date = $params['GET']['from_date'];
        if($date){
            $date = $date . ' ' . '23:59:59';
            $addWhere = " AND trans_date <= '". $date . "'";
        }
        while ($balance = tep_db_fetch_array($balanceSheetQuery)) {
            $array[] = $balance;

            $balanceSheetQuerySub = tep_db_query("
                select id, name, balance_sheet_id from account_type where balance_sheet_id = ". $balance['id']."
            ");
            while ($balance_sub = tep_db_fetch_array($balanceSheetQuerySub)) {
                $arrayDetail[] = $balance_sub;

                $balanceSheetQuerySubDetail = tep_db_query("
                    select id, name, account_type_id from account_chart where account_type_id = ". $balance_sub['id']."
                ");
                while ($balance_sub_detail = tep_db_fetch_array($balanceSheetQuerySubDetail)) {
                    $arrayDetailSub[] = $balance_sub_detail;


                    //start sum total of sub detail chart account balance sheet
                    //set conditional depend on type of account has balance sheet id
                    $sumTypeAccount = tep_db_query("
                        SELECT account_chart_id,
                            SUM(
                                CASE
                                    WHEN
                                        type_of_account_report = 1 THEN debit - credit
                                      WHEN
                                        type_of_account_report = 2 THEN credit - debit
                                    ELSE
                                        0
                                END
                            ) as total
                        FROM
                            account_transaction_detail
                        WHERE
                            account_chart_id = '" . (int)$balance_sub_detail['id'] . "'
                                AND
                            status = 1 ". $addWhere . "
                    ");
                    $sumSubDetail[] = tep_db_fetch_array($sumTypeAccount);
                }
                //start sum total of detail type of account balance sheet
                //set conditional depend on type of account has balance sheet id
                $sumBalanceDetail = tep_db_query("
                    SELECT account_type_id,
                        SUM(
                            CASE
                                WHEN
                                    type_of_account_report = 1 THEN debit - credit
                                  WHEN
                                    type_of_account_report = 2 THEN credit - debit
                                ELSE
                                    0
                            END
                        )
                        as total
                    FROM
                        account_transaction_detail
                    WHERE
                        account_type_id = '" . (int)$balance_sub['id'] . "'
                            AND
                        status = 1 ". $addWhere ."
                ");
                $sumSub[] = tep_db_fetch_array($sumBalanceDetail);

            }

            //start sum total of main balance sheet
            $sumMain = tep_db_query("
                SELECT type_of_account_report,
                    SUM(
                        CASE
                            WHEN
                                type_of_account_report = 1 THEN debit - credit
                              WHEN
                                type_of_account_report = 2 THEN credit - debit
                            ELSE
                                0
                        END
                    ) as total
                FROM
                    account_transaction_detail
                WHERE
                    type_of_account_report = '" . (int)$balance['id'] . "'
                        AND
                    status = 1 ". $addWhere . "
            ");
            $sum[] = tep_db_fetch_array($sumMain);

        }


        return array(data => array(
            'master' => $array,
            'detail' => $arrayDetail,
            'detail_sub' => $arrayDetailSub,
            'sum_master' => $sum,
            'sum_detail' => $sumSub,
            'sum_detail_sub' => $sumSubDetail
        ));


//        $col=new BalanceSheetCol;
//        $col->setOrderById('ASC');
//        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date']) : '';

//        $col->populate();
//        if($col->getTotalCount() > 0){
//            foreach ($col->getFirstElement() as $balance) {
//                $from_date = $params['GET']['from_date'] .' ' . '23:59:59';
////                $balance->load(array('from_date' => $from_date));
//
//            }
//            return $this->getReturn($col,$params);
//        }
    }

}
