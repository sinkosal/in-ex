<?php

use
	OSC\Products\Collection
		as ProductCol
	, OSC\Products\Object
		as ProductObj
;

class RestApiProducts extends RestApi {

	public function get($params){
		$col = new ProductCol();
		$id = $params['GET']['id'];
		$col->sortByDate('DESC');
		$col->filterById($id);
		if( $params['GET']['status'] == 1 ){
			$col->filterByStatus(1);
		}
		if( $params['GET']['status'] == 0 && $params['GET']['status'] != ''){
			$col->filterByStatus(0);
		}
		$col->filterByName($params['GET']['name']);
		$params['GET']['type'] ? $col->filterByType($params['GET']['type']) : '';
		// start limit page
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
		$productObject = new ProductObj();
		$productObject->setCreateBy($_SESSION['user_name']);
		$productObject->setProperties($params['POST']);
		$productObject->insert();
		return array(
			'data' => array(
				'id' => $productObject->getId()
			)
		);
	}

	public function put($params){
		$obj = new ProductObj();
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setProperties($params['PUT']);
		$obj->update($this->getId());
	}

	public function delete(){
		$obj = new ProductObj();
		$obj->delete($this->getId());
		return array(
			'data' => array(
				'data' => 'success'
			)
		);
	}

	public function patch($params){
		$obj = new ProductObj();
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus($this->getId());
	}

}

