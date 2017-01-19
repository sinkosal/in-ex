app.controller(
    'doctor_expense_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/DoctorExpense/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.doctorExpense = data;
                    $scope.totalItems = data.count;
                });
            };
            var params = {paginate: 'yes'};
            $scope.init(params);

            // doctor filter
            $scope.doctor_list = {};
            $scope.refreshDoctorList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/DoctorListOnly', params).then(function(response) {
                    $scope.doctors = response.data.elements;
                });
            };
            // customer filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/CustomerListOnly', params).then(function(response) {
                    $scope.customers = response.data.elements;
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

            $scope.search = function(id){
                params.invoice_no = $scope.search_name;
                params.id = $scope.search_id;
                $scope.init(params);
            };

            $scope.edit = function(params){
                $scope.params = angular.copy(params);
                $scope.date = $scope.params.expense_date;
                $scope.description = $scope.params.description;
                $scope.invoice_no = $scope.params.invoice_no;
                $scope.price = $scope.params.price;
                $scope.qty = $scope.params.qty;
                $scope.amount = $scope.params.amount;
                $scope.doctor_list = {full_name: $scope.params.doctor_detail[0].name};
                $scope.customer_id = $scope.params.customer_detail[0].id;
                $scope.id = $scope.params.id;
                $('#doctor-expense-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){console.log( $scope.customer_list);
                console.log( $scope.doctor_list);
                var data = {
                    expense_date: $scope.date,
                    description: $scope.description,
                    invoice_no: $scope.invoice_no,
                    doctor_id: $scope.doctor_list.selected.id,
                    customer_id: $scope.customer_list.selected.id,
                    qty: $scope.qty,
                    price: $scope.price,
                    amount: $scope.amount
                };console.log(data);
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#doctor-expense-popup').modal('hide');
                        $scope.clear();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#doctor-expense-popup').modal('hide');
                        $scope.clear();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {console.log(data);
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };

            $scope.clear = function(){
                $scope.disable = true;
                $scope.date = '';
                $scope.doctor_list = {};
                $scope.customer_list = {};
                $scope.description = '';
                $scope.price = '';
                $scope.qty = '';
                $scope.amount = '';
                $scope.id = '';
                $scope.invoice_no = '';
                $scope.amount = '';
            };
        }
    ]);