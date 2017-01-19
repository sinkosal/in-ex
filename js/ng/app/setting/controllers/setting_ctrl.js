app.controller(
	'setting_ctrl', [
	'$scope'
	, 'Restful'
	, 'Services'
	, function ($scope, Restful, Services){
		'use strict';
		$scope.service = new Services();
		var url = 'api/Setting/';
		$scope.init = function(params){
			Restful.get(url, params).success(function(data){
				$scope.setting = data;
			});
		};
		$scope.init();
		$scope.edit = function(params){
			$scope.params = angular.copy(params);
			$scope.company_name = $scope.params.company_name;
			$scope.description = $scope.params.description;
			$scope.id = $scope.params.id;
			$('#setting-popup').modal('show');
		};
		$scope.disable = true;
		$scope.save = function(){
			var data = {company_name: $scope.company_name, description: $scope.description};
			Restful.put( url + $scope.id, data).success(function (data) {
				$scope.init();
				$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
				$('#setting-popup').modal('hide');
				$scope.disable = true;
			});
		};
	}
]);