<?php

use
	OSC\Administrator\Collection
		as UserCol
	, OSC\Administrator\Object
	as UserObj
;

class RestApiUser extends RestApi {

	public function get($params){
		$col = new UserCol();
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

	public function post($params){
		$col = new UserCol();
		$col->filterByUserName($params['POST']['user_name']);
		if( $col->getTotalCount() < 1){
			$col->populate();
			$co = $col->getFirstElement();
			$password = $params['POST']['user_password'];
			if( isset( $password )){
				$params['POST']['user_password'] = tep_encrypt_password($password);
				$obj = new UserObj();
				$obj->setCreateBy($_SESSION['user_name']);
				$obj->setProperties($params['POST']);
				$obj->insert();
			}
			return array(
				'data' => array(
					'id' => $obj->getId(),
					'success' => 'success'
				)
			);
		}else{
			return array(
				'data' => array(
					'duplicate' => 'duplicate'
				)
			);
		}
	}

	public function put($params){
		$obj = new UserObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$params['PUT']['user_password'] = tep_encrypt_password($params['PUT']['user_password']);
		$obj->setProperties($params['PUT']);
		$obj->update();
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function patch($params){
		$obj = new UserObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

}
