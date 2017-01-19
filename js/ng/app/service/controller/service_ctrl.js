app.controller(
    'service_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/Service/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.services = data;
                    $scope.totalItems = data.count;
                });
                Restful.get('api/CustomerType').success(function(data){
                    $scope.customerType = data;
                });
            };
            $scope.init();
            /**
             * start functionality pagination
             */
            var params = {};
            $scope.currentPage = 1;
            //get another portions of data on page changed
            $scope.pageChanged = function() {
                $scope.pageSize = 10 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                $scope.init(params);
            };

            $scope.edit = function(params){
                $scope.params = angular.copy(params);
                $scope.service_name = $scope.params.service_name;
                $scope.detail = $scope.params.detail;
                $scope.type = $scope.params.type;
                $scope.normal = $scope.params.normal;
                var customerId = '';
                if(  $scope.params.customer_type.length > 0){
                    var customerId =  $scope.params.customer_type[0].id;
                }
                $scope.customer_type_id = customerId;
                $scope.price = $scope.params.price;
                $scope.unit = $scope.params.unit;
                $scope.id = $scope.params.id;
                $('#service-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {
                    service_name: $scope.service_name,
                    type: $scope.type,
                    customer_type_id: $scope.customer_type_id,
                    unit: $scope.unit,
                    price: $scope.price,
                    normal: $scope.normal,
                    detail: $scope.detail
                };
                $scope.disable = false;
                if($scope.id) {console.log($scope.id);
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#service-popup').modal('hide');
                        $scope.close();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#service-popup').modal('hide');
                        $scope.close();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };

            $scope.close = function(){
                $scope.disable = true;
                $scope.service_name = '';
                $scope.detail = '';
                $scope.type = '';
                $scope.customer_type_id = '';
                $scope.unit = '';
                $scope.price = '';
                $scope.normal = '';
                $scope.id = '';
            };
        }
    ]);