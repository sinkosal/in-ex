<?php

use
	OSC\ProductsType\Collection
		as ProductsTypeCol
	, OSC\ProductsType\Object
		as ProductsTypeObj
;

class RestApiProductType extends RestApi {

	public function get($params){
		$col = new ProductsTypeCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$params['GET']['status'] ? $col->filterByStatus(1) : '';
		if($params['GET']['pagination']){
			$showDataPerPage = 10;
			$start = $params['GET']['start'];
			$this->applyLimit($col,
				array(
					'limit' => array( $start, $showDataPerPage )
				)
			);
		}
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}
	public function post($params){
		$obj = new ProductsTypeObj();
		$obj->setCreateBy($_SESSION['user_name']);
		$obj->setProperties($params['POST']);
		$obj->insert();
		return array(
			'data' => array(
				'id' => $obj->getId(),
				'success' => 'success'
			)
		);
	}

	public function put($params){
		$obj = new ProductsTypeObj();
		$this->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
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
		$obj = new ProductsTypeObj();
		$obj->delete($this->getId());
	}

	public function patch($params){
		$obj = new ProductsTypeObj();
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setId($this->getId());
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}


}
