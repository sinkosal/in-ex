app.controller(
    'report_sale_summary_ctrl', [
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
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                };
                $scope.loading = false;
                $scope.stockOut = [];
                Restful.get('api/StockOut', data).success(function(data){
                    $scope.stockOut = data;console.log(data);
                    $scope.loading = true;
                    $scope.grand_total = 0;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.grand_total = $scope.grand_total + obj.grand_total;
                    }
                });
            };

            $scope.print = function(){
                if( !$scope.stockOut ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Filter to Print.','warning'
                    );
                }
                var divToPrint = document.getElementById("print");
                var myWindow = window.open();
                myWindow.document.write(divToPrint.innerHTML);
                myWindow.document.close();
                myWindow.focus();
                myWindow.print();
                myWindow.close();
            };

        }
]);