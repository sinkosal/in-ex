app.controller(
	'appointment_ctrl', [
	'$scope'
	, 'Restful'
	, 'Services'
	, function ($scope, Restful, Services){
		'use strict';
		$scope.service = new Services();
		$scope.invoice_no = '';
		$scope.customer_name = '';
		$scope.tel = '';
		var url = 'api/Appointment/';
		$scope.init = function(params){
			Restful.get(url, params).success(function(data){
				$scope.appointments = data;
				$scope.totalItems = data.count;console.log(data);
			});
		};
		var params = {pagination:'yes'};
		$scope.init(params);
		$scope.updateStatus = function(params){
			params.status === 1 ? params.status = 0 : params.status = 1;
			Restful.patch(url + params.id, params ).success(function(data) {
				$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
			});
		};

		$scope.search = function(){
			params.customer_name = $scope.customer_name;
			params.invoice_no = $scope.invoice_no;
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
			$scope.customer_list.selected = $scope.params.customer_detail[0];
			$scope.dateList = $scope.params.detail;
			$scope.id = $scope.params.id;
			$('#appointment-popup').modal('show');
		};
		$scope.disable = true;
		$scope.dateList = [];
		$scope.save = function(){
			var cId='', iNo='', iId='', iDate='';
			if(angular.isDefined( $scope.invoice_list.selected ) ){
				iId =  $scope.invoice_list.selected.id;
				iNo = $scope.invoice_list.selected.invoice_no;
				iDate = $scope.invoice_list.selected.invoice_date;
				cId = $scope.invoice_list.selected.customer_id;
			}
			if(angular.isDefined( $scope.customer_list.selected ) ){
				cId =  $scope.customer_list.selected.id;
			}
			var data = {
				appointment: {
					customer_id: cId,
				},
				appointment_detail: $scope.dateList
			};
			console.log(data);
			$scope.disable = false;
			if($scope.id) {
				Restful.put( url + $scope.id, data).success(function (data) {
					$scope.init();
					$scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
					$('#appointment-popup').modal('hide');
					$scope.clear();
					console.log(data);
					$scope.disable = true;
				});
			}else {
				Restful.save( url , data).success(function (data) {
					$scope.init();
					console.log(data);
					$('#appointment-popup').modal('hide');
					$scope.clear();
					$scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
					$scope.disable = true;
				});
			}
		};

		$scope.add = function() {
			$scope.dateList.push({
				date: '',
				note: '',
			});
		};
		$scope.remove = function($index){
			$scope.dateList.splice($index, 1);
		};

		// customer filter
		$scope.customer_list = {};
		$scope.refreshCustomerList = function(customer_list) {
			var params = {name: customer_list, search_in_invoice: 'yes'};
			return Restful.get('api/CustomerList', params).then(function(response) {
				$scope.customerList = response.data.elements;
				console.log(response);
			});
		};

		// invoice_list filter
		$scope.invoice_list = {};
		$scope.refreshInvoiceList = function(invoice_list) {
			var params = {invoice_no: invoice_list, paginate: 'yes'};
			return Restful.get('api/Invoice', params).then(function(response) {
				$scope.invoiceList = response.data.elements;
			});
		};

		$scope.clear = function(){
			$scope.disable = true;
			$scope.alertWarning = false;
			$scope.invoice_no = '';
			$scope.tel = '';
			$scope.customer_name = '';
			$scope.first = '';
			$scope.second = '';
			$scope.third = '';
			$scope.fourth = '';
			$scope.fifth = '';
			$scope.customer_list = {};
			$scope.invoice_list = {};
			$scope.id = '';
			$scope.note = '';
			$scope.dateList = [];
		};
	}
]);