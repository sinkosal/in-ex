app.controller(
    'report_staff_ctrl', [
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
                var id = '';
                if(angular.isDefined($scope.staff_list.selected)){
                    id = $scope.staff_list.selected.id;
                }
                var data = {
                    from_date: $scope.from_date,
                    to_date: $scope.to_date,
                    id: id
                };
                $scope.loading = false;
                $scope.staff = [];
                Restful.get('api/Staff', data).success(function(data){
                    $scope.staff = data;console.log(data);
                    $scope.loading = true;
                });
            };

            // customer filter
            $scope.staff_list = {};
            $scope.refreshStaffList = function(staff_list) {
                var params = {name: staff_list};
                return Restful.get('api/Staff', params).then(function(response) {
                    $scope.staffs = response.data.elements;
                });
            };

            $scope.print = function(){
                if( !$scope.staff ){
                    return $scope.service.alertMessage(
                        'Warning:','No Report To Print.','warning'
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