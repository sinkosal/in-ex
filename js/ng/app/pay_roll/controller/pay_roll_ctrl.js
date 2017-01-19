app.controller(
    'pay_roll_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            $scope.total_regular_amount = 0;
            $scope.total_ot_amount = 0;
            $scope.total_service_perform = 0;
            $scope.total_other_compensation = 0;
            $scope.spouse_minor = 0;
            $scope.gross_salary = 0;
            $scope.total_tax_amount = 0;
            $scope.tax = 0;
            $scope.net_salary = 0;
            $scope.staff_advance = 0;
            $scope.final_salary = 0;
            $scope.staff_list = {};
            // tax variable
            var fivePercent = 200;
            var tenPercent = 312.5;
            var fifteenPercent = 2125;
            var twentyPercent = 3125;
            $scope.total_tax_amount = 0;
            $scope.tax = 0;
            $scope.refreshStaffList = function(staff_list) {
                var params = {name: staff_list, search_in_invoice: 'yes'};
                return Restful.get('api/Staff', params).then(function(response) {
                    $scope.staffs = response.data.elements;
                });
            };

            $scope.totalServicePerform = function () {
                $scope.total_service_perform = parseFloat($scope.service_perform * $scope.benefit).toFixed(2);
                $scope.grossSalary();
            };

            $scope.totalOtAmount = function() {
                $scope.total_ot_amount = parseFloat($scope.ot * $scope.ot_rate).toFixed(2);
                $scope.grossSalary();
            };

            $scope.totalRegularAmount = function(){
                $scope.total_regular_amount = ($scope.staff_list.selected.basic_salary * $scope.regular_rate / 26).toFixed(2);
                $scope.grossSalary();
            };

            $scope.grossSalary = function(){
                var compensation = $scope.total_other_compensation;
                if( compensation ){
                    compensation = parseFloat($scope.total_other_compensation);
                }else{
                    compensation = 0;
                }
                $scope.gross_salary = (
                    + parseFloat($scope.total_ot_amount)
                    + parseFloat($scope.total_regular_amount)
                    + parseFloat($scope.total_service_perform)
                    + parseFloat(compensation)
                ).toFixed(2);
                $scope.taxable_salary = parseFloat( $scope.gross_salary - $scope.spouse_minor).toFixed(2);
                if( parseFloat($scope.taxable_salary) > twentyPercent ){
                    $scope.tax = 20;
                    $scope.total_tax_amount = ( (parseFloat($scope.taxable_salary) * 0.20) - 288.13 ).toFixed(2);
                }else if( parseFloat($scope.taxable_salary) > fifteenPercent ){
                    $scope.tax = 15;
                    $scope.total_tax_amount = ( (parseFloat($scope.taxable_salary) * 0.15) - 131.88 ).toFixed(2);
                }else if( parseFloat($scope.taxable_salary) > tenPercent ){
                    $scope.tax = 10;
                    $scope.total_tax_amount = ( (parseFloat($scope.taxable_salary) * 0.10) - 25.63).toFixed(2);
                }else if( parseFloat($scope.taxable_salary) > fivePercent ){
                    $scope.tax = 5;
                    $scope.total_tax_amount = ( (parseFloat($scope.taxable_salary) * 0.05) - 10 ).toFixed(2);
                }else{
                    $scope.tax = 0;
                    $scope.total_tax_amount = 0;
                }
                $scope.net_salary = parseFloat($scope.taxable_salary - $scope.total_tax_amount).toFixed(2);
                $scope.total_salary = (parseFloat($scope.net_salary) + parseFloat($scope.spouse_minor)).toFixed(2);
                if($scope.staff_advance) {
                    $scope.final_salary = (parseFloat($scope.total_salary) - parseFloat($scope.staff_advance) ).toFixed(2);
                }else{
                    $scope.final_salary = $scope.total_salary;
                }
            };

            $scope.disable = true;
            $scope.save = function(){
                if( !$scope.staff_list.selected ){
                    return $scope.service.alertMessage('<strong>Warning: </strong>', 'Need Select Staff.', 'warning');
                }
                var data = {
                    staff_id: $scope.staff_list.selected.id,
                    regular_rate: $scope.regular_rate,
                    total_regular_amount: $scope.total_regular_amount,
                    ot: $scope.ot,
                    ot_rate: $scope.ot_rate,
                    total_ot_amount: $scope.total_ot_amount,
                    service_perform: $scope.service_perform,
                    benefit: $scope.benefit,
                    total_service_perform: $scope.total_service_perform,
                    note: $scope.note,
                    other_compensation: $scope.total_other_compensation,
                    spouse_minor: $scope.spouse_minor,
                    gross_salary: $scope.gross_salary,
                    taxable_salary: $scope.taxable_salary,
                    tax: $scope.tax,
                    tax_amount: $scope.total_tax_amount,
                    net_salary: $scope.net_salary,
                    total_salary: $scope.total_salary,
                    staff_advance: $scope.staff_advance,
                    final_salary: $scope.final_salary,
                };
                console.log(data);
                $scope.disable = false;
                Restful.save( 'api/Payroll' , data).success(function (data) {
                    clear();console.log(data);
                    $scope.disable = true;
                    if(data.id){
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                    }else{
                        $scope.service.alertMessage(
                            '<strong>Warning: </strong>',
                            'Duplicate Data. This Staff Already Payroll.',
                            'warning'
                        );
                    }
                });
            };


            function clear(){
                $scope.disable = true;
                $scope.staff_list = {};
                $scope.regular_rate = '';
                $scope.total_regular_amount = 0;
                $scope.ot = '';
                $scope.ot_rate = '';
                $scope.total_ot_amount = 0;
                $scope.total_salary = 0;
                $scope.taxable_salary = 0;
                $scope.service_perform = '';
                $scope.benefit = '';
                $scope.total_service_perform = 0;
                $scope.note = '';
                $scope.total_other_compensation = 0;
                $scope.spouse_minor = 0;
                $scope.gross_salary = 0;
                $scope.tax = 0;
                $scope.total_tax_amount = 0;
                $scope.net_salary = 0;
                $scope.staff_advance = 0;
                $scope.final_salary = 0;
            };
        }
    ]);