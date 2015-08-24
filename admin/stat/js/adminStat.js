var app = angular.module('adminStatApp', ['angularCharts' ]);
                          
app.controller('adminStatController', 
    ['$rootScope', '$scope', '$http', function($rootScope, $scope, $http) {


$scope.dataVal = {

series: [''],
data: [
{x: "aa", y: [100]},
{x: "bb", y: [300]},
{x: "cc", y: [351]},
{x: "dd", y: [54]}
]
};


$scope.chartType = 'line';

$scope.config = {
labels: false,
title: "Compteur",
legend: {
display: true,
position: 'left'
},
innerRadius: 0
};

  $scope.change = function() {

/*    $scope.dataVal["data"][$scope.xval].x = "val " + $scope.xval ;
    $scope.dataVal["data"][$scope.xval].y[0] = $scope.yval;

    $scope.$apply();
    $scope.cc = $scope.struc.y[0];
*/  }

  $scope.cc = 20;


  getStats = function() {

//    $scope.dataVal["data"] = [];

    $http.post('../php/getStats.php', { "type" : "weeks"} )
    .success(function(data, status) {

      for (var i = 0; i < data.length; i++) {

        var tmp = [{x:0, y: [0]}];
        tmp['x'] = i;
        tmp['y'] = data[i].total;

/*        $scope.dataVal["data"][i] = "";
        $scope.dataVal["data"][i].y[0] = data[i].total;
*/      }
    }
    )
    .error(function(data, status) {
      var err = data || "Request failed";
  })


  }

  getStats();
/*

  $http.post('../php/getStats.php', { "type" : "weeks"} )
  .success(function(data, status) {

    for (var i = 0, i < data.length; i++) {
      $scope.dataVal["data"][i].x = "";
      $scope.dataVal["data"][i].y[0] = data[i].total;
    }


    $scope.$apply();
  }

  )}

  getStats();
*/

}]);

/*
SELECT DATE(datCreat) AS st_date,
       count(*) AS total
  FROM em_animal
 GROUP BY DATE(datCreat)
 ORDER BY DATE(datCreat)


SELECT FROM_DAYS(TO_DAYS(datCreat) -MOD(TO_DAYS(datCreat) -2, 7)) AS week_beginning,
       count(*) AS total
  FROM em_animal
 GROUP BY FROM_DAYS(TO_DAYS(datCreat) -MOD(TO_DAYS(datCreat) -2, 7))
 ORDER BY FROM_DAYS(TO_DAYS(datCreat) -MOD(TO_DAYS(datCreat) -2, 7))



 */

