app.controller(
    'cash_flow_report_ctrl', [
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
            // doctor filter
            $scope.doctor_list = {};
            $scope.refreshDoctorList = function(name) {
                var params = {name: name, pagination: 'yes'};
                return Restful.get('api/DoctorList', params).then(function(response) {
                    $scope.doctorList = response.data.elements;
                });
            };
            $scope.init = function(params){
                var d_id = '';
                if(angular.isDefined($scope.doctor_list.selected)){
                    d_id = $scope.doctor_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    doctor_id: d_id
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

                Restful.get('api/DoctorExpense', data).success(function(data){
                    $scope.expense = data;
                    $scope.loading = true;
                    $scope.total_amount = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_amount = $scope.total_amount + obj.amount;
                    }
                });

                $scope.total_net_income_amount = parseFloat($scope.total_income_amount) - parseFloat($scope.total_amount);
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