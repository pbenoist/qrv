app.controller('searchController', ['$rootScope', '$scope', '$http', '$routeParams', '$location', 
	function( $rootScope, $scope, $http, $routeParams, $location) {
    
    $scope.processSearch = function()    {

        new_url = "/Show/" + $scope.str_search;
        $location.path(new_url);
    }

    count_tuple = function()    {
        $http.post('php/count_qrcode.php' )
	    .success(function(data, status) {
    	obj = angular.fromJson(data);
//	    $scope.count_qrcode     = obj.count_qrcode;
        $scope.cc1     = obj.cc1;
        $scope.cc2     = obj.cc2;
	})}

	count_tuple();
	
}]);

