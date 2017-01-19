app.controller(
	'account_type_ctrl', [
	'$scope'
	, 'Restful'
	, 'Services'
	, function ($scope, Restful, Services){
		'use strict';
		$scope.service = new Services();
		var url = 'api/AccountType/';
		$scope.init = function(params){
			Restful.get(url, params).success(function(data){
				$scope.accountType = data;console.log(data);
				$scope.totalItems = data.count;
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
			$scope.min_values = $scope.params.min_values;
			$scope.max_values = $scope.params.max_values;
			$scope.drcr = $scope.params.drcr;
			$scope.id = $scope.params.id;
			$('#account-type-popup').modal('show');
		};
		$scope.disable = true;
		$scope.save = function(){
			var data = {name: $scope.name, min_values: $scope.min_values, max_values: $scope.max_values, drcr: $scope.drcr};
			$scope.disable = false;
			if($scope.id) {console.log($scope.id);
				Restful.put( url + $scope.id, data).success(function (data) {
					$scope.init();console.log(data);
					$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
					$('#account-type-popup').modal('hide');
					$scope.clear();
					$scope.disable = true;
				});
			}else {
				Restful.save( url , data).success(function (data) {
					$scope.init();console.log(data);
					$('#account-type-popup').modal('hide');
					$scope.clear();
					$scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
					$scope.disable = true;
				});
			}
		};

		$scope.clear = function(){
			$scope.disable = true;
			$scope.name = '';
			$scope.min_values = '';
			$scope.max_values = '';
			$scope.drcr = '';
			$scope.id = '';
		};
	}
]);