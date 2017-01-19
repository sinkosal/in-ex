app.controller(
	'index_ctrl', [
	'$scope'
	, 'Restful'
	, function ($scope, Restful){
		$scope.title = 'Dashboard';

		function init(){
			Restful.get('api/Index').success(function(data){
				$scope.index = data;console.log(data);
			});
		};
		init();
	}
]);