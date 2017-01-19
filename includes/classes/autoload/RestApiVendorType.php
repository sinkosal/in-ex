<?php

use
	OSC\VendorType\Collection
		as VendorTypeCol
	, OSC\VendorType\Object
		as VendorTypeObj
;

class RestApiVendorType extends RestApi {

	public function get($params){
		$col = new VendorTypeCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$params['GET']['status'] ? $col->filterByStatus($params['GET']['status']) : '';
		$params['GET']['id'] ? $col->filterById($params['GET']['id']) : '';
		$showDataPerPage = 10;
		$start = $params['GET']['start'];
		$this->applyLimit($col,
			array(
				'limit' => array( $start, $showDataPerPage )
			)
		);
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

	public function post($params){
		$obj = new VendorTypeObj();
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
		$obj = new VendorTypeObj();
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
		$obj = new VendorTypeObj();
		$obj->delete($this->getId());
	}

	public function patch($params){
		$obj = new VendorTypeObj();
		$obj->setId($this->getId());
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

}
