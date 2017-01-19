app.controller(
    'purchase_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            $scope.service = new Services();
            /*********************************
             * Start filter List of Purchase *
             *********************************
             */
            var params = {pagination: 'yes'};
            $scope.search = function(){
                params.reference_no = $scope.reference_no;
                params.supplier_name = $scope.vendor;
                initPurchaseList(params);
            };

            $scope.addNew = false;
            function initVendorList(params){
                Restful.get('api/VendorList', params).success(function(data){
                    $scope.vendorList = data;
                });
            };
            initVendorList();
            function initPurchaseList(params){
                Restful.get('api/Purchase', params).success(function(data){
                    $scope.purchaseList = data;
                    $scope.totalItems = data.count;
                });
            };
            initPurchaseList(params);
            $scope.currentPage = 1;
            $scope.pageChanged = function(){
                $scope.pageSize = 10 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                initPurchaseList(params);
            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch('api/Purchase/' + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };
            $scope.back = function(){
                $scope.addNew = false;
                initPurchaseList();
            };

            /***************************************
             * Start functionality for insert New **
             ***************************************
             */
            $scope.init = function(params){
                Restful.get('api/ProductType').success(function(data){
                    $scope.ProductType = data;
                });
            };
            $scope.init();

            // Event for calculate qty with price when input
            $scope.calculate = function() {
                $scope.total = ($scope.qty * $scope.product_filter.selected.products_price_in).toFixed(2) ;
            };

            $scope.listData = [];
            // functional get total of all products
            $scope.getTotal = function(){
                $scope.sub_total = 0;
                for (var i = 0, l = $scope.listData.length; i < l; i++) {
                    var obj = $scope.listData[i];
                    //var sub_total = obj.qty * obj.price;
                    $scope.sub_total = $scope.sub_total + (obj.qty * obj.unit_price);
                }
                $scope.sub_total.toFixed(2);
                $scope.remaining = $scope.sub_total;
                $scope.input();
            };

            // calculate money
            $scope.input = function(){
                if($scope.sub_total){
                    $scope.remaining = ($scope.sub_total - $scope.input_money).toFixed(2);
                }else{
                    $scope.remaining = '';
                }
            };
            $scope.date = moment().format('DD-MM-YYYY');
            $scope.sub_total = 0;
            $scope.input_money = 0;
            $scope.remaining = 0;
            $scope.save = function(){
                if( !angular.isDefined($scope.vendor_list.selected) ){
                    return $scope.service.alertMessage(
                        'Warning:','No Vendor. Please Select Vendor.','warning'
                    );
                }
                if( !angular.isDefined($scope.reff_no) || $scope.reff_no == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','No Reff No. Please Input ReffNo.','warning'
                    );
                }
                if($scope.listData.length == 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Product In List. Please Add Product.','warning'
                    );
                }
                $scope.disable = true;
                var data = {
                    purchase: [{
                        purchase_date: $scope.date,
                        total: $scope.sub_total,
                        payment: $scope.input_money,
                        remain: $scope.remaining,
                        note: $scope.note,
                        reff_no: $scope.reff_no,
                        supplier_id: $scope.vendor_list.selected.id,
                        supplier_name: $scope.vendor_list.selected.name,
                    }],
                    purchase_detail: $scope.listData
                };
                $scope.disabled = true;
                Restful.save('api/Purchase', data).success(function (data) {
                    console.log(data);
                    $scope.disabled = false;
                    $scope.service.alertMessage(
                        'success:','Save Complete.','success'
                    );
                    clear();
                    clearVendor();
                    clearProduct();
                });
            };

            function clearProduct(){
                $scope.products_name = '';
                $scope.products_description = '';
                $scope.products_price_out = '';
                $scope.products_price_in = '';
                $scope.barcode = '';
                $scope.products_quantity = '';
                $scope.products_type = '';
            };

            $scope.add = function(){
                if(!angular.isDefined( $scope.product_filter.selected ) ){
                    return $scope.service.alertMessage(
                        'warning:','Please Select Product.','warning'
                    );
                }
                if(!angular.isDefined( $scope.qty ) ){
                    return $scope.service.alertMessage(
                        'warning:','Please Input Qty.','warning'
                    );
                }
                // check if exist in list
                for (var i = 0, l = $scope.listData.length; i < l; i++) {
                    var obj = $scope.listData[i];
                    if (obj.product_id === $scope.product_filter.selected.id) {
                        var old_qty = parseInt(obj.qty) + parseInt($scope.qty);
                        obj.qty = old_qty;
                        obj.unit_price = $scope.product_filter.selected.products_price_in;
                        obj.description = $scope.product_filter.selected.products_description;
                        obj.barcode = $scope.product_filter.selected.barcode;
                        obj.total = obj.qty * obj.unit_price;
                        $scope.getTotal();
                        clear();
                        return;
                    }
                }
                $scope.listData.push({
                    product_id: $scope.product_filter.selected.id,
                    product_name: $scope.product_filter.selected.products_name,
                    description: $scope.product_filter.selected.products_description,
                    barcode: $scope.product_filter.selected.barcode,
                    qty: $scope.qty,
                    unit_price: $scope.product_filter.selected.products_price_in,
                    total: $scope.qty * parseInt($scope.product_filter.selected.products_price_in)
                });
                $scope.getTotal();
                clear();
            };

            $scope.remove = function($index){
                $scope.listData.splice($index, 1);
                $scope.getTotal();
            };

            function clear(){
                $scope.qty = undefined;
                $scope.product_filter = {};
                $scope.total = '';
            };

            function clearVendor(){
                $scope.listData = [];
                $scope.reff_no = '';
                $scope.vendor_list = {};
                $scope.note = '';
                $scope.input_money = '';
                $scope.sub_total = 0;
                $scope.remaining = 0;
            };

            // vendor filter
            $scope.vendor_list = {};
            $scope.refreshVendorList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.vendors = response.data.elements;
                });
            };

            // product filter
            $scope.product_filter = {};
            $scope.refreshProductsList = function(product_filter) {
                var params = {name: product_filter, status: '1'};
                return Restful.get('api/Products', params).then(function(response) {
                    $scope.products = response.data.elements;
                });
            };
            $scope.disable = true;
            $scope.saveProduct = function(){
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
                console.log(data);
                Restful.save( 'api/Products' , data).success(function (data) {
                    console.log(data);
                    $('#purchase-pop-up').modal('hide');
                    $scope.refreshProductsList();
                    clearProduct();
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                    $scope.disable = true;
                });
            };

        }
]);