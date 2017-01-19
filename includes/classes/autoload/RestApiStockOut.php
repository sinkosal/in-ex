<?php

use
	OSC\StockOut\Collection
		as StockOutCol
	, OSC\StockOut\Object
		as StockOutObj
	, OSC\StockOutDetail\Object
		as StockOutDetailObj
	, OSC\Products\Object
		as productObj
;

class RestApiStockOut extends RestApi {

	public function get($params){
		$col = new StockOutCol();
		$col->sortById('DESC');
		$params['GET']['from_date'] ? $col->filterByDate($params['GET']['from_date'], $params['GET']['to_date']) : '';
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

	public function post($params){
		$obj = new StockOutObj();
		$obj->setCreateBy($_SESSION['user_name']);
		$obj->setProperties($params['POST']['stock'][0]);
		$obj->insert();
		$stockId = $obj->getId();
		foreach( $params['POST']['stock_detail'] as $key => $value){
			$objStockOut = new StockOutDetailObj();
			$objStockOut->setStockOutId($stockId);
			$objStockOut->setProperties($value);
			$objStockOut->insert();
			$objpro = new productObj();
			$objpro->setProductsQuantity($value['qty']);
			$objpro->updateStock($value['product_id']);
			unset($value);
		}
		return array( data => array(
			id=> $stockId
		));

	}

}
