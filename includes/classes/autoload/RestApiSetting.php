<?php

use
	OSC\Setting\Collection
		as SettingCol
	, OSC\Setting\Object
		as SettingObj
;

class RestApiSetting extends RestApi {

	public function get($params){
		$col = new SettingCol();
		return $this->getReturn($col, $params);
	}

	public function put($params){
		$obj = new SettingObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setProperties($params['PUT']);
		$obj->update();
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

}
