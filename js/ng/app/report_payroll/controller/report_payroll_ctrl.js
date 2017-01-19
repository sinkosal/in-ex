app.controller(
    'report_payroll_ctrl', [
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
                var staff_Id = '';
                if( !angular.isDefined($scope.date) || $scope.date == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Input Date.','warning'
                    );
                }
                if(angular.isDefined($scope.staff_list.selected)){
                    staff_Id = $scope.staff_list.selected.id;
                }
                var data = {
                    date: $scope.date,
                    staff_id: staff_Id
                };
                $scope.loading = false;
                $scope.payroll = [];
                Restful.get('api/Payroll', data).success(function(data){
                    $scope.payroll = data;
                    $scope.loading = true;
                    $scope.total_tax_amount = 0;
                    $scope.total_salary_amount = 0;
                    $scope.total_staff_advance = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_tax_amount = $scope.total_tax_amount + obj.tax_amount;
                        $scope.total_salary_amount = $scope.total_salary_amount + obj.final_salary;
                        $scope.total_staff_advance = $scope.total_staff_advance + obj.staff_advance;
                    }

                });
            };
            // product name filter
            $scope.staff_list = {};
            $scope.refreshStaffList = function(staff_list) {
                var params = {name: staff_list, pagination: 'yes'};
                return Restful.get('api/Staff', params).then(function(response) {
                    $scope.staffList = response.data.elements;
                });
            };

            $scope.print = function(){
                if( !$scope.payroll ){
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