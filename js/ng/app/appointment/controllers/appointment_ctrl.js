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
			// start init Countries List
            Restful.get('api/Countries').success(function(data){
                $scope.countries = data;
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
			$scope.customer_name = $scope.params.customer_name;
			$scope.tel = $scope.params.customer_telephone;
			$scope.invoice_no = $scope.params.invoice_no;
			$scope.dateList = $scope.params.detail;
			$scope.id = $scope.params.id;
			$('#appointment-popup').modal('show');
		};
		$scope.disable = true;
		$scope.dateList = [];
		$scope.save = function(){
			var cId='', cName='', iNo='', iId='', iDate='', cTel = '';
			if(angular.isDefined( $scope.invoice_list.selected ) ){
				iId =  $scope.invoice_list.selected.id;
				iNo = $scope.invoice_list.selected.invoice_no;
				iDate = $scope.invoice_list.selected.invoice_date;
				cId = $scope.invoice_list.selected.customer_id;
				cName = $scope.invoice_list.selected.customer_name;
				cTel = $scope.invoice_list.selected.customer_tel;
			}else{
				if(!$scope.id){
					return $scope.alertWarning = true;
				}
			}
			var data = {
				appointment: {
					customer_id: cId,
					customer_name: cName,
					customer_telephone: cTel,
					invoice_id: iId,
					invoice_no: iNo,
					invoice_date: iDate,
					customer_address: iDate,
					customer_gender: iDate,
					customer_country: iDate,
					customer_dob: iDate,						
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