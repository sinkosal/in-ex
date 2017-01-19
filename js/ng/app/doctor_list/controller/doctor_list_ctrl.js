app.controller(
    'doctor_list_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/DoctorList/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.doctorList = data;
                    $scope.totalItems = data.count;
                });
                Restful.get('api/DoctorType', {status: 1}).success(function(data){
                    $scope.doctorType = data;
                });
            };
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
                $scope.detail = $scope.params.detail;
                $scope.sex = $scope.params.sex;
                $scope.address = $scope.params.address;
                $scope.phone = $scope.params.phone;
                $scope.doctor_type_id = $scope.params.doctor_type[0].id;
                $scope.id = $scope.params.id;
                $('#doctor-list-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {
                    name: $scope.name,
                    detail: $scope.detail,
                    phone: $scope.phone,
                    doctor_type_id: $scope.doctor_type_id,
                    address: $scope.address,
                    sex: $scope.sex
                };console.log(data);
                $scope.disable = false;
                if($scope.id) {
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#doctor-list-popup').modal('hide');
                        $scope.clear();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#doctor-list-popup').modal('hide');
                        $scope.clear();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Save Success.', 'success');
                        $scope.disable = true;
                    });
                }
            };

            $scope.updateStatus = function(params){
                params.status === 1 ? params.status = 0 : params.status = 1;
                Restful.patch(url + params.id, params ).success(function(data) {console.log(data);
                    $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                });
            };

            $scope.clear = function(){
                $scope.disable = true;
                $scope.name = '';
                $scope.doctor_type_id = '';
                $scope.detail = '';
                $scope.sex = '';
                $scope.address = '';
                $scope.phone = '';
                $scope.id = '';
            };
        }
    ]);