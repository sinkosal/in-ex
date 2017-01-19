app.controller(
    'products_list_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/Products/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.productsList = data;
                    $scope.totalItems = data.count;
                });
                Restful.get('api/ProductType').success(function(data){
                    $scope.ProductType = data;
                });
            };
            var params = {pagination: 'yes'};
            $scope.init(params);
            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };

            /**
             * start functionality pagination
             */
            $scope.currentPage = 1;
            //get another portions of data on page changed
            $scope.pageChanged = function(){
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
                $scope.products_name = $scope.params.products_name;
                $scope.barcode = $scope.params.barcode;
                $scope.products_description = $scope.params.products_description;
                $scope.products_quantity = $scope.params.products_quantity;
                $scope.products_type = $scope.params.products_type_fields[0].id;
                $scope.products_price_in = $scope.params.products_price_in;
                $scope.products_price_out = $scope.params.products_price_out;
                $scope.id = $scope.params.id;
                $scope.qty = true;
                $('#products-list-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {
                    products_name: $scope.products_name,
                    products_description: $scope.products_description,
                    products_price_in: $scope.products_price_in,
                    products_price_out: $scope.products_price_out,
                    barcode: $scope.barcode,
                    products_quantity: $scope.products_quantity,
                    products_type_id: $scope.products_type
                };
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function(data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#products-list-popup').modal('hide');
                        $scope.close();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#products-list-popup').modal('hide');
                        $scope.close();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.remove = function(id){
                if (confirm('Are you sure you want to delete this product?')) {
                    console.log(url + id );
                    Restful.delete(url + id ).success(function(data){
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Delete Success.', 'success');
                        $scope.init();
                    });
                }
            };

            $scope.close = function(){
                $scope.qty = false;
                $scope.disable = true;
                $scope.products_name = '';
                $scope.products_description = '';
                $scope.products_type = '';
                $scope.products_price_out = '';
                $scope.products_price_in = '';
                $scope.products_quantity = '';
                $scope.barcode = '';
                $scope.id = '';
            };
        }
    ]);