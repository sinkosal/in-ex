app.controller(
    'report_purchase_summary_ctrl', [
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
                var vendorId = '';
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
                if(angular.isDefined($scope.vendor_list.selected)){
                    vendorId = $scope.vendor_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    vendor_id: vendorId,
                    status: 'yes'
                };
                $scope.loading = false;
                $scope.purchase = [];
                Restful.get('api/Purchase', data).success(function(data){
                    $scope.purchase = data;
                    $scope.sub_total = 0;
                    $scope.total_balance = 0;
                    $scope.total_payment_amount = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_balance = $scope.total_balance + obj.remain;
                        $scope.total_payment_amount = $scope.total_payment_amount + obj.payment;
                        $scope.sub_total = $scope.sub_total + obj.total;
                    }
                    $scope.loading = true;
                });
            };

            $scope.print = function(){
                if( !$scope.purchase ){
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

            // vendor filter
            $scope.vendor_list = {};
            $scope.refreshVendorList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.vendors = response.data.elements;
                });
            };

        }
]);