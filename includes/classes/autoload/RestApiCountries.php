<?php

use
	OSC\Country\Collection
		as CountryCol
;

class RestApiCountries extends RestApi {

	public function get($params){
		$col = new CountryCol();
		// start limit page
		$this->applyFilters($col, $params);
		$this->applySortBy($col, $params);
		return $this->getReturn($col, $params);
	}


}
