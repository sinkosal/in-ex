app.controller(
	'chart_account_ctrl', [
	'$scope'
	, 'Restful'
	, 'Services'
	, function ($scope, Restful, Services){
		'use strict';
		$scope.service = new Services();
		var url = 'api/ChartAccount/';
		$scope.init = function(params){
			Restful.get(url, params).success(function(data){
				$scope.chartAccount = data;
				$scope.totalItems = data.count;
			});
			Restful.get('api/AccountType', {status: 'yes'}).success(function(data){
				$scope.accountType = data;
			});
		};
		var params = {paginate:'yes'};
		$scope.init(params);
		$scope.updateStatus = function(params){
			params.status === 1 ? params.status = 0 : params.status = 1;
			Restful.patch(url + params.id, params ).success(function(data) {console.log(data);
				$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
			});
		};

		$scope.search = function(id){
			params.name = $scope.name;
			params.account_type_id = $scope.type_id;
			params.id = $scope.id;
			$scope.init(params);
		};
		/**
		 * start functionality pagination
		 */
		$scope.currentPage = 1;
		//get another portions of data on page changed
		$scope.pageChanged = function() {
			$scope.pageSize = 10 * ( $scope.currentPage - 1 );
			params.start = $scope.pageSize;
			$scope.init(params);
		};

		$scope.edit = function(params){
			$scope.params = angular.copy(params);
			$scope.name = $scope.params.name;
			$scope.account_code = $scope.params.account_code;
			$scope.account_type_id = $scope.params.detail[0].id	;
			$scope.min_value = $scope.params.detail[0].min_values;
			$scope.max_value = $scope.params.detail[0].max_values;
			$scope.id = $scope.params.id;
			$('#account-chart-popup').modal('show');
		};
		$scope.disable = true;
		$scope.message = true;
		$scope.save = function(){
			if($scope.account_code < $scope.min_value ){
				return $scope.message = false;
			}
			if( $scope.account_code > $scope.max_value){
				return $scope.message = false;
			}
			var data = {name: $scope.name, account_code: $scope.account_code, account_type_id: $scope.account_type_id};
			$scope.disable = false;
			if($scope.id) {
				Restful.put( url + $scope.id, data).success(function (data) {
					$scope.init();
					$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
					$('#account-chart-popup').modal('hide');
					$scope.clear();
					$scope.disable = true;
				});
			}else {
				Restful.save( url , data).success(function (data) {
					$scope.init();
					$('#account-chart-popup').modal('hide');
					$scope.clear();
					$scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
					$scope.disable = true;
				});
			}
		};

		$scope.min_value = '';
		$scope.max_value = '';
		$scope.checkValue = function(params){
			Restful.get('api/AccountType', {id: params, status: 'yes'}).success(function(data){
				$scope.min_value = data.elements[0].min_values;
				$scope.max_value = data.elements[0].max_values;
			});
		};
		$scope.clear = function(){
			$scope.disable = true;
			$scope.name = '';
			$scope.message = true;
			$scope.account_code = '';
			$scope.account_type_id = '';
			$scope.id = '';
			$scope.min_value = '';
			$scope.max_value = '';
		};
	}
]);