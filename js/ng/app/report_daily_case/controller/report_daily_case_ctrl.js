app.controller(
    'report_daily_case_ctrl', [
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
                $scope.caseFlow = [];
                Restful.get('api/CaseFlowDoctor', data).success(function(data){
                    $scope.caseFlow = data;console.log(data);
                    $scope.loading = true;
                    $scope.total_cash_amount = 0;
                    $scope.total_bank_amount = 0;
                    $scope.total_bank_charge_amount = 0;
                    $scope.total_income_amount = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_cash_amount = $scope.total_cash_amount + obj.cash_in;
                        $scope.total_bank_amount = $scope.total_bank_amount + obj.bank;
                        $scope.total_bank_charge_amount = $scope.total_bank_charge_amount + (obj.bank_charge * (obj.cash_in + obj.bank) / 100);
                    }
                    $scope.total_income_amount = $scope.total_cash_amount + $scope.total_bank_amount - $scope.total_bank_charge_amount;
                });

            };

            $scope.print = function(){
                if( !$scope.caseFlow ){
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