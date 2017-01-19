app.controller(
    'report_receive_payment_ctrl', [
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
                $scope.invoice = [];
                Restful.get('api/ReceivePayment', data).success(function(data){
                    $scope.invoice = data;console.log(data);
                    $scope.loading = true;
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
                if( !$scope.invoice ){
                    return $scope.service.alertMessage(
                        'Warning:','No Report To Print.','warning'
                    );
                }
                var divToPrint = document.getElementById("print");
                var newWin= window.open("");
                newWin.document.write('' +
                    '<html><head>' +
                    '<link href="css/lib_template/bootstrap.min.css" rel="stylesheet" type="text/css">' +
                    '</head>' +
                    '<body>' + divToPrint.innerHTML + '</body>' +
                    '</html>'
                );
                newWin.print();
                newWin.close();
            };
        }
    ]);