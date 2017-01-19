var app = angular.module(
	'main',
	[
	 	'ui.router'
		, 'ui.bootstrap'
		, 'ngSanitize'
		, 'ui.select'
		, 'cambodia.datepicker'
		//, 'pascalprecht.translate'
		//, 'ngCookies'
	]
);
app.controller(
	'main_ctrl', [
		'$scope'
		, 'Restful'
		, 'Services'
		, function ($scope, Restful, Services){
			$scope.role = $('#role').data('role');
		}
	]
);
