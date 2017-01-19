<?php

use
	OSC\VendorList\Collection
		as VendorListCol
	, OSC\VendorList\Object
		as VendorListObj
;

class RestApiVendorList extends RestApi {

	public function get($params){
		$col = new VendorListCol();
		// start limit page
		$col->sortById('DESC');
		$col->filterByName($params['GET']['name']);
		$params['GET']['status'] ? $col->filterByStatus($params['GET']['status']) : '';
		$col->filterById($params['GET']['id']);
		if($params['GET']['paginate']){
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
		$obj = new VendorListObj();
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
		$obj = new VendorListObj();
		$obj->setCreateBy($_SESSION['user_name']);
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
		$obj = new VendorListObj();
		$obj->delete($this->getId());
	}

	public function patch($params){
		$obj = new VendorListObj();
		$obj->setId($this->getId());
		$obj->setUpdateBy($_SESSION['user_name']);
		$obj->setStatus($params['PATCH']['status']);
		$obj->updateStatus();
	}

}
