app.controller(
    'income_statement_report_ctrl', [
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
                    from_date: $scope.from_date,
                    to_date: $scope.to_date
                };
                $scope.loading = false;
                $scope.invoice = [];
                Restful.get('api/IncomeStatement', data).success(function(data){
                    $scope.invoice = data;
                    $scope.loading = true;
                    console.log(data);
                    $scope.netIncome = data.total_master[0].total - data.total_master[1].total - data.total_master[2].total;
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