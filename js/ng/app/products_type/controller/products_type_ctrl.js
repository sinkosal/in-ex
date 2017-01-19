app.controller(
    'products_type_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/ProductType/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.productsType = data;
                    $scope.totalItems = data.count;
                });
            };
            var params = {pagination: 'yes'};
            $scope.init();
            /**
             * start functionality pagination
             */
            $scope.currentPage = 1;
            //get another portions of data on page changed
            $scope.pageChanged = function() {
                $scope.pageSize = 10 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                $scope.init(params);
            };

            $scope.search = function(id){
                params.name = $scope.search_name;
                params.id = $scope.search_id;
                $scope.init(params);
            };

            $scope.edit = function(params){
                $scope.params = angular.copy(params);
                $scope.name = $scope.params.name;
                $scope.description = $scope.params.description;
                $scope.id = $scope.params.id;
                $('#products-type-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {name: $scope.name, description: $scope.description};
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#products-type-popup').modal('hide');
                        $scope.clear();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#products-type-popup').modal('hide');
                        $scope.clear();
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

            $scope.clear = function(){
                $scope.disable = true;
                $scope.name = '';
                $scope.description = '';
                $scope.id = '';
            };
        }
    ]);