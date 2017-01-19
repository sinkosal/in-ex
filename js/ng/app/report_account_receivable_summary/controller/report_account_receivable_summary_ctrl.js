app.controller(
    'report_account_receivable_summary_ctrl', [
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
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    customer_id: customerId,
                    doctor_id: doctorId
                };
                $scope.loading = false;
                $scope.receivePayment = [];
                Restful.get('api/ReceivePayment', data).success(function(data){
                    $scope.receivePayment = data;
                    $scope.loading = true;
                    console.log(data);
                    $scope.total_discount_amount = 0;
                    $scope.total_balance = 0;
                    $scope.total_payment_amount = 0;
                    $scope.total_last_balance = 0;
                    $scope.total_bank_amount = 0;
                    $scope.total_cash_amount = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_balance = $scope.total_balance + obj.total_balance;
                        if( obj.payment_method == 'PCB' || obj.payment_method == 'ACLIDA' ){
                            $scope.total_bank_amount = $scope.total_bank_amount + obj.total_payment_amount;
                        }
                        if( obj.payment_method == 'Cash' ){
                            $scope.total_cash_amount = $scope.total_cash_amount + obj.total_payment_amount;
                        }
                        $scope.total_payment_amount = $scope.total_payment_amount + obj.total_payment_amount;
                        $scope.total_discount_amount = $scope.total_discount_amount + obj.total_discount_amount;
                        $scope.total_last_balance = $scope.total_last_balance + obj.total_last_balance;
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

            $scope.print = function(){
                if( !$scope.receivePayment ){
                    return $scope.service.alertMessage(
                        'Warning:','No Report To Print.','warning'
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