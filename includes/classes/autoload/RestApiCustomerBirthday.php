<?php

use
	OSC\CustomerList\Collection
		as CustomerListCol
;

class RestApiCustomerBirthday extends RestApi {

	public function get($params)
	{
		$col = new CustomerListCol();
		$col->sortById('DESC');
		$col->filterByStatus(1);
		$col->filterByBirthday();
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}

}
