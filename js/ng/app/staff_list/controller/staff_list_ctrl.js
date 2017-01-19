app.controller(
    'staff_list_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/Staff/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.staffList = data;
                    $scope.totalItems = data.count;
                });
                // My customize to get customer type to show.
                Restful.get('api/CustomerType', {status: 'yes'}).success(function(data){
                    $scope.customerType = data;
                });
            };
            $scope.gender = ['Male', 'Female'];
            var params = {pagination: 'yes'};
            $scope.init(params);
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
                $scope.identity_card = $scope.params.identity_card;
                $scope.note = $scope.params.note;
                $scope.sex = $scope.params.sex;
                $scope.address = $scope.params.address;
                $scope.phone = $scope.params.phone;
                $scope.type = $scope.params.type;
                $scope.minor = $scope.params.minor;
                $scope.spouse = $scope.params.spouse;
                $scope.position = $scope.params.position;
                $scope.start_work = $scope.params.start_work;
                $scope.basic_salary = $scope.params.basic_salary;
                $scope.dob = $scope.params.dob;
                $scope.start_contract = $scope.params.start_contract;
                $scope.end_contract = $scope.params.end_contract;
                $scope.id = $scope.params.id;
                $('#staff-list-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {
                    name: $scope.name,
                    note: $scope.note,
                    phone: $scope.phone,
                    identity_card: $scope.identity_card,
                    basic_salary: $scope.basic_salary,
                    type: $scope.type,
                    address: $scope.address,
                    sex: $scope.sex,
                    spouse: $scope.spouse,
                    position: $scope.position,
                    minor: $scope.minor,
                    start_work: $scope.start_work,
                    start_contract: $scope.start_contract,
                    end_contract: $scope.end_contract,
                    dob: $scope.dob
                };
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();console.log(data);
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#staff-list-popup').modal('hide');
                        $scope.clear();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();console.log(data);
                        $('#staff-list-popup').modal('hide');
                        $scope.clear();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.remove = function(id){
                if (confirm('Are you sure you want to delete this staff?')) {
                    Restful.delete(url + id ).success(function(data){console.log(data);
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Delete Success.', 'success');
                        $scope.init();
                    });
                }
            };

            $scope.clear = function(){
                $scope.disable = true;
                $scope.name = '';
                $scope.identity_card = '';
                $scope.basic_salary = '';
                $scope.note = '';
                $scope.sex = '';
                $scope.address = '';
                $scope.phone = '';
                $scope.position = '';
                $scope.dob = '';
                $scope.spouse = '';
                $scope.minor = '';
                $scope.id = '';
                $scope.type = '';
                $scope.start_work = '';
                $scope.start_contract = '';
                $scope.end_contract = '';

            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };
        }
    ]);