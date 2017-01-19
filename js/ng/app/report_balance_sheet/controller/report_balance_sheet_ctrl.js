app.controller(
    'report_balance_sheet_ctrl', [
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
                var data = {
                    from_date: $scope.from_date
                };
                $scope.loading = false;
                $scope.balanceSheet = [];
                Restful.get('api/BalanceSheet', data).success(function(data){
                    $scope.balanceSheet = data;
                    $scope.loading = true;
                    //$scope.total_asset = data.elements[0].total;
                    //$scope.total_liability = data.elements[1].total;
                     console.log(data);
                    //console.log($scope.total_asset);
                    //console.log($scope.total_liability);
                });
            };

            $scope.print = function(){
                if( !$scope.balanceSheet ){
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