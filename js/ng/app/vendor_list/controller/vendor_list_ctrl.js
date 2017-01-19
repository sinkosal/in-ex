app.controller(
    'vendor_list_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/VendorList/';
            var params = {paginate: 'yes'};
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.vendorList = data;
                    $scope.totalItems = data.count;
                });
                Restful.get('api/VendorType').success(function(data){
                    $scope.supplierType = data;
                });
            };
            $scope.init(params);
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

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {console.log(data);
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };

            $scope.search = function(id){
                params.name = $scope.search_name;
                params.id = $scope.search_id;
                $scope.init(params);console.log(params);
            };

            $scope.edit = function(params){
                $scope.params = angular.copy(params);
                $scope.name = $scope.params.name;
                $scope.supplier_type_id = $scope.params.supplier_type_id;
                $scope.company_name = $scope.params.company_name;
                $scope.tel = $scope.params.tel;
                $scope.contact_name = $scope.params.contact_name;
                $scope.email = $scope.params.email;
                $scope.country = $scope.params.country;
                $scope.address = $scope.params.address;
                $scope.note = $scope.params.note;
                $scope.id = $scope.params.id;
                $('#vendor-list-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {
                    name: $scope.name,
                    supplier_type_id: $scope.supplier_type_id,
                    company_name: $scope.company_name,
                    tel: $scope.tel,
                    contact_name: $scope.contact_name,
                    email: $scope.email,
                    country: $scope.country,
                    address: $scope.address,
                    note: $scope.note
                };
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function(data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#vendor-list-popup').modal('hide');
                        $scope.close();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#vendor-list-popup').modal('hide');
                        $scope.close();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.remove = function(id){
                if (confirm('Are you sure you want to delete this vendor?')) {
                    Restful.delete(url + id ).success(function(data){
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Delete Success.', 'success');
                        $scope.init();
                    });
                }
            };

            $scope.close = function(){
                $scope.disable = true;
                $scope.name = '';
                $scope.supplier_type_id = '';
                $scope.company_name = '';
                $scope.tel = '';
                $scope.contact_name = '';
                $scope.email = '';
                $scope.country = '';
                $scope.address = '';
                $scope.note = '';
                $scope.id = '';
            };
        }
    ]);