var app = angular.module('adminApp', ['ui.bootstrap']);

app.controller('adminController', ['$rootScope', '$scope', '$http', function($rootScope, $scope, $http) {

    tuple_count         = 0;

    $rootScope.tuple = [];

    $scope.current_limit    = 10;
    $scope.current_pos      = 0;

    $scope.cur_page     = 1;
    $scope.max_page     = 1;

    calcPageInfo = function() {
        $scope.cur_page = ($scope.current_pos / $scope.current_limit) + 1; 
        var pages = tuple_count / $scope.current_limit;
        var pplus = 0;
        if (pages%1 > 0)
            pplus = 1;
        $scope.max_page = parseInt(tuple_count / $scope.current_limit) + pplus; 
    }

    $scope.prevDisabled = function() {
        if ($scope.current_pos == 0)
            return true;
        return false;
    }
    $scope.nextDisabled = function() {
        if ($scope.current_pos + $scope.current_limit >= tuple_count)
            return true;
        return false;
    }

    $scope.alert_prev = function() {
        if ($scope.current_pos == 0)
            return;        
        if ($scope.current_pos >= $scope.current_limit)
            $scope.current_pos -= $scope.current_limit;
        getTuples();
    }
    $scope.alert_next = function() {
        if ($scope.current_pos + $scope.current_limit >= tuple_count)
            return;
        $scope.current_pos += $scope.current_limit;
        getTuples();
    }

    $scope.setCurrentTuple = function(tuple) {
        $rootScope.tuple = tuple;
    }

    $scope.delete_no = function() {
    }

    $scope.delete_yes = function() {
        var id = $rootScope.tuple.id_animal;
        deletetuple(id);
    }


    deletetuple = function(id) {
        $http.post('php/deleteData.php', {'id' : id})
        .success(function(data) 
        {
            count_tuple();
            getTuples();
        })
    }


    $scope.update = function() {
        var p1 = $rootScope.tuple.id_animal;
        var p2 = $rootScope.tuple.nuserie;
        var p3 = $rootScope.tuple.email_proprio;
        var p4 = $rootScope.tuple.rotate_image;
        var p5 = $rootScope.tuple.etat;
        var p6 = $rootScope.tuple.password_proprio;

        $http.post('php/updateData.php', {'p1' : p1, 'p2' : p2, 'p3' : p3, 'p4' : p4, 'p5' : p5, 'p6' : p6  })
        .success(function(data) 
        {
            getTuples();
        })
    }

    $scope.assoc = function() {
        var p1 = $scope.newnuserie;
        var p2 = $scope.newcodepin;

        $http.post('php/assocData.php', {'p1' : p1, 'p2' : p2 })
        .success(function(data) 
        {
            obj = angular.fromJson(data);
            if (obj.ret == 0)
            {
                alert('Insertion effectuée');
                return;
            }
            if (obj.ret > 0)
            {
                alert(obj.mess);
                return;
            }
        
        }
        )
    }


    count_tuple = function()    {
        $http.post('php/count_tuple.php' , {'search' : $scope.str_search} )
        .success(function(data, status) {
            obj = angular.fromJson(data);
            tuple_count = obj.cc;
            $scope.cc   = tuple_count;
            calcPageInfo();
        })
    }



    getTuples = function() {
        $scope.tuples = [];
        $http.post('php/getData.php' , {'search' : $scope.str_search, 'deb' : $scope.current_pos, 'size' : $scope.current_limit} )
        .success(function(data) 
        {
            $scope.tuples = data;
            calcPageInfo();
        });
    }

    $scope.init_search = function()   {
        $scope.current_pos  = 0;
        $scope.str_search   = "";
        $scope.search();
    }

    $scope.search = function()    {
        $scope.current_pos  = 0;
        count_tuple();
        getTuples();
//        calcPageInfo();
    }

/*
    $scope.exportData = function()   {
        $http.post('php/exportData.php' )
        .success(function(data) 
        {
            return data;
        });
    }
*/
    $scope.search();

}]);

app.directive('rotate', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.degrees, function (rotateDegrees) {
                console.log(rotateDegrees);
                var r = 'rotate(' + rotateDegrees + 'deg)';
                element.css({
                    '-moz-transform': r,
                    '-webkit-transform': r,
                    '-o-transform': r,
                    '-ms-transform': r
                });
            });
        }
    }
});
