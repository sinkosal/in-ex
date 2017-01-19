<?php
//use
//    OSC\AccountTypeIncomeStatement\Collection as  IncomeStatementCol
//;

class RestApiIncomeStatement extends RestApi{

    public function get($params){

        $incomeQuery = tep_db_query("
            select id, name from account_type where income_statement = 1
        ");

        $array = array();
        $arrayDetail = array();
        $sum = array();
        $sumDetail = array();
        $from = $params['GET']['from_date'];
        $to = $params['GET']['to_date'];
        if($from && $to){
            $addWhere = " AND trans_date BETWEEN '" . $from . "' AND '" . $to . "'";
        }
        while ($income = tep_db_fetch_array($incomeQuery)) {
            $array[] = $income;

            $incomeQueryDetail = tep_db_query("
                select id, name, account_type_id from account_chart where account_type_id = ". $income['id']."
            ");
            while ($income_detail = tep_db_fetch_array($incomeQueryDetail)) {
                $arrayDetail[] = $income_detail;

                //set conditional depend on type of account has income statement
                // 3 for revenues and 4 for expense 5 for cost of sales
                $sumSub = tep_db_query("
                    SELECT account_chart_id,
                        SUM(
                            CASE
                                WHEN
                                    type_of_account_report = 4 THEN debit - credit
                                WHEN
                                    type_of_account_report = 3 THEN credit - debit
                                WHEN
                                    type_of_account_report = 5 THEN debit - credit
                                ELSE
                                    0
                            END
                        ) as total
                    FROM
                        account_transaction_detail
                    WHERE
                        account_chart_id = '" . (int)$income_detail['id'] . "'
                            AND
                        status = 1 " . $addWhere . "
                ");
                $sumDetail[] = tep_db_fetch_array($sumSub);
            }
            //set conditional depend on type of account has income statement
            // 3 for revenues and 4 for expense 5 for cost of sales
            $sumMain = tep_db_query("
                SELECT account_type_id,
                    SUM(
                        CASE
                            WHEN
                                type_of_account_report = 4 THEN debit - credit
                            WHEN
                                type_of_account_report = 3 THEN credit - debit
                            WHEN
                                type_of_account_report = 5 THEN debit - credit
                            ELSE
                                0
                        END
                    )
                    as total
                FROM
                    account_transaction_detail
                WHERE
                    account_type_id = '" . (int)$income['id'] . "'
                        AND
                    status = 1 " . $addWhere . "
            ");
            $sum[] = tep_db_fetch_array($sumMain);
        }

        return array(data => array(
            'master' => $array,
            'master_detail' => $arrayDetail,
            'total_master' => $sum,
            'total_master_detail' => $sumDetail,
        ));

//        $col=new IncomeStatementCol;
//        $col->filterByIncomeStatementId();
//        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date']) : '';
//        return $this->getReturn($col,$params);
    }

}
