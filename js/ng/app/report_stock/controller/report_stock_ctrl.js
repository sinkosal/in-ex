app.controller(
    'report_stock_ctrl', [
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
                var pId = '';
                var p_type_Id = '';
                if( angular.isDefined($scope.product_list.selected) ){
                    pId = $scope.product_list.selected.id;
                }
                if( angular.isDefined($scope.product_type_list.selected) ){
                    p_type_Id = $scope.product_type_list.selected.id;
                }
                var data = {
                    type: p_type_Id,
                    id: pId,
                    status: $scope.status
                };

                $scope.totalRegularAmount = function(){
                    $scope.total_regular_amount = ($scope.staff_list.selected.basic_salary * $scope.regular_rate / 26).toFixed(2);
                    $scope.grossSalary();
                };

                $scope.totalqtyinstock = function(){
                    $scope.total_prodcuts_price_in = $scope.qty_in_hand * $scope.products_price_in;
                    console.log('totalqtyinstock');
                }


                $scope.loading = false;
                $scope.products = [];
                Restful.get('api/Products', data).success(function(data){
                    $scope.products = data;
                    $scope.loading = true;
                    $scope.total_qty = 0;
                    $scope.total_price_in = 0;
                    $scope.total_price_out = 0;
                    $scope.total_product_price_in = 0;
                    $scope.total_product_price_out = 0;

                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.total_qty = $scope.total_qty + obj.products_quantity;
                        $scope.total_price_in = $scope.total_price_in + obj.products_price_in;
                        $scope.total_price_out = $scope.total_price_out + obj.products_price_out;
                        $scope.total_product_price_in = $scope.total_product_price_in + (obj.products_quantity * obj.products_price_in);
                        $scope.total_product_price_out = $scope.total_product_price_out + (obj.products_quantity * obj.products_price_out);
                    }

                });
            };
            // product name filter
            $scope.product_list = {};
            $scope.refreshProductList = function(product_list) {
                var params = {name: product_list, pagination: 'yes'};
                return Restful.get('api/Products', params).then(function(response) {
                    $scope.productList = response.data.elements;
                });
            };

            // Product type filter
            $scope.product_type_list = {};
            $scope.refreshProductTypeList = function(product_type_list) {
                var params = {name: product_type_list};
                return Restful.get('api/ProductType', params).then(function(response) {
                    $scope.productType = response.data.elements;
                });
            };
            $scope.print = function(){
                if( !$scope.products ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Filter to Print.','warning'
                    );
                }
                var divToPrint = document.getElementById("print");
                var newWin= window.open("");
                newWin.document.write('' +
                    '<html><head>' +
                    '<link href="css/lib_template/bootstrap.min.css" rel="stylesheet" type="text/css">' +
                    '</head>' +
                    '<body>' + divToPrint.innerHTML + '</body>' +
                    '</html>'
                );
                newWin.print();
                newWin.close();
            };

        }
]);