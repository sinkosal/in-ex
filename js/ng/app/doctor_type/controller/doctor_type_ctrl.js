app.controller(
    'doctor_type_ctrl', [
        '$scope'
        , 'Restful'
        , 'Services'
        , function ($scope, Restful, Services){
            'use strict';
            $scope.service = new Services();
            var url = 'api/DoctorType/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.doctorType = data;
                    $scope.totalItems = data.count;
                });
            };
            $scope.init();
            /**
             * start functionality pagination
             */
            var params = {};
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
                $scope.id = $scope.params.id;
                $('#doctor-type-popup').modal('show');
            };
            $scope.disable = true;
            $scope.save = function(){
                var data = {name: $scope.name, detail: $scope.detail};
                $scope.disable = false;
                if($scope.id) {console.log($scope.id);
                    Restful.put( url + $scope.id, data).success(function (data) {
                        $scope.init();
                        $scope.service.alertMessage('<strong>Success: </strong>', 'Update Success.', 'success');
                        $('#doctor-type-popup').modal('hide');
                        clear();
                        $scope.disable = true;
                    });
                }else {
                    Restful.save( url , data).success(function (data) {
                        $scope.init();
                        $('#doctor-type-popup').modal('hide');
                        clear();
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

            function clear(){
                $scope.disable = true;
                $scope.name = '';
                $scope.detail = '';
                $scope.id = '';
            };
        }
    ]);