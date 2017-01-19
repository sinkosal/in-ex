app.controller(
    'journal_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.trans_date = moment().format('YYYY-MM-DD');
            $scope.service = new Services();
            $scope.total_debit = 0;
            $scope.total_credit = 0;
            $scope.vendor_balance = 0;
            $scope.addNew = false;
            function init(params){
                Restful.get('api/Journal', params).success(function(data){
                    $scope.journal = data;
                    $scope.totalItems = data.count;
                });
            };
            var params = {pagination: 'yes'};
            init(params);
            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch('api/Journal/' + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };
            $scope.currentPage = 1;
            $scope.pageChanged = function(){
                $scope.pageSize = 10 * ( $scope.currentPage - 1 );
                params.start = $scope.pageSize;
                init(params);
            };

            $scope.generateTransNo = function(){
                var params_date = {trans_date: $scope.trans_date};
                Restful.get('api/GenerateTransNo', params_date).success(function(data){
                    $scope.trans_no = data.trans_no;
                });
            };
            //$scope.changeTransNo = function(){
            //    params_date.trans_date = $scope.trans_date;
            //    Restful.get('api/GenerateTransNo', params_date).success(function(data){
            //        $scope.trans_no = data.trans_no;
            //    });
            //};

            $scope.generateTransNo();
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
                if($scope.listJournal.length == 0){
                    return $scope.service.alertMessage(
                        'Warning:','No Journal IN List.','warning'
                    );
                }
                if( parseFloat($scope.total_credit.toFixed(2)) != parseFloat($scope.total_debit.toFixed(2)) ){
                    return $scope.service.alertMessage(
                        'Warning:','Credit must be equal Debit.','warning'
                    );
                }
                var data = {
                    journal: {
                        trans_no: $scope.trans_no,
                        trans_date: $scope.trans_date,
                        remarks: $scope.remarks
                    },
                    journal_detail: $scope.listJournal
                };
                $scope.disable = true;
                Restful.save( 'api/Journal' , data).success(function (data) {
                    $scope.generateTransNo();
                    init(params);
                    $scope.service.alertMessage('Success:', 'Save Success.', 'success');
                    $scope.disable = false;
                    clearAll();
                });
            };

            $scope.remove = function($index){
                $scope.listJournal.splice($index, 1);
                $scope.getTotal();
            };
            $scope.listJournal = [];
            $scope.add = function(){
                //if(!angular.isDefined( $scope.reference_no ) ){
                //    return $scope.service.alertMessage(
                //        'warning:','Please Input Reference No.','warning'
                //    );
                //}
                if(!angular.isDefined( $scope.account_list.selected ) ){
                    return $scope.service.alertMessage(
                        'warning:','Please Select Account Code.','warning'
                    );
                }
                var s_id = '', s_name = '';
                if(angular.isDefined( $scope.supplier_list.selected ) ){
                    s_id = $scope.supplier_list.selected.id;
                    s_name = $scope.supplier_list.selected.name;
                }
                $scope.listJournal.push({
                    reference_id: $scope.reference_no,
                    supplier_id: s_id,
                    supplier_name: s_name,
                    account_id: $scope.account_list.selected.account_code,
                    account_type_id: $scope.account_list.selected.detail[0].id,
                    type_of_account_report: $scope.account_list.selected.detail[0].balance_sheet_id,
                    account_chart_id: $scope.account_list.selected.id,
                    account_name: $scope.account_list.selected.name,
                    debit: $scope.debit ? parseFloat($scope.debit) : 0,
                    credit: $scope.credit ? parseFloat($scope.credit) : 0,
                    note: $scope.description,
                    payee_invoice: $scope.payee_invoice,
                    pay_amount: 0
                });
                $scope.getTotal();
                clear();
            };
            function clearAll(){
                $scope.remarks = '';
                $scope.listJournal = [];
                $scope.total_debit = 0;
                $scope.total_credit = 0;
                clear();
            };
            function clear(){
                $scope.reference_no = '';
                $scope.account_list = {};
                $scope.supplier_list = {};
                $scope.description = '';
                $scope.debit = '';
                $scope.credit = '';
                $scope.payee_invoice = '';
                $scope.vendor_balance = 0;
            };
            // functional get total of all journal
            $scope.getTotal = function(){
                $scope.total_debit = 0;
                $scope.total_credit = 0;
                for (var i = 0, l = $scope.listJournal.length; i < l; i++) {
                    var obj = $scope.listJournal[i];
                    $scope.total_debit = parseFloat($scope.total_debit) + ( obj.debit ? parseFloat(obj.debit) : 0 );
                    $scope.total_credit = parseFloat($scope.total_credit) + ( obj.credit ? parseFloat(obj.credit) : 0 );
                }
            };

            // Account filter
            $scope.account_list = {};
            $scope.refreshAccountList = function(account_list) {
                var params = {mix: account_list, search_in_invoice: 'yes'};
                return Restful.get('api/ChartAccount', params).then(function(response) {
                    $scope.accountList = response.data.elements;
                });
            };

            // supplier_list filter
            $scope.supplier_list = {};
            $scope.refreshSupplierList = function(supplier_list) {
                var params = {name: supplier_list};
                return Restful.get('api/VendorList', params).then(function(response) {
                    $scope.supplierList = response.data.elements;
                });
            };

            $scope.getBalanceVendor = function(params){
                $scope.vendor_balance = 0;
                var data = {vendor_id: params.id, balance: 'yes'};
                Restful.get('api/Purchase', data).success(function(data){
                    $scope.vendor = data;
                    for (var i = 0, l = data.elements.length; i < l; i++) {
                        var obj = data.elements[i];
                        $scope.vendor_balance = $scope.vendor_balance + obj.remain;
                    }
                });
            };
        }
    ]);