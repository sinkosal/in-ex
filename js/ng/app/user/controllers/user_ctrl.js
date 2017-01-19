app.controller(
	'user_ctrl', [
	'$scope'
	, 'Restful'
	, 'Services'
	, function ($scope, Restful, Services){
		'use strict';
		$scope.service = new Services();
		var url = 'api/User/';
		$scope.init = function(params){
			Restful.get(url, params).success(function(data){
				$scope.users = data;
			});
		};
		$scope.init();

		$scope.edit = function(params){
			var temp = angular.copy(params);
			$scope.user_name = temp.user_name;
			$scope.user_password = temp.user_password;
			$scope.role = temp.role;
			$scope.id = temp.id;
			$('#user-popup').modal('show');
		};
		$scope.disable = true;
		$scope.save = function(){
			$scope.disable = false;
			var data = {user_name: $scope.user_name, user_password: $scope.user_password, role: $scope.role};
			console.log(data);
			if($scope.id) {
				Restful.put( url + $scope.id, data).success(function (data) {
					$scope.init();console.log(data);
					$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
					$('#user-popup').modal('hide');
					clear();
					$scope.disable = true;
				});
			}else {
				Restful.save( url , data).success(function (data) {console.log(data);
					if(data.id){
						$scope.init();
						console.log('save: ', data);
						$('#user-popup').modal('hide');
						clear();
						$scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
					}else{
						$scope.disable = true;
						$scope.error = true;
					}

				});
			}
		};

		$scope.updateStatus = function(params){
			params.status === 1 ? params.status = 0 : params.status = 1;
			Restful.patch(url + params.id, params ).success(function(data) {
				$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
			});
		};

		$scope.close = function(){
			clear();
		};

		function clear(){
			$scope.error = false;
			$scope.disable = true;
			$scope.user_name = '';
			$scope.user_password = '';
			$scope.id = '';
			$scope.role = '';
		};
	}
]);