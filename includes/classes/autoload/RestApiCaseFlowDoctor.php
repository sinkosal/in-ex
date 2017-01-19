<?php
use
    OSC\CaseFlowDoctor\Collection as  CaseFlowCol
;

class RestApiCaseFlowDoctor extends RestApi{

    public function get($params){
        $col=new CaseFlowCol;
        $col->sortById('DESC');
        $col->sortByStatus(1);
        $params['GET']['doctor_id'] ? $col->filterByDoctorId($params['GET']['doctor_id']) : '';
        $params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
        return $this->getReturn($col,$params);
    }

}
