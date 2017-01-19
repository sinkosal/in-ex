app.controller(
    'journal_report_ctrl', [
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
                if(angular.isDefined($scope.vendor_list.selected) ){
                    vendor = $scope.vendor_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    vendor_id: vendor
                };
                $scope.loading = false;
                $scope.journal = [];
                Restful.get('api/JournalReport', data).success(function(data){console.log(data);
                    $scope.journal = data;
                    $scope.loading = true;
                    $scope.total_credit = 0;
                    $scope.total_debit = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_credit = $scope.total_credit + obj.credit;
                        $scope.total_debit = $scope.total_debit + obj.debit;
                    }
                });
            };

            $scope.formatDate = function(date){
                var dateOut = new Date(date);
                return dateOut;
            };

            $scope.print = function(){
                if( !$scope.journal ){
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
            $scope.vendor_list = {};
            $scope.refreshVendorList = function(vendor_list) {
                var params = {name: vendor_list, paginate: 'yes'};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.vendorList = response.data.elements;console.log(response);
                });
            };
        }
    ]);