app.controller(
    'customer_birthday_ctrl', [
        '$scope'
        , 'Restful'
        , function ($scope, Restful ){
            'use strict';
            var url = 'api/CustomerBirthday/';
            $scope.init = function(params){
                Restful.get(url, params).success(function(data){
                    $scope.totalItems = data.count;
                    $scope.customerList = data;console.log(data);
                });

            };
            $scope.init();

        }
    ]
);