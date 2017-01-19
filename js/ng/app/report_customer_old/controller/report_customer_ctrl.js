app.controller(
    'report_customer_ctrl', [
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
            var params = {search_in_report: 'yes'};
            $scope.init = function(){
                params.id = '';
                params.customer_type_id = ''
                if(angular.isDefined($scope.customer_list.selected)){
                    params.id = $scope.customer_list.selected.id;
                }
                if(angular.isDefined($scope.customer_type.selected)){
                    params.customer_type_id = $scope.customer_type.selected.id;
                }
                //params.search_in_report = 'yes';
                console.log(params);
                $scope.loading = false;
                $scope.customers = [];
                Restful.get('api/CustomerList', params).success(function(data){
                    $scope.customers = data;
                    $scope.totalItems = data.count;
                    $scope.loading = true;
                });

            };

            /**
             * start functionality pagination
             */
            $scope.currentPage = 1;
            //get another portions of data on page changed
            $scope.pageChanged = function() {
                $scope.pageSize = 100 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                $scope.init(params);
            };

            // customer filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(customer_list) {
                var params = {name: customer_list};
                return Restful.get('api/CustomerList', params).then(function(response) {
                    $scope.customer = response.data.elements;
                });
            };

            // customer type filter
            $scope.customer_type = {};
            $scope.refreshCustomerTypeList = function(customer_type) {
                var params = {name: customer_type};
                return Restful.get('api/CustomerType', params).then(function(response) {
                    $scope.customerType = response.data.elements;
                });
            };


            $scope.print = function(){
                if( !$scope.customers ){
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