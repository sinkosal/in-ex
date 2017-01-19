app.controller(
    'create_invoice_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.today = new Date();
            $scope.user = $('#user').html();
            $scope.invoice_date = moment().format('YYYY-MM-DD');
            $scope.service = new Services();
            $scope.grand_total = 0;
            $scope.input_money = 0;
            $scope.sub_total = 0;
            $scope.discount = '';
            $scope.total_discount = 0;
            $scope.remaining = 0;
            $scope.payment_method = 'Cash';
            $scope.addNew = false;
            var pay_type = '';
            var bank = '';
            var discount = '';
            function initInvoiceNo(){
                var params = {invoice_date: $scope.invoice_date};
                Restful.get('api/GenerateInvoiceNo', params).success(function(data){
                    $scope.invoice_no = data.invoice_no;
                });
                // GET Company Profile
                Restful.get('api/setting').success(function(data){
                    $scope.company = data.elements[0];
                });
            };
            initInvoiceNo();
            $scope.init = function(params){
                // start init Doctor List
                Restful.get('api/DoctorList').success(function(data){
                    $scope.doctors = data;
                });
                Restful.get('api/CustomerType').success(function(data){
                    $scope.customerType = data;
                });
            };
            $scope.init();

            var params = {paginate: 'yes'};
            function initInvoiceList(params){
                Restful.get('api/Invoice', params).success(function(data){
                    $scope.invoiceList = data;
                    $scope.totalItems = data.count;
                    console.log(data);
                });
            };
            initInvoiceList(params);
            $scope.currentPage = 1;
            $scope.pageChanged = function(){
                $scope.pageSize = 20 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                initInvoiceList(params);
            };

            // search functionality
            $scope.search = function(){
                var customerId  = '';
                if( angular.isDefined($scope.customer_list.selected) ){
                    var customerId  = $scope.customer_list.selected.id;
                }
                params.invoice_no = $scope.invoice_no_search;
                params.doctor_id = $scope.doctorId;
                params.customer_id = customerId;console.log(params);
                initInvoiceList(params);
            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch('api/Invoice/' + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };
            $scope.back = function(){
                $scope.addNew = false;
                initInvoiceList();
            };
            /*-----------------------------------------------------------------*/
            /*-----------------------------------------------------------------*/
            /*-----------------------save functionality-----------------------*/
            /*-----------------------------------------------------------------*/
            /*-----------------------------------------------------------------*/
            $scope.changeInvoiceNo = function(){
                var params = {invoice_date: $scope.invoice_date};
                Restful.get('api/GenerateInvoiceNo', params).success(function(data){
                    $scope.invoice_no = data.invoice_no;
                });
            };

            $scope.edit = function(params){
                alert('edit');
            };
            $scope.disable = false;
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

            $scope.selectService = function(params){
                $scope.service_copy = angular.copy(params);
                if(params.price <= 0){
                    $scope.disable_price = false;
                    console.log('test');
                }else{
                    $scope.disable_price = true;
                }
            };

            $scope.save = function(){
                if( !angular.isDefined($scope.customer_list.selected) ){
                    return $scope.service.alertMessage(
                        'Warning:','No Customer. Please Select Customer.','warning'
                    );
                }
                if( !$scope.customer_type ){
                    return $scope.service.alertMessage(
                        'Warning:','No customer type. Please Select customer type.','warning'
                    );
                }
                if( !$scope.doctorObject ){
                    return $scope.service.alertMessage(
                        'Warning:','No Doctor. Please Select Doctor.','warning'
                    );
                }
                if( !angular.isDefined($scope.invoice_no) || $scope.invoice_no == '' ){
                    return $scope.service.alertMessage(
                        'Warning:','Please Input Invoice No.','warning'
                    );
                }
                if($scope.invoices.length == 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Service In List. Please Add Service.','warning'
                    );
                }
                var data = {
                    invoice: {
                        invoice_no: $scope.invoice_no,
                        reference_no: $scope.reference_no,
                        invoice_date: $scope.invoice_date,
                        customer_id: $scope.customer_list.selected.id,
                        customer_name: $scope.customer_list.selected.full_name,
                        customer_tel: $scope.customer_list.selected.tel,
                        customer_type_id: $scope.customer_type.id,
                        customer_type_name: $scope.customer_type.name,
                        doctor_id: $scope.doctorObject.id,
                        doctor_name: $scope.doctorObject.name,
                        noted: $scope.note,
                        pay_type: $scope.payment_method,
                        bank: bank,
                        sub_total: $scope.sub_total,
                        discount_type: discount,
                        total_discount: $scope.total_discount,
                        discount: $scope.discount,
                        grand_total: $scope.grand_total,
                        deposit: $scope.input_money,
                        balance: $scope.remaining,
                        bank_charge: $scope.bank_charge
                    },
                    case_flow: {
                        invoice_no: $scope.invoice_no,
                        invoice_date: $scope.invoice_date,
                        customer_id: $scope.customer_list.selected.id,
                        customer_name: $scope.customer_list.selected.full_name,
                        doctor_id: $scope.doctorObject.id,
                        doctor_name: $scope.doctorObject.name,
                        bank: $scope.payment_method != 'Cash' ? $scope.input_money : '',
                        cash_in: $scope.payment_method == 'Cash' ? $scope.input_money : '',
                        treatment: $scope.treatment ,
                        bank_charge: $scope.bank_charge
                    },
                    invoice_detail: $scope.invoices,
                    appointment: $scope.dateList
                };
                $scope.data = data;console.log(data);
                $scope.disable = true;
                Restful.save( 'api/Invoice' , data).success(function (data) {
                    $scope.close();console.log(data);
                    $scope.in_no = $scope.invoice_no;
                    initInvoiceNo();
                    $('#create-invoice-popup').modal('show');
                    //$scope.print();
                    $scope.service.alertMessage('Success:', 'Save Success.', 'success');
                    $scope.disable = false;
                    clearAll();
                });
            };
            $scope.dateList = [];
            $scope.addAppointment = function() {
                $scope.dateList.push({
                    date: '',
                    note: '',
                });
            };

            $scope.removeAppointment = function($index){
                $scope.dateList.splice($index, 1);
            };
            function clearAll(){
                $scope.close();
                $scope.note = '';
                $scope.service_copy = '';
                $scope.note_appointment = '';
                $scope.invoice_no = '';
                $scope.reference_no = '';
                $scope.grand_total = 0;
                $scope.input_money = 0;
                $scope.sub_total = 0;
                $scope.discount = '';
                $scope.total_discount = 0;
                $scope.remaining = 0;
                pay_type = '';
                $scope.inputDiscountAmount = false;
                $scope.customer_list = {};
                bank = '';
                discount = '';
                $scope.cash = false;
                $scope.dollar = false;
                $scope.percent = false;
                $scope.aclida = false;
                $scope.bank = false;
                $scope.cpb = false;
                $scope.payment_method = 'Cash';
                $scope.invoices = [];
                $scope.dateList = [];
                $scope.bank_charge = '';
            };

            $scope.changePaySelect = function(value){
                pay_type = value;
                if(value == 'bank'){
                    $scope.cash = false;
                    $scope.bank = true;
                }else{
                    $scope.bank = false;
                    bank = '';
                    $scope.aclida = false;
                    $scope.cpb = false;
                    $scope.cash = true;
                }
            };

            $scope.changeBankSelect = function(value){
                bank = value;
                if(value == 'CPB'){
                    $scope.aclida = false;
                    $scope.cpb = true;
                }else{
                    $scope.cpb = false;
                    $scope.aclida = true;
                }
            };

            $scope.remove = function($index){
                $scope.invoices.splice($index, 1);
                $scope.getTotal();
            };

            $scope.changeDoctor = function(params){
                console.log(params);
                $scope.doctorObject = params;
            };

            $scope.invoices = [];
            $scope.treatment = '';
            $scope.add = function(){
                if(!angular.isDefined( $scope.service_list.selected ) ){
                    return $scope.service.alertMessage(
                        'warning:','Please Select Service Name.','warning'
                    );
                }
                if(!angular.isDefined( $scope.unit ) ){
                    return $scope.service.alertMessage(
                        'warning:','Please Input Unit.','warning'
                    );
                }
                $scope.disable_price = false;
                var unit_in_stock = parseInt($scope.service_list.selected.unit);
                // check if qty has in stock add
                //if($scope.unit < unit_in_stock ) {
                    // check if exist in list
                    //for (var i = 0, l = $scope.invoices.length; i < l; i++) {
                    //    var obj = $scope.invoices[i];
                    //    if (obj.service_id === $scope.service_list.selected.id) {
                    //        var old_qty = parseInt(obj.qty) + parseInt($scope.unit);
                    //        // check again in existing object
                    //        //if( old_qty < unit_in_stock){
                    //            obj.qty = old_qty;
                    //            obj.dent_order = $scope.dent_order;
                    //            obj.color = $scope.color;
                    //            obj.total = obj.qty * obj.unit_price;
                    //            $scope.getTotal();
                    //            $scope.close();
                    //            return;
                    //        //}else{
                    //        //    return  $scope.service.alertMessage(
                    //        //        'warning:','OPP! Out Off Stock. You Have Only ' + $scope.service_list.selected.unit +' Unit In Stock.','warning'
                    //        //    );
                    //        //}
                    //    }
                    //}
                    $scope.invoices.push({
                        service_id: $scope.service_list.selected.id,
                        service_name: $scope.service_list.selected.service_name,
                        dent_order: $scope.dent_order,
                        color: $scope.color,
                        qty: $scope.unit,
                        unit_price: $scope.service_copy.price,
                        total: $scope.service_copy.price * $scope.unit
                    });
                    $scope.treatment +=  $scope.service_list.selected.service_name + ', ';
                console.log($scope.treatment);
                    $scope.getTotal();
                    $scope.close();
                    $scope.service_copy = '';
                //}else{
                //    return  $scope.service.alertMessage(
                //        'warning:','OPP! Out Off Stock. You Have Only ' + $scope.service_list.selected.unit +' Unit In Stock.','warning'
                //    );
                //}
            };

            // functional get total of all products
            $scope.getTotal = function(){
                $scope.sub_total = 0;
                for (var i = 0, l = $scope.invoices.length; i < l; i++) {
                    var obj = $scope.invoices[i];
                    $scope.sub_total = $scope.sub_total + (obj.qty * obj.unit_price);
                }
                $scope.sub_total.toFixed(2);
                $scope.grand_total = $scope.sub_total;
                $scope.remaining = $scope.grand_total;
            };

            // calculate money payment
            $scope.inputMoney = function(){
                if($scope.sub_total){
                    $scope.remaining = ($scope.sub_total - $scope.input_money - $scope.total_discount).toFixed(2);
                }else{
                    $scope.remaining = '';
                }
            };
            // function calculate discount when change value
            $scope.inputDiscount = function(){
                //$scope.total_discount = $scope.discount;
                $scope.checkTypeDiscount();
                //$scope.grand_total = $scope.sub_total - $scope.total_discount;
            };

            // functionality calculate discount type
            $scope.checkTypeDiscount = function(value) {
                $scope.inputDiscountAmount = true;
                if( value ){
                    discount = value;
                }
                if(discount == "percent"){
                    $scope.total_discount = (($scope.discount / 100) * $scope.sub_total).toFixed(2);
                    $scope.grand_total = $scope.sub_total - $scope.total_discount;
                    $scope.remaining =  $scope.grand_total - $scope.input_money;
                    $scope.percent = true;
                    $scope.dollar = false;
                }else{
                    $scope.total_discount = $scope.discount;
                    $scope.grand_total = $scope.sub_total - $scope.total_discount;
                    $scope.remaining = $scope.grand_total - $scope.input_money;
                    $scope.dollar = true;
                    $scope.percent = false;
                }
            };

            $scope.changeUnit = function(params){
                $scope.getTotal();
            };

            $scope.close = function(){
                $scope.disable = false;
                $scope.service_list = {};
                $scope.unit = '';
                $scope.dent_order = '';
                $scope.color = '';
                $scope.edit = '';
            };

            // customer filter
            $scope.customer_list = {};
            $scope.refreshCustomerList = function(customer_list) {
                var params = {name: customer_list, search_in_invoice: 'yes'};
                return Restful.get('api/CustomerList', params).then(function(response) {
                    $scope.CustomerList = response.data.elements;
                });
            };
            var customerId = '';
            $scope.changeService = function(params){
                customerId = params.id;
                $scope.customer_type = params;
                $scope.refreshServiceList();
            };

            $scope.changeCustomer = function(params){
                customerId = params.selected.customer_type_id;
                $scope.refreshServiceList();
            };

            // service filter
            $scope.service_list = {};

            $scope.refreshServiceList = function(service) {
                var params = {name: service, customer_type_id: customerId};
                return Restful.get('api/Service', params).then(function(response) {
                    $scope.serviceList = response.data.elements;
                });
            };
        }
    ]);

app.filter('words', function() {
    function isInteger(x) {
        return x % 1 === 0;
    }


    return function(value) {
        if (value && isInteger(value))
            return  toWords(value);

        return value;
    };

});

var th = ['','thousand','million', 'billion','trillion'];
var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine'];
var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen'];
var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];


function toWords(s)
{
    s = s.toString();
    s = s.replace(/[\, ]/g,'');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i=0; i < x; i++)
    {
        if ((x-i)%3==2)
        {
            if (n[i] == '1')
            {
                str += tn[Number(n[i+1])] + ' ';
                i++;
                sk=1;
            }
            else if (n[i]!=0)
            {
                str += tw[n[i]-2] + ' ';
                sk=1;
            }
        }
        else if (n[i]!=0)
        {
            str += dg[n[i]] +' ';
            if ((x-i)%3==0) str += 'hundred ';
            sk=1;
        }


        if ((x-i)%3==1)
        {
            if (sk) str += th[(x-i-1)/3] + ' ';
            sk=0;
        }
    }
    if (x != s.length)
    {
        var y = s.length;
        str += 'point ';
        for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';
    }
    return str.replace(/\s+/g,' ');
}

window.toWords = toWords;