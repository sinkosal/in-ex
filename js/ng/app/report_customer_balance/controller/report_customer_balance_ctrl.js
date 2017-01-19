app.controller(
    'report_customer_balance_ctrl', [
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
                    balance: 'yes',
                    status: 1
                };
                $scope.loading = false;
                $scope.invoice = [];
                Restful.get('api/Invoice', data).success(function(data){
                    $scope.invoice = data;console.log(data);
                    $scope.loading = true;
                    $scope.total_balance = 0;
                    $scope.total_grand = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_balance = $scope.total_balance + obj.balance;
                        $scope.total_grand = $scope.total_grand + obj.grand_total;
                    }
                });
            };

            // customer filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(customer_list) {
                var params = {name: customer_list};
                return Restful.get('api/CustomerList', params).then(function(response) {
                    $scope.customers = response.data.elements;
                });
            };

            // doctor filter
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
                        'Warning:','No Report To Print.','warning'
                    );
                }
                var divToPrint = document.getElementById("print");
                var newWin= window.open("");
                newWin.document.write('' +
                    '<html><head>' +
                    '<link href="css/print_table.css"  rel="stylesheet" type="text/css">' +
                    '</head>' +
                    '<body>' + divToPrint.innerHTML + '</body>' +
                    '</html>'
                );
                newWin.print();
                newWin.close();
            };
        }
    ]);