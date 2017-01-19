app.controller(
    'received_payment_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            $scope.service = new Services();
            $scope.user = $('#user').html();
            $scope.treatment = '';
            $scope.loading = false;
            function initInvoiceNo(){
                //Restful.get('api/GenerateInvoiceNo').success(function(data){
                //    $scope.invoice_no = data.invoice_no;
                //});
                // GET Company Profile
                Restful.get('api/setting').success(function(data){
                    $scope.company = data.elements[0];
                });
            };
            initInvoiceNo();
            $scope.payment_method = 'Cash';
            // function for init invoice filter
            $scope.init = function(params){
                $scope.loading = true;
                $scope.invoice = '';
                Restful.get('api/Invoice', params).success(function(data){
                    $scope.invoice = data;
                    $scope.getTotal(data.elements);
                    $scope.loading = false;
                });
            };
            // search by invoice
            $scope.search = function(){
                var data = {
                    invoice_no: $scope.invoice_no,
                    balance: 'yes'
                };
                $scope.init(data);
            };

            $scope.calculatePayAmount = function(id){
                $scope.getTotal($scope.invoice.elements, id);
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
                    $scope.total_balance = $scope.total_balance + obj.balance;
                    if( pay >= obj.balance){
                        pay = obj.balance;
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
            $scope.invoice = [];

            $scope.save = function(){

                if( !angular.isDefined($scope.receive_payment_no) || $scope.receive_payment_no == ''){
                    return $scope.service.alertMessage(
                        'Warning:','No Receive Payment No.','warning'
                    );
                }
                if($scope.invoice.length == 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Invoice In List.','warning'
                    );
                }
                if($scope.invoice.count >= 2){
                    return $scope.service.alertMessage(
                        'Warning:','You can payment only one invoice per transaction. Please filter Invoice No you want to payment.','warning'
                    );
                }
                if($scope.total_payment_amount <= 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Payment Amount In Invoice List.','warning'
                    );
                }
                var cId = '';
                var cName = '';
                for (var i = 0, l = $scope.invoice.elements[0].detail.length; i < l; i++) {
                    var obj = $scope.invoice.elements[0].detail[i];
                    $scope.treatment +=  obj.service_name + ', ';
                }
                if($scope.invoice.count == 1){
                    cId = $scope.invoice.elements[0].customer_id;
                    cName = $scope.invoice.elements[0].customer_name;
                }else{
                    cId = $scope.customer_list.selected.id;
                    cName = $scope.customer_list.selected.full_name;
                }
                var data = {
                    receive_payment: [{
                        customer_id: cId,
                        customer_name: cName,
                        receive_payment_date: $scope.date,
                        receive_payment_no: $scope.receive_payment_no,
                        note: $scope.note,
                        total_balance: $scope.total_balance,
                        total_payment_amount: $scope.total_payment_amount,
                        discount_type: discount,
                        discount_amount: $scope.discount,
                        total_discount_amount: $scope.total_discount,
                        payment_method: $scope.payment_method,
                        bank_charge: $scope.bank_charge,
                        total_last_balance: $scope.total_last_balance,
                    }],
                    receive_case_flow_doctor: [{
                        customer_id: cId,
                        customer_name: cName,
                        invoice_date: $scope.date,
                        invoice_no: $scope.receive_payment_no,
                        doctor_id: $scope.invoice.elements[0].doctor_id,
                        doctor_name: $scope.invoice.elements[0].doctor_name,
                        bank: $scope.payment_method != 'Cash' ? $scope.total_payment_amount : '',
                        cash_in: $scope.payment_method == 'Cash' ? $scope.total_payment_amount : '',
                        bank_charge: $scope.bank_charge,
                        treatment: $scope.treatment
                    }],
                    receive_payment_detail: $scope.invoice.elements
                };
                console.log(data);
                $scope.disable = false;
                $scope.data = data;
                Restful.save('api/ReceivePayment', data).success(function (data) {
                    $('#receive-invoice-popup').modal('show');
                    $scope.service.alertMessage(
                        'Success:','Save Complete.','success'
                    );
                    clear();
                });
            };
            $scope.data_print = {};
            $scope.print = function(){
                //$('#create-invoice-popup').modal('hide');
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

            function clear(){
                $scope.invoice = [];
                $scope.invoice_no = '';
                $scope.disable = true;
                $scope.receive_payment_no = '';
                $scope.payment_method = 'Cash';
                $scope.customer_list = {};
                $scope.note = '';
                $scope.percent = false;
                $scope.dollar = false;
                $scope.total_payment_amount = 0;
                $scope.total_balance = 0;
                $scope.bank_charge = '';
                discount = '';
                $scope.total_discount = 0;
                $scope.treatment = '';
                $scope.grand_total = 0;
                $scope.discount = '';
                $scope.total_last_balance = 0;
            };

            // customer_list filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(customer_list) {
                var params = {name: customer_list};
                return Restful.get('api/CustomerList', params).then(function(response) {
                    $scope.customers = response.data.elements;
                });
            };

            // filter by Customer to show in received payment list
            $scope.getCustomer = function(params){
                var data = {
                    customer_id: params.id,
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