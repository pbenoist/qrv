var app = angular.module('appStat', ['chart.js']);

app.controller ('controllerStat', ['$rootScope', '$scope', '$http', function($rootScope, $scope, $http) {
    
  $scope.type = 'Line';
  $scope.labels = [];
  $scope.series = ['Inscriptions'];
  $scope.data = [[]];

  $scope.options = [{name: 'Semaine', value: 0 },{name: 'Mois', value: 1 },{name: 'Année', value: 2 }];
  $scope.selectedItem = $scope.options[0]; 


  $scope.onClick = function (points, evt) {
    console.log(points, evt);
  };

  $scope.changeOption = function () {

    $scope.type = 'Line';
    if ($scope.selectedItem.value == 2)
      $scope.type = 'Bar';

    getStats();
  };

  getStats = function() {

    $scope.labels = [];
    $scope.series = ['Inscriptions'];
    $scope.data = [[]];

    $http.post('../php/getStats.php', { "type" : $scope.selectedItem.value} )
    .success(function(data, status) {

    for (var i = 0; i < data.length; i++) {
  		  $scope.labels[i] = data[i].ref;
	 	    $scope.data[0][i] = data[i].total;
    }

    })
    .error(function(data, status) {
      var err = data || "Request failed";
    })


  }

  getStats();

}]);

