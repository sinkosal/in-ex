app.controller(
    'report_invoice_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            $scope.service = new Services();
            $scope.loading = true;
            function getCompanyProfile(){
                Restful.get('api/setting').success(function(data){
                    $scope.company = data.elements[0];
                });
            };
            getCompanyProfile();
            $scope.init = function(params){
                var customerId = '';
                var doctorId = '';
                if( !angular.isDefined($scope.from_date) || $scope.from_date == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Input From Date.','warning'
                    );
                }
                if( !angular.isDefined($scope.to_date) || $scope.to_date == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Input To Date.','warning'
                    );
                }
                if(angular.isDefined($scope.customer_list.selected)){
                    customerId = $scope.customer_list.selected.id;
                }
                if(angular.isDefined($scope.doctor_list.selected)){
                    doctorId = $scope.doctor_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    customer_id: customerId,
                    doctor_id: doctorId,
                    status: 1
                };
                $scope.loading = false;
                $scope.invoice = [];
                Restful.get('api/Invoice', data).success(function(data){
                    $scope.invoice = data;
                    $scope.loading = true;
                    $scope.sub_total = 0;
                    $scope.total_balance = 0;
                    $scope.total_deposit_amount = 0;
                    $scope.total_discount = 0;
                    $scope.total_bank_amount = 0;
                    $scope.total_cash_amount = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        if( obj.pay_type == 'CPB' || obj.pay_type == 'ACLIDA' ){
                            $scope.total_bank_amount = $scope.total_bank_amount + obj.deposit;
                        }
                        if( obj.pay_type == 'Cash' ){
                            $scope.total_cash_amount = $scope.total_cash_amount + obj.deposit;
                        }
                        $scope.total_balance = $scope.total_balance + obj.balance;
                        $scope.total_deposit_amount = $scope.total_deposit_amount + obj.deposit;
                        $scope.sub_total = $scope.sub_total + obj.sub_total;
                        $scope.total_discount = $scope.total_discount + obj.total_discount;
                    }
                });
            };

            // vendor filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/CustomerList', params).then(function(response) {
                    $scope.customers = response.data.elements;
                });
            };

            // vendor filter
            $scope.doctor_list = {};
            $scope.refreshDoctorList = function(doctor_list) {
                var params = {name: doctor_list};
                return Restful.get('api/DoctorList', params).then(function(response) {
                    $scope.doctors = response.data.elements;
                });
            };

            $scope.print = function(){
                if( !$scope.invoice ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Filter to Print.','warning'
                    );
                }
                var divToPrint = document.getElementById("print");
                var newWin= window.open("");
                newWin.document.write('' +
                    '<html><head>' +
                    '<link href="css/print_table.css" rel="stylesheet" type="text/css">' +
                    '</head>' +
                    '<body>' + divToPrint.innerHTML + '</body>' +
                    '</html>'
                );
                newWin.print();
                newWin.close();
            };
        }
    ]);