app.controller(
    'pay_bill_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            $scope.service = new Services();

            $scope.loading = false;
            $scope.init = function(params){
                $scope.loading = true;
                $scope.purchase = '';
                Restful.get('api/Purchase', params).success(function(data){
                    $scope.purchase = data;console.log(data);
                    $scope.getTotal(data.elements);
                    $scope.loading = false;
                });
            };

            // functionality for filter purchase No
            // search by purchase no
            $scope.search = function(){
                var data = {
                    reference_no: $scope.purchase_no,
                    balance: 'yes'
                };
                $scope.init(data);
            };

            $scope.calculatePayAmount = function(id){
                $scope.getTotal($scope.purchase.elements, id);
            };

            // functional get total of all products
            $scope.getTotal = function(params, id){
                $scope.total_balance = 0;
                $scope.total_payment_amount = 0;
                for (var i = 0, l = params.length; i < l; i++) {
                    var obj = params[i];
                    var pay = parseFloat(obj.payment_next);
                    if( isNaN(pay) ){
                        pay = 0;
                    }
                    $scope.total_balance = $scope.total_balance + obj.remain;
                    if( pay >= obj.remain){
                        pay = obj.remain;
                    }
                    $scope.total_payment_amount = $scope.total_payment_amount + pay;
                }
                $scope.grand_total = $scope.total_balance - $scope.total_payment_amount - parseFloat($scope.total_discount);
                $scope.total_last_balance = $scope.grand_total;
            };

            $scope.date = moment().format('YYYY-MM-DD');
            $scope.total_payment_amount = 0;
            $scope.total_balance = 0;
            var discount = '';
            $scope.total_discount = 0;
            $scope.grand_total = 0;
            $scope.discount = '';
            $scope.total_last_balance = 0;
            $scope.disable = true;

            $scope.save = function(){
                if( !angular.isDefined($scope.reference_no) || $scope.reference_no == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','No PaymentNo. Please Input PaymentNo.','warning'
                    );
                }
                // if( !angular.isDefined($scope.vendor_list.selected) ){
                //     return $scope.service.alertMessage(
                //         'Warning:','No Vendor. Please Select Vendor.','warning'
                //     );
                // }
                if($scope.purchase.elements.length == 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Purchase In List.','warning'
                    );
                }
                if($scope.total_payment_amount <= 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Payment Amount In Purchase List.','warning'
                    );
                }

                var vId = '';
                var vName = '';
                // if($scope.vendor_list.selected){
                //     vId = $scope.vendor_list.selected.id;
                //     vName = $scope.vendor_list.selected.name;
                // }else{
                    vId = $scope.purchase.elements[0].supplier_id;
                    vName = $scope.purchase.elements[0].supplier_name;                    
                // }
                $scope.disable = false;
                var data = {
                    payment: [{
                        vendor_payment_no: 'test_vendor_payment_no',
                        reference_no: $scope.reference_no,
                        vendor_id: vId,
                        vendor_name: vName,
                        payment_date: $scope.date,
                        total_amount: $scope.total_amount,
                        discount_type: discount,
                        discount: $scope.discount,
                        total_discount: $scope.total_discount,
                        payment_method: $scope.payment_method,
                        grand_total: $scope.total_payment_amount,
                        total_balance: $scope.total_balance,
                        total_last_balance: $scope.total_last_balance,
                        description: $scope.note,

                    }],
                    payment_detail: $scope.purchase.elements
                };
                console.log(data);
                Restful.save('api/Payment', data).success(function (data) {
                    console.log(data);
                    $scope.service.alertMessage(
                        'Success:','Save Complete.','success'
                    );
                    clearVendor();
                });
            };

            function clearVendor(){
                $scope.purchase = [];
                $scope.disable = true;
                $scope.reference_no = '';
                $scope.payment_method = '';
                $scope.vendor_list = {};
                $scope.note = '';
                $scope.total_payment_amount = 0;
                $scope.total_balance = 0;
                var discount = '';
                $scope.total_discount = 0;
                $scope.grand_total = 0;
                $scope.discount = '';
                $scope.purchase_no = '';
//                $scope.p.payment_next = '';
                $scope.total_last_balance = 0;
            };

            // vendor filter
            $scope.vendor_list = {};
            $scope.refreshVendorList = function(vendor_list) {
                var params = {name: vendor_list};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.vendors = response.data.elements;
                });
            };

            // filter by vendor to show in purchase list
            $scope.getVendor = function(params){
                var data = {
                    vendor_id: params.id,
                    balance: 'yes'
                };
                $scope.discount = 0;
                $scope.total_discount = 0;
                $scope.init(data);
            };

            // function change value in text purchase payment
            $scope.changeValuePayment = function(value, params){
                var payment = parseFloat(value);

            };

            // function calculate discount when change value
            $scope.inputDiscount = function(){
                $scope.checkTypeDiscount();
            };

            // functionality calculate discount type
            $scope.checkTypeDiscount = function(value) {
                $scope.inputDiscountAmount = true;
                if( value ){
                    discount = value;
                }
                if(discount == "percent"){
                    $scope.total_discount = (($scope.discount / 100) * $scope.total_balance);
                    $scope.grand_total = $scope.total_balance - $scope.total_payment_amount - $scope.total_discount;
                    $scope.total_last_balance =  $scope.grand_total;
                    $scope.percent = true;
                    $scope.dollar = false;
                }else{
                    $scope.total_discount = $scope.discount;
                    $scope.grand_total = $scope.total_balance - $scope.total_discount - $scope.total_payment_amount;
                    $scope.total_last_balance = $scope.grand_total;
                    $scope.dollar = true;
                    $scope.percent = false;
                }
            };
        }
    ]);