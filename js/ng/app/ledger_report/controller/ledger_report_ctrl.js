app.controller(
    'ledger_report_ctrl', [
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
                var vendor = '';
                if( !angular.isDefined($scope.account_list.selected) ){
                    return $scope.service.alertMessage(
                        'Warning:','Please select account','warning'
                    );
                }
                if(angular.isDefined($scope.vendor_list.selected) ){
                    vendor = $scope.vendor_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    account_id: $scope.account_list.selected.id,
                    vendor_id: vendor
                };
                $scope.loading = false;
                $scope.ledger = [];
                Restful.get('api/LedgerReport', data).success(function(data){
                    $scope.ledger = data;
                    $scope.loading = true;
                    $scope.total_credit = 0;
                    $scope.total_debit = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_credit = $scope.total_credit + obj.credit;
                        $scope.total_debit = $scope.total_debit + obj.debit;
                    }
                    $scope.total_type_of_balance = 0;
                    if($scope.ledger.elements[0].type_of_account_report == 1){
                        $scope.total_type_of_balance = $scope.total_debit - $scope.total_credit;
                    }
                    if($scope.ledger.elements[0].type_of_account_report == 2){
                        $scope.total_type_of_balance = $scope.total_credit - $scope.total_debit;
                    }
                    if($scope.ledger.elements[0].type_of_account_report == 3){
                        $scope.total_type_of_balance = $scope.total_credit - $scope.total_debit;
                    }
                    if($scope.ledger.elements[0].type_of_account_report == 4){
                        $scope.total_type_of_balance = $scope.total_debit - $scope.total_credit;
                    }
                });
            };

            $scope.formatDate = function(date){
                var dateOut = new Date(date);
                return dateOut;
            };

            $scope.print = function(){
                if( !$scope.ledger ){
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

            // account chart filter
            $scope.account_list = {};
            $scope.refreshAccountList = function(account_list) {
                var params = {name: account_list, pagination: 'yes'};
                return Restful.get('api/ChartAccount', params).then(function(response) {
                    $scope.accountList = response.data.elements;
                });
            };

            $scope.vendor_list = {};
            $scope.refreshVendorList = function(vendor_list) {
                var params = {name: vendor_list, paginate: 'yes'};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.vendorList = response.data.elements;console.log(response);
                });
            };
        }
    ]);